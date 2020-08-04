<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Student;
use Faker\Generator as Faker;

$factory->define(Student::class, function (Faker $faker) {
    $name = explode(" ", $faker->name);
    return [
        'first_name' => $name[0],
        'last_name' => $name[1],
        'age' => rand(9, 25)
    ];
});
