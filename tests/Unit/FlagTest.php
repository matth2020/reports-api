<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Http\Request;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FlagTest extends TestCase
{
    /**
     * Create flag.
     * @return flag ID
     */
    public function test_flag_insert()
    {
        $url = $this->makeUrl('/v1/patient/{patient_id}/flag');

        $data = [
            'category' => 'Clerical',
            'status' => 'active',
            'period_start' => '2017-01-23',
            'period_end' => '2017-08-23',
            'period_interval' => '30',
            'code' => 'Patient pays by credit card',
            'identifier' => 'Payment',
            'last_alert' => '2017-08-23 01:23:20'
        ];

        $response = $this->postJsonTest($url, $data);

        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'data' => [
                    'flag' => [
                        'category' => 'Clerical',
                        'status' => 'active',
                        'period_start' => '2017-01-23 00:00:00',
                        'period_end' => '2017-08-23 00:00:00',
                        'period_interval' => '30',
                        'code' => 'Patient pays by credit card',
                        'identifier' => 'Payment',
                        'last_alert' => '2017-08-23 01:23:20'
                    ]
                ]
            ]);

        $arr = $response->json();
        $flag = $arr['data']['flag'];
        $insertedId = $flag['flag_id'];

        $this->assertTrue($insertedId !== 0);
        return $insertedId;
    }

    /**
     * Update flag.
     * @depends test_flag_insert
     * @return void
     */
    public function test_flag_update($insertedId)
    {
        $url = $this->makeUrl('/v1/patient/{patient_id}/flag/{id}', $insertedId);

        $data = [
            'category' => 'Test_cat_new',
            'code' => 'Test_code_new'
        ];

        $response = $this->putJsonTest($url, $data);

        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'data' => [
                    'flag' => [
                        'category' => 'Test_cat_new',
                        'code' => 'Test_code_new'
                    ]
                ]
            ]);

        return $insertedId;
    }

    /**
     * Read flag
     * @depends test_flag_update
     * @return void
     */
    public function test_flag_read($insertedId)
    {
        $url = $this->makeUrl('/v1/patient/{patient_id}/flag/{id}', $insertedId);

        $response = $this->getJsonTest($url);

        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'data' => [
                    'flag' => []
                ]
            ]);

        // $arr = $response->json();
    }

    /**
     * Search flag.
     * @depends test_flag_insert
     * @return void
     */
    public function test_flag_search($insertedId)
    {
        $url = $this->makeUrl('/v1/patient/{patient_id}/flag/_search');

        $data = [
            'category' => 'Test_cat_new',
            'code' => 'Test_code_new'
        ];

        $response = $this->postJsonTest($url, $data);

        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => 'success'
            ]);

        $arr = $response->json();

        $flags = $arr['data']['flag'];
        $this->assertTrue(count($flags) > 0);
    }

    /**
     * Delete flag.
     * @depends test_flag_update
     * @return void
     */
    public function Test_flag_delete($insertedId)
    {
        //////////////////////// test not used for now - needs database to add deleted column
        ///

        $url = $this->makeUrl('/v1/patient/{patient_id}/flag/{id}', $insertedId);

        $response = $this->deleteJsonTest($url, []);

        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'data' => [
                    'flag' => [
                        'flag_id' => $insertedId,
                        'deleted' => 'T'
                    ]
                ]
            ]);
    }
}
