<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Surat Pemberitahuan - {{ $data['name'] }} ({{ $data['nim'] }})</title>
    <style>
        @page {
            margin: 1cm;
        }
        body {
            font-family: 'Times New Roman', Times, serif;
            line-height: 1.15;
            font-size: 10pt;
        }
        .header {
            text-align: center;
            border-bottom: 2px double #000;
            padding-bottom: 2px;
            margin-bottom: 5px;
        }
        .title {
            text-align: center;
            font-weight: bold;
            font-size: 12pt;
            margin-bottom: 5px;
            text-decoration: underline;
            margin-top: 5px;
        }
        .content {
            margin-bottom: 5px;
            text-align: justify;
        }
        .table-info {
            width: 100%;
            margin-bottom: 5px;
            border-collapse: collapse;
            margin-left: 10px;
        }
        .table-info td {
            padding: 0px 4px;
            vertical-align: top;
            font-size: 10pt;
        }
        .label {
            width: 130px;
            font-weight: bold;
        }
        .colon {
            width: 15px;
            text-align: center;
        }
        .signature {
            float: right;
            width: 220px;
            text-align: center;
            margin-top: 5px;
            font-size: 10pt;
            line-height: 1.2;
        }
        .signature img {
            height: 50px;
            object-fit: contain;
        }
        .footer {
            position: fixed;
            bottom: -20px; 
            left: 0px;
            right: 0px;
            font-size: 8pt;
            color: #555;
            text-align: center; 
        }
        .watermark {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            opacity: 0.08;
            font-size: 40pt;
            font-weight: bold;
            color: #000;
            z-index: -1000;
            white-space: nowrap;
            text-align: center;
        }
    </style>
</head>
<body>


    <div style="border-bottom: 3px double #000; margin-bottom: 15px; padding-bottom: 5px;">
        <table width="100%">
            <tr>
                <td width="15%" align="left" style="vertical-align: middle;">
                    @if(!empty($data['logo_base64']))
                        <img src="{{ $data['logo_base64'] }}" alt="Logo" style="height: 60px; width: auto;">
                    @endif
                </td>
                <td width="70%" align="center" style="vertical-align: middle;">
                    <h1 style="margin: 0; font-size: 14pt; text-transform: uppercase;">{{ $data['header'] }}</h1>
                    <h2 style="margin: 0; font-size: 11pt; font-weight: normal;">{{ $data['sub_header'] }}</h2>
                </td>
                <td width="15%" align="right" style="vertical-align: top;">
                    <div style="border: 1px solid #000; padding: 5px; font-weight: bold; font-size: 9pt; display: inline-block;">
                        {{ $data['dpt_code'] ?? 'DPT-CODE' }}
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <div class="title">{{ $data['title'] }}</div>

    <div class="content">
        <p>{{ $data['body_top'] }}</p>

        <table class="table-info">
            <tr>
                <td class="label">Nama</td>
                <td class="colon">:</td>
                <td>{{ $data['name'] }}</td>
            </tr>
            <tr>
                <td class="label">NIM</td>
                <td class="colon">:</td>
                <td>{{ $data['nim'] }}</td>
            </tr>
            <tr>
                <td class="label">Fakultas</td>
                <td class="colon">:</td>
                <td>{{ $data['faculty'] }}</td>
            </tr>
            <tr>
                <td class="label">Program Studi</td>
                <td class="colon">:</td>
                <td>{{ $data['study_program'] }}</td>
            </tr>
            <tr>
                <td class="label">Hari, Tanggal</td>
                <td class="colon">:</td>
                <td>{{ $data['date'] }}</td>
            </tr>
            <tr>
                <td class="label">Waktu</td>
                <td class="colon">:</td>
                <td>{{ $data['time'] }}</td>
            </tr>
            <tr>
                <td class="label">Tempat/Link</td>
                <td class="colon">:</td>
                <td>{{ $data['location'] }}</td>
            </tr>
        </table>

        <p>{{ $data['body_bottom'] }}</p>
    </div>

    <div style="margin-top: 5px;">
        <table width="100%">
            <tr>
                <!-- QR Code Section (Left) -->
                <td width="50%" align="left" style="vertical-align: top; padding-top: 20px; padding-left: 10px;">
                    @if(!empty($data['qr_image']))
                        <img src="{{ $data['qr_image'] }}" alt="QR Code" style="width: 120px; height: 120px; border: 1px solid #000; display: block;">
                    @else
                        <div style="border: 1px solid #000; width: 120px; height: 120px; display: block; background-color: #f5f5f5;"></div>
                    @endif
                    <p style="margin: 8px 0 0 0; font-size: 8pt; color: #333; font-weight: normal;">Scan untuk validasi</p>
                </td>
                
                <!-- Signature Section (Right) -->
                <td width="50%" align="center" style="vertical-align: top;">
                    <div class="signature" style="float: none; width: 100%; text-align: center;">
                        <p style="margin: 0; margin-bottom: 5px;">{{ $data['signature_place_date'] }}</p>
                        <p style="margin: 0; margin-bottom: 2px; font-weight: bold;">{{ $data['signature_title'] }}</p>
                        
                        <div style="position: relative; width: 100px; margin: 0 auto; min-height: 50px;">
                            @if(!empty($data['stamp_base64']))
                                <img src="{{ $data['stamp_base64'] }}" style="position: absolute; left: -30px; top: -10px; width: 80px; height: auto; z-index: 0; opacity: 0.8;">
                            @endif
                            
                            @if(!empty($data['signature_base64']))
                                <img src="{{ $data['signature_base64'] }}" alt="Signature" style="position: relative; z-index: 10; height: 50px; width: auto; object-fit: contain;">
                            @else
                                <br><br><br>
                            @endif
                        </div>
                        
                        <p style="margin: 0; font-weight: bold; margin-top: 2px;">{{ $data['signature_name'] }}</p>
                        <p style="margin: 0;">NIM : {{ $data['signature_nim'] }}</p>
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <div class="footer">
        Dicetak otomatis oleh sistem pada {{ date('d F Y H:i') }}
    </div>

</body>
</html>
