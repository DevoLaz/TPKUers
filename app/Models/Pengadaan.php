<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pengadaan extends Model
{
    use HasFactory;

    protected $table = 'pengadaans';

    protected $fillable = [
        'barang_id',
        'supplier_id', 
        'tanggal_pembelian',
        'no_invoice',
        'jumlah_masuk',
        'harga_beli',
        'total_harga',
        'keterangan'
    ];

    protected $casts = [
        'tanggal_pembelian' => 'date',
        'harga_beli' => 'decimal:2',
        'total_harga' => 'decimal:2'
    ];

    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }
}