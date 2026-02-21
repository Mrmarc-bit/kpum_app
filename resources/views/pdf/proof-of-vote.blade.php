<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Bukti Pilihan - {{ $voteData['name'] }}</title>
    <style>
        body {
            font-family: 'Times New Roman', Times, serif;
            line-height: 1.6;
            margin: 0;
            padding: 20px;
        }
        .header {
            text-align: center;
            border-bottom: 3px double #000;
            padding-bottom: 10px;
            margin-bottom: 20px;
            position: relative;
        }
        .header img {
            position: absolute;
            left: 0;
            top: 0;
            width: 80px;
            height: auto;
        }
        .header h1 {
            margin: 0;
            font-size: 18pt;
            text-transform: uppercase;
        }
        .header h2 {
            margin: 0;
            font-size: 14pt;
            font-weight: normal;
        }
        .header p {
            margin: 0;
            font-size: 10pt;
        }
        .title {
            text-align: center;
            font-weight: bold;
            font-size: 14pt;
            margin-bottom: 20px;
            text-decoration: underline;
        }
        .content {
            margin-bottom: 30px;
        }
        .table-info {
            width: 100%;
            margin-bottom: 20px;
            border-collapse: collapse;
        }
        .table-info td {
            padding: 5px;
            vertical-align: top;
        }
        .label {
            width: 150px;
            font-weight: bold;
        }
        .colon {
            width: 20px;
            text-align: center;
        }
        .signature {
            float: right;
            width: 250px;
            text-align: center;
            margin-top: 50px;
        }
        .signature img {
            height: 80px;
            margin-bottom: 10px;
        }
        .footer {
            position: fixed;
            bottom: 0px;
            left: 0px;
            right: 0px;
            text-align: center;
            font-size: 9pt;
            color: #555;
            border-top: 1px solid #ddd;
            padding-top: 5px;
        }
        .watermark {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            opacity: 0.08;
            font-size: 60pt;
            font-weight: bold;
            color: #000;
            z-index: -1000;
            white-space: nowrap;
            text-align: center;
        }
    </style>
</head>
<body>

    <div class="watermark">{{ $letterSettings['watermark_text'] }}</div>

    <div style="border-bottom: 3px double #000; margin-bottom: 20px; padding-bottom: 10px;">
        <table width="100%">
            <tr>
                <td width="15%" align="center" style="vertical-align: middle;">
                    @if(!empty($letterSettings['logo_base64']))
                        <img src="{{ $letterSettings['logo_base64'] }}" alt="Logo" style="height: 80px; width: auto;">
                    @endif
                </td>
                <td width="85%" align="center" style="vertical-align: middle;">
                    <h1 style="margin: 0; font-size: 18pt; text-transform: uppercase;">{{ $letterSettings['header'] }}</h1>
                    <h2 style="margin: 0; font-size: 14pt; font-weight: normal;">{{ $letterSettings['sub_header'] }}</h2>
                </td>
            </tr>
        </table>
    </div>

    <div class="title">SURAT BUKTI PILIHAN</div>

    <div class="content">
        <p>{{ $letterSettings['body_top'] }}</p>

        <table class="table-info">
            <tr>
                <td class="label">Nama</td>
                <td class="colon">:</td>
                <td>{{ $voteData['name'] }}</td>
            </tr>
            <tr>
                <td class="label">NIM</td>
                <td class="colon">:</td>
                <td>{{ $voteData['nim'] }}</td>
            </tr>
            <tr>
                <td class="label">Fakultas</td>
                <td class="colon">:</td>
                <td>{{ $voteData['faculty'] }}</td>
            </tr>
            <tr>
                <td class="label">Program Studi</td>
                <td class="colon">:</td>
                <td>{{ $voteData['study_program'] }}</td>
            </tr>
            <tr>
                <td class="label">Waktu Memilih</td>
                <td class="colon">:</td>
                <td>{{ $voteData['timestamp'] }}</td>
            </tr>
        </table>

        <p><strong>Rincian Pilihan:</strong></p>
        <table class="table-info" style="border: 1px solid #000; width: 100%;">
            <tr style="background-color: #f0f0f0;">
                <th style="border: 1px solid #000; padding: 5px; text-align: left;">Kategori</th>
                <th style="border: 1px solid #000; padding: 5px; text-align: left;">Status / Pilihan</th>
            </tr>
            <tr>
                <td style="border: 1px solid #000; padding: 5px;">Presiden Mahasiswa</td>
                <td style="border: 1px solid #000; padding: 5px;">
                    @if($voteData['presma_voted'])
                        <span style="color: green;">SUDAH MEMILIH</span>
                    @else
                        <span style="color: red;">BELUM MEMILIH</span>
                    @endif
                </td>
            </tr>
            <tr>
                <td style="border: 1px solid #000; padding: 5px;">Dewan Perwakilan Mahasiswa</td>
                <td style="border: 1px solid #000; padding: 5px;">
                    @if($voteData['dpm_voted'])
                        <span style="color: green;">SUDAH MEMILIH</span>
                    @else
                        <span style="color: red;">BELUM MEMILIH</span>
                    @endif
                </td>
            </tr>
        </table>

        <p>{{ $letterSettings['body_bottom'] }}</p>
    </div>

    <div class="signature">
        <p style="margin: 0; margin-bottom: 5px;">{{ $letterSettings['signature_place_date'] }}</p>
        <p style="margin: 0; margin-bottom: 10px; font-weight: bold;">{{ $letterSettings['signature_title'] }}</p>
        
        <div style="position: relative; width: 100%; margin: 0 auto; min-height: 80px;">
             @if(!empty($letterSettings['stamp_base64']))
                <img src="{{ $letterSettings['stamp_base64'] }}" style="position: absolute; left: 10px; top: -10px; width: 100px; height: auto; z-index: 0; opacity: 0.8;">
            @endif
            
            @if(!empty($letterSettings['signature_base64']))
                <img src="{{ $letterSettings['signature_base64'] }}" alt="Signature" style="position: relative; z-index: 10; height: 80px; width: auto;">
            @else
                <br><br><br><br>
            @endif
        </div>
        
        <p style="margin: 0; font-weight: bold;">{{ $letterSettings['signature_name'] }}</p>
        <p style="margin: 0;">NIM : {{ $letterSettings['signature_nim'] }}</p>
    </div>

    <div class="footer">
        {{ $letterSettings['footer'] }} | Token: {{ md5($voteData['nim'] . $voteData['timestamp']) }}
    </div>

</body>
</html>
