<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('likes', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->foreignId('post_id')->constrained()->onDelete('cascade'); // Relasi ke tabel posts
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Relasi ke tabel users
            $table->boolean('seen')->default(false); // Apakah sudah dilihat
            $table->boolean('hide')->default(false); // Apakah disembunyikan
            $table->timestamps(); // Kolom created_at dan updated_at
        });
    }

    public function down()
    {
        Schema::dropIfExists('likes'); // Menghapus tabel jika rollback
    }
};
