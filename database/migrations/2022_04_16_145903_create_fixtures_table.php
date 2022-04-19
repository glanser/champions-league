<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('fixtures', function (Blueprint $table) {
            $table->id();
            $table->integer('week');
            $table->unsignedBigInteger('first_team_id');
            $table->unsignedBigInteger('second_team_id');
            $table->unsignedBigInteger('first_team_goals')->nullable();
            $table->unsignedBigInteger('second_team_goals')->nullable();
            $table->integer('won_team_id')->nullable();
            $table->string('status');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fixtures');
    }
};
