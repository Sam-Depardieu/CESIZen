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
        Schema::create('emotions_ressenties', function (Blueprint $table) {
            $table->foreignId('id_entree')->constrained('journal_entrees')->onDelete('cascade');
            $table->foreignId('id_emotion')->constrained('emotions');
            $table->integer('intensite');
            $table->primary(['id_entree', 'id_emotion']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('emotions_ressenties');
    }
};
