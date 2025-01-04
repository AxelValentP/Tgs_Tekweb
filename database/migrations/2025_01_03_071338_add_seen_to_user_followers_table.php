<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSeenToUserFollowersTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('user_followers', function (Blueprint $table) {
            $table->boolean('seen')->default(0)->after('follower_id'); // Add 'seen' column
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_followers', function (Blueprint $table) {
            $table->dropColumn('seen'); // Remove 'seen' column
        });
    }
}
