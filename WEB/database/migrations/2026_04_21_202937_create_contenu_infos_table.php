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
        Schema::create('contenu_infos', function (Blueprint $table) {
            $table->id(); // id_contenu
            $table->string('titre');
            $table->text('texte_contenu');
            $table->timestamp('date_publication')->useCurrent();
            $table->foreignId('id_auteur')->constrained('users'); // Asso_2
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contenu_infos');
    }
};
