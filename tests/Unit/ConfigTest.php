<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;

class ConfigTest extends TestCase
{
    /**
     * Create config into read_only section, fail.
     * @return void
     */
    public function test_read_only_config_insert()
    {
        $url = $this->makeUrl('/v1/config');

        $data = [
            'app' => 'XST',
            'section' => 'read_only',
            'name' => 'test_name',
            'value' => 'test_value'
        ];

        $response = $this->postJsonTest($url, $data, 'validation');

        $response
            ->assertStatus(400)
            ->assertJson([
                'status' => 'validation',
                'errors' => [
                    'section' => [
                        'The selected section is invalid.'
                    ]
                ]
            ]);
    }

    /**
     * Create config.
     * @return void
     */
    public function test_config_insert()
    {
        $url = $this->makeUrl('/v1/config');

        $data = [
            'app' => 'XST',
            'section' => 'test_section',
            'name' => 'test_name',
            'value' => 'test_value'
        ];

        $response = $this->postJsonTest($url, $data);

        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'data' => [
                    'config' => $data
                ]
            ]);

        $arr = $response->json();
        $config = $arr['data']['config'];
        $insertedId = $config['config_id'];

        $this->assertTrue($insertedId !== 0);
        return $insertedId;
    }

    /**
     * Test config re-insert, fail due to 'already defined'.
     * @depends test_config_insert
     * @return void
     */
    public function test_config_reinsert($insertedId)
    {
        $url = $this->makeUrl('/v1/config');

        $data = [
            'app' => 'XST',
            'section' => 'test_section',
            'name' => 'test_name',
            'value' => 'test_value'
        ];

        $response = $this->postJsonTest($url, $data, 'validation');

        $response
            ->assertStatus(400)
            ->assertJson([
                'status' => 'validation',
                'errors' => [
                    'name' => [
                        'The value for (test_section, test_value, XST, ) is already defined.'
                    ]
                ]
            ]);
    }

    /**
     * Update config.
     * @depends test_config_insert
     * @return void
     */
    public function test_config_update($insertedId)
    {
        $url = $this->makeUrl('/v1/config/{id}', $insertedId);

        $data = [
            'app' => 'XST',
            'section' => 'test_section',
            'name' => 'test_name',
            'value' => 'test_new_value'
        ];

        $response = $this->putJsonTest($url, $data);

        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'data' => [
                    'config' => $data
                ]
            ]);

        return $insertedId;
    }

    /**
     * Read config.
     * @depends test_config_update
     * @return void
     */
    public function test_config_read($insertedId)
    {
        $url = $this->makeUrl('/v1/config/{id}', $insertedId);

        $response = $this->getJsonTest($url);

        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'data' => [
                    'config' => [
                        'app' => 'XST',
                        'section' => 'test_section',
                        'name' => 'test_name',
                        'value' => 'test_new_value',
                        'config_id' => $insertedId
                    ]
                ]
            ]);
    }

    /**
     * Search for non-config.
     * @return void
     */
    private function Test_config_search_non_config($name, $value = null)
    {
        $url = $this->makeUrl('/v1/config/_search');

        $data = [
            'section' => 'read_only',
            'name' => $name
        ];

        $response = $this->postJsonTest($url, $data);

        $expected = $value ? array_merge($data, ['value' => $value]) : $data;

        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'data' => [
                    'config' => [
                        $expected
                    ]
                ]
            ]);
    }

    /**
     * Search for non-config: enforceLogout
     * Search for non-config: allowLogout
     * Search for non-config: doQuestions
     * Search for non-config: enforceQuestionnaire
     * Search for non-config: doDailyInjectionReport
     * Search for non-config: questionnaireIdConfirm
     * @return void
     */
    public function test_config_search_non_configs()
    {
        $this->Test_config_search_non_config(
            'reaction_names',
            [
                'systemic' => [
                    0 => 'N',
                    1 => 'Y',
                ],
                'local' => [
                    0 => 'None',
                    1 => 'Dime',
                    2 => 'Nickel',
                    3 => 'Quarter',
                ]
            ]
        );
        $this->Test_config_search_non_config('enforceLogout', 'F');
        $this->Test_config_search_non_config('allowLogout', 'F');
        $this->Test_config_search_non_config('doQuestions', 'F');
        $this->Test_config_search_non_config('enforceQuestionnaire', 'F');
        $this->Test_config_search_non_config('doDailyInjectionReport', 'F');
        $this->Test_config_search_non_config('questionnaireIdConfirm', 'F');
    }

    /**
     * Delete config.
     * @depends test_config_update
     * @return void
     */
    public function test_config_delete($insertedId)
    {
        $url = $this->makeUrl('/v1/config/{id}', $insertedId);

        $response = $this->deleteJsonTest($url, []);

        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'data' => [
                    'config' => [
                        'config_id' => $insertedId
                    ]
                ]
            ]);
    }

    /**
     * Delete non-config, fail.
     * @return void
     */
    public function test_config_delete_non_config()
    {
        $url = $this->makeUrl('/v1/config/{id}', 0);

        $response = $this->deleteJsonTest($url, [], 'fail');
        $response
            ->assertStatus(404)
            ->assertJson([
                'status' => 'fail'
            ]);
    }

    /**
     * Read all configs.
     * @return void
     */
    public function test_config_read_all()
    {
        $url = $this->makeUrl('/v1/config');

        $response = $this->getJsonTest($url);

        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => 'success'
            ]);

        $arr = $response->json();
        $config = $arr['data']['config'];
        $this->assertTrue(count($config) > 0);
    }
}
