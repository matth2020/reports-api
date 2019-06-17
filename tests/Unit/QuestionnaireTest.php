<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Http\Request;
use Illuminate\Foundation\Testing\RefreshDatabase;

class QuestionnaireTest extends TestCase
{
    /**
     * Create questionnaire.
     * @return questionnaire ID
     */
    public function test_questionnaire_insert()
    {
        // first clean up old test data if needed
        $url = $this->makeUrl('/v1/questionnaire/_search');

        $response = $this->postJsonTest('/v1/questionnaire/_search', ['name' => 'Unit_test_questionnaire', 'deleted' => 'F']);

        $questionnaire = $response->json();
        if ($questionnaire['status'] == 'success' && count($questionnaire['data']['questionnaire']) > 0) {
            $foundId = $questionnaire['data']['questionnaire'][0]['questionnaire_id'];
            $url = $this->makeUrl('/v1/questionnaire/{id}', $foundId);
            $response = $this->deleteJsonTest($url, []);
            $response
                ->assertStatus(200)
                ->assertJson([
                    'status' => 'success',
                    'data' => [
                        'questionnaire' => [
                            'questionnaire_id' => $foundId,
                            'deleted' => 'T'
                        ]
                    ]
                ]);
        }

        $url = $this->makeUrl('/v1/questionnaire/_search');
        $response = $this->postJsonTest($url, ['name' => 'Unit_test_questionnaire_new', 'deleted' => 'F']);

        $questionnaire = $response->json();
        if ($questionnaire['status'] == 'success' && count($questionnaire['data']['questionnaire']) > 0) {
            $foundId = $questionnaire['data']['questionnaire'][0]['questionnaire_id'];
            $url = $this->makeUrl('/v1/questionnaire/{id}', $foundId);
            $response = $this->deleteJsonTest($url, []);
            $response
                ->assertStatus(200)
                ->assertJson([
                    'status' => 'success',
                    'data' => [
                        'questionnaire' => [
                            'questionnaire_id' => $foundId,
                            'deleted' => 'T'
                        ]
                    ]
                ]);
        }

        $url = $this->makeUrl('/v1/questionnaire');

        $data = [
            'name' => 'Unit_test_questionnaire',
            'minimum_frequency' => '3'
        ];

        // insert a questionnaire

        $response = $this->postJsonTest($url, $data);

        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'data' => [
                    'questionnaire' => [
                        'name' => 'Unit_test_questionnaire',
                        'minimum_frequency' => '3'
                    ]
                ]
            ]);

        $arr = $response->json();
        $questionnaire = $arr['data']['questionnaire'];
        $insertedId = $questionnaire['questionnaire_id'];

        $this->assertTrue($insertedId !== 0);
        return $insertedId;
    }

    /**
     * Update questionnaire.
     * @depends test_questionnaire_insert
     * @return void
     */
    public function test_questionnaire_update($insertedId)
    {
        $url = $this->makeUrl('/v1/questionnaire/{id}', $insertedId);

        $data = [
            'name' => 'Unit_test_questionnaire_new'
        ];

        // update the questionnaire

        $response = $this->putJsonTest($url, $data);

        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'data' => [
                    'questionnaire' => [
                        'name' => 'Unit_test_questionnaire_new'
                    ]
                ]
            ]);

        return $insertedId;
    }

    /**
     * Read questionnaire.
     * @depends test_questionnaire_update
     * @return void
     */
    public function test_questionnaire_read($insertedId)
    {
        $url = $this->makeUrl('/v1/questionnaire/{id}', $insertedId);

        // read the questionnaire

        $response = $this->getJsonTest($url);

        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'data' => [
                    'questionnaire' => [
                        'questionnaire_id' => $insertedId,
                        'name' => 'Unit_test_questionnaire_new'
                    ]
                ]
            ]);

        $arr = $response->json();
    }

    /**
     * Read all questionnaires.
     * @depends test_questionnaire_update
     * @return void
     */
    public function test_questionnaire_read_all()
    {
        $url = $this->makeUrl('/v1/questionnaire/');

        $response = $this->getJsonTest($url);

        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => 'success'
            ]);

        $arr = $response->json();

        $questionnaires = $arr['data']['questionnaire'];
        $this->assertTrue(count($questionnaires) > 0);
    }

    /**
     * Search questionnaire.
     * @depends test_questionnaire_insert
     * @return void
     */
    public function Test_questionnaire_search($insertedId)
    {
        $url = $this->makeUrl('/v1/questionnaire/_search');

        $data = [
            'name' => 'Unit_test_questionnaire_new'
        ];

        // search for the questionnaire

        $response = $this->postJsonTest($url, $data);

        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => 'success'
            ]);

        $arr = $response->json();

        $questionnaires = $arr['data']['questionnaire'];
        $this->assertTrue(count($questionnaires) > 0);
    }

    /**
     * Delete questionnaire.
     * @depends test_questionnaire_update
     * @return void
     */
    public function Test_questionnaire_delete($insertedId)
    {
        $url = $this->makeUrl('/v1/questionnaire/{id}', $insertedId);

        // delete the questionnaire

        $response = $this->deleteJsonTest($url, []);

        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'data' => [
                    'questionnaire' => [
                        'questionnaire_id' => $insertedId,
                        'deleted' => 'T'
                    ]
                ]
            ]);

        // verify item is marked as deleted
        $url = $this->makeUrl('/v1/questionnaire/{id}', $insertedId);

        $response = $this->getJsonTest($url);

        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'data' => [
                    'questionnaire' => [
                        'deleted' => 'T'
                    ]
                ]
            ]);

        $arr = $response->json();
    }
}
