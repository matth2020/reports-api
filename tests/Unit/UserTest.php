<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Http\Request;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{
    /**
     * Create user.
     * @return user ID
     */
    public function test_user_insert()
    {
        $url = $this->makeUrl('/v1/user');

        $data = [
            'displayname' => 'John Doe',
            'firstname' => 'John',
            'lastname' => 'Doe',
            'title' => 'Dr.',
            'general' => 'Some notes',
            'deleted' => 'F',
            'privilege' => 'Admin',
            'password' => '1234'
        ];

        $response = $this->postJsonTest($url, $data);

        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'data' => [
                    'user' => [
                        'displayname' => 'John Doe',
                        'firstname' => 'John',
                        'lastname' => 'Doe',
                        'title' => 'Dr.',
                        'general' => 'Some notes',
                        'deleted' => 'F',
                        'privilege' => 'Admin'
                    ]
                ]
            ]);

        $arr = $response->json();
        $user = $arr['data']['user'];
        $insertedId = $user['user_id'];

        $this->assertTrue($insertedId !== 0);
        return $insertedId;
    }

    /**
     * Update user.
     * @depends test_user_insert
     * @return void
     */
    public function test_user_update($insertedId)
    {
        $url = $this->makeUrl('/v1/user/{id}', $insertedId);

        $data = [
            'firstname' => 'Test_first_new',
            'lastname' => 'Test_last_new'
        ];

        $response = $this->putJsonTest($url, $data);

        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'data' => [
                    'user' => [
                        'firstname' => 'Test_first_new',
                        'lastname' => 'Test_last_new'
                    ]
                ]
            ]);

        return $insertedId;
    }

    /**
     * Read user.
     * @depends test_user_update
     * @return void
     */
    public function test_user_read($insertedId)
    {
        $url = $this->makeUrl('/v1/user/{id}', $insertedId);

        $response = $this->getJsonTest($url);

        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'data' => [
                    'user' => []
                ]
            ]);
    }

    /**
     * Search user.
     * @depends test_user_insert
     * @return void
     */
    public function test_user_search($insertedId)
    {
        $url = $this->makeUrl('/v1/user/_search');

        $data = [
          'firstname' => 'Test_first_new',
          'lastname' => 'Test_last_new'
        ];

        $response = $this->postJsonTest($url, $data);

        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => 'success'
            ]);

        $arr = $response->json();

        $users = $arr['data']['user'];
        $this->assertTrue(count($users) > 0);
    }

    /**
     * Delete user.
     * @depends test_user_update
     * @return void
     */
    public function test_user_delete($insertedId)
    {
        $url = $this->makeUrl('/v1/user/{id}', $insertedId);

        $response = $this->deleteJsonTest($url, []);

        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'data' => [
                    'user' => [
                        'user_id' => $insertedId,
                        'deleted' => 'T'
                    ]
                ]
            ]);
    }
}
