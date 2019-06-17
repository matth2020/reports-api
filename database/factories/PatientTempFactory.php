<?php

use Faker\Generator as Faker;
use App\Models\PatientTemp;
use database\factories\XtractUtil;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(PatientTemp::class, function (Faker $faker) {
    $gender = rand(0, 1) === 0 ? 'male' : 'female';
    $first = $faker->firstname($gender);
    $last = $faker->lastname;

    return [
        'firstname' => $first,
        'lastname' => $last,
        'mi' => substr($faker->firstname, 0, 1),
        'dob' => $faker->date($format = 'Y-m-d', $max = 'now'),
        'phone' => $faker->e164PhoneNumber,
        'addr1' => $faker->streetAddress,
        'addr2' => $faker->secondaryAddress,
        'city' => $faker->city,
        'state' => $faker->state,
        'zip' => $faker->postcode,
        'displayName' => substr($first, 0, 1).'. '.substr($last, 0, 1).'.',
        'chart' =>  $faker->sha1,
        'MSHsegment'  => substr($faker->sha256, 0, 1114),
        'patient_notes' => XtractUtil::standard($faker->realText(500)),
        'eContact' => $faker->name,
        'eContactNum' => $faker->e164PhoneNumber,
        'email' => $faker->email,
        'smsphone' => $faker->e164PhoneNumber,
        'home_phone' => $faker->e164PhoneNumber,
        'guar_last' => $last,
        'guar_first' => $faker->firstname,
        'guar_mi' => substr($faker->firstname, 0, 1),
        'guar_suffix' => $faker->suffix,
        'guar_addr1' => $faker->streetAddress,
        'guar_addr2' => $faker->secondaryAddress,
        'guar_city' => $faker->city,
        'guar_state' => $faker->state,
        'guar_zip' => $faker->postcode,
        'prim_carrier' => substr($faker->company, 0, 20),
        'sec_carrier' => substr($faker->company, 0, 20),
        'PIDsegment' => $faker->sha256,
        'PV1segment' => $faker->sha256,
        'MRGsegment' => substr($faker->sha256, 0, 500),
        'hl7message' => $faker->sha256,
        'gender' => $gender === 'male' ? 'M' : 'F',
        'ssn' => str_replace('-', '', $faker->ssn)
    ];
});
