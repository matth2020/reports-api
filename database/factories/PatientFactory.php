<?php

use Faker\Generator as Faker;
use App\Models\Patient;

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

$factory->define(Patient::class, function (Faker $faker) {
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
        'displayname' => substr($first, 0, 1).'. '.substr($last, 0, 1).'.',
        'chart' =>  $faker->sha1,
        'eContact' => $faker->name,
        'eContactNum' => $faker->e164PhoneNumber,
        'email' => $faker->email,
        'smsphone' => $faker->e164PhoneNumber,
        'PIDsegment' => $faker->sha256,
        'PV1segment' => $faker->sha256,
        'gender' => $gender === 'male' ? 'M' : 'F',
        'ssn' => str_replace('-', '', $faker->ssn),
        'fax' => $faker->e164PhoneNumber,
    ];
});
