<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Karyawan extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'karyawans';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nama_lengkap',
        'jabatan',
        'nik',
        'npwp',
        'status_karyawan',
        'tanggal_bergabung',
        'gaji_pokok_default',
        'aktif',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'tanggal_bergabung' => 'date',
        'gaji_pokok_default' => 'decimal:2',
        'aktif' => 'boolean',
    ];
}
