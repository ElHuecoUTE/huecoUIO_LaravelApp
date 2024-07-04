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
        Schema::create('ventas', function (Blueprint $table) {
            $table->id('ven_id');
            $table->foreignId('fk_cli_id')->references('cli_id')->on('cliente')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('fk_ped_id')->references('ped_id')->on('pedido')->onDelete('cascade')->onUpdate('cascade');
            $table->decimal('ven_tot', 12, 2);
            $table->timestamps();
        });

        Schema::create('iva', function (Blueprint $table) {
            $table->tinyInteger('iva_por')->primary()->autoIncrement();
            $table->decimal('iva_val', 10, 2)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ventas');
        Schema::dropIfExists('iva');
    }
};
