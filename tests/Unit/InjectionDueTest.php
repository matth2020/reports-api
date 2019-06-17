<?php
/**
 * Created by PhpStorm.
 * User: Martin
 * Date: 2/1/2018
 * Time: 4:17 PM
 */

namespace Tests\Unit;

use Tests\TestCase;

class InjectionDueTest extends TestCase
{
    public $treatment_plan = null;
    public $dosing_plan = null;

    private function get_treatment_plan()
    {
        if ($this->treatment_plan === null) {
            $url = $this->makeUrl('/v1/treatment_plan/{id}', 1);

            $response = $this->getJsonTest($url);

            $response
                ->assertStatus(200)
                ->assertJson([
                    'status' => 'success',
                    'data' => [
                        'treatment_plan' => []
                    ]
                ]);

            $arr = $response->json();
            $details = $arr ['data']['treatment_plan']['details'];

            $this->treatment_plan = [];
            foreach ($details as $detail) {
                foreach ($detail['steps'] as $step) {
                    $this->treatment_plan [$step['step_number']] = [
                        'step_number' => $step['step_number'],
                        'min_interval' => $step['min_interval'],
                        'max_interval' => $step['max_interval'],
                        'dose' => $step['dose'],
                        'dilution' => $detail ['dilution'],
                        'color' => $detail ['color']
                    ];
                }
            }
        }
    }

    private function get_dosing_plan()
    {
        if ($this->dosing_plan === null) {
            $url = $this->makeUrl('/v1/dosing_plan/{id}', 1);

            $response = $this->getJsonTest($url);

            $response
                ->assertStatus(200)
                ->assertJson([
                    'status' => 'success',
                    'data' => [
                        'dosing_plan' => []
                    ]
                ]);

            $arr = $response->json();
            $plan = $arr ['data']['dosing_plan']['plan'];

            $this->dosing_plan = [];
            foreach ($plan as $typeValue) {
                $type = $typeValue ['reaction_type'];
                $value = $typeValue ['reaction_value'];
                if (!array_key_exists($type, $this->dosing_plan)) {
                    $this->dosing_plan [$type] = [];
                }
                $this->dosing_plan [$type][$value] = $typeValue ['adjustments'];
            }
        }
    }

    /**
     * Read injection_dues for all prescriptions for patient
     */
    // not working -- need to fix

    // public function test_injection_due_get_all()
    // {
    //     $url = $this->makeUrl('/v1/patient/{patient_id}/injection_due');

    //     $response = $this->getJsonTest($url);

    //     $response->assertStatus(200);

    //     $response
    //         ->assertStatus(200)
    //         ->assertJson([
    //             'status' => 'success',
    //             'data' => [
    //                 'injection_due' => [
    //                     [],
    //                     [],
    //                     [
    //                         'prescription_id' => '3',
    //                         'next_injection' => [
    //                             'dilution' => 200,
    //                             'dose' => 0.050
    //                         ]
    //                     ]
    //                 ]
    //             ]
    //         ]);
    // }

    /**
     * Read injection_dues for one prescription
     */
    // not working - need to fix
    // public function test_injection_due_get_prescription()
    // {
    //     $url = $this->makeUrl('/v1/patient/{patient_id}/prescription/{prescription_id}/injection_due');

    //     $response = $this->getJsonTest($url);

    //     $response->assertStatus(200);

    //     $response
    //         ->assertStatus(200)
    //         ->assertJson([
    //             'status' => 'success',
    //             'data' => [
    //                 'injection_due' => [
    //                     'prescription_id' => '3',
    //                     'next_injection' => [
    //                         'dilution' => 200,
    //                         'dose' => 0.050
    //                     ]
    //                 ]
    //             ]
    //         ]);
    // }

    /**
     * Test one injection_due given parameters
     */
    public function Test_injection_due_get_one($dose, $vial_id, $reaction_type, $reaction_value, $daysSince)
    {
        $this->get_treatment_plan();
        $this->get_dosing_plan();

        // get the vial info

        $url = $this->makeUrl('/v1/patient/{patient_id}/prescription/{prescription_id}/vial/{id}', $vial_id);

        $response = $this->getJsonTest($url);

        $arr = $response->json();
        $vial = $arr['data']['vial'];
        $dilution = $vial['dilution'];

        // insert an injection

        $url = $this->makeUrl('/v1/patient/{patient_id}/injection');

        $injDate = mktime(0, 0, 0, date("m"), date("d") - $daysSince, date("Y"));
        $dateAgo = date('Y-m-d H:i:s', $injDate);

        $data = [
            'dose' => $dose,
            'site' => 'upperR',
            strtolower($reaction_type) . '_reaction' => $reaction_value,
            'vial_id' => $vial_id,
            'datetime_administered' => $dateAgo
        ];

        $response = $this->postJsonTest($url, $data);

        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'data' => [
                    'injection' => [
                        'dose' => $dose
                    ]
                ]
            ]);

        $arr = $response->json();
        $insertedId = $arr['data']['injection']['injection_id'];

        // get prediction from treatment plan

        // get all steps where the dose is less or equal to the target
        $allLessDose = array_filter($this->treatment_plan, function ($step) use ($dilution, $dose) {
            return ($step['dilution'] == $dilution) && ($step['dose'] <= $dose);
        });

        // if we didn't find one, get the first dose for the given dilution
        if (count($allLessDose) == 0) {
            $allDilution = array_filter($this->treatment_plan, function ($step) use ($dilution, $dose) {
                return ($step['dilution'] == $dilution);
            });
            $step = array_pop($allLessDose);
        } else {
            $step = end($allLessDose);
        }

        // compute the number of days late we are

        $latest = mktime(0, 0, 0, date("m"), date("d") - ($daysSince - $step['max_interval']), date("Y"));

        $latestOkDate = date_create("@$latest");

        $daysLate = intval(max(0, date_diff($latestOkDate, date_create())->format('%R%a')));

        // get the adjustment required from the dosing rules

        $adjust = 'ask';
        if ($daysLate < count($this->dosing_plan[$reaction_type][$reaction_value])) {
            $adjust = strtolower($this->dosing_plan[$reaction_type][$reaction_value][$daysLate]);
        }

        // adjust by the number of steps figured

        if ($adjust == 'ask') {
            $newDilution = 'ask';
            $newDose = 'ask';
        } else {
            $newStep_num = max(0, $step['step_number'] + $adjust);
            $adjustedPlan = $this->treatment_plan[$newStep_num];
            $newDilution = $adjustedPlan ['dilution'];
            $newDose = $adjustedPlan ['dose'];
        }

        // get the injectionDue and verify against our local computations

        $url = $this->makeUrl('/v1/patient/{patient_id}/prescription/{prescription_id}/injection_due');

        $response = $this->getJsonTest($url);

        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'data' => [
                    'injection_due' => [
                        'prescription_id' => '3',
                        'next_injection' => [
                            'dilution' => $newDilution,
                            'dose' => $newDose
                        ]
                    ]
                ]
            ]);

        // delete the injection

        $url = $this->makeUrl('/v1/patient/{patient_id}/injection/{id}', $insertedId);

        $response = $this->deleteJsonTest($url, []);

        $response->assertStatus(200);
    }

    /**
     * Clear out all the injections for this patient
     */
    public function test_injection_due_clean_injections()
    {
        // delete all the injections we find - clean slate

        $url = $this->makeUrl('/v1/patient/{patient_id}/injection');

        $response = $this->getJsonTest($url);

        $response->assertStatus(200);

        foreach ($response->json()['data']['injection'] as $key => $injection) {
            $url = $this->makeUrl('/v1/patient/{patient_id}/injection/{id}', $injection['injection_id']);
            $response = $this->deleteJsonTest($url, []);
            $response->assertStatus(200);
        }
    }

    /**
     * Verify injectionDue for one combination for ($dose, $vial_id, $reaction_type, $reaction_value, $daysSince)
     * @depend test_injection_due_clean_injections
     */
    public function test_injection_due_1()
    {
        $this->Test_injection_due_get_one(0.1, 1, 'LOCAL', 'None', 1);
    }

    /**
     * Verify injectionDue for one combination for ($dose, $vial_id, $reaction_type, $reaction_value, $daysSince)
     * @depend test_injection_due_clean_injections
     */
    public function test_injection_due_2()
    {
        $this->Test_injection_due_get_one(0.1, 1, 'LOCAL', 'Dime', 1);
    }

    /**
     * Verify injectionDue for one combination for ($dose, $vial_id, $reaction_type, $reaction_value, $daysSince)
     * @depend test_injection_due_clean_injections
     */
    public function test_injection_due_3()
    {
        $this->Test_injection_due_get_one(0.1, 1, 'LOCAL', 'Nickel', 1);
    }

    /**
     * Verify injectionDue for one combination for ($dose, $vial_id, $reaction_type, $reaction_value, $daysSince)
     * @depend test_injection_due_clean_injections
     */
    public function test_injection_due_4()
    {
        $this->Test_injection_due_get_one(0.1, 1, 'LOCAL', 'Quarter', 1);
    }

    /**
     * Verify injectionDue for one combination for ($dose, $vial_id, $reaction_type, $reaction_value, $daysSince)
     * @depend test_injection_due_clean_injections
     */
    public function test_injection_due_5()
    {
        $this->Test_injection_due_get_one(0.1, 1, 'LOCAL', 'None', 5);
    }

    /**
     * Verify injectionDue for one combination for ($dose, $vial_id, $reaction_type, $reaction_value, $daysSince)
     * @depend test_injection_due_clean_injections
     */
    public function test_injection_due_6()
    {
        $this->Test_injection_due_get_one(0.1, 1, 'LOCAL', 'Dime', 5);
    }

    /**
     * Verify injectionDue for one combination for ($dose, $vial_id, $reaction_type, $reaction_value, $daysSince)
     * @depend test_injection_due_clean_injections
     */
    public function test_injection_due_7()
    {
        $this->Test_injection_due_get_one(0.1, 1, 'LOCAL', 'Nickel', 5);
    }

    /**
     * Verify injectionDue for one combination for ($dose, $vial_id, $reaction_type, $reaction_value, $daysSince)
     * @depend test_injection_due_clean_injections
     */
    public function test_injection_due_8()
    {
        $this->Test_injection_due_get_one(0.1, 1, 'LOCAL', 'Quarter', 5);
    }

    /**
     * Verify injectionDue for one combination for ($dose, $vial_id, $reaction_type, $reaction_value, $daysSince)
     * @depend test_injection_due_clean_injections
     */
    public function test_injection_due_9()
    {
        $this->Test_injection_due_get_one(0.1, 1, 'LOCAL', 'None', 10);
    }

    /**
     * Verify injectionDue for one combination for ($dose, $vial_id, $reaction_type, $reaction_value, $daysSince)
     * @depend test_injection_due_clean_injections
     */
    public function test_injection_due_10()
    {
        $this->Test_injection_due_get_one(0.1, 1, 'LOCAL', 'Dime', 10);
    }

    /**
     * Verify injectionDue for one combination for ($dose, $vial_id, $reaction_type, $reaction_value, $daysSince)
     * @depend test_injection_due_clean_injections
     */
    public function test_injection_due_11()
    {
        $this->Test_injection_due_get_one(0.1, 1, 'LOCAL', 'Nickel', 10);
    }

    /**
     * Verify injectionDue for one combination for ($dose, $vial_id, $reaction_type, $reaction_value, $daysSince)
     * @depend test_injection_due_clean_injections
     */
    public function test_injection_due_12()
    {
        $this->Test_injection_due_get_one(0.1, 1, 'LOCAL', 'Quarter', 10);
    }

    /**
     * Verify injectionDue for one combination for ($dose, $vial_id, $reaction_type, $reaction_value, $daysSince)
     * @depend test_injection_due_clean_injections
     */
    public function test_injection_due_13()
    {
        $this->Test_injection_due_get_one(0.1, 1, 'LOCAL', 'None', 15);
    }

    /**
     * Verify injectionDue for one combination for ($dose, $vial_id, $reaction_type, $reaction_value, $daysSince)
     * @depend test_injection_due_clean_injections
     */
    public function test_injection_due_14()
    {
        $this->Test_injection_due_get_one(0.1, 1, 'LOCAL', 'Dime', 15);
    }

    /**
     * Verify injectionDue for one combination for ($dose, $vial_id, $reaction_type, $reaction_value, $daysSince)
     * @depend test_injection_due_clean_injections
     */
    public function test_injection_due_15()
    {
        $this->Test_injection_due_get_one(0.1, 1, 'LOCAL', 'Nickel', 15);
    }

    /**
     * Verify injectionDue for one combination for ($dose, $vial_id, $reaction_type, $reaction_value, $daysSince)
     * @depend test_injection_due_clean_injections
     */
    public function test_injection_due_16()
    {
        $this->Test_injection_due_get_one(0.1, 1, 'LOCAL', 'Quarter', 15);
    }

    /**
     * Verify injectionDue for one combination for ($dose, $vial_id, $reaction_type, $reaction_value, $daysSince)
     * @depend test_injection_due_clean_injections
     */
    public function test_injection_due_17()
    {
        $this->Test_injection_due_get_one(0.1, 1, 'LOCAL', 'None', 20);
    }

    /**
     * Verify injectionDue for one combination for ($dose, $vial_id, $reaction_type, $reaction_value, $daysSince)
     * @depend test_injection_due_clean_injections
     */
    public function test_injection_due_18()
    {
        $this->Test_injection_due_get_one(0.1, 1, 'LOCAL', 'Dime', 20);
    }

    /**
     * Verify injectionDue for one combination for ($dose, $vial_id, $reaction_type, $reaction_value, $daysSince)
     * @depend test_injection_due_clean_injections
     */
    public function test_injection_due_19()
    {
        $this->Test_injection_due_get_one(0.1, 1, 'LOCAL', 'Nickel', 20);
    }

    /**
     * Verify injectionDue for one combination for ($dose, $vial_id, $reaction_type, $reaction_value, $daysSince)
     * @depend test_injection_due_clean_injections
     */
    public function test_injection_due_20()
    {
        $this->Test_injection_due_get_one(0.1, 1, 'LOCAL', 'Quarter', 20);
    }

    /**
     * Verify injectionDue for one combination for ($dose, $vial_id, $reaction_type, $reaction_value, $daysSince)
     * @depend test_injection_due_clean_injections
     */
    public function test_injection_due_21()
    {
        $this->Test_injection_due_get_one(0.1, 1, 'LOCAL', 'None', 50);
    }

    /**
     * Verify injectionDue for one combination for ($dose, $vial_id, $reaction_type, $reaction_value, $daysSince)
     * @depend test_injection_due_clean_injections
     */
    public function test_injection_due_22()
    {
        $this->Test_injection_due_get_one(0.1, 1, 'LOCAL', 'Dime', 50);
    }

    /**
     * Verify injectionDue for one combination for ($dose, $vial_id, $reaction_type, $reaction_value, $daysSince)
     * @depend test_injection_due_clean_injections
     */
    public function test_injection_due_23()
    {
        $this->Test_injection_due_get_one(0.1, 1, 'LOCAL', 'Nickel', 50);
    }

    /**
     * Verify injectionDue for one combination for ($dose, $vial_id, $reaction_type, $reaction_value, $daysSince)
     * @depend test_injection_due_clean_injections
     */
    public function test_injection_due_24()
    {
        $this->Test_injection_due_get_one(0.1, 1, 'LOCAL', 'Quarter', 50);
    }

    /**
     * Verify injectionDue for one combination for ($dose, $vial_id, $reaction_type, $reaction_value, $daysSince)
     * @depend test_injection_due_clean_injections
     */
    public function test_injection_due_25()
    {
        $this->Test_injection_due_get_one(0.1, 1, 'SYSTEMIC', 'Y', 50);
    }

    /**
     * Verify injectionDue for one combination for ($dose, $vial_id, $reaction_type, $reaction_value, $daysSince)
     * @depend test_injection_due_clean_injections
     */
    public function test_injection_due_26()
    {
        $this->Test_injection_due_get_one(0.1, 2, 'LOCAL', 'Quarter', 50);
    }

    /**
     * Verify injectionDue for one combination for ($dose, $vial_id, $reaction_type, $reaction_value, $daysSince)
     * @depend test_injection_due_clean_injections
     */
    public function test_injection_due_27()
    {
        $this->Test_injection_due_get_one(0.24, 3, 'LOCAL', 'Quarter', 1);
    }
}
