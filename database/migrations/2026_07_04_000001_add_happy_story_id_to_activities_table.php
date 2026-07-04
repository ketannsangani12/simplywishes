<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('activities', function (Blueprint $table) {
            $table->unsignedBigInteger('happy_story_id')->nullable()->after('donation_id');
            $table->index(['happy_story_id', 'type']);
        });
    }

    public function down(): void
    {
        Schema::table('activities', function (Blueprint $table) {
            $table->dropIndex(['happy_story_id', 'type']);
            $table->dropColumn('happy_story_id');
        });
    }
};
