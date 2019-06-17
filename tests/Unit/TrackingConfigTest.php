<?php

namespace Tests\Unit;

use Tests\TestCase;

class TrackingConfigTest extends TestCase
{
    /**
     * Read trackingConfig.
     */
    public function test_trackingConfig_read_patient()
    {
        $url = $this->makeUrl('/v1/patient/{patient_id}/tracking_config');

        $response = $this->getJsonTest($url);

        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'data' => [
                    'tracking_config' => [
                        [
                            'tracking_name' => 'peak flow',
                            'minimum' => -1,
                            'maximum' => -1,
                        ],
                        [
                            'tracking_name' => 'peak flow 2',
                            'minimum' => -2,
                            'maximum' => -2,
                        ],
                    ],
                ],
            ]);

        $arr = $response->json();

        $trackingConfigs = $arr['data']['tracking_config'];
        $this->assertTrue(count($trackingConfigs) >= 2);

        $trackingId = function ($config) {
            return $config['tracking_config_id'];
        };

        return array_map($trackingId, $trackingConfigs);
    }

    /**
     * Update trackingConfig.
     *
     * @depends test_trackingConfig_read_patient
     */
    public function test_trackingConfig_update($foundIDs)
    {
        $url = $this->makeUrl('/v1/patient/{patient_id}/tracking_config/{id}', $foundIDs[0]);

        $data = [
            'tracking_name' => 'peak flow',
            'minimum' => 3,
            'maximum' => 3,
        ];

        $response = $this->putJsonTest($url, $data);

        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'data' => [
                    'tracking_config' => [
                        'tracking_config_id' => $foundIDs[0],
                        'tracking_name' => 'peak flow',
                        'minimum' => 3,
                        'maximum' => 3,
                    ],
                ],
            ]);

        return $foundIDs[0];
    }

    /**
     * Update trackingConfig.
     *
     * @depends test_trackingConfig_read_patient
     */
    public function test_trackingConfig_read($foundIDs)
    {
        $url = $this->makeUrl('/v1/patient/{patient_id}/tracking_config/{id}', $foundIDs[0]);

        $response = $this->getJsonTest($url);

        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'data' => [
                    'tracking_config' => [
                        'tracking_config_id' => $foundIDs[0],
                        'tracking_name' => 'peak flow',
                        'minimum' => 3,
                        'maximum' => 3,
                    ],
                ],
            ]);

        return $foundIDs[0];
    }

    /**
     * Delete trackingConfig.
     *
     * @depends test_trackingConfig_update
     */
    public function test_trackingConfig_delete($foundID)
    {
        $url = $this->makeUrl('/v1/patient/{patient_id}/tracking_config/{id}', $foundID);

        $response = $this->deleteJsonTest($url, []);

        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'data' => [
                    'tracking_config' => [
                        'tracking_config_id' => $foundID,
                    ],
                ],
            ]);

        // verify that it is gone
        $response = $this->getJsonTest($url);

        // we expect to get the default config

        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'data' => [
                    'tracking_config' => [
                        'tracking_name' => 'peak flow',
                        'minimum' => -1,
                        'maximum' => -1,
                    ],
                ],
            ]);
    }
}
