<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Barang;
use App\Models\Transaction;
use Carbon\Carbon;
use DB;

class TestingDataSeeder extends Seeder
{
    public function run()
    {
        // Clear existing data
        DB::table('transaksis')->delete();
        DB::table('barangs')->delete();
        
        echo "🔧 Creating test data for TPKU System...\n";
        
        // 1. BUAT DATA BARANG
        echo "📦 Creating products...\n";
        $barangs = [
            // ATK
            ['nama' => 'Pulpen Pilot', 'kategori' => 'ATK', 'harga' => 5000, 'stok' => 50],
            ['nama' => 'Kertas A4 (Rim)', 'kategori' => 'ATK', 'harga' => 45000, 'stok' => 20],
            ['nama' => 'Stapler HD-10', 'kategori' => 'ATK', 'harga' => 25000, 'stok' => 15],
            ['nama' => 'Spidol Whiteboard', 'kategori' => 'ATK', 'harga' => 8000, 'stok' => 30],
            
            // Elektronik
            ['nama' => 'Mouse Wireless Logitech', 'kategori' => 'Elektronik', 'harga' => 150000, 'stok' => 10],
            ['nama' => 'Keyboard USB', 'kategori' => 'Elektronik', 'harga' => 200000, 'stok' => 8],
            ['nama' => 'Flashdisk 16GB', 'kategori' => 'Elektronik', 'harga' => 80000, 'stok' => 25],
            ['nama' => 'Printer Epson L3210', 'kategori' => 'Elektronik', 'harga' => 2500000, 'stok' => 3],
            
            // Peralatan
            ['nama' => 'Gunting Kenko', 'kategori' => 'Peralatan', 'harga' => 15000, 'stok' => 20],
            ['nama' => 'Cutter Besar', 'kategori' => 'Peralatan', 'harga' => 12000, 'stok' => 15],
            ['nama' => 'Penggaris 30cm', 'kategori' => 'Peralatan', 'harga' => 8000, 'stok' => 25],
            ['nama' => 'Lakban Bening', 'kategori' => 'Peralatan', 'harga' => 10000, 'stok' => 40],
        ];
        
        foreach ($barangs as $barang) {
            Barang::create($barang);
        }
        
        // 2. BUAT TRANSAKSI MODAL AWAL
        echo "💰 Creating initial capital...\n";
        DB::table('transaksis')->insert([
            'jenis' => 'masuk',
            'kategori' => 'modal',
            'keterangan' => 'Modal Awal TPKU',
            'jumlah' => 50000000, // 50 juta
            'tanggal' => Carbon::now()->subMonths(3),
            'created_at' => Carbon::now()->subMonths(3),
            'updated_at' => Carbon::now()->subMonths(3),
        ]);
        
        // 3. TRANSAKSI PEMBELIAN BARANG (3 bulan terakhir)
        echo "🛒 Creating purchase transactions...\n";
        $barangList = Barang::all();
        
        // Bulan 1 - Pembelian awal
        foreach ($barangList as $barang) {
            $qtyBeli = rand(20, 50);
            $hargaBeli = $barang->harga * 0.7; // Harga beli 70% dari harga jual
            
            DB::table('transaksis')->insert([
                'jenis' => 'keluar',
                'kategori' => 'pembelian',
                'keterangan' => 'Pembelian ' . $barang->nama,
                'jumlah' => $hargaBeli * $qtyBeli,
                'qty' => $qtyBeli,
                'barang_id' => $barang->id,
                'tanggal' => Carbon::now()->subMonths(3)->addDays(rand(1, 10)),
                'created_at' => Carbon::now()->subMonths(3)->addDays(rand(1, 10)),
                'updated_at' => Carbon::now()->subMonths(3)->addDays(rand(1, 10)),
            ]);
        }
        
        // 4. TRANSAKSI PENJUALAN (tersebar 3 bulan)
        echo "💸 Creating sales transactions...\n";
        for ($i = 0; $i < 100; $i++) {
            $barang = $barangList->random();
            $qtyJual = rand(1, 5);
            $hargaJual = $barang->harga * (1 + rand(0, 20) / 100); // Markup 0-20%
            
            DB::table('transaksis')->insert([
                'jenis' => 'masuk',
                'kategori' => 'penjualan',
                'keterangan' => 'Penjualan ' . $barang->nama,
                'jumlah' => $hargaJual * $qtyJual,
                'qty' => $qtyJual,
                'barang_id' => $barang->id,
                'tanggal' => Carbon::now()->subDays(rand(1, 90)),
                'created_at' => Carbon::now()->subDays(rand(1, 90)),
                'updated_at' => Carbon::now()->subDays(rand(1, 90)),
            ]);
        }
        
        // 5. TRANSAKSI OPERASIONAL
        echo "🏢 Creating operational transactions...\n";
        $operasional = [
            ['keterangan' => 'Pembayaran Listrik', 'jumlah' => 1500000],
            ['keterangan' => 'Pembayaran Internet', 'jumlah' => 500000],
            ['keterangan' => 'Gaji Karyawan', 'jumlah' => 15000000],
            ['keterangan' => 'Sewa Gedung', 'jumlah' => 5000000],
            ['keterangan' => 'Maintenance AC', 'jumlah' => 300000],
            ['keterangan' => 'Bensin Kendaraan Operasional', 'jumlah' => 800000],
        ];
        
        // Transaksi operasional bulanan
        for ($month = 2; $month >= 0; $month--) {
            foreach ($operasional as $op) {
                DB::table('transaksis')->insert([
                    'jenis' => 'keluar',
                    'kategori' => 'operasional',
                    'keterangan' => $op['keterangan'],
                    'jumlah' => $op['jumlah'] + rand(-100000, 100000),
                    'tanggal' => Carbon::now()->subMonths($month)->startOfMonth()->addDays(rand(1, 28)),
                    'created_at' => Carbon::now()->subMonths($month),
                    'updated_at' => Carbon::now()->subMonths($month),
                ]);
            }
        }
        
        // 6. TRANSAKSI TAMBAHAN HARI INI
        echo "📅 Creating today's transactions...\n";
        
        // Penjualan hari ini
        for ($i = 0; $i < 5; $i++) {
            $barang = $barangList->random();
            DB::table('transaksis')->insert([
                'jenis' => 'masuk',
                'kategori' => 'penjualan',
                'keterangan' => 'Penjualan ' . $barang->nama,
                'jumlah' => $barang->harga * rand(1, 3),
                'qty' => rand(1, 3),
                'barang_id' => $barang->id,
                'tanggal' => Carbon::today(),
                'created_at' => Carbon::now()->subHours(rand(1, 8)),
                'updated_at' => Carbon::now(),
            ]);
        }
        
        // Pembelian ATK hari ini
        DB::table('transaksis')->insert([
            'jenis' => 'keluar',
            'kategori' => 'operasional',
            'keterangan' => 'Pembelian ATK Kantor',
            'jumlah' => 250000,
            'tanggal' => Carbon::today(),
            'created_at' => Carbon::now()->subHours(2),
            'updated_at' => Carbon::now(),
        ]);
        
        // 7. UPDATE STOK BARANG BERDASARKAN TRANSAKSI
        echo "📊 Updating product stocks...\n";
        foreach ($barangList as $barang) {
            $totalBeli = DB::table('transaksis')
                ->where('barang_id', $barang->id)
                ->where('kategori', 'pembelian')
                ->sum('qty');
                
            $totalJual = DB::table('transaksis')
                ->where('barang_id', $barang->id)
                ->where('kategori', 'penjualan')
                ->sum('qty');
                
            $stokAkhir = $totalBeli - $totalJual;
            
            Barang::where('id', $barang->id)->update(['stok' => $stokAkhir]);
        }
        
        // 8. SUMMARY
        echo "\n✅ Testing data created successfully!\n";
        echo "📦 Products: " . Barang::count() . "\n";
        echo "💰 Transactions: " . DB::table('transaksis')->count() . "\n";
        echo "📈 Total Income: Rp " . number_format(DB::table('transaksis')->where('jenis', 'masuk')->sum('jumlah'), 0, ',', '.') . "\n";
        echo "📉 Total Expense: Rp " . number_format(DB::table('transaksis')->where('jenis', 'keluar')->sum('jumlah'), 0, ',', '.') . "\n";
        
        $saldo = DB::table('transaksis')->where('jenis', 'masuk')->sum('jumlah') - 
                 DB::table('transaksis')->where('jenis', 'keluar')->sum('jumlah');
        echo "💵 Current Balance: Rp " . number_format($saldo, 0, ',', '.') . "\n\n";
        
        echo "🎯 You can now test all reports with integrated data!\n";
    }
}