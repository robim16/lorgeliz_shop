<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use App\TallaTipo;
use Faker\Generator as Faker;

$factory->define(TallaTipo::class, function (Faker $faker) {
    
    return [
        'talla_id' =>$faker->word,
        'tipo_id' =>$faker->sentence
    ];

});
