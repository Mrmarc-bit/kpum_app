<!DOCTYPE html>
<html>
<head>
    <title>Berita Acara Pemilihan</title>
    <style>
        body { font-family: 'Times New Roman', serif; line-height: 1.6; font-size: 12pt; color: #000; margin: 20px; }
        
        /* KOP SURAT */
        .kop-table { width: 100%; border-bottom: 3px double #000; margin-bottom: 20px; padding-bottom: 10px; }
        .kop-logo { width: 80px; text-align: left; vertical-align: middle; }
        .kop-text { text-align: center; vertical-align: middle; }
        .kop-header { font-size: 14pt; font-weight: bold; text-transform: uppercase; margin: 0; }
        .kop-subheader { font-size: 12pt; font-weight: bold; text-transform: uppercase; margin: 5px 0; }
        .kop-address { font-size: 10pt; font-style: italic; }

        .title { font-size: 14pt; margin-bottom: 5px; text-align: center; font-weight: bold; text-transform: uppercase; }
        .subtitle { font-size: 12pt; text-align: center; font-weight: bold; text-transform: uppercase; margin-bottom: 30px; }
        .content { text-align: justify; margin-bottom: 20px; }
        .data-table { width: 100%; margin: 20px 0; border-collapse: collapse; }
        .data-table td { padding: 5px; vertical-align: top; }
        
        /* SIGNATURE */
        .signatures { width: 100%; margin-top: 50px; }
        .signatures td { width: 50%; text-align: center; vertical-align: top; }
        .sig-image { height: 80px; margin: 5px auto; display: block; }
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
            <td class="kop-logo" style="width: 10px;"></td>
        </tr>
    </table>

    <div class="title">BERITA ACARA HASIL PEMILIHAN UMUM RAYA</div>
    <div class="subtitle">TAHUN {{ $data['year'] }}</div>

    <div class="content">
        <p>
            Pada hari ini <strong>{{ $data['day'] }}</strong>, tanggal <strong>{{ $data['date'] }}</strong>, bulan <strong>{{ $data['month'] }}</strong>, tahun <strong>{{ $data['year'] }}</strong>, bertempat di {{ $letterSettings['sub_header'] }}, telah dilaksanakan Pemilihan Umum Raya Mahasiswa secara elektronik (e-voting).
        </p>

        <p>Berdasarkan hasil rekapitulasi sistem elektronik yang telah diverifikasi, ditetapkan data sebagai berikut:</p>

        <table class="data-table">
            <tr>
                <td style="width: 250px;">Total Daftar Pemilih Tetap</td>
                <td>: <strong>{{ $data['total_dpt'] }}</strong> Mahasiswa</td>
            </tr>
            <tr>
                <td>Total Suara Masuk (Sah)</td>
                <td>: <strong>{{ $data['total_votes'] }}</strong> Suara</td>
            </tr>
            <tr>
                <td>Total Tidak Memilih (Golput)</td>
                <td>: <strong>{{ $data['total_abstain'] }}</strong> Mahasiswa</td>
            </tr>
        </table>

        <p>Dari hasil penghitungan suara untuk kandidat Presiden Mahasiswa & Wakil Presiden Mahasiswa, diperoleh hasil tertinggi oleh pasangan:</p>

        @if($data['presma_winner'])
        <div style="border: 2px solid #000; padding: 15px; margin: 20px 0; text-align: center; background: #fdfdfd;">
            <h3 style="margin: 0; text-transform: uppercase; font-size: 14pt;">{{ $data['presma_winner']->nama_ketua }} & {{ $data['presma_winner']->nama_wakil }}</h3>
            <p style="margin: 5px 0; font-size: 12pt;">Nomor Urut: {{ $data['presma_winner']->no_urut }}</p>
            <p style="margin: 10px 0; font-weight: bold; font-size: 14pt;">Dengan Perolehan: {{ $data['presma_winner']->votes_count }} Suara</p>
        </div>
        @else
        <p style="color: red; text-align: center;">[Belum ada data suara masuk]</p>
        @endif

        <p>
            Demikian berita acara ini dibuat dengan sesungguhnya untuk dapat dipergunakan sebagaimana mestinya. Hasil pemilihan ini bersifat mutlak dan sah menurut peraturan yang berlaku.
        </p>
    </div>

    <table class="signatures">
        <tr>
            <td>
                Saksi 1<br><br><br><br><br><br>
                ( .................................... )
            </td>
            <td>
                Ketua KPU Mahasiswa<br>
                @if($letterSettings['signature_base64'])
                    <img src="{{ $letterSettings['signature_base64'] }}" class="sig-image">
                @else
                    <br><br><br><br>
                @endif
                ( <strong>{{ $letterSettings['signature_name'] }}</strong> )
            </td>
        </tr>
    </table>
</body>
</html>
