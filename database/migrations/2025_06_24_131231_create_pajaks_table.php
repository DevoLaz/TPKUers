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
        Schema::create('pajaks', function (Blueprint $table) {
            $table->id();
            $table->string('jenis_pajak'); // Contoh: PPN Masukan, PPh 21, dll.
            $table->string('no_referensi')->nullable();
            $table->date('tanggal_transaksi');
            $table->decimal('dasar_pengenaan_pajak', 20, 2); // DPP
            $table->decimal('tarif_pajak', 5, 2); // Tarif dalam persen, misal: 11.00
            $table->decimal('jumlah_pajak', 20, 2); // Hasil perhitungan
            $table->enum('status', ['sudah_dibayar', 'belum_dibayar'])->default('belum_dibayar');
            $table->text('keterangan')->nullable();
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
        Schema::dropIfExists('pajaks');
    }
};
