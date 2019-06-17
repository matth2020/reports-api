<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Http\Request;
use Illuminate\Foundation\Testing\RefreshDatabase;

class QuestionTest extends TestCase
{
    /**
     * Create question.
     * @return question ID
     */
    public function test_question_insert()
    {
        $url = $this->makeUrl('/v1/question');

        $data = [
            'text' =>             'Are you feeling well today?',
            'note' =>             'test note',
            'type' =>             'yes,no',
            'allow_multiple' =>   'F',
            'questionnaires' =>   [1]
        ];

        $response = $this->postJsonTest($url, $data);

        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'data' => [
                    'question' => [
                        'text' =>             'Are you feeling well today?',
                        'note' =>             'test note',
                        'type' =>             'yes,no',
                        'allow_multiple' =>   'F',
                        'questionnaires' =>   [1]
                    ]
                ]
            ]);

        $arr = $response->json();
        $question = $arr['data']['question'];
        $insertedId = $question['question_id'];

        $this->assertTrue($insertedId !== 0);
        return $insertedId;
    }

    /**
     * Update question.
     * @depends test_question_insert
     * @return void
     */
    public function test_question_update($insertedId)
    {
        $url = $this->makeUrl('/v1/question/{id}', $insertedId);

        $data = [
            'text' => 'Are you feeling well today (healthy)?',
            'questionnaires' =>   []
        ];

        $response = $this->putJsonTest($url, $data);

        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'data' => [
                    'question' => [
                        'text' => 'Are you feeling well today (healthy)?'
                    ]
                ]
            ]);

        // put the questionnaires back
        $data = [
            'questionnaires' => [1]
        ];

        $response = $this->putJsonTest($url, $data);

        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'data' => [
                    'question' => [
                        'questionnaires' => [1]
                    ]
                ]
            ]);

        return $insertedId;
    }

    /**
     * Read question.
     * @depends test_question_update
     * @return void
     */
    public function test_question_read($insertedId)
    {
        $url = $this->makeUrl('/v1/question/{id}', $insertedId);

        $response = $this->getJsonTest($url);

        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'data' => [
                    'question' => [
                        'question_id' => $insertedId,
                        'text' => 'Are you feeling well today (healthy)?'
                    ]
                ]
            ]);
    }

    /**
     * Search question.
     * @depends test_question_insert
     * @return void
     */
    public function test_question_search($insertedId)
    {
        $url = $this->makeUrl('/v1/question/_search');

        $data = [
            'text' => 'Are you feeling well today (healthy)?',
        ];

        $response = $this->postJsonTest($url, $data);

        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => 'success'
            ]);

        $arr = $response->json();

        $questions = $arr['data']['question'];
        $this->assertTrue(count($questions) > 0);
    }

    /**
     * Delete question.
     * @depends test_question_update
     * @return void
     */
    public function test_question_delete($insertedId)
    {
        $url = $this->makeUrl('/v1/question/{id}', $insertedId);

        $response = $this->deleteJsonTest($url, []);

        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'data' => [
                    'question' => [
                        'question_id' => $insertedId,
                        'deleted' => 'T'
                    ]
                ]
            ]);
    }
}
