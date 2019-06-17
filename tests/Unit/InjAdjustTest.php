<?php
/**
 * Created by PhpStorm.
 * User: Martin
 * Date: 2/1/2018
 * Time: 9:56 PM
 */

namespace Tests\Unit;

use Tests\TestCase;

class InjAdjustTest extends TestCase
{
    /**
     * Create injection adjustment.
     */
    public function test_injadjust_insert()
    {
        // delete all the injection adjustments we find - clean slate

        $url = $this->makeUrl('/v1/patient/{patient_id}/injection_adjust');

        $response = $this->getJsonTest($url);

        $response->assertStatus(200);

        foreach ($response->json()['data']['injection_adjust'] as $key => $injectionAdjustment) {
            $url = $this->makeUrl('/v1/patient/{patient_id}/injection_adjust/{id}', $injectionAdjustment['injection_adjust_id']);
            $response = $this->deleteJsonTest($url, []);
            $response->assertStatus(200);
        }

        $url = $this->makeUrl('/v1/patient/{patient_id}/prescription/{prescription_id}/injection_adjust');

        $data = [
            'dose' => '0.050',
            'dilution' => '200',
            'date' => date("Y-m-d"),
            'reason' => 'injadjust_insert'
        ];

        $response = $this->postJsonTest($url, $data);

        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'data' => [
                    'injection_adjust' => $data
                ]
            ]);

        $arr = $response->json();
        $injection_adjust = $arr['data']['injection_adjust'];
        $insertedId = $injection_adjust['injection_adjust_id'];

        $this->assertTrue($insertedId !== 0);
        return $insertedId;
    }

    /**
     * Read all injection adjustments for patient.
     * @depends test_injadjust_insert
     */
    public function test_injadjust_read_all_by_patient($insertedId)
    {
        $url = $this->makeUrl('/v1/patient/{patient_id}/injection_adjust');

        $response = $this->getJsonTest($url);

        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'data' => [
                    'injection_adjust' => [
                        [
                            'injection_adjust_id' => $insertedId,
                            'reason' => 'injadjust_insert'
                        ]
                    ]
                ]
            ]);
    }

    /**
     * Read all injection adjustments for patient particular prescription.
     * @depends test_injadjust_insert
     */
    public function test_injadjust_read_all_by_prescription($insertedId)
    {
        $url = $this->makeUrl('/v1/patient/{patient_id}/prescription/{prescription_id}/injection_adjust');

        $response = $this->getJsonTest($url);

        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'data' => [
                    'injection_adjust' => [
                        [
                            'injection_adjust_id' => $insertedId,
                            'reason' => 'injadjust_insert'
                        ]
                    ]
                ]
            ]);
    }

    /**
     * Read injection adjustment by id.
     * @depends test_injadjust_insert
     */
    public function test_injadjust_read_by_id($insertedId)
    {
        $url = $this->makeUrl('/v1/patient/{patient_id}/injection_adjust/{id}', $insertedId);

        $response = $this->getJsonTest($url);

        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'data' => [
                    'injection_adjust' => [
                        'injection_adjust_id' => $insertedId,
                        'reason' => 'injadjust_insert'
                    ]
                ]
            ]);
    }

    /**
     * Read injection adjustment given prescription.
     * @depends test_injadjust_insert
     */
    public function test_injadjust_read_by_prescription($insertedId)
    {
        $url = $this->makeUrl('/v1/patient/{patient_id}/prescription/{prescription_id}/injection_adjust/{id}', $insertedId);

        $response = $this->getJsonTest($url);

        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'data' => [
                    'injection_adjust' => [
                        'injection_adjust_id' => $insertedId,
                        'reason' => 'injadjust_insert'
                    ]
                ]
            ]);
    }

    /**
     * Search injection adjustment.
     * @depends test_injadjust_insert
     */
    public function test_injadjust_search($insertedId)
    {
        $url = $this->makeUrl('/v1/patient/{patient_id}/injection_adjust/_search');

        $data = [
            'dilution' => '200',
            'reason' => 'injadjust_insert'
        ];

        $response = $this->postJsonTest($url, $data);

        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'data' => [
                    'injection_adjust' => [
                        [
                            'injection_adjust_id' => $insertedId,
                            'reason' => 'injadjust_insert'
                        ]
                    ]
                ]
            ]);
    }

    /**
     * Search injection adjustment for non existent.
     * @depends test_injadjust_insert
     */
    public function test_injadjust_search_not_found($insertedId)
    {
        $url = $this->makeUrl('/v1/patient/{patient_id}/injection_adjust/_search');

        $data = [
            'dilution' => '200',
            'reason' => 'injadjust_create_not_here'
        ];

        $response = $this->postJsonTest($url, $data);

        $response
            ->assertStatus(200);

        $arr = $response->json();
        $injection_adjust = $arr['data']['injection_adjust'];
        $this->assertTrue(count($injection_adjust) == 0);
    }

    /**
     * Search injection adjustment within prescription.
     * @depends test_injadjust_insert
     */
    public function test_injadjust_search_with_prescription($insertedId)
    {
        $url = $this->makeUrl('/v1/patient/{patient_id}/injection_adjust/_search');

        $data = [
            'dilution' => '200',
            'reason' => 'injadjust_insert'
        ];

        $response = $this->postJsonTest($url, $data);

        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'data' => [
                    'injection_adjust' => [
                        [
                            'injection_adjust_id' => $insertedId,
                            'reason' => 'injadjust_insert'
                        ]
                    ]
                ]
            ]);
    }

    /**
     * Update injection adjustment - fail with out of plan dose.
     * @depends test_injadjust_insert
     */
    public function test_injadjust_update_fail_dose($insertedId)
    {
        $url = $this->makeUrl('/v1/patient/{patient_id}/injection_adjust/{id}', $insertedId);

        $data = [
            'dose' => '0.550',
            'date' => '2017-08-21',
            'reason' => 'injadjust_update_fail_dose'
        ];

        $response = $this->putJsonTest($url, $data, 'validation');

        $response
            ->assertStatus(400)
            ->assertJson([
                'status' => 'validation',
                'errors' => [
                    'dose' => [
                        'The dose provided is outside of the treatment plan.'
                    ]
                ]
            ]);
    }

    /**
     * Update injection adjustment - fail with out of plan dilution.
     * @depends test_injadjust_insert
     */
    public function test_injadjust_update_fail_dilution($insertedId)
    {
        $url = $this->makeUrl('/v1/patient/{patient_id}/injection_adjust/{id}', $insertedId);

        $data = [
            'dilution' => '201',
            'date' => '2017-08-22',
            'reason' => 'injadjust_update_fail_dilution'
        ];

        $response = $this->putJsonTest($url, $data, 'validation');

        $response
            ->assertStatus(400)
            ->assertJson([
                'status' => 'validation',
                'errors' => [
                    'dilution' => [
                        'The dilution provided is outside of the treatment plan.'
                    ]
                ]
            ]);
    }

    /**
     * Update injection adjustment.
     * @depends test_injadjust_insert
     */
    public function test_injadjust_update($insertedId)
    {
        $url = $this->makeUrl('/v1/patient/{patient_id}/injection_adjust/{id}', $insertedId);

        $data = [
            'dose' => '0.200',
            'dilution' => '10',
            'date' => '2017-08-23',
            'reason' => 'injadjust_update'
        ];

        $response = $this->putJsonTest($url, $data);

        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'data' => [
                    'injection_adjust' => [
                        'injection_adjust_id' => $insertedId,
                        'reason' => 'injadjust_update'
                    ]
                ]
            ]);
    }

    /**
     * Update injection adjustment within prescription.
     * @depends test_injadjust_insert
     */
    public function test_injadjust_update_with_prescription($insertedId)
    {
        $url = $this->makeUrl('/v1/patient/{patient_id}/prescription/{prescription_id}/injection_adjust/{id}', $insertedId);

        $data = [
            'dose' => '0.150',
            'dilution' => '100',
            'date' => '2017-08-24',
            'reason' => 'injadjust_update_with_prescription'
        ];

        $response = $this->putJsonTest($url, $data);

        $response
            ->assertStatus(200)
            ->assertJson([ //Should fail validation since dilution 300 is outside of tp
                'status' => 'success',
                'data' => [
                    'injection_adjust' => [
                        'injection_adjust_id' => $insertedId,
                        'reason' => 'injadjust_update_with_prescription'
                    ]
                ]
            ]);
    }

    /**
     * Delete injection adjustments for a patient.
     * @depends test_injadjust_insert
     */
    public function test_injadjust_delete($insertedId)
    {
        $url = $this->makeUrl('/v1/patient/{patient_id}/injection_adjust/{id}', $insertedId);

        $response = $this->deleteJsonTest($url, []);

        $response
            ->assertStatus(200)
            ->assertJson([
                'data' => [
                    'injection_adjust' => [
                        'deleted' => 'T'
                    ]
                ]
            ]);

        //verify it's gone
        // 3/3 we dont do this anymore since we only mark deleted=T now

        // $response = $this->getJsonTest($url, 'fail');

        // $response
        //     ->assertStatus(404);
    }

    /**
     * Delete injection for a patient + prescription.
     */
    public function test_injadjust_delete_by_prescription()
    {
        $url = $this->makeUrl('/v1/patient/{patient_id}/prescription/{prescription_id}/injection_adjust');

        $data = [
            'dose' => '0.100',
            'dilution' => '200',
            'date' => date("Y-m-d"),
            'reason' => 'injadjust_delete_by_prescription'
        ];

        $response = $this->postJsonTest($url, $data);

        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'data' => [
                    'injection_adjust' => $data
                ]
            ]);

        $arr = $response->json();
        $dosing_plan = $arr['data']['injection_adjust'];
        $insertedId = $dosing_plan['injection_adjust_id'];

        $this->assertTrue($insertedId !== 0);

        // now delete it

        $url .= '/' . $insertedId;

        $response = $this->deleteJsonTest($url, []);

        $response
            ->assertStatus(200)
            ->assertJson([
                'data' => [
                    'injection_adjust' => [
                        'deleted' => 'T'
                    ]
                ]
            ]);

        //verify it's gone
        // 3/3 we don't do this anymore since we only mark deleted=T now

        // $response = $this->getJsonTest($url, 'fail');

        // $response
        //     ->assertStatus(404);
    }
}
