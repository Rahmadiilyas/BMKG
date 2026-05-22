<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('link_archives', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('link_id');
    $table->unsignedBigInteger('kategori_id');
    $table->string('judul_link', 150);
    $table->text('url');
    $table->text('keterangan')->nullable();
    $table->timestamps();

    $table->foreign('link_id')->references('id')->on('links')->onDelete('cascade');
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
