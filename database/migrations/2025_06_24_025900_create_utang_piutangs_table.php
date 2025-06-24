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
        Schema::create('utang_piutangs', function (Blueprint $table) {
            $table->id();
            $table->enum('tipe', ['utang', 'piutang']); // Menentukan jenisnya, utang atau piutang
            $table->string('nama_kontak'); // Nama pelanggan atau pemasok
            $table->string('akun'); // Jenis akun seperti 'Utang Usaha', 'Piutang Usaha', dll.
            $table->decimal('jumlah', 15, 2); // Jumlah uang
            $table->string('no_invoice')->nullable(); // Nomor invoice atau referensi
            $table->date('tanggal'); // Tanggal transaksi
            $table->date('jatuh_tempo'); // Tanggal jatuh tempo
            $table->text('keterangan')->nullable(); // Catatan tambahan
            $table->enum('status', ['belum_lunas', 'lunas', 'sebagian'])->default('belum_lunas');
            $table->timestamps(); // Ini akan otomatis membuat kolom created_at dan updated_at
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('utang_piutangs');
    }
};
