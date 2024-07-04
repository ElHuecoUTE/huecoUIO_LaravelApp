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
        Schema::create('estado_producto', function (Blueprint $table) {
            $table->smallInteger('est_pro_id')->primary()->autoIncrement();
            $table->string('est_pro_nom', 15);
        });

        Schema::create('tipo_producto', function (Blueprint $table) {
            $table->smallInteger('tip_pro_id')->primary()->autoIncrement();
            $table->string('tip_pro_nom', 15);
        });

        Schema::create('producto', function (Blueprint $table) {
            $table->id('pro_id');
            $table->string('pro_nom', 30);
            $table->decimal('pro_val');
            $table->smallInteger('fk_est_pro_id')->default(1);
            $table->smallInteger('fk_tip_pro_id');
            $table->foreign('fk_est_pro_id')->references('est_pro_id')->on('estado_producto')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('fk_tip_pro_id')->references('tip_pro_id')->on('tipo_producto')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('producto');
        Schema::dropIfExists('estado_producto');
        Schema::dropIfExists('tipo_producto');
    }
};
