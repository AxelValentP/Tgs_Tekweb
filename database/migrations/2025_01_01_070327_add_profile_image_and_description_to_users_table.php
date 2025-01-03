<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddProfileImageAndDescriptionToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // Add a profile_image column with a default link
            $table->string('profile_image', 2083) // 2083 is the maximum length for a URL
                ->default('https://scontent.fsub6-1.fna.fbcdn.net/v/t39.30808-6/472236335_1848184122254134_9052240727040896307_n.jpg?stp=dst-jpg_s1080x2048_tt6&_nc_cat=110&ccb=1-7&_nc_sid=127cfc&_nc_eui2=AeHeisKoC88v1ft4-R1rnv8K4RvK4_TJcr_hG8rj9Mlyv5I0mGD0Gbvs7OvsSwNGxuOjd8hgPRCjdOAYKSlpdDs5&_nc_ohc=gUoV4lqR8SQQ7kNvgE9orcX&_nc_zt=23&_nc_ht=scontent.fsub6-1.fna&_nc_gid=ArMPkvD5JxsiAy54nS-4caK&oh=00_AYCPiQIfLeGPTb7E9B4v5xseFNAsTdP2O4wbVE9sKZWolA&oe=677AD9A2')
                ->after('email');

            // Add a description column with a default value
            $table->text('description')->default('None')->after('profile_image');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            // Drop the columns
            $table->dropColumn(['profile_image', 'description']);
        });
    }
}
