<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;

class PurchaseOrderTest extends TestCase
{
    /**
     * Read purchase orders for a patient.
     * @return void
     */
    public function test_purchase_order_get()
    {
        $url = $this->makeUrl('/v1/patient/{patient_id}/purchase_order');

        $response = $this->getJsonTest($url);

        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'data' => [
                    'purchase_order' => [
                        [
                            'purchase_order_id' => 2,
                            'account_id' => 1,
                            'set_orders' => [
                                [
                                    'transaction' => '800000',
                                    'purchase_order_id' => 2,
                                    'patient_id' => 2,
                                    'provider_id' => 1,
                                    'prescription_id' => 3,
                                    'clinic_id' => 2,
                                  //  'priority' => '0',
                                    'source' => 'XST',
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
                                            'dose' => '2.500',
                                            'ent_dilution' => 0,
                                            'extract_id' => 2
                                        ],
                                        [
                                            'dose' => '1.500',
                                            'ent_dilution' => 0,
                                            'extract_id' => 4
                                        ],
                                        [
                                            'dose' => '0.500',
                                            'ent_dilution' => 0,
                                            'extract_id' => 7
                                        ],
                                        [
                                            'dose' => '0.500',
                                            'ent_dilution' => 0,
                                            'extract_id' => 8
                                        ]
                                    ],
                                    'prescription' => [
                                        'prescription_id' => 3,
                                        'multiplier' => 1,
                                        'treatment_plan_id' => 1,
                                        'clinic_id' => 2,
                                        'user_id' => 2,
                                        'provider_id' => 1,
                                        'diagnosis_id' => 1,
                                        'patient_id' => 2,
                                    //    'priority' => '0',
                                        'source' => 'XST',
                                        'name' => 'MLD\\TRS',
                                        'prescription_number' => '900002',
                                        'strike_through' => 'F',
                                        'custom_units' => 'v/v',
                                        'profile_id' => 1,
                                        'dosing_plan_id' => 1
                                    ],
                                    'set_order_id' => 1
                                ]
                            ]
                        ],
                        [
                            'purchase_order_id' => 3,
                            'account_id' => 1,
                            'set_orders' => [
                                [
                                    'transaction' => '800001',
                                    'purchase_order_id' => 3,
                                    'patient_id' => 2,
                                    'provider_id' => 1,
                                    'prescription_id' => 3,
                                    'clinic_id' => 2,
                                //    'priority' => '0',
                                    'source' => 'XST',
                                    'size' => '10 mL',
                                    'dilutions' => [
                                        10,
                                        100,
                                        200
                                    ],
                                    'dosings' => [
                                        [
                                            'dose' => '6.000',
                                            'ent_dilution' => 0,
                                            'extract_id' => 2
                                        ],
                                        [
                                            'dose' => '3.000',
                                            'ent_dilution' => 0,
                                            'extract_id' => 4
                                        ],
                                        [
                                            'dose' => '0.400',
                                            'ent_dilution' => 0,
                                            'extract_id' => 7
                                        ],
                                        [
                                            'dose' => '0.600',
                                            'ent_dilution' => 0,
                                            'extract_id' => 8
                                        ]
                                    ],
                                    'prescription' => [
                                        'prescription_id' => 3,
                                        'multiplier' => 1,
                                        'treatment_plan_id' => 1,
                                        'clinic_id' => 2,
                                        'user_id' => 2,
                                        'provider_id' => 1,
                                        'diagnosis_id' => 1,
                                        'patient_id' => 2,
                                    //    'priority' => '0',
                                        'source' => 'XST',
                                        'name' => 'MLD\\TRS',
                                        'prescription_number' => '900002',
                                        'strike_through' => 'F',
                                        'custom_units' => 'v/v',
                                        'profile_id' => 1,
                                        'dosing_plan_id' => 1
                                    ],
                                    'set_order_id' => 2
                                ]
                            ]
                        ]
                    ]
                ]
            ]);
    }

    /**
     * Read purchase order by prescription id.
     * @return void
     */
    public function test_purchase_order_get_one()
    {
        $url = $this->makeUrl('/v1/patient/{patient_id}/purchase_order/3');

        $response = $this->getJsonTest($url);

        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'data' => [
                    'purchase_order' => [
                            'purchase_order_id' => 3,
                            'account_id' => 1,
                            'set_orders' => [
                                [
                                    'transaction' => '800001',
                                    'purchase_order_id' => 3,
                                    'patient_id' => 2,
                                    'provider_id' => 1,
                                    'prescription_id' => 3,
                                    'clinic_id' => 2,
                                //    'priority' => '0',
                                    'source' => 'XST',
                                    'size' => '10 mL',
                                    'dilutions' => [
                                        10,
                                        100,
                                        200
                                    ],
                                    'dosings' => [
                                        [
                                            'dose' => '6.000',
                                            'ent_dilution' => 0,
                                            'extract_id' => 2
                                        ],
                                        [
                                            'dose' => '3.000',
                                            'ent_dilution' => 0,
                                            'extract_id' => 4
                                        ],
                                        [
                                            'dose' => '0.400',
                                            'ent_dilution' => 0,
                                            'extract_id' => 7
                                        ],
                                        [
                                            'dose' => '0.600',
                                            'ent_dilution' => 0,
                                            'extract_id' => 8
                                        ]
                                    ],
                                    'prescription' => [
                                        'prescription_id' => 3,
                                        'multiplier' => 1,
                                        'treatment_plan_id' => 1,
                                        'clinic_id' => 2,
                                        'user_id' => 2,
                                        'provider_id' => 1,
                                        'diagnosis_id' => 1,
                                        'patient_id' => 2,
                                     //   'priority' => '0',
                                        'source' => 'XST',
                                        'name' => 'MLD\\TRS',
                                        'prescription_number' => '900002',
                                        'strike_through' => 'F',
                                        'custom_units' => 'v/v',
                                        'profile_id' => 1,
                                        'dosing_plan_id' => 1
                                    ],
                                    'set_order_id' => 2
                                ]
                            ]
                    ]
                ]
            ]);

    }

    /**
     * Read purchase order by prescription and get specific set_orders fields.
     * @return void
     */
    public function test_purchase_order_get_one_with_fields()
    {
        $url = $this->makeUrl('/v1/patient/{patient_id}/purchase_order/3?fields=set_orders.set_order_id%2Cset_orders.queue_state');

        $response = $this->getJsonTest($url);

        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'data' => [
                    'purchase_order' => [
                        'set_orders' => [
                            [
                                'queue_state' => 'queued',
                                'set_order_id' => 2
                            ]
                        ]
                    ]
                ]
            ]);
    }

    /**
     * Create purchase order using custom dilution steps.
     * @return int prescription_id created
     */

    public function test_purchase_order_create_custom_good()
    {

        // first create a prescription

        $url = $this->makeUrl('/v1/patient/{patient_id}/prescription');

        $data =  [
            'name' => 'Rx for test_purchase_order_create_custom_good',
            'multiplier' => 1,
            //    'priority' => '3',
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

        $prescription_id = $prescription['prescription_id'];
        $prescription_number = $prescription['prescription_number'];

        $url = $this->makeUrl('/v1/patient/{patient_id}/purchase_order');

        $data =  [
            'account_id' => 1,
            'status_id' => 1,
            'set_orders' => [
                [
                    'name' => 'from test_purchase_order_create_custom_good',
                    'note' => 'Note for New PO Vial B',
                    'provider_id' => 1,
                    'prescription_id' => $prescription_id,
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
                            'extract_id' => 8
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

        /*
                // get the vials and verify the bottle numbers

                $url = $this->makeUrl('/v1/patient/{patient_id}/vial');

                // get the vial
                $response = $this->getJsonTest($url);

                $response
                    ->assertStatus(200)
                    ->assertJson([
                        'status' => 'success'
                    ]);

                // get the bottle numbers

                $arr = $response->json();
                $vial = $arr['data']['vial'];

                $bottles = [];

                foreach ($vial as $v) {
                    if ($v['prescription_number'] == $prescription_number) {
                        $bottles[$v['vial_number']] = $v['dilution'];
                    }
                }

                $this->assertTrue($bottles[1] == 200 && $bottles[2] == 100 && $bottles[3] == 10 && $bottles[4] == 2 && $bottles[5] == 1);
        */

        $set_orders = $response->json()['data']['purchase_order']['set_orders'];

        assert (count($set_orders) == 1, 'Correct number of set_orders');
        assert (count($set_orders[0]['dilutions']) == 5, 'Correct number of dilutions');
        assert (count($set_orders[0]['dosings']) == 3, 'Correct number of dosings');

        return $prescription_id;
    }

    /**
     * Create purchase reorder.
     * @depends test_purchase_order_create_custom_good
     * @return void
     */

    public function test_purchase_order_create_custom_good_reorder($prescription_id)
    {
        // reorder the previous

        $url = $this->makeUrl('/v1/patient/{patient_id}/purchase_order');

        $data =  [
            'account_id' => 1,
            'status_id' => 1,
            'set_orders' => [
                [
                    'name' => 'from test_purchase_order_create_custom_good_reorder',
                    'note' => 'Note for New PO Vial B reorder',
                    'provider_id' => 1,
                    'prescription_id' => $prescription_id,
                    'clinic_id' => 2,
                    'status_id' => 1,
                    'size' => '5 mL',
                    'dilutions' => [
                        10,
                        100,
                        200
                    ],
                    'dosings' => [
                        [
                            'dose' => '2.100',
                            'ent_dilution' => 0,
                            'extract_id' => 2
                        ],
                        [
                            'dose' => '1.100',
                            'ent_dilution' => 0,
                            'extract_id' => 4
                        ],
                        [
                            'dose' => '1.550',
                            'ent_dilution' => 0,
                            'extract_id' => 8
                        ]
                    ]
                ]
            ]
        ];

        $response = $this->postJsonTest($url, $data);

        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'data' => [
                    'purchase_order' => $data
                ]
            ]);

        $set_orders = $response->json()['data']['purchase_order']['set_orders'];

        assert (count($set_orders) == 1, 'Correct number of set_orders');
        assert (count($set_orders[0]['dilutions']) == 3, 'Correct number of dilutions');
        assert (count($set_orders[0]['dosings']) == 3, 'Correct number of dosings');
    }

    /**
     * Create purchase order using 10-fold profile.
     * @return int prescription_id created
     */

    public function test_purchase_order_create_10fold_good()
    {

        // first create a prescription

        $url = $this->makeUrl('/v1/patient/{patient_id}/prescription');

        $data =  [
            'name' => 'Rx for test_purchase_order_create_10fold_good',
            'multiplier' => 1,
            'status_id' => 1,
            'clinic_id' => 2,
            'provider_id' => 2,
            'diagnosis_id' => 4,
            'profile_id' => 2,
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

        $prescription_id = $prescription['prescription_id'];
        $prescription_number = $prescription['prescription_number'];

        $url = $this->makeUrl('/v1/patient/{patient_id}/purchase_order');

        $data =  [
            'account_id' => 1,
            'status_id' => 1,
            'set_orders' => [
                [
                    'name' => 'from test_purchase_order_create_10fold_good',
                    'note' => 'Note for New PO Vial B',
                    'provider_id' => 1,
                    'prescription_id' => $prescription_id,
                    'clinic_id' => 2,
                    'status_id' => 1,
                    'size' => '5 mL',
                    'dilutions' => [
                        1,
                        10,
                        100,
                        1000
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
                            'extract_id' => 8
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

        $set_orders = $response->json()['data']['purchase_order']['set_orders'];

        assert (count($set_orders) == 1, 'Correct number of set_orders');
        assert (count($set_orders[0]['dilutions']) == 4, 'Correct number of dilutions');
        assert (count($set_orders[0]['dosings']) == 3, 'Correct number of dosings');

        return $prescription_id;
    }

    /**
     * Create purchase reorder.
     * @depends test_purchase_order_create_10fold_good
     * @return void
     */

    public function test_purchase_order_create_10fold_good_reorder($prescription_id)
    {
        // reorder the previous

        $url = $this->makeUrl('/v1/patient/{patient_id}/purchase_order');

        $data =  [
            'account_id' => 1,
            'status_id' => 2,
            'set_orders' => [
                [
                    'name' => 'from test_purchase_order_create_10fold_good_reorder',
                    'note' => 'Note for New PO 10-fold Vial reorder',
                    'provider_id' => 1,
                    'prescription_id' => $prescription_id,
                    'clinic_id' => 2,
                    'status_id' => 4,
                    'size' => '5 mL',
                    'dilutions' => [
                        1,
                        10,
                        1000
                    ],
                    'dosings' => [
                        [
                            'dose' => '2.100',
                            'ent_dilution' => 0,
                            'extract_id' => 2
                        ],
                        [
                            'dose' => '1.100',
                            'ent_dilution' => 0,
                            'extract_id' => 4
                        ],
                        [
                            'dose' => '1.550',
                            'ent_dilution' => 0,
                            'extract_id' => 8
                        ]
                    ]
                ]
            ]
        ];

        $response = $this->postJsonTest($url, $data);

        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'data' => [
                    'purchase_order' => $data
                ]
            ]);

        $set_orders = $response->json()['data']['purchase_order']['set_orders'];

        assert (count($set_orders) == 1, 'Correct number of set_orders');
        assert (count($set_orders[0]['dilutions']) == 3, 'Correct number of dilutions');
        assert (count($set_orders[0]['dosings']) == 3, 'Correct number of dosings');

        return $prescription_id;
    }

    /**
     * Create purchase order using two 10-fold profile prescriptions in one purchase order.
     * @depends test_purchase_order_create_10fold_good_reorder
     * @return int prescription_id created
     */

    public function test_purchase_order_create_10fold_multiple_good_reorder($prescription_id)
    {

        // first create another 10-fold prescription

        $url = $this->makeUrl('/v1/patient/{patient_id}/prescription');

        $data =  [
            'name' => 'Rx for test_purchase_order_create_10fold_multiple_good_reorder',
            'multiplier' => 1,
            'status_id' => 3,
            'clinic_id' => 2,
            'provider_id' => 2,
            'diagnosis_id' => 4,
            'profile_id' => 2,
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

        $prescription_id2 = $prescription['prescription_id'];

        // make the purchase order

        $url = $this->makeUrl('/v1/patient/{patient_id}/purchase_order');

        $data =  [
            'account_id' => 1,
            'set_orders' => [
                [
                    'name' => 'from test_purchase_order_create_10fold_multiple_good_reorder',
                    'note' => 'Note for 10-fold multiple reorder first',
                    'provider_id' => 1,
                    'prescription_id' => $prescription_id,
                    'clinic_id' => 2,
                    'status_id' => 3,
                    'size' => '5 mL',
                    'dilutions' => [
                        1,
                        10,
                        100,
                        1000
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
                            'extract_id' => 8
                        ]
                    ]
                ],
                [
                    'name' => 'from test_purchase_order_create_10fold_multiple_good_reorder second',
                    'note' => 'Note for 10-fold multiple reorder second',
                    'provider_id' => 1,
                    'prescription_id' => $prescription_id2,
                    'clinic_id' => 2,
                    'status_id' => 3,
                    'size' => '5 mL',
                    'dilutions' => [
                        1,
                        10,
                        100,
                        1000
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
                            'extract_id' => 8
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

        $arr = $response->json();
        $purchase_order = $arr['data']['purchase_order'];

        $set_orders = $response->json()['data']['purchase_order']['set_orders'];

        assert (count($set_orders) == 2, 'Correct number of set_orders');
        assert (count($set_orders[0]['dilutions']) == 4, 'Correct number of dilutions');
        assert (count($set_orders[0]['dosings']) == 3, 'Correct number of dosings');

        return $purchase_order;
    }

    /**
     * Create purchase order using 5-fold profile.
     * @return int prescription_id created
     */

    public function test_purchase_order_create_5fold_good()
    {

        // first create a prescription

        $url = $this->makeUrl('/v1/patient/{patient_id}/prescription');

        $data =  [
            'name' => 'Rx for test_purchase_order_create_5fold_good',
            'multiplier' => 1,
            'status_id' => 3,
            'clinic_id' => 2,
            'provider_id' => 2,
            'diagnosis_id' => 4,
            'profile_id' => 3,
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

        $prescription_id = $prescription['prescription_id'];
        $prescription_number = $prescription['prescription_number'];

        $url = $this->makeUrl('/v1/patient/{patient_id}/purchase_order');

        $data =  [
            'account_id' => 1,
            'set_orders' => [
                [
                    'name' => 'from test_purchase_order_create_5fold_good',
                    'note' => 'Note for New PO 5-fold Vial',
                    'provider_id' => 1,
                    'prescription_id' => $prescription_id,
                    'clinic_id' => 2,
                    'status_id' => 3,
                    'size' => '5 mL',
                    'dilutions' => [
                        1,
                        5,
                        25,
                        125
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
                            'extract_id' => 8
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

        return $prescription_id;
    }

    /**
     * Create purchase reorder.
     * @depends test_purchase_order_create_5fold_good
     * @return void
     */

    public function test_purchase_order_create_5fold_good_reorder($prescription_id)
    {
        // reorder the previous

        $url = $this->makeUrl('/v1/patient/{patient_id}/purchase_order');

        $data =  [
            'account_id' => 1,
            'set_orders' => [
                [
                    'name' => 'from test_purchase_order_create_5fold_good_reorder',
                    'note' => 'Note for New PO 5-fold Vial reorder',
                    'provider_id' => 1,
                    'prescription_id' => $prescription_id,
                    'clinic_id' => 2,
                    'status_id' => 3,
                    'size' => '5 mL',
                    'dilutions' => [
                        1,
                        5,
                        125
                    ],
                    'dosings' => [
                        [
                            'dose' => '2.100',
                            'ent_dilution' => 0,
                            'extract_id' => 2
                        ],
                        [
                            'dose' => '1.100',
                            'ent_dilution' => 0,
                            'extract_id' => 4
                        ],
                        [
                            'dose' => '1.550',
                            'ent_dilution' => 0,
                            'extract_id' => 8
                        ]
                    ]
                ]
            ]
        ];

        $response = $this->postJsonTest($url, $data);

        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'data' => [
                    'purchase_order' => $data
                ]
            ]);
    }

    /**
     * Create purchase order with bad patient ID.
     * @return void
     */

    public function test_purchase_order_create_bad_patient()
    {
        $url = $this->makeUrl('/v1/patient/9999/purchase_order');

        $data =  [
            'account_id' => 1,
            'set_orders' => [
                [
                    'name' => 'test_purchase_order_create_bad_patient',
                    'provider_id' => 1,
                    'prescription_id' => 3,
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
                            'extract_id' => 8
                        ]
                    ]
                ]
            ]
        ];

        // create the purchase_order
        $response = $this->postJsonTest($url, $data, 'validation');

        $response
            ->assertStatus(400)
            ->assertJson([
                'status' => 'validation',
                'errors' => [
                    'set_order_0' => [
                        'patient_id' => [
                            'The selected patient id is invalid.'
                        ]
                    ]
                ]
            ]);
    }

    /**
     * Create purchase order with out dilutions array.
     * @return void
     */

    public function test_purchase_order_create_bad_no_dilutions()
    {
        $url = $this->makeUrl('/v1/patient/{patient_id}/purchase_order');

        $data =  [
            'account_id' => 1,
            'set_orders' => [
                [
                    'name' => 'test_purchase_order_create_bad_no_dilutions',
                    'provider_id' => 1,
                    'prescription_id' => 3,
                    'clinic_id' => 2,
                    'status_id' => 3,
                    'size' => '5 mL',
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
                            'extract_id' => 8
                        ]
                    ]
                ]
            ]
        ];

        // create the purchase_order
        $response = $this->postJsonTest($url, $data, 'validation');

        $response
            ->assertStatus(400)
            ->assertJson([
                'status' => 'validation',
                'errors' => [
                    'set_order_0' => [
                        'There must at least 1 and no more than 8 dilutions.'
                    ]
                ]
            ]);
    }

    /**
     * Create purchase order with bad vial size.
     * @return void
     */

    public function test_purchase_order_create_bad_size()
    {
        $url = $this->makeUrl('/v1/patient/2/purchase_order');

        $data =  [
            'account_id' => 1,
            'set_orders' => [
                [
                    'name' => 'test_purchase_order_create_bad_size',
                    'provider_id' => 1,
                    'prescription_id' => 3,
                    'clinic_id' => 2,
                    'status_id' => 3,
                    'size' => '9999 mL',
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
                            'extract_id' => 8
                        ]
                    ]
                ]
            ]
        ];

        // create the purchase_order
        $response = $this->postJsonTest($url, $data, 'validation');

        $response
            ->assertStatus(400)
            ->assertJson([
                'status' => 'validation',
                'errors' => [
                    'set_order_0' => [
                        'size' => [
                            'The selected size is invalid.'
                        ]
                    ]
                ]
            ]);
    }

    /**
     * Create purchase order with missing name.
     * @return void
     */

    public function test_purchase_order_create_missing_name()
    {
        $url = $this->makeUrl('/v1/patient/2/purchase_order');

        $data =  [
            'account_id' => 1,
            'set_orders' => [
                [
                    'provider_id' => 1,
                    'prescription_id' => 3,
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
                            'dose' => '2.500',
                            'ent_dilution' => 0,
                            'extract_id' => 2
                        ],
                        [
                            'dose' => '1.500',
                            'ent_dilution' => 0,
                            'extract_id' => 4
                        ],
                        [
                            'dose' => '0.500',
                            'ent_dilution' => 0,
                            'extract_id' => 7
                        ],
                        [
                            'dose' => '0.500',
                            'ent_dilution' => 0,
                            'extract_id' => 8
                        ]
                    ]
                ]
            ]
        ];

        // create the purchase_order
        $response = $this->postJsonTest($url, $data, 'validation');

        $response
            ->assertStatus(400)
            ->assertJson([
                'status' => 'validation',
                'errors' => [
                    'set_order_0' => [
                        'name' => [
                            'The name field is required.'
                        ]
                    ]
                ]
            ]);
    }

    /**
     * Create purchase order with bad dilution.
     * @return void
     */

    public function test_purchase_order_create_bad_dilution()
    {
        $url = $this->makeUrl('/v1/patient/2/purchase_order');

        $data =  [
            'account_id' => 1,
            'set_orders' => [
                [
                    'name' => 'test_purchase_order_create_bad_dilution',
                    'provider_id' => 1,
                    'prescription_id' => 3,
                    'clinic_id' => 2,
                    'status_id' => 3,
                    'size' => '5 mL',
                    'dilutions' => [
                        1234,
                        100,
                        10,
                        2,
                        1
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
                            'extract_id' => 8
                        ]
                    ]
                ]
            ]
        ];

        // create the purchase_order
        $response = $this->postJsonTest($url, $data, 'validation');

        $response
            ->assertStatus(400)
            ->assertJson([
                'status' => 'validation',
                'errors' => [
                    'set_order_0' => [
                        'This prescription has been previously mixed so the included extracts may not be changed.',
                        'Requested dilution 1234 is not available in the requested profile.'
                    ]
                ]
            ])
            ->assertJsonMissing([
                'color' => [
                    'The color field is required.'
                ]
            ]);
    }

    /**
     * Create purchase order with bad dose.
     * @return void
     */

    public function test_purchase_order_create_bad_dose()
    {
        $url = $this->makeUrl('/v1/patient/2/purchase_order');

        $data =  [
            'account_id' => 1,
            'set_orders' => [
                [
                    'name' => 'test_purchase_order_create_bad_dose',
                    'provider_id' => 1,
                    'prescription_id' => 3,
                    'clinic_id' => 2,
                    'status_id' => 1,
                    'size' => '5 mL',
                    'dilutions' => [
                        100,
                        200
                    ],
                    'dosings' => [
                        [
                            'dose' => 'non-numeric',
                            'ent_dilution' => 0,
                            'extract_id' => 2
                        ],
                        [
                            'dose' => '-1.000',
                            'ent_dilution' => 0,
                            'extract_id' => 4
                        ],
                        [
                            'dose' => '1.750',
                            'ent_dilution' => 0,
                            'extract_id' => 8
                        ]
                    ]
                ]
            ]
        ];

        // create the purchase_order
        $response = $this->postJsonTest($url, $data, 'validation');

        $response
            ->assertStatus(400)
            ->assertJson([
                'status' => 'validation',
                'errors' => [
                    'set_order_0' => [
                        'dose_0' => [
                            'dose' => [
                                'The dose field must be decimal value with a maximum characteristic of 3 digits and a maximum mantissa of 3 digits.'
                            ]
                        ],
                        'dose_1' => [
                            'dose' => [
                                'The dose field must be non-negative.'
                            ]
                        ]
                    ]
                ]
            ])

            // don't let the error be repeated for each dilution

            ->assertJsonMissing([
                'dose' => [
                    'The dose must be a non-negative decimal value with a maximum characteristic of 3 digits and a maximum mantissa of 3 digits.'
                ],
                'dose' => [
                    'The dose must be a non-negative decimal value with a maximum characteristic of 3 digits and a maximum mantissa of 3 digits.'
                ],
                'dose' => [
                    'The dose must be a non-negative decimal value with a maximum characteristic of 3 digits and a maximum mantissa of 3 digits.'
                ],
                'dose' => [
                    'The dose must be a non-negative decimal value with a maximum characteristic of 3 digits and a maximum mantissa of 3 digits.'
                ]
            ]);
    }

    /**
     * Create purchase order with bad extract ID.
     * @return void
     */

    public function test_purchase_order_create_bad_extract()
    {
        $url = $this->makeUrl('/v1/patient/2/purchase_order');

        $data =  [
            'account_id' => 1,
            'set_orders' => [
                [
                    'name' => 'test_purchase_order_create_bad_extract',
                    'provider_id' => 1,
                    'prescription_id' => 3,
                    'clinic_id' => 2,
                    'status_id' => 1,
                    'size' => '5 mL',
                    'dilutions' => [
                        100,
                        200
                    ],
                    'dosings' => [
                        [
                            'dose' => '2.000',
                            'ent_dilution' => 0,
                            'extract_id' => 2234
                        ],
                        [
                            'dose' => '1.000',
                            'ent_dilution' => 0,
                            'extract_id' => 4
                        ],
                        [
                            'dose' => '1.750',
                            'ent_dilution' => 0,
                            'extract_id' => 8
                        ]
                    ]
                ]
            ]
        ];

        // create the purchase_order
        $response = $this->postJsonTest($url, $data, 'validation');

        $response
            ->assertStatus(400)
            ->assertJson([
                'status' => 'validation',
                'errors' => [
                    'set_order_0' => [
                        'dose_0' => [
                            'extract_id' => [
                                'The selected extract id is invalid.'
                            ]
                        ]
                    ]
                ]
            ])

            // don't let the error be repeated for each dilution

            ->assertJsonMissing([
                'set_order_0' => [
                    [
                        'extract_id' => [
                            'The selected extract id is invalid.'
                        ]
                    ],
                    [
                        'extract_id' => [
                            'The selected extract id is invalid.'
                        ]
                    ]
                ]
            ]);
    }

    /**
     * Create purchase order with bad provider ID and others.
     * @return void
     */

    public function test_purchase_order_create_bad_provider_etc()
    {
        $url = $this->makeUrl('/v1/patient/2/purchase_order');

        $data =  [
            'account_id' => 1,
            'set_orders' => [
                [
                    'name' => 'test_purchase_order_create_bad_provider_etc',
                    'provider_id' => 9999,
                    'prescription_id' => 9999,
                    'clinic_id' => 9999,
                    'status_id' => 9999,
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
                            'extract_id' => 8
                        ]
                    ]
                ]
            ]
        ];

        // create the purchase_order
        $response = $this->postJsonTest($url, $data, 'validation');

        $response
            ->assertStatus(400)
            ->assertJson([
                'status' => 'validation',
                'errors' => [
                    'set_order_0' => [
                        'provider_id' => [
                            'The selected provider_id must be a valid provider_id where deleted = F.'
                        ],
                        'prescription_id' => [
                            'The selected prescription id is invalid.'
                        ],
                        'clinic_id' => [
                            'The selected clinic id is invalid.'
                        ]
                    ]
                ]
            ]);
    }

    /**
     * Delete purchase reorder.
     * @depends test_purchase_order_create_custom_good
     * @return void
     */

    public function test_purchase_order_delete($prescription_id)
    {
        // create new purchase order

        $url = $this->makeUrl('/v1/patient/{patient_id}/purchase_order');

        $data =  [
            'account_id' => 1,
            'status_id' => 1,
            'set_orders' => [
                [
                    'name' => 'from test_purchase_order_delete',
                    'note' => 'PO to delete',
                    'provider_id' => 1,
                    'prescription_id' => $prescription_id,
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
                            'extract_id' => 8
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

        // get the new purchase order ID

        $arr = $response->json();
        $purchaseOrder = $arr['data']['purchase_order'];

        $purchaseOrderId = $purchaseOrder['purchase_order_id'];

        $url = $this->makeUrl('/v1/patient/{patient_id}/purchase_order/' . $purchaseOrderId);

        // delete the purchase_order
        $response = $this->deleteJsonTest($url, $data);

        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'data' => [
                    'purchase_order' => [
                        'purchase_order_id' => $purchaseOrderId
                    ]
                ]
            ]);

        // verify that it is not there

        $response = $this->getJsonTest($url, 'fail');

        $response
            ->assertStatus(404);
    }

    /**
     * Update purchase reorder.
     * @depends test_purchase_order_create_10fold_multiple_good_reorder
     * @return void
     */

    public function test_purchase_order_update($purchase_order_info)
    {
        $data = $purchase_order_info;
        unset($data['updated_at']);
        $data['set_orders'][0]['dilutions'] = [10];

        $url = $this->makeUrl('/v1/patient/{patient_id}/purchase_order/' . $purchase_order_info['purchase_order_id']);

        // update the purchase_order
        $response = $this->putJsonTest($url, $data);

        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'data' => [
                    'purchase_order' => $data
                ]
            ]);

        $set_orders = $response->json()['data']['purchase_order']['set_orders'];

        assert (count($set_orders) == 2, 'Correct number of set_orders');
        assert (count($set_orders[0]['dilutions']) == 1, 'Correct number of dilutions');
        assert (count($set_orders[0]['dosings']) == 3, 'Correct number of dosings');

    }

    /**
     * Create profile, prescription - fail on bad dilution
     */

    public function test_purchase_order_8_vial_bad_dilution()
    {
        $testString = '8_vial_forward_1';

        $url = $this->makeUrl('/v1/provider/1/profile');

        $data = [
            'offset' => 0,
            'provider_id' => 1,
            'dilution_steps' => [
                [
                    'dilution' => 1,
                    'expiration' => '3',
                    'color_name' => 'RED',
                    'color' => '16711680'
                ],
                [
                    'dilution' => 10,
                    'expiration' => '3',
                    'color_name' => 'YLW',
                    'color' => '16776960'
                ],
                [
                    'dilution' => 100,
                    'expiration' => '3',
                    'color_name' => 'BLUE',
                    'color' => '255'
                ],
                [
                    'dilution' => 1000,
                    'expiration' => '3',
                    'color_name' => 'GRN',
                    'color' => '32768'
                ],
                [
                    'dilution' => 10000,
                    'expiration' => '3',
                    'color_name' => 'SLVR',
                    'color' => '12632256'
                ],
                [
                    'dilution' => 100000,
                    'expiration' => '6',
                    'color_name' => 'PRPL',
                    'color' => '8388736'
                ],
                [
                    'dilution' => 1000000,
                    'expiration' => '9',
                    'color_name' => 'ORNG',
                    'color' => '16753920'
                ],
                [
                    'dilution' => 10000000,
                    'expiration' => '12',
                    'color_name' => 'GOLD',
                    'color' => '12607488'
                ]
            ],
            'bottle_numbering_order' => 'ascending_dilution',
            'name' => $testString,
            'low_glycerin_limit' => '20.00',
            'high_glycerin_limit' => '50.00',
            'default_vial_size' => '5 mL',
            'include_diluent_name' => 'F',
            'preferred_glycerin_diluent_id' => 1,
            'preferred_aqueous_diluent_id' => 3
        ];

        $response = $this->postJsonTest($url, $data);

        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'data' => [
                    'profile' => $data
                ]
            ]);

        $arr = $response->json();
        $profile = $arr['data']['profile'];
        $profileId = $profile['profile_id'];

        $this->assertTrue($profileId !== 0);

        /**
         * Create prescription using new profile
         */

        $url = $this->makeUrl('/v1/patient/{patient_id}/prescription');

        $data =  [
            'name' => $testString,
            'note' => 'Note for ' . $testString,
            'multiplier' => 1,
            'priority' => '0',
            'clinic_id' => 2,
            'provider_id' => 1,
            'diagnosis_id' => 4,
            'profile_id' => $profileId,
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

        $response = $this->postJsonTest($url, $data);

        $arr = $response->json();
        $prescription = $arr['data']['prescription'];
        $prescriptionId = $prescription['prescription_id'];

        /**
         * Order the prescription
         */

        $url = $this->makeUrl('/v1/patient/{patient_id}/purchase_order');

        $data =  [
            'account_id' => 1,
            'status_id' => 1,
            'set_orders' => [
                [
                    'name' => 'from profile/prescription test ' . $testString,
                    'note' => 'Note for New PO Vial B',
                    'provider_id' => 1,
                    'prescription_id' => $prescriptionId,
                    'clinic_id' => 2,
                    'status_id' => 1,
                    'size' => '5 mL',
                    'dilutions' => [
                        1,
                        10,
                        100,
                        1000,
                        10000,
                        100000,
                        1000000,
                        10000001
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
                            'extract_id' => 7
                        ]
                    ]
                ]
            ]
        ];

        // create the purchase_order
        $response = $this->postJsonTest($url, $data, 'validation');

        $response
            ->assertStatus(400)
            ->assertJson([
                'status' => 'validation',
                'errors' => [
                    'set_order_0' => [
                        'Requested dilution 10000001 is not available in the requested profile.'
                    ]
                ]
            ]);

    }

    /**
     * Create profile, prescription, and order - forward
     */

    public function test_purchase_order_8_vial_forward_1()
    {
        $testString = '8_vial_forward_1';

        $url = $this->makeUrl('/v1/provider/1/profile');

        $data = [
            'offset' => 0,
            'provider_id' => 1,
            'dilution_steps' => [
                [
                    'dilution' => 1,
                    'expiration' => '3',
                    'color_name' => 'RED',
                    'color' => '16711680'
                ],
                [
                    'dilution' => 10,
                    'expiration' => '3',
                    'color_name' => 'YLW',
                    'color' => '16776960'
                ],
                [
                    'dilution' => 100,
                    'expiration' => '3',
                    'color_name' => 'BLUE',
                    'color' => '255'
                ],
                [
                    'dilution' => 1000,
                    'expiration' => '3',
                    'color_name' => 'GRN',
                    'color' => '32768'
                ],
                [
                    'dilution' => 10000,
                    'expiration' => '3',
                    'color_name' => 'SLVR',
                    'color' => '12632256'
                ],
                [
                    'dilution' => 100000,
                    'expiration' => '6',
                    'color_name' => 'PRPL',
                    'color' => '8388736'
                ],
                [
                    'dilution' => 1000000,
                    'expiration' => '9',
                    'color_name' => 'ORNG',
                    'color' => '16753920'
                ],
                [
                    'dilution' => 10000000,
                    'expiration' => '12',
                    'color_name' => 'GOLD',
                    'color' => '12607488'
                ]
            ],
            'bottle_numbering_order' => 'ascending_dilution',
            'name' => $testString,
            'low_glycerin_limit' => '20.00',
            'high_glycerin_limit' => '50.00',
            'default_vial_size' => '5 mL',
            'include_diluent_name' => 'F',
            'preferred_glycerin_diluent_id' => 1,
            'preferred_aqueous_diluent_id' => 3
        ];

        $response = $this->postJsonTest($url, $data);

        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'data' => [
                    'profile' => $data
                ]
            ]);

        $arr = $response->json();
        $profile = $arr['data']['profile'];
        $profileId = $profile['profile_id'];

        $this->assertTrue($profileId !== 0);

        /**
         * Create prescription using new profile
         */

        $url = $this->makeUrl('/v1/patient/{patient_id}/prescription');

        $data =  [
            'name' => $testString,
            'note' => 'Note for ' . $testString,
            'multiplier' => 1,
            'priority' => '0',
            'clinic_id' => 2,
            'provider_id' => 1,
            'diagnosis_id' => 4,
            'profile_id' => $profileId,
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

        $response = $this->postJsonTest($url, $data);

        $arr = $response->json();
        $prescription = $arr['data']['prescription'];
        $prescriptionId = $prescription['prescription_id'];

        /**
         * Order the prescription
         */

        $url = $this->makeUrl('/v1/patient/{patient_id}/purchase_order');

        $data =  [
            'account_id' => 1,
            'status_id' => 1,
            'set_orders' => [
                [
                    'name' => 'from profile/prescription test ' . $testString,
                    'note' => 'Note for New PO Vial B',
                    'provider_id' => 1,
                    'prescription_id' => $prescriptionId,
                    'clinic_id' => 2,
                    'status_id' => 1,
                    'size' => '5 mL',
                    'dilutions' => [
                        1,
                        10,
                        100,
                        1000,
                        10000,
                        100000,
                        1000000,
                        10000000
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
                            'extract_id' => 7
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
                    'purchase_order' => $data,

                ]
            ]);

        /**
         * Read the prescription back and verify the colors, dilutions, and numbers
         */

        $url = $this->makeUrl('/v1/patient/{patient_id}/prescription/' . $prescriptionId);

        $response = $this->getJsonTest($url);

        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'data' => [
                    'prescription' => [
                        'treatment_sets' => [
                            [
                                'vials' => [
                                    [
                                        'dilution' => 1,
                                        'color' => 'RED',
                                        'vial_number' => '1'
                                    ],
                                    [
                                        'dilution' => 10,
                                        'color' => 'YLW',
                                        'vial_number' => '2'
                                    ],
                                    [
                                        'dilution' => 100,
                                        'color' => 'BLUE',
                                        'vial_number' => '3'
                                    ],
                                    [
                                        'dilution' => 1000,
                                        'color' => 'GRN',
                                        'vial_number' => '4'
                                    ],
                                    [
                                        'dilution' => 10000,
                                        'color' => 'SLVR',
                                        'vial_number' => '5'
                                    ],
                                    [
                                        'dilution' => 100000,
                                        'color' => 'PRPL',
                                        'vial_number' => '6'
                                    ],
                                    [
                                        'dilution' => 1000000,
                                        'color' => 'ORNG',
                                        'vial_number' => '7'
                                    ],
                                    [
                                        'dilution' => 10000000,
                                        'color' => 'GOLD',
                                        'vial_number' => '8'
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ]);
    }

    /**
     * Create profile, prescription, and order - reverse order
     */

    public function test_purchase_order_8_vial_reverse_1()
    {
        $testString = '8_vial_reverse_1';

        $url = $this->makeUrl('/v1/provider/1/profile');

        $data = [
            'offset' => 0,
            'provider_id' => 1,
            'dilution_steps' => [
                [
                    'dilution' => 10000000,
                    'expiration' => '12',
                    'color_name' => 'GOLD',
                    'color' => '12607488'
                ],
                [
                    'dilution' => 1000000,
                    'expiration' => '9',
                    'color_name' => 'ORNG',
                    'color' => '16753920'
                ],
                [
                    'dilution' => 100000,
                    'expiration' => '6',
                    'color_name' => 'PRPL',
                    'color' => '8388736'
                ],
                [
                    'dilution' => 10000,
                    'expiration' => '3',
                    'color_name' => 'SLVR',
                    'color' => '12632256'
                ],
                [
                    'dilution' => 1000,
                    'expiration' => '3',
                    'color_name' => 'GRN',
                    'color' => '32768'
                ],
                [
                    'dilution' => 100,
                    'expiration' => '3',
                    'color_name' => 'BLUE',
                    'color' => '255'
                ],
                [
                    'dilution' => 10,
                    'expiration' => '3',
                    'color_name' => 'YLW',
                    'color' => '16776960'
                ],
                [
                    'dilution' => 1,
                    'expiration' => '3',
                    'color_name' => 'RED',
                    'color' => '16711680'
                ]
            ],
            'bottle_numbering_order' => 'descending_dilution',
            'name' => $testString,
            'low_glycerin_limit' => '20.00',
            'high_glycerin_limit' => '50.00',
            'default_vial_size' => '5 mL',
            'include_diluent_name' => 'F',
            'preferred_glycerin_diluent_id' => 1,
            'preferred_aqueous_diluent_id' => 3
        ];

        $response = $this->postJsonTest($url, $data);

        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'data' => [
                    'profile' => $data
                ]
            ]);

        $arr = $response->json();
        $profile = $arr['data']['profile'];
        $profileId = $profile['profile_id'];

        $this->assertTrue($profileId !== 0);

        /**
         * Create prescription using new profile
         */

        $url = $this->makeUrl('/v1/patient/{patient_id}/prescription');

        $data =  [
            'name' => $testString,
            'note' => 'Note for ' . $testString,
            'multiplier' => 1,
            'priority' => '0',
            'clinic_id' => 2,
            'provider_id' => 1,
            'diagnosis_id' => 4,
            'profile_id' => $profileId,
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

        $response = $this->postJsonTest($url, $data);

        $arr = $response->json();
        $prescription = $arr['data']['prescription'];
        $prescriptionId = $prescription['prescription_id'];

        /**
         * Order the prescription
         */

        $url = $this->makeUrl('/v1/patient/{patient_id}/purchase_order');

        $data =  [
            'account_id' => 1,
            'status_id' => 1,
            'set_orders' => [
                [
                    'name' => 'from profile/prescription test ' . $testString,
                    'note' => 'Note for New PO Vial B',
                    'provider_id' => 1,
                    'prescription_id' => $prescriptionId,
                    'clinic_id' => 2,
                    'status_id' => 1,
                    'size' => '5 mL',
                    'dilutions' => [
                      1,
                      10,
                      100,
                      1000,
                      10000,
                      100000,
                      1000000,
                      10000000
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
                            'extract_id' => 7
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
                    'purchase_order' => $data,

                ]
            ]);

        /**
         * Read the prescription back and verify the colors, dilutions, and numbers
         */

        $url = $this->makeUrl('/v1/patient/{patient_id}/prescription/' . $prescriptionId);

        $response = $this->getJsonTest($url);

        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'data' => [
                    'prescription' => [
                        'treatment_sets' => [
                            [
                                'vials' => [
                                    [
                                        'dilution' => 10000000,
                                        'color' => 'GOLD',
                                        'vial_number' => '1'
                                    ],
                                    [
                                        'dilution' => 1000000,
                                        'color' => 'ORNG',
                                        'vial_number' => '2'
                                    ],
                                    [
                                        'dilution' => 100000,
                                        'color' => 'PRPL',
                                        'vial_number' => '3'
                                    ],
                                    [
                                        'dilution' => 10000,
                                        'color' => 'SLVR',
                                        'vial_number' => '4'
                                    ],
                                    [
                                        'dilution' => 1000,
                                        'color' => 'GRN',
                                        'vial_number' => '5'
                                    ],
                                    [
                                        'dilution' => 100,
                                        'color' => 'BLUE',
                                        'vial_number' => '6'
                                    ],
                                    [
                                        'dilution' => 10,
                                        'color' => 'YLW',
                                        'vial_number' => '7'
                                    ],
                                    [
                                        'dilution' => 1,
                                        'color' => 'RED',
                                        'vial_number' => '8'
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ]);
    }

    /**
     * Create profile, prescription, and order - forward custom
     */

    public function test_purchase_order_8_vial_forward_custom()
    {
        $testString = '8_vial_forward_custom';

        $url = $this->makeUrl('/v1/provider/1/profile');

        $data = [
            'offset' => 0,
            'provider_id' => 1,
            'dilution_steps' => [
                [
                    'dilution' => 1,
                    'expiration' => '3',
                    'color_name' => 'RED',
                    'color' => '16711680'
                ],
                [
                    'dilution' => 2,
                    'expiration' => '3',
                    'color_name' => 'YLW',
                    'color' => '16776960'
                ],
                [
                    'dilution' => 10,
                    'expiration' => '3',
                    'color_name' => 'BLUE',
                    'color' => '255'
                ],
                [
                    'dilution' => 20,
                    'expiration' => '3',
                    'color_name' => 'GRN',
                    'color' => '32768'
                ],
                [
                    'dilution' => 100,
                    'expiration' => '3',
                    'color_name' => 'SLVR',
                    'color' => '12632256'
                ],
                [
                    'dilution' => 200,
                    'expiration' => '6',
                    'color_name' => 'PRPL',
                    'color' => '8388736'
                ],
                [
                    'dilution' => 500,
                    'expiration' => '9',
                    'color_name' => 'ORNG',
                    'color' => '16753920'
                ],
                [
                    'dilution' => 1000,
                    'expiration' => '12',
                    'color_name' => 'GOLD',
                    'color' => '12607488'
                ]
            ],
            'bottle_numbering_order' => 'ascending_dilution',
            'name' => $testString,
            'low_glycerin_limit' => '20.00',
            'high_glycerin_limit' => '50.00',
            'default_vial_size' => '5 mL',
            'include_diluent_name' => 'F',
            'preferred_glycerin_diluent_id' => 1,
            'preferred_aqueous_diluent_id' => 3
        ];

        $response = $this->postJsonTest($url, $data);

        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'data' => [
                    'profile' => $data
                ]
            ]);

        $arr = $response->json();
        $profile = $arr['data']['profile'];
        $profileId = $profile['profile_id'];

        $this->assertTrue($profileId !== 0);

        /**
         * Create prescription using new profile
         */

        $url = $this->makeUrl('/v1/patient/{patient_id}/prescription');

        $data =  [
            'name' => $testString,
            'note' => 'Note for ' . $testString,
            'multiplier' => 1,
            'priority' => '0',
            'clinic_id' => 2,
            'provider_id' => 1,
            'diagnosis_id' => 4,
            'profile_id' => $profileId,
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

        $response = $this->postJsonTest($url, $data);

        $arr = $response->json();
        $prescription = $arr['data']['prescription'];
        $prescriptionId = $prescription['prescription_id'];

        /**
         * Order the prescription
         */

        $url = $this->makeUrl('/v1/patient/{patient_id}/purchase_order');

        $data =  [
            'account_id' => 1,
            'status_id' => 1,
            'set_orders' => [
                [
                    'name' => 'from profile/prescription test ' . $testString,
                    'note' => 'Note for New PO Vial B',
                    'provider_id' => 1,
                    'prescription_id' => $prescriptionId,
                    'clinic_id' => 2,
                    'status_id' => 1,
                    'size' => '5 mL',
                    'dilutions' => [
                        1,
                        2,
                        10,
                        20,
                        100,
                        200,
                        500,
                        1000
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
                            'extract_id' => 7
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
                    'purchase_order' => $data,

                ]
            ]);

        /**
         * Read the prescription back and verify the colors, dilutions, and numbers
         */

        $url = $this->makeUrl('/v1/patient/{patient_id}/prescription/' . $prescriptionId);

        $response = $this->getJsonTest($url);

        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'data' => [
                    'prescription' => [
                        'treatment_sets' => [
                            [
                                'vials' => [
                                    [
                                        'dilution' => 1,
                                        'color' => 'RED',
                                        'vial_number' => '1'
                                    ],
                                    [
                                        'dilution' => 2,
                                        'color' => 'YLW',
                                        'vial_number' => '2'
                                    ],
                                    [
                                        'dilution' => 10,
                                        'color' => 'BLUE',
                                        'vial_number' => '3'
                                    ],
                                    [
                                        'dilution' => 20,
                                        'color' => 'GRN',
                                        'vial_number' => '4'
                                    ],
                                    [
                                        'dilution' => 100,
                                        'color' => 'SLVR',
                                        'vial_number' => '5'
                                    ],
                                    [
                                        'dilution' => 200,
                                        'color' => 'PRPL',
                                        'vial_number' => '6'
                                    ],
                                    [
                                        'dilution' => 500,
                                        'color' => 'ORNG',
                                        'vial_number' => '7'
                                    ],
                                    [
                                        'dilution' => 1000,
                                        'color' => 'GOLD',
                                        'vial_number' => '8'
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ]);
    }

    /**
     * Create profile, prescription, and order - reverse order custom
     */

    public function test_purchase_order_8_vial_reverse_custom()
    {
        $testString = '8_vial_reverse_custom';

        $url = $this->makeUrl('/v1/provider/1/profile');

        $data = [
            'offset' => 0,
            'provider_id' => 1,
            'dilution_steps' => [
                [
                    'dilution' => 1000,
                    'expiration' => '12',
                    'color_name' => 'GOLD',
                    'color' => '12607488'
                ],
                [
                    'dilution' => 500,
                    'expiration' => '9',
                    'color_name' => 'ORNG',
                    'color' => '16753920'
                ],
                [
                    'dilution' => 200,
                    'expiration' => '6',
                    'color_name' => 'PRPL',
                    'color' => '8388736'
                ],
                [
                    'dilution' => 100,
                    'expiration' => '3',
                    'color_name' => 'SLVR',
                    'color' => '12632256'
                ],
                [
                    'dilution' => 20,
                    'expiration' => '3',
                    'color_name' => 'GRN',
                    'color' => '32768'
                ],
                [
                    'dilution' => 10,
                    'expiration' => '3',
                    'color_name' => 'BLUE',
                    'color' => '255'
                ],
                [
                    'dilution' => 2,
                    'expiration' => '3',
                    'color_name' => 'YLW',
                    'color' => '16776960'
                ],
                [
                    'dilution' => 1,
                    'expiration' => '3',
                    'color_name' => 'RED',
                    'color' => '16711680'
                ]
            ],
            'bottle_numbering_order' => 'descending_dilution',
            'name' => $testString,
            'low_glycerin_limit' => '20.00',
            'high_glycerin_limit' => '50.00',
            'default_vial_size' => '5 mL',
            'include_diluent_name' => 'F',
            'preferred_glycerin_diluent_id' => 1,
            'preferred_aqueous_diluent_id' => 3
        ];

        $response = $this->postJsonTest($url, $data);

        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'data' => [
                    'profile' => $data
                ]
            ]);

        $arr = $response->json();
        $profile = $arr['data']['profile'];
        $profileId = $profile['profile_id'];

        $this->assertTrue($profileId !== 0);

        /**
         * Create prescription using new profile
         */

        $url = $this->makeUrl('/v1/patient/{patient_id}/prescription');

        $data =  [
            'name' => $testString,
            'note' => 'Note for ' . $testString,
            'multiplier' => 1,
            'priority' => '0',
            'clinic_id' => 2,
            'provider_id' => 1,
            'diagnosis_id' => 4,
            'profile_id' => $profileId,
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

        $response = $this->postJsonTest($url, $data);

        $arr = $response->json();
        $prescription = $arr['data']['prescription'];
        $prescriptionId = $prescription['prescription_id'];

        /**
         * Order the prescription
         */

        $url = $this->makeUrl('/v1/patient/{patient_id}/purchase_order');

        $data =  [
            'account_id' => 1,
            'status_id' => 1,
            'set_orders' => [
                [
                    'name' => 'from profile/prescription test ' . $testString,
                    'note' => 'Note for New PO Vial B',
                    'provider_id' => 1,
                    'prescription_id' => $prescriptionId,
                    'clinic_id' => 2,
                    'status_id' => 1,
                    'size' => '5 mL',
                    'dilutions' => [
                      1,
                      2,
                      10,
                      20,
                      100,
                      200,
                      500,
                      1000
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
                            'extract_id' => 7
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
                    'purchase_order' => $data,

                ]
            ]);

        /**
         * Read the prescription back and verify the colors, dilutions, and numbers
         */

        $url = $this->makeUrl('/v1/patient/{patient_id}/prescription/' . $prescriptionId);

        $response = $this->getJsonTest($url);

        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'data' => [
                    'prescription' => [
                        'treatment_sets' => [
                            [
                                'vials' => [
                                    [
                                        'dilution' => 1000,
                                        'color' => 'GOLD',
                                        'vial_number' => '1'
                                    ],
                                    [
                                      'dilution' => 500,
                                      'color' => 'ORNG',
                                      'vial_number' => '2'
                                    ],
                                    [
                                        'dilution' => 200,
                                        'color' => 'PRPL',
                                        'vial_number' => '3'
                                    ],
                                    [
                                        'dilution' => 100,
                                        'color' => 'SLVR',
                                        'vial_number' => '4'
                                    ],
                                    [
                                        'dilution' => 20,
                                        'color' => 'GRN',
                                        'vial_number' => '5'
                                    ],
                                    [
                                        'dilution' => 10,
                                        'color' => 'BLUE',
                                        'vial_number' => '6'
                                    ],
                                    [
                                        'dilution' => 2,
                                        'color' => 'YLW',
                                        'vial_number' => '7'
                                    ],
                                    [
                                        'dilution' => 1,
                                        'color' => 'RED',
                                        'vial_number' => '8'
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ]);
    }

    /**
     * Create profile, prescription, and order - reverse order -1   returning validation error 'The offset must be between 0 and 8.'
     */

    public function test_purchase_order_8_vial_reverse_negative_1()
    {
        $testString = '8_vial_reverese_neg_1';

        $url = $this->makeUrl('/v1/provider/1/profile');

        $data = [
            'offset' => -1,
            'provider_id' => 1,
            'dilution_steps' => [
                [
                    'dilution' => 10000000,
                    'expiration' => '12',
                    'color_name' => 'GOLD',
                    'color' => '12607488'
                ],
                [
                    'dilution' => 1000000,
                    'expiration' => '9',
                    'color_name' => 'ORNG',
                    'color' => '16753920'
                ],
                [
                    'dilution' => 100000,
                    'expiration' => '6',
                    'color_name' => 'PRPL',
                    'color' => '8388736'
                ],
                [
                    'dilution' => 10000,
                    'expiration' => '3',
                    'color_name' => 'SLVR',
                    'color' => '12632256'
                ],
                [
                    'dilution' => 1000,
                    'expiration' => '3',
                    'color_name' => 'GRN',
                    'color' => '32768'
                ],
                [
                    'dilution' => 100,
                    'expiration' => '3',
                    'color_name' => 'BLUE',
                    'color' => '255'
                ],
                [
                    'dilution' => 10,
                    'expiration' => '3',
                    'color_name' => 'YLW',
                    'color' => '16776960'
                ],
                [
                    'dilution' => 1,
                    'expiration' => '3',
                    'color_name' => 'RED',
                    'color' => '16711680'
                ]
            ],
            'bottle_numbering_order' => 'descending_dilution',
            'name' => $testString,
            'low_glycerin_limit' => '20.00',
            'high_glycerin_limit' => '50.00',
            'default_vial_size' => '5 mL',
            'include_diluent_name' => 'F',
            'preferred_glycerin_diluent_id' => 1,
            'preferred_aqueous_diluent_id' => 3
        ];

        $response = $this->postJsonTest($url, $data);

        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'data' => [
                    'profile' => $data
                ]
            ]);

        $arr = $response->json();
        $profile = $arr['data']['profile'];
        $profileId = $profile['profile_id'];

        $this->assertTrue($profileId !== 0);

        /**
         * Create prescription using new profile
         */

        $url = $this->makeUrl('/v1/patient/{patient_id}/prescription');

        $data =  [
            'name' => $testString,
            'note' => 'Note for ' . $testString,
            'multiplier' => 1,
            'priority' => '0',
            'clinic_id' => 2,
            'provider_id' => 1,
            'diagnosis_id' => 4,
            'profile_id' => $profileId,
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

        $response = $this->postJsonTest($url, $data);

        $arr = $response->json();
        $prescription = $arr['data']['prescription'];
        $prescriptionId = $prescription['prescription_id'];

        /**
         * Order the prescription
         */

        $url = $this->makeUrl('/v1/patient/{patient_id}/purchase_order');

        $data =  [
            'account_id' => 1,
            'status_id' => 1,
            'set_orders' => [
                [
                    'name' => 'from profile/prescription test ' . $testString,
                    'note' => 'Note for New PO Vial B',
                    'provider_id' => 1,
                    'prescription_id' => $prescriptionId,
                    'clinic_id' => 2,
                    'status_id' => 1,
                    'size' => '5 mL',
                    'dilutions' => [
                      1,
                      10,
                      100,
                      1000,
                      10000,
                      100000,
                      1000000,
                      10000000
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
                            'extract_id' => 7
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
                    'purchase_order' => $data,

                ]
            ]);

        /**
         * Read the prescription back and verify the colors, dilutions, and numbers
         */

        $url = $this->makeUrl('/v1/patient/{patient_id}/prescription/' . $prescriptionId);

        $response = $this->getJsonTest($url);

        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'data' => [
                    'prescription' => [
                        'treatment_sets' => [
                            [
                                'vials' => [
                                    [
                                        'dilution' => 10000000,
                                        'color' => 'GOLD',
                                        'vial_number' => '0'
                                    ],
                                    [
                                        'dilution' => 1000000,
                                        'color' => 'ORNG',
                                        'vial_number' => '1'
                                    ],
                                    [
                                        'dilution' => 100000,
                                        'color' => 'PRPL',
                                        'vial_number' => '2'
                                    ],
                                    [
                                        'dilution' => 10000,
                                        'color' => 'SLVR',
                                        'vial_number' => '3'
                                    ],
                                    [
                                        'dilution' => 1000,
                                        'color' => 'GRN',
                                        'vial_number' => '4'
                                    ],
                                    [
                                        'dilution' => 100,
                                        'color' => 'BLUE',
                                        'vial_number' => '5'
                                    ],
                                    [
                                        'dilution' => 10,
                                        'color' => 'YLW',
                                        'vial_number' => '6'
                                    ],
                                    [
                                        'dilution' => 1,
                                        'color' => 'RED',
                                        'vial_number' => '7'
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ]);
    }

    /**
     * Create profile, prescription, and order forward -1   returning validation error 'The offset must be between 0 and 8.'
     */

    public function test_purchase_order_8_vial_forward_neg_1()
    {
        $testString = '8_vial_forward_1';

        $url = $this->makeUrl('/v1/provider/1/profile');

        $data = [
            'offset' => -1,
            'provider_id' => 1,
            'dilution_steps' => [
                [
                    'dilution' => 1,
                    'expiration' => '3',
                    'color_name' => 'RED',
                    'color' => '16711680'
                ],
                [
                    'dilution' => 10,
                    'expiration' => '3',
                    'color_name' => 'YLW',
                    'color' => '16776960'
                ],
                [
                    'dilution' => 100,
                    'expiration' => '3',
                    'color_name' => 'BLUE',
                    'color' => '255'
                ],
                [
                    'dilution' => 1000,
                    'expiration' => '3',
                    'color_name' => 'GRN',
                    'color' => '32768'
                ],
                [
                    'dilution' => 10000,
                    'expiration' => '3',
                    'color_name' => 'SLVR',
                    'color' => '12632256'
                ],
                [
                    'dilution' => 100000,
                    'expiration' => '6',
                    'color_name' => 'PRPL',
                    'color' => '8388736'
                ],
                [
                    'dilution' => 1000000,
                    'expiration' => '9',
                    'color_name' => 'ORNG',
                    'color' => '16753920'
                ],
                [
                    'dilution' => 10000000,
                    'expiration' => '12',
                    'color_name' => 'GOLD',
                    'color' => '12607488'
                ]
            ],
            'bottle_numbering_order' => 'ascending_dilution',
            'name' => $testString,
            'low_glycerin_limit' => '20.00',
            'high_glycerin_limit' => '50.00',
            'default_vial_size' => '5 mL',
            'include_diluent_name' => 'F',
            'preferred_glycerin_diluent_id' => 1,
            'preferred_aqueous_diluent_id' => 3
        ];

        $response = $this->postJsonTest($url, $data);

        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'data' => [
                    'profile' => $data
                ]
            ]);

        $arr = $response->json();
        $profile = $arr['data']['profile'];
        $profileId = $profile['profile_id'];

        $this->assertTrue($profileId !== 0);

        /**
         * Create prescription using new profile
         */

        $url = $this->makeUrl('/v1/patient/{patient_id}/prescription');

        $data =  [
            'name' => $testString,
            'note' => 'Note for ' . $testString,
            'multiplier' => 1,
            'priority' => '0',
            'clinic_id' => 2,
            'provider_id' => 1,
            'diagnosis_id' => 4,
            'profile_id' => $profileId,
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

        $response = $this->postJsonTest($url, $data);

        $arr = $response->json();
        $prescription = $arr['data']['prescription'];
        $prescriptionId = $prescription['prescription_id'];

        /**
         * Order the prescription
         */

        $url = $this->makeUrl('/v1/patient/{patient_id}/purchase_order');

        $data =  [
            'account_id' => 1,
            'status_id' => 1,
            'set_orders' => [
                [
                    'name' => 'from profile/prescription test ' . $testString,
                    'note' => 'Note for New PO Vial B',
                    'provider_id' => 1,
                    'prescription_id' => $prescriptionId,
                    'clinic_id' => 2,
                    'status_id' => 1,
                    'size' => '5 mL',
                    'dilutions' => [
                        1,
                        10,
                        100,
                        1000,
                        10000,
                        100000,
                        1000000,
                        10000000
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
                            'extract_id' => 7
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
                    'purchase_order' => $data,

                ]
            ]);

        /**
         * Read the prescription back and verify the colors, dilutions, and numbers
         */

        $url = $this->makeUrl('/v1/patient/{patient_id}/prescription/' . $prescriptionId);

        $response = $this->getJsonTest($url);

        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'data' => [
                    'prescription' => [
                        'treatment_sets' => [
                            [
                                'vials' => [
                                    [
                                        'dilution' => 1,
                                        'color' => 'RED',
                                        'vial_number' => '0'
                                    ],
                                    [
                                        'dilution' => 10,
                                        'color' => 'YLW',
                                        'vial_number' => '1'
                                    ],
                                    [
                                        'dilution' => 100,
                                        'color' => 'BLUE',
                                        'vial_number' => '2'
                                    ],
                                    [
                                        'dilution' => 1000,
                                        'color' => 'GRN',
                                        'vial_number' => '3'
                                    ],
                                    [
                                        'dilution' => 10000,
                                        'color' => 'SLVR',
                                        'vial_number' => '4'
                                    ],
                                    [
                                        'dilution' => 100000,
                                        'color' => 'PRPL',
                                        'vial_number' => '5'
                                    ],
                                    [
                                        'dilution' => 1000000,
                                        'color' => 'ORNG',
                                        'vial_number' => '6'
                                    ],
                                    [
                                        'dilution' => 10000000,
                                        'color' => 'GOLD',
                                        'vial_number' => '7'
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ]);
    }

    /**
     * Create profile, prescription, and order - 5 vial 5 fold
     */

    public function test_purchase_order_5_vial_forward_5_fold()
    {
        $testString = '5_vial_forward_5_fold';

        $url = $this->makeUrl('/v1/provider/1/profile');

        $data = [
            'offset' => 0,
            'provider_id' => 1,
            'dilution_steps' => [
                [
                    'dilution' => 1,
                    'expiration' => '3',
                    'color_name' => 'RED',
                    'color' => '16711680'
                ],
                [
                    'dilution' => 5,
                    'expiration' => '3',
                    'color_name' => 'YLW',
                    'color' => '16776960'
                ],
                [
                    'dilution' => 25,
                    'expiration' => '3',
                    'color_name' => 'BLUE',
                    'color' => '255'
                ],
                [
                    'dilution' => 125,
                    'expiration' => '3',
                    'color_name' => 'GRN',
                    'color' => '32768'
                ],
                [
                    'dilution' => 625,
                    'expiration' => '3',
                    'color_name' => 'SLVR',
                    'color' => '12632256'
                ]
            ],
            'bottle_numbering_order' => 'ascending_dilution',
            'name' => $testString,
            'low_glycerin_limit' => '20.00',
            'high_glycerin_limit' => '50.00',
            'default_vial_size' => '5 mL',
            'include_diluent_name' => 'F',
            'preferred_glycerin_diluent_id' => 1,
            'preferred_aqueous_diluent_id' => 3
        ];

        $response = $this->postJsonTest($url, $data);

        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'data' => [
                    'profile' => $data
                ]
            ]);

        $arr = $response->json();
        $profile = $arr['data']['profile'];
        $profileId = $profile['profile_id'];

        $this->assertTrue($profileId !== 0);

        /**
         * Create prescription using new profile
         */

        $url = $this->makeUrl('/v1/patient/{patient_id}/prescription');

        $data =  [
            'name' => $testString,
            'note' => 'Note for ' . $testString,
            'multiplier' => 1,
            'priority' => '0',
            'clinic_id' => 2,
            'provider_id' => 1,
            'diagnosis_id' => 4,
            'profile_id' => $profileId,
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

        $response = $this->postJsonTest($url, $data);

        $arr = $response->json();
        $prescription = $arr['data']['prescription'];
        $prescriptionId = $prescription['prescription_id'];

        /**
         * Order the prescription
         */

        $url = $this->makeUrl('/v1/patient/{patient_id}/purchase_order');

        $data =  [
            'account_id' => 1,
            'status_id' => 1,
            'set_orders' => [
                [
                    'name' => 'from profile/prescription test ' . $testString,
                    'note' => 'Note for New PO Vial B',
                    'provider_id' => 1,
                    'prescription_id' => $prescriptionId,
                    'clinic_id' => 2,
                    'status_id' => 1,
                    'size' => '5 mL',
                    'dilutions' => [
                        1,
                        5,
                        25,
                        125,
                        625
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
                            'extract_id' => 7
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
                    'purchase_order' => $data,

                ]
            ]);

        /**
         * Read the prescription back and verify the colors, dilutions, and numbers
         */

        $url = $this->makeUrl('/v1/patient/{patient_id}/prescription/' . $prescriptionId);

        $response = $this->getJsonTest($url);

        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'data' => [
                    'prescription' => [
                        'treatment_sets' => [
                            [
                                'vials' => [
                                    [
                                        'dilution' => 1,
                                        'color' => 'RED',
                                        'vial_number' => '1'
                                    ],
                                    [
                                        'dilution' => 5,
                                        'color' => 'YLW',
                                        'vial_number' => '2'
                                    ],
                                    [
                                        'dilution' => 25,
                                        'color' => 'BLUE',
                                        'vial_number' => '3'
                                    ],
                                    [
                                        'dilution' => 125,
                                        'color' => 'GRN',
                                        'vial_number' => '4'
                                    ],
                                    [
                                        'dilution' => 625,
                                        'color' => 'SLVR',
                                        'vial_number' => '5'
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ]);
    }

    /**
     * Create profile, prescription, and order - 8 vial 5 fold
     */

     public function test_purchase_order_8_vial_forward_5_fold()
    {
        $testString = '8_vial_forward_5_fold';

        $url = $this->makeUrl('/v1/provider/1/profile');

        $data = [
            'offset' => 0,
            'provider_id' => 1,
            'dilution_steps' => [
                [
                    'dilution' => 1,
                    'expiration' => '3',
                    'color_name' => 'RED',
                    'color' => '16711680'
                ],
                [
                    'dilution' => 5,
                    'expiration' => '3',
                    'color_name' => 'YLW',
                    'color' => '16776960'
                ],
                [
                    'dilution' => 25,
                    'expiration' => '3',
                    'color_name' => 'BLUE',
                    'color' => '255'
                ],
                [
                    'dilution' => 125,
                    'expiration' => '3',
                    'color_name' => 'GRN',
                    'color' => '32768'
                ],
                [
                    'dilution' => 625,
                    'expiration' => '3',
                    'color_name' => 'SLVR',
                    'color' => '12632256'
                ],
                [
                    'dilution' => 3125,
                    'expiration' => '3',
                    'color_name' => 'PRPL',
                    'color' => '8388736'
                ],
                [
                    'dilution' => 15625,
                    'expiration' => '3',
                    'color_name' => 'ORNG',
                    'color' => '16753920'
                ],
                [
                    'dilution' => 78125,
                    'expiration' => '3',
                    'color_name' => 'GOLD',
                    'color' => '12607488'
                ]
            ],
            'bottle_numbering_order' => 'ascending_dilution',
            'name' => $testString,
            'low_glycerin_limit' => '20.00',
            'high_glycerin_limit' => '50.00',
            'default_vial_size' => '5 mL',
            'include_diluent_name' => 'F',
            'preferred_glycerin_diluent_id' => 1,
            'preferred_aqueous_diluent_id' => 3
        ];

        $response = $this->postJsonTest($url, $data);

        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'data' => [
                    'profile' => $data
                ]
            ]);

        $arr = $response->json();
        $profile = $arr['data']['profile'];
        $profileId = $profile['profile_id'];

        $this->assertTrue($profileId !== 0);

        /**
         * Create prescription using new profile
         */

        $url = $this->makeUrl('/v1/patient/{patient_id}/prescription');

        $data =  [
            'name' => $testString,
            'note' => 'Note for ' . $testString,
            'multiplier' => 5,
            'priority' => '0',
            'clinic_id' => 2,
            'provider_id' => 1,
            'diagnosis_id' => 4,
            'profile_id' => $profileId,
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

        $response = $this->postJsonTest($url, $data);

        $arr = $response->json();
        $prescription = $arr['data']['prescription'];
        $prescriptionId = $prescription['prescription_id'];

        /**
         * Order the prescription
         */

        $url = $this->makeUrl('/v1/patient/{patient_id}/purchase_order');

        $data =  [
            'account_id' => 1,
            'status_id' => 1,
            'set_orders' => [
                [
                    'name' => 'from profile/prescription test ' . $testString,
                    'note' => 'Note for New PO Vial B',
                    'provider_id' => 1,
                    'prescription_id' => $prescriptionId,
                    'clinic_id' => 2,
                    'status_id' => 1,
                    'size' => '5 mL',
                    'dilutions' => [
                        1,
                        5,
                        25,
                        125,
                        625,
                        3125,
                        15625,
                        78125
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
                            'extract_id' => 7
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
                    'purchase_order' => $data,

                ]
            ]);

        /**
         * Read the prescription back and verify the colors, dilutions, and numbers
         */

        $url = $this->makeUrl('/v1/patient/{patient_id}/prescription/' . $prescriptionId);

        $response = $this->getJsonTest($url);

        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'data' => [
                    'prescription' => [
                        'treatment_sets' => [
                            [
                                'vials' => [
                                    [
                                        'dilution' => 1,
                                        'color' => 'RED',
                                        'vial_number' => '1'
                                    ],
                                    [
                                        'dilution' => 5,
                                        'color' => 'YLW',
                                        'vial_number' => '2'
                                    ],
                                    [
                                        'dilution' => 25,
                                        'color' => 'BLUE',
                                        'vial_number' => '3'
                                    ],
                                    [
                                        'dilution' => 125,
                                        'color' => 'GRN',
                                        'vial_number' => '4'
                                    ],
                                    [
                                        'dilution' => 625,
                                        'color' => 'SLVR',
                                        'vial_number' => '5'
                                    ],
                                    [
                                        'dilution' => 3125,
                                        'color' => 'PRPL',
                                        'vial_number' => '6'
                                    ],
                                    [
                                        'dilution' => 15625,
                                        'color' => 'ORNG',
                                        'vial_number' => '7'
                                    ],
                                    [
                                        'dilution' => 78125,
                                        'color' => 'GOLD',
                                        'vial_number' => '8'
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ]);
    }

    /**
     * Create profile, prescription, and order - 8 vial 5 fold reverse
     */

     public function test_purchase_order_8_vial_reverse_5_fold()
    {
        $testString = '8_vial_reverse_5_fold';

        $url = $this->makeUrl('/v1/provider/1/profile');

        $data = [
            'offset' => 0,
            'provider_id' => 1,
            'dilution_steps' => [
                [
                    'dilution' => 78125,
                    'expiration' => '3',
                    'color_name' => 'GOLD',
                    'color' => '12607488'
                ],
                [
                    'dilution' => 15625,
                    'expiration' => '3',
                    'color_name' => 'ORNG',
                    'color' => '16753920'
                ],
                [
                    'dilution' => 3125,
                    'expiration' => '3',
                    'color_name' => 'PRPL',
                    'color' => '8388736'
                ],
                [
                    'dilution' => 625,
                    'expiration' => '3',
                    'color_name' => 'SLVR',
                    'color' => '12632256'
                ],
                [
                    'dilution' => 125,
                    'expiration' => '3',
                    'color_name' => 'GRN',
                    'color' => '32768'
                ],
                [
                    'dilution' => 25,
                    'expiration' => '3',
                    'color_name' => 'BLUE',
                    'color' => '255'
                ],
                [
                    'dilution' => 5,
                    'expiration' => '3',
                    'color_name' => 'YLW',
                    'color' => '16776960'
                ],
                [
                    'dilution' => 1,
                    'expiration' => '3',
                    'color_name' => 'RED',
                    'color' => '16711680'
                ]
            ],
            'bottle_numbering_order' => 'descending_dilution',
            'name' => $testString,
            'low_glycerin_limit' => '20.00',
            'high_glycerin_limit' => '50.00',
            'default_vial_size' => '5 mL',
            'include_diluent_name' => 'F',
            'preferred_glycerin_diluent_id' => 1,
            'preferred_aqueous_diluent_id' => 3
        ];

        $response = $this->postJsonTest($url, $data);

        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'data' => [
                    'profile' => $data
                ]
            ]);

        $arr = $response->json();
        $profile = $arr['data']['profile'];
        $profileId = $profile['profile_id'];

        $this->assertTrue($profileId !== 0);

        /**
         * Create prescription using new profile
         */

        $url = $this->makeUrl('/v1/patient/{patient_id}/prescription');

        $data =  [
            'name' => $testString,
            'note' => 'Note for ' . $testString,
            'multiplier' => 5,
            'priority' => '0',
            'clinic_id' => 2,
            'provider_id' => 1,
            'diagnosis_id' => 4,
            'profile_id' => $profileId,
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

        $response = $this->postJsonTest($url, $data);

        $arr = $response->json();
        $prescription = $arr['data']['prescription'];
        $prescriptionId = $prescription['prescription_id'];

        /**
         * Order the prescription
         */

        $url = $this->makeUrl('/v1/patient/{patient_id}/purchase_order');

        $data =  [
            'account_id' => 1,
            'status_id' => 1,
            'set_orders' => [
                [
                    'name' => 'from profile/prescription test ' . $testString,
                    'note' => 'Note for New PO Vial B',
                    'provider_id' => 1,
                    'prescription_id' => $prescriptionId,
                    'clinic_id' => 2,
                    'status_id' => 1,
                    'size' => '5 mL',
                    'dilutions' => [
                        1,
                        5,
                        25,
                        125,
                        625,
                        3125,
                        15625,
                        78125
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
                            'extract_id' => 7
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
                    'purchase_order' => $data,

                ]
            ]);

        /**
         * Read the prescription back and verify the colors, dilutions, and numbers
         */

        $url = $this->makeUrl('/v1/patient/{patient_id}/prescription/' . $prescriptionId);

        $response = $this->getJsonTest($url);

        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'data' => [
                    'prescription' => [
                        'treatment_sets' => [
                            [
                                'vials' => [
                                    [
                                        'dilution' => 78125,
                                        'color' => 'GOLD',
                                        'vial_number' => '1'
                                    ],
                                    [
                                        'dilution' => 15625,
                                        'color' => 'ORNG',
                                        'vial_number' => '2'
                                    ],
                                    [
                                        'dilution' => 3125,
                                        'color' => 'PRPL',
                                        'vial_number' => '3'
                                    ],
                                    [
                                        'dilution' => 625,
                                        'color' => 'SLVR',
                                        'vial_number' => '4'
                                    ],
                                    [
                                        'dilution' => 125,
                                        'color' => 'GRN',
                                        'vial_number' => '5'
                                    ],
                                    [
                                        'dilution' => 25,
                                        'color' => 'BLUE',
                                        'vial_number' => '6'
                                    ],
                                    [
                                        'dilution' => 5,
                                        'color' => 'YLW',
                                        'vial_number' => '7'
                                    ],
                                    [
                                        'dilution' => 1,
                                        'color' => 'RED',
                                        'vial_number' => '8'
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ]);
    }

    /**
     * Create profile, prescription, and order - 8 vial 5 fold reverse -1
     */

     public function test_purchase_order_8_vial_reverse_5_fold_neg_one()
    {
        $testString = '8_vial_reverse_5_fold_neg1';

        $url = $this->makeUrl('/v1/provider/1/profile');

        $data = [
            'offset' => -1,
            'provider_id' => 1,
            'dilution_steps' => [
                [
                    'dilution' => 78125,
                    'expiration' => '3',
                    'color_name' => 'GOLD',
                    'color' => '12607488'
                ],
                [
                    'dilution' => 15625,
                    'expiration' => '3',
                    'color_name' => 'ORNG',
                    'color' => '16753920'
                ],
                [
                    'dilution' => 3125,
                    'expiration' => '3',
                    'color_name' => 'PRPL',
                    'color' => '8388736'
                ],
                [
                    'dilution' => 625,
                    'expiration' => '3',
                    'color_name' => 'SLVR',
                    'color' => '12632256'
                ],
                [
                    'dilution' => 125,
                    'expiration' => '3',
                    'color_name' => 'GRN',
                    'color' => '32768'
                ],
                [
                    'dilution' => 25,
                    'expiration' => '3',
                    'color_name' => 'BLUE',
                    'color' => '255'
                ],
                [
                    'dilution' => 5,
                    'expiration' => '3',
                    'color_name' => 'YLW',
                    'color' => '16776960'
                ],
                [
                    'dilution' => 1,
                    'expiration' => '3',
                    'color_name' => 'RED',
                    'color' => '16711680'
                ]
            ],
            'bottle_numbering_order' => 'descending_dilution',
            'name' => $testString,
            'low_glycerin_limit' => '20.00',
            'high_glycerin_limit' => '50.00',
            'default_vial_size' => '5 mL',
            'include_diluent_name' => 'F',
            'preferred_glycerin_diluent_id' => 1,
            'preferred_aqueous_diluent_id' => 3
        ];

        $response = $this->postJsonTest($url, $data);

        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'data' => [
                    'profile' => $data
                ]
            ]);

        $arr = $response->json();
        $profile = $arr['data']['profile'];
        $profileId = $profile['profile_id'];

        $this->assertTrue($profileId !== 0);

        /**
         * Create prescription using new profile
         */

        $url = $this->makeUrl('/v1/patient/{patient_id}/prescription');

        $data =  [
            'name' => $testString,
            'note' => 'Note for ' . $testString,
            'multiplier' => 5,
            'priority' => '0',
            'clinic_id' => 2,
            'provider_id' => 1,
            'diagnosis_id' => 4,
            'profile_id' => $profileId,
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

        $response = $this->postJsonTest($url, $data);

        $arr = $response->json();
        $prescription = $arr['data']['prescription'];
        $prescriptionId = $prescription['prescription_id'];

        /**
         * Order the prescription
         */

        $url = $this->makeUrl('/v1/patient/{patient_id}/purchase_order');

        $data =  [
            'account_id' => 1,
            'status_id' => 1,
            'set_orders' => [
                [
                    'name' => 'from profile/prescription test ' . $testString,
                    'note' => 'Note for New PO Vial B',
                    'provider_id' => 1,
                    'prescription_id' => $prescriptionId,
                    'clinic_id' => 2,
                    'status_id' => 1,
                    'size' => '5 mL',
                    'dilutions' => [
                        1,
                        5,
                        25,
                        125,
                        625,
                        3125,
                        15625,
                        78125
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
                            'extract_id' => 7
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
                    'purchase_order' => $data,

                ]
            ]);

        /**
         * Read the prescription back and verify the colors, dilutions, and numbers
         */

        $url = $this->makeUrl('/v1/patient/{patient_id}/prescription/' . $prescriptionId);

        $response = $this->getJsonTest($url);

        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'data' => [
                    'prescription' => [
                        'treatment_sets' => [
                            [
                                'vials' => [
                                    [
                                        'dilution' => 78125,
                                        'color' => 'GOLD',
                                        'vial_number' => '0'
                                    ],
                                    [
                                        'dilution' => 15625,
                                        'color' => 'ORNG',
                                        'vial_number' => '1'
                                    ],
                                    [
                                        'dilution' => 3125,
                                        'color' => 'PRPL',
                                        'vial_number' => '2'
                                    ],
                                    [
                                        'dilution' => 625,
                                        'color' => 'SLVR',
                                        'vial_number' => '3'
                                    ],
                                    [
                                        'dilution' => 125,
                                        'color' => 'GRN',
                                        'vial_number' => '4'
                                    ],
                                    [
                                        'dilution' => 25,
                                        'color' => 'BLUE',
                                        'vial_number' => '5'
                                    ],
                                    [
                                        'dilution' => 5,
                                        'color' => 'YLW',
                                        'vial_number' => '6'
                                    ],
                                    [
                                        'dilution' => 1,
                                        'color' => 'RED',
                                        'vial_number' => '7'
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ]);
    }

    /**
     * Create profile, prescription, and order - 8 vial 5 fold -1
     */

     public function test_purchase_order_8_vial_forward_5_fold_neg_one()
    {
        $testString = '8_vial_forward_5_fold_neg1';

        $url = $this->makeUrl('/v1/provider/1/profile');

        $data = [
            'offset' => -1,
            'provider_id' => 1,
            'dilution_steps' => [
                [
                    'dilution' => 1,
                    'expiration' => '3',
                    'color_name' => 'RED',
                    'color' => '16711680'
                ],
                [
                    'dilution' => 5,
                    'expiration' => '3',
                    'color_name' => 'YLW',
                    'color' => '16776960'
                ],
                [
                    'dilution' => 25,
                    'expiration' => '3',
                    'color_name' => 'BLUE',
                    'color' => '255'
                ],
                [
                    'dilution' => 125,
                    'expiration' => '3',
                    'color_name' => 'GRN',
                    'color' => '32768'
                ],
                [
                    'dilution' => 625,
                    'expiration' => '3',
                    'color_name' => 'SLVR',
                    'color' => '12632256'
                ],
                [
                    'dilution' => 3125,
                    'expiration' => '3',
                    'color_name' => 'PRPL',
                    'color' => '8388736'
                ],
                [
                    'dilution' => 15625,
                    'expiration' => '3',
                    'color_name' => 'ORNG',
                    'color' => '16753920'
                ],
                [
                    'dilution' => 78125,
                    'expiration' => '3',
                    'color_name' => 'GOLD',
                    'color' => '12607488'
                ]
            ],
            'bottle_numbering_order' => 'ascending_dilution',
            'name' => $testString,
            'low_glycerin_limit' => '20.00',
            'high_glycerin_limit' => '50.00',
            'default_vial_size' => '5 mL',
            'include_diluent_name' => 'F',
            'preferred_glycerin_diluent_id' => 1,
            'preferred_aqueous_diluent_id' => 3
        ];

        $response = $this->postJsonTest($url, $data);

        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'data' => [
                    'profile' => $data
                ]
            ]);

        $arr = $response->json();
        $profile = $arr['data']['profile'];
        $profileId = $profile['profile_id'];

        $this->assertTrue($profileId !== 0);

        /**
         * Create prescription using new profile
         */

        $url = $this->makeUrl('/v1/patient/{patient_id}/prescription');

        $data =  [
            'name' => $testString,
            'note' => 'Note for ' . $testString,
            'multiplier' => 5,
            'priority' => '0',
            'clinic_id' => 2,
            'provider_id' => 1,
            'diagnosis_id' => 4,
            'profile_id' => $profileId,
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

        $response = $this->postJsonTest($url, $data);

        $arr = $response->json();
        $prescription = $arr['data']['prescription'];
        $prescriptionId = $prescription['prescription_id'];

        /**
         * Order the prescription
         */

        $url = $this->makeUrl('/v1/patient/{patient_id}/purchase_order');

        $data =  [
            'account_id' => 1,
            'status_id' => 1,
            'set_orders' => [
                [
                    'name' => 'from profile/prescription test ' . $testString,
                    'note' => 'Note for New PO Vial B',
                    'provider_id' => 1,
                    'prescription_id' => $prescriptionId,
                    'clinic_id' => 2,
                    'status_id' => 1,
                    'size' => '5 mL',
                    'dilutions' => [
                        1,
                        5,
                        25,
                        125,
                        625,
                        3125,
                        15625,
                        78125
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
                            'extract_id' => 7
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
                    'purchase_order' => $data,

                ]
            ]);

        /**
         * Read the prescription back and verify the colors, dilutions, and numbers
         */

        $url = $this->makeUrl('/v1/patient/{patient_id}/prescription/' . $prescriptionId);

        $response = $this->getJsonTest($url);

        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'data' => [
                    'prescription' => [
                        'treatment_sets' => [
                            [
                                'vials' => [
                                    [
                                        'dilution' => 1,
                                        'color' => 'RED',
                                        'vial_number' => '0'
                                    ],
                                    [
                                        'dilution' => 5,
                                        'color' => 'YLW',
                                        'vial_number' => '1'
                                    ],
                                    [
                                        'dilution' => 25,
                                        'color' => 'BLUE',
                                        'vial_number' => '2'
                                    ],
                                    [
                                        'dilution' => 125,
                                        'color' => 'GRN',
                                        'vial_number' => '3'
                                    ],
                                    [
                                        'dilution' => 625,
                                        'color' => 'SLVR',
                                        'vial_number' => '4'
                                    ],
                                    [
                                        'dilution' => 3125,
                                        'color' => 'PRPL',
                                        'vial_number' => '5'
                                    ],
                                    [
                                        'dilution' => 15625,
                                        'color' => 'ORNG',
                                        'vial_number' => '6'
                                    ],
                                    [
                                        'dilution' => 78125,
                                        'color' => 'GOLD',
                                        'vial_number' => '7'
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ]);
    }

}
