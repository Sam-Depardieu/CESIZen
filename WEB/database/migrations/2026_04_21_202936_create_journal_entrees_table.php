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
        Schema::create('journal_entrees', function (Blueprint $table) {
            $table->id(); // id_entree
            $table->foreignId('id_user')->constrained('users'); // Asso_8 [cite: 98]
            $table->timestamp('date_heure')->useCurrent();
            $table->text('commentaire')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('journal_entrees');
    }
};
