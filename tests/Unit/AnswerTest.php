<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Http\Request;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AnswerTest extends TestCase
{
    /**
     * Create answer with an invalid 'ask'.
     * @return answer ID
     */
    public function test_answer_create_ask_fail()
    {
        $url = $this->makeUrl('/v1/patient/{patient_id}/multianswer');

        $question_id = '1';

        $data = [[
            'question_id' =>      $question_id,
            'questionnaire_id' => '1',
            'response' =>         'yes',
            'nurse_comment' =>    'nurse comment',
            'ask' =>              '4'
        ]];

        $response = $this->postJsonTest($url, $data, 'validation');

        $response
            ->assertStatus(400)
            ->assertJson([
                'status' => 'validation',
                'errors' => [
                    $question_id =>      [
                        'The selected ask is invalid.'
                    ]
                ]
            ]);
    }

    /**
     * Create yes/no answer.
     * @return answer ID
     */
    public function test_answer_create_yn()
    {
        $url = $this->makeUrl('/v1/patient/{patient_id}/multianswer');

        $question_id = '4';
        
        $data = [[
            'question_id' =>      $question_id,
            'questionnaire_id' => '1',
            'response' =>         'yes',
            'nurse_comment' =>    'nurse comment',
            'ask' =>              'T'
        ]];

        $response = $this->postJsonTest($url, $data);

        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'data' => [
                    'multianswer' => [[
                        'question_id' =>      $question_id,
                        'questionnaire_id' => '1',
                        'response' =>         'yes',
                        'nurse_comment' =>          'nurse comment',
                        'ask' =>              'T',
                        'patient_id' =>       '2'
                    ]]
                ]
            ]);
    }

    /**
     * Create yes/no answer with invalid response.
     * @return answer ID
     */
    public function test_answer_create_yn_fail()
    {
        $url = $this->makeUrl('/v1/patient/{patient_id}/multianswer');

        $question_id = '1';

        $data = [[
            'question_id' =>      $question_id,
            'questionnaire_id' => '1',
            'response' =>         'other',
            'nurse_comment' =>    'nurse comment',
            'ask' =>              'T'
        ]];

        $response = $this->postJsonTest($url, $data, 'validation');

        $response
            ->assertStatus(400)
            ->assertJson([
                'status' => 'validation',
                'errors' => [
                    $question_id =>      [
                        'The response must be one of the following types: yes, no'
                    ]
                ]
            ]);
    }

    /**
     * Create text answer.
     * @return answer ID
     */
    public function test_answer_create_text()
    {
        $url = $this->makeUrl('/v1/patient/{patient_id}/multianswer');

        $question_id = '4';

        $data = [[
            'question_id' =>      $question_id,
            'questionnaire_id' => '1',
            'response' =>         'some text',
            'nurse_comment' =>    'nurse comment',
            'ask' =>              'T'
        ]];

        $response = $this->postJsonTest($url, $data);

        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'data' => [
                    'multianswer' => $data
                ]
            ]);
    }

    /**
     * Create numeric answer.
     * @return answer ID
     */
    public function test_answer_create_numeric()
    {
        $url = $this->makeUrl('/v1/patient/{patient_id}/multianswer');

        $question_id = '2';

        $data = [[
            'question_id' =>      $question_id,
            'questionnaire_id' => '1',
            'response' =>         '3',
            'nurse_comment' =>    'nurse comment',
            'ask' =>              'T'
        ]];

        $response = $this->postJsonTest($url, $data);

        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'data' => [
                    'multianswer' => $data
                ]
            ]);
    }

    /**
     * Create numeric answer with invalid response.
     * @return answer ID
     */
    public function test_answer_create_numeric_fail()
    {
        $url = $this->makeUrl('/v1/patient/{patient_id}/multianswer');

        $question_id = '2';

        $data = [[
            'question_id' =>      $question_id,
            'questionnaire_id' => '1',
            'response' =>         '1,3',
            'nurse_comment' =>    'nurse comment',
            'ask' =>              'T'
        ]];

        $response = $this->postJsonTest($url, $data, 'validation');

        $response
            ->assertStatus(400)
            ->assertJson([
                'status' => 'validation',
                'errors' => [
                    $question_id =>      [
                        'The response must be one of the following types: 1, 2, 3, 4'
                    ]
                ]
            ]);
    }

    /**
      * Create multi-answer numeric answer.
      * @return answer ID
      */
    public function test_answer_create_numeric_multi()
    {
        $url = $this->makeUrl('/v1/patient/{patient_id}/multianswer');

        $question_id = '3';

        $data = [[
            'question_id' =>      $question_id,
            'questionnaire_id' => '1',
            'response' =>         '1,3',
            'nurse_comment' =>    'nurse comment',
            'ask' =>              'T'
        ]];

        $response = $this->postJsonTest($url, $data);

        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'data' => [
                    'multianswer' => $data
                ]
            ]);
    }

    /**
     * Create multi-answer numeric answer with invalid answer.
     * @return answer ID
     */
    public function test_answer_create_numeric_multi_fail()
    {
        $url = $this->makeUrl('/v1/patient/{patient_id}/multianswer');

        $question_id = '3';

        $data = [
            [
                'question_id' =>      $question_id,
                'questionnaire_id' => '1',
                'response' =>         '1,Y',
                'nurse_comment' =>    'nurse comment',
                'ask' =>              'T'
            ]
        ];

        $response = $this->postJsonTest($url, $data, 'validation');

        $response
            ->assertStatus(400)
            ->assertJson([
                'status' => 'validation',
                'errors' => [
                    $question_id => [
                        'The response must be one of the following types: 1, 2, 3, 4, 5'
                    ]
                ]
            ]);
    }

    /**
     * Create multi-answer answer.
     * @return answer ID
     */
    public function test_answer_create_multiple_answers()
    {
        $url = $this->makeUrl('/v1/patient/{patient_id}/multianswer');

        $question1_id = '1';
        $question2_id = '3';

        $data = [
            [
                'question_id' =>      $question1_id,
                'questionnaire_id' => '1',
                'response' =>         'yes',
                'nurse_comment' =>    'nurse comment',
                'ask' =>              'T'
            ],
            [
                'question_id' =>      $question2_id,
                'questionnaire_id' => '1',
                'response' =>         '1,3',
                'nurse_comment' =>    'nurse comment',
                'ask' =>              'T'
            ]
        ];

        $response = $this->postJsonTest($url, $data);

        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'data' => [
                    'multianswer' => $data
                ]
            ]);
    }

    /**
     * Create multi-answer answer with invalid response.
     * @return answer ID
     */
    public function test_answer_create_multiple_answers_fail()
    {
        $url = $this->makeUrl('/v1/patient/{patient_id}/multianswer');

        $question1_id = '1';
        $question2_id = '3';

        $data = [
            [
                'question_id' =>      $question1_id,
                'questionnaire_id' => '1',
                'response' =>           'other',
                'nurse_comment' =>    'nurse comment',
                'ask' =>              'T'
            ],
            [
                'question_id' =>      $question2_id,
                'questionnaire_id' => '1',
                'response' =>           '1,Y',
                'nurse_comment' =>    'nurse comment',
                'ask' =>              'T'
            ]
        ];

        $response = $this->postJsonTest($url, $data, 'validation');

        $response
            ->assertStatus(400)
            ->assertJson([
                'status' => 'validation',
                'errors' => [
                    $question1_id => [
                        'The response must be one of the following types: yes, no'
                    ],
                    $question2_id => [
                        'The response must be one of the following types: 1, 2, 3, 4, 5'
                    ]
                ]
            ]);
    }
}
