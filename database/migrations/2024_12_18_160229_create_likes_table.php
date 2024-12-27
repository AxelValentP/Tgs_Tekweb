<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Check if the 'likes' table exists, and create it if not
        if (!Schema::hasTable('likes')) {
            Schema::create('likes', function (Blueprint $table) {
                $table->id(); // Primary key
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->foreignId('post_id')->constrained()->onDelete('cascade');
                $table->boolean('seen')->default(false); // Default false
                $table->timestamps();
            });
        } else {
            // If the table exists, modify it to add the 'seen' column
            Schema::table('likes', function (Blueprint $table) {
                if (!Schema::hasColumn('likes', 'seen')) {
                    $table->boolean('seen')->default(false)->after('user_id');
                }
            });
        }

        // Add a trigger to set the default value of 'seen'
        DB::unprepared('
            CREATE TRIGGER set_like_seen_default BEFORE INSERT ON likes
            FOR EACH ROW
            BEGIN
                DECLARE post_owner_id BIGINT;
                SET post_owner_id = (SELECT user_id FROM posts WHERE id = NEW.post_id);

                IF post_owner_id = NEW.user_id THEN
                    SET NEW.seen = TRUE;
                ELSE
                    SET NEW.seen = FALSE;
                END IF;
            END
        ');
    }

    public function down()
    {
        // Remove the trigger
        DB::unprepared('DROP TRIGGER IF EXISTS set_like_seen_default');

        // Remove the 'seen' column if the table exists
        if (Schema::hasTable('likes')) {
            Schema::table('likes', function (Blueprint $table) {
                if (Schema::hasColumn('likes', 'seen')) {
                    $table->dropColumn('seen');
                }
            });
        }

        // Drop the 'likes' table if necessary
        Schema::dropIfExists('likes');
    }
};
