<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Add the seen column to the likes table
        Schema::table('likes', function (Blueprint $table) {
            $table->boolean('seen')->default(false)->after('user_id'); // Default false
        });

        // Add a trigger to set the default value of 'seen'
        DB::statement('
            CREATE TRIGGER set_like_seen_default BEFORE INSERT ON likes
            FOR EACH ROW
            BEGIN
                SET NEW.seen = (SELECT posts.user_id = NEW.user_id FROM posts WHERE posts.id = NEW.post_id);
            END
        ');
    }

    public function down()
    {
        // Remove the trigger
        DB::statement('DROP TRIGGER IF EXISTS set_like_seen_default');

        // Remove the seen column
        Schema::table('likes', function (Blueprint $table) {
            $table->dropColumn('seen');
        });
    }
};
