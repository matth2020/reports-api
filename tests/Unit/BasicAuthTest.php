<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BasicAuthTest extends TestCase
{
    /**
     * A basic user test.
     * by Joe Petsche - Dec 2017
     * @return void
     */
    public function testBasicAdminUserExists()
    {
        // For log in via LabView application
        $this->assertDatabaseHas('user', [
            'displayname' => 'Xtract Admin'
        ]);

        // For log in via Reporting Tool (and potentially others)
        $this->assertDatabaseHas('user', [
            'email' => 'xps@xtractsolutions.com'
        ]);
    }

    public function testOauthSetup()
    {
        // Testing oauth key exists
        $this->assertDatabaseHas('oauth_clients', [
            'id' => '2',
            'name' => 'Xtract Solutions Password Grant',
            'password_client' => '1',
            'personal_access_client' => '0'
        ]);

        // Setup authentication
        static $url_auth = '/oauth/token';

        $data = array(
            'grant_type'=>'password',
            'client_id'=>'2',
            'client_secret'=>'unknown',
            'username'=>'unknown',
            'password'=>'unknown'
        );
        $response_auth = $this->post($url_auth, $data);
        $response_auth
            ->assertStatus(401);
        /*
                    ->assertJson([
                        // many possibilities:
                        'error' => 'invalid_credentials'
                        'error' => 'invalid_client'
                    ]);
        */
    }
}
