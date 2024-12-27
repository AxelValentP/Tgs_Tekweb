<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Buat tabel `comments`
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('post_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->unsignedBigInteger('parent_id')->nullable(); // Kolom parent_id tanpa AFTER
            $table->foreign('parent_id')->references('id')->on('comments')->onDelete('cascade');
            $table->text('text');
            $table->boolean('seen')->default(false); // Default false
            $table->boolean('hide')->default(false); // Default false
            $table->timestamps();
        });

        // Trigger untuk mengatur kolom `seen`
        DB::unprepared('
            CREATE TRIGGER set_seen_default BEFORE INSERT ON comments
            FOR EACH ROW
            BEGIN
                DECLARE is_post_owner BOOLEAN;
                SELECT (posts.user_id = NEW.user_id) INTO is_post_owner FROM posts WHERE posts.id = NEW.post_id;
                SET NEW.seen = is_post_owner;
            END
        ');
    }

    public function down()
    {
        // Hapus trigger
        DB::unprepared('DROP TRIGGER IF EXISTS set_seen_default');

        // Hapus tabel `comments`
        Schema::dropIfExists('comments');
    }
};
