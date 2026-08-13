<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Pengajuan Penawaran Baru</title>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; background-color: #f4f6f8; margin: 0; padding: 20px; color: #333; }
        .container { max-width: 600px; margin: 0 auto; background: #ffffff; border-radius: 8px; overflow: hidden; border: 1px solid #e1e4e8; }
        .header { background: #0c0d0e; padding: 24px; text-align: center; color: #ffffff; }
        .header h1 { margin: 0; font-size: 20px; color: #ff4950; text-transform: uppercase; letter-spacing: 1px; }
        .content { padding: 24px; }
        .badge { display: inline-block; padding: 4px 10px; background: #ff4950; color: #fff; font-size: 12px; font-weight: bold; border-radius: 4px; }
        .info-table { width: 100%; border-collapse: collapse; margin-top: 16px; margin-bottom: 24px; }
        .info-table td { padding: 8px 12px; border-bottom: 1px solid #eee; font-size: 14px; }
        .info-table td.label { font-weight: bold; color: #666; width: 35%; }
        .items-table { width: 100%; border-collapse: collapse; margin-top: 16px; }
        .items-table th { background: #f8f9fa; text-align: left; padding: 10px; font-size: 13px; border-bottom: 2px solid #dee2e6; }
        .items-table td { padding: 10px; font-size: 13px; border-bottom: 1px solid #eee; }
        .footer { background: #f8f9fa; padding: 16px; text-align: center; font-size: 12px; color: #888; border-top: 1px solid #eee; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>PT. PROLABIOS MITRA ANALITIKA</h1>
            <p style="margin: 6px 0 0; font-size: 13px; opacity: 0.8;">Notifikasi Permintaan Penawaran Baru</p>
        </div>
        <div class="content">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <h2 style="margin: 0; font-size: 16px; color: #111;">RFQ: {{ $rfq->rfq_number }}</h2>
                <span class="badge">Baru</span>
            </div>

            <p style="font-size: 14px; margin-top: 16px;">Halo Tim Sales, terdapat pengajuan penawaran baru dari customer:</p>

            <table class="info-table">
                <tr><td class="label">Nama Pemohon</td><td><strong>{{ $rfq->name }}</strong></td></tr>
                <tr><td class="label">Nama Instansi</td><td>{{ $rfq->company_name }}</td></tr>
                <tr><td class="label">Email</td><td><a href="mailto:{{ $rfq->email }}">{{ $rfq->email }}</a></td></tr>
                <tr><td class="label">WhatsApp</td><td><a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $rfq->phone_wa) }}" target="_blank">{{ $rfq->phone_wa }}</a></td></tr>
                <tr><td class="label">Catatan</td><td>{{ $rfq->notes ?: '-' }}</td></tr>
            </table>

            <h3 style="font-size: 15px; margin-bottom: 8px; color: #111;">Daftar Produk yang Diajukan:</h3>
            <table class="items-table">
                <thead>
                    <tr>
                        <th>Katalog</th>
                        <th>Produk</th>
                        <th>Harga Katalog</th>
                        <th>Jumlah</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($rfq->items as $item)
                        <tr>
                            <td>{{ $item->catalog_no ?: '-' }}</td>
                            <td><strong>{{ $item->product_title }}</strong></td>
                            <td>Rp {{ number_format($item->original_price, 0, ',', '.') }}</td>
                            <td>{{ $item->quantity }} Unit</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div style="margin-top: 24px; padding: 12px; background: #eef2f5; border-radius: 6px; font-size: 13px; color: #444;">
                Silakan hubungi customer melalui Email atau WhatsApp untuk follow-up penawaran resmi.
            </div>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} PT. Prolabios Mitra Analitika. Inquiry Notification System.
        </div>
    </div>
</body>
</html>
