<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Barang;
use App\Models\Transaction;
use Carbon\Carbon;
use DB;

class SafeDemoSeeder extends Seeder
{
    public function run()
    {
        // Clear data safely
        Transaction::query()->delete();
        Barang::query()->delete();
        
        echo "\n🎯 Creating Safe Demo Data...\n\n";
        
        // Create products
        $laptop = Barang::create([
            'nama' => 'Laptop ASUS VivoBook',
            'kategori' => 'Elektronik', 
            'harga' => 7500000,
            'stok' => 10
        ]);
        
        $printer = Barang::create([
            'nama' => 'Printer Epson L3210',
            'kategori' => 'Elektronik',
            'harga' => 2300000, 
            'stok' => 5
        ]);
        
        $kertas = Barang::create([
            'nama' => 'Kertas HVS A4',
            'kategori' => 'ATK',
            'harga' => 48000,
            'stok' => 100
        ]);
        
        echo "📦 Created 3 products\n";
        
        // JANUARI - Modal & Untung
        echo "📅 Creating January data...\n";
        
        Transaction::create([
            'jenis' => 'masuk',
            'kategori' => 'modal',
            'keterangan' => 'Modal Awal TPKU',
            'jumlah' => 100000000,
            'tanggal' => '2025-01-02'
        ]);
        
        Transaction::create([
            'jenis' => 'keluar',
            'kategori' => 'pembelian',
            'keterangan' => 'Pembelian Laptop 10 unit',
            'jumlah' => 50000000,
            'qty' => 10,
            'barang_id' => $laptop->id,
            'tanggal' => '2025-01-05'
        ]);
        
        // Penjualan Januari
        for ($i = 10; $i <= 25; $i += 5) {
            Transaction::create([
                'jenis' => 'masuk',
                'kategori' => 'penjualan',
                'keterangan' => 'Penjualan Laptop',
                'jumlah' => 7500000,
                'qty' => 1,
                'barang_id' => $laptop->id,
                'tanggal' => "2025-01-{$i}"
            ]);
        }
        
        Transaction::create([
            'jenis' => 'keluar',
            'kategori' => 'operasional',
            'keterangan' => 'Gaji Karyawan Januari',
            'jumlah' => 15000000,
            'tanggal' => '2025-01-25'
        ]);
        
        // FEBRUARI - Rugi
        echo "📅 Creating February data (LOSS)...\n";
        
        Transaction::create([
            'jenis' => 'keluar',
            'kategori' => 'pembelian',
            'keterangan' => 'Pembelian Printer (Harga Naik)',
            'jumlah' => 8000000,
            'qty' => 3,
            'barang_id' => $printer->id,
            'tanggal' => '2025-02-03'
        ]);
        
        Transaction::create([
            'jenis' => 'masuk',
            'kategori' => 'penjualan',
            'keterangan' => 'Flash Sale Printer (Rugi)',
            'jumlah' => 1800000,
            'qty' => 1,
            'barang_id' => $printer->id,
            'tanggal' => '2025-02-14'
        ]);
        
        Transaction::create([
            'jenis' => 'keluar',
            'kategori' => 'operasional',
            'keterangan' => 'Perbaikan AC Rusak',
            'jumlah' => 5000000,
            'tanggal' => '2025-02-10'
        ]);
        
        Transaction::create([
            'jenis' => 'keluar',
            'kategori' => 'operasional',
            'keterangan' => 'Gaji + THR Februari',
            'jumlah' => 25000000,
            'tanggal' => '2025-02-25'
        ]);
        
        // MARET - Recovery
        echo "📅 Creating March data...\n";
        
        for ($day = 5; $day <= 25; $day += 10) {
            Transaction::create([
                'jenis' => 'masuk',
                'kategori' => 'penjualan',
                'keterangan' => 'Penjualan Laptop',
                'jumlah' => 7500000,
                'qty' => 1,
                'barang_id' => $laptop->id,
                'tanggal' => "2025-03-{$day}"
            ]);
        }
        
        // APRIL - Best Month
        echo "📅 Creating April data (HIGH PROFIT)...\n";
        
        for ($i = 1; $i <= 5; $i++) {
            Transaction::create([
                'jenis' => 'masuk',
                'kategori' => 'penjualan',
                'keterangan' => "Penjualan Laptop #$i",
                'jumlah' => 7500000,
                'qty' => 1,
                'barang_id' => $laptop->id,
                'tanggal' => "2025-04-" . ($i * 5)
            ]);
        }
        
        Transaction::create([
            'jenis' => 'masuk',
            'kategori' => 'penjualan',
            'keterangan' => 'Project PT XYZ (5 Laptop)',
            'jumlah' => 37500000,
            'tanggal' => '2025-04-15'
        ]);
        
        // MEI
        echo "📅 Creating May data...\n";
        
        Transaction::create([
            'jenis' => 'masuk',
            'kategori' => 'penjualan',
            'keterangan' => 'Penjualan Printer',
            'jumlah' => 2300000,
            'qty' => 1,
            'barang_id' => $printer->id,
            'tanggal' => '2025-05-10'
        ]);
        
        Transaction::create([
            'jenis' => 'keluar',
            'kategori' => 'operasional',
            'keterangan' => 'Gaji Mei',
            'jumlah' => 15000000,
            'tanggal' => '2025-05-25'
        ]);
        
        // JUNI - Current
        echo "📅 Creating June data (current)...\n";
        
        Transaction::create([
            'jenis' => 'masuk',
            'kategori' => 'penjualan',
            'keterangan' => 'Penjualan Laptop Awal Juni',
            'jumlah' => 7500000,
            'qty' => 1,
            'barang_id' => $laptop->id,
            'tanggal' => '2025-06-05'
        ]);
        
        Transaction::create([
            'jenis' => 'keluar',
            'kategori' => 'operasional',
            'keterangan' => 'Listrik Juni',
            'jumlah' => 1500000,
            'tanggal' => Carbon::today()
        ]);
        
        $this->printSummary();
    }
    
    private function printSummary()
    {
        echo "\n================================================\n";
        echo "✅ DEMO DATA CREATED SUCCESSFULLY!\n";
        echo "================================================\n\n";
        
        $months = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret',
            4 => 'April', 5 => 'Mei', 6 => 'Juni'
        ];
        
        echo "📊 MONTHLY PROFIT/LOSS:\n";
        foreach ($months as $num => $name) {
            $income = Transaction::where('jenis', 'masuk')
                ->whereMonth('tanggal', $num)
                ->whereYear('tanggal', 2025)
                ->sum('jumlah');
                
            $expense = Transaction::where('jenis', 'keluar')
                ->whereMonth('tanggal', $num)
                ->whereYear('tanggal', 2025)
                ->sum('jumlah');
                
            $profit = $income - $expense;
            $status = $profit >= 0 ? '✅' : '❌';
            
            echo sprintf("- %s: %s Rp %s\n", 
                $name, 
                $status,
                number_format(abs($profit), 0, ',', '.')
            );
        }
        
        echo "\n📦 Products: " . Barang::count() . "\n";
        echo "💰 Transactions: " . Transaction::count() . "\n\n";
        echo "🎯 Ready for testing!\n";
    }
}