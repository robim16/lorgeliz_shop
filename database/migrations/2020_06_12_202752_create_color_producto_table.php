<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateColorProductoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('color_producto', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('visitas')->default(0);
            $table->enum('activo', ['Si','No']);
            $table->string('slug')->nullable();
            $table->unsignedInteger('color_id');
            $table->foreign('color_id')->references('id')->on('colores');
            $table->unsignedInteger('producto_id');
            $table->foreign('producto_id')->references('id')->on('productos')->onDelete('cascade');
            //$table->timestamps();

            $table->index('slug');
            // $table->index('activo');
            $table->index(['id','producto_id','activo']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('color_producto');
    }
}
