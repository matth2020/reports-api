<?php
/**
 * Created by PhpStorm.
 * User: Martin
 * Date: 2/7/2018
 * Time: 4:41 PM
 */

namespace Tests\Unit;

use Tests\TestCase;

class VialTest extends TestCase
{

    /**
     * Read all vials for patient, prescription
     */
    public function test_vial_get_all()
    {
        $url = $this->makeUrl('/v1/patient/{patient_id}/prescription/{prescription_id}/vial?fields=size,name,color,dilution,active,compound_receipt_id,created_at,user,barcode,mix_date,out_date,vial_id,vial_note,vial_number');

        $response = $this->getJsonTest($url);

        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'data' => [
                    'vial' => [
                        [
                            'size' => '5 mL',
                            'name' => 'MLD\\TRS',
                            'color' => 'RED',
                            'dilution' => '200',
                            'active' => 'T',
                            'compound_receipt_id' => 1,
                            'created_at' => '2018-01-13 13:50:45',
                            'user' => 'Xtract Admin',
                            'barcode' => '100000',
                            'mix_date' => '2018-02-01 00:00:00',
                            'out_date' => '2019-02-01',
                            'vial_id' => 1,
                            'vial_note' => '',
                            'vial_number' => '1'
                        ],
                        [
                            'size' => '5 mL',
                            'name' => 'MLD\\TRS',
                            'color' => 'YLW',
                            'dilution' => '100',
                            'active' => 'T',
                            'compound_receipt_id' => 1,
                            'created_at' => '2018-01-13 13:50:45',
                            'user' => 'Xtract Admin',
                            'barcode' => '100001',
                            'mix_date' => '2018-02-01 00:00:00',
                            'out_date' => '2018-11-01',
                            'vial_id' => 2,
                            'vial_note' => '',
                            'vial_number' => '2'
                        ],
                        [
                            'size' => '5 mL',
                            'name' => 'MLD\\TRS',
                            'color' => 'BLUE',
                            'dilution' => '10',
                            'active' => 'T',
                            'compound_receipt_id' => 1,
                            'created_at' => '2018-01-13 13:50:45',
                            'user' => 'Xtract Admin',
                            'barcode' => '100002',
                            'mix_date' => '2018-02-01 00:00:00',
                            'out_date' => '2018-08-01',
                            'vial_id' => 3,
                            'vial_note' => '',
                            'vial_number' => '3'
                        ],
                        [
                            'size' => '5 mL',
                            'name' => 'MLD\\TRS',
                            'color' => 'GRN',
                            'dilution' => '2',
                            'active' => 'T',
                            'compound_receipt_id' => 1,
                            'created_at' => '2018-01-13 13:50:45',
                            'user' => 'Xtract Admin',
                            'barcode' => '100003',
                            'mix_date' => '2018-02-01 00:00:00',
                            'out_date' => '2018-05-01',
                            'vial_id' => 4,
                            'vial_note' => '',
                            'vial_number' => '4'
                        ],
                        [
                            'size' => '5 mL',
                            'name' => 'MLD\\TRS',
                            'color' => 'SLVR',
                            'dilution' => '1',
                            'active' => 'F',
                            'compound_receipt_id' => 1,
                            'created_at' => '2018-01-13 13:50:45',
                            'user' => 'Xtract Admin',
                            'barcode' => '100004',
                            'mix_date' => '2018-02-01 00:00:00',
                            'out_date' => '2018-05-01',
                            'vial_id' => 5,
                            'vial_note' => '',
                            'vial_number' => '5'
                        ]
                    ]
                ]
            ]);
    }

    /**
     * Read one vial for patient, prescription
     */
    public function test_vial_get_one()
    {
        $url = $this->makeUrl('/v1/patient/{patient_id}/prescription/{prescription_id}/vial/{id}?fields=size,name,color,dilution,active,compound_receipt_id,created_at,user,barcode,mix_date,out_date,vial_id,vial_note,vial_number', 3);

        $response = $this->getJsonTest($url);

        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'data' => [
                    'vial' => [
                        'size' => '5 mL',
                        'name' => 'MLD\\TRS',
                        'color' => 'BLUE',
                        'dilution' => '10',
                        'active' => 'T',
                        'compound_receipt_id' => 1,
                        'created_at' => '2018-01-13 13:50:45',
                        'user' => 'Xtract Admin',
                        'barcode' => '100002',
                        'mix_date' => '2018-02-01 00:00:00',
                        'out_date' => '2018-08-01',
                        'vial_id' => 3,
                        'vial_note' => '',
                        'vial_number' => '3'
                    ]
                ]
            ]);
    }

    /**
     * Search for vials
     */
    public function test_vial_search()
    {
        $url = $this->makeUrl('/v1/patient/{patient_id}/prescription/{prescription_id}/vial/_search?fields=size,name,color,dilution,active,compound_receipt_id,created_at,user,barcode,mix_date,out_date,vial_id,vial_note,vial_number');

        $data = [
            'dilution' => 10,
            'color' => 'BLUE'
        ];

        $response = $this->postJsonTest($url, $data);

        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'data' => [
                    'vial' => [
                        [
                            'size' => '5 mL',
                            'name' => 'MLD\\TRS',
                            'color' => 'BLUE',
                            'dilution' => '10',
                            'active' => 'T',
                            'compound_receipt_id' => 1,
                            'created_at' => '2018-01-13 13:50:45',
                            'user' => 'Xtract Admin',
                            'barcode' => '100002',
                            'mix_date' => '2018-02-01 00:00:00',
                            'out_date' => '2018-08-01',
                            'vial_id' => 3,
                            'vial_note' => '',
                            'vial_number' => '3'
                        ]
                    ]
                ]
            ]);
    }

    /**
     * Update a vial
     */
    public function test_vial_update()
    {
        $url = $this->makeUrl('/v1/patient/{patient_id}/prescription/{prescription_id}/vial/{id}?fields=size,name,color,dilution,active,compound_receipt_id,created_at,user,barcode,mix_date,out_date,vial_id,vial_note,vial_number', 3);

        $data = [
            'active' => 'F'
        ];

        $response = $this->putJsonTest($url, $data);

        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'data' => [
                    'vial' => [
                        'size' => '5 mL',
                        'name' => 'MLD\\TRS',
                        'color' => 'BLUE',
                        'dilution' => '10',
                        'active' => 'F',
                        'compound_receipt_id' => 1,
                        'created_at' => '2018-01-13 13:50:45',
                        'user' => 'Xtract Admin',
                        'barcode' => '100002',
                        'mix_date' => '2018-02-01 00:00:00',
                        'out_date' => '2018-08-01',
                        'vial_id' => 3,
                        'vial_note' => '',
                        'vial_number' => '3'
                    ]
                ]
            ]);

        // put active back
        $data = [
            'active' => 'T'
        ];

        $response = $this->putJsonTest($url, $data);

        $response
            ->assertStatus(200);
    }

    /**
     * Update a vial fail due to missing active field
     */
    //removed 7/11/18 AMH active is no longer a required field
    // public function test_vial_update_fail()
    // {
    //     $url = $this->makeUrl('/v1/patient/{patient_id}/prescription/{prescription_id}/vial/{id}', 3);

    //     $data = [
    //         'vial_note' => 'updated note',
    //         'current_volume' => '4.25'
    //     ];

    //     $response = $this->putJsonTest($url, $data, 'validation');

    //     $response
    //         ->assertStatus(400)
    //         ->assertJson([
    //             'status' => 'validation',
    //             'errors' => [
    //                 'active' => [
    //                     'The active field is required.'
    //                 ]
    //             ]
    //         ]);
    // }
}
