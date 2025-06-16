<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Laba Rugi</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            font-size: 12px;
        }
        
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .header h1 {
            margin: 0;
            color: #173720;
            font-size: 24px;
        }
        
        .header p {
            margin: 5px 0;
            color: #666;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        
        th, td {
            padding: 8px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        
        th {
            background-color: #f5f5f5;
            font-weight: bold;
        }
        
        .section-title {
            font-size: 16px;
            font-weight: bold;
            margin: 20px 0 10px 0;
            color: #173720;
        }
        
        .pendapatan th {
            background-color: #dff2e1;
            color: #173720;
        }
        
        .pengeluaran th {
            background-color: #fcefe9;
            color: #a94442;
        }
        
        .total-row {
            font-weight: bold;
            border-top: 2px solid #333;
        }
        
        .text-right {
            text-align: right;
        }
        
        .summary-box {
            margin-top: 30px;
            padding: 15px;
            border-radius: 5px;
            font-size: 16px;
            font-weight: bold;
            text-align: center;
        }
        
        .laba {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .rugi {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        .footer {
            margin-top: 50px;
            text-align: right;
            font-size: 11px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>LAPORAN LABA RUGI</h1>
        <p>Periode: {{ $periode }}</p>
        <p>Tanggal Cetak: {{ now()->format('d F Y') }}</p>
    </div>
    
    <!-- PENDAPATAN -->
    <div class="section-title">Pendapatan</div>
    <table class="pendapatan">
        <thead>
            <tr>
                <th width="10%">No</th>
                <th width="25%">Tanggal</th>
                <th width="45%">Keterangan</th>
                <th width="20%" class="text-right">Jumlah</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pendapatan as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y') }}</td>
                    <td>{{ $item->keterangan ?? '-' }}</td>
                    <td class="text-right">Rp {{ number_format($item->jumlah, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" style="text-align: center; color: #999;">Belum ada data pendapatan</td>
                </tr>
            @endforelse
        </tbody>
        @if(count($pendapatan) > 0)
            <tfoot>
                <tr class="total-row">
                    <td colspan="3">Total Pendapatan</td>
                    <td class="text-right">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</td>
                </tr>
            </tfoot>
        @endif
    </table>
    
    <!-- PENGELUARAN -->
    <div class="section-title" style="color: #a94442;">Pengeluaran</div>
    <table class="pengeluaran">
        <thead>
            <tr>
                <th width="10%">No</th>
                <th width="25%">Tanggal</th>
                <th width="45%">Keterangan</th>
                <th width="20%" class="text-right">Jumlah</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pengeluaran as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y') }}</td>
                    <td>{{ $item->keterangan ?? '-' }}</td>
                    <td class="text-right">Rp {{ number_format($item->jumlah, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" style="text-align: center; color: #999;">Belum ada data pengeluaran</td>
                </tr>
            @endforelse
        </tbody>
        @if(count($pengeluaran) > 0)
            <tfoot>
                <tr class="total-row">
                    <td colspan="3">Total Pengeluaran</td>
                    <td class="text-right">Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</td>
                </tr>
            </tfoot>
        @endif
    </table>
    
    <!-- LABA/RUGI BERSIH -->
    @php
        $selisih = $totalPendapatan - $totalPengeluaran;
        $isLaba = $selisih >= 0;
    @endphp
    
    <div class="summary-box {{ $isLaba ? 'laba' : 'rugi' }}">
        {{ $isLaba ? 'LABA BERSIH' : 'RUGI BERSIH' }}: 
        Rp {{ number_format(abs($selisih), 0, ',', '.') }}
    </div>
    
    <div class="footer">
        <p>Dicetak oleh: {{ auth()->user()->name ?? 'Administrator' }}</p>
        <p>© {{ date('Y') }} - Sistem Informasi Keuangan</p>
    </div>
</body>
</html>