<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Tambahkan kolom 'seen' ke tabel 'likes'
        Schema::table('likes', function (Blueprint $table) {
            if (!Schema::hasColumn('likes', 'seen')) {
                $table->boolean('seen')->default(false); // Default false
            }
        });

        // Buat trigger untuk mengatur nilai default 'seen'
        DB::unprepared('
            CREATE TRIGGER set_like_seen_default BEFORE INSERT ON likes
            FOR EACH ROW
            BEGIN
                DECLARE is_post_owner BOOLEAN;
                SELECT (posts.user_id = NEW.user_id) INTO is_post_owner 
                FROM posts WHERE posts.id = NEW.post_id;
                SET NEW.seen = is_post_owner;
            END
        ');

        // Perbarui baris yang sudah ada
        DB::statement('
            UPDATE likes 
            INNER JOIN posts ON likes.post_id = posts.id
            SET likes.seen = (likes.user_id = posts.user_id)
        ');
    }

    public function down()
    {
        // Hapus trigger
        DB::unprepared('DROP TRIGGER IF EXISTS set_like_seen_default');

        // Hapus kolom 'seen'
        Schema::table('likes', function (Blueprint $table) {
            if (Schema::hasColumn('likes', 'seen')) {
                $table->dropColumn('seen');
            }
        });
    }
};
