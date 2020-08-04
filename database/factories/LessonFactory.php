<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Lesson;
use Faker\Generator as Faker;
use Carbon\Carbon;

$factory->define(Lesson::class, function (Faker $faker) {
    $timeFrames = [];
    $start = '';

    do{
        array_push($startTimes, $start);
        $start = rand(9,20) . ":" . str_pad(rand(0,59), 2, "0", STR_PAD_LEFT);
    }while(in_array($start, $startTimes));

    $end = Carbon::parse($start)->addHour(1)->format('H:i');
    $instruments = [
        'violin',
        'vocal',
        'piano',
        'guitar',
        'drums'
    ];

    return [
        //'day' => $faker->numberBetween($min = 2, $max = 7),
        'day' => 2,
        'description' => $instruments[rand(0, count($instruments)-1)],
        'duration' => 60,
        'grade' => numberBetween($min = 1, $max = 8),
        'start_time' => $start,
        'end_time' => $end,
        'student_id' => 1,
        'teacher_id' => 1,
    ];
});
