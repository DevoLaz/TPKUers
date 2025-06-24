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
        Schema::table('barangs', function (Blueprint $table) {
            // Cek apakah kolom sudah ada atau belum
            if (!Schema::hasColumn('barangs', 'kode_barang')) {
                $table->string('kode_barang', 50)->unique()->after('id');
            }
            
            if (!Schema::hasColumn('barangs', 'unit')) {
                $table->string('unit', 20)->default('pcs')->after('kategori');
            }
            
            if (!Schema::hasColumn('barangs', 'status')) {
                $table->enum('status', ['Tersedia', 'Habis'])->default('Tersedia')->after('stok');
            }
            
            // Index untuk performance
            if (!Schema::hasIndex('barangs', ['kode_barang'])) {
                $table->index('kode_barang');
            }
            
            if (!Schema::hasIndex('barangs', ['nama'])) {
                $table->index('nama');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('barangs', function (Blueprint $table) {
            // Drop kolom yang ditambahkan (hati-hati sama data!)
            $table->dropColumn(['kode_barang', 'unit', 'status']);
        });
    }
};