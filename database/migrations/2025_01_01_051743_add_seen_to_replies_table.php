<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AddSeenToRepliesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {   
        Schema::table('replies', function (Blueprint $table) {
            $table->boolean('seen')->default(false)->after('user_id'); // Adjust 'user_id' to the appropriate column
        });

        // Update existing records where the replier is the comment's author
        DB::table('replies')
            ->join('comments', 'replies.comment_id', '=', 'comments.id')
            ->whereColumn('replies.user_id', 'comments.user_id')
            ->update(['replies.seen' => true]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('replies', function (Blueprint $table) {
            $table->dropColumn('seen');
        });
    }
}
