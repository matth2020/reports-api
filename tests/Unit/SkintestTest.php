<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Http\Request;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SkintestTest extends TestCase
{
    /**
     * Read all skintests for a patient.
     */
    public function test_skintest_get_list()
    {
        $url = $this->makeUrl('/v1/patient/{patient_id}/skintest');

        $response = $this->getJsonTest($url);

        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'data' => [
                    'skintest' => [[
                        'name' => 'Simple'
                    ]]
                ]
            ]);
    }

    /**
     * Read a specific skintest for a patient.
     */
    public function test_skintest_get()
    {
        $url = $this->makeUrl('/v1/patient/{patient_id}/skintest/1');

        $response = $this->getJsonTest($url);

        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'data' => [
                    'skintest' => [
                        'name' => 'Simple'
                    ]
                ]
            ]);
    }

    /**
     * Search for skintests for a patient.
     */
    // not yet working
    public function Test_skintest_search()
    {
        $url = $this->makeUrl('/v1/patient/{patient_id}/skintest/_search');

        $data = [
            'name' => 'Simple'
        ];

        $response = $this->postJsonTest($url, $data);

        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'data' => [
                    'skintest' => [
                        'name' => 'Simple'
                    ]
                ]
            ]);
    }
}
