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
        Schema::create('gajis', function (Blueprint $table) {
            $table->id();
            // Menghubungkan ke tabel karyawans
            $table->foreignId('karyawan_id')->constrained('karyawans')->onDelete('cascade');
            
            $table->date('periode'); // Menyimpan periode gaji, misal: 2025-06-01
            
            // 🔥 FIXED: Ukuran semua kolom uang diperbesar
            // Komponen Pendapatan
            $table->decimal('gaji_pokok', 20, 2)->default(0);
            $table->decimal('tunjangan_jabatan', 20, 2)->default(0);
            $table->decimal('tunjangan_transport', 20, 2)->default(0);
            $table->decimal('bonus', 20, 2)->default(0);
            
            // Komponen Potongan
            $table->decimal('pph21', 20, 2)->default(0);
            $table->decimal('bpjs', 20, 2)->default(0);
            $table->decimal('potongan_lain', 20, 2)->default(0);
            
            // Total Kalkulasi
            $table->decimal('total_pendapatan', 20, 2);
            $table->decimal('total_potongan', 20, 2);
            $table->decimal('gaji_bersih', 20, 2);

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
        Schema::dropIfExists('gajis');
    }
};
