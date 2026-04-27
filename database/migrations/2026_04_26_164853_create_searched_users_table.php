<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('searched_users', function (Blueprint $table) {
            $table->id();
            $table->string('spotify_username')->unique();
            $table->string('display_name')->nullable();
            $table->text('avatar_url')->nullable();
            $table->integer('total_public_playlists')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('searched_users');
    }
};
