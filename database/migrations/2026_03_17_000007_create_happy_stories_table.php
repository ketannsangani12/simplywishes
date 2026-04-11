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
        Schema::create('happy_stories', function (Blueprint $table) {
            $table->increments('hs_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('wish_id')->nullable();
            $table->longText('story_text')->nullable();
            $table->text('story_image')->nullable();
            $table->tinyInteger('status')->default(0);
            $table->timestamp('created_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('happy_stories');
    }
};
