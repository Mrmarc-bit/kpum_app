<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }
        .header h1 {
            margin: 0;
            font-size: 18px;
            text-transform: uppercase;
        }
        .header p {
            margin: 5px 0 0;
            color: #666;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f4f4f4;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 11px;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .footer {
            margin-top: 30px;
            text-align: right;
            font-size: 10px;
            color: #888;
        }
        /* Hide print button when printing */
        @media print {
            .no-print {
                display: none;
            }
        }
        .print-btn {
            background: #2563eb;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body onload="window.print()">

    <button onclick="window.print()" class="print-btn no-print">🖨️ Cetak / Simpan PDF</button>

    <div class="header">
        <h1>Laporan Audit Log Aktivitas</h1>
        <p>KPUM - Komisi Pemilihan Umum Mahasiswa</p>
        <p>Dicetak pada: {{ now()->format('d M Y H:i:s') }}</p>
        <p>{{ $title }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th width="15%">Waktu</th>
                <th width="20%">Pengguna</th>
                <th width="15%">Aksi</th>
                <th width="15%">IP Address</th>
                <th width="35%">Detail</th>
            </tr>
        </thead>
        <tbody>
            @forelse($logs as $log)
                <tr>
                    <td>{{ $log->created_at->format('d M Y H:i:s') }}</td>
                    <td>{{ $log->user_name ?? 'System/Guest' }}</td>
                    <td>{{ $log->action }}</td>
                    <td>{{ $log->ip_address }}</td>
                    <td>{{ $log->details }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="text-align: center; padding: 20px;">Tidak ada data log.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Dicetak oleh: {{ auth()->check() ? auth()->user()->name : 'System' }}
    </div>

</body>
</html>
