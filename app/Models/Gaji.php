<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gaji extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'gajis';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'karyawan_id',
        'periode',
        'gaji_pokok',
        'tunjangan_jabatan',
        'tunjangan_transport',
        'bonus',
        'pph21',
        'bpjs',
        'potongan_lain',
        'total_pendapatan',
        'total_potongan',
        'gaji_bersih',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'periode' => 'date',
    ];

    /**
     * Get the karyawan that owns the gaji.
     */
    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class);
    }
}
