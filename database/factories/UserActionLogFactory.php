<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Book;
use App\Models\User;
use App\Models\UserActionLog;
use Faker\Generator as Faker;

$factory->define(UserActionLog::class, function (Faker $faker) {
    return [
        'user_id' => User::pluck('id')[$faker->numberBetween(1, User::count()-1)],
        'book_id' => Book::pluck('id')[$faker->numberBetween(1, Book::count()-1)],
        'action' => $faker->randomElement(['CHECKIN', 'CHECKOUT'])
    ];
});
