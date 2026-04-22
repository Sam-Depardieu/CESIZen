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
        Schema::create('detail_resultats', function (Blueprint $table) {
            $table->foreignId('id_resultat')->constrained('resultat_diags')->onDelete('cascade');
            $table->foreignId('id_event')->constrained('evenement_stress');
            $table->primary(['id_resultat', 'id_event']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_resultats');
    }
};
