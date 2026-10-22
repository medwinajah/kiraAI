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
        Schema::create('list_tabungans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_tabungan')->constrained('tabungans','id')->cascadeOnDelete()->cascadeOnUpdate();
            $table->integer("tambahan_tabungan")->default(0);
            $table->timestamp('tanggal_tambahan');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('list_tabungans');
    }
};
