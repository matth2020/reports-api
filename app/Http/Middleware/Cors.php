<?php
namespace App\Http\Middleware;

use Closure;
use Log;

class Cors
{

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // disable cors if in test environment
        if (env('APP_ENV') === 'testing') {
            return $next($request);
        }

        $config = app()->make('config');
        $AllowedOrigins = $config['cors.allowedOrigins'];
        $AllowedMethods = $config['cors.allowedMethods'];
        $AllowedHeaders = $config['cors.allowedHeaders'];
        $MaxAge = $config['cors.maxAge'];
        $SupportCredientials = $config['cors.supportsCredentials'];
        $ExposeHeaders = $config['cors.exposedHeaders'];

        $origin = $request->header('origin');

        if (in_array($origin, $AllowedOrigins) || in_array('*', $AllowedOrigins) || $origin == null) { //Null origin === same origin
            header("Access-Control-Allow-Origin: " . $origin);
            // ALLOW OPTIONS METHOD
            $headers = [
                'Access-Control-Allow-Methods'=> implode(',', $AllowedMethods),
                'Access-Control-Allow-Headers'=> implode(',', $AllowedHeaders),
                'Cache-Control' => 'max-age='.$MaxAge,
                'Access-Control-Expose-Headers' => implode(',', $ExposeHeaders)
            ];

            if ($SupportCredientials) {
                array_push($headers, ['Access-Control-Allow-Credentials']);
            }

            if ($request->getMethod() == "OPTIONS") {
                // The client-side application can set only headers allowed in Access-Control-Allow-Headers
                return Response('OK', 200, $headers);
            }

            $response = $next($request);

            foreach ($headers as $key => $value) {
                $response->headers->set($key, $value);
            }

            return $response;
        } else {
            Log::info('Blocked cors request from origin:' . $origin);
            return Response('Not allowed', 401);
        }
    }
}
