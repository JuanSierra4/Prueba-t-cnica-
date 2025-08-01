<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Crear la tabla de facturas
        Schema::create('facturas', function (Blueprint $table) {
            $table->id('num_factura');
            $table->date('fecha');

            $table->foreignId('id_cliente')->references('id_cliente')
            ->on('clientes')->onDelete('cascade');
            $table->foreign('id_producto')->references('id_producto')
            ->on('productos')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('facturas');
    }
};
