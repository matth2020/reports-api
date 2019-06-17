<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Http\Request;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TrackingValueTest extends TestCase
{
    /**
     * Create trackingValue.
     * @return trackingValue ID
     */
    public function test_trackingValue_insert()
    {
        $url = $this->makeUrl('/v1/patient/{patient_id}/tracking_value');

        $data = [
            'tracking_name' => 'peak flow',
            'value' => '0.20'
        ];

        $response = $this->postJsonTest($url, $data);

        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'data' => [
                    'tracking_value' => [
                        'tracking_name' => 'peak flow',
                        'value' => '0.20'
                    ]
                ]
            ]);

        $arr = $response->json();
        $trackingValue = $arr['data']['tracking_value'];
        $insertedId = $trackingValue['tracking_value_id'];

        $this->assertTrue($insertedId !== 0);
        return $insertedId;
    }

    /**
     * Update trackingValue.
     * @depends test_trackingValue_insert
     * @return void
     */
    public function test_trackingValue_update($insertedId)
    {
        $url = $this->makeUrl('/v1/patient/2/tracking_value/{id}', $insertedId);

        $data = [
            'tracking_name' => 'peak flow',
            'value' => '0.30'
        ];

        $response = $this->putJsonTest($url, $data);

        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'data' => [
                    'tracking_value' => [
                        'tracking_name' => 'peak flow',
                        'value' => '0.30'
                    ]
                ]
            ]);

        return $insertedId;
    }

    /**
     * Read trackingValue test.
     * @depends test_trackingValue_update
     * @return void
     */
    public function test_trackingValue_read($insertedId)
    {
        $url = $this->makeUrl('/v1/patient/2/tracking_value/{id}', $insertedId);

        $response = $this->getJsonTest($url);

        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'data' => [
                    'tracking_value' => [
                        'tracking_name' => 'peak flow',
                        'value' => '0.30'
                    ]
                ]
            ]);

        // $arr = $response->json();
    }

    /**
     * Read trackingValues for patient test.
     * @depends test_trackingValue_update
     * @return void
     */
    public function test_trackingValue_read_patient($insertedId)
    {
        $url = $this->makeUrl('/v1/patient/{patient_id}/tracking_value');

        $response = $this->getJsonTest($url);

        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'data' => [
                    'tracking_value' => [[
                        'tracking_name' => 'peak flow',
                        'value' => '0.30'
                    ]]
                ]
            ]);

        // $arr = $response->json();
    }

    /**
     * Search trackingValue test.
     * @depends test_trackingValue_insert
     * @return void
     */
    public function test_trackingValue_search($insertedId)
    {
        $url = $this->makeUrl('/v1/patient/{patient_id}/tracking_value/_search');

        $data = [
            'tracking_name' => 'peak flow'
        ];

        $response = $this->postJsonTest($url, $data);

        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => 'success'
            ]);

        $arr = $response->json();

        $trackingValues = $arr['data']['tracking_value'];
        $this->assertTrue(count($trackingValues) > 0);
    }

    /**
     * Delete trackingValue.
     * @depends test_trackingValue_update
     * @return void
     */
    public function test_trackingValue_delete($insertedId)
    {
        $url = $this->makeUrl('/v1/patient/{patient_id}/tracking_value/{id}', $insertedId);

        $response = $this->deleteJsonTest($url, []);

        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'data' => [
                    'tracking_value' => [
                        'tracking_value_id' => $insertedId
                    ]
                ]
            ]);

        // verify that it is gone
        $response = $this->getJsonTest($url, 'fail');

        $response->assertStatus(404);
    }
}
