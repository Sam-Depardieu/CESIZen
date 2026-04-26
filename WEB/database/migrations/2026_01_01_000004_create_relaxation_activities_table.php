<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('relaxation_activities', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->string('type'); // audio, video, article
            $table->string('category');
            $table->string('url')->nullable();
            $table->string('image_url')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('relaxation_activities'); }
};
