<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Http\Request;
use Illuminate\Foundation\Testing\RefreshDatabase;

class QuestionnaireDueTest extends TestCase
{
    /**
     * Read due questionnaires for a patient
     */
    public function test_questionnaire_due_get()
    {
        $url = $this->makeUrl('/v1/patient/{patient_id}/questionnaire_due');

        $response = $this->getJsonTest($url);

        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'data' => [
                    'questionnaire_due' => [[
                        'questionnaire_id' => 1,
                        'name' => 'test questionnaire',
                        'minimum_frequency' => 30,
                        'deleted' => 'F',
                        'questions' => [
                            [
                                'questionnaire_id' => 1,
                                'question_id' => 1,
                                'text' => 'yes / no question number 1',
                                'type' => 'yes,no',
                                'allow_multiple' => 'F'
                            ],
                            [
                                'questionnaire_id' => 1,
                                'question_id' => 2,
                                'text' => 'numeric question number 2',
                                'type' => '1,2,3,4',
                                'allow_multiple' => 'F'
                            ],
                            [
                                'questionnaire_id' => 1,
                                'question_id' => 3,
                                'text' => 'multi answer question number 3',
                                'type' => '1,2,3,4,5',
                                'allow_multiple' => 'T'
                            ],
                            [
                                'questionnaire_id' => 1,
                                'question_id' => 4,
                                'text' => 'text question number 3',
                                'type' => 'text',
                                'allow_multiple' => 'T'
                            ]
                        ]
                    ]]
                ]
            ]);
    }
}
