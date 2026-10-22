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
        Schema::create('pengeluarans', function (Blueprint $table) {
            $table->id();
             $table->foreignId('id_users')->constrained('users','id')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('id_kategori')->constrained('kategori_pengelurans','id')->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('label');
            $table->integer("jumlah_pengeluaran")->default(0);
            $table->text('deskripsi_pengeluaran')->nullable();
            $table->timestamp('tanggal_pengeluaran');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengeluarans');
    }
};
