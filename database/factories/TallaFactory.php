<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use App\Talla;
use Faker\Generator as Faker;

$factory->define(Talla::class, function (Faker $faker) {
    return [
        'nombre' =>$faker->word,
        'descripcion' =>$faker->sentence
    ];
});
