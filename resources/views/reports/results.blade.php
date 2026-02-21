<!DOCTYPE html>
<html>
<head>
    <title>Laporan Hasil Pemilihan</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; color: #333; }
        
        /* KOP SURAT */
        .kop-table { width: 100%; border-bottom: 3px double #000; margin-bottom: 20px; padding-bottom: 10px; }
        .kop-logo { width: 80px; text-align: left; vertical-align: middle; }
        .kop-text { text-align: center; vertical-align: middle; }
        .kop-header { font-size: 14pt; font-weight: bold; text-transform: uppercase; margin: 0; }
        .kop-subheader { font-size: 12pt; font-weight: bold; text-transform: uppercase; margin: 5px 0; }
        .kop-address { font-size: 10pt; font-style: italic; }

        .section-title { font-size: 14px; font-weight: bold; margin-top: 25px; margin-bottom: 10px; border-left: 4px solid #333; padding-left: 10px; background: #f4f4f4; padding: 5px 10px; }
        table.data { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
        table.data th, table.data td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        table.data th { background-color: #f8f8f8; font-weight: bold; }
        .footer { position: fixed; bottom: 0; width: 100%; text-align: center; font-size: 10px; color: #777; border-top: 1px solid #ddd; padding-top: 10px; }
        
        /* SIGNATURE */
        .signature-container { margin-top: 50px; page-break-inside: avoid; }
        .signature-box { float: right; width: 250px; text-align: center; }
        .sig-image { height: 80px; margin: 10px auto; }
    </style>
</head>
<body>
    <!-- KOP SURAT -->
    <table class="kop-table">
        <tr>
            <td class="kop-logo">
                @if($letterSettings['logo_base64'])
                    <img src="{{ $letterSettings['logo_base64'] }}" style="width: 80px; height: auto;">
                @endif
            </td>
            <td class="kop-text">
                <h1 class="kop-header">{{ $letterSettings['header'] }}</h1>
                <h2 class="kop-subheader">{{ $letterSettings['sub_header'] }}</h2>
                <div class="kop-address">{{ $letterSettings['address'] }}</div>
            </td>
            <td class="kop-logo" style="width: 10px;"></td> <!-- Spacer balance -->
        </tr>
    </table>

    <div style="text-align: center; margin-bottom: 20px;">
        <h3 style="text-transform: uppercase; margin: 0; text-decoration: underline;">LAPORAN HASIL REKAPITULASI SUARA</h3>
        <p style="margin: 5px 0;">Tanggal Cetak: {{ $letterSettings['date'] }}</p>
    </div>

    <div class="stats-box">
        <table style="width: 100%; border: 1px solid #ddd; padding: 10px; background: #fff;">
            <tr>
                <td style="border: none; width: 33%; text-align: center;"><strong>Total DPT</strong><br><span style="font-size: 16px;">{{ $stats['total_dpt'] }}</span></td>
                <td style="border: none; width: 33%; text-align: center;"><strong>Total Suara Masuk</strong><br><span style="font-size: 16px;">{{ $stats['total_presma_votes'] }}</span></td>
                <td style="border: none; width: 33%; text-align: center;"><strong>Partisipasi</strong><br><span style="font-size: 16px;">{{ number_format($stats['presma_percentage'], 1) }}%</span></td>
            </tr>
        </table>
    </div>

    <div class="section-title">I. Hasil Pemilihan Presiden Mahasiswa</div>
    <table class="data">
        <thead>
            <tr>
                <th style="width: 50px; text-align: center;">No</th>
                <th>Kandidat</th>
                <th style="width: 100px; text-align: center;">Total Suara</th>
                <th style="width: 100px; text-align: center;">Persentase</th>
            </tr>
        </thead>
        <tbody>
            @foreach($presmaCandidates as $candidate)
            <tr>
                <td style="text-align: center;">{{ $candidate->no_urut }}</td>
                <td>
                    <strong>{{ $candidate->nama_ketua }}</strong>
                    @if($candidate->nama_wakil) & {{ $candidate->nama_wakil }} @endif
                </td>
                <td style="text-align: center; font-weight: bold;">{{ $candidate->votes_count }}</td>
                <td style="text-align: center;">
                   {{ $stats['total_presma_votes'] > 0 ? number_format(($candidate->votes_count / $stats['total_presma_votes']) * 100, 1) : 0 }}%
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="section-title">II. Hasil Pemilihan DPM</div>
    <table class="data">
        <thead>
            <tr>
                <th style="width: 50px; text-align: center;">Urut</th>
                <th>Calon Legislatif</th>
                <th>Fakultas / Prodi</th>
                <th style="width: 100px; text-align: center;">Total Suara</th>
            </tr>
        </thead>
        <tbody>
            @foreach($dpmCandidates as $candidate)
            <tr>
                <td style="text-align: center;">{{ $candidate->nomor_urut ?? $candidate->urutan_tampil }}</td>
                <td>{{ $candidate->nama }}</td>
                <td>{{ $candidate->fakultas }}<br><small>{{ $candidate->prodi }}</small></td>
                <td style="text-align: center; font-weight: bold;">{{ $candidate->dpm_votes_count }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- SIGNATURE -->
    <div class="signature-container">
        <div class="signature-box">
            <p>Disahkan oleh,<br>{{ $letterSettings['signature_name'] }}</p>
            @if($letterSettings['signature_base64'])
                <img src="{{ $letterSettings['signature_base64'] }}" class="sig-image">
            @else
                <br><br><br><br>
            @endif
            <p style="border-top: 1px solid #000; width: 80%; margin: 0 auto; padding-top: 5px;">
                (Tanda Tangan Digital Sah)
            </p>
        </div>
    </div>

    <div class="footer">
        {{ $letterSettings['footer'] }}
    </div>
</body>
</html>
