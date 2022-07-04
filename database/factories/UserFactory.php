<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use App\User;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

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

$factory->define(User::class, function (Faker $faker) {
    $nombres = 'Lorenzo Antonio';
    $apellidos = 'Geliz Cotera';
    $usuario = 'lorgeliz';
    $direccion = 'Montería';
    $telefono = '3138645929';
    $departamento = 'Córdoba';
    $municipio = 'Montería';
    $email = 'lorenzogelizcotera@gmail.com';
    return [
        'role_id' => \App\Role::ADMIN,
        'identificacion' => '1063456009',
        'nombres' => $nombres,
        'apellidos' => $apellidos,
        'direccion' => $direccion,
        'telefono' => $telefono,
        'departamento' => $departamento,
        'municipio' => $municipio,
        'email' => $email,
        'email_verified_at' => now(),
        'username' => $usuario,
        'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
        'slug' => Str::slug( $nombres . " " . $apellidos, '-'),
        'remember_token' => Str::random(10),
    ];
});