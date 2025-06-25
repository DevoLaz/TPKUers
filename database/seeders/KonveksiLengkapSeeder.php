<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Barang;
use App\Models\Supplier;
use App\Models\Pengadaan;
use App\Models\Karyawan;
use App\Models\Gaji;
use App\Models\AsetTetap;
use App\Models\UtangPiutang;
use App\Models\Pajak;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

/**
 * Seeder lengkap untuk data konveksi.
 * Jalankan dengan `php artisan db:seed --class=KonveksiLengkapSeeder`
 */
class KonveksiLengkapSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info("\n=================================================");
        $this->command->info("🔥 Memulai Seeding Data Lengkap Konveksi TPKU 🔥");
        $this->command->info("=================================================\n");

        // 1. Kosongkan Tabel (Aman dengan Foreign Key Check)
        $this->command->warn('🗑️  Mengosongkan tabel-tabel terkait...');
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Transaction::truncate();
        Pajak::truncate();
        UtangPiutang::truncate();
        Gaji::truncate();
        AsetTetap::truncate();
        Pengadaan::truncate();
        Supplier::truncate();
        Karyawan::truncate();
        Barang::truncate();
        User::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        $this->command->info('✅  Tabel berhasil dikosongkan.');

        // 2. Buat User Utama
        $this->command->line('👤 Membuat user utama...');
        // Kita tetap buat user, tapi tidak akan dipakai di tabel transaksi
        $user = User::create([
            'name' => 'Admin Konveksi',
            'email' => 'admin@tpku.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);
        $this->command->info('-> User "admin@tpku.com" (password: "password") berhasil dibuat.');

        // 3. Buat Supplier
        $this->command->line('🚚 Membuat data supplier...');
        $supplierKain = Supplier::create(['nama_supplier' => 'PT. Sinar Jaya Textile', 'kontak_person' => 'Bapak Budi', 'no_telepon' => '081234567890', 'alamat' => 'Jl. Kain No. 1, Jakarta']);
        $supplierBenang = Supplier::create(['nama_supplier' => 'CV. Benang Mas', 'kontak_person' => 'Ibu Rini', 'no_telepon' => '082345678901', 'alamat' => 'Jl. Benang No. 2, Bandung']);
        $supplierAksesoris = Supplier::create(['nama_supplier' => 'UD. Aksesoris Garmen', 'kontak_person' => 'Mas Anto', 'no_telepon' => '083456789012', 'alamat' => 'Jl. Kancing No. 3, Surabaya']);
        $this->command->info('-> 3 supplier berhasil dibuat.');

        // 4. Buat Barang (Bahan Baku & Produk Jadi)
        $this->command->line('📦 Membuat data barang (bahan baku & produk jadi)...');
        $kainCombed = Barang::create(['kode_barang' => 'BB-001', 'nama' => 'Kain Cotton Combed 30s Hitam', 'kategori' => 'Kain', 'unit' => 'kg', 'harga' => 120000, 'stok' => 0, 'status' => 'Habis']);
        $benangJahit = Barang::create(['kode_barang' => 'BB-002', 'nama' => 'Benang Jahit 5000 Yard Hitam', 'kategori' => 'Benang', 'unit' => 'pcs', 'harga' => 25000, 'stok' => 0, 'status' => 'Habis']);
        $kancing = Barang::create(['kode_barang' => 'BB-003', 'nama' => 'Kancing Baju Hitam (1 gross)', 'kategori' => 'Aksesoris', 'unit' => 'pak', 'harga' => 15000, 'stok' => 0, 'status' => 'Habis']);
        $kaosPolos = Barang::create(['kode_barang' => 'PJ-001', 'nama' => 'Kaos Polos Hitam Pria', 'kategori' => 'Pakaian Jadi', 'unit' => 'pcs', 'harga' => 75000, 'stok' => 50, 'status' => 'Tersedia']);
        $kemejaKerja = Barang::create(['kode_barang' => 'PJ-002', 'nama' => 'Kemeja Kerja Lengan Panjang Navy', 'kategori' => 'Pakaian Jadi', 'unit' => 'pcs', 'harga' => 185000, 'stok' => 30, 'status' => 'Tersedia']);
        $this->command->info('-> 5 master barang berhasil dibuat.');

        // 5. Buat Karyawan
        $this->command->line('👥 Membuat data karyawan...');
        $manager = Karyawan::create(['nama_lengkap' => 'Andi Wijaya', 'jabatan' => 'Manajer Produksi', 'nik' => '1234567890123456', 'npwp' => '987654321098765', 'status_karyawan' => 'tetap', 'tanggal_bergabung' => '2023-01-15', 'gaji_pokok_default' => 8000000, 'aktif' => true]);
        $penjahit = Karyawan::create(['nama_lengkap' => 'Siti Aminah', 'jabatan' => 'Operator Jahit', 'nik' => '2345678901234567', 'status_karyawan' => 'kontrak', 'tanggal_bergabung' => '2024-03-10', 'gaji_pokok_default' => 450000, 'aktif' => true]);
        $cs = Karyawan::create(['nama_lengkap' => 'Dewi Lestari', 'jabatan' => 'Customer Service', 'nik' => '3456789012345678', 'status_karyawan' => 'harian', 'tanggal_bergabung' => '2024-05-20', 'gaji_pokok_default' => 150000, 'aktif' => true]);
        $this->command->info('-> 3 karyawan berhasil dibuat.');

        // 6. Buat Aset Tetap
        $this->command->line('🏛️  Membuat data aset tetap...');
        AsetTetap::create(['nama_aset' => 'Mesin Jahit JUKI DDL-8100e', 'deskripsi' => 'Mesin jahit high-speed untuk produksi massal', 'kategori' => 'Mesin Produksi', 'tanggal_perolehan' => '2023-02-01', 'harga_perolehan' => 7500000, 'masa_manfaat' => 5, 'nilai_residu' => 500000]);
        AsetTetap::create(['nama_aset' => 'Ruko Produksi', 'deskripsi' => 'Gedung tempat usaha konveksi', 'kategori' => 'Bangunan', 'tanggal_perolehan' => '2022-01-01', 'harga_perolehan' => 850000000, 'masa_manfaat' => 20, 'nilai_residu' => 100000000]);
        $this->command->info('-> 2 aset tetap berhasil dibuat.');

        // 7. Transaksi Pengadaan Bahan Baku (sekaligus mengisi stok & utang)
        $this->command->line('🔄 Membuat transaksi pengadaan...');
        $pengadaan1 = Pengadaan::create(['barang_id' => $kainCombed->id, 'supplier_id' => $supplierKain->id, 'tanggal_pembelian' => '2025-05-10', 'no_invoice' => 'INV-TEXTILE-001', 'jumlah_masuk' => 100, 'harga_beli' => 115000, 'total_harga' => 11500000, 'keterangan' => 'Pembelian kain batch 1']);
        $kainCombed->update(['stok' => DB::raw("stok + {$pengadaan1->jumlah_masuk}")]);
        UtangPiutang::create(['tipe' => 'utang', 'nama_kontak' => $supplierKain->nama_supplier, 'akun' => 'Utang Usaha', 'jumlah' => $pengadaan1->total_harga, 'no_invoice' => $pengadaan1->no_invoice, 'tanggal' => $pengadaan1->tanggal_pembelian, 'jatuh_tempo' => '2025-06-10', 'status' => 'belum_lunas']);
        Transaction::create(['jenis' => 'keluar', 'kategori' => 'pembelian', 'keterangan' => "Beli: {$kainCombed->nama}", 'jumlah' => $pengadaan1->total_harga, 'barang_id' => $kainCombed->id, 'qty' => $pengadaan1->jumlah_masuk, 'tanggal' => $pengadaan1->tanggal_pembelian]); // DIHAPUS user_id

        $pengadaan2 = Pengadaan::create(['barang_id' => $benangJahit->id, 'supplier_id' => $supplierBenang->id, 'tanggal_pembelian' => '2025-05-11', 'no_invoice' => 'INV-BENANG-001', 'jumlah_masuk' => 200, 'harga_beli' => 24000, 'total_harga' => 4800000, 'keterangan' => 'Stok benang bulanan']);
        $benangJahit->update(['stok' => DB::raw("stok + {$pengadaan2->jumlah_masuk}")]);
        UtangPiutang::create(['tipe' => 'utang', 'nama_kontak' => $supplierBenang->nama_supplier, 'akun' => 'Utang Usaha', 'jumlah' => $pengadaan2->total_harga, 'no_invoice' => $pengadaan2->no_invoice, 'tanggal' => $pengadaan2->tanggal_pembelian, 'jatuh_tempo' => '2025-06-11', 'status' => 'lunas']);
        Transaction::create(['jenis' => 'keluar', 'kategori' => 'pembelian', 'keterangan' => "Beli: {$benangJahit->nama}", 'jumlah' => $pengadaan2->total_harga, 'barang_id' => $benangJahit->id, 'qty' => $pengadaan2->jumlah_masuk, 'tanggal' => $pengadaan2->tanggal_pembelian]); // DIHAPUS user_id
        $this->command->info('-> 2 transaksi pengadaan & utang terkait berhasil dibuat.');

        // 8. Transaksi Penjualan Produk Jadi (sekaligus mengisi piutang)
        $this->command->line('💰 Membuat transaksi penjualan...');
        $penjualan1 = Transaction::create(['jenis' => 'masuk', 'kategori' => 'penjualan', 'keterangan' => 'Penjualan 20 Kaos ke Distro Keren', 'jumlah' => 1500000, 'barang_id' => $kaosPolos->id, 'qty' => 20, 'tanggal' => '2025-06-01']); // DIHAPUS user_id
        $kaosPolos->update(['stok' => DB::raw("stok - {$penjualan1->qty}")]);
        UtangPiutang::create(['tipe' => 'piutang', 'nama_kontak' => 'Distro Keren', 'akun' => 'Piutang Usaha', 'jumlah' => $penjualan1->jumlah, 'no_invoice' => 'INV-2025-001', 'tanggal' => $penjualan1->tanggal, 'jatuh_tempo' => '2025-07-01', 'status' => 'belum_lunas']);

        $penjualan2 = Transaction::create(['jenis' => 'masuk', 'kategori' => 'penjualan', 'keterangan' => 'Penjualan 10 Kemeja ke Kantor ABC', 'jumlah' => 1850000, 'barang_id' => $kemejaKerja->id, 'qty' => 10, 'tanggal' => '2025-06-05']); // DIHAPUS user_id
        $kemejaKerja->update(['stok' => DB::raw("stok - {$penjualan2->qty}")]);
        $this->command->info('-> 2 transaksi penjualan & piutang terkait berhasil dibuat.');
        
        // 9. Transaksi Gaji (sekaligus utang gaji)
        $this->command->line('💸 Membuat transaksi penggajian...');
        $gaji1 = Gaji::create(['karyawan_id' => $manager->id, 'periode' => '2025-05-01', 'gaji_pokok' => 8000000, 'tunjangan_jabatan' => 2000000, 'bonus' => 500000, 'pph21' => 425000, 'total_pendapatan' => 10500000, 'total_potongan' => 425000, 'gaji_bersih' => 10075000]);
        $gaji2 = Gaji::create(['karyawan_id' => $penjahit->id, 'periode' => '2025-05-01', 'gaji_pokok' => 4500000, 'tunjangan_transport' => 300000, 'bonus' => 200000, 'bpjs' => 50000, 'total_pendapatan' => 5000000, 'total_potongan' => 50000, 'gaji_bersih' => 4950000]);
        Transaction::create(['jenis' => 'keluar', 'kategori' => 'gaji', 'keterangan' => 'Pembayaran Gaji Mei 2025', 'jumlah' => ($gaji1->gaji_bersih + $gaji2->gaji_bersih), 'tanggal' => '2025-05-25']); // DIHAPUS user_id
        $this->command->info('-> 2 data gaji berhasil dibuat.');
        
        // 10. Transaksi Pajak
        $this->command->line('🧾 Membuat transaksi pajak...');
        Pajak::create(['jenis_pajak' => 'PPN Masukan', 'no_referensi' => 'INV-TEXTILE-001', 'tanggal_transaksi' => '2025-05-10', 'dasar_pengenaan_pajak' => 11500000, 'tarif_pajak' => 11, 'jumlah_pajak' => 1265000, 'status' => 'belum_dibayar', 'keterangan' => 'PPN atas pembelian kain dari PT Sinar Jaya Textile']);
        Pajak::create(['jenis_pajak' => 'PPN Keluaran', 'no_referensi' => 'INV-2025-001', 'tanggal_transaksi' => '2025-06-01', 'dasar_pengenaan_pajak' => 1500000, 'tarif_pajak' => 11, 'jumlah_pajak' => 165000, 'status' => 'belum_dibayar', 'keterangan' => 'PPN atas penjualan ke Distro Keren']);
        Pajak::create(['jenis_pajak' => 'PPh 21', 'no_referensi' => 'GAJI-MEI-2025', 'tanggal_transaksi' => '2025-05-25', 'dasar_pengenaan_pajak' => 10500000, 'tarif_pajak' => 5, 'jumlah_pajak' => 425000, 'status' => 'sudah_dibayar', 'keterangan' => 'PPh 21 Gaji Sdr. Andi Wijaya']);
        $this->command->info('-> 3 data pajak (PPN & PPh) berhasil dibuat.');
        
        // 11. Transaksi Operasional Lainnya
        $this->command->line('💡 Membuat transaksi operasional lainnya...');
        Transaction::create(['jenis' => 'keluar', 'kategori' => 'operasional', 'keterangan' => 'Biaya Listrik & Air Bulan Mei', 'jumlah' => 1200000, 'tanggal' => '2025-06-03']); // DIHAPUS user_id
        Transaction::create(['jenis' => 'keluar', 'kategori' => 'operasional', 'keterangan' => 'Biaya Internet Kantor Bulan Mei', 'jumlah' => 450000, 'tanggal' => '2025-06-05']); // DIHAPUS user_id
        Transaction::create(['jenis' => 'keluar', 'kategori' => 'operasional', 'keterangan' => 'Biaya Pemasaran & Iklan', 'jumlah' => 2500000, 'tanggal' => '2025-06-10']); // DIHAPUS user_id
        $this->command->info('-> 3 transaksi operasional berhasil dibuat.');

        $this->command->info("\n================================================");
        $this->command->info("✅  PROSES SEEDING LENGKAP SELESAI! ✅");
        $this->command->info("================================================\n");
    }
}