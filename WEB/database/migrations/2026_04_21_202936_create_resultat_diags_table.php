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
        Schema::create('resultat_diags', function (Blueprint $table) {
            $table->id(); // id_resultat
            $table->foreignId('id_user')->constrained('users'); // Asso_3
            $table->timestamp('date_passage')->useCurrent();
            $table->integer('score_total');
            $table->string('niveau_stress');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('resultat_diags');
    }
};
