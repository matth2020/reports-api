<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use App\Models\TreatmentSet;
use App\Models\Compound;
use App\Models\Vial;

class SetOrderTest extends TestCase
{

    /**
     * Read all set_orders for on prescription id.
     * @return void
     */
    public function test_set_order_get_prescription()
    {
        $url = $this->makeUrl('/v1/patient/{patient_id}/prescription/{prescription_id}/set_order');

        $response = $this->getJsonTest($url);

        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'data' => [
                    'set_order' => []
                ]
            ]);

        $arr = $response->json();

        $this->assertTrue(count($arr['data']['set_order']) === 2);

        $set_order = $arr['data']['set_order'][0];

        $this->assertTrue($set_order['prescription']['prescription_number'] == 900002);
        $this->assertTrue($set_order['prescription']['name'] == 'MLD\\TRS');
    }

    /**
     * Read all set_orders for a patient.
     * @return void
     */
    public function test_set_order_get_all()
    {
        $url = $this->makeUrl('/v1/patient/{patient_id}/set_order');

        $response = $this->getJsonTest($url);

        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'data' => [
                    'set_order' => []
                ]
            ]);
    }

    /**
     * Read one set_order by set_order id.
     * @return void
     */

    public function test_set_order_get_one()
    {
        $url = $this->makeUrl('/v1/patient/{patient_id}/prescription/{prescription_id}/set_order/{set_order_id}');

        // get the set_order
        $response = $this->getJsonTest($url);

        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'data' => [
                    'set_order' => []
                ]
            ]);

        $arr = $response->json();
        $set_order = $arr['data']['set_order'];

        $this->assertTrue($set_order['set_order_id'] == 1);
        $this->assertTrue($set_order['prescription']['name'] == 'MLD\\TRS');
    }

    /**
     * Read set_order specifying fields.
     * @return void
     */
    public function test_set_order_get_fields()
    {
        $url = $this->makeUrl('/v1/patient/{patient_id}/prescription/{prescription_id}/set_order/{set_order_id}?fields=prescription,set_order_id');

        $response = $this->getJsonTest($url);

        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'data' => [
                    'set_order' => [
                        'prescription' =>
                        [
                            'prescription_number' => '900002',
                            'name' => 'MLD\\TRS'
                        ],
                        'set_order_id' => TestCase::$set_order_id
                    ]
                ]
            ]);
    }

    /**
     * Update a mixed set_order.
     * @return void
     */
    public function test_set_order_update_rx_mixed_good()
    {
        $url = $this->makeUrl('/v1/patient/{patient_id}/prescription/{prescription_id}/set_order/{set_order_id}');

        $data = [
            'name' => 'updated_name',
            'clinic_id' =>  1,
            'provider_id' =>  2,
            'patient_id' =>  2, // shouldn't be able to change
            'dilutions' =>  [ // only if nothing is mixed yet
                1,
                2
            ],
            'dosings' =>  [ // only change extracts if never been mixed
                [            // otherwise only change doses if set is unmixed
                    'dose' =>  '2.000',
                    'ent_dilution' =>  0,
                    'extract_id' => 2
                ],
                [
                    'dose' =>  '1.000',
                    'ent_dilution' =>  0,
                    'extract_id' => 4
                ],
                [
                    'dose' =>  '1.000',
                    'ent_dilution' =>  0,
                    'extract_id' => 7
                ]
            ]
        ];

        // save the set_order
        $response = $this->putJsonTest($url, $data, 'validation');

        $response
            ->assertStatus(400)
            ->assertJson([
                'status' => 'validation',
                'errors' => [
                    'set_order_id' => 'The selected treatment set has already been mixed and can not be edited.'
                ]
            ]);
    }

    // this will fail if not run after purchase order tests. should be fixed
    // sometime
    public function test_set_order_update_rx_mixed_bad()
    {
        // note ts 1 is for the same rx and is postpone=F so mixed
        // ts2 (this one) is unmixed
        $url = $this->makeUrl('/v1/patient/{patient_id}/prescription/{prescription_id}/set_order/2');

        $data = [
            'name' => 'updated_name',
            'clinic_id' =>  2,
            'provider_id' =>  2,
            'patient_id' =>  2,
            'dilutions' =>  [
                1,
                2
            ],
            'dosings' =>  [
                [
                    'dose' =>  '2.000',
                    'ent_dilution' =>  0,
                    'extract_id' =>  7 // trying to change this but it shouldn't
                ],                     // let me
                [
                    'dose' =>  '1.000',
                    'ent_dilution' =>  0,
                    'extract_id' => 4
                ],
                [
                    'dose' =>  '1.000',
                    'ent_dilution' =>  0,
                    'extract_id' => 7
                ]
            ]
        ];

        // save the set_order
        $response = $this->putJsonTest($url, $data, 'validation');

        $response
            ->assertStatus(400)
            ->assertJson([
                'status' => 'validation',
                'errors' => [
                    'set_order_0' => ['This prescription has been previously mixed so the included extracts may not be changed.']
                ]
            ]);
    }

    /**
     * Update a set_order
     * @return void
     */
    public function test_set_order_update_unmixed_good()
    {
        // feel free to replace this test with something more appropriate
        // and not hardcoded
        $url = $this->makeUrl('/v1/patient/{patient_id}/prescription/4/set_order/4');

        $data = [
            'name' => 'updated_name',
            'clinic_id' =>  2,
            'provider_id' =>  2,
            'patient_id' =>  2, // shouldn't be able to change
            'dilutions' =>  [ // only if nothing is mixed yet
                1,
                10
            ],
            'dosings' =>  [ // only change extracts if never been mixed
                [            // otherwise only change doses if set is unmixed
                    'dose' =>  '1.000',
                    'ent_dilution' =>  0,
                    'extract_id' => 2
                ],
                [
                    'dose' =>  '1.250',
                    'ent_dilution' =>  0,
                    'extract_id' => 4
                ]
            ]
        ];

        // save the set_order
        $response = $this->putJsonTest($url, $data, 'success');

        // name is assigned to the compound (bottle) which isn't returned
        // until a set_order becomes a treatment_set with actual bottles.
        // It may be returned within the prescription object but only if
        // we are editing the first ever treatment_set
        unset($data['name']);
        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'data' => [
                    'set_order' => $data
                ]
            ]);
    }


    /**
     * Delete a set_order
     * @return void
     */
    public function test_set_order_delete()
    {
        // first create two prescriptions

        $url = $this->makeUrl('/v1/patient/{patient_id}/prescription');

        $data =  [
            'name' => 'First Rx for test_set_order_delete',
            'multiplier' => 1,
            'clinic_id' => 2,
            'provider_id' => 1,
            'diagnosis_id' => 4,
            'profile_id' => 1,
            'extracts' => [
                [
                    'extract_id' => 2,
                    'name' => 'Glycerinated Diluent',
                    'is_diluent' => 'T'
                ],
                [
                    'extract_id' => 7,
                    'name' => 'Alternaria',
                    'is_diluent' => 'F'
                ]
            ]
        ];

        // create the prescription
        $response = $this->postJsonTest($url, $data);

        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => 'success'
            ]);

        // get the new prescription ID

        $arr = $response->json();
        $prescription = $arr['data']['prescription'];

        $prescription_id1 = $prescription['prescription_id'];

        $url = $this->makeUrl('/v1/patient/{patient_id}/prescription');

        $data =  [
            'name' => 'Second Rx for test_set_order_delete',
            'multiplier' => 1,
            'clinic_id' => 2,
            'provider_id' => 1,
            'diagnosis_id' => 4,
            'profile_id' => 1,
            'extracts' => [
                [
                    'extract_id' => 2,
                    'name' => 'Glycerinated Diluent',
                    'is_diluent' => 'T'
                ],
                [
                    'extract_id' => 9,
                    'name' => 'Timothy Grass',
                    'is_diluent' => 'F'
                ]
            ]
        ];

        // create the prescription
        $response = $this->postJsonTest($url, $data);

        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => 'success'
            ]);

        // get the new prescription ID

        $arr = $response->json();
        $prescription = $arr['data']['prescription'];

        $prescription_id2 = $prescription['prescription_id'];

        // make purchase order

        $url = $this->makeUrl('/v1/patient/{patient_id}/purchase_order');

        $data =  [
            'account_id' => 1,
            'status_id' => 1,
            'set_orders' => [
                [
                    'name' => 'test_set_order_delete_1',
                    'note' => 'test_set_order_delete_1',
                    'provider_id' => 1,
                    'prescription_id' => $prescription_id1,
                    'clinic_id' => 2,
                    'status_id' => 3,
                    'size' => '5 mL',
                    'dilutions' => [
                        1,
                        2,
                        10,
                        100,
                        200
                    ],
                    'dosings' => [
                        [
                            'dose' => '2.000',
                            'ent_dilution' => 0,
                            'extract_id' => 2
                        ],
                        [
                            'dose' => '1.000',
                            'ent_dilution' => 0,
                            'extract_id' => 4
                        ],
                        [
                            'dose' => '1.750',
                            'ent_dilution' => 0,
                            'extract_id' => 7
                        ]
                    ]
                ],
                [
                    'name' => 'test_set_order_delete_2',
                    'note' => 'test_set_order_delete_2',
                    'provider_id' => 1,
                    'prescription_id' => $prescription_id2,
                    'clinic_id' => 2,
                    'status_id' => 3,
                    'size' => '5 mL',
                    'dilutions' => [
                        1,
                        2,
                        10,
                        100,
                        200
                    ],
                    'dosings' => [
                        [
                            'dose' => '2.000',
                            'ent_dilution' => 0,
                            'extract_id' => 2
                        ],
                        [
                            'dose' => '1.000',
                            'ent_dilution' => 0,
                            'extract_id' => 4
                        ],
                        [
                            'dose' => '1.750',
                            'ent_dilution' => 0,
                            'extract_id' => 9
                        ]
                    ]
                ]
            ]
        ];

        // create the purchase_order
        $response = $this->postJsonTest($url, $data);

        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'data' => [
                    'purchase_order' => $data
                ]
            ]);

        // get the set_order IDs

        $arr = $response->json();
        $purchaseOrder = $arr['data']['purchase_order'];

        $set_order_id1 = $purchaseOrder['set_orders'][0]['set_order_id'];
        $set_order_id2 = $purchaseOrder['set_orders'][1]['set_order_id'];
        $purchaseOrderId = $purchaseOrder['purchase_order_id'];

        // delete the first set_order

        $url = $this->makeUrl('/v1/patient/{patient_id}/prescription/' . $prescription_id1 . '/set_order/' . $set_order_id1);

        // delete the set_order
        $response = $this->deleteJsonTest($url, $data);

        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'data' => [
                    'set_order' => [
                        'set_order_id' => $set_order_id1
                    ]
                ]
            ]);

        // attempt to delete the second set_order

        $url = $this->makeUrl('/v1/patient/{patient_id}/prescription/' . $prescription_id2 . '/set_order/' . $set_order_id2);

        // delete the set_order
        $response = $this->deleteJsonTest($url, $data, 'validation');

        $response
            ->assertStatus(400)
            ->assertJson([
                'status' => 'validation',
                'errors' => [
                    'The requested set_order is the last set order in purchase_order ' . $purchaseOrderId . '. If you still want to delete the set_order, please delete the whole purchase_order.'
                ]
            ]);
    }


    /**
     * Create a bad set_order - change the extracts.
     * @return void
     */

    // public function test_set_order_bad_create_extracts()
    // {
    //     $url = $this->makeUrl('/v1/patient/{patient_id}/prescription/{prescription_id}/set_order');

    //     $data = [
    //         'name' => 'new_set_order',
    //         'clinic_id' =>  2,
    //         'provider_id' =>  1,
    //         'patient_id' =>  2,
    //         'priority' =>  '0',
    //         'dilutions' =>  [
    //             [
    //                 'dilution' =>  200,
    //                 'color' =>  'RED'
    //             ],
    //             [
    //                 'dilution' =>  100,
    //                 'color' =>  'YLW'
    //             ]
    //         ],
    //         'extracts' =>  [
    //             [
    //                 'dose' =>  '2.500',
    //                 'ent_dilution' =>  0,
    //                 'extract_id' => 2,
    //                 'name' =>  'Glycerinated Diluent'
    //             ],
    //             [
    //                 'dose' =>  '0.500',
    //                 'ent_dilution' =>  0,
    //                 'extract_id' =>  8,
    //                 'name' =>  'Timothy Grass'
    //             ],
    //             [
    //                 'dose' =>  '0.500',
    //                 'ent_dilution' =>  0,
    //                 'extract_id' =>  9,
    //                 'name' =>  'Candida Albicans'
    //             ]
    //         ]
    //     ];

    //     // save the set_order
    //     $response = $this->postJsonTest($url, $data, 'validation');

    //     $response->assertStatus('validation');
    // }
}
