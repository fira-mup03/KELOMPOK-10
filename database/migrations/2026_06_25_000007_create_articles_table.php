<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            $table->string('title', 255);
            $table->string('slug', 255)->unique();
            $table->enum('category', ['perawatan', 'penyakit', 'tips', 'nutrisi']);
            $table->string('thumbnail', 255)->nullable();
            $table->longText('content');
            $table->integer('read_time')->default(3)->comment('Estimasi waktu baca dalam menit');
            $table->boolean('is_published')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('articles');
    }
};
