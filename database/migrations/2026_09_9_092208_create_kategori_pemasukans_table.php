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
        Schema::create('kategori_pemasukans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_users')->constrained('users', 'id')->cascadeOnDelete()->cascadeOnUpdate();
            $table->string("nama_kategori");
            $table->text("deskripsi_kategori")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kategori_pemasukans');
    }
};
