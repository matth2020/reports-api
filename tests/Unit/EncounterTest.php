<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Http\Request;
use Illuminate\Foundation\Testing\RefreshDatabase;

class EncounterTest extends TestCase
{
    /**
     * Create an encounter with invalid clinic.
     */
    public function test_encounter_bad_create_clinic()
    {
        $url = $this->makeUrl('/v1/patient/{patient_id}/encounter');

        $data = [
            'clinic_id' => '1'
        ];

        // insert an encounter

        $response = $this->postJsonTest($url, $data, 'validation');

        $response
            ->assertStatus(400)
            ->assertJson([
                'status' => 'validation',
                'errors' => [
                    'clinic_id' => [
                        '0' => 'The selected clinic id is invalid.'
                    ]
                ]
            ]);
    }

    /**
     * Create encounter.
     * @return encounter ID
     */
    public function test_encounter_insert()
    {
        $url = $this->makeUrl('/v1/patient/{patient_id}/encounter');

        $data = [
            'clinic_id' => '2'
        ];

        // insert a encounter

        $response = $this->postJsonTest($url, $data);

        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'data' => [
                    'encounter' => [
                        'clinic_id' => '2',
                        'patient_id' => '2',
                        'state' => 'waiting_for_injection'
                    ]
                ]
            ]);

        $arr = $response->json();
        $encounter = $arr['data']['encounter'];
        $insertedId = $encounter['encounter_id'];

        $this->assertTrue($insertedId !== 0);
        return $insertedId;
    }

    /**
     * Create encounter when one is already in progress.
     */
    public function test_encounter_bad_create_existing()
    {
        $url = $this->makeUrl('/v1/patient/{patient_id}/encounter');

        $data = [
        ];

        // insert an encounter

        $response = $this->postJsonTest($url, $data, 'validation');

        $response
            ->assertStatus(400)
            ->assertJson([
                'status' => 'validation',
                'errors' => []
            ]);

        $arr = $response->json();
        $this->assertTrue(array_key_exists('exists', $arr['errors']));
    }

    /**
     * Update encounter.
     * @depends test_encounter_insert
     * @return void
     */
    public function test_encounter_update($insertedId)
    {
        $url = $this->makeUrl('/v1/patient/{patient_id}/encounter/{id}', $insertedId);

        $data = [
            'state' => 'with_injection_staff',
            'excuse_time' => '2018-01-18 13:37:20',
            'last_departure_attempt' => '2018-01-18 13:37:23'
        ];

        // update the encounter

        $response = $this->putJsonTest($url, $data);

        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'data' => [
                    'encounter' => [
                        'state' => 'with_injection_staff',
                        'excuse_time' => '2018-01-18 13:37:20',
                        'last_departure_attempt' => '2018-01-18 13:37:23'
                    ]
                ]
            ]);

        return $insertedId;
    }

    /**
     * Read encounter
     * @depends test_encounter_update
     * @return void
     */
    public function test_encounter_read($insertedId)
    {
        $url = $this->makeUrl('/v1/patient/{patient_id}/encounter/{id}?fields=name,patient_id,state,clinic_id,encounter_id,login_time', $insertedId);

        // read the encounter

        $response = $this->getJsonTest($url);

        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'data' => [
                    'encounter' => [
                        'state' => 'with_injection_staff'
                    ]
                ]
            ]);

        // $arr = $response->json();
    }

    /**
     * Read all encounters
     * @depends test_encounter_update
     * @return void
     */
    public function test_encounter_read_all()
    {
        $url = $this->makeUrl('/v1/patient/{patient_id}/encounter');

        $response = $this->getJsonTest($url);

        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => 'success'
            ]);

        $arr = $response->json();

        $encounters = $arr['data']['encounter'];
        $this->assertTrue(count($encounters) > 0);
    }

    /**
     * Delete encounter.
     * @depends test_encounter_update
     * @return void
     */
    public function test_encounter_delete($insertedId)
    {
        $url = $this->makeUrl('/v1/patient/{patient_id}/encounter/{id}', $insertedId);

        // delete the encounter

        $response = $this->deleteJsonTest($url, []);

        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'data' => [
                    'encounter' => [
                        'encounter_id' => $insertedId
                    ]
                ]
            ]);

        // verify item is marked as deleted
        $url = $this->makeUrl('/v1/patient/{patient_id}/encounter/{id}', $insertedId);

        $response = $this->getJsonTest($url);

        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'data' => [
                    'encounter' => [
                        'state' => 'logged_out'
                    ]
                ]
            ]);

        // $arr = $response->json();
    }
}
