<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Surat Penawaran Harga Resmi - PT. Prolabios Mitra Analitika</title>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; background-color: #f4f6f8; margin: 0; padding: 20px; color: #333; }
        .container { max-width: 640px; margin: 0 auto; background: #ffffff; border-radius: 8px; overflow: hidden; border: 1px solid #e1e4e8; }
        .header { background: #0c0d0e; padding: 24px; text-align: center; color: #ffffff; }
        .header h1 { margin: 0; font-size: 20px; color: #ff4950; text-transform: uppercase; letter-spacing: 1px; }
        .content { padding: 28px; }
        .info-box { background: #f8f9fa; border-left: 4px solid #ff4950; padding: 14px; margin-bottom: 20px; font-size: 14px; border-radius: 0 6px 6px 0; }
        .items-table { width: 100%; border-collapse: collapse; margin-top: 16px; margin-bottom: 20px; }
        .items-table th { background: #0c0d0e; color: #ffffff; text-align: left; padding: 10px; font-size: 13px; }
        .items-table td { padding: 10px; font-size: 13px; border-bottom: 1px solid #eee; }
        .total-row td { font-weight: bold; background: #f8f9fa; font-size: 14px; }
        .footer { background: #f8f9fa; padding: 16px; text-align: center; font-size: 12px; color: #888; border-top: 1px solid #eee; }
        .btn { display: inline-block; padding: 12px 24px; background: #28a745; color: #ffffff; text-decoration: none; border-radius: 6px; font-weight: bold; font-size: 14px; margin-top: 16px; }
        .btn-pdf { display: inline-block; padding: 12px 24px; background: #ff4950; color: #ffffff; text-decoration: none; border-radius: 6px; font-weight: bold; font-size: 14px; margin-top: 16px; margin-left: 10px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>PT. PROLABIOS MITRA ANALITIKA</h1>
            <p style="margin: 6px 0 0; font-size: 13px; opacity: 0.8;">Surat Penawaran Harga Resmi (Official Quotation)</p>
        </div>
        <div class="content">
            <p style="font-size: 14px; margin-top: 0;">Kepada Yth.<br>
            <strong>{{ $rfq->pic_name }}</strong> ({{ $rfq->pic_position ?: 'Bagian Procurement' }})<br>
            <strong>{{ $rfq->company_name }}</strong></p>

            <p style="font-size: 14px;">Terima kasih atas kepercayaan Anda berkonsultasi dengan PT. Prolabios Mitra Analitika. Berikut adalah rincian penawaran harga resmi untuk pengajuan <strong>{{ $rfq->rfq_number }}</strong>:</p>

            @if($rfq->admin_response_notes)
                <div class="info-box">
                    <strong>Catatan Penawaran dari Sales:</strong><br>
                    {{ $rfq->admin_response_notes }}
                </div>
            @endif

            <table class="items-table">
                <thead>
                    <tr>
                        <th>No. Katalog</th>
                        <th>Item Produk</th>
                        <th>Qty</th>
                        <th>Harga Satuan (Rp)</th>
                        <th>Subtotal (Rp)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($rfq->items as $item)
                        <tr>
                            <td>{{ $item->catalog_no ?: '-' }}</td>
                            <td><strong>{{ $item->product_title }}</strong></td>
                            <td style="text-align: center;">{{ $item->quantity }}</td>
                            <td style="text-align: right;">{{ number_format($item->offered_price, 0, ',', '.') }}</td>
                            <td style="text-align: right;">{{ number_format($item->subtotal, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                    <tr class="total-row">
                        <td colspan="4" style="text-align: right;">TOTAL PENAWARAN:</td>
                        <td style="text-align: right; color: #ff4950;">Rp {{ number_format($rfq->total_offered_amount, 0, ',', '.') }}</td>
                    </tr>
                </tbody>
            </table>

            <p style="font-size: 13px; color: #666; margin-bottom: 24px;">
                * Masa berlaku penawaran sampai: <strong>{{ $rfq->valid_until ? $rfq->valid_until->format('d F Y') : '30 hari dari tanggal penawaran' }}</strong>.
            </p>

            <div style="text-align: center; margin-top: 24px;">
                <a href="{{ route('rfq.track', ['number' => $rfq->rfq_number, 'token' => $rfq->access_token]) }}" class="btn" style="background-color: #28a745 !important; background: #28a745 !important; color: #ffffff !important; text-decoration: none !important; display: inline-block; padding: 12px 24px; border-radius: 6px; font-weight: bold; font-size: 14px; margin-top: 16px;"><span style="color: #ffffff !important; text-decoration: none !important;">Setujui Penawaran &amp; Proses PO</span></a>
                <a href="{{ route('rfq.pdf', ['number' => $rfq->rfq_number, 'token' => $rfq->access_token]) }}" target="_blank" class="btn-pdf" style="background-color: #ff4950 !important; background: #ff4950 !important; color: #ffffff !important; text-decoration: none !important; display: inline-block; padding: 12px 24px; border-radius: 6px; font-weight: bold; font-size: 14px; margin-top: 16px; margin-left: 10px;"><span style="color: #ffffff !important; text-decoration: none !important;">Cetak / Download Quotation PDF</span></a>
            </div>
        </div>
        <div class="footer">
            PT. Prolabios Mitra Analitika | Distributor Alat &amp; Reagen Laboratorium<br>
            Email: info@prolabios.com | WhatsApp: +62 812-3456-7890
        </div>
    </div>
</body>
</html>
