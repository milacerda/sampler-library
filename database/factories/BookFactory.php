<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Book;
use Faker\Generator as Faker;

$factory->define(Book::class, function (Faker $faker) {
    return [
        'title' => $faker->sentence,
        'isbn' => $faker->unique()->isbn10,
        'publication_date' => $faker->date(),
        'status' => $faker->randomElement(['CHECKED_OUT', 'AVAILABLE'])
    ];
});
