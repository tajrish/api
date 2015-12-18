<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/


$factory->define(Tajrish\Models\User::class, function (Faker\Generator $faker) {
    return [
        'first_name'  => $faker->name,
        'last_name' => $faker->lastName,
        'username' => $faker->userName,
        'email' => $faker->email,
        'mobile' => $faker->phoneNumber,
        'password' => bcrypt('123456'),
        'birth_date' => $faker->date(),
        'status' => $faker->randomElement([
            'verified',
            'email_not_verified',
            'mobile_not_verified',
            'email_mobile_not_verified'
        ]),
        'avatar' => $faker->imageUrl()
    ];
});

