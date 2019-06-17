<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Http\Request;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProviderTest extends TestCase
{
    /**
     * Create provider.
     * @return provider ID
     */
    public function test_provider_insert()
    {
        $url = $this->makeUrl('/v1/provider');

        $data = [
          'first' => 'Test_first',
          'last' => 'Test_last',
          'mi' => 'Test_mi',
          'suffix' => 'Jr',
          'display_name' => 'Test first last',
          'display_name_short' => 'John S',
          'phone' => '+1-555-555-5555',
          'fax' => '+1-555-555-5556',
          'email' => 'johndoe@someplace.com',
          'addr1' => '9954 SW Arctic Ave.',
          'addr2' => '',
          'city' => 'Beaverton',
          'state' => 'Oregon',
          'zip' => '97005',
          'country' => 'USA',
          'province' => '',
          'provider_notes' => 'test notes',
          'rate' => '10',
          'face_image' => 'John_Smith.jpg',
          'license_number' => 'test_license',
          'provider_number' => 'test_prov_number',
          'dea_number' => 'test_dea',
          'contact_name' => 'Jane R',
          'contact_phone' => '+1-555-555-5557',
          'account' => 'test_account',
          'emr_code' => 'test_emr',
          'general' => '',
          'non_xtract_provider' => 'F'
        ];

        $response = $this->postJsonTest($url, $data);

        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'data' => [
                    'provider' => [
                    'first' => 'Test_first',
                    'last' => 'Test_last',
                    'mi' => 'Test_mi',
                    'suffix' => 'Jr',
                    'display_name' => 'Test first last',
                    'display_name_short' => 'John S',
                    'phone' => '+15555555555',
                    'fax' => '+15555555556',
                    'email' => 'johndoe@someplace.com',
                    'addr1' => '9954 SW Arctic Ave.',
                    'city' => 'Beaverton',
                    'state' => 'Oregon',
                    'zip' => '97005',
                    'country' => 'USA',
                    'provider_notes' => 'test notes',
                    'rate' => '10',
                    'face_image' => 'John_Smith.jpg',
                    'license_number' => 'test_license',
                    'provider_number' => 'test_prov_number',
                    'dea_number' => 'test_dea',
                    'contact_name' => 'Jane R',
                    'contact_phone' => '+15555555557',
                    'account' => 'test_account',
                    'emr_code' => 'test_emr',
                    'non_xtract_provider' => 'F'
                    ]
                ]
            ]);

        $arr = $response->json();
        $provider = $arr['data']['provider'];
        $insertedId = $provider['provider_id'];

        $this->assertTrue($insertedId !== 0);
        return $insertedId;
    }

    /**
     * Update provider.
     * @depends test_provider_insert
     * @return void
     */
    public function test_provider_update($insertedId)
    {
        $url = $this->makeUrl('/v1/provider/{id}', $insertedId);

        $data = [
            'first' => 'Test_first_new',
            'last' => 'Test_last_new'
        ];

        $response = $this->putJsonTest($url, $data);

        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'data' => [
                    'provider' => [
                        'first' => 'Test_first_new',
                        'last' => 'Test_last_new'
                    ]
                ]
            ]);

        return $insertedId;
    }

    /**
     * Read provider.
     * @depends test_provider_update
     * @return void
     */
    public function test_provider_read($insertedId)
    {
        $url = $this->makeUrl('/v1/provider/{id}?fields=provider_id,first,last,suffix,addr1,city,state,zip,phone,fax,provider_notes,rate,deleted,display_name,display_name_short,license_number,provider_number,dea_number,contact_name,contact_phone,emr_code,non_xtract_provider', $insertedId);

        $response = $this->getJsonTest($url);

        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'data' => [
                    'provider' => []
                ]
            ]);

        $arr = $response->json();
        $provider = $arr['data']['provider'];
        $this->assertTrue($provider['license_number'] == 'test_license');
    }

    /**
     * Read all providers.
     * @depends test_provider_insert
     * @return void
     */
    public function test_provider_get_all()
    {
        $url = $this->makeUrl('/v1/provider');

        $response = $this->getJsonTest($url);

        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'data' => [
                    'provider' => [[],[],[
                        'first' => 'Test_first_new'
                    ]]
                ]
            ]);
    }

    /**
     * Search provider.
     * @depends test_provider_insert
     * @return void
     */
    public function test_provider_search($insertedId)
    {
        $url = $this->makeUrl('/v1/provider/_search');

        $data = [
          'first' => 'Test_first_new',
          'last' => 'Test_last_new'
        ];

        $response = $this->postJsonTest($url, $data);

        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => 'success'
            ]);

        $arr = $response->json();

        $providers = $arr['data']['provider'];
        $this->assertTrue(count($providers) > 0);
    }

    /**
     * Delete provider.
     * @depends test_provider_update
     * @return void
     */
    public function test_provider_delete($insertedId)
    {
        $url = $this->makeUrl('/v1/provider/{id}', $insertedId);

        $response = $this->deleteJsonTest($url, []);

        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'data' => [
                    'provider' => [
                        'provider_id' => $insertedId,
                        'deleted' => 'T'
                    ]
                ]
            ]);

        // verify that the provider is no longer seen in searches

        $url = $this->makeUrl('/v1/provider');

        $response = $this->getJsonTest($url);

        $response
            ->assertStatus(200)
            ->assertJsonMissing([
                'first' => 'Test_first_new'
            ]);

        $url = $this->makeUrl('/v1/provider/_search');

        $data = [
            'first' => 'Test_first_new'
        ];

        $response = $this->postJsonTest($url, $data);

        $response
            ->assertStatus(200)
            ->assertJsonMissing([
                'first' => 'Test_first_new'
            ]);

        // verify that we can still get it by asking directly for it

        $url = $this->makeUrl('/v1/provider/{id}', $insertedId);

        $response = $this->getJsonTest($url);

        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'data' => [
                    'provider' => [
                        'first' => 'Test_first_new',
                        'deleted' => 'T'
                    ]
                ]
            ]);

    }
}
