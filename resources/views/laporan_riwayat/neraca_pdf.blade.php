<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Neraca</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            font-size: 12px;
            line-height: 1.5;
        }
        
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .header h1 {
            margin: 0;
            color: #173720;
            font-size: 24px;
            text-transform: uppercase;
        }
        
        .header h2 {
            margin: 5px 0;
            color: #333;
            font-size: 16px;
            font-weight: normal;
        }
        
        .header p {
            margin: 5px 0;
            color: #666;
            font-size: 12px;
        }
        
        .content {
            margin-top: 20px;
        }
        
        .neraca-container {
            width: 100%;
            border-collapse: collapse;
        }
        
        .neraca-container td {
            vertical-align: top;
            padding: 10px;
        }
        
        .section-title {
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 10px;
            color: #173720;
            border-bottom: 2px solid #173720;
            padding-bottom: 5px;
        }
        
        .sub-title {
            font-weight: bold;
            margin: 10px 0 5px 10px;
            color: #173720;
        }
        
        .item-row {
            display: flex;
            justify-content: space-between;
            margin: 3px 0;
            padding: 2px 0;
        }
        
        .item-name {
            margin-left: 20px;
        }
        
        .item-value {
            text-align: right;
        }
        
        .total-row {
            font-weight: bold;
            border-top: 1px solid #333;
            padding-top: 5px;
            margin-top: 5px;
        }
        
        .grand-total {
            font-weight: bold;
            font-size: 14px;
            background-color: #f0f0f0;
            padding: 8px;
            margin: 10px 0;
        }
        
        .balance-check {
            text-align: center;
            margin-top: 30px;
            padding: 15px;
            border: 2px solid #4CAF50;
            background-color: #e8f5e9;
            color: #2e7d32;
            font-weight: bold;
            border-radius: 5px;
        }
        
        .footer {
            margin-top: 50px;
            font-size: 11px;
            color: #666;
        }
        
        .signature-section {
            margin-top: 60px;
            display: table;
            width: 100%;
        }
        
        .signature-box {
            display: table-cell;
            width: 33.33%;
            text-align: center;
            padding: 0 20px;
        }
        
        .signature-line {
            margin-top: 60px;
            border-top: 1px solid #333;
            padding-top: 5px;
        }
        
        .company-info {
            text-align: center;
            margin-bottom: 10px;
            font-size: 11px;
            color: #666;
        }
        
        /* Untuk memastikan nilai rata kanan */
        table.items-table {
            width: 100%;
            border-collapse: collapse;
        }
        
        table.items-table td {
            padding: 2px 0;
        }
        
        table.items-table td:last-child {
            text-align: right;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>NERACA</h1>
        <h2>TPKU (TIM PENGADAAN KEBUTUHAN UNIT)</h2>
        <p>Per {{ \Carbon\Carbon::parse($tanggal ?? now())->format('d F Y') }}</p>
        <div class="company-info">
            Jl. Raya TPKU No. 123, Surabaya<br>
            Telp: (031) 123-4567 | Email: tpku@example.com
        </div>
    </div>
    
    <div class="content">
        <table class="neraca-container">
            <tr>
                <!-- KOLOM KIRI: ASET -->
                <td style="width: 50%; padding-right: 20px;">
                    <div class="section-title">ASET</div>
                    
                    <!-- Aset Lancar -->
                    <div class="sub-title">Aset Lancar</div>
                    <table class="items-table">
                        <tr>
                            <td class="item-name">Kas</td>
                            <td>Rp {{ number_format($data['kas'] ?? 0, 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <td class="item-name">Bank</td>
                            <td>Rp {{ number_format($data['bank'] ?? 0, 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <td class="item-name">Piutang Usaha</td>
                            <td>Rp {{ number_format($data['piutang'] ?? 0, 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <td class="item-name">Persediaan Barang</td>
                            <td>Rp {{ number_format($data['persediaan'] ?? 0, 0, ',', '.') }}</td>
                        </tr>
                        <tr class="total-row">
                            <td><strong>Total Aset Lancar</strong></td>
                            <td><strong>Rp {{ number_format($data['total_aset_lancar'] ?? 0, 0, ',', '.') }}</strong></td>
                        </tr>
                    </table>
                    
                    <!-- Aset Tetap -->
                    <div class="sub-title" style="margin-top: 20px;">Aset Tetap</div>
                    <table class="items-table">
                        <tr>
                            <td class="item-name">Tanah</td>
                            <td>Rp {{ number_format($data['tanah'] ?? 0, 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <td class="item-name">Bangunan</td>
                            <td>Rp {{ number_format($data['bangunan'] ?? 0, 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <td class="item-name">Kendaraan</td>
                            <td>Rp {{ number_format($data['kendaraan'] ?? 0, 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <td class="item-name">Peralatan</td>
                            <td>Rp {{ number_format($data['peralatan'] ?? 0, 0, ',', '.') }}</td>
                        </tr>
                        @if(($data['akm_penyusutan'] ?? 0) > 0)
                        <tr>
                            <td class="item-name" style="color: #d32f2f;">(Akumulasi Penyusutan)</td>
                            <td style="color: #d32f2f;">(Rp {{ number_format($data['akm_penyusutan'] ?? 0, 0, ',', '.') }})</td>
                        </tr>
                        @endif
                        <tr class="total-row">
                            <td><strong>Total Aset Tetap</strong></td>
                            <td><strong>Rp {{ number_format($data['total_aset_tetap'] ?? 0, 0, ',', '.') }}</strong></td>
                        </tr>
                    </table>
                    
                    <!-- Total Aset -->
                    <div class="grand-total" style="margin-top: 30px;">
                        <table style="width: 100%;">
                            <tr>
                                <td><strong>TOTAL ASET</strong></td>
                                <td style="text-align: right;"><strong>Rp {{ number_format($data['total_aset'] ?? 0, 0, ',', '.') }}</strong></td>
                            </tr>
                        </table>
                    </div>
                </td>
                
                <!-- KOLOM KANAN: KEWAJIBAN & EKUITAS -->
                <td style="width: 50%; padding-left: 20px;">
                    <div class="section-title">KEWAJIBAN & EKUITAS</div>
                    
                    <!-- Kewajiban Lancar -->
                    <div class="sub-title">Kewajiban Lancar</div>
                    <table class="items-table">
                        <tr>
                            <td class="item-name">Utang Usaha</td>
                            <td>Rp {{ number_format($data['utang_usaha'] ?? 0, 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <td class="item-name">Utang Gaji</td>
                            <td>Rp {{ number_format($data['utang_gaji'] ?? 0, 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <td class="item-name">Utang Pajak</td>
                            <td>Rp {{ number_format($data['utang_pajak'] ?? 0, 0, ',', '.') }}</td>
                        </tr>
                        <tr class="total-row">
                            <td><strong>Total Kewajiban Lancar</strong></td>
                            <td><strong>Rp {{ number_format($data['total_kewajiban_lancar'] ?? 0, 0, ',', '.') }}</strong></td>
                        </tr>
                    </table>
                    
                    <!-- Kewajiban Jangka Panjang -->
                    <div class="sub-title" style="margin-top: 20px;">Kewajiban Jangka Panjang</div>
                    <table class="items-table">
                        <tr>
                            <td class="item-name">Utang Bank</td>
                            <td>Rp {{ number_format($data['utang_bank'] ?? 0, 0, ',', '.') }}</td>
                        </tr>
                        <tr class="total-row">
                            <td><strong>Total Kewajiban Jangka Panjang</strong></td>
                            <td><strong>Rp {{ number_format($data['total_kewajiban_panjang'] ?? 0, 0, ',', '.') }}</strong></td>
                        </tr>
                    </table>
                    
                    <!-- Total Kewajiban -->
                    <div style="background-color: #ffebee; padding: 8px; margin: 15px 0;">
                        <table style="width: 100%;">
                            <tr>
                                <td><strong>Total Kewajiban</strong></td>
                                <td style="text-align: right;"><strong>Rp {{ number_format($data['total_kewajiban'] ?? 0, 0, ',', '.') }}</strong></td>
                            </tr>
                        </table>
                    </div>
                    
                    <!-- Ekuitas -->
                    <div class="sub-title" style="margin-top: 20px;">Ekuitas</div>
                    <table class="items-table">
                        <tr>
                            <td class="item-name">Modal Pemilik</td>
                            <td>Rp {{ number_format($data['modal'] ?? 0, 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <td class="item-name">Laba Ditahan</td>
                            <td>Rp {{ number_format($data['laba_ditahan'] ?? 0, 0, ',', '.') }}</td>
                        </tr>
                        <tr class="total-row">
                            <td><strong>Total Ekuitas</strong></td>
                            <td><strong>Rp {{ number_format($data['total_ekuitas'] ?? 0, 0, ',', '.') }}</strong></td>
                        </tr>
                    </table>
                    
                    <!-- Total Kewajiban & Ekuitas -->
                    <div class="grand-total" style="margin-top: 30px;">
                        <table style="width: 100%;">
                            <tr>
                                <td><strong>TOTAL KEWAJIBAN & EKUITAS</strong></td>
                                <td style="text-align: right;"><strong>Rp {{ number_format($data['total_kewajiban_ekuitas'] ?? 0, 0, ',', '.') }}</strong></td>
                            </tr>
                        </table>
                    </div>
                </td>
            </tr>
        </table>
    </div>
    
    <!-- Balance Check -->
    @php
        $isBalanced = ($data['total_aset'] ?? 0) == ($data['total_kewajiban_ekuitas'] ?? 0);
    @endphp
    <div class="balance-check">
        @if($isBalanced)
            ✓ NERACA SEIMBANG (BALANCED)
        @else
            ✗ NERACA TIDAK SEIMBANG!
        @endif
    </div>
    
    <!-- Tanda Tangan -->
    <div class="signature-section">
        <div class="signature-box">
            <p>Mengetahui,</p>
            <p>Kepala TPKU</p>
            <div class="signature-line">
                (....................................)
            </div>
        </div>
        <div class="signature-box">
            <p>Diperiksa oleh,</p>
            <p>Supervisor Keuangan</p>
            <div class="signature-line">
                (....................................)
            </div>
        </div>
        <div class="signature-box">
            <p>Dibuat oleh,</p>
            <p>Admin Keuangan</p>
            <div class="signature-line">
                {{ auth()->user()->name ?? '(.......................................)' }}
            </div>
        </div>
    </div>
    
    <div class="footer">
        <p style="text-align: center;">
            Dicetak pada: {{ now()->format('d F Y H:i:s') }}<br>
            © {{ date('Y') }} TPKU - Sistem Informasi Keuangan
        </p>
    </div>
</body>
</html>