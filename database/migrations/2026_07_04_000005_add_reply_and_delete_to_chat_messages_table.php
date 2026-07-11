<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('chat_messages', function (Blueprint $table) {
            $table->unsignedBigInteger('reply_to_id')->nullable()->after('sender_id');
            $table->timestamp('deleted_at')->nullable()->after('read_at');

            $table->index('reply_to_id');
        });
    }

    public function down(): void
    {
        Schema::table('chat_messages', function (Blueprint $table) {
            $table->dropIndex(['reply_to_id']);
            $table->dropColumn(['reply_to_id', 'deleted_at']);
        });
    }
};
