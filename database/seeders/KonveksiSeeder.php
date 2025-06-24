<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Barang;
use App\Models\Supplier;
use App\Models\Pengadaan;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;

class KonveksiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->command->info("\n🔥 Memulai Seeding Data Konveksi TPKU...");

        // Mengosongkan tabel terkait dengan aman
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Pengadaan::truncate();
        Supplier::truncate();
        Barang::truncate();
        // Kosongkan juga transaksi pembelian agar tidak duplikat
        Transaction::where('kategori', 'pembelian')->delete(); 
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        $this->command->warn('🗑️  Data lama (Barang, Supplier, Pengadaan, Transaksi Pembelian) berhasil dibersihkan.');

        // --- Data Master ---
        $suppliers = [
            'TB. SUMBER KAIN' => Supplier::create(['nama_supplier' => 'TB. SUMBER KAIN']),
            'TB. BENANG MAS' => Supplier::create(['nama_supplier' => 'TB. BENANG MAS']),
            'TB. KANCING JAYA' => Supplier::create(['nama_supplier' => 'TB. KANCING JAYA']),
        ];
        $this->command->info('👥  Data Supplier dibuat.');

        $barangs = [
            'KAIN KATUN COMBED 30S HITAM' => Barang::create(['nama' => 'KAIN KATUN COMBED 30S HITAM', 'kategori' => 'KAIN', 'harga' => 95000, 'stok' => 0]),
            'KAIN KATUN COMBED 30S PUTIH' => Barang::create(['nama' => 'KAIN KATUN COMBED 30S PUTIH', 'kategori' => 'KAIN', 'harga' => 92000, 'stok' => 0]),
            'KAIN KATUN COMBED 30S NAVY' => Barang::create(['nama' => 'KAIN KATUN COMBED 30S NAVY', 'kategori' => 'KAIN', 'harga' => 98000, 'stok' => 0]),
            'BENANG JAHIT HITAM' => Barang::create(['nama' => 'BENANG JAHIT HITAM', 'kategori' => 'BENANG', 'harga' => 15000, 'stok' => 0]),
            'BENANG JAHIT PUTIH' => Barang::create(['nama' => 'BENANG JAHIT PUTIH', 'kategori' => 'BENANG', 'harga' => 15000, 'stok' => 0]),
            'KANCING HITAM 15MM' => Barang::create(['nama' => 'KANCING HITAM 15MM', 'kategori' => 'KANCING', 'harga' => 500, 'stok' => 0]),
            'KANCING PUTIH 15MM' => Barang::create(['nama' => 'KANCING PUTIH 15MM', 'kategori' => 'KANCING', 'harga' => 500, 'stok' => 0]),
        ];
        $this->command->info('📦  Data Master Bahan Baku dibuat.');

        // --- Data Transaksi Pengadaan ---
        $pengadaanData = [
            ['2025-06-01', 'INV001', 'KAIN KATUN COMBED 30S HITAM', 'TB. SUMBER KAIN', 25, 95000, 2375000],
            ['2025-06-01', 'INV002', 'KAIN KATUN COMBED 30S PUTIH', 'TB. SUMBER KAIN', 30, 92000, 2760000],
            ['2025-06-02', 'INV003', 'KAIN KATUN COMBED 30S NAVY', 'TB. SUMBER KAIN', 20, 98000, 1960000],
            ['2025-06-03', 'INV004', 'BENANG JAHIT HITAM', 'TB. BENANG MAS', 50, 15000, 750000],
            ['2025-06-03', 'INV005', 'BENANG JAHIT PUTIH', 'TB. BENANG MAS', 50, 15000, 750000],
            ['2025-06-04', 'INV006', 'KANCING HITAM 15MM', 'TB. KANCING JAYA', 500, 500, 250000],
            ['2025-06-04', 'INV007', 'KANCING PUTIH 15MM', 'TB. KANCING JAYA', 500, 500, 250000],
        ];

        foreach ($pengadaanData as $data) {
            $barang = $barangs[$data[2]];
            $supplier = $suppliers[$data[3]];
            
            // 1. Catat di tabel pengadaan
            $pengadaan = Pengadaan::create([
                'barang_id' => $barang->id,
                'supplier_id' => $supplier->id,
                'tanggal_pembelian' => $data[0],
                'no_invoice' => $data[1],
                'jumlah_masuk' => $data[4],
                'harga_beli' => $data[5],
                'total_harga' => $data[6],
                'keterangan' => 'Pembelian ' . $barang->nama,
            ]);

            // 2. Update stok barang
            $barang->stok += $data[4];
            $barang->save();

            // 3. Catat sebagai transaksi pengeluaran kas
            Transaction::create([
                'jenis' => 'keluar',
                'kategori' => 'pembelian',
                'jumlah' => $data[6],
                'tanggal' => $data[0],
                'keterangan' => "Pembelian {$barang->nama} dari {$supplier->nama_supplier}",
                'barang_id' => $barang->id,
                'qty' => $data[4],
            ]);
        }
        $this->command->info('🚚  Data Riwayat Pengadaan & Transaksi berhasil dibuat.');
        
        $this->command->info("\n================================================");
        $this->command->info("✅  PROSES SEEDING KONVEKSI SELESAI!");
        $this->command->info("================================================\n");
    }
}
