<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UtangPiutang extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'utang_piutangs';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'tipe',
        'nama_kontak',
        'akun',
        'jumlah',
        'no_invoice',
        'tanggal',
        'jatuh_tempo',
        'keterangan',
        'status',
    ];
}
