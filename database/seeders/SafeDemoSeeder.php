<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Barang;
use App\Models\Transaction;
use App\Models\Karyawan;
use App\Models\Gaji;
use App\Models\UtangPiutang;
use App\Models\Pajak;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class SafeDemoSeeder extends Seeder
{
    public function run()
    {
        $this->command->info("\n================================================");
        $this->command->info("🎯 Memulai Proses Seeding Data Demo...");
        $this->command->info("================================================\n");

        // Nonaktifkan foreign key checks untuk truncate
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Kosongkan semua tabel terkait
        Transaction::truncate();
        Barang::truncate();
        Karyawan::truncate();
        Gaji::truncate();
        UtangPiutang::truncate();
        Pajak::truncate();
        
        $this->command->warn("🗑️  Semua data lama berhasil dihapus.");

        // Aktifkan kembali foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // --- PEMBUATAN DATA MASTER ---
        
        // 1. Buat Data Karyawan
        $karyawan1 = Karyawan::create(['nama_lengkap' => 'Adam Sholihuddin', 'jabatan' => 'CEO / Founder', 'status_karyawan' => 'tetap', 'tanggal_bergabung' => '2024-01-01', 'gaji_pokok_default' => 15000000]);
        $karyawan2 = Karyawan::create(['nama_lengkap' => 'Budi Santoso', 'jabatan' => 'Manajer Marketing', 'status_karyawan' => 'tetap', 'tanggal_bergabung' => '2024-02-15', 'gaji_pokok_default' => 9000000]);
        $karyawan3 = Karyawan::create(['nama_lengkap' => 'Citra Lestari', 'jabatan' => 'Staf Akuntansi', 'status_karyawan' => 'kontrak', 'tanggal_bergabung' => '2025-03-01', 'gaji_pokok_default' => 5500000]);
        $this->command->info("👥  Created 3 Karyawan.");

        // 2. Buat Data Barang
        $laptop = Barang::create(['nama' => 'Laptop ASUS VivoBook Pro', 'kategori' => 'Elektronik', 'harga' => 12500000, 'stok' => 15]);
        $printer = Barang::create(['nama' => 'Printer Epson L3210', 'kategori' => 'Elektronik', 'harga' => 2300000, 'stok' => 8]); // Stok sengaja sedikit
        $kertas = Barang::create(['nama' => 'Kertas HVS A4 75gr', 'kategori' => 'ATK', 'harga' => 48000, 'stok' => 100]);
        $this->command->info("📦  Created 3 Barang.");

        // --- PEMBUATAN DATA TRANSAKSIONAL ---

        // 3. Buat Data Utang & Piutang
        UtangPiutang::create(['tipe' => 'piutang', 'nama_kontak' => 'PT. Jaya Abadi', 'akun' => 'Piutang Usaha', 'jumlah' => 25000000, 'no_invoice' => 'INV/2025/05/001', 'tanggal' => '2025-05-20', 'jatuh_tempo' => '2025-06-20', 'status' => 'belum_lunas', 'keterangan' => 'Penjualan 2 unit laptop']);
        UtangPiutang::create(['tipe' => 'piutang', 'nama_kontak' => 'CV. Sinar Terang', 'akun' => 'Piutang Usaha', 'jumlah' => 2300000, 'no_invoice' => 'INV/2025/04/015', 'tanggal' => '2025-04-15', 'jatuh_tempo' => '2025-05-15', 'status' => 'lunas']);
        UtangPiutang::create(['tipe' => 'utang', 'nama_kontak' => 'Supplier ATK', 'akun' => 'Utang Usaha', 'jumlah' => 5000000, 'no_invoice' => 'SUP/ATK/001', 'tanggal' => '2025-06-01', 'jatuh_tempo' => '2025-07-01', 'status' => 'belum_lunas']);
        $this->command->info("💸  Created 3 Utang & Piutang records.");

        // 4. Buat Data Gaji
        Gaji::create(['karyawan_id' => $karyawan1->id, 'periode' => '2025-05-01', 'gaji_pokok' => 15000000, 'tunjangan_jabatan' => 5000000, 'pph21' => 1250000, 'total_pendapatan' => 20000000, 'total_potongan' => 1250000, 'gaji_bersih' => 18750000]);
        Gaji::create(['karyawan_id' => $karyawan2->id, 'periode' => '2025-05-01', 'gaji_pokok' => 9000000, 'tunjangan_jabatan' => 2000000, 'bonus' => 1000000, 'pph21' => 650000, 'total_pendapatan' => 12000000, 'total_potongan' => 650000, 'gaji_bersih' => 11350000]);
        $this->command->info("💰  Created 2 Gaji records for May.");

        // 5. Buat Data Pajak
        Pajak::create(['jenis_pajak' => 'PPN Keluaran', 'no_referensi' => 'FKP-001', 'tanggal_transaksi' => '2025-05-20', 'dasar_pengenaan_pajak' => 25000000, 'tarif_pajak' => 11.00, 'jumlah_pajak' => 2750000, 'status' => 'belum_dibayar']);
        Pajak::create(['jenis_pajak' => 'PPh 21', 'no_referensi' => 'GAJI/MAY/2025', 'tanggal_transaksi' => '2025-05-25', 'dasar_pengenaan_pajak' => 32000000, 'tarif_pajak' => 5.94, 'jumlah_pajak' => 1900000, 'status' => 'belum_dibayar', 'keterangan' => 'PPh 21 Gabungan Karyawan Mei']);
        $this->command->info("🧾  Created 2 Pajak records.");
        
        // 6. Buat Data Transaksi Umum (untuk mengisi laporan laba rugi & kas)
        Transaction::create(['jenis' => 'masuk', 'kategori' => 'modal', 'keterangan' => 'Modal Awal TPKU', 'jumlah' => 200000000, 'tanggal' => '2025-01-02']);
        Transaction::create(['jenis' => 'keluar', 'kategori' => 'pembelian', 'keterangan' => 'Pembelian Awal Laptop 20 unit', 'jumlah' => 150000000, 'barang_id' => $laptop->id, 'tanggal' => '2025-01-05']);
        Transaction::create(['jenis' => 'masuk', 'kategori' => 'penjualan', 'keterangan' => 'Penjualan 2 Laptop ke PT Jaya Abadi', 'jumlah' => 25000000, 'barang_id' => $laptop->id, 'tanggal' => '2025-05-20']);
        Transaction::create(['jenis' => 'keluar', 'kategori' => 'operasional', 'keterangan' => 'Biaya Listrik & Internet Mei', 'jumlah' => 2500000, 'tanggal' => '2025-05-30']);
        Transaction::create(['jenis' => 'keluar', 'kategori' => 'operasional', 'keterangan' => 'Pembayaran Gaji Mei', 'jumlah' => 30100000, 'tanggal' => '2025-05-25']); // Total gaji bersih dari data di atas
        $this->command->info("🔄  Created 5 general transactions.");


        $this->command->info("\n================================================");
        $this->command->info("✅  PROSES SEEDING SELESAI!");
        $this->command->info("================================================\n");
    }
}
