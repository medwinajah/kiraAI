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
        Schema::create('tabungans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_users')->constrained('users','id')->cascadeOnDelete()->cascadeOnUpdate();
            $table->string("label_tabungan");
            $table->string('gambar_target_menabung')->nullable();
            $table->integer('target_tabungan')->default(0);
            $table->timestamp('tanggal_awal_menabung');
            $table->integer("tabungan_awal")->default(0);
            $table->enum('status_tabungan',['new','proses','sukses']);
            $table->timestamp('tanggal_target_tercapai')->nullable();
            $table->text('deskripsi')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tabungans');
    }
};
