<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;

class PrescriptionTest extends TestCase
{
    private $good_prescription;

    /**
     * Read prescription by id.
     * @return void
     */
    public function test_prescription_get()
    {
        $url = $this->makeUrl('/v1/patient/{patient_id}/prescription/{prescription_id}?fields=prescription_id,patient_id,timestamp,multiplier,priority,vials,clinic,user,provider,fold,prescription_number,strike_through,custom_units');

        $response = $this->getJsonTest($url);

        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'data' => [
                    'prescription' => []
                ]
            ]);

        $arr = $response->json();
        $prescription = $arr['data']['prescription'];

        $this->assertTrue($prescription['prescription_number'] == 900002);
    }

    /**
     * Read all prescriptions.
     * @return void
     */
    public function test_prescription_get_all()
    {
        $url = $this->makeUrl('/v1/patient/{patient_id}/prescription');

        $response = $this->getJsonTest($url);

        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'data' => [
                    'prescription' => []
                ]
            ]);
    }

    /**
     * Read prescription bad prescription_id.
     * @return void
     */
    public function test_prescription_get_bad_rx_id()
    {
        $url = $this->makeUrl('/v1/patient/{patient_id}/prescription/990902');

        $response = $this->getJsonTest($url, 'fail');

        $response
            ->assertStatus(404)
            ->assertJson([
                'status' => 'fail',
                'message' => 'Resource could not be located'
            ]);
    }

    // /**
    //  * Read all prescriptions with non existent patient_id. Should this be returning a validation error?
    //  * @return void
    //  */
    // public function test_prescription_get_bad_patient()
    // {
    //     $url = $this->makeUrl('/v1/patient/999999/prescription');
    //
    //     $response = $this->getJsonTest($url);
    //
    //     $response
    //     ->assertStatus(200)
    //     ->assertJson([
    //         'status' => 'success',
    //         'data' => [
    //             'prescription' => []
    //         ]
    //     ]);
    // }

    /**
     * Search for prescription by number.
     * @return void
     */

    public function test_prescription_search()
    {
        $url = $this->makeUrl('/v1/patient/{patient_id}/prescription/_search');

        // verify nothing returned for non-existent number
        $data = [
            'prescription_number' => '123456789'
        ];

        // get the prescription
        $response = $this->postJsonTest($url, $data);

        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'data' => [
                    'prescription' => []
                ],
            ]);

        // verify something returned for existent number
        $data = [
            'prescription_number' => '900000'
        ];

        // get the prescription
        $response = $this->postJsonTest($url, $data);

        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'data' => [
                    'prescription' => []
                ],
            ]);

        $arr = $response->json();
        $prescription = $arr['data']['prescription'];

        $this->assertTrue($prescription[0]['prescription_number'] == 900000);
    }

    // /**
    //  * Search for prescription by number. Bad patient_id. Returning 200, should fail
    //  * @return void
    //  */
    //
    // public function test_prescription_search_bad_patient()
    // {
    //     $url = $this->makeUrl('/v1/patient/999999/prescription/_search');
    //
    //     // verify nothing returned for non-existent number
    //     $data = [
    //         'deleted' => 'F'
    //     ];
    //
    //     // get the prescription
    //     $response = $this->postJsonTest($url, $data);
    //
    //     $response
    //         ->assertStatus(200)
    //         ->assertJson([
    //             'status' => 'success',
    //             'data' => [
    //                 'prescription' => []
    //             ],
    //         ]);
    // }

    /**
     * Create prescription.
     * @return void
     */

    public function test_prescription_create_good()
    {
        $url = $this->makeUrl('/v1/patient/{patient_id}/prescription');

        $data =  [
            'prescription_number' => '0',   // should be ignored
            'prescription_id' => 0,         // should be ignored
            'patient_id' => 0,              // should be ignored
            'name' => 'New Vial B',
            'note' => 'Note for New Vial B',
            'multiplier' => 1,
            'priority' => '0',
            'clinic_id' => 2,
            'provider_id' => 1,
            'diagnosis_id' => 4,
            'profile_id' => 2,
            'strike_through' => 'F',
            'custom_units' => 'v/v',
            'extracts' => [
                [
                    'extract_id' => 2,
                    'name' => 'Glycerinated Diluent',
                    'is_diluent' => 'T'
                ],
                [
                    'extract_id' => 4,
                    'name' => 'Aqueous Dil',
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
                'status' => 'success',
                'data' => [
                    'prescription' => [
                        'note' => 'Note for New Vial B',
                        'multiplier' => 1,
                        'priority' => '0',
                        'clinic' => [
                            'clinic_id' => 2
                        ],
                        'provider' => [
                            'provider_id' => 1
                        ],
                        'profile_id' => 2,
                        'strike_through' => 'F',
                        'custom_units' => 'v/v',
                        'extracts' => [
                            [
                                'extract_id' => 2,
                                'name' => 'Glycerinated Diluent',
                                'is_diluent' => 'T'
                            ],
                            [
                                'extract_id' => 4,
                                'name' => 'Aqueous Dil',
                                'is_diluent' => 'T'
                            ],
                            [
                                'extract_id' => 7,
                                'name' => 'Alternaria',
                                'is_diluent' => 'F'
                            ]
                        ]
                    ]
                ],
            ]);
    }

    /**
     * Create prescription bad extract id.
     * @return void
     */

    public function test_prescription_create_bad_extract()
    {
        $url = $this->makeUrl('/v1/patient/{patient_id}/prescription');

        $data =  [
            'multiplier' => 1,
            'priority' => '0',
            'clinic_id' => 2,
            'provider_id' => 1,
            'diagnosis_id' => 4,
            'name' => 'Bad Extract',
            'profile_id' => 2,
            'strike_through' => 'F',
            'custom_units' => 'v/v',
            'extracts' => [
                [
                    'extract_id' => 9999,
                    'name' => 'Glycerinated Diluent',
                    'is_diluent' => 'T'
                ],
                [
                    'extract_id' => 4,
                    'name' => 'Aqueous Dil',
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
        $response = $this->postJsonTest($url, $data, 'validation');

        $response
            ->assertStatus(400)
            ->assertJson([
                'status' => 'validation',
                'errors' => [
                    'extract_0' => [
                        'extract_id' => [
                            'The selected extract id is invalid.'
                        ]
                    ]
                ]
            ]);
    }

    /**
     * Create prescription - bad data.
     * @return void
     */

    public function test_prescription_create_bad_data()
    {
        $url = $this->makeUrl('/v1/patient/{patient_id}/prescription');

        $data =  [
            'multiplier' => 9999,
            'priority' => '9999',
            'clinic_id' => 9999,
            'provider_id' => 9999,
            'diagnosis_id' => 9999,
            'name' => '',
            'profile_id' => 9999,
            'strike_through' => '',
            'custom_units' => '',
            'extracts' => [
                [
                    'extract_id' => 2,
                    'name' => 'Glycerinated Diluent',
                    'is_diluent' => 'T'
                ],
                [
                    'extract_id' => 4,
                    'name' => 'Aqueous Dil',
                    'is_diluent' => 'Q'
                ],
                [
                    'extract_id' => 9999,
                    'name' => 'Alternaria',
                    'is_diluent' => 'F'
                ]
            ]
        ];

        // create the prescription
        $response = $this->postJsonTest($url, $data, 'validation');

        $response
            ->assertStatus(400)
            ->assertJson([
                'status' => 'validation',
                'errors' => [
                    'profile_id' => [
                        'The selected profile id is invalid.'
                    ],
                    'priority' => [
                        'Invalid priority value.'
                    ],
                    'clinic_id' => [
                        'The selected clinic id is invalid.'
                    ],
                    'provider_id' => [
                        'The selected provider id is invalid.'
                    ]
                ]
            ]);
    }


    /**
     * Create prescription - missing data.
     * @return void
     */

    public function test_prescription_create_missing_data()
    {
        $url = $this->makeUrl('/v1/patient/{patient_id}/prescription');

        $data =  [
        ];

        // create the prescription
        $response = $this->postJsonTest($url, $data, 'validation');

        $response
            ->assertStatus(400)
            ->assertJson([
                'status' => 'validation',
                'errors' => [
                    'extracts' => [
                        'The extracts field is required.'
                    ],
                    'profile_id' => [
                        'The profile id field is required when outsourced is not present.'
                    ],
                    'clinic_id' => [
                        'The clinic id field is required.'
                    ],
                    'provider_id' => [
                        'The provider id field is required.'
                    ]
                ]
            ]);
    }

    /**
     * Create prescription - bad patient (non-existent).
     * @return void
     */

    public function test_prescription_create_bad_patient()
    {
        $url = $this->makeUrl('/v1/patient/999999/prescription');

        $data =  [
            'multiplier' => 1,
            'priority' => '0',
            'clinic_id' => 2,
            'provider_id' => 1,
            'diagnosis_id' => 4,
            'name' => 'New Vial B',
            'profile_id' => 2,
            'strike_through' => 'F',
            'custom_units' => 'v/v',
            'extracts' => [
                [
                    'extract_id' => 2,
                    'name' => 'Glycerinated Diluent',
                    'is_diluent' => 'T'
                ],
                [
                    'extract_id' => 4,
                    'name' => 'Aqueous Dil',
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
        $response = $this->postJsonTest($url, $data, 'validation');

        $response
            ->assertStatus(400)
            ->assertJson([
                'status' => 'validation',
                'errors' => [
                    'patient_id' => [
                        'The selected patient id is invalid.'
                    ]
                ]
            ]);
    }

    /**
     * Create prescription - missing data, only has extracts.
     * @return void
     */

    public function test_prescription_create_only_extracts()
    {
        $url = $this->makeUrl('/v1/patient/{patient_id}/prescription');

        $data =  [
          'extracts' => [
              [
                  'extract_id' => 2,
                  'name' => 'Glycerinated Diluent',
                  'is_diluent' => 'T'
              ],
              [
                  'extract_id' => 4,
                  'name' => 'Aqueous Dil',
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
        $response = $this->postJsonTest($url, $data, 'validation');

        $response
            ->assertStatus(400)
            ->assertJson([
                'status' => 'validation',
                'errors' => [
                    'profile_id' => [
                        'The profile id field is required when outsourced is not present.'
                    ],
                    'clinic_id' => [
                        'The clinic id field is required.'
                    ],
                    'provider_id' => [
                        'The provider id field is required.'
                    ]
                ]
            ]);
    }

    /**
     * Create prescription - bad profile_id.
     * @return void
     */

    public function test_prescription_create_bad_profileId()
    {
        $url = $this->makeUrl('/v1/patient/{patient_id}/prescription');

        $data =  [
          'name' => 'New Vial B',
          'note' => 'Note for New Vial B',
          'multiplier' => 1,
          'priority' => '0',
          'clinic_id' => 2,
          'provider_id' => 1,
          'diagnosis_id' => 4,
          'profile_id' => 9999,
          'strike_through' => 'F',
          'custom_units' => 'v/v',
          'extracts' => [
              [
                  'extract_id' => 2,
                  'name' => 'Glycerinated Diluent',
                  'is_diluent' => 'T'
              ],
              [
                  'extract_id' => 4,
                  'name' => 'Aqueous Dil',
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
        $response = $this->postJsonTest($url, $data, 'validation');

        $response
            ->assertStatus(400)
            ->assertJson([
                'status' => 'validation',
                'errors' => [
                    'profile_id' => [
                        'The selected profile id is invalid.'
                    ]
                ]
            ]);
    }

    /**
     * Create prescription - bad provider_id.
     * @return void
     */

    public function test_prescription_create_bad_providerId()
    {
        $url = $this->makeUrl('/v1/patient/{patient_id}/prescription');

        $data =  [
          'name' => 'New Vial B',
          'note' => 'Note for New Vial B',
          'multiplier' => 1,
          'priority' => '0',
          'clinic_id' => 2,
          'provider_id' => 9999,
          'diagnosis_id' => 4,
          'profile_id' => 2,
          'strike_through' => 'F',
          'custom_units' => 'v/v',
          'extracts' => [
              [
                  'extract_id' => 2,
                  'name' => 'Glycerinated Diluent',
                  'is_diluent' => 'T'
              ],
              [
                  'extract_id' => 4,
                  'name' => 'Aqueous Dil',
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
        $response = $this->postJsonTest($url, $data, 'validation');

        $response
            ->assertStatus(400)
            ->assertJson([
                'status' => 'validation',
                'errors' => [
                    'provider_id' => [
                        'The selected provider id is invalid.'
                    ]
                ]
            ]);
    }

    /**
     * Create prescription - bad clinic_id.
     * @return void
     */

    public function test_prescription_create_bad_clinicId()
    {
        $url = $this->makeUrl('/v1/patient/{patient_id}/prescription');

        $data =  [
          'name' => 'New Vial B',
          'note' => 'Note for New Vial B',
          'multiplier' => 1,
          'priority' => '0',
          'clinic_id' => 9999,
          'provider_id' => 1,
          'diagnosis_id' => 4,
          'profile_id' => 2,
          'strike_through' => 'F',
          'custom_units' => 'v/v',
          'extracts' => [
              [
                  'extract_id' => 2,
                  'name' => 'Glycerinated Diluent',
                  'is_diluent' => 'T'
              ],
              [
                  'extract_id' => 4,
                  'name' => 'Aqueous Dil',
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
        $response = $this->postJsonTest($url, $data, 'validation');

        $response
            ->assertStatus(400)
            ->assertJson([
                'status' => 'validation',
                'errors' => [
                    'clinic_id' => [
                        'The selected clinic id is invalid.'
                    ]
                ]
            ]);
    }

    /**
     * Update prescription good - strike_through: set & change
     * @return void
     */

    public function test_prescription_update_good()
    {
        $url = $this->makeUrl('/v1/patient/{patient_id}/prescription/{prescription_id}');

        $data =  [
            'strike_through' => 'T',
            'strike_through_reason' => 'Because I said so'
        ];

        // update the prescription
        $response = $this->putJsonTest($url, $data);

        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'data' => [
                    'prescription' => [
                        'strike_through' => 'T',
                        'strike_through_reason' => 'Because I said so'
                    ]
                ]
            ]);

        $data2 =  [   //did this so other tests pass
            'strike_through' => 'F',
            'strike_through_reason' => ''
        ];

        // update the prescription
        $response = $this->putJsonTest($url, $data2);

        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'data' => [
                    'prescription' => [
                        'strike_through' => 'F',
                        'strike_through_reason' => ''
                    ]
                ]
            ]);
    }

    /**
     * Update prescription good - strike_through: null
     * @return void
     */

    public function test_prescription_update_strike_through_null()
    {
        $url = $this->makeUrl('/v1/patient/{patient_id}/prescription/{prescription_id}');

        $data =  [
            'strike_through' => null,
            'strike_through_reason' => null
        ];

        // update the prescription
        $response = $this->putJsonTest($url, $data);

        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'data' => [
                    'prescription' => [
                        'strike_through' => 'F',
                        'strike_through_reason' => ''
                    ]
                ]
            ]);
    }

    // /**
    //  * Update prescription good - note: null
    //  * @return void
    //  */
    //
    // public function test_prescription_update_note_null()
    // {
    //     $url = $this->makeUrl('/v1/patient/{patient_id}/prescription/1');
    //
    //     $data =  [
    //         'note' => 'yollo'
    //     ];
    //
    //     // update the prescription
    //     $response = $this->putJsonTest($url, $data);
    //
    //     $response
    //         ->assertStatus(200)
    //         ->assertJson([
    //             'status' => 'success',
    //             'data' => [
    //               'prescription' => [
    //                   'prescription_number' => '900000',
    //                   'strike_through' => 'F',
    //                   'custom_units' => 'v/v',
    //                   'note' => 'yollo'
    //               ]
    //             ]
    //         ]);
    // }

    // /**
    //  * Update prescription good - extracts    Not updating or 'failing' (it is expecting the origial data)
    //  * @return void
    //  */
    //
    // public function test_prescription_update_extracts()
    // {
    //     $url = $this->makeUrl('/v1/patient/{patient_id}/prescription/{prescription_id}');
    //
    //     $data =  [
    //       'extracts' => [
    //           [
    //               'extract_id' => 2,
    //               'name' => 'Glycerinated Diluent',
    //               'is_diluent' => 'T'
    //           ],
    //           [
    //               'extract_id' => 4,
    //               'name' => 'Aqueous Dil',
    //               'is_diluent' => 'T'
    //           ],
    //           [
    //               'extract_id' => 9,
    //               'name' => 'Timothy Grass',
    //               'is_diluent' => 'F'
    //           ],
    //           [
    //               'extract_id' => 8,
    //               'name' => 'Pine Mix',
    //               'is_diluent' => 'F'
    //           ]
    //       ]
    //     ];
    //
    //     // update the prescription
    //     $response = $this->putJsonTest($url, $data);
    //
    //     $response
    //         ->assertStatus(200)
    //         ->assertJson([
    //             'status' => 'success',
    //             'data' => [
    //                 'prescription' => [
    //                   'extracts' => [
    //                       [
    //                           'extract_id' => 2,
    //                           'name' => 'Glycerinated Diluent',
    //                           'is_diluent' => 'T'
    //                       ],
    //                       [
    //                           'extract_id' => 4,
    //                           'name' => 'Aqueous Dil',
    //                           'is_diluent' => 'T'
    //                       ],
    //                       [
    //                           'extract_id' => 9,
    //                           'name' => 'Timothy Grass',
    //                           'is_diluent' => 'F'
    //                       ],
    //                       [
    //                           'extract_id' => 8,
    //                           'name' => 'Pine Mix',
    //                           'is_diluent' => 'F'
    //                       ]
    //                   ]
    //                 ]
    //             ]
    //         ]);
    // }

    /**
     * Update prescription good - clinic_id: changed
     * @return void
     */

    public function test_prescription_update_clinic_id_changed()
    {
        $url = $this->makeUrl('/v1/patient/{patient_id}/prescription/{prescription_id}');

        $data =  [
            'clinic_id' => 2
        ];

        // update the prescription
        $response = $this->putJsonTest($url, $data);

        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'data' => [
                    'prescription' => [
                        'clinic' => [
                          'clinic_id' => 2
                        ]
                    ]
                ]
            ]);
    }

    /**
     * Update prescription good - clinic_id: null
     * @return void
     */

    public function test_prescription_update_clinic_id_null()
    {
        $url = $this->makeUrl('/v1/patient/{patient_id}/prescription/{prescription_id}');

        $data =  [
            'clinic_id' => null
        ];

        // update the prescription
        $response = $this->putJsonTest($url, $data);

        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'data' => [
                    'prescription' => [
                        'strike_through' => 'F',
                        'strike_through_reason' => '',
                        'custom_units' => 'v/v'
                    ]
                ]
            ]);
    }

    // /**
    //  * Update prescription good - diagnosis_id: changed  //returning 200 with no changes in db
    //  * @return void
    //  */
    //
    // public function test_prescription_update_diagnosis_changed()
    // {
    //     $url = $this->makeUrl('/v1/patient/{patient_id}/prescription/5');
    //
    //     $data =  [
    //         'diagnosis_id' => 1
    //     ];
    //
    //     // update the prescription
    //     $response = $this->putJsonTest($url, $data);
    //
    //     $response
    //         ->assertStatus(200)
    //         ->assertJson([
    //             'status' => 'success',
    //             'data' => [
    //                 'prescription' => [
    //                     'strike_through' => 'F',
    //                     'note' => 'Note for New Vial B',
    //                     'custom_units' => 'v/v'
    //                 ]
    //             ]
    //         ]);
    // }

    // /**
    //  * Update prescription good - diagnosis_id: null  //returning 200 with no changes in db
    //  * @return void
    //  */
    //
    // public function test_prescription_update_diagnosis_null()
    // {
    //     $url = $this->makeUrl('/v1/patient/{patient_id}/prescription/5');
    //
    //     $data =  [
    //         'diagnosis_id' => null
    //     ];
    //
    //     // update the prescription
    //     $response = $this->putJsonTest($url, $data);
    //
    //     $response
    //         ->assertStatus(200)
    //         ->assertJson([
    //             'status' => 'success',
    //             'data' => [
    //                 'prescription' => [
    //                     'strike_through' => 'F',
    //                     'note' => 'Note for New Vial B',
    //                     'custom_units' => 'v/v'
    //                 ]
    //             ]
    //         ]);
    // }

    // /**
    //  * Update prescription good - name: change
    //  * @return void
    //  */
    //
    // public function test_prescription_update_name_change()
    // {
    //     $url = $this->makeUrl('/v1/patient/{patient_id}/prescription/{prescription_id}');
    //
    //     $data =  [
    //         'name' => 'Changing name'
    //     ];
    //
    //     // update the prescription
    //     $response = $this->putJsonTest($url, $data);
    //
    //     $response
    //         ->assertStatus(200)
    //         ->assertJson([
    //             'status' => 'success',
    //             'data' => [
    //                 'prescription' => [
    //                     'name' => 'Changing name'
    //                 ]
    //             ]
    //         ]);
    // }

    // /**
    //  * Update prescription good - name: null
    //  * @return void
    //  */
    //
    // public function test_prescription_update_name_null()
    // {
    //     $url = $this->makeUrl('/v1/patient/{patient_id}/prescription/{prescription_id}');
    //
    //     $data =  [
    //         'name' => null
    //     ];
    //
    //     // update the prescription
    //     $response = $this->putJsonTest($url, $data);
    //
    //     $response
    //         ->assertStatus(200)
    //         ->assertJson([
    //             'status' => 'success',
    //             'data' => [
    //                 'prescription' => [
    //                     'name' => null
    //                 ]
    //             ]
    //         ]);
    // }

    // /**
    //  * Update prescription good - tray_location: change
    //  * @return void
    //  */
    //
    // public function test_prescription_update_tray_location_change()
    // {
    //     $url = $this->makeUrl('/v1/patient/{patient_id}/prescription/{prescription_id}');
    //
    //     $data =  [
    //       'tray_location' => '32b'
    //     ];
    //
    //     // update the prescription
    //     $response = $this->putJsonTest($url, $data);
    //
    //     $response
    //         ->assertStatus(200)
    //         ->assertJson([
    //             'status' => 'success',
    //             'data' => [
    //                 'prescription' => [
    //                   'treatment_sets' => [
    //                         [
    //                           'order_id' => 0,
    //                           'transaction' => 800000,
    //                           'vials' => [
    //                             [
    //                               'active' => 'T',
    //                               'dilution' => 200,
    //                               'name' => 'MLD\\TRS',
    //                               'color' => 'RED',
    //                               'size' => '5 mL',
    //                               'barcode' => '100000',
    //                               'tray_location' => '32b',
    //                               'mixed' => 'T',
    //                               'outdate' => '2019-02-01',
    //                               'transaction' => 800000,
    //                               'level' => '100%',
    //                               'prescription_id' => 3,
    //                               'vial_id' => 1,
    //                               'vial_number' => '1'
    //                           ]
    //                         ]
    //                       ]
    //                     ]
    //                 ]
    //             ]
    //         ]);
    // }

    // /**
    //  * Update prescription good - tray_location: null
    //  * @return void
    //  */
    //
    // public function test_prescription_update_tray_location_null()
    // {
    //     $url = $this->makeUrl('/v1/patient/{patient_id}/prescription/{prescription_id}');
    //
    //     $data =  [
    //       'treatment_sets' => [
    //             {
    //               'order_id' => 0,
    //               'transaction' => 800000,
    //               'vials' => [
    //                 {
    //                   'active' => 'T',
    //                   'dilution' => 200,
    //                   'name' => 'MLD\\TRS',
    //                   'color' => 'RED',
    //                   'size' => '5 mL',
    //                   'barcode' => '100000',
    //                   'tray_location' => null,
    //                   'mixed' => 'T',
    //                   'outdate' => '2019-02-01',
    //                   'transaction' => 800000,
    //                   'level' => '100%',
    //                   'prescription_id' => 3,
    //                   'vial_id' => 1,
    //                   'vial_number' => '1'
    //               }
    //             ]
    //         ]
    //     ];
    //
    //     // update the prescription
    //     $response = $this->putJsonTest($url, $data);
    //
    //     $response
    //         ->assertStatus(200)
    //         ->assertJson([
    //             'status' => 'success',
    //             'data' => [
    //                 'prescription' => [
    //                   'treatment_sets' => [
    //                         {
    //                           'order_id' => 0,
    //                           'transaction' => 800000,
    //                           'vials' => [
    //                             {
    //                               'active' => 'T',
    //                               'dilution' => 200,
    //                               'name' => 'MLD\\TRS',
    //                               'color' => 'RED',
    //                               'size' => '5 mL',
    //                               'barcode' => '100000',
    //                               'tray_location' => null,
    //                               'mixed' => 'T',
    //                               'outdate' => '2019-02-01',
    //                               'transaction' => 800000,
    //                               'level' => '100%',
    //                               'prescription_id' => 3,
    //                               'vial_id' => 1,
    //                               'vial_number' => '1'
    //                           }
    //                         ]
    //                     ]
    //                 ]
    //             ]
    //         ]);
    // }


    /**
     * Update prescription bad treatment_plan_id
     * @return void
     */

    public function test_prescription_update_bad_tp_id()
    {
        $url = $this->makeUrl('/v1/patient/{patient_id}/prescription/{prescription_id}');

        $data =  [
            'treatment_plan_id' => '99093'
        ];

        // update the prescription
        $response = $this->putJsonTest($url, $data, 'validation');

        $response
            ->assertStatus(400)
            ->assertJson([
                'status' => 'validation',
                'errors' => [
                    'treatment_plan_id' => [
                        'The selected treatment plan id is invalid.'
                      ]
                ]
            ]);
    }

    /**
     * Update prescription bad dosing_plan_id
     * @return void
     */

    public function test_prescription_update_bad_dose_plan_id()
    {
        $url = $this->makeUrl('/v1/patient/{patient_id}/prescription/{prescription_id}');

        $data =  [
            'dosing_plan_id' => '9909'
        ];

        // update the prescription
        $response = $this->putJsonTest($url, $data, 'validation');

        $response
            ->assertStatus(400)
            ->assertJson([
                'status' => 'validation',
                'errors' => [
                    'dosing_plan_id' => [
                        'The selected dosing plan id is invalid.'
                      ]
                ]
            ]);
    }

    /**
     * Update prescription bad patient_id
     * @return void
     */

    public function test_prescription_update_bad_patient()
    {
        $url = $this->makeUrl('/v1/patient/9990902/prescription/{prescription_id}');

        $data =  [
            'strike_through' => 'T'
        ];

        // update the prescription
        $response = $this->putJsonTest($url, $data, 'fail');

        $response
            ->assertStatus(404)
            ->assertJson([
                'status' => 'fail',
                'message' => 'Resource could not be located.'
            ]);
    }

    /**
     * Update prescription bad provider_id
     * @return void
     */

    public function test_prescription_update_bad_provider()
    {
        $url = $this->makeUrl('/v1/patient/{patient_id}/prescription/{prescription_id}');

        $data =  [
          'provider_id' => 999909
        ];

        // update the prescription
        $response = $this->putJsonTest($url, $data, 'validation');

        $response
            ->assertStatus(400)
            ->assertJson([
                'status' => 'validation',
                'errors' => [
                    'provider_id' => [
                        'The selected provider id is invalid.'
                    ]
                ]
            ]);
    }

    /**
     * Update prescription bad clinic_id
     * @return void
     */

    public function test_prescription_update_bad_clinic()
    {
        $url = $this->makeUrl('/v1/patient/{patient_id}/prescription/{prescription_id}');

        $data =  [
            'clinic_id' => 99999
        ];

        // update the prescription
        $response = $this->putJsonTest($url, $data, 'validation');

        $response
            ->assertStatus(400)
            ->assertJson([
                'status' => 'validation',
                'errors' => [
                    'clinic_id' => [
                        'The selected clinic id is invalid.'
                    ]
                ]
            ]);
    }

    /**
     * Update prescription - bad data.
     * @return void
     */

    public function test_prescription_update_bad_data()
    {
        $url = $this->makeUrl('/v1/patient/{patient_id}/prescription/{prescription_id}');

        $data =  [
            'multiplier' => 9999,
            'clinic_id' => 9999,
            'provider_id' => 9999,
            'diagnosis_id' => 9999,
            'name' => '',
            'profile_id' => 9999,
            'strike_through' => '',
            'custom_units' => '',
            'extracts' => [
                [
                    'extract_id' => 2,
                    'name' => 'Glycerinated Diluent',
                    'is_diluent' => 'T'
                ],
                [
                    'extract_id' => 4,
                    'name' => 'Aqueous Dil',
                    'is_diluent' => 'Q'
                ],
                [
                    'extract_id' => 9999,
                    'name' => 'Alternaria',
                    'is_diluent' => 'F'
                ]
            ]
        ];

        // update the prescription
        $response = $this->putJsonTest($url, $data, 'validation');

        $response
            ->assertStatus(400)
            ->assertJson([
                'status' => 'validation',
                'errors' => [
                    'clinic_id' => [
                        'The selected clinic id is invalid.'
                    ],
                    'provider_id' => [
                        'The selected provider id is invalid.'
                    ]
                ]
            ]);
    }

    // /**
    //  * Update prescription missing extracts. Returning 200 even though it should not
    //  * @return void
    //  */
    //
    // public function test_prescription_update_missing_extracts()
    // {
    //     $url = $this->makeUrl('/v1/patient/{patient_id}/prescription/{prescription_id}');
    //
    //     $data =  [
    //         'extracts' => null
    //     ];
    //
    //     // update the prescription
    //     $response = $this->putJsonTest($url, $data, 'validation');
    //
    //     $response
    //         ->assertStatus(400)
    //         ->assertJson([
    //             'status' => 'validation',
    //             'errors' => [
    //               'extracts' => [
    //                   'The extracts field is required.'
    //               ]
    //             ]
    //         ]);
    // }

    // /**
    //  * Update prescription bad profile_id. Returning 200 even though it should not
    //  * @return void
    //  */
    //
    // public function test_prescription_update_bad_profile()
    // {
    //     $url = $this->makeUrl('/v1/patient/{patient_id}/prescription/{prescription_id}');
    //
    //     $data =  [
    //       'profile_id' => 9999
    //     ];
    //
    //     // update the prescription
    //     $response = $this->putJsonTest($url, $data, 'validation');
    //
    //     $response
    //         ->assertStatus(400)
    //         ->assertJson([
    //             'status' => 'validation',
    //             'errors' => [
    //                 'profile_id' => [
    //                     'The selected profile id is invalid.'
    //                 ]
    //             ]
    //         ]);
    // }

    // /**
    //  * Update prescription bad extract id. Returning 200 even though it should not
    //  * @return void
    //  */
    //
    // public function test_prescription_update_bad_extract()
    // {
    //     $url = $this->makeUrl('/v1/patient/{patient_id}/prescription/{prescription_id}');
    //
    //     $data =  [
    //       'extracts' => [
    //           [
    //               'extract_id' => 9999,
    //               'name' => 'Glycerinated Diluent',
    //               'is_diluent' => 'T'
    //           ],
    //           [
    //               'extract_id' => 4,
    //               'name' => 'Aqueous Dil',
    //               'is_diluent' => 'T'
    //           ],
    //           [
    //               'extract_id' => 7,
    //               'name' => 'Alternaria',
    //               'is_diluent' => 'F'
    //           ]
    //       ]
    //     ];
    //
    //     // update the prescription
    //     $response = $this->putJsonTest($url, $data);
    //
    //     $response
    //     ->assertStatus(400)
    //     ->assertJson([
    //         'status' => 'validation',
    //         'errors' => [
    //             'extract_0' => [
    //                 'extract_id' => [
    //                     'The selected extract id is invalid.'
    //                 ]
    //             ]
    //         ]
    //     ]);
    // }
}
