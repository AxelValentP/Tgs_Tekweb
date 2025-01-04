<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AddSeenToCommentLikesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {   
        Schema::table('comment_likes', function (Blueprint $table) {
            $table->boolean('seen')->default(false)->after('user_id'); // Adjust 'user_id' to the appropriate column
        });

        // Update existing records where the liker is the comment's author
        DB::table('comment_likes')
            ->join('comments', 'comment_likes.comment_id', '=', 'comments.id')
            ->whereColumn('comment_likes.user_id', 'comments.user_id')
            ->update(['comment_likes.seen' => true]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('comment_likes', function (Blueprint $table) {
            $table->dropColumn('seen');
        });
    }
}
