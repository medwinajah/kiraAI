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
        Schema::create('pemasukans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_users')->constrained('users','id')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('id_kategori')->constrained('kategori_pemasukans','id')->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('label');
            $table->integer('jumlah_pemasukan')->default(0);
            $table->string("sumber_pemasukan")->nullable();
            $table->timestamp('tanggal_pemasukan');
            $table->text('deskripsi_pemasukan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pemasukans');
    }
};
