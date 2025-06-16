<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Transaction extends Model
{
    use HasFactory;
    
    protected $table = 'transaksis';
    
    protected $fillable = [
        'tanggal',
        'jenis', // masuk/keluar
        'kategori', // penjualan/pembelian/operasional/dll
        'keterangan',
        'jumlah',
        'user_id',
        'barang_id', // kalau transaksi terkait barang
        'qty', // jumlah barang kalau ada
    ];
    
    protected $casts = [
        'tanggal' => 'date',
        'jumlah' => 'decimal:2',
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }
}