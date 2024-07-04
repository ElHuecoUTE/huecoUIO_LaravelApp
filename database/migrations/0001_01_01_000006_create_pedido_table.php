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
        Schema::create('estado_pedido', function (Blueprint $table) {
            $table->smallInteger('est_ped_id')->primary();
            $table->string('est_ped_nom', 15);
        });

        Schema::create('pedido', function (Blueprint $table) {
            $table->id('ped_id');
            $table->foreignId('fk_cli_id')->references('cli_id')->on('cliente')->onDelete('cascade')->onUpdate('cascade');
            $table->date('ped_fec');
            $table->decimal('ped_tot', 10, 2);
            $table->timestamps();
            $table->smallInteger('fk_est_ped_id')->default(1);
            $table->foreign('fk_est_ped_id')->references('est_ped_id')->on('estado_pedido')->onDelete('restrict')->onUpdate('cascade');
        });

        Schema::create('detalle_pedido', function (Blueprint $table) {
            $table->id('det_ped_id');
            $table->foreignId('fk_ped_id')->references('ped_id')->on('pedido')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('fk_pro_id')->references('pro_id')->on('producto')->onDelete('cascade')->onUpdate('cascade');
            $table->smallInteger('det_ped_can');
            $table->decimal('det_ped_pre', 8, 2);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('estado_pedido');
        Schema::dropIfExists('detalle_pedido');
        Schema::dropIfExists('pedido');
    }
};
