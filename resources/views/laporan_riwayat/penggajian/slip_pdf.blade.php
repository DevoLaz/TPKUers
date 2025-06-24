<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Slip Gaji - {{ $gaji->karyawan->nama_lengkap }}</title>
    <style>
        body { font-family: 'Helvetica', sans-serif; font-size: 12px; color: #333; }
        .container { width: 100%; margin: 0 auto; padding: 20px; }
        .header { text-align: center; border-bottom: 2px solid #333; padding-bottom: 10px; margin-bottom: 20px; }
        .header h1 { margin: 0; font-size: 24px; color: #173720; }
        .header p { margin: 2px 0; }
        .info-section { margin-bottom: 20px; }
        .info-section table { width: 100%; }
        .info-section td { padding: 4px; vertical-align: top; }
        .details-section table { width: 100%; border-collapse: collapse; }
        .details-section th, .details-section td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        .details-section th { background-color: #f2f2f2; font-weight: bold; }
        .text-right { text-align: right; }
        .total-row { font-weight: bold; background-color: #f9f9f9; }
        .grand-total { margin-top: 20px; padding: 10px; border-top: 2px double #333; background-color: #eee; }
        .signatures { margin-top: 40px; width: 100%; }
        .signature-box { width: 45%; display: inline-block; text-align: center; }
        .signature-box .signature-line { border-bottom: 1px solid #333; margin-top: 60px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>SLIP GAJI KARYAWAN</h1>
            <p><strong>TPKU FINANCE</strong></p>
            <p>Periode: {{ \Carbon\Carbon::parse($gaji->periode)->isoFormat('MMMM YYYY') }}</p>
        </div>

        <div class="info-section">
            <table>
                <tr>
                    <td width="50%">
                        <strong>Nama Karyawan:</strong> {{ $gaji->karyawan->nama_lengkap }}
                    </td>
                    <td width="50%">
                        <strong>Jabatan:</strong> {{ $gaji->karyawan->jabatan }}
                    </td>
                </tr>
                <tr>
                    <td>
                        <strong>Status:</strong> {{ ucfirst($gaji->karyawan->status_karyawan) }}
                    </td>
                    <td>
                        <strong>Tanggal Cetak:</strong> {{ now()->isoFormat('DD MMMM YYYY') }}
                    </td>
                </tr>
            </table>
        </div>

        <div class="details-section">
            <table style="width: 100%;">
                <tr>
                    <td style="width: 50%; vertical-align: top; padding-right: 10px;">
                        <table>
                            <tr><th colspan="2">PENDAPATAN</th></tr>
                            <tr><td>Gaji Pokok</td><td class="text-right">Rp {{ number_format($gaji->gaji_pokok, 0, ',', '.') }}</td></tr>
                            <tr><td>Tunjangan Jabatan</td><td class="text-right">Rp {{ number_format($gaji->tunjangan_jabatan, 0, ',', '.') }}</td></tr>
                            <tr><td>Tunjangan Transport</td><td class="text-right">Rp {{ number_format($gaji->tunjangan_transport, 0, ',', '.') }}</td></tr>
                            <tr><td>Bonus / Lembur</td><td class="text-right">Rp {{ number_format($gaji->bonus, 0, ',', '.') }}</td></tr>
                            <tr class="total-row"><td>Total Pendapatan (A)</td><td class="text-right">Rp {{ number_format($gaji->total_pendapatan, 0, ',', '.') }}</td></tr>
                        </table>
                    </td>
                    <td style="width: 50%; vertical-align: top; padding-left: 10px;">
                        <table>
                            <tr><th colspan="2">POTONGAN</th></tr>
                            <tr><td>Pajak PPh 21</td><td class="text-right">Rp {{ number_format($gaji->pph21, 0, ',', '.') }}</td></tr>
                            <tr><td>Iuran BPJS</td><td class="text-right">Rp {{ number_format($gaji->bpjs, 0, ',', '.') }}</td></tr>
                            <tr><td>Potongan Lainnya</td><td class="text-right">Rp {{ number_format($gaji->potongan_lain, 0, ',', '.') }}</td></tr>
                            <tr><td>&nbsp;</td><td>&nbsp;</td></tr> {{-- Spacer --}}
                            <tr class="total-row"><td>Total Potongan (B)</td><td class="text-right">Rp {{ number_format($gaji->total_potongan, 0, ',', '.') }}</td></tr>
                        </table>
                    </td>
                </tr>
            </table>
        </div>

        <div class="grand-total">
            <table style="width:100%;">
                <tr>
                    <td style="font-weight: bold; font-size: 14px;">GAJI BERSIH (A - B)</td>
                    <td class="text-right" style="font-weight: bold; font-size: 14px;">Rp {{ number_format($gaji->gaji_bersih, 0, ',', '.') }}</td>
                </tr>
            </table>
        </div>

        <div class="signatures">
            <div class="signature-box" style="float: left;">
                <p>Penerima,</p>
                <div class="signature-line"></div>
                <p>({{ $gaji->karyawan->nama_lengkap }})</p>
            </div>
            <div class="signature-box" style="float: right;">
                <p>Bagian Keuangan,</p>
                <div class="signature-line"></div>
                <p>(.....................)</p>
            </div>
        </div>
    </div>
</body>
</html>
