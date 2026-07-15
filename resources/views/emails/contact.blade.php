<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Pesan Baru Hubungi Kami</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f5f7;
            color: #333333;
            margin: 0;
            padding: 30px;
        }
        .container {
            max-width: 600px;
            background-color: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            margin: 0 auto;
        }
        .header {
            background-color: #2b2d42;
            color: #ffffff;
            padding: 25px;
            text-align: center;
        }
        .header h2 {
            margin: 0;
            font-size: 20px;
            font-weight: 700;
            letter-spacing: 0.5px;
        }
        .content {
            padding: 30px;
        }
        .field-group {
            margin-bottom: 20px;
            border-bottom: 1px solid #edf2f7;
            padding-bottom: 12px;
        }
        .field-label {
            font-size: 12px;
            font-weight: 700;
            color: #e63946;
            text-transform: uppercase;
            margin-bottom: 5px;
            letter-spacing: 0.5px;
        }
        .field-value {
            font-size: 15px;
            color: #2d3748;
            line-height: 1.5;
        }
        .footer {
            background-color: #f7fafc;
            padding: 15px;
            text-align: center;
            font-size: 12px;
            color: #718096;
            border-top: 1px solid #edf2f7;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Pesan Kontak Baru (Website Prolabios)</h2>
        </div>
        <div class="content">
            <div class="field-group">
                <div class="field-label">Nama Pengirim</div>
                <div class="field-value">{{ $data['nama'] }}</div>
            </div>
            
            <div class="field-group">
                <div class="field-label">Email Pengirim</div>
                <div class="field-value"><a href="mailto:{{ $data['email'] }}">{{ $data['email'] }}</a></div>
            </div>
            
            <div class="field-group">
                <div class="field-label">Nomor Telepon</div>
                <div class="field-value">{{ $data['telepon'] ?: '-' }}</div>
            </div>
            
            <div class="field-group">
                <div class="field-label">Perusahaan / Instansi</div>
                <div class="field-value">{{ $data['perusahaan'] ?: '-' }}</div>
            </div>
            
            <div class="field-group">
                <div class="field-label">Kategori Subjek</div>
                <div class="field-value"><strong>{{ $data['subjek_label'] }}</strong></div>
            </div>
            
            <div class="field-group" style="border-bottom: none; padding-bottom: 0;">
                <div class="field-label">Isi Pesan</div>
                <div class="field-value" style="white-space: pre-line;">{{ $data['pesan'] }}</div>
            </div>
        </div>
        <div class="footer">
            Email ini dikirim otomatis oleh sistem formulir kontak PT Prolabios Mitra Analitika.
        </div>
    </div>
</body>
</html>
