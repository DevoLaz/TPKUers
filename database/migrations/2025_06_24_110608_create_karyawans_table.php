<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('karyawans', function (Blueprint $table) {
            $table->id();
            $table->string('nama_lengkap');
            $table->string('jabatan');
            $table->string('nik')->unique()->nullable();
            $table->string('npwp')->unique()->nullable();
            $table->enum('status_karyawan', ['tetap', 'kontrak', 'harian'])->default('kontrak');
            $table->date('tanggal_bergabung');
            
            // 🔥 FIXED: Ukuran kolom diubah menjadi 20,2 agar bisa menampung angka lebih besar
            $table->decimal('gaji_pokok_default', 20, 2)->default(0);
            
            $table->boolean('aktif')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('karyawans');
    }
};
