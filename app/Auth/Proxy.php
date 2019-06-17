<?php

namespace App\Auth;

use Symfony\Bridge\PsrHttpMessage\Factory\DiactorosFactory;
use Symfony\Bridge\PsrHttpMessage\Factory\HttpFoundationFactory;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use GuzzleHttp\Client;
use App\Models\Config;
use App\Models\User;

class Proxy
{
    public function attemptLogin()
    {
        $config = app()->make('config');
        $oauthRequest = app()->make('request');

        $username = $oauthRequest->get('username');
        $password = $oauthRequest->get('password');

        //attach client_id, secret, and grant type to the request
        $oauthRequest->merge([
            'client_id' => $config->get('app.client_id'),
            'client_secret' => $config->get('app.client_secret'),
            'grant_type' => 'password',
            'scope' => '*'
        ]);

        // do extract step of active directory validation
        if ($config->get('auth.ad')) {
            $AD = new ActiveDirectoryController;
            if (!$AD->validateUser($username, $password)) {
                return response()->json('Unauthorized', 401);
            }
        }
        
        //Convert symphony request to psr7 (required by passport)
        $psr7Factory = new DiactorosFactory();
        $psr7Request = $psr7Factory->createRequest($oauthRequest);

        //submit the request directly to the passport issueToken method
        $symphonyResponse = app()
            ->make('Laravel\Passport\Http\Controllers\AccessTokenController')
            ->issueToken($psr7Request);

        //Decode the response.
        $responseContent = json_decode($symphonyResponse->getContent());

        if (is_null($responseContent)) {
            return response()->json('API unable to contact database.', 500);
        }

        if (array_key_exists('access_token', $responseContent)) {
            // since the user has been verified, find their access level
            // and return that as well. (only do this for initial login aka
            // password grant... for refresh grant we have already told
            // them what sort of user they are so skip this)
            try {
                $User = User::where('displayname', $username)->where('deleted', 'F')->firstOrFail();
            } catch (ModelNotFoundException $e) {
                return response('Error locating user', 404);
            }

            try {
                $InactivityTimeout = Config::where('name', 'inactivityTimeoutMins')->firstOrFail()->value;
            } catch (ModelNotFoundException $e) {
                $InactivityTimeout = 0;
            }

            $responseContent = [
                'accessToken' => $responseContent->access_token,
                'refreshToken' => $responseContent->refresh_token,
                'accessTokenExpiration' => $responseContent->expires_in,
                'privilege' => $User->getPrivileges(),
                'displayname' =>$User->displayname,
                'inactivity_timeout' => $InactivityTimeout
            ];
        }

        return response()->json($responseContent);
    }

    public function attemptRefresh()
    {
        $config = app()->make('config');
        $oauthRequest = app()->make('request');

        $oauthRequest->merge([
            'grant_type' => 'refresh_token',
            'client_id' => $config->get('app.client_id'),
            'client_secret' => $config->get('app.client_secret'),
            'scope' => '*'
        ]);

        //Convert symphony request to psr7 (required by passport)
        $psr7Factory = new DiactorosFactory();
        $psr7Request = $psr7Factory->createRequest($oauthRequest);

        //submit the request directly to the passport issueToken method
        $symphonyResponse = app()
            ->make('Laravel\Passport\Http\Controllers\AccessTokenController')
            ->issueToken($psr7Request);
        
        //Decode the response.
        $responseContent = json_decode($symphonyResponse->getContent());

        if ($symphonyResponse->getStatusCode() === 200 && array_key_exists('access_token', $responseContent)) {
            $responseContent = [
                'accessToken' => $responseContent->access_token,
                'accessTokenExpiration' => $responseContent->expires_in,
                'refreshToken' => $responseContent->refresh_token,
            ];
            return response()->json($responseContent);
        } else {
            // the call to issue the accessTokenController failed. This is a
            // a request to the same machine so if it failed this is likely a
            // a bigger configuration issue but it makes most sense to just
            // pass along the failing response.
            return $symphonyResponse;
        }
    }
}
