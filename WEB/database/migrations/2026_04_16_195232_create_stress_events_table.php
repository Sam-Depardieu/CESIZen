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
        Schema::create('stress_events', function (Blueprint $table) {
            $table->id();
            $table->string('event_name'); // Nom de l'événement (ex: Mariage, Licenciement)
            $table->integer('points');    // Valeur selon l'échelle de Holmes et Rahe
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stress_events');
    }
};
