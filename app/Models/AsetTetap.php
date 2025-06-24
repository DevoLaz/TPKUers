<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class AsetTetap extends Model
{
    use HasFactory;

    protected $table = 'aset_tetaps';

    protected $fillable = [
        'nama_aset',
        'deskripsi',
        'kategori',
        'tanggal_perolehan',
        'harga_perolehan',
        'masa_manfaat',
        'nilai_residu',
        'metode_penyusutan',
    ];

    protected $casts = [
        'tanggal_perolehan' => 'date',
        'harga_perolehan' => 'decimal:2',
        'nilai_residu' => 'decimal:2',
    ];

    /**
     * Menghitung penyusutan per tahun.
     */
    public function getPenyusutanPerTahunAttribute()
    {
        if ($this->masa_manfaat > 0) {
            return ($this->harga_perolehan - $this->nilai_residu) / $this->masa_manfaat;
        }
        return 0;
    }

    /**
     * Menghitung akumulasi penyusutan hingga saat ini.
     */
    public function getAkumulasiPenyusutanAttribute()
    {
        $penyusutanTahunan = $this->penyusutan_per_tahun;
        $tanggalPerolehan = Carbon::parse($this->tanggal_perolehan);
        $hariBerlalu = $tanggalPerolehan->diffInDays(Carbon::now());
        $tahunBerlalu = $hariBerlalu / 365;

        if ($tahunBerlalu > $this->masa_manfaat) {
            $tahunBerlalu = $this->masa_manfaat;
        }

        return $penyusutanTahunan * $tahunBerlalu;
    }

    /**
     * Menghitung nilai buku aset saat ini.
     */
    public function getNilaiBukuAttribute()
    {
        return $this->harga_perolehan - $this->akumulasi_penyusutan;
    }
}