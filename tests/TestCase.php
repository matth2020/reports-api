<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

$access_token = '';

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    public static $patient_id = 2;
    public static $prescription_id = 3;
    public static $set_order_id = 1;

    protected function trace($method, $url, $data, $response)
    {
        $frame = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 3)[2];
        $caller = $frame['function'];
        $dataStr = $data ? ('Data => ' . substr(print_r($data, true), 5)) : null;
        $responseStr = 'Response => ' . substr(print_r($response->json(), true), 5);
        echo "\n{$caller} ({$method} {$url}):\n{$dataStr}{$responseStr}";
    }

    protected function traceOne()
    {
        $frame = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 3)[2];
        $caller = $frame['function'];
        //echo "\n{$caller} ";
    }

    public function makeUrl($pattern, $id = null)
    {
        $filled = str_replace('{patient_id}', TestCase::$patient_id, $pattern);
        $filled = str_replace('{prescription_id}', TestCase::$prescription_id, $filled);
        $filled = str_replace('{set_order_id}', TestCase::$set_order_id, $filled);

        if (!is_null($id)) {
            $filled = str_replace('{id}', $id, $filled);
        }

        return $filled;
    }

    protected function getAuth($username = 'xtract admin', $password = '9495')
    {
        global $access_token;

        if ($access_token == '') {
            // Setup authentication
            $url_auth = '/oauth/token';
            $grant_type = 'password';
            $client_id = '2';
            //static $client_secret = '4fhvhEGt000xB89ibIAJqSMMxGLTIl5K99ET4dBe';
            $client_secret = env('OAUTH_CLIENT_SECRET', 'mysql');

            $data = array(
                'grant_type' => $grant_type,
                'client_id' => $client_id,
                'client_secret' => $client_secret,
                'username' => $username,
                'password' => $password
            );

            $response_auth = $this->post($url_auth, $data);
            $response_auth
                ->assertStatus(200)
                ->assertJson([
                    'token_type' => 'Bearer'
                ]);
            $get_auth = $response_auth->json();
            $access_token = $get_auth['token_type'] . " " . $get_auth['access_token'];
        }

        return $access_token;
    }

    public function getJsonTest($url, $status = 'success')
    {
        $headers = [
            'Authorization' => $this::getAuth()
        ];
        $this->traceOne();

        $response = $this->getJson($url, $headers);

        if (!array_key_exists('status', $response->json()) || $response->json()['status'] != $status) {
            $this->trace('GET', $url, null, $response);
        }

        return $response;
    }

    public function putJsonTest($url, $data, $status = 'success')
    {
        $headers = [
            'Authorization' => $this::getAuth()
        ];
        $this->traceOne();

        $response = $this->putJson($url, $data, $headers);

        if (!array_key_exists('status', $response->json()) || $response->json()['status'] != $status) {
            $this->trace('PUT', $url, $data, $response);
        }

        return $response;
    }

    public function postJsonTest($url, $data, $status = 'success')
    {
        $headers = [
            'Authorization' => $this::getAuth()
        ];
        $this->traceOne();

        $response = $this->postJson($url, $data, $headers);

        if (!array_key_exists('status', $response->json()) || $response->json()['status'] != $status) {
            $this->trace('POST', $url, $data, $response);
        }

        return $response;
    }

    public function deleteJsonTest($url, $data, $status = 'success')
    {
        $headers = [
            'Authorization' => $this::getAuth()
        ];
        $this->traceOne();

        $response = $this->deleteJson($url, $data, $headers);

        if (!array_key_exists('status', $response->json()) || $response->json()['status'] != $status) {
            $this->trace('DELETE', $url, $data, $response);
        }

        return $response;
    }
}
