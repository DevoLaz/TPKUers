<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('transaksis', function (Blueprint $table) {
            // Cek dulu sebelum nambah kolom
            if (!Schema::hasColumn('transaksis', 'kategori')) {
                $table->string('kategori')->nullable()->after('jenis');
            }
            
            if (!Schema::hasColumn('transaksis', 'qty')) {
                $table->integer('qty')->nullable()->after('jumlah');
            }
            
            if (!Schema::hasColumn('transaksis', 'barang_id')) {
                $table->unsignedBigInteger('barang_id')->nullable()->after('qty');
                $table->foreign('barang_id')->references('id')->on('barangs')->onDelete('set null');
            }
        });
    }

    public function down()
    {
        Schema::table('transaksis', function (Blueprint $table) {
            if (Schema::hasColumn('transaksis', 'barang_id')) {
                $table->dropForeign(['barang_id']);
                $table->dropColumn('barang_id');
            }
            
            if (Schema::hasColumn('transaksis', 'qty')) {
                $table->dropColumn('qty');
            }
            
            if (Schema::hasColumn('transaksis', 'kategori')) {
                $table->dropColumn('kategori');
            }
        });
    }
};