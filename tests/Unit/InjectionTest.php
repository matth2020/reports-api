<?php

namespace Tests\Unit;
use Tests\TestCase;
use App\Models\Compound;
use App\Models\Injection;
use App\Models\Prescription;
use App\Models\DosingPlanDetails;
use App\Models\TreatPlanDetails;
use App\Models\TreatmentPlan;

/*
 * Code notes from Andrew
 * 1) there are two ways the next step is determined (before the dose rules
 * layer is added).
 *     A) If injection.treatment_plan_id of the last injection matches
 * prescription.treatment_plan_id then the treatment plan hasn't saved and we can
 * just increment the injection.tpdetails_id from the last injection to get to the
 * next step (not to exceed the top of the tp) so testing with unchanged
 * treatment plans should always hit this case.
 *     B) If injection.treatment_plan_id of the last injection is different from
 * prescription.treatment_plan_id then the treatment plan has been changed. In
 * this case, I have to try to find the step in the new plan that most closely
 * matches the dose and dilution of the last injection. This process is a lot
 * more complicated so it would be great to have a handful of tests that use a
 * different tp_id on the previous injection to ensure we are testing this
 * algorithm. The injection following a manual adjust also hits this algorithm.
 * And since an injection can be manually adjusted to a step that doesn't exist
 * in the tp (for example, they might injectino .23 when the tp only has .2 or .3
 * as steps) the algorithm should default to the next lowest step if there is
 * no exact match or multiple matches.
 */
class InjectionTest extends TestCase
{
    protected function wipeClean()
    {
        // delete all the injections we find - clean slate

        $url = $this->makeUrl('/v1/patient/{patient_id}/injection');

        $response = $this->getJsonTest($url);

        $response->assertStatus(200);

        foreach ($response->json()['data']['injection'] as $key => $injection) {
            $url = $this->makeUrl('/v1/patient/{patient_id}/injection/{id}', $injection['injection_id']);
            $response = $this->deleteJsonTest($url, []);
            $response->assertStatus(200);
        }
    }

    /**
     * Create injection that references an inactive vial, fail
     */
    public function test_injection_create_fail_inactive()
    {
        $this->wipeClean();

        $url = $this->makeUrl('/v1/patient/{patient_id}/injection');

        $data = [
            'dose' => '0.20',
            'site' => 'upperR',
            'notes_user' => 'insert_inactive',
            'vial_id' => '5'
        ];

        $response = $this->postJsonTest($url, $data, 'validation');

        $response
            ->assertStatus(400)
            ->assertJson([
                'status' => 'validation',
                'errors' => [
                    'vial_id' => [
                        'The requested vial does not exist or is not active.'
                    ]
                ]
            ]);
    }

    /**
     * Create injection that references has no vial or barcode, fail
     */
    public function test_injection_create_fail_no_vial_or_barcode()
    {
        $url = $this->makeUrl('/v1/patient/{patient_id}/injection');

        $data = [
            'notes_user' => 'insert_no_vial_or_barcode',
            'dose' => '0.20',
            'site' => 'upperR'
        ];

        $response = $this->postJsonTest($url, $data, 'validation');

        $response
            ->assertStatus(400)
            ->assertJson([
                'status' => 'validation',
                'errors' => [
                    'barcode' => [
                        'The barcode field is required when vial id is not present.'
                    ],
                    'vial_id' => [
                        'The vial id field is required when barcode is not present.'
                    ]
                ]
            ]);
    }

    /**
     * Create injection that references a non-existent vial, fail
     */
    public function test_injection_create_fail_no_such_vial()
    {
        $url = $this->makeUrl('/v1/patient/{patient_id}/injection');

        $data = [
            'dose' => '0.20',
            'site' => 'upperR',
            'notes_user' => 'insert_no_such_vial',
            'vial_id' => 'asdf'
        ];

        $response = $this->postJsonTest($url, $data, 'validation');

        $response
            ->assertStatus(400)
            ->assertJson([
                'status' => 'validation',
                'errors' => [
                    'vial_id' => [
                        'The requested vial does not exist or is not active.'
                    ]
                ]
            ]);
    }

    /**
     * Create injection that references a non-existent barcode, fail
     */
    public function test_injection_create_fail_no_such_barcode()
    {
        $url = $this->makeUrl('/v1/patient/{patient_id}/injection');

        $data = [
            'dose' => '0.20',
            'site' => 'upperR',
            'notes_user' => 'insert_no_such_barcode',
            'barcode' => 'asdf'
        ];

        $response = $this->postJsonTest($url, $data, 'validation');

        $response
            ->assertStatus(400)
            ->assertJson([
                'status' => 'validation',
                'errors' => [
                    'barcode' => [
                        'The selected barcode is invalid.'
                    ]
                ]
            ]);
    }

    /**
     * Create injection that references a barcode with too few digits, fail
     */
    public function test_injection_create_fail_barcode_too_short()
    {
        $url = $this->makeUrl('/v1/patient/{patient_id}/injection');

        $data = [
            'dose' => '0.20',
            'site' => 'upperR',
            'notes_user' => 'insert_not_enough_digits_in_barcode',
            'barcode' => '10000'
        ];

        $response = $this->postJsonTest($url, $data, 'validation');

        $response
            ->assertStatus(400)
            ->assertJson([
                'status' => 'validation',
                'errors' => [
                    'barcode' => [
                        'The selected barcode is invalid.'
                    ]
                ]
            ]);
    }


    /**
     * Create injection that references a good barcode, pass
     */
    public function test_injection_create_good_barcode()
    {
        $this->wipeClean();
        
        $url = $this->makeUrl('/v1/patient/{patient_id}/injection');

        $todaysDate = date('Y-m-d H:i:s', mktime(0, 0, 0, date("m"), date("d"), date("Y")));

        $data = [
            'dose' => '0.050',
            'site' => 'upperR',
            'notes_user' => 'insert_barcode',
            'barcode' => '100000',
            'datetime_administered' => $todaysDate
        ];

        $response = $this->postJsonTest($url, $data);

        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'data' => [
                    'injection' => [
                        'dose' => '0.050',
                        'site' => 'upperR',
                        'notes_user' => 'insert_barcode',
                        'datetime_administered' => $todaysDate,
                        'deleted' => 'F'
                    ]
                ]
            ]);
    }

    
    /**
     * Create injection that references a non-existent site, fail
     */
    public function test_injection_create_fail_no_such_site()
    {
        $url = $this->makeUrl('/v1/patient/{patient_id}/injection');

        $data = [
            'dose' => '0.20',
            'notes_user' => 'insert_no_such_site',
            'site' => 'bad_site'
        ];

        $response = $this->postJsonTest($url, $data, 'validation');

        $response
            ->assertStatus(400)
            ->assertJson([
                'status' => 'validation',
                'errors' => [
                    'site' => [
                        'Site must be in upperL,upperR,lowerL,lowerR,midL,midR,other'
                    ]
                ]
            ]);
    }

    /**
     * Create injection that doesn't have a site or dose, fail
     */
    public function test_injection_create_fail_missing_site_and_dose()
    {
        $url = $this->makeUrl('/v1/patient/{patient_id}/injection');

        $data = [
        ];

        $response = $this->postJsonTest($url, $data, 'validation');

        $response
            ->assertStatus(400)
            ->assertJson([
                'status' => 'validation',
                'errors' => [
                    'site' => [
                        'The site field is required.'
                    ],
                    'dose' => [
                        'The dose field is required.'
                    ]
                ]
            ]);
    }

    /**
     * Create injection that has a negative dose, fail
     */
    public function test_injection_create_fail_negative_dose()
    {
        $url = $this->makeUrl('/v1/patient/{patient_id}/injection');

        $data = [
            'dose' => '-.50',
            'site' => 'upperL',
            'vial_id' => '1',
            'notes_user' => 'insert_dose_is_negative'
        ];

        $response = $this->postJsonTest($url, $data, 'validation');

        $response
            ->assertStatus(400)
            ->assertJson([
                'status' => 'validation',
                'errors' => [
                    'dose' => [
                        'The dose field must be non-negative.',
                        'validation.tp_dose_dilution'
                    ]
                ],
                'confirmation_required' => [
                    'override_dose_warning' => [
                        "The provided dose does not match the predicted dose. Please ensure the value is correct and attach the override_dose_warning property with a value of T to continue."
                    ],
                    'override_date_warning' => [
                        "The indicated injection is not due per treatment plan. To record this injection anyway, attach the override_date_warning property with a value of T to continue."
                    ]
                ]
            ]);
    }

    /**
     * Create injection that has a bad dilution, fail
     */
    public function test_injection_create_fail_bad_dilution()
    {
        $url = $this->makeUrl('/v1/patient/{patient_id}/injection');

        $data = [
            'dose' => '0.20',
            'notes_user' => 'no_such_dilution',
            'site' => 'upperR',
            'vial_id' => '1',
            'dilution' => '100'
        ];

        $response = $this->postJsonTest($url, $data, 'validation');

        $response
            ->assertStatus(400)
            ->assertJson([
                'status' => 'validation',
                'confirmation_required' => [
                    'override_dose_warning' => [
                        'The provided dose does not match the predicted dose. Please ensure the value is correct and attach the override_dose_warning property with a value of T to continue.'
                    ]
                ]
            ]);
    }

    /**
     * Create injection that has bad reaction names, fail
     */
    public function test_injection_create_fail_bad_reactions()
    {
        $url = $this->makeUrl('/v1/patient/{patient_id}/injection');

        $data = [
            'notes_user' => 'insert_bad_reactions',
            'systemic_reaction' => 'bogus',
            'local_reaction' => 'bogus also'
        ];

        $response = $this->postJsonTest($url, $data, 'validation');

        $response
            ->assertStatus(400)
            ->assertJson([
                'status' => 'validation',
                'errors' => [
                    'local_reaction' => [
                        'The provided local reaction is not defined in this system.'
                    ],
                    'systemic_reaction' => [
                        'The provided systemic reaction is not defined in this system.'
                    ],
                ]
            ]);
    }

    /**
     * Create injection that has bad deleted flag, fail
     */
    public function test_injection_create_fail_bad_deleted()
    {
        $url = $this->makeUrl('/v1/patient/{patient_id}/injection');

        $data = [
            'notes_user' => 'insert_bad_deleted',
            'deleted' => 'bogus'
        ];

        $response = $this->postJsonTest($url, $data, 'validation');

        $response
            ->assertStatus(400)
            ->assertJson([
                'status' => 'validation',
                'errors' => [
                    'deleted' => [
                        'The selected deleted is invalid.'
                    ]
                ]
            ]);
    }

    /**
     * Create injection that has a long notes_user, fail
     */
    public function test_injection_create_fail_long_notes_user()
    {
        $url = $this->makeUrl('/v1/patient/{patient_id}/injection');

        $data = [
            'notes_user' => 'this is definitely too long for the data base!'
        ];

        $response = $this->postJsonTest($url, $data, 'validation');

        $response
            ->assertStatus(400)
            ->assertJson([
                'status' => 'validation',
                'errors' => [
                    'notes_user' => [
                        'The notes user must be between 0 and 45 characters.'
                    ]
                ]
            ]);
    }

    /**
     * Create injection with a dose that is out of plan, fail
     */
    public function test_injection_create_fail_dose_out_of_plan()
    {
        $url = $this->makeUrl('/v1/patient/{patient_id}/injection');

        $data = [
            'dose' => '1.0',
            'site' => 'upperR',
            'notes_user' => 'insert_dose_out_of_plan',
            'vial_id' => '1'
        ];

        $response = $this->postJsonTest($url, $data, 'validation');

        $response
            ->assertStatus(400)
            ->assertJson([
                'status' => 'validation',
                'confirmation_required' => [
                    'override_dose_warning' => [
                        'The provided dose does not match the predicted dose. Please ensure the value is correct and attach the override_dose_warning property with a value of T to continue.'
                    ]
                ]
            ]);
    }

    /**
     * Create injection with a dilution that is out of plan, fail
     */
    public function test_injection_create_fail_dilution_out_of_plan()
    {
        $url = $this->makeUrl('/v1/patient/{patient_id}/injection');

        $data = [
            'dose' => '0.050',
            'site' => 'upperR',
            'notes_user' => 'insert_dilution_out_of_plan',
            'vial_id' => '2'
        ];

        $response = $this->postJsonTest($url, $data, 'validation');

        $response
            ->assertStatus(400)
            ->assertJson([
                'status' => 'validation',
                'confirmation_required' => [
                    'override_dilution_warning' => [
                        'The provided dilution does not match the predicted dilution. Please ensure the barcode and/or vial_id is correct and attach the override_dilution_warning property with a value of T to continue.'
                    ]
                ]
            ]);
    }

    /**
     * Create injection in the future, fail
     */
    public function test_injection_create_fail_in_future()
    {
        $url = $this->makeUrl('/v1/patient/{patient_id}/injection');

        $futureDate = date('Y-m-d H:i:s', mktime(0, 0, 0, date("m"), date("d")+1, date("Y")));

        $data = [
            'dose' => '0.050',
            'site' => 'upperR',
            'notes_user' => 'insert_dilution_out_of_plan',
            'vial_id' => '2',
            'datetime_administered' => $futureDate
        ];

        $response = $this->postJsonTest($url, $data, 'validation');

        $response
            ->assertStatus(400)
            ->assertJson([
                'status' => 'validation',
                'errors' => [
                    'datetime_administered' => []
                ]
            ]);
    }

    /**
     * Create injection that uses a future year in date, fail
     */
    public function test_injection_create_fail_future_year_invalid()
    {
        $this->wipeClean();
        
        $url = $this->makeUrl('/v1/patient/{patient_id}/injection');

        $todaysDate = date('Y-m-d H:i:s', mktime(23, 59, 59, date("m"), date("d"), date("Y")));

        $data = [
            'dose' => '0.30',
            'site' => 'upperR',
            'notes_user' => 'insert_date_future_year_invalid',
            'vial_id' => '1',
            'datetime_administered' => "2030-12-05 12:10:10"
        ];

        $response = $this->postJsonTest($url, $data, 'validation');

        $response
            ->assertStatus(400)
            ->assertJson([
                'status' => 'validation',
                'errors' => [
                    'datetime_administered' => [
                        'The datetime administered must be a date before '.$todaysDate.'.'
                    ]
                ]
            ]);
    }

    /**
     * Create injection that uses a bad month in date, fail
     */
    public function test_injection_create_fail_month_invalid()
    {
        $this->wipeClean();
        
        $url = $this->makeUrl('/v1/patient/{patient_id}/injection');

        $data = [
            'dose' => '0.30',
            'site' => 'upperR',
            'notes_user' => 'insert_date_month_invalid',
            'vial_id' => '1',
            'datetime_administered' => "2018-14-05 12:10:10"
        ];

        $response = $this->postJsonTest($url, $data, 'validation');

        $response
            ->assertStatus(400)
            ->assertJson([
                'status' => 'validation',
                'errors' => [
                    'datetime_administered' => [
                        'The datetime administered does not match the format Y-m-d H:i:s.'
                    ]
                ]
            ]);
    }

    /**
     * Create injection that uses a bad day in date, fail
     */
    public function test_injection_create_fail_day_invalid()
    {
        $this->wipeClean();
        
        $url = $this->makeUrl('/v1/patient/{patient_id}/injection');

        $data = [
            'dose' => '0.30',
            'site' => 'upperR',
            'notes_user' => 'insert_date_day_invalid',
            'vial_id' => '1',
            'datetime_administered' => "2018-10-34 12:10:10"
        ];

        $response = $this->postJsonTest($url, $data, 'validation');

        $response
            ->assertStatus(400)
            ->assertJson([
                'status' => 'validation',
                'errors' => [
                    'datetime_administered' => [
                        'The datetime administered does not match the format Y-m-d H:i:s.'
                    ]
                ]
            ]);
    }

    /**
     * Create injection that uses too few digits in date, fail
     */
    public function test_injection_create_fail_too_few_digits_in_date()
    {
        $this->wipeClean();
        
        $url = $this->makeUrl('/v1/patient/{patient_id}/injection');

        $data = [
            'dose' => '0.30',
            'site' => 'upperR',
            'notes_user' => 'insert_date_digits_too_few',
            'vial_id' => '1',
            'datetime_administered' => "2018-1-05 12:10:10"
        ];

        $response = $this->postJsonTest($url, $data, 'validation');

        $response
            ->assertStatus(400)
            ->assertJson([
                'status' => 'validation',
                'errors' => [
                    'datetime_administered' => [
                        'The datetime administered does not match the format Y-m-d H:i:s.'
                    ]
                ]
            ]);
    }

    
    /**
     * Create injection with a datetime_administered that won't parse because of bad date, fail
     */
    public function test_injection_create_fail_bad_datetime_administered_date()
    {
        $url = $this->makeUrl('/v1/patient/{patient_id}/injection');

        $data = [
            'dose' => '0.050',
            'site' => 'upperR',
            'vial_id' => '2',
            'notes_user' => 'insert_bad_datetime_administered',
            'datetime_administered' => '20X7-08-23 01:02:03'
        ];

        $response = $this->postJsonTest($url, $data, 'validation');

        $response
            ->assertStatus(400)
            ->assertJson([
                'status' => 'validation'
            ]);
    }

    /**
     * Create injection with a datetime_administered that won't parse because of bad time, fail
     */
    public function test_injection_create_fail_bad_datetime_administered_time()
    {
        $url = $this->makeUrl('/v1/patient/{patient_id}/injection');

        $data = [
            'dose' => '0.050',
            'site' => 'upperR',
            'vial_id' => '2',
            'notes_user' => 'insert_bad_datetime_administered',
            'datetime_administered' => '2017-08-23 01:02:X3'
        ];

        $response = $this->postJsonTest($url, $data, 'validation');

        $response
            ->assertStatus(400)
            ->assertJson([
                'status' => 'validation'
            ]);
    }

    /**
     * Create injection - the first in the treatment plan.
     */
    public function test_injection_create_good()
    {
        $this->wipeClean();
        
        $url = $this->makeUrl('/v1/patient/{patient_id}/injection');

        $twoDaysAgoDate = date('Y-m-d H:i:s', mktime(0, 0, 0, date("m"), date("d")-2, date("Y")));

        $data = [
            'dose' => '0.050',
            'site' => 'upperR',
            'notes_user' => 'Patient forgot epipen.',
            'notes_patient' => 'patient notes',
            'systemic_reaction' => 'N',
            'local_reaction' => 'None',
            'vial_id' => '1',
            'attending' => 'Dr. Smith',
            'datetime_administered' => $twoDaysAgoDate
        ];

        $response = $this->postJsonTest($url, $data);

        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'data' => [
                    'injection' => [
                        'dose' => '0.050',
                        'site' => 'upperR',
                        'notes_user' => 'Patient forgot epipen.',
                        'notes_patient' => 'patient notes',
                        'systemic_reaction' => 'N',
                        'local_reaction' => 'None',
                        'deleted' => 'F',
                        'attending' => 'Dr. Smith'
                    ]
                ]
            ]);

        $arr = $response->json();
        $dosing_plan = $arr['data']['injection'];
        $insertedId = $dosing_plan['injection_id'];

        $this->assertTrue($insertedId !== 0);
        return $insertedId;
    }

    /**
     * Read one injection.
     * @depends test_injection_create_good
     */
    public function test_injection_read_one($insertedId)
    {
        $url = $this->makeUrl('/v1/patient/{patient_id}/injection/{id}', $insertedId);

        $twoDaysAgoDate = date('Y-m-d H:i:s', mktime(0, 0, 0, date("m"), date("d")-2, date("Y")));

        $response = $this->getJsonTest($url);

        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'data' => [
                    'injection' => [
                        'injection_id' => $insertedId,
                        'dose' => '0.050',
                        'site' => 'upperR',
                        'notes_user' => 'Patient forgot epipen.',
                        'notes_patient' => 'patient notes',
                        'systemic_reaction' => 'N',
                        'local_reaction' => 'None',
                        'deleted' => 'F',
                        'attending' => 'Dr. Smith',
                        'datetime_administered' => $twoDaysAgoDate
                    ]
                ]
            ]);
    }

    /**
     * Create injection - the second in the treatment plan.
     * @depends test_injection_create_good
     */
    public function test_injection_create_good_second()
    {
        $url = $this->makeUrl('/v1/patient/{patient_id}/injection');

        $todaysDate = date('Y-m-d H:i:s', mktime(0, 0, 0, date("m"), date("d"), date("Y")));

        $data = [
            'dose' => '0.10',
            'site' => 'upperR',
            'notes_user' => 'Patient feeling okay.',
            'notes_patient' => 'patient notes',
            'systemic_reaction' => 'N',
            'local_reaction' => 'None',
            'vial_id' => '1',
            'attending' => 'Dr. Jones',
            'datetime_administered' => $todaysDate
        ];

        $response = $this->postJsonTest($url, $data);

        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'data' => [
                    'injection' => [
                        'dose' => '0.10',
                        'site' => 'upperR',
                        'notes_user' => 'Patient feeling okay.',
                        'notes_patient' => 'patient notes',
                        'systemic_reaction' => 'N',
                        'local_reaction' => 'None',
                        'deleted' => 'F',
                        'attending' => 'Dr. Jones'
                    ]
                ]
            ]);

        $arr = $response->json();
        $dosing_plan = $arr['data']['injection'];
        $insertedId2 = $dosing_plan['injection_id'];

        $this->assertTrue($insertedId2 !== 0);
        return $insertedId2;
    }
    
    /**
    * Read all injections.
    * @depends test_injection_create_good
    * @depends test_injection_create_good_second
    */
    public function test_injection_read_all($insertedId, $insertedId2)
    {
        $url = $this->makeUrl('/v1/patient/{patient_id}/injection');

        $response = $this->getJsonTest($url);

        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'data' => [
                    'injection' => [
                        [
                            'injection_id' => $insertedId2,
                            'dose' => '0.10',
                            'site' => 'upperR',
                            'notes_user' => 'Patient feeling okay.',
                            'systemic_reaction' => 'N',
                            'local_reaction' => 'None',
                            'deleted' => 'F'
                        ],
                        [
                            'injection_id' => $insertedId,
                            'dose' => '0.050',
                            'site' => 'upperR',
                            'notes_user' => 'Patient forgot epipen.',
                            'notes_patient' => 'patient notes',
                            'systemic_reaction' => 'N',
                            'local_reaction' => 'None',
                            'deleted' => 'F',
                            'attending' => 'Dr. Smith'
                        ]
                    ]
                ]
            ]);
    }

    /**
     * Update an injection.
     * @depends test_injection_create_good
     */
    public function test_injection_update($insertedId)
    {
        $url = $this->makeUrl('/v1/patient/{patient_id}/injection/{id}', $insertedId);

        $data = [
            'dose' => '0.30'
        ];

        $response = $this->putJsonTest($url, $data);

        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'data' => [
                    'injection' => [
                        'injection_id' => $insertedId,
                        'dose' => '0.300'
                    ]
                ]
            ]);
    }

    /**
     * Search for injections.
     * @depends test_injection_create_good
     */
    public function test_injection_search($insertedId)
    {
        $url = $this->makeUrl('/v1/patient/{patient_id}/injection/_search');

        $data = [
            'dose' => '0.30',
            'site' => 'upperR',
            'notes_user' => 'Patient forgot epipen.',
            'notes_patient' => 'patient notes',
            'systemic_reaction' => 'N'
        ];

        $response = $this->postJsonTest($url, $data);

        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'data' => [
                    'injection' => [
                        [
                            'injection_id' => $insertedId,
                            'dose' => '0.30',
                            'site' => 'upperR',
                            'notes_user' => 'Patient forgot epipen.',
                            'notes_patient' => 'patient notes',
                            'systemic_reaction' => 'N',
                            'local_reaction' => 'None',
                            'deleted' => 'F',
                            'attending' => 'Dr. Smith'
                        ]
                    ]
                ]
            ]);
    }

    /**
     * Delete injection.
     * @depends test_injection_create_good
     */
    public function test_injection_delete($insertedId)
    {
        $url = $this->makeUrl('/v1/patient/{patient_id}/injection/{id}', $insertedId);

        $response = $this->deleteJsonTest($url, []);

        $response->assertStatus(200);

        //verify it's gone

        $response = $this->getJsonTest($url);

        $response
            ->assertStatus(200)
            ->assertJson([
                'data' => [
                    'injection' => [
                        'deleted' => 'T'
                    ]
                ]
            ]);
    }

    /*************************************************************************************
     *
     * Now let's have some fun with dates
     *
     ************************************************************************************/

    /**
     * Create injection with a datetime_administered that is on the same day, fail
     */
    public function test_injection_create_fail_datetime_administered_too_soon()
    {
        $this->wipeClean();
        
        // insert first dose on today

        $url = $this->makeUrl('/v1/patient/{patient_id}/injection');

        $todaysDate = date('Y-m-d H:i:s', mktime(0, 0, 0, date("m"), date("d"), date("Y")));

        $data = [
            'dose' => '0.050',
            'site' => 'upperR',
            'vial_id' => '1',
            'datetime_administered' => $todaysDate
        ];

        $response = $this->postJsonTest($url, $data);

        $response->assertStatus(200);

        $arr = $response->json();
        $firstId = $arr['data']['injection']['injection_id'];

        // try to insert another (next dose) on today

        $url = $this->makeUrl('/v1/patient/{patient_id}/injection');

        $data = [
            'dose' => '0.100',
            'site' => 'upperR',
            'vial_id' => '1',
            'notes_patient' => 'test_injection_create_fail_datetime_administered_too_soon',
            'datetime_administered' => $todaysDate
        ];

        $response = $this->postJsonTest($url, $data, 'validation');

        $response
            ->assertStatus(400)
            ->assertJson([
                'status' => 'validation',
                'confirmation_required' => [
                    'override_date_warning' => [
                        'The indicated injection is not due per treatment plan. To record this injection anyway, attach the override_date_warning property with a value of T to continue.'
                    ]
                ]
            ]);

        // now try it again with the override

        $data = [
            'dose' => '0.100',
            'site' => 'upperR',
            'vial_id' => '1',
            'datetime_administered' => $todaysDate,
            'notes_patient' => 'test_injection_create_fail_datetime_administered_too_soon_override',
            'override_date_warning' => 'Y'
        ];

        $response = $this->postJsonTest($url, $data, 'success');

        $expected = $data;
        unset($expected['override_date_warning']);

        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'data' => [
                    'injection' => $expected
                ]
            ]);

        $arr = $response->json();
        $secondId = $arr['data']['injection']['injection_id'];

        // now delete the injection

        $url = $this->makeUrl('/v1/patient/{patient_id}/injection/{id}', $firstId);

        $response = $this->deleteJsonTest($url, []);

        $response->assertStatus(200);

        $url = $this->makeUrl('/v1/patient/{patient_id}/injection/{id}', $secondId);

        $response = $this->deleteJsonTest($url, []);

        $response->assertStatus(200);
    }

    public function Make_injection_create_days_ago($daysSince, $result = 'success')
    {
        // insert first dose on $daysAgo days before today

        $url = $this->makeUrl('/v1/patient/{patient_id}/injection');

        $daysAgo = date('Y-m-d H:i:s', mktime(0, 0, 0, date("m"), date("d") - $daysSince, date("Y")));

        $data = [
            'dose' => '0.050',
            'site' => 'upperR',
            'vial_id' => '1',
            'notes_patient' => 'test_injection_create_fail_datetime_administered_min_interval',
            'datetime_administered' => $daysAgo
        ];

        $response = $this->postJsonTest($url, $data);

        $response->assertStatus(200);

        $arr = $response->json();
        $firstId = $arr['data']['injection']['injection_id'];

        // try to insert another (next dose) on today

        $data = [
            'dose' => '0.100',
            'site' => 'upperR',
            'vial_id' => '1',
            'notes_patient' => 'create_' . $daysSince . "_ago"
        ];

        $response = $this->postJsonTest($url, $data, $result == 'success' ? 'success' : 'validation');

        // if we're supposed to succeed, verify that we did

        if ($result == 'success') {
            \Log::info (json_encode($result));
            $response
                ->assertStatus(200)
                ->assertJson([
                    'status' => 'success',
                    'data' => [
                        'injection' => $data
                    ]
                ]);

            $arr = $response->json();
            $secondId = $arr['data']['injection']['injection_id'];

            // delete the second injection

            $url = $this->makeUrl('/v1/patient/{patient_id}/injection/{id}', $secondId);

            $response = $this->deleteJsonTest($url, []);

            $response->assertStatus(200);
        } else {
            $response
                ->assertStatus(400)
                ->assertJson([
                    'confirmation_required' => [
                        $result => []
                    ]
                ]);
        }

        // delete the first injection

        $url = $this->makeUrl('/v1/patient/{patient_id}/injection/{id}', $firstId);

        $response = $this->deleteJsonTest($url, []);

        $response->assertStatus(200);
    }

    /**
     * Create injection with a datetime_administered that is N days out from previous one, where N in 0..12
     * @depends test_injection_delete
     */
    public function test_injection_create_days_since()
    {
        $this->wipeClean();

        $this->Make_injection_create_days_ago(0, 'override_date_warning');
        $this->Make_injection_create_days_ago(1, 'override_date_warning');
        $this->Make_injection_create_days_ago(2);
        $this->Make_injection_create_days_ago(3);
        $this->Make_injection_create_days_ago(4);
        $this->Make_injection_create_days_ago(5);
        $this->Make_injection_create_days_ago(6);
        $this->Make_injection_create_days_ago(7);
        $this->Make_injection_create_days_ago(8);
        $this->Make_injection_create_days_ago(9);
        $this->Make_injection_create_days_ago(10);
        $this->Make_injection_create_days_ago(11, 'override_dose_warning');
        $this->Make_injection_create_days_ago(12, 'override_dose_warning');
        // $this->Make_injection_create_days_ago(32, 'override_dose_warning');
        // $this->Make_injection_create_days_ago(400);

    }

    /**
     * Create injection with an out of plan dilution, but override
     * @depends test_injection_create_days_since
     */
    public function test_injection_create_override_dilution_out_of_plan()
    {
        $url = $this->makeUrl('/v1/patient/{patient_id}/injection');

        $data = [
            'dose' => '0.050',
            'site' => 'upperR',
            'vial_id' => '2',
            'override_dilution_warning' => 'Y'
        ];

        $response = $this->postJsonTest($url, $data);

        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'data' => [
                    'injection' => [
                        'dose' => '0.050',
                        'site' => 'upperR',
                        'vial_id' => '2'
                    ]
                ]
            ]);

        // delete the injection

        $arr = $response->json();
        $insertedId = $arr['data']['injection']['injection_id'];

        $url = $this->makeUrl('/v1/patient/{patient_id}/injection/{id}', $insertedId);

        $response = $this->deleteJsonTest($url, []);

        $response->assertStatus(200);
    }

    /**
     * Create injection with a dose that is out of plan, but override
     * @depends test_injection_create_days_since
     */
    public function test_injection_create_fail_dose_out_of_plan_override()
    {
        $url = $this->makeUrl('/v1/patient/{patient_id}/injection');

        $data = [
            'dose' => '0.10',
            'site' => 'upperR',
            'notes_user' => 'insert_dose_out_of_plan',
            'vial_id' => '1',
            'override_dose_warning' => 'Y'
        ];

        $response = $this->postJsonTest($url, $data);

        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'data' => [
                    'injection' => [
                        'dose' => '0.10',
                        'site' => 'upperR',
                        'vial_id' => '1'
                    ]
                ]
            ]);

        // delete the injection

        $arr = $response->json();
        $insertedId = $arr['data']['injection']['injection_id'];

        $url = $this->makeUrl('/v1/patient/{patient_id}/injection/{id}', $insertedId);

        $response = $this->deleteJsonTest($url, []);

        $response->assertStatus(200);
    }

    /**
     * Create injection with a dose that is out of plan, but is in the past
     */
    public function test_injection_create_fail_dose_out_of_plan_in_past()
    {
        $this->wipeClean();
        
        $url = $this->makeUrl('/v1/patient/{patient_id}/injection');

        $backDate = date('Y-m-d H:i:s', mktime(0, 0, 0, date("m"), date("d")-1, date("Y")));

        $data = [
            'dose' => '0.20',
            'site' => 'upperR',
            'notes_user' => 'insert_dose_out_of_plan',
            'vial_id' => '2',
            'override_dose_warning' => 'Y',
            'datetime_administered' => $backDate
        ];

        $response = $this->postJsonTest($url, $data);

        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'data' => [
                    'injection' => [
                        'dose' => '0.20',
                        'site' => 'upperR',
                        'vial_id' => '2'
                    ]
                ]
            ]);

        // delete the injection

        $arr = $response->json();
        $insertedId = $arr['data']['injection']['injection_id'];

        $url = $this->makeUrl('/v1/patient/{patient_id}/injection/{id}', $insertedId);

        $response = $this->deleteJsonTest($url, []);

        $response->assertStatus(200);
    }

    /**
     * Create four injections with the last injection two days late which forces the dose to jump ahead 3 doses per the dosing plan
     */
    public function test_injection_create_good_with_jump_forward_per_dosing_plan()
    {
        $this -> wipeClean();
        // Set compound to active state
        $Row = Compound::find(5);
        $Row->active = 'T';
        $Row->save();
        
        $url = $this->makeUrl('/v1/patient/{patient_id}/injection');

        $backDate18 = date('Y-m-d H:i:s', mktime(0, 0, 0, date("m"), date("d")-18, date("Y")));
        $backDate15 = date('Y-m-d H:i:s', mktime(0, 0, 0, date("m"), date("d")-15, date("Y")));
        $backDate12 = date('Y-m-d H:i:s', mktime(0, 0, 0, date("m"), date("d")-12, date("Y")));
        $todaysDate = date('Y-m-d H:i:s', mktime(0, 0, 0, date("m"), date("d"), date("Y")));

        // Initalize the first injection in the treatment plan. It must be backdated or you get a warning about wrong dilution
        // backdating 18 days is 8 days late per tp. The dose rules for 8 days late is delta=+1, move forward one dose
        $data = [
            'dose' => '0.50',
            'site' => 'upperR',
            'notes_user' => 'insert_first_dose',
            'vial_id' => '4',
            'datetime_administered' => $backDate18
        ];

        $response = $this->postJsonTest($url, $data);

        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'data' => [
                    'injection' => [
                        'dose' => '0.50',
                        'site' => 'upperR',
                        'vial_id' => '4'
                    ]
                ]
            ]);


        $data = [
            'dose' => '0.05',
            'site' => 'upperR',
            'notes_user' => 'insert_second_dose',
            'vial_id' => '5',
            'datetime_administered' => $todaysDate
        ];

        $response = $this->postJsonTest($url, $data);

        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'data' => [
                    'injection' => [
                        'dose' => '0.05',
                        'site' => 'upperR',
                        'vial_id' => '5'
                    ]
                ]
            ]);
        // backdating 15 days is 5 days late per tp. The dose rules for 5 days late is delta=+1, move forward one dose
        $newInj = Injection::orderBy('injection_id', 'desc')->first();
        $newInj->date = $backDate15;
        $newInj->save();

        $data = [
            'dose' => '0.07',
            'site' => 'upperR',
            'notes_user' => 'insert_third_dose',
            'vial_id' => '5',
            'datetime_administered' => $todaysDate
        ];

        $response = $this->postJsonTest($url, $data);

        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'data' => [
                    'injection' => [
                        'dose' => '0.07',
                        'site' => 'upperR',
                        'vial_id' => '5'
                    ]
                ]
            ]);
        // backdating 12 days is 2 days late per tp. The dose rules for 2 days late is delta=+3, move forward three doses
        $newInj = Injection::orderBy('injection_id', 'desc')->first();
        $newInj->date = $backDate12;
        $newInj->save();

        $data = [
            'dose' => '0.2',
            'site' => 'upperR',
            'notes_user' => 'insert_fourth_dose_late_two_days',
            'vial_id' => '5',
            'datetime_administered' => $todaysDate
        ];
    
        $response = $this->postJsonTest($url, $data);

        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'data' => [
                    'injection' => [
                        'dose' => '0.2',
                        'site' => 'upperR',
                        'vial_id' => '5'
                    ]
                ]
            ]);

        //restore the compound to its inactive state
        $Row = Compound::find(5);
        $Row->active='F';
        $Row->save();

    }

    /**
     * Create four injections with the last injection late forcing the dose to jump backward 1 dose per the dosing plan
     */
    public function test_injection_create_good_with_one_jump_backward_per_dosing_plan()
    {
        $this -> wipeClean();

        $Row = Compound::find(5);
        $Row->active='T';
        $Row->save();
        
        $url = $this->makeUrl('/v1/patient/{patient_id}/injection');

        $backDate20 = date('Y-m-d H:i:s', mktime(0, 0, 0, date("m"), date("d")-20, date("Y")));
        $backDate18 = date('Y-m-d H:i:s', mktime(0, 0, 0, date("m"), date("d")-18, date("Y")));
        $backDate16 = date('Y-m-d H:i:s', mktime(0, 0, 0, date("m"), date("d")-16, date("Y")));
        $todaysDate = date('Y-m-d H:i:s', mktime(0, 0, 0, date("m"), date("d"), date("Y")));

        // Initalize the first injection in the treatment plan. It must be backdated or you get a warning about wrong dilution
        // backdating 20 days is 10 days late per tp. The dose rules for 10 days late is delta=0, stay at same dose
        $data = [
            'dose' => '0.15',
            'site' => 'upperR',
            'notes_user' => 'insert_first_dose',
            'vial_id' => '5',
            'datetime_administered' => $backDate20
        ];

        $response = $this->postJsonTest($url, $data);

        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'data' => [
                    'injection' => [
                        'dose' => '0.15',
                        'site' => 'upperR',
                        'vial_id' => '5'
                    ]
                ]
            ]);

        $data = [
            'dose' => '0.15',
            'site' => 'upperR',
            'notes_user' => 'insert_second_dose',
            'vial_id' => '5',
            'datetime_administered' => $todaysDate
        ];

        $response = $this->postJsonTest($url, $data);

        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'data' => [
                    'injection' => [
                        'dose' => '0.15',
                        'site' => 'upperR',
                        'vial_id' => '5'
                    ]
                ]
            ]);

        
        // backdating 18 days is 8 days late per tp. The dose rules for 8 days late is delta=+1, move forward one dose
        $newInj = Injection::orderBy('injection_id', 'desc')->first();
        $newInj->date = $backDate18;
        $newInj->save();

        $data = [
            'dose' => '0.2',
            'site' => 'upperR',
            'notes_user' => 'insert_third_dose',
            'vial_id' => '5',
            'datetime_administered' => $todaysDate
        ];

        $response = $this->postJsonTest($url, $data);

        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'data' => [
                    'injection' => [
                        'dose' => '0.2',
                        'site' => 'upperR',
                        'vial_id' => '5'
                    ]
                ]
            ]);

        // backdating 16 days is 6 days late per tp. The dose rules for 6 days late is normally delta=+1, move forward one dose
        // change dose rules to delta = -1 for this test
        $newDoseRule = DosingPlanDetails::find(7);
        $newDoseRule->delta=-1;
        $newDoseRule->save();
        $newInj = Injection::orderBy('injection_id', 'desc')->first();
        $newInj->date = $backDate16;
        $newInj->save();

        $data = [
            'dose' => '0.15',
            'site' => 'upperR',
            'notes_user' => 'insert_fourth_dose_late_six_days',
            'vial_id' => '5',
            'datetime_administered' => $todaysDate
        ];
    
        $response = $this->postJsonTest($url, $data);

        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'data' => [
                    'injection' => [
                        'dose' => '0.15',
                        'site' => 'upperR',
                        'vial_id' => '5'
                    ]
                ]
            ]);

        //restore the doseruleplans and compound to its original state
        $newDoseRule = DosingPlanDetails::find(7);
        $newDoseRule->delta=1;
        $newDoseRule->save();
        $Row = Compound::find(5);
        $Row->active='F';
        $Row->save();

    }

    /**
     * Add a new row at the end of the treatment plan with the same dose as the one prior
     * Create four injections with the last injection late 
     */
    public function dont_test_injection_create_good_with_jump_backward_and_same_two_doses_at_end()
    {
        $this -> wipeClean();
 
        // make the compound active
        $Row = Compound::find(5);
        $Row->active = 'T';
        $Row->save();

        $Step = new TreatPlanDetails;
        $Step->dose = 0.3;
        $Step->minInterval = 0;
        $Step->maxInterval = 3;
        $Step->minIntervalUnit = 0;
        $Step->maxIntervalUnit = 0;
        $Step->color = 13684944;
        $Step->{'5or10'} = 10;
        $Step->dilution = 1;
        $Step->treatment_plan_id = 1;
        $Step->step=31;
        $Step->save();
        
        $newDoseRule = DosingPlanDetails::find(4);
        $newDoseRule->delta=-2;
        $newDoseRule->save();

        $url = $this->makeUrl('/v1/patient/{patient_id}/injection');

        // $backDate22 = date('Y-m-d H:i:s', mktime(0, 0, 0, date("m"), date("d")-22, date("Y")));
        $backDate19 = date('Y-m-d H:i:s', mktime(0, 0, 0, date("m"), date("d")-19, date("Y")));
        $backDate16 = date('Y-m-d H:i:s', mktime(0, 0, 0, date("m"), date("d")-16, date("Y")));
        $backDate6 = date('Y-m-d H:i:s', mktime(0, 0, 0, date("m"), date("d")-6, date("Y")));
        $todaysDate = date('Y-m-d H:i:s', mktime(0, 0, 0, date("m"), date("d"), date("Y")));

        $data = [
            'dose' => '0.25',
            'site' => 'upperR',
            'notes_user' => 'insert_first_dose',
            'vial_id' => '5',
            'datetime_administered' => $backDate19
        ];

        $response = $this->postJsonTest($url, $data);

        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'data' => [
                    'injection' => [
                        'dose' => '0.25',
                        'site' => 'upperR',
                        'vial_id' => '5'
                    ]
                ]
            ]);
        // backdating 19 days is 9 days late per tp. The dose rules for 9 days late is delta=+1, move forward one dose
        $data = [
            'dose' => '0.3',
            'site' => 'upperR',
            'notes_user' => 'insert_second_dose',
            'vial_id' => '5',
            'datetime_administered' => $todaysDate
        ];

        $response = $this->postJsonTest($url, $data);

        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'data' => [
                    'injection' => [
                        'dose' => '0.3',
                        'site' => 'upperR',
                        'vial_id' => '5'
                    ]
                ]
            ]);

        $newInj = Injection::orderBy('injection_id', 'desc')->first();
        $newInj->date = $backDate16;
        $newInj->save();
        // backdating 16 days is 6 days late per tp. The dose rules for 6 days late is delta=+1, move forward one dose
        $data = [
            'dose' => '0.3',
            'site' => 'upperR',
            'notes_user' => 'insert_third_dose_as_duplicate_dose',
            'vial_id' => '5',
            'datetime_administered' => $todaysDate
        ];
    
        $response = $this->postJsonTest($url, $data);

        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'data' => [
                    'injection' => [
                        'dose' => '0.3',
                        'site' => 'upperR',
                        'vial_id' => '5'
                    ]
                ]
            ]);
        $arr = $response->json();
        $insertedId = $arr['data']['injection']['injection_id'];

        $newInj = Injection::orderBy('injection_id', 'desc')->first();
        $newInj->date = $backDate6;
        $newInj->save();
        // backdating 6 days is 3 days late per tp and the new step added above. The dose rules for 3 days late is delta=-2, move back two doses
        $data = [
            'dose' => '0.3',
            'site' => 'upperR',
            'notes_user' => 'insert_late_dose',
            'vial_id' => '5',
            'datetime_administered' => $todaysDate
        ];
    
        $response = $this->postJsonTest($url, $data);

        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'data' => [
                    'injection' => [
                        'dose' => '0.3',
                        'site' => 'upperR',
                        'vial_id' => '5'
                    ]
                ]
            ]);
        $arr = $response->json();
        $insertedId2 = $arr['data']['injection']['injection_id'];

        //delete the last injection
        // $arr = $response->json();
        // $insertedId = $arr['data']['injection']['injection_id'];

        // $url = $this->makeUrl('/v1/patient/{patient_id}/injection/{id}', $insertedId);

        // $response = $this->deleteJsonTest($url, []);

        // $response->assertStatus(200);

        // $url = $this->makeUrl('/v1/patient/{patient_id}/injection/{id}', $insertedId2);

        // $response = $this->deleteJsonTest($url, []);

        // $response->assertStatus(200);

        //restore the compound to its inactive state and delete the new treatment plan row
        // $sql = 'DELETE FROM treatplandetails WHERE step=31';

        // if ($conn->query($sql) === TRUE) {
        //     echo "Record deleted successfully";
        // } else {
        //     echo "Error deleting record: ".$conn->error;
        // }

        $Row = Compound::find(5);
        $Row->active = 'F';
        $Row->save();

    }

    /**
     * Create an injection with a new active vial. Suggested dose should reflect maintenance steps of treatment plan
     */
    public function dont_test_injection_create_good_injection_with_new_active_vial()
    {
        $this -> wipeClean();

        $url=$this->makeUrl('/v1/patient/{patient_id}/injection');

        $backDate18=date('Y-m-d H:i:s', mktime(0, 0, 0, date("m"), date("d")-18, date("Y")));
        $backDate15=date('Y-m-d H:i:s', mktime(0, 0, 0, date("m"), date("d")-15, date("Y")));
        $backDate10=date('Y-m-d H:i:s', mktime(0, 0, 0, date("m"), date("d")-10, date("Y")));
        $backDate6=date('Y-m-d H:i:s', mktime(0, 0, 0, date("m"), date("d")-6, date("Y")));
        $todaysDate=date('Y-m-d H:i:s', mktime(0, 0, 0, date("m"), date("d"), date("Y")));

        $data = [
            'dose' => '0.05',
            'site' => 'upperR',
            'notes_user' => 'insert_first_dose',
            'vial_id' => '1',
            'datetime_administered' => $todaysDate
        ];

        $response = $this->postJsonTest($url, $data);

        // $response
        //     ->assertStatus(200)
        //     ->assertJson([
        //         'status' => 'success',
        //         'data' => [
        //             'injection' => [
        //                 'dose' => '0.05',
        //                 'site' => 'upperR',
        //                 'vial_id' => '1',
        //                 'datetime_administered' => $todaysDate
        //             ]
        //         ]
        //     ]);

        $newInj = Injection::orderBy('injection_id', 'desc')->first();
        $newInj->date = $backDate18;
        $newInj->save();

        $data = [
            'dose' => '0.1',
            'site' => 'upperR',
            'notes_user' => 'insert_second_dose',
            'vial_id' => '1',
            'datetime_administered' => $todaysDate
        ];

        $response = $this->postJsonTest($url, $data);

        // $response
        //     ->assertStatus(200)
        //     ->assertJson([
        //         'status' => 'success',
        //         'data' => [
        //             'injection' => [
        //                 'dose' => '0.1',
        //                 'site' => 'upperR',
        //                 'vial_id' => '1',
        //                 'datetime_administered' => $todaysDate
        //             ]
        //         ]
        //     ]);

        $newInj = Injection::orderBy('injection_id', 'desc')->first();
        $newInj->date = $backDate15;
        $newInj->save();

        //  make the compound inactive
        // $Row = Compound::find(1);
        // $Row->active = 'F';
        // $Row->save(); 
        // make this compound active
        // $Row2 = Compound::find(6);
        // $Row2->currVol = 5;
        // $Row2->size = '5 mL';
        // $Row2->active = 'T';
        // $Row2->treatment_set_id = 1;
        // $Row2->compound_receipt_id = 1;
        // $Row2->save();

        $data = [
            'dose' => '0.2',
            'site' => 'upperR',
            'notes_user' => 'insert_third_dose',
            'vial_id' => '1',
            'datetime_administered' => $todaysDate
        ];

        $response = $this->postJsonTest($url, $data);

        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'data' => [
                    'injection' => [
                        'dose' => '0.2',
                        'site' => 'upperR',
                        'vial_id' => '1',
                        'datetime_administered' => $todaysDate
                    ]
                ]
            ]);
 
        $newInj = Injection::orderBy('injection_id', 'desc')->first();
        $newInj->date = $backDate10;
        $newInj->save();
    
            
        $data = [
            'dose' => '0.35',
            'site' => 'upperR',
            'notes_user' => 'insert_fourth_dose',
            'vial_id' => '1',
            'datetime_administered' => $todaysDate
        ];

        $response = $this->postJsonTest($url, $data);
        
        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'data' => [
                    'injection' => [
                        'dose' => '0.35',
                        'site' => 'upperR',
                        'vial_id' => '1',
                        'datetime_administered' => $todaysDate
                    ]
                ]
            ]);

        $newInj = Injection::orderBy('injection_id', 'desc')->first();
        $newInj->date = $backDate6;
        $newInj->save();

        //  make the compound inactive
        $Row = Compound::find(1);
        $Row->active = 'F';
        $Row->save(); 
        // make this compound active
        $Row2 = Compound::find(6);
        $Row2->currVol = 5;
        $Row2->size = '5 mL';
        $Row2->active = 'T';
        $Row2->treatment_set_id = 1;
        $Row2->compound_receipt_id = 1;
        $Row2->save();

        $data = [
            'dose' => '0.1',
            'site' => 'upperR',
            'notes_user' => 'insert_fifth_dose',
            'vial_id' => '6',
            'datetime_administered' => $todaysDate
        ];

        $response = $this->postJsonTest($url, $data);
        
        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'data' => [
                    'injection' => [
                        'dose' => '0.1',
                        'site' => 'upperR',
                        'vial_id' => '6',
                        'datetime_administered' => $todaysDate
                    ]
                ]
            ]);
    }

    /**
     * Give an injection that triggers the ASK condition
     * this test should only pass if there is an override warning
     */

     public function dont_test_injection_create_good_with_ASK_condition()
     {
        $this -> wipeClean();

        $url = $this->makeUrl('/v1/patient/{patient_id}/injection');

        $backDate32 = date('Y-m-d H:i:s', mktime(0, 0, 0, date("m"), date("d")-32, date("Y")));
        $todaysDate = date('Y-m-d H:i:s', mktime(0, 0, 0, date("m"), date("d"), date("Y")));

        $data = [
            'dose' => '0.05',
            'site' => 'upperR',
            'notes_user' => 'insert_first_dose',
            'vial_id' => '1',
            'datetime_administered' => $todaysDate
        ];

        $response = $this->postJsonTest($url, $data);

        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'data' => [
                    'injection' => [
                        'dose' => '0.05',
                        'site' => 'upperR',
                        'vial_id' => '1',
                        'datetime_administered' => $todaysDate
                    ]
                ]
            ]);

        // back date the injection 32 days so that it is 22 days late and triggers the ASK condition
        $newInj = Injection::orderBy('injection_id', 'desc')->first();
        $newInj->date = $backDate32;
        $newInj->save();

        $data = [
            'dose' => '0.1',
            'site' => 'upperR',
            'notes_user' => 'insert_second_dose',
            'vial_id' => '1',
            'datetime_administered' => $todaysDate
        ];

        $response = $this->postJsonTest($url, $data);

        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'data' => [
                    'injection' => [
                        'dose' => '0.1',
                        'site' => 'upperR',
                        'vial_id' => '1',
                        'datetime_administered' => $todaysDate
                    ]
                ]
            ]);
    }

    /**
     * Give an injection after changing the treatment plan
     * determine if it is defaulting to the correct dose and dilution
     * currently passing at dose .15 when it should pass at .09
     */
    public function dont_test_injection_create_good_if_tp_changes()
    {
        $this->wipeClean();
        $url = $this->makeUrl('/v1/patient/{patient_id}/injection');

        $todaysDate = date('Y-m-d H:i:s', mktime(0, 0, 0, date("m"), date("d"), date("Y")));
        $backDate3 = date('Y-m-d H:i:s', mktime(0, 0, 0, date("m"), date("d")-3, date("Y")));

        $newTp =  new TreatmentPlan;
        $newTp->name='.1 maint';
        $newTp->type='maint';
        $newTp->save();

        

        $Step0 = new TreatPlanDetails;
        $Step0->dose = 0.03;
        $Step0->minInterval = 2;
        $Step0->maxInterval = 10;
        $Step0->{'5or10'} = 10;
        $Step0->dilution = 200;
        $Step0->treatment_plan_id = $newTp['treatment_plan_id'];
        $Step0->step=0;
        $Step0->save();

        $Step1 = new TreatPlanDetails;
        $Step1->dose = 0.06;
        $Step1->minInterval = 2;
        $Step1->maxInterval = 10;
        $Step1->{'5or10'} = 10;
        $Step1->dilution = 200;
        $Step1->treatment_plan_id = $newTp['treatment_plan_id'];
        $Step1->step=1;
        $Step1->save();

        $Step2 = new TreatPlanDetails;
        $Step2->dose = 0.09;
        $Step2->minInterval = 2;
        $Step2->maxInterval = 10;
        $Step2->{'5or10'} = 10;
        $Step2->dilution = 200;
        $Step2->treatment_plan_id = $newTp['treatment_plan_id'];
        $Step2->step=2;
        $Step2->save();

        $Step3 = new TreatPlanDetails;
        $Step3->dose = 0.12;
        $Step3->minInterval = 2;
        $Step3->maxInterval = 10;
        $Step3->{'5or10'} = 10;
        $Step3->dilution = 200;
        $Step3->treatment_plan_id = $newTp['treatment_plan_id'];
        $Step3->step=3;
        $Step3->save();

        $Step4 = new TreatPlanDetails;
        $Step4->dose = 0.15;
        $Step4->minInterval = 2;
        $Step4->maxInterval = 10;
        $Step4->{'5or10'} = 10;
        $Step4->dilution = 200;
        $Step4->treatment_plan_id = $newTp['treatment_plan_id'];
        $Step4->step=4;
        $Step4->save();

        //give the first injection with the original treatment plan
        $data = [
            'dose' => '0.05',
            'site' => 'upperR',
            'notes_user' => 'insert_first_dose',
            'vial_id' => '1',
            'datetime_administered' => $todaysDate
        ];

        $response = $this->postJsonTest($url, $data);

        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'data' => [
                    'injection' => [
                        'dose' => '0.05',
                        'site' => 'upperR',
                        'vial_id' => '1',
                        'datetime_administered' => $todaysDate
                    ]
                ]
            ]);

        // back date the injection 3 days so that it does not trigger any dosing rules for lateness
        $newInj = Injection::orderBy('injection_id', 'desc')->first();
        $newInj->date = $backDate3;
        $newInj->save();

        //change the treatment plan
        $Pres = Prescription::find(3);
        $Pres->treatment_plan_id = $newTp['treatment_plan_id'];
        $Pres->save();

        $data = [
            'dose' => '0.15',
            'site' => 'upperR',
            'notes_user' => 'insert_second_dose',
            'vial_id' => '1',
            'datetime_administered' => $todaysDate
        ];

        $response = $this->postJsonTest($url, $data);

        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'data' => [
                    'injection' => [
                        'dose' => '0.15',
                        'site' => 'upperR',
                        'vial_id' => '1',
                        'datetime_administered' => $todaysDate
                    ]
                ]
            ]);
    }
}
