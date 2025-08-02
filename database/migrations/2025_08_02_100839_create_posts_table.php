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
        Schema::create('posts', function (Blueprint $table) {
            $table->id();

            // Foreign key to users
            $table->foreignId('user_id')
                ->constrained()
                ->onDelete('cascade')
                ->index();

            $table->string('title')->index();
            $table->text('body');

            // View count, default 0
            $table->unsignedBigInteger('views')->default(0)->index();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
