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
        Schema::create('forum', function (Blueprint $table) {
            $table->increments('e_id');
            $table->string('e_title', 250);
            $table->longText('e_text')->nullable();
            $table->longText('description')->nullable();
            $table->string('e_image', 255)->nullable();
            $table->tinyText('article_image')->nullable();
            $table->text('featured_video_url')->nullable();
            $table->tinyInteger('status')->default(0);
            $table->tinyInteger('is_video_only')->default(0);
            $table->timestamp('created_at')->useCurrent();
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->timestamp('updated_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('forum');
    }
};
