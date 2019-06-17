<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;

class PatientTest extends TestCase
{
    /**
     * Create patient.
     * @return void
     */
    public function test_patient_insert()
    {
        $url = $this->makeUrl('/v1/patient');

        $data = [
            "firstname" => "Test",
            "lastname" => "Xtract",
            "mi" => "Z",
            "dob" => "1970-01-01",
            "phone" => "+1-503-379-0110",
            "sms_phone" => "+1-503-379-0112",
            "fax" => "+1-503-379-0116",
            "addr1" => "9954 SW Arctic Ave.",
            "addr2" => "",
            "city" => "Beaverton",
            "state" => "Oregon",
            "zip" => "97005",
            "province" => "",
            "country" => "USA",
            "displayname" => "Inserted Patient",
            "chart" => '12345',
            "patient_notes" => "Patient forgot epipen last visit.",
            "login_notes" => "Patient prefers Nurse Betty.",
            "phone_note" => "",
            "email" => "johndoe@someplace.com",
            "pid_segment" => "",
            "pv1_segment" => "",
            "gender" => "F",
            "ssn" => "123456789",
            "e_contact_num" => "+1-503-379-0114",
            "e_contact" => "Suzie Q",
            "contact_by" => "sms"
        ];

        $response = $this->postJsonTest($url, $data);

        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'data' => [
                    'patient' => [
                        'firstname' => 'Test',
                        'lastname' => 'Xtract',
                        'dob' => '1970-01-01'
                    ]
                ]
            ]);

        $arr = $response->json();
        $patient = $arr['data']['patient'];
        $insertedId = $patient['patient_id'];

        $this->assertTrue($insertedId !== 0);
        return $insertedId;
    }

    /**
     * Create patient, fail due to: non-unique (first, last, middle, date of birth, chart), invalid contact, invalid SSN, bad format for e_contact_num, bad PV1 segment, bad PID segment, invalid gender, bad phone format, bad fax format..
     * @depend test_patient_insert
     * @return void
     */
    public function test_patient_insert_fail()
    {
        $url = $this->makeUrl('/v1/patient');

        $data = [
            "firstname" => "Test",
            "lastname" => "Xtract",
            "mi" => "Z",
            "dob" => "1970-01-01",
            "phone" => "+1-5X03-379-0110",
            "sms_phone" => "+1-503-379-0112",
            "fax" => "+1-5X03-379-0116",
            "addr1" => "9954 SW Arctic Ave.",
            "addr2" => "",
            "city" => "Beaverton",
            "state" => "Oregon",
            "zip" => "970X05",
            "province" => "",
            "country" => "USA",
            "displayname" => "Inserted Patient",
            "chart" => '12345',
            "patient_notes" => "Patient forgot epipen last visit.",
            "login_notes" => "Patient prefers Nurse Betty.",
            "phone_note" => "",
            "email" => "johndoe@someplace.com",
            "pid_segment" => "ASD",
            "pv1_segment" => "asd",
            "gender" => "V",
            "ssn" => "123456X789",
            "e_contact_num" => "+1-5X03-379-0114",
            "e_contact" => "Suzie Q",
            "contact_by" => "sXms"
        ];

        $response = $this->postJsonTest($url, $data, 'validation');

        $response
            ->assertStatus(400)
            ->assertJson([
                'status' => 'validation',
                'errors' => [
                    'firstname' => ['The combination of first, last, middle, date of birth, and chart must be unique in the system.'],
                    'contact_by' => ['The selected contact by is invalid.'],
                    'ssn' => ['The ssn format is invalid.'],
                    'e_contact_num' => ['The e contact num is not a valid phone number format. Should be +1-000-000-0000'],
                    'pv1_segment' => ['The pv1 segment must be empty or a string that starts with \'PV1\'.'],
                    'pid_segment' => ['The pid segment must be empty or a string that starts with \'PID\'.'],
                    'gender' => ['The selected gender is invalid.'],
                    'phone' => ['The phone is not a valid phone number format. Should be +1-000-000-0000'],
                    'fax' => ['The fax is not a valid phone number format. Should be +1-000-000-0000']
                ]
            ]);
    }

    /**
     * Read patient.
     * @depends test_patient_insert
     * @return void
     */
    public function test_patient_get($insertedId)
    {
        $url = $this->makeUrl('/v1/patient/{id}?fields=patient_id,firstname,lastname,mi,displayname,archived,provider_id,updated_at,created_at,last_visit,dob', $insertedId);

        $response = $this->getJsonTest($url);

        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'data' => [
                    'patient' => [
                        'firstname' => 'Test',
                        'lastname' => 'Xtract',
                        "mi" => "Z",
                        'dob' => '1970-01-01'
                    ],
                ]
            ]);
    }

    /**
     * Delete non-existent patient, fail.
     * Delete existent patient.
     * @depends test_patient_insert
     * @return void
     */
    public function test_patient_delete($insertedId)
    {
        $presentID = $insertedId;               // must exist in database
        static $nonPresentID = '123456789';     // must not exist in database

        $url = $this->makeUrl('/v1/patient/{id}', $nonPresentID);

        // test for deleting non-extant ID

        $response = $this->deleteJsonTest($url, [], 'fail');

        $response
            ->assertStatus(404);

        // test for deleting extant ID

        $url = $this->makeUrl('/v1/patient/{id}', $presentID);

        $response = $this->deleteJsonTest($url, []);

        $response
        ->assertStatus(200)
        ->assertJson([
            'status' => 'success',
            'data' => [
                'patient' => [
                    'archived' => 'T'
                ],
            ],
        ]);
    }

    /**
     * Update patient.
     * @depends test_patient_insert
     * @return void
     */
    public function test_patient_update($insertedId)
    {
        $url = $this->makeUrl('/v1/patient/{id}', $insertedId);

        $data = [
        'mi' => 'ZZ'
        ];

        // update the patient
        $response = $this->putJsonTest($url, $data);

        $response
        ->assertStatus(200)
        ->assertJson([
            'status' => 'success',
            'data' => [
                'patient' => [
                    'mi' => 'ZZ'
                ],
            ],
        ]);
    }

    /**
     * Search by name, dob, chart. 'xtract'
     * @return void
     */

    public function test_patient_search_by_name1()
    {
        $url = $this->makeUrl('/v1/patient/_search');

        $data = [
            'search' => 'xtract'
        ];

        $response = $this->postJsonTest($url, $data);

        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'data' => [
                    'patient' => [
                        'matches' => [
                            0 => ['firstname' => 'TEST']
                        ]
                    ]
                ]
            ]);

        $arr = $response->json();
        $patient = $arr['data']['patient'];
        $this->assertCount(2, $patient ['matches']);
    }

    /**
     * Search by name, dob, chart. 'xtract,test'
     * @return void
     */

    public function test_patient_search_by_name2()
    {
        $url = $this->makeUrl('/v1/patient/_search');

        $data = [
            'search' => 'xtract,test'
        ];

        $response = $this->postJsonTest($url, $data);

        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'data' => [
                    'patient' => [
                        'matches' => [
                            0 => ['firstname' => 'TEST']
                        ]
                    ]
                ]
            ]);

        $arr = $response->json();
        $patient = $arr['data']['patient'];
        $this->assertCount(2, $patient ['matches']);
    }

    /**
     * Search by name, dob, chart. 'xtract,test,q'
     * @return void
     */

    public function test_patient_search_by_name3()
    {
        $url = $this->makeUrl('/v1/patient/_search');

        $data = [
            'search' => 'xtract,test,q'
        ];

        $response = $this->postJsonTest($url, $data);

        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'data' => [
                    'patient' => [
                        'matches' => [
                            0 => ['firstname' => 'TEST']
                        ]
                    ]
                ]
            ]);

        $arr = $response->json();
        $patient = $arr['data']['patient'];
        $this->assertCount(2, $patient ['matches']);
    }

    /**
     * Search by name, dob, chart. 'xtract,test,q,000000'
     * @return void
     */

    public function test_patient_search_by_name4()
    {
        $url = $this->makeUrl('/v1/patient/_search');

        $data = [
            'search' => 'xtract,test,q,000000'
        ];

        $response = $this->postJsonTest($url, $data);

        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'data' => [
                    'patient' => [
                        'matches' => [
                            0 => ['firstname' => 'TEST']
                        ]
                    ]
                ]
            ]);

        $arr = $response->json();
        $patient = $arr['data']['patient'];
        $this->assertCount(1, $patient ['matches']);
    }

    /**
     * Search by name, dob, chart. 'xtract,test,q,000000,1970-01-01'
     * @return void
     */

    public function test_patient_search_by_name5()
    {
        $url = $this->makeUrl('/v1/patient/_search');

        $data = [
            'search' => 'xtract,test,q,000000,1970-01-01'
        ];

        $response = $this->postJsonTest($url, $data);

        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'data' => [
                    'patient' => [
                        'matches' => [
                            0 => ['firstname' => 'TEST']
                        ]
                    ]
                ]
            ]);

        $arr = $response->json();
        $patient = $arr['data']['patient'];
        $this->assertCount(1, $patient ['matches']);
    }

    /**
     * Search by name, dob, chart. 'xtract,test,q,000000,1970-0'
     * @return void
     */

    public function test_patient_search_by_name6()
    {
        $url = $this->makeUrl('/v1/patient/_search');

        $data = [
            'search' => 'xtract,test,q,000000,1970-0'
        ];

        $response = $this->postJsonTest($url, $data);

        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'data' => [
                    'patient' => [
                        'matches' => [
                            0 => ['firstname' => 'TEST']
                        ]
                    ]
                ]
            ]);

        $arr = $response->json();
        $patient = $arr['data']['patient'];
        $this->assertCount(1, $patient ['matches']);
    }

    /**
     * Search by name, dob, chart. 'xtra'
     * @return void
     */

    public function test_patient_search_by_name7()
    {
        $url = $this->makeUrl('/v1/patient/_search');

        $data = [
            'search' => 'xtra'
        ];

        $response = $this->postJsonTest($url, $data);

        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'data' => [
                    'patient' => [
                        'matches' => [
                            0 => ['lastname' => 'XTRACT']
                        ]
                    ]
                ]
            ]);

        $arr = $response->json();
        $patient = $arr['data']['patient'];
        $this->assertCount(2, $patient ['matches']);
    }

    /**
     * Search by name, dob, chart. 'xtract,test,q,0000'
     * @return void
     */

    public function test_patient_search_by_name8()
    {
        $url = $this->makeUrl('/v1/patient/_search');

        $data = [
            'search' => 'xtract,test,q,0000'
        ];

        $response = $this->postJsonTest($url, $data);

        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'data' => [
                    'patient' => [
                        'matches' => [
                            0 => ['firstname' => 'TEST']
                        ]
                    ]
                ]
            ]);

        $arr = $response->json();
        $patient = $arr['data']['patient'];
        $this->assertCount(2, $patient ['matches']);
    }

    /**
     * Search by name, dob, chart. ',test,q,0000'
     * @return void
     */

    public function test_patient_search_by_name9()
    {
        $url = $this->makeUrl('/v1/patient/_search');

        $data = [
            'search' => ',test,q,0000'
        ];

        $response = $this->postJsonTest($url, $data);

        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'data' => [
                    'patient' => [
                        'matches' => [
                            0 => ['firstname' => 'TEST']
                        ]
                    ]
                ]
            ]);

        $arr = $response->json();
        $patient = $arr['data']['patient'];
        $this->assertCount(2, $patient ['matches']);
    }

    /**
     * Search by name, dob, chart. 'xtract,test,q,0002'
     * @return void
     */

    public function test_patient_search_by_name10()
    {
        $url = $this->makeUrl('/v1/patient/_search');

        // verify we don't find this

        $data = [
            'search' => 'xtract,test,q,0002'
        ];

        $response = $this->postJsonTest($url, $data);

        $response
            ->assertStatus(200);

        $arr = $response->json();
        $patient = $arr['data']['patient'];

        $this->assertCount(0, $patient ['matches']);
    }

    /**
     * Search by name, dob, chart. 'xtrat,test'
     * @return void
     */

    public function test_patient_search_by_name11()
    {
        $url = $this->makeUrl('/v1/patient/_search');

        // verify we don't find this

        $data = [
            'search' => 'xtrat,test'
        ];

        $response = $this->postJsonTest($url, $data);

        $response
            ->assertStatus(200);

        $arr = $response->json();
        $patient = $arr['data']['patient'];

        $this->assertCount(0, $patient ['matches']);
    }

    /**
     * Search by number.
     * @return void
     */

    public function test_patient_search_by_number()
    {
        $url = $this->makeUrl('/v1/patient/_search');

        $data = [
            'number' => '900002'
        ];

        $response = $this->postJsonTest($url, $data);

        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'data' => [
                    'patient' => [
                        'matches' => [
                            0 => ['firstname' => 'TEST']
                        ]
                    ]
                ]
            ]);

        $data = [
            'number' => '2'
        ];

        $response = $this->postJsonTest($url, $data);

        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'data' => [
                    'patient' => [
                        'matches' => [
                            0 => ['firstname' => 'TEST']
                        ]
                    ]
                ]
            ]);

        $data = [
            'number' => '100004'
        ];

        $response = $this->postJsonTest($url, $data);

        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'data' => [
                    'patient' => [
                        'matches' => [
                            0 => ['firstname' => 'TEST']
                        ]
                    ]
                ]
            ]);
    }
}
