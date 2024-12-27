<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('post_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->text('text');
            $table->boolean('seen')->default(false); // Default false
            $table->boolean('hide')->default(false); // Default false
            $table->timestamps();
            $table->unsignedBigInteger('parent_id')->nullable()->after('user_id');
            $table->foreign('parent_id')->references('id')->on('comments')->onDelete('cascade');
        });

        // Add logic to update the 'seen' column based on ownership
        Schema::table('comments', function (Blueprint $table) {
            DB::statement('
                CREATE TRIGGER set_seen_default BEFORE INSERT ON comments
                FOR EACH ROW
                BEGIN
                    SET NEW.seen = (SELECT posts.user_id = NEW.user_id FROM posts WHERE posts.id = NEW.post_id);
                END
            ');
        });

        // Add logic to update the 'seen' column based on ownership
        Schema::table('comments', function (Blueprint $table) {
            DB::statement('
                CREATE TRIGGER set_seen_default BEFORE INSERT ON comments
                FOR EACH ROW
                BEGIN
                    SET NEW.seen = (SELECT posts.user_id = NEW.user_id FROM posts WHERE posts.id = NEW.post_id);
                END
            ');
        });
    }

    public function down()
    {
        Schema::table('comments', function (Blueprint $table) {
            DB::statement('DROP TRIGGER IF EXISTS set_seen_default');
        });

        Schema::dropIfExists('comments');
    }
};
