<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Http\Request;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ClinicTest extends TestCase
{
    protected $newData = [
        'name' => 'Test_clinic_new',
        'name2' => 'Test clinic name 2 new',
        'abbreviation' => 'TC_new',
        'contact' => 'Test Tester new',
        'addr1' => '9954 SW Arctic Avenew.',
        'city' => 'Beavertonew',
        'state' => 'Oregonew',
        'zip' => '97007',
        'country' => 'USAnew',
        'phone' => '+15033790111',
        'fax' => '+15037151379',
        'non_xtract_clinic' => 'T'
    ];

    /**
     * Create clinic.
     * @return clinic ID
     */
    public function test_clinic_insert()
    {
        $url = $this->makeUrl('/v1/clinic');

        $data = [
            'name' => 'Test_clinic',
            'name2' => 'Test clinic name 2',
            'abbreviation' => 'TC',
            'contact' => 'Test Tester',
            'addr1' => '9954 SW Arctic Ave.',
            'city' => 'Beaverton',
            'state' => 'Oregon',
            'zip' => '97005',
            'country' => 'USA',
            'phone' => '+15033790110',
            'fax' => '+15037151378',
            'non_xtract_clinic' => 'F'
        ];

        $response = $this->postJsonTest($url, $data);

        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'data' => [
                    'clinic' => $data
                ]
            ]);

        $arr = $response->json();
        $clinic = $arr['data']['clinic'];
        $insertedId = $clinic['clinic_id'];

        $this->assertTrue($insertedId !== 0);
        return $insertedId;
    }

    /**
     * Update clinic.
     * @depends test_clinic_insert
     * @return void
     */
    public function test_clinic_update($insertedId)
    {
        $url = $this->makeUrl('/v1/clinic/{id}', $insertedId);

        $response = $this->putJsonTest($url, $this->newData);

        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'data' => [
                    'clinic' => array_merge($this->newData, ['clinic_id' => $insertedId])
                ]
            ]);

        return $insertedId;
    }

    /**
     * Read clinic.
     * @depends test_clinic_update
     * @return void
     */
    public function test_clinic_read($insertedId)
    {
        $url = $this->makeUrl('/v1/clinic/{id}?fields=clinic_id,name,name2,abbreviation,contact,addr1,city,state,zip,country,phone,fax,deleted,non_xtract_clinic', $insertedId);

        $response = $this->getJsonTest($url);

        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'data' => [
                    'clinic' => array_merge($this->newData, ['clinic_id' => $insertedId])
                ]
            ]);
    }

    /**
     * Read all clinics.
     * @depends test_clinic_insert
     * @return void
     */
    public function test_clinic_get_all()
    {
        $url = $this->makeUrl('/v1/clinic');

        $response = $this->getJsonTest($url);

        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'data' => [
                    'clinic' => [[],[
                        'name' => 'Test_clinic_new'
                    ]]
                ]
            ]);
    }

    /**
     * Search clinic.
     * @depends test_clinic_insert
     * @return void
     */
    public function test_clinic_search()
    {
        $url = $this->makeUrl('/v1/clinic/_search');

        $data = [
            'name' => 'Test_clinic_new',
            'name2' => 'Test clinic name 2 new'
        ];

        $response = $this->postJsonTest($url, $data);

        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => 'success'
            ]);

        $arr = $response->json();

        $clinics = $arr['data']['clinic'];
        $this->assertTrue(count($clinics) > 0);
    }

    /**
     * Delete clinic.
     * @depends test_clinic_update
     * @return void
     */
    public function test_clinic_delete($insertedId)
    {
        $url = $this->makeUrl('/v1/clinic/{id}', $insertedId);

        $response = $this->deleteJsonTest($url, []);

        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'data' => [
                    'clinic' => array_merge($this->newData, ['clinic_id' => $insertedId, 'deleted' => 'T'])
                ]
            ]);

        // verify that it no longer shows up in searches

        $url = $this->makeUrl('/v1/clinic');

        $response = $this->getJsonTest($url);

        $response
            ->assertStatus(200)
            ->assertJsonMissing([
                'name' => 'Test_clinic_new'
            ]);

        $url = $this->makeUrl('/v1/clinic/_search');

        $data = [
            'name' => 'Test_clinic_new'
        ];

        $response = $this->postJsonTest($url, $data);

        $response
            ->assertStatus(200)
            ->assertJsonMissing([
                'name' => 'Test_clinic_new'
            ]);

        // verify that we can still get it by asking directly for it

        $url = $this->makeUrl('/v1/clinic/{id}', $insertedId);

        $response = $this->getJsonTest($url);

        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'data' => [
                    'clinic' => [
                        'name' => 'Test_clinic_new',
                        'deleted' => 'T'
                    ]
                ]
            ]);
    }
}
