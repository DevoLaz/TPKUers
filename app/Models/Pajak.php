<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pajak extends Model
{
    use HasFactory;

    protected $table = 'pajaks';

    protected $fillable = [
        'jenis_pajak',
        'no_referensi',
        'tanggal_transaksi',
        'dasar_pengenaan_pajak',
        'tarif_pajak',
        'jumlah_pajak',
        'status',
        'keterangan',
    ];

    protected $casts = [
        'tanggal_transaksi' => 'date',
        'dasar_pengenaan_pajak' => 'decimal:2',
        'tarif_pajak' => 'decimal:2',
        'jumlah_pajak' => 'decimal:2',
    ];
}
