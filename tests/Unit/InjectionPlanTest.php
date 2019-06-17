<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Http\Request;
use Illuminate\Foundation\Testing\RefreshDatabase;

class InjectionPlanTest extends TestCase
{
    /**
     * Read injectionPlan that has no adjustments in it.
     */
    public function test_injection_plan_no_adjust()
    {
        // delete all the injections we find - clean slate

        $url = $this->makeUrl('/v1/patient/[patient_id]/injection');

        $response = $this->getJsonTest($url);

        $response->assertStatus(200);

        foreach ($response->json()['data']['injection'] as $key => $injection) {
            $url = $this->makeUrl('/v1/patient/[patient_id]/injection/[id]', $injection['injection_id']);

            $response = $this->deleteJsonTest($url, []);
            $response->assertStatus(200);

            // insert an injection

            $url = $this->makeUrl('/v1/patient/[patient_id]/injection');

            $data = [
            'dose' => '0.20',
            'site' => 'upperR',
            'notes' => 'Patient forgot epipen.',
            'notes_patient' => 'patient notes',
            'systemic_reaction' => 'N',
            'local_reaction' => 'Quarter',
            'user_id' => '9999',
            'vial_id' => '1',
            'deleted' => 'F',
            'attending' => 'Dr. Smith',
            'treatment_plan_step' => '1',
            'override_dose_warning' => 'Y'
            ];

            $response = $this->postJsonTest($url, $data);

            $response->assertStatus(200);

            // get the plan

            $url = $this->makeUrl('/v1/patient/[patient_id]/injection_plan');

            $response = $this->getJsonTest($url);

            $response
            ->assertStatus(200)
            ->assertJson(
                [
                'status' => 'success',
                'data' => [
                'injection_plan' => [
                [
                    'prescription_id' => '3',
                    'prescription_number' => '900002',
                    'name' => 'MLD\TRS',
                    'series' => [
                        [
                            'points' => [
                                [
                                    'x' => '2018-06-20 08:18:03',
                                    'y' => '0.050',
                                    'type' => 'Predicted'
                                ],
                                [
                                    'x' => '2018-06-26 08:18:03',
                                    'y' => '0.100',
                                    'type' => 'Predicted'
                                ],
                                [
                                    'x' => '2018-07-02 08:18:03',
                                    'y' => '0.200',
                                    'type' => 'Predicted'
                                ],
                                [
                                    'x' => '2018-07-08 08:18:03',
                                    'y' => '0.350',
                                    'type' => 'Predicted'
                                ],
                                [
                                    'x' => '2018-07-14 08:18:03',
                                    'y' => '0.500',
                                    'type' => 'Predicted'
                                ]
                            ],
                            'dilution' => 200,
                            'color' => 'RED'
                        ],
                        [
                            'points' => [
                                [
                                    'x' => '2018-07-20 08:18:03',
                                    'y' => '0.050',
                                    'type' => 'Predicted'
                                ],
                                [
                                    'x' => '2018-07-26 08:18:03',
                                    'y' => '0.100',
                                    'type' => 'Predicted'
                                ],
                                [
                                    'x' => '2018-08-01 08:18:03',
                                    'y' => '0.200',
                                    'type' => 'Predicted'
                                ],
                                [
                                    'x' => '2018-08-07 08:18:03',
                                    'y' => '0.350',
                                    'type' => 'Predicted'
                                ],
                                [
                                    'x' => '2018-08-13 08:18:03',
                                    'y' => '0.500',
                                    'type' => 'Predicted'
                                ]
                            ],
                            'dilution' => 100,
                            'color' => 'YLW'
                        ],
                        [
                            'points' => [
                                [
                                    'x' => '2018-08-19 08:18:03',
                                    'y' => '0.050',
                                    'type' => 'Predicted'
                                ],
                                [
                                    'x' => '2018-08-25 08:18:03',
                                    'y' => '0.070',
                                    'type' => 'Predicted'
                                ],
                                [
                                    'x' => '2018-08-31 08:18:03',
                                    'y' => '0.100',
                                    'type' => 'Predicted'
                                ],
                                [
                                    'x' => '2018-09-06 08:18:03',
                                    'y' => '0.150',
                                    'type' => 'Predicted'
                                ],
                                [
                                    'x' => '2018-09-12 08:18:03',
                                    'y' => '0.250',
                                    'type' => 'Predicted'
                                ],
                                [
                                    'x' => '2018-09-18 08:18:03',
                                    'y' => '0.350',
                                    'type' => 'Predicted'
                                ],
                                [
                                    'x' => '2018-09-24 08:18:03',
                                    'y' => '0.500',
                                    'type' => 'Predicted'
                                ]
                            ],
                            'dilution' => 10,
                            'color' => 'BLUE'
                        ],
                        [
                            'points' => [
                                [
                                    'x' => '2018-09-30 08:18:03',
                                    'y' => '0.050',
                                    'type' => 'Predicted'
                                ],
                                [
                                    'x' => '2018-10-06 08:18:03',
                                    'y' => '0.070',
                                    'type' => 'Predicted'
                                ],
                                [
                                    'x' => '2018-10-12 08:18:03',
                                    'y' => '0.100',
                                    'type' => 'Predicted'
                                ],
                                [
                                    'x' => '2018-10-18 08:18:03',
                                    'y' => '0.150',
                                    'type' => 'Predicted'
                                ],
                                [
                                    'x' => '2018-10-24 08:18:03',
                                    'y' => '0.250',
                                    'type' => 'Predicted'
                                ],
                                [
                                    'x' => '2018-10-30 08:18:03',
                                    'y' => '0.350',
                                    'type' => 'Predicted'
                                ],
                                [
                                    'x' => '2018-11-05 08:18:03',
                                    'y' => '0.500',
                                    'type' => 'Predicted'
                                ]
                            ],
                            'dilution' => 2,
                            'color' => 'GRN'
                        ],
                        [
                            'points' => [
                                [
                                    'x' => '2018-11-11 08:18:03',
                                    'y' => '0.050',
                                    'type' => 'Predicted'
                                ],
                                [
                                    'x' => '2018-11-17 08:18:03',
                                    'y' => '0.070',
                                    'type' => 'Predicted'
                                ],
                                [
                                    'x' => '2018-11-23 08:18:03',
                                    'y' => '0.100',
                                    'type' => 'Predicted'
                                ],
                                [
                                    'x' => '2018-11-29 08:18:03',
                                    'y' => '0.150',
                                    'type' => 'Predicted'
                                ],
                                [
                                    'x' => '2018-12-05 08:18:03',
                                    'y' => '0.200',
                                    'type' => 'Predicted'
                                ],
                                [
                                    'x' => '2018-12-11 08:18:03',
                                    'y' => '0.250',
                                    'type' => 'Predicted'
                                ],
                                [
                                    'x' => '2018-12-17 08:18:03',
                                    'y' => '0.300',
                                    'type' => 'Predicted'
                                ]
                            ],
                            'dilution' => 1,
                            'color' => 'SLVR'
                        ]
                    ]
                ]]
                ]]
            );
        }
    }
}
