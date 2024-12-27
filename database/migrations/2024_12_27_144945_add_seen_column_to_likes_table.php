<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
{
    Schema::table('likes', function (Blueprint $table) {
        $table->boolean('seen')->default(false)->after('user_id');
    });

    DB::statement('
        CREATE TRIGGER set_like_seen_default BEFORE INSERT ON likes
        FOR EACH ROW
        BEGIN
            SET NEW.seen = (SELECT posts.user_id = NEW.user_id FROM posts WHERE posts.id = NEW.post_id);
        END
    ');

    // Update existing rows
    DB::table('likes')
        ->join('posts', 'likes.post_id', '=', 'posts.id')
        ->update([
            'seen' => DB::raw('likes.user_id = posts.user_id')
        ]);
}


    public function down()
    {
        // Menghapus trigger dan kolom 'seen'
        DB::statement('DROP TRIGGER IF EXISTS set_like_seen_default');
        Schema::table('likes', function (Blueprint $table) {
            $table->dropColumn('seen');
        });
    }
};
