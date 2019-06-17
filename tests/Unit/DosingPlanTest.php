<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;

define(
    'dosingPlanJson',
    <<<dosingPlanJson
    {
      "name": "ORIGINAL_NAME",
      "deleted": "F",
      "plan": [
        {
            "reaction_type": "LOCAL",
            "reaction_value": "None",
            "adjustments": [
                "+1", "+2", "+3", "+1", "+1", "+1", "+1", "+1", "+1", "+1", "0", "0", "0", "0", "0", "0", "0", "0", "-1", "-1", "-1", "-1", "ASK"
            ]
        },
        {
            "reaction_type": "LOCAL",
            "reaction_value": "Dime",
            "adjustments": [
                "0", "0", "-1", "-1", "-1", "-1", "-1", "-1", "-1", "-1", "-2", "-2", "-2", "-2", "-2", "-2", "-2", "-2", "-3", "-3", "-3", "-3", "ASK"
            ]
        },
        {
            "reaction_type": "LOCAL",
            "reaction_value": "Nickel",
            "adjustments": [
                "-1", "-1", "-2", "-2", "-2", "-2", "-2", "-2", "-2", "-2", "-3", "-3", "-3", "-3", "-3", "-3", "-3", "-3", "-4", "-4", "-4", "-4", "ASK"
            ]
        },
        {
            "reaction_type": "LOCAL",
            "reaction_value": "Quarter",
            "adjustments": [
                "-1", "-1", "-2", "-2", "-2", "-2", "-2", "-2", "-2", "-2", "-3", "-3", "-3", "-3", "-3", "-3", "-3", "-3", "-4", "-4", "-4", "-4", "ASK"
            ]
        },
        {
            "reaction_type": "SYSTEMIC",
            "reaction_value": "Y",
            "adjustments": [
                "ASK", "ASK", "ASK", "ASK", "ASK", "ASK", "ASK", "ASK", "ASK", "ASK", "ASK", "ASK", "ASK", "ASK", "ASK", "ASK", "ASK", "ASK", "ASK", "ASK", "ASK", "ASK", "ASK"
            ]
        }
      ]
    }
dosingPlanJson
);

define(
    'dosingPlanJsonChanged',
    <<<dosingPlanJsonChanged
    {
      "name": "CHANGED_NAME",
      "plan": [
        {
            "reaction_type": "LOCAL",
            "reaction_value": "None",
            "adjustments": [
                "+1", "+1", "0", "0", "0", "0", "0", "0", "0", "0", "-1", "-1", "-1", "-1", "-1", "-1", "-1", "-1", "-2", "-2", "-2", "-2", "ASK"
            ]
        },
        {
            "reaction_type": "LOCAL",
            "reaction_value": "Dime",
            "adjustments": [
                "0", "0", "-1", "-1", "-1", "-1", "-1", "-1", "-1", "-1", "-2", "-2", "-2", "-2", "-2", "-2", "-2", "-2", "-3", "-3", "-3", "-3", "ASK"
            ]
        },
        {
            "reaction_type": "LOCAL",
            "reaction_value": "Nickel",
            "adjustments": [
                "-1", "-1", "-2", "-2", "-2", "-2", "-2", "-2", "-2", "-2", "-3", "-3", "-3", "-3", "-3", "-3", "-3", "-3", "-4", "-4", "-4", "-4", "ASK"
            ]
        },
        {
            "reaction_type": "LOCAL",
            "reaction_value": "Quarter",
            "adjustments": [
                "-1", "-1", "-2", "-2", "-2", "-2", "-2", "-2", "-2", "-2", "-3", "-3", "-3", "-3", "-3", "-3", "-3", "-3", "-4", "-4", "-4", "-4", "ASK"
            ]
        },
        {
            "reaction_type": "SYSTEMIC",
            "reaction_value": "Y",
            "adjustments": [
                "ASK", "ASK", "ASK", "ASK", "ASK", "ASK", "ASK", "ASK", "ASK", "ASK", "ASK", "ASK", "ASK", "ASK", "ASK", "ASK", "ASK", "ASK", "ASK", "ASK", "ASK", "ASK", "ASK"
            ]
        }
      ]
    }
dosingPlanJsonChanged
);

class DosingPlanTest extends TestCase
{

    /**
     * Create dosing_plan.
     * @return integer dosing plan id
     */
    public function test_dosing_plan_insert()
    {
        $url = $this->makeUrl('/v1/dosing_plan');

        $data = (array)json_decode(dosingPlanJson);

        $response = $this->postJsonTest($url, $data);

        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'data' => [
                    'dosing_plan' => [
                        "name" => "ORIGINAL_NAME",
                        'deleted' => 'F',
                        'plan' => [
                            [
                                'reaction_type' => 'LOCAL',
                                'reaction_value' => 'None',
                                'adjustments' => [
                                    '1', '2', '3', '1', '1', '1', '1', '1', '1', '1', '0', '0', '0', '0', '0', '0', '0', '0', '-1', '-1', '-1', '-1', 'ASK'
                                ]
                            ],
                            [
                                'reaction_type' => 'LOCAL',
                                'reaction_value' => 'Dime',
                                'adjustments' => [
                                    '0', '0', '-1', '-1', '-1', '-1', '-1', '-1', '-1', '-1', '-2', '-2', '-2', '-2', '-2', '-2', '-2', '-2', '-3', '-3', '-3', '-3', 'ASK'
                                ]
                            ],
                            [
                                'reaction_type' => 'LOCAL',
                                'reaction_value' => 'Nickel',
                                'adjustments' => [
                                    '-1', '-1', '-2', '-2', '-2', '-2', '-2', '-2', '-2', '-2', '-3', '-3', '-3', '-3', '-3', '-3', '-3', '-3', '-4', '-4', '-4', '-4', 'ASK'
                                ]
                            ],
                            [
                                'reaction_type' => 'LOCAL',
                                'reaction_value' => 'Quarter',
                                'adjustments' => [
                                    '-1', '-1', '-2', '-2', '-2', '-2', '-2', '-2', '-2', '-2', '-3', '-3', '-3', '-3', '-3', '-3', '-3', '-3', '-4', '-4', '-4', '-4', 'ASK'
                                ]
                            ],
                            [
                                'reaction_type' => 'SYSTEMIC',
                                'reaction_value' => 'Y',
                                'adjustments' => [
                                    'ASK', 'ASK', 'ASK', 'ASK', 'ASK', 'ASK', 'ASK', 'ASK', 'ASK', 'ASK', 'ASK', 'ASK', 'ASK', 'ASK', 'ASK', 'ASK', 'ASK', 'ASK', 'ASK', 'ASK', 'ASK', 'ASK', 'ASK'
                                ]
                            ]
                        ]
                    ]
                ]
            ]);

        $arr = $response->json();
        $dosing_plan = $arr['data']['dosing_plan'];
        $insertedId = $dosing_plan['dosing_plan_id'];

        $this->assertTrue($insertedId !== 0);
        return $insertedId;
    }

    /**
     * Read dosing_plan.
     * @depends test_dosing_plan_insert
     * @return void
     */
    public function test_dosing_plan_get($insertedId)
    {
        $url = $this->makeUrl('/v1/dosing_plan/{id}', $insertedId);

        $response = $this->getJsonTest($url);

        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'data' => [
                    'dosing_plan' => [
                        "name" => "ORIGINAL_NAME",
                        'deleted' => 'F',
                        'plan' => [
                            [
                                'reaction_type' => 'LOCAL',
                                'reaction_value' => 'None',
                                'adjustments' => [
                                    '1', '2', '3', '1', '1', '1', '1', '1', '1', '1', '0', '0', '0', '0', '0', '0', '0', '0', '-1', '-1', '-1', '-1', 'ASK'
                                ]
                            ]
                        ]
                    ],
                ]
            ]);
    }

    /**
     * Read all dosing_plans.
     * @depends test_dosing_plan_insert
     * @return void
     */
    public function test_dosing_plan_get_all($insertedId)
    {
        $url = $this->makeUrl('/v1/dosing_plan');

        $response = $this->getJsonTest($url);

        $response
            ->assertStatus(200)
            ->assertJsonFragment([
                 "name" => "ORIGINAL_NAME"
            ]);
    }

    /**
     * Update dosing_plan.
     * @depends test_dosing_plan_insert
     * @return void
     */
    public function test_dosing_plan_update($insertedId)
    {
        $url = $this->makeUrl('/v1/dosing_plan/{id}', $insertedId);

        $data = (array)json_decode(dosingPlanJsonChanged);

        // update the dosing_plan
        $response = $this->putJsonTest($url, $data);

        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'data' => [
                    'dosing_plan' => [
                        'name' => 'CHANGED_NAME',
                        'plan' => [
                            [
                                'reaction_type' => 'LOCAL',
                                'reaction_value' => 'None',
                                'adjustments' => [
                                    '1', '1', '0', '0', '0', '0', '0', '0', '0', '0', '-1', '-1', '-1', '-1', '-1', '-1', '-1', '-1', '-2', '-2', '-2', '-2', 'ASK'
                                ]
                            ]
                        ]
                    ],
                ],
            ]);
    }


    /**
     * Search dosing_plan.
     * @return void
     */

    public function test_dosing_plan_search()
    {
        $url = $this->makeUrl('/v1/dosing_plan/_search');

        // verify nothing returned for non-existent name
        $data = [
            'name' => 'NON_EXISTENT_NAME'
        ];

        // get the dosing_plan
        $response = $this->postJsonTest($url, $data);

        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'data' => [
                    'dosing_plan' => []
                ],
            ]);

        // verify something returned for existent name
        $data = [
            'name' => 'CHANGED_NAME'
        ];

        // get the dosing_plan
        $response = $this->postJsonTest($url, $data);

        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'data' => [
                    'dosing_plan' => [
                        [
                            'name' => 'CHANGED_NAME',
                            'plan' => [
                                [
                                    'reaction_type' => 'LOCAL',
                                    'reaction_value' => 'None',
                                    'adjustments' => [
                                        '1', '1', '0', '0', '0', '0', '0', '0', '0', '0', '-1', '-1', '-1', '-1', '-1', '-1', '-1', '-1', '-2', '-2', '-2', '-2', 'ASK'
                                    ]
                                ]
                            ]
                        ]
                    ],
                ],
            ]);
    }

    /**
     * Delete dosing_plan.
     * @depends test_dosing_plan_insert
     * @return void
     */
    public function test_dosing_plan_delete($insertedId)
    {
        static $nonPresentID = '123456789';     // must not exist in database
        $presentID = $insertedId;               // must exist in database

        $url = $this->makeUrl('/v1/dosing_plan/{id}', $nonPresentID);

        // test for deleting non-existent ID

        $response = $this->deleteJsonTest($url, [], 'fail');

        $response
            ->assertStatus(404);

        // test for deleting existing ID

        $url = $this->makeUrl('/v1/dosing_plan/{id}', $presentID);

        $response = $this->deleteJsonTest($url, []);

        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'data' => [
                    'dosing_plan' => [
                        'deleted' => 'T'
                    ]
                ]
            ]);

        // verify that we don't see the deleted item in searches

        $url = $this->makeUrl('/v1/dosing_plan');

        $response = $this->getJsonTest($url);

        $response
            ->assertStatus(200)
            ->assertJsonMissing([
                "name" => "CHANGED_NAME"
            ]);

        $url = $this->makeUrl('/v1/dosing_plan/_search');

        $data = [
            'name' => 'CHANGED_NAME'
        ];

        $response = $this->postJsonTest($url, $data);

        $response
            ->assertStatus(200)
            ->assertJsonMissing([
                "name" => "CHANGED_NAME"
            ]);

        // verify that we do see the item if specifically requested

        $url = $this->makeUrl('/v1/dosing_plan/{id}', $insertedId);

        $response = $this->getJsonTest($url);

        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'data' => [
                    'dosing_plan' => [
                        "name" => "CHANGED_NAME",
                        'deleted' => 'T'
                    ],
                ]
            ]);
    }
}
