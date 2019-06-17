<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Http\Request;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProfileTest extends TestCase
{
    /**
     * Create provider.
     * @return provider ID
     */
    public function test_profile_insert()
    {
        $url = $this->makeUrl('/v1/provider/1/profile');

        $data = [
            'offset' => 0,
            'provider_id' => 1,
            'dilution_steps' => [
                [
                    'dilution' => 1,
                    'expiration' => '3',
                    'color_name' => 'SLVR',
                    'color' => '12632256'
                ],
                [
                    'dilution' => 2,
                    'expiration' => '3',
                    'color_name' => 'GRN',
                    'color' => '32768'
                ],
                [
                    'dilution' => 10,
                    'expiration' => '6',
                    'color_name' => 'BLUE',
                    'color' => '255'
                ],
                [
                    'dilution' => 100,
                    'expiration' => '9',
                    'color_name' => 'YLW',
                    'color' => '16776960'
                ],
                [
                    'dilution' => 200,
                    'expiration' => '12',
                    'color_name' => 'RED',
                    'color' => '16711680'
                ]
            ],
            'bottle_numbering_order' => 'ascending_dilution',
            'name' => 'New profile',
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
        $insertedId = $profile['profile_id'];

        $this->assertTrue($insertedId !== 0);
        return $insertedId;
    }

    /**
     * Create profile - test colors.
     */
    public function test_profile_insert_colors()
    {
        $url = $this->makeUrl('/v1/provider/1/profile');

        $data = [
            'offset' => '0',
            'provider_id' => '1',
            'dilution_steps' => [
                [
                    'dilution' => 1000000,
                    'expiration' => '3',
                    'color_name' => 'WHT'
                ],
                [
                    'dilution' => 1000000,
                    'expiration' => '3',
                    'color_name' => 'ORNG'
                ],
                [
                    'dilution' => 100000,
                    'expiration' => '3',
                    'color_name' => 'PRPL'
                ],
                [
                    'dilution' => 10000,
                    'expiration' => '3',
                    'color_name' => 'SLVR'
                ],
                [
                    'dilution' => 1000,
                    'expiration' => '3',
                    'color_name' => 'GRN'
                ],
                [
                    'dilution' => 100,
                    'expiration' => '6',
                    'color_name' => 'BLUE'
                ],
                [
                    'dilution' => 10,
                    'expiration' => '9',
                    'color_name' => 'YLW'
                ],
                [
                    'dilution' => 1,
                    'expiration' => '12',
                    'color_name' => 'RED'
                ]
            ],
            'bottle_numbering_order' => 'descending_dilution',
            'name' => 'New profile for colors',
            'low_glycerin_limit' => '20.00',
            'high_glycerin_limit' => '50.00',
            'default_vial_size' => '5 mL',
            'include_diluent_name' => 'F',
            'preferred_glycerin_diluent_id' => '1',
            'preferred_aqueous_diluent_id' => '3'
        ];

        $response = $this->postJsonTest($url, $data);

        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'data' => [
                    'profile' => [
                        'dilution_steps' => [
                            [
                                'dilution' => 1000000,
                                'expiration' => '3',
                                'color_name' => 'WHT',
                                'color' => '16777215'
                            ],
                            [
                                'dilution' => 1000000,
                                'expiration' => '3',
                                'color_name' => 'ORNG',
                                'color' => '16753920'
                            ],
                            [
                                'dilution' => 100000,
                                'expiration' => '3',
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
                                'expiration' => '6',
                                'color_name' => 'BLUE',
                                'color' => '255'
                            ],
                            [
                                'dilution' => 10,
                                'expiration' => '9',
                                'color_name' => 'YLW',
                                'color' => '16776960'
                            ],
                            [
                                'dilution' => 1,
                                'expiration' => '12',
                                'color_name' => 'RED',
                                'color' => '16711680'
                            ]
                        ]
                    ]
                ]
            ]);

        // complete the color set

        $data = [
            'offset' => 0,
            'provider_id' => 1,
            'dilution_steps' => [
                [
                    'dilution' => 100,
                    'expiration' => '3',
                    'color_name' => 'PINK'
                ],
                [
                    'dilution' => 10,
                    'expiration' => '3',
                    'color_name' => 'LTGR'
                ],
                [
                    'dilution' => 1,
                    'expiration' => '3',
                    'color_name' => 'LTBL'
                ]
            ],
            'bottle_numbering_order' => 'ascending_dilution',
            'name' => 'New profile for colors 2',
            'low_glycerin_limit' => '20.00',
            'high_glycerin_limit' => '50.00',
            'default_vial_size' => '5 mL',
            'include_diluent_name' => 'F',
            'preferred_glycerin_diluent_id' => '2',
            'preferred_aqueous_diluent_id' => '4'
        ];

        $response = $this->postJsonTest($url, $data);

        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'data' => [
                    'profile' => [
                        'dilution_steps' => [
                            [
                                'dilution' => 1,
                                'expiration' => '3',
                                'color_name' => 'LTBL',
                                'color' => '11393254'
                            ],
                            [
                                'dilution' => 10,
                                'expiration' => '3',
                                'color_name' => 'LTGR',
                                'color' => '9498256'
                            ],
                            [
                                'dilution' => 100,
                                'expiration' => '3',
                                'color_name' => 'PINK',
                                'color' => '16761035'
                            ],
                        ]
                    ]
                ]
            ]);


    }

    /**
     * Update profile.
     * @depends test_profile_insert
     * @return void
     */
    public function test_profile_update($insertedId)
    {
        $url = $this->makeUrl('/v1/provider/1/profile/{id}', $insertedId);

        $data = [
            'offset' => '0',
            'provider_id' => '1',
            'dilution_steps' => [
                [
                    'dilution' => '2000',
                    'expiration' => '12',
                    'color_name' => 'RED',
                    'color' => '16711680'
                ],
                [
                    'dilution' => '1000',
                    'expiration' => '9',
                    'bill_rate' => '1',
                    'color_name' => 'YLW',
                    'color' => '16776960'
                ],
                [
                    'dilution' => '100',
                    'expiration' => '6',
                    'bill_rate' => '2',
                    'color_name' => 'BLUE',
                    'color' => '255'
                ],
                [
                    'dilution' => '20',
                    'expiration' => '3',
                    'color_name' => 'GRN',
                    'color' => '65280'
                ],
                [
                    'dilution' => '1',
                    'expiration' => '3',
                    'color_name' => 'SLVR',
                    'color' => '13684944'
                ]
            ],
            'bottle_numbering_order' => 'descending_dilution',
            'name' => 'Newer profile',
            'low_glycerin_limit' => '21.00',
            'high_glycerin_limit' => '51.00',
            'default_vial_size' => '10 mL',
            'include_diluent_name' => 'T',
            'preferred_glycerin_diluent_id' => '3',
            'preferred_aqueous_diluent_id' => '4'
        ];

        $response = $this->putJsonTest($url, $data);

        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'data' => [
                    'profile' => [
                        'offset' => '0',
                        'provider_id' => '1',
                        'dilution_steps' => [ ],
                        'bottle_numbering_order' => 'descending_dilution',
                        'name' => 'Newer profile',
                        'low_glycerin_limit' => '21.00',
                        'high_glycerin_limit' => '51.00',
                        'default_vial_size' => '10 mL',
                        'include_diluent_name' => 'T',
                        'preferred_glycerin_diluent_id' => '3',
                        'preferred_aqueous_diluent_id' => '4'
                    ]
                ]
            ]);

        return $insertedId;
    }

    /**
     * Update profile with bad values.
     * @depends test_profile_update
     * @return void
     */
    public function test_profile_update_bad($insertedId)
    {
        $url = $this->makeUrl('/v1/provider/1/profile/{id}', $insertedId);

        $data = [
            'offset' => '101',
            'provider_id' => '999999',
            'dilution_steps' => [
                [
                    'dilution' => '-1',
                    'expiration' => '100',
                    'bill_rate' => 'x',
                    'color_name' => 'REDxx',
                    'color' => '1671168000'
                ],
            ],
            'bottle_numbering_order' => 'bad',
            'name' => '12345678990123456789901234567899012345678990123456',
            'low_glycerin_limit' => '100.01',
            'high_glycerin_limit' => '100.01',
            'default_vial_size' => '12345678990123456',
            'include_diluent_name' => 'x',
            'preferred_glycerin_diluent_id' => '9999',
            'preferred_aqueous_diluent_id' => '9999'
        ];

        $response = $this->putJsonTest($url, $data, 'validation');

        $response
            ->assertStatus(400)
            ->assertJson([
                'status' => 'validation',
                'errors' => [
                    'name' => [
                        'The name must be between 1 and 45 characters.'
                    ],
//                    'provider_id' => [
//                        'The selected provider id is invalid.'
//                    ],
                    'offset' => [
                        'The offset must be between -2 and 2.'
                    ],
                    'low_glycerin_limit' => [
                        'The low glycerin limit must be between 0 and 100.00.'
                    ],
                    'high_glycerin_limit' => [
                        'The high glycerin limit must be between 0 and 100.00.'
                    ],
                    'default_vial_size' => [
                        'The default vial size must be between 0 and 15 characters.'
                    ],
                    'include_diluent_name' => [
                        'The selected include diluent name is invalid.'
                    ],
                    'preferred_glycerin_diluent_id' => [
                        'The selected preferred glycerin diluent id is invalid.'
                    ],
                    'preferred_aqueous_diluent_id' => [
                        'The selected preferred aqueous diluent id is invalid.'
                    ],
                    'bottle_numbering_order' => [
                        'The selected bottle numbering order is invalid.'
                    ],
                    'dilution_steps.0.dilution' => [
                        'The dilution_steps.0.dilution must be between 0 and 100000000.'
                    ],
                    'dilution_steps.0.bill_rate' => [
                        'The dilution_steps.0.bill_rate must be a number.'
                    ],
                    'dilution_steps.0.expiration' => [
                        'The dilution_steps.0.expiration must be between 0 and 60.'
                    ],
                    'dilution_steps.0.color_name' => [
                        'The dilution_steps.0.color_name must be between 0 and 4 characters.'
                    ]
                ]
            ]);

        return $insertedId;
    }

    /**
     * Read profile.
     * @depends test_profile_update
     * @return void
     */
    public function test_profile_read($insertedId)
    {
        $url = $this->makeUrl('/v1/provider/1/profile/{id}?fields=offset,provider_id,dilution_steps,bottle_numbering_order,name,low_glycerin_limit,high_glycerin_limit,default_vial_size,include_diluent_name,preferred_glycerin_diluent_id,preferred_aqueous_diluent_id', $insertedId);

        $response = $this->getJsonTest($url);

        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'data' => [
                    'profile' => []
                ]
            ]);

        $arr = $response->json();
        $profile = $arr['data']['profile'];
        $this->assertTrue($profile['name'] == 'Newer profile');
    }

    /**
     * Search provider.
     * @depends test_profile_insert
     * @return void
     */
    public function test_profile_provider_search($insertedId)
    {
        $url = $this->makeUrl('/v1/provider/1/profile/_search');

        $data = [
            'name' => 'Newer profile'
        ];

        $response = $this->postJsonTest($url, $data);

        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => 'success'
            ]);

        $arr = $response->json();

        $profiles = $arr['data']['profile'];
        $this->assertTrue(count($profiles) > 0);
    }

    /**
     * Search profile.
     * @depends test_profile_insert
     * @return void
     */
    public function test_profile_search($insertedId)
    {
        $url = $this->makeUrl('/v1/profile/_search');

        $data = [
            'name' => 'Newer profile'
        ];

        $response = $this->postJsonTest($url, $data);

        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => 'success'
            ]);

        $arr = $response->json();

        $profiles = $arr['data']['profile'];
        $this->assertTrue(count($profiles) > 0);
    }

    /**
     * Delete provider.
     * @depends test_profile_update
     * @return void
     */
    public function test_profile_delete($insertedId)
    {
        $url = $this->makeUrl('/v1/provider/1/profile/{id}', $insertedId);

        $response = $this->deleteJsonTest($url, []);

        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'data' => [
                    'profile' => [
                        'profile_id' => $insertedId,
                        'deleted' => 'T'
                    ]
                ]
            ]);

        // verify that the provider is no longer seen in searches

        $url = $this->makeUrl('/v1/provider/1/profile');

        $response = $this->getJsonTest($url);

        $response
            ->assertStatus(200)
            ->assertJsonMissing([
                'name' => 'Newer profile'
            ]);

        $url = $this->makeUrl('/v1/provider/1/profile/_search');

        $data = [
            'name' => 'Newer profile'
        ];

        $response = $this->postJsonTest($url, $data);

        $response
            ->assertStatus(200)
            ->assertJsonMissing([
                'name' => 'Newer profile'
            ]);

        // verify that we can still get it by asking directly for it

        $url = $this->makeUrl('/v1/provider/1/profile/{id}', $insertedId);

        $response = $this->getJsonTest($url);

        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'data' => [
                    'profile' => [
                        'name' => 'Newer profile',
                        'deleted' => 'T'
                    ]
                ]
            ]);
    }
}
