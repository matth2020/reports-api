<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Http\Request;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TreatmentplanTest extends TestCase
{
    /**
     * Create treatmentplan that is poorly formed because:
     * The maintenance steps back may not be greater than 0.
     * The dilution must be one of the following values: 0, 1, 10, 100, 1000, 10000, 100000, 1000000, 10000000.
     * The fold must be one of the following values: 5, 10.
     * The color must be one of BLUE, GRN, LTBL, LTGR, ORNG, PINK, PRPL, RED, SLVR, WHT, or YLW.
     * The min interval must be at least 0.
     * The min interval must be at least 0.
     * The max interval must be at least 0.
     * The max interval must be at least 0.
     * The dose must be in the format ddd.ddd.
     * The dose must be a number.
     * The dose must be in the format ddd.ddd.
     * The dose must be at least 0.00.
     * The dose must be at least 0.00.
     * There are errors in the details.
     * Treatment plan steps must start at zero.
     * Treatment plan steps must increase by one.
     * Treatment plan doses must increase each step within a dilution.
     * The selected dosing plan id is invalid.

     * @return treatmentplan ID
     */
    public function test_treatment_plan_bad_insert()
    {
        $url = $this->makeUrl('/v1/treatment_plan');

        $data = [
            'name' => 'Test Bad Insert',
            'maintenance_steps_back' => 2,
            'dosing_plan_id' => 99999,
            'details' => [
                [
                    'dilution' => -10,
                    'steps' => [
                        [ 'step_number' => -1, 'min_interval' => 2, 'max_interval' => 10, 'dose' => 0.1501],
                        [ 'step_number' => 2, 'min_interval' => -2, 'max_interval' => 10, 'dose' => 'abc'],
                        [ 'step_number' => 3, 'min_interval' => 2, 'max_interval' => -10, 'dose' => 0.100],
                        [ 'step_number' => 4, 'min_interval' => 2, 'max_interval' => 10, 'dose' => -0.050],
                        [ 'step_number' => 5, 'min_interval' => 2, 'max_interval' => 10, 'dose' => 0.250],
                        [ 'step_number' => 6, 'min_interval' => 2, 'max_interval' => 10, 'dose' => 0.150],
                        [ 'step_number' => 8, 'min_interval' => 2, 'max_interval' => 10, 'dose' => 0.500]
                    ],
                    'fold' => '-10',
                    'color' => 'ASDF'
                ],
                [
                    'dilution' => 10,
                    'steps' => [
                        [ 'step_number' => 8, 'min_interval' => 2, 'max_interval' => 10, 'dose' => 0.150],
                        [ 'step_number' => 9, 'min_interval' => -2, 'max_interval' => 10, 'dose' => 0.070],
                        [ 'step_number' => 10, 'min_interval' => 2, 'max_interval' => -10, 'dose' => 0.100],
                        [ 'step_number' => 11, 'min_interval' => 2, 'max_interval' => 10, 'dose' => -0.050],
                        [ 'step_number' => 12, 'min_interval' => 2, 'max_interval' => 10, 'dose' => 0.250],
                        [ 'step_number' => 13, 'min_interval' => 2, 'max_interval' => 10, 'dose' => 0.350],
                        [ 'step_number' => 14, 'min_interval' => 2, 'max_interval' => 10, 'dose' => 0.500]
                    ],
                    'fold' => '-10',
                    'color' => 'RED'
                ]

            ]
        ];

        $response = $this->postJsonTest($url, $data, 'validation');

        $response
            ->assertStatus(400)
            ->assertJson([
                'status' => 'validation',
                'errors' => [
                    'maintenance_steps_back' => [
                        'The maintenance steps back may not be greater than 0.'
                    ],
                    'dilution' => [
                        'The dilution must be one of the following values: 0, 1, 10, 100, 1000, 10000, 100000, 1000000, 10000000.'
                    ],
                    'fold' => [
                        'The fold must be one of the following values: 5, 10.'
                    ],
                    'color' => [
                        'The color must be one of BLUE, GRN, LTBL, LTGR, ORNG, PINK, PRPL, RED, SLVR, WHT, or YLW.'
                    ],
                    'min_interval' => [
                        'The min interval must be at least 0.',
                        'The min interval must be at least 0.'
                    ],
                    'max_interval' => [
                        'The max interval must be at least 0.',
                        'The max interval must be at least 0.'
                    ],
                    'dose' => [
                        'The dose must be in the format ddd.ddd.',
                        'The dose must be a number.',
                        'The dose must be in the format ddd.ddd.',
                        'The dose must be at least 0.00.',
                        'The dose must be at least 0.00.'
                    ],
                    'details' => [
                        'There are errors in the details.',
                        'Treatment plan steps must start at zero.',
                        'Treatment plan steps must increase by one.',
                        'Treatment plan doses must increase each step within a dilution.'
                    ],
                    'dosing_plan_id' => [
                        'The selected dosing plan id is invalid.'
                    ]
                ]
            ]);
    }

    /**
     * Create treatment_plan.
     * @return treatment_plan ID
     */
    public function test_treatment_plan_insert()
    {
        $url = $this->makeUrl('/v1/treatment_plan');

        $data = [
            'name' => 'Test Insert 2',
            'maintenance_steps_back' => -2,
            'dosing_plan_id' => 1,
            'details' => [
                [
                    'dilution' => 10,
                    'steps' => [
                        [ 'step_number' => 0, 'min_interval' => 2, 'max_interval' => 10, 'dose' => 0.050],
                        [ 'step_number' => 1, 'min_interval' => 2, 'max_interval' => 10, 'dose' => 0.070],
                        [ 'step_number' => 2, 'min_interval' => 2, 'max_interval' => 10, 'dose' => 0.100],
                        [ 'step_number' => 3, 'min_interval' => 2, 'max_interval' => 10, 'dose' => 0.150],
                        [ 'step_number' => 4, 'min_interval' => 2, 'max_interval' => 10, 'dose' => 0.250],
                        [ 'step_number' => 5, 'min_interval' => 2, 'max_interval' => 10, 'dose' => 0.350],
                        [ 'step_number' => 6, 'min_interval' => 2, 'max_interval' => 10, 'dose' => 0.500]
                    ],
                    'fold' => '10',
                    'color' => 'BLUE'
                ],
                [
                    'dilution' => 1,
                    'steps' => [
                        [ 'step_number' => 7, 'min_interval' => 2, 'max_interval' => 10, 'dose' => 0.050],
                        [ 'step_number' => 8, 'min_interval' => 2, 'max_interval' => 10, 'dose' => 0.070],
                        [ 'step_number' => 9, 'min_interval' => 2, 'max_interval' => 10, 'dose' => 0.100],
                        [ 'step_number' => 10, 'min_interval' => 2, 'max_interval' => 10, 'dose' => 0.150],
                        [ 'step_number' => 11, 'min_interval' => 2, 'max_interval' => 10, 'dose' => 0.200],
                        [ 'step_number' => 12, 'min_interval' => 2, 'max_interval' => 10, 'dose' => 0.250],
                        [ 'step_number' => 13, 'min_interval' => 2, 'max_interval' => 10, 'dose' => 0.300],
                        [ 'step_number' => 14, 'min_interval' => 2, 'max_interval' => 10, 'dose' => 0.350],
                        [ 'step_number' => 15, 'min_interval' => 2, 'max_interval' => 10, 'dose' => 0.400],
                        [ 'step_number' => 16, 'min_interval' => 2, 'max_interval' => 10, 'dose' => 0.450],
                        [ 'step_number' => 17, 'min_interval' => 2, 'max_interval' => 10, 'dose' => 0.500],
                        [ 'step_number' => 18, 'min_interval' => 5, 'max_interval' => 10, 'dose' => 0.500]
                    ],
                    'fold' => '10',
                    'color' => 'RED'
                ]
            ]
        ];

        $response = $this->postJsonTest($url, $data);

        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'data' => [
                    'treatment_plan' => [
                        'name' => 'Test Insert 2',
                        'maintenance_steps_back' => -2,
                        'dosing_plan_id' => 1,
                        'details' => [
                            [
                                'dilution' => 10,
                                'steps' => [
                                    [ 'step_number' => 0, 'min_interval' => 2, 'max_interval' => 10, 'dose' => 0.050],
                                    [ 'step_number' => 1, 'min_interval' => 2, 'max_interval' => 10, 'dose' => 0.070],
                                    [ 'step_number' => 2, 'min_interval' => 2, 'max_interval' => 10, 'dose' => 0.100],
                                    [ 'step_number' => 3, 'min_interval' => 2, 'max_interval' => 10, 'dose' => 0.150],
                                    [ 'step_number' => 4, 'min_interval' => 2, 'max_interval' => 10, 'dose' => 0.250],
                                    [ 'step_number' => 5, 'min_interval' => 2, 'max_interval' => 10, 'dose' => 0.350],
                                    [ 'step_number' => 6, 'min_interval' => 2, 'max_interval' => 10, 'dose' => 0.500]
                                ],
                                'fold' => '10',
                                'color' => 'BLUE'
                            ],
                            [
                                'dilution' => 1,
                                'steps' => [
                                    [ 'step_number' => 7, 'min_interval' => 2, 'max_interval' => 10, 'dose' => 0.050],
                                    [ 'step_number' => 8, 'min_interval' => 2, 'max_interval' => 10, 'dose' => 0.070],
                                    [ 'step_number' => 9, 'min_interval' => 2, 'max_interval' => 10, 'dose' => 0.100],
                                    [ 'step_number' => 10, 'min_interval' => 2, 'max_interval' => 10, 'dose' => 0.150],
                                    [ 'step_number' => 11, 'min_interval' => 2, 'max_interval' => 10, 'dose' => 0.200],
                                    [ 'step_number' => 12, 'min_interval' => 2, 'max_interval' => 10, 'dose' => 0.250],
                                    [ 'step_number' => 13, 'min_interval' => 2, 'max_interval' => 10, 'dose' => 0.300],
                                    [ 'step_number' => 14, 'min_interval' => 2, 'max_interval' => 10, 'dose' => 0.350],
                                    [ 'step_number' => 15, 'min_interval' => 2, 'max_interval' => 10, 'dose' => 0.400],
                                    [ 'step_number' => 16, 'min_interval' => 2, 'max_interval' => 10, 'dose' => 0.450],
                                    [ 'step_number' => 17, 'min_interval' => 2, 'max_interval' => 10, 'dose' => 0.500],
                                    [ 'step_number' => 18, 'min_interval' => 5, 'max_interval' => 10, 'dose' => 0.500]
                                ],
                                'fold' => '10',
                                'color' => 'RED'
                            ]
                        ]
                    ]
                ]
            ]);

        $arr = $response->json();
        $treatmentplan = $arr['data']['treatment_plan'];
        $insertedId = $treatmentplan['treatment_plan_id'];

        $this->assertTrue($insertedId !== 0);
        return $insertedId;
    }

    /**
     * Update treatment_plan.
     * @depends test_treatment_plan_insert
     * @return void
     */
    public function test_treatment_plan_update($insertedId)
    {
        $url = $this->makeUrl('/v1/treatment_plan/{id}', $insertedId);

        $data = [
            'name' => 'Test Update 2',
            'maintenance_steps_back' => -3,
            'dosing_plan_id' => 1,
            'details' => [
                [
                    'dilution' => 10,
                    'steps' => [
                        [ 'step_number' => 0, 'min_interval' => 2, 'max_interval' => 10, 'dose' => 0.050],
                        [ 'step_number' => 1, 'min_interval' => 2, 'max_interval' => 10, 'dose' => 0.070],
                        [ 'step_number' => 2, 'min_interval' => 2, 'max_interval' => 10, 'dose' => 0.100],
                        [ 'step_number' => 3, 'min_interval' => 2, 'max_interval' => 10, 'dose' => 0.150]
                    ],
                    'fold' => '10',
                    'color' => 'BLUE'
                ],
                [
                    'dilution' => 1,
                    'steps' => [
                        [ 'step_number' => 4, 'min_interval' => 2, 'max_interval' => 10, 'dose' => 0.050],
                        [ 'step_number' => 5, 'min_interval' => 2, 'max_interval' => 10, 'dose' => 0.070],
                        [ 'step_number' => 6, 'min_interval' => 2, 'max_interval' => 10, 'dose' => 0.100],
                        [ 'step_number' => 7, 'min_interval' => 2, 'max_interval' => 10, 'dose' => 0.150]
                    ],
                    'fold' => '10',
                    'color' => 'GRN'
                ]
            ]
        ];

        $response = $this->putJsonTest($url, $data);

        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'data' => [
                    'treatment_plan' => [
                        'name' => 'Test Update 2',
                        'maintenance_steps_back' => -3,
                        'dosing_plan_id' => 1,
                        'details' => [
                            [
                                'dilution' => 10,
                                'steps' => [
                                    [ 'step_number' => 0, 'min_interval' => 2, 'max_interval' => 10, 'dose' => 0.050],
                                    [ 'step_number' => 1, 'min_interval' => 2, 'max_interval' => 10, 'dose' => 0.070],
                                    [ 'step_number' => 2, 'min_interval' => 2, 'max_interval' => 10, 'dose' => 0.100],
                                    [ 'step_number' => 3, 'min_interval' => 2, 'max_interval' => 10, 'dose' => 0.150]
                                ],
                                'fold' => '10',
                                'color' => 'BLUE'
                            ],
                            [
                                'dilution' => 1,
                                'steps' => [
                                    [ 'step_number' => 4, 'min_interval' => 2, 'max_interval' => 10, 'dose' => 0.050],
                                    [ 'step_number' => 5, 'min_interval' => 2, 'max_interval' => 10, 'dose' => 0.070],
                                    [ 'step_number' => 6, 'min_interval' => 2, 'max_interval' => 10, 'dose' => 0.100],
                                    [ 'step_number' => 7, 'min_interval' => 2, 'max_interval' => 10, 'dose' => 0.150]
                                ],
                                'fold' => '10',
                                'color' => 'GRN'
                            ]
                        ]
                    ]
                ]
            ]);

        return $insertedId;
    }

    /**
     * Read treatment_plan.
     * @depends test_treatment_plan_update
     * @return void
     */
    public function test_treatment_plan_read($insertedId)
    {
        $url = $this->makeUrl('/v1/treatment_plan/{id}', $insertedId);

        $response = $this->getJsonTest($url);

        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'data' => [
                    'treatment_plan' => [
                        'name' => 'Test Update 2'
                    ]
                ]
            ]);

        // $arr = $response->json();
    }

    /**
     * Search treatment_plan.
     * @depends test_treatment_plan_insert
     * @return void
     */
    public function test_treatment_plan_search($insertedId)
    {
        $url = $this->makeUrl('/v1/treatment_plan/_search');

        $data = [
            'name' => 'Test Update 2',
            'maintenance_steps_back' => -3,
            'dosing_plan_id' => 1,
            'details' => [
                [
                    'dilution' => 10,
                    'steps' => [
                        [ 'step_number' => 2, 'min_interval' => 2, 'max_interval' => 10, 'dose' => 0.100]
                    ],
                    'fold' => '10',
                    'color' => 'BLUE'
                ]
            ]
        ];

        $response = $this->postJsonTest($url, $data);

        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'data' => [
                    'treatment_plan' => [[
                        'name' => 'Test Update 2'
                    ]]
                ]
            ]);

        $arr = $response->json();

        $treatmentplans = $arr['data']['treatment_plan'];
        $this->assertTrue(count($treatmentplans) > 0);
    }

    /**
     * Delete treatment_plan.
     * @depends test_treatment_plan_update
     * @return void
     */
    public function test_treatment_plan_delete($insertedId)
    {
        $url = $this->makeUrl('/v1/treatment_plan/{id}', $insertedId);

        $response = $this->deleteJsonTest($url, []);

        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'data' => [
                    'treatment_plan' => [
                        'treatment_plan_id' => $insertedId,
                        'deleted' => 'T'
                    ]
                ]
            ]);
    }
}
