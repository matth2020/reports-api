<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Http\Request;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ExtractTest extends TestCase
{
    /**
     * Read all extracts.
     * @return void
     */
    private function verify_extract_order($expected_ids, $expected_orders)
    {
        $url = $this->makeUrl('/v1/extract/_search?fields=extract_id,test_order');

        $response = $this->postJsonTest($url, ['deleted' => 'F']);
        $response
            ->assertStatus(200);

        $arr = $response->json();

        // use just those that have test_order

        $extracts = array_filter($arr['data']['extract'], function ($val) {
            return isset($val['test_order']);
        });

        usort($extracts, function ($a, $b) {
            if ($a['test_order'] == $b['test_order']) {
                return 0;
            }
            return ($a['test_order'] < $b['test_order']) ? -1 : 1;
        });

        $id_str = join(',', array_column($extracts, 'extract_id'));
        $order_str = join(',', array_column($extracts, 'test_order'));

        $this->assertEquals($expected_ids, $id_str);
        $this->assertEquals($expected_orders, $order_str);
    }

    /**
     * Verify we have a known set of extracts and orders.
     */
    public function test_extract_verify_test_orders()
    {
        $expected_ids = '2,3,4,5,6,7,8,9,10,11';
        $expected_orders = '1,2,3,4,5,6,7,8,9,10';
        $this->verify_extract_order($expected_ids, $expected_orders);
    }

    /**
     * Create extract.
     * @depends test_extract_verify_test_orders
     * @return extract ID
     */
    public function test_extract_insert()
    {
        $url = $this->makeUrl('/v1/extract');

        $data = [
            'name' => 'New test extract',
            'manufacturer' => 'TBD',
            'code' => 'GP46',
            'is_visible' => 'T',
            'percent_glycerin' => '50.00',
            'dilution' => '1:20',
            'latin_name' => '',
            'percent_phenol' => '0.00',
            'percent_hsa' => '0.00',
            'specific_gravity' => '1.16',
            'outdate_alert' => '5',
            'image_file' => '',
            'is_diluent' => 'F',
            'season_start' => '1/1',
            'season_end' => '12/31',
            'units' => [
                'units_id' => '2'
            ],
            'compatibility_class' => [
                'compatibility_class_id' => '1'
            ],
            'test_order' => 5
        ];

        $response = $this->postJsonTest($url, $data);

        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'data' => [
                    'extract' => [
                        'name' => 'New test extract',
                        'manufacturer' => 'TBD',
                        'code' => 'GP46',
                        'is_visible' => 'T',
                        'percent_glycerin' => '50.00',
                        'dilution' => '1:20',
                        'deleted' => 'F',
                        'percent_phenol' => '0.00',
                        'percent_hsa' => '0.00',
                        'specific_gravity' => '1.16',
                        'outdate_alert' => '5',
                        'is_diluent' => 'F',
                        'season_start' => '1/1',
                        'season_end' => '12/31',
                        'compatibility_class' => [
                            'compatibility_class_id' => 1,
                            'name' => 'Trees',
                            'incompatible_classes' => [
                                [
                                    'compatibility_class_id' => 2,
                                    'name' => 'Bacteria'
                                ],
                                [
                                    'compatibility_class_id' => 3,
                                    'name' => 'Grasses'
                                ]
                            ]
                        ],
                        'test_order' => 5
                    ]
                ]
            ]);

        $arr = $response->json();
        $extract = $arr['data']['extract'];
        $insertedId = $extract['extract_id'];

        // verify that the test_order renumbering worked

        $expected_ids = '2,3,4,5,' . $insertedId . ',6,7,8,9,10,11';
        $expected_orders = '1,2,3,4,5,6,7,8,9,10,11';
        $this->verify_extract_order($expected_ids, $expected_orders);

        $this->assertTrue($insertedId !== 0);

        return $insertedId;
    }

    /**
     * Update extract - bad values.
     * @depends test_extract_insert
     * @return void
     */
    public function test_extract_bad_values($insertedId)
    {
        $url = $this->makeUrl('/v1/extract/{id}', $insertedId);

        $data = [
            'name' => '12345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901',
            'latin_name' => '12345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901',
            'manufacturer' => '1234567890123456789012345678901234567890123456',
            'abbreviation' => '1234567890123456789012345678901234567890123456',
            'ndc' => '12345678901234',
            'is_visible' => '12345678901',
            'percent_glycerin' => '100.1',
            'percent_phenol' => '100.1',
            'percent_hsa' => '100.1',
            'cost' => '1234567890123456789012345678901234567890123456',
            'substitutes' => '123456789012345678901234567890123456789012345678901',
            'specific_gravity' => '1234567890123456789012345678901234567890123456',
            'clinic_part_number' => '123456789012345678901234567890123',
            'test_order' => 0,
            'outdate_alert' => '1234567890123456789012345678901234567890123456',
            'compatibility_class_id' => '12345678901234567890123456789012345678901234567890',
            'image_file' => '1234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901',
            'is_diluent' => '123456',
            'silhouette' => '1234567890123456789012345678901234567890123456',
            'color' => '1234567890123456789012345678901234567890123456',
            'icon_top_line' => '1234567890123456789012345678901234567890123456',
            'icon_middle_line' => '1234567890123456789012345678901234567890123456',
            'icon_bottom_line' => '1234567890123456789012345678901234567890123456',
            'season_start' => '1234567890123456789012345678901234567890123456',
            'season_end' => '1234567890123456789012345678901234567890123456',
            'deleted' => '1234567890123456789012345678901234567890123456'
        ];

        $response = $this->putJsonTest($url, $data, 'validation');

        $response
            ->assertStatus(400)
            ->assertJson([
                'status' => 'validation',
                'errors' => [
                    'name' => [
                        'The name must be between 0 and 100 characters.'
                    ],
                    'latin_name' => [
                        'The latin name must be between 0 and 100 characters.'
                    ],
                    'manufacturer' => [
                        'The manufacturer must be between 0 and 45 characters.'
                    ],
                    'abbreviation' => [
                        'The abbreviation must be between 0 and 45 characters.'
                    ],
                    'ndc' => [
                        'The ndc must be between 0 and 13 characters.'
                    ],
                    'is_visible' => [
                        'The is visible must be between 0 and 10 characters.'
                    ],
                    'percent_glycerin' => [
                        'The percent glycerin must be between 0 and 100.00.'
                    ],
                    'percent_phenol' => [
                        'The percent phenol must be between 0 and 100.00.'
                    ],
                    'percent_hsa' => [
                        'The percent hsa must be between 0 and 100.00.'
                    ],
                    'cost' => [
                        'The cost must be between 0 and 45 characters.'
                    ],
                    'substitutes' => [
                        'The substitutes must be an array.'
                    ],
                    'specific_gravity' => [
                        'The specific gravity must be between 0 and 45 characters.'
                    ],
                    'outdate_alert' => [
                        'The outdate alert must be between 0 and 45 characters.'
                    ],
                    'compatibility_class_id' => [
                        'The selected compatibility class id is invalid.'
                    ],
                    'image_file' => [
                        'The image file must be between 0 and 150 characters.'
                    ],
                    'clinic_part_number' => [
                        'The clinic part number must be between 0 and 32 characters.'
                    ],
                    'is_diluent' => [
                        'The is diluent must be between 0 and 5 characters.'
                    ],
                    'silhouette' => [
                        'The silhouette must be between 0 and 45 characters.'
                    ],
                    'color' => [
                        'The color must be between 0 and 45 characters.'
                    ],
                    'icon_top_line' => [
                        'The icon top line must be between 0 and 45 characters.'
                    ],
                    'icon_middle_line' => [
                        'The icon middle line must be between 0 and 45 characters.'
                    ],
                    'icon_bottom_line' => [
                        'The icon bottom line must be between 0 and 45 characters.'
                    ],
                    'season_start' => [
                        'Season must be of the form [*]m/d, where m = month and d = day.'
                    ],
                    'season_end' => [
                        'Season must be of the form [*]m/d, where m = month and d = day.'
                    ],
                    'deleted' => [
                        'The deleted must be between 0 and 45 characters.'
                    ]
                ]
            ]);
    }

    /**
     * Read extract.
     * @depends test_extract_insert
     * @return void
     */
    public function test_extract_read($insertedId)
    {
        $url = $this->makeUrl('/v1/extract/{id}?fields=name,manufacturer,code,visible,percent_glycerin,dilution,latin_name,percent_phenol,percent_hsa,specific_gravity,outdate_alert,image_file,is_diluent,season_start,season_end,units_id,compatibility_class_id,test_order', $insertedId);

        $response = $this->getJsonTest($url);

        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'data' => [
                    'extract' => [
                        'name' => 'New test extract',
                        'manufacturer' => 'TBD',
                        'code' => 'GP46',
                        'is_visible' => 'T',
                        'percent_glycerin' => '50.00',
                        'dilution' => '1:20',
                        'test_order' => 5,
                        'latin_name' => '',
                        'percent_phenol' => '0.00',
                        'percent_hsa' => '0.00',
                        'specific_gravity' => '1.16',
                        'outdate_alert' => '5',
                        'image_file' => '',
                        'is_diluent' => 'F',
                        'season_start' => '1/1',
                        'season_end' => '12/31'
                    ]
                ]
            ]);
    }

    /**
     * Read all extracts.
     * @depends test_extract_insert
     * @return void
     */
    public function test_extract_get_all()
    {
        $url = $this->makeUrl('/v1/extract');

        $response = $this->getJsonTest($url);

        $response
            ->assertStatus(200)
            ->assertJsonFragment([
                'name' => 'New test extract',
                'manufacturer' => 'TBD',
                'code' => 'GP46',
                'is_visible' => 'T',
                'percent_glycerin' => '50.00',
                'dilution' => '1:20',
                'test_order' => 5,
                'latin_name' => '',
                'percent_phenol' => '0.00',
                'percent_hsa' => '0.00',
                'specific_gravity' => '1.16',
                'outdate_alert' => '5',
                'image_file' => '',
                'is_diluent' => 'F',
                'season_start' => '1/1',
                'season_end' => '12/31'
            ]);
    }

    /**
     * Search extract.
     * @depends test_extract_insert
     * @return void
     */
    public function test_extract_search($insertedId)
    {
        $url = $this->makeUrl('/v1/extract/_search');

        $data = [
            'name' => 'New test extract',
            'image_file' => ''
        ];

        $response = $this->postJsonTest($url, $data);

        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => 'success'
            ])
            ->assertJsonFragment([
                'name' => 'New test extract',
                'manufacturer' => 'TBD',
                'code' => 'GP46',
                'is_visible' => 'T',
                'percent_glycerin' => '50.00',
                'dilution' => '1:20',
                'test_order' => 5,
                'latin_name' => '',
                'percent_phenol' => '0.00',
                'percent_hsa' => '0.00',
                'specific_gravity' => '1.16',
                'outdate_alert' => '5',
                'image_file' => '',
                'is_diluent' => 'F',
                'season_start' => '1/1',
                'season_end' => '12/31'
            ]);
    }

    /**
     * Delete extract.
     * @depends test_extract_insert
     * @return void
     */
    public function test_extract_delete($insertedId)
    {
        $url = $this->makeUrl('/v1/extract/{id}', $insertedId);

        $response = $this->deleteJsonTest($url, []);

        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'data' => [
                    'extract' => [
                        'extract_id' => $insertedId,
                        'deleted' => 'T'
                    ]
                ]
            ]);

        // verify that the test_order renumbering worked

        $expected_ids = '2,3,4,5,6,7,8,9,10,11';
        $expected_orders = '1,2,3,4,5,6,7,8,9,10';
        $this->verify_extract_order($expected_ids, $expected_orders);

        $this->assertTrue($insertedId !== 0);

        return $insertedId;
    }

    /**
     * Create extract.
     * @depends test_extract_delete
     * @return extract ID
     */
    public function test_extract_substitute_insert()
    {
        // insert another extract
        $url = $this->makeUrl('/v1/extract');

        $data = [
            'name' => 'New test extract for substitute',
            'manufacturer' => 'TBD',
            'code' => 'GP46',
            'is_visible' => 'T',
            'percent_glycerin' => '50.00',
            'dilution' => '1:20',
            'latin_name' => '',
            'percent_phenol' => '0.00',
            'percent_hsa' => '0.00',
            'specific_gravity' => '1.16',
            'outdate_alert' => '5',
            'image_file' => '',
            'is_diluent' => 'F',
            'season_start' => '1/1',
            'season_end' => '12/31',
            'units' => [
                'units_id' => '2'
            ],
            'compatibility_class' => [
                'compatibility_class_id' => '1'
            ],
            'test_order' => 11
        ];

        $response = $this->postJsonTest($url, $data);

        $response
            ->assertStatus(200);

        $arr = $response->json();
        $extract = $arr['data']['extract'];
        $insertedId = $extract['extract_id'];

        $url = $this->makeUrl('/v1/extract');

        $data = [
            'name' => 'New test extract with substitute',
            'manufacturer' => 'TBD',
            'code' => 'GP46',
            'is_visible' => 'T',
            'percent_glycerin' => '50.00',
            'dilution' => '1:20',
            'latin_name' => '',
            'percent_phenol' => '0.00',
            'percent_hsa' => '0.00',
            'specific_gravity' => '1.16',
            'outdate_alert' => '5',
            'image_file' => '',
            'is_diluent' => 'F',
            'season_start' => '1/1',
            'season_end' => '12/31',
            'units' => [
                'units_id' => '2'
            ],
            'substitutes' => [
                [
                    'extract_id' => $insertedId
                ]
            ],
            'compatibility_class' => [
                'compatibility_class_id' => '1'
            ],
            'test_order' => 12
        ];

        $response = $this->postJsonTest($url, $data);

        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'data' => [
                    'extract' => [
                        'name' => 'New test extract with substitute',
                        'manufacturer' => 'TBD',
                        'code' => 'GP46',
                        'is_visible' => 'T',
                        'percent_glycerin' => '50.00',
                        'dilution' => '1:20',
                        'deleted' => 'F',
                        'percent_phenol' => '0.00',
                        'percent_hsa' => '0.00',
                        'specific_gravity' => '1.16',
                        'outdate_alert' => '5',
                        'is_diluent' => 'F',
                        'season_start' => '1/1',
                        'season_end' => '12/31',
                        'substitutes' => [
                            [
                                'extract_id' => $insertedId
                            ]
                        ],
                        'compatibility_class' => [
                            'compatibility_class_id' => 1,
                            'name' => 'Trees',
                            'incompatible_classes' => [
                                [
                                    'compatibility_class_id' => 2,
                                    'name' => 'Bacteria'
                                ],
                                [
                                    'compatibility_class_id' => 3,
                                    'name' => 'Grasses'
                                ]
                            ]
                        ],
                        'test_order' => 12
                    ]
                ]
            ]);

        $arr = $response->json();
        $extract = $arr['data']['extract'];
        $insertedId = $extract['extract_id'];

        $this->assertTrue($insertedId !== 0);

        return $insertedId;
    }

    /**
     * Update extract.
     * @depends test_extract_substitute_insert
     * @return void
     */
    public function test_extract_update($insertedId)
    {
        // update a bunch of things in the extract

        $url = $this->makeUrl('/v1/extract/{id}', $insertedId);

        $data = [
            'name' => 'Newly named test extract with substitute',
            'manufacturer' => 'TBD',
            'code' => 'GP46',
            'is_visible' => 'T',
            'percent_glycerin' => '50.00',
            'dilution' => '1:20',
            'latin_name' => '',
            'percent_phenol' => '1.00',
            'percent_hsa' => '2.00',
            'specific_gravity' => '1.17',
            'outdate_alert' => '7',
            'image_file' => '',
            'is_diluent' => 'T',
            'season_start' => '3/1',
            'season_end' => '3/31',
            'units' => [
                'units_id' => '3'
            ],
            'compatibility_class' => [
                'compatibility_class_id' => 1
            ],
            'test_order' => 2
        ];

        $response = $this->putJsonTest($url, $data);

        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'data' => [
                    'extract' => [
                        'name' => 'Newly named test extract with substitute',
                        'manufacturer' => 'TBD',
                        'code' => 'GP46',
                        'is_visible' => 'T',
                        'percent_glycerin' => '50.00',
                        'dilution' => '1:20',
                        'latin_name' => '',
                        'percent_phenol' => '1.00',
                        'percent_hsa' => '2.00',
                        'specific_gravity' => '1.17',
                        'outdate_alert' => '7',
                        'image_file' => '',
                        'is_diluent' => 'T',
                        'season_start' => '3/1',
                        'season_end' => '3/31',
                        'units' => [
                            'units_id' => '3'
                        ],
                        'compatibility_class' => [
                            'compatibility_class_id' => '1'
                        ],
                        'test_order' => 2
                    ]
                ]
            ]);

        // verify that the test_order renumbering worked

        $expected_ids = '2,' . $insertedId . ',3,4,5,6,7,8,9,10,11,13';
        $expected_orders = '1,2,3,4,5,6,7,8,9,10,11,12';
        $this->verify_extract_order($expected_ids, $expected_orders);

        // add another substitute to it

        $arr = $response->json();
        $extract = $arr['data']['extract'];
        $substitutes = $extract['substitutes'];

        array_push($substitutes, ['extract_id' => 5]);

        $data = [
            'substitutes' => $substitutes
        ];

        $response = $this->putJsonTest($url, $data);

        $response
            ->assertStatus(200)
            ->assertJson([
                'data' => [
                    'extract' => [
                        'substitutes' => $substitutes
                    ]
                ]
            ]);
        // remove the substitutes from it

        $data = [
            'substitutes' => [
                [
                ]
            ]
        ];

        $response = $this->putJsonTest($url, $data);

        $response
            ->assertStatus(200)
            ->assertJsonMissing([
                'data' => [
                    'substitutes' => [
                        [
                        ]
                    ]
                ]
            ]);
    }
}
