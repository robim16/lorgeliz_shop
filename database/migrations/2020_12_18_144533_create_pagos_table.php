<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePagosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pagos', function (Blueprint $table) {
            $table->increments('id');
            $table->string('ref_epayco')->nullable();
            $table->dateTime('fecha');
            $table->decimal('monto', 12, 0);
            $table->unsignedInteger('venta_id');
            $table->foreign('venta_id')->references('id')->on('ventas');
            $table->enum('estado',['1','2','3','4','5']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pagos');
    }
}
