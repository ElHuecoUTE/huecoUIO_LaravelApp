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
        Schema::create('estado_inventario', function (Blueprint $table) {
            $table->smallInteger('est_inv_id')->primary();
            $table->string('est_inv_des', 15);
        });

        Schema::create('tipo_registro_inventario', function (Blueprint $table) {
            $table->smallInteger('tip_reg_inv_id')->primary();
            $table->string('tip_reg_inv_des', 15);
        });

        Schema::create('inventario', function (Blueprint $table) {
            $table->id('inv_id');
            $table->foreignId('fk_pro_id')->references('pro_id')->on('producto')->onDelete('cascade')->onUpdate('cascade');
            $table->integer('inv_stock');
            $table->smallInteger('fk_est_inv_id')->default(1);
            $table->foreign('fk_est_inv_id')->references('est_inv_id')->on('estado_inventario')->onDelete('restrict')->onUpdate('cascade');
        });

        Schema::create('registro_inventario', function (Blueprint $table) {
            $table->id('reg_inv_id');
            $table->foreignId('fk_inv_id')->references('inv_id')->on('inventario')->onDelete('cascade')->onUpdate('cascade');
            $table->date('reg_inv_fec');
            $table->integer('reg_inv_can');
            $table->smallInteger('fk_reg_inv_tip');
            $table->bigInteger('fk_usu_id')->nullable();
            $table->foreign('fk_reg_inv_tip')->references('tip_reg_inv_id')->on('tipo_registro_inventario')->onDelete('restrict')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('registro_inventario');
        Schema::dropIfExists('inventario');
        Schema::dropIfExists('estado_inventario');
        Schema::dropIfExists('tipo_registro_inventario');
    }
};
