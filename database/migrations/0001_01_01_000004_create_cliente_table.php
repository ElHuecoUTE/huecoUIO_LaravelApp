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
        Schema::create('cliente', function (Blueprint $table) {
            $table->id('cli_id');
            $table->string('cli_nom', 30);
            $table->string('cli_ape', 30);
            $table->string('cli_tel', 10);
            $table->string('cli_ema', 50);
            $table->string('cli_dir', 100);
            $table->string('cli_sex', 1);
            $table->boolean('cli_est')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cliente');
    }
};
