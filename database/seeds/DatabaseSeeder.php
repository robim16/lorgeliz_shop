<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // factory(\App\Role::class, 1)->create(['nombre' => 'cliente', 'descripcion' => 'usuario con rol cliente']);
        // factory(\App\Role::class, 1)->create(['nombre' => 'administrador', 'descripcion' => 'usuario con rol administrador']);

        // factory(\App\Tipo::class, 1)->create(['nombre' => 'camisetas', 'descripcion' => 'camisetas', 'slug' => 'camisetas]);
        // factory(\App\Tipo::class, 1)->create(['nombre' => 'vestidos', 'descripcion' => 'vestidos', 'slug' => 'vestidos]);
        // factory(\App\Tipo::class, 1)->create(['nombre' => 'zapatos', 'descripcion' => 'zapatos', 'slug' => 'zapatos]);
        // factory(\App\Tipo::class, 1)->create(['nombre' => 'blusas', 'descripcion' => 'blusas', 'slug' => 'blusas]);
        // factory(\App\Tipo::class, 1)->create(['nombre' => 'baletas', 'descripcion' => 'baletas', 'slug' => 'baletas]);
        // factory(\App\Tipo::class, 1)->create(['nombre' => 'tacones', 'descripcion' => 'tacones', 'slug' => 'tacones]);


        // factory(\App\Talla::class, 1)->create(['nombre' => '24', 'descripcion' => 'talla 24']);
        // factory(\App\Talla::class, 1)->create(['nombre' => '25', 'descripcion' => 'talla 25']);
        // factory(\App\Talla::class, 1)->create(['nombre' => '26', 'descripcion' => 'talla 26']);
        // factory(\App\Talla::class, 1)->create(['nombre' => '27', 'descripcion' => 'talla 27']);
        // factory(\App\Talla::class, 1)->create(['nombre' => '28', 'descripcion' => 'talla 28']);
        // factory(\App\Talla::class, 1)->create(['nombre' => '29', 'descripcion' => 'talla 29']);
        // factory(\App\Talla::class, 1)->create(['nombre' => '30', 'descripcion' => 'talla 30']);
        // factory(\App\Talla::class, 1)->create(['nombre' => '31', 'descripcion' => 'talla 31']);
        // factory(\App\Talla::class, 1)->create(['nombre' => '32', 'descripcion' => 'talla 32']);
        // factory(\App\Talla::class, 1)->create(['nombre' => '33', 'descripcion' => 'talla 33']);
        // factory(\App\Talla::class, 1)->create(['nombre' => '34', 'descripcion' => 'talla 34']);
        // factory(\App\Talla::class, 1)->create(['nombre' => '35', 'descripcion' => 'talla 35']);
        // factory(\App\Talla::class, 1)->create(['nombre' => '36', 'descripcion' => 'talla 36']);
        // factory(\App\Talla::class, 1)->create(['nombre' => '37', 'descripcion' => 'talla 37']);
        // factory(\App\Talla::class, 1)->create(['nombre' => '38', 'descripcion' => 'talla 38']);
        // factory(\App\Talla::class, 1)->create(['nombre' => '39', 'descripcion' => 'talla 39']);
        // factory(\App\Talla::class, 1)->create(['nombre' => '40', 'descripcion' => 'talla 40']);
        // factory(\App\Talla::class, 1)->create(['nombre' => '41', 'descripcion' => 'talla 41']);
        // factory(\App\Talla::class, 1)->create(['nombre' => '42', 'descripcion' => 'talla 42']);


        // factory(\App\Talla::class, 1)->create(['nombre' => 'S', 'descripcion' => 'talla S']);
        // factory(\App\Talla::class, 1)->create(['nombre' => 'L', 'descripcion' => 'talla L']);
        // factory(\App\Talla::class, 1)->create(['nombre' => 'M', 'descripcion' => 'talla M']);
        // factory(\App\Talla::class, 1)->create(['nombre' => 'X', 'descripcion' => 'talla X']);
        // factory(\App\Talla::class, 1)->create(['nombre' => 'XL', 'descripcion' => 'talla XL']);
        

        // factory(\App\User::class, 1)->create()

        //     ->each(function (App\User $u){
        //         factory(\App\Cliente::class, 1)->create(['user_id' => $u->id]);
        //     });
        

        factory(\App\TallaTipo::class, 1)->create(['talla_id' => '1', 'tipo_id' => '1']);
        factory(\App\TallaTipo::class, 1)->create(['talla_id' => '2', 'tipo_id' => '1']);
        factory(\App\TallaTipo::class, 1)->create(['talla_id' => '3', 'tipo_id' => '1']);
        factory(\App\TallaTipo::class, 1)->create(['talla_id' => '4', 'tipo_id' => '1']);
        factory(\App\TallaTipo::class, 1)->create(['talla_id' => '5', 'tipo_id' => '1']);
        factory(\App\TallaTipo::class, 1)->create(['talla_id' => '6', 'tipo_id' => '1']);
        factory(\App\TallaTipo::class, 1)->create(['talla_id' => '7', 'tipo_id' => '1']);
        factory(\App\TallaTipo::class, 1)->create(['talla_id' => '8', 'tipo_id' => '1']);
        factory(\App\TallaTipo::class, 1)->create(['talla_id' => '9', 'tipo_id' => '1']);
        factory(\App\TallaTipo::class, 1)->create(['talla_id' => '10', 'tipo_id' => '1']);
        factory(\App\TallaTipo::class, 1)->create(['talla_id' => '11', 'tipo_id' => '1']);
        factory(\App\TallaTipo::class, 1)->create(['talla_id' => '12', 'tipo_id' => '1']);
        factory(\App\TallaTipo::class, 1)->create(['talla_id' => '13', 'tipo_id' => '1']);
        factory(\App\TallaTipo::class, 1)->create(['talla_id' => '14', 'tipo_id' => '1']);
        factory(\App\TallaTipo::class, 1)->create(['talla_id' => '15', 'tipo_id' => '1']);
        factory(\App\TallaTipo::class, 1)->create(['talla_id' => '16', 'tipo_id' => '1']);
        factory(\App\TallaTipo::class, 1)->create(['talla_id' => '17', 'tipo_id' => '1']);

    
    }
}
