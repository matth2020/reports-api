<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Http\Request;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CompatibilityTest extends TestCase
{
    protected $newData = [
        'name' => 'Test_compatibility_class_1',
    ];

    /**
     * Create compatibility_class.
     * @return array of compatibility_class ID
     */
    public function test_compatibility_class_insert_1()
    {
        $url = $this->makeUrl('/v1/compatibility_class');

        $data = [
            'name' => 'Test_compatibility_class_1'
        ];

        $response = $this->postJsonTest($url, $data);

        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'data' => [
                    'compatibility_class' => $data
                ]
            ]);

        $arr = $response->json();
        $compatibility_class = $arr['data']['compatibility_class'];
        $insertedId = $compatibility_class['compatibility_class_id'];

        $this->assertTrue($insertedId !== 0);
        return [$insertedId];
    }

    /**
     * Create compatibility_class.
     * @depends test_compatibility_class_insert_1
     * @return compatibility_class ID
     */
    public function test_compatibility_class_insert_2($insertedIds)
    {
        $url = $this->makeUrl('/v1/compatibility_class');

        $data = [
            'name' => 'Test_compatibility_class_2',
            'incompatible_classes' => [
                '0' => [
                    'compatibility_class_id' => $insertedIds[0]     // incompatible with the first
                ],
            ]
        ];

        $response = $this->postJsonTest($url, $data);

        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'data' => [
                    'compatibility_class' => $data
                ]
            ]);

        $arr = $response->json();
        $compatibility_class = $arr['data']['compatibility_class'];
        $insertedId_2 = $compatibility_class['compatibility_class_id'];

        $this->assertTrue($insertedId_2 !== 0);
        array_push($insertedIds, $insertedId_2);
        return $insertedIds;
    }

    /**
     * Create compatibility_class.
     * @depends test_compatibility_class_insert_2
     * @return array of compatibility_class ID
     */
    public function test_compatibility_class_insert_3($insertedIds)
    {
        $url = $this->makeUrl('/v1/compatibility_class');

        $data = [
            'name' => 'Test_compatibility_class_3',
            'incompatible_classes' => [
                '0' => [
                    'compatibility_class_id' => $insertedIds[0]
                ],
                '1' => [
                    'compatibility_class_id' => $insertedIds[1]
                ],
            ]
        ];

        $response = $this->postJsonTest($url, $data);

        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'data' => [
                    'compatibility_class' => $data
                ]
            ]);

        $arr = $response->json();
        $compatibility_class = $arr['data']['compatibility_class'];
        $insertedId_3 = $compatibility_class['compatibility_class_id'];

        $this->assertTrue($insertedId_3 !== 0);
        array_push($insertedIds, $insertedId_3);
        return $insertedIds;
    }

    /**
     * Read compatibility_class.
     * @depends test_compatibility_class_insert_1
     * @return void
     */
    public function test_compatibility_class_read($insertedIds)
    {
        $url = $this->makeUrl('/v1/compatibility_class/{id}?fields=compatibility_class_id,name', $insertedIds[0]);

        $response = $this->getJsonTest($url);

        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'data' => [
                    'compatibility_class' => array_merge($this->newData, ['compatibility_class_id' => $insertedIds[0]])
                ]
            ]);
    }

    /**
     * Update compatibility_class.
     * @depends test_compatibility_class_insert_3
     * @return void
     */
    public function test_compatibility_class_update($insertedIds)
    {
        $url = $this->makeUrl('/v1/compatibility_class/{id}', $insertedIds[0]);

        // Add an incompatibility to first class

        $incompatibilities = [
            'incompatible_classes' => [
                '0' => [
                    'compatibility_class_id' => $insertedIds[1]     // first class is incompatible with second class
                ]
            ]
        ];

        $updatedData = array_merge($this->newData, $incompatibilities);
        unset ($updatedData->name);

        $response = $this->putJsonTest($url, $updatedData);

        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'data' => [
                    'compatibility_class' => $updatedData
                ]
            ]);

        // Add another incompatibility

        $incompatibilities = [
            'incompatible_classes' => [
                '0' => [
                    'compatibility_class_id' => $insertedIds[0]     // first class is incompatible with itself
                ],
                '1' => [
                    'compatibility_class_id' => $insertedIds[1]     // first class is incompatible with second class
                ],
            ]
        ];

        $updatedData = array_merge($this->newData, $incompatibilities);
        unset ($updatedData->name);

        $response = $this->putJsonTest($url, $updatedData);

        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'data' => [
                    'compatibility_class' => $updatedData
                ]
            ]);

        // Read second class to verify it is shown as incompatible with the first

        $url = $this->makeUrl('/v1/compatibility_class/{id}?fields=compatibility_class_id,name,incompatible_classes', $insertedIds[1]);

        $response = $this->getJsonTest($url);

        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'data' => [
                    'compatibility_class' => [
                        'incompatible_classes' => [
                            '0' => [
                                'compatibility_class_id' => $insertedIds[0]     // second class is incompatible with first class
                            ],
                        ]
                    ]
                ]
            ]);

        // Remove second incompatibility

        $url = $this->makeUrl('/v1/compatibility_class/{id}?fields=compatibility_class_id,name,incompatible_classes', $insertedIds[0]);

        $incompatibilities = [
            'incompatible_classes' => [
                '0' => [
                    'compatibility_class_id' => $insertedIds[0]     // first class is incompatible with itself
                ]
            ]
        ];

        $updatedData = array_merge($this->newData, $incompatibilities);
        unset ($updatedData->name);

        $response = $this->putJsonTest($url, $updatedData);

        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'data' => [
                    'compatibility_class' => $updatedData
                ]
            ]);

        // Read second class to verify it is shown as no longer incompatible with the first

        $url = $this->makeUrl('/v1/compatibility_class/{id}?fields=compatibility_class_id,name', $insertedIds[1]);

        $response = $this->getJsonTest($url);

        $response
            ->assertStatus(200)
            ->assertJsonMissing([
                'incompatible_classes' => [
                    '0' => [
                        'compatibility_class_id' => $insertedIds[0]     // second class is incompatible with first class
                    ],
                ]
            ]);

        return $insertedIds;
    }

    /**
     * Read all compatibility_classes.
     * @depends test_compatibility_class_insert_1
     * @return void
     */
    public function test_compatibility_class_get_all()
    {
        $url = $this->makeUrl('/v1/compatibility_class');

        $response = $this->getJsonTest($url);

        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'data' => [
                    'compatibility_class' => [[],[],[
                        'name' => $this->newData['name']
                    ]]
                ]
            ]);
    }

    /**
     * Search compatibility_class.
     * @depends test_compatibility_class_insert_1
     * @return void
     */
    public function test_compatibility_class_search()
    {
        $url = $this->makeUrl('/v1/compatibility_class/_search');

        $data = [
            'name' => 'Test_compatibility_class_1'
        ];

        $response = $this->postJsonTest($url, $data);

        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => 'success'
            ]);

        $arr = $response->json();

        $compatibility_classes = $arr['data']['compatibility_class'];
        $this->assertTrue(count($compatibility_classes) > 0);
    }

    /**
     * Insert compatibility_class - fail: duplicate name.
     */
    public function test_compatibility_class_insert_duplicate()
    {
        $url = $this->makeUrl('/v1/compatibility_class');

        $data = [
            'name' => 'Test_compatibility_class_test_duplicate'
        ];

        $response = $this->postJsonTest($url, $data);

        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'data' => [
                    'compatibility_class' => $data
                ]
            ]);

        $arr = $response->json();
        $compatibility_class = $arr['data']['compatibility_class'];
        $insertedId = $compatibility_class['compatibility_class_id'];

        $this->assertTrue($insertedId !== 0);

        // try to insert the same one again

        $response = $this->postJsonTest($url, $data, 'validation');

        $response
            ->assertStatus(400)
            ->assertJson([
                'status' => 'validation',
                'errors' => [
                    'name' => [
                        'A compatibility class with that name already exists.'
                    ]
                ]
            ]);

        return $insertedId;
    }

    /**
     * Delete compatibility_class.
     * @depends test_compatibility_class_insert_duplicate
     * @return void
     */
    public function test_compatibility_class_delete($insertedId)
    {
        $url = $this->makeUrl('/v1/compatibility_class/{id}', $insertedId);

        $response = $this->deleteJsonTest($url, []);

        $data = [
            'name' => 'Test_compatibility_class_test_duplicate'
        ];

        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'data' => [
                    'compatibility_class' => $data
                ]
            ]);

        // verify that it no longer shows up in searches

        $url = $this->makeUrl('/v1/compatibility_class');

        $response = $this->getJsonTest($url);

        $response
            ->assertStatus(200)
            ->assertJsonMissing([
                'name' => 'Test_compatibility_class_test_duplicate'
            ]);

        $url = $this->makeUrl('/v1/compatibility_class/_search');

        $data = [
            'name' => 'Test_compatibility_class_test_duplicate'
        ];

        $response = $this->postJsonTest($url, $data);

        $response
            ->assertStatus(200)
            ->assertJsonMissing([
                'name' => $this->newData['name']
            ]);
    }
}
