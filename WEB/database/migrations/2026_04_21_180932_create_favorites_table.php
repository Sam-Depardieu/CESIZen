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
        Schema::create('favorites', function (Blueprint $table) {
            $table->foreignId('id_user')->constrained('users')->onDelete('cascade');
        
            // id_activite fait référence à la table activites
            // C'est ici que l'erreur se produit si le nom ne correspond pas exactement
            $table->foreignId('id_activite')->constrained('activites')->onDelete('cascade');
            
            $table->timestamp('date_ajout')->useCurrent();
            
            // Clé primaire composée pour éviter les doublons (RGPD/Qualité)
            $table->primary(['id_user', 'id_activite']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('favorites');
    }
};
