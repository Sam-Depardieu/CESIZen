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
        Schema::create('exercice_respi', function (Blueprint $table) {
            $table->id(); // id_respi
            $table->string('nom');
            $table->integer('duree_inspi'); // Cohérence cardiaque [cite: 133]
            $table->integer('duree_apnee'); // [cite: 134]
            $table->integer('duree_expi'); // [cite: 135]
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exercice_respi');
    }
};
