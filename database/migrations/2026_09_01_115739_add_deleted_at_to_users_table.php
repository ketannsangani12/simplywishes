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
        Schema::table('users', function (Blueprint $table) {
            // Marks a self-deleted account. Deliberately not Laravel's
            // SoftDeletes trait: that applies a global query scope which
            // would hide the row from every belongsTo relation across the
            // app (Wish/Donation/ForumPost/etc. all reference users by a
            // plain integer column, no FK), breaking historical display of
            // "who created/granted this" for other users' content. Instead
            // the row stays queryable as normal; only login checks this
            // column explicitly (see AuthController::login()).
            $table->timestamp('deleted_at')->nullable()->after('email_verified_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('deleted_at');
        });
    }
};
