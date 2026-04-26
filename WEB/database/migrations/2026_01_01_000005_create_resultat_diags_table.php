<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('resultat_diags', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_user')->constrained('users')->onDelete('cascade');
            $table->timestamp('date_passage')->useCurrent();
            $table->integer('score_total');
            $table->string('niveau_stress');
            $table->text('event_ids')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('resultat_diags'); }
};
