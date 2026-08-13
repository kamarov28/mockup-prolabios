<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Tanda Terima Pengajuan Penawaran</title>
  <style>
    body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; background-color: #f4f5f7; margin: 0; padding: 20px; color: #333; }
    .container { max-width: 600px; margin: 0 auto; background: #ffffff; border-radius: 8px; overflow: hidden; border: 1px solid #e2e8f0; }
    .header { background: #070708; padding: 24px; text-align: center; border-bottom: 2px solid #FF4950; }
    .header h2 { color: #ffffff; margin: 0; font-size: 20px; font-weight: 700; letter-spacing: 1px; }
    .header p { color: #FF4950; margin: 4px 0 0 0; font-size: 12px; font-weight: 600; text-transform: uppercase; letter-spacing: 1.5px; }
    .body { padding: 30px; }
    .greeting { font-size: 16px; font-weight: 600; color: #1e293b; margin-bottom: 12px; }
    .intro { font-size: 14px; line-height: 1.6; color: #475569; margin-bottom: 20px; }
    .card-info { background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 6px; padding: 16px; margin-bottom: 24px; }
    .card-info table { width: 100%; border-collapse: collapse; }
    .card-info td { padding: 6px 0; font-size: 13px; vertical-align: top; }
    .card-info td.label { color: #64748b; width: 35%; font-weight: 500; }
    .card-info td.val { color: #0f172a; font-weight: 600; }
    .table-items { width: 100%; border-collapse: collapse; margin-bottom: 24px; }
    .table-items th { background: #f1f5f9; color: #475569; font-size: 12px; text-align: left; padding: 10px; border-bottom: 2px solid #cbd5e1; text-transform: uppercase; }
    .table-items td { padding: 10px; border-bottom: 1px solid #e2e8f0; font-size: 13px; color: #334155; }
    .footer { background: #f8fafc; padding: 20px; text-align: center; border-top: 1px solid #e2e8f0; font-size: 12px; color: #94a3b8; line-height: 1.5; }
  </style>
</head>
<body>
  <div class="container">
    <div class="header">
      <h2>PT. PROLABIOS MITRA ANALITIKA</h2>
      <p>Official Product Inquiry Confirmation</p>
    </div>
    
    <div class="body">
      <div class="greeting">Halo {{ $rfq->name }},</div>
      <div class="intro">
        Terima kasih telah mengajukan penawaran di <strong>PT. Prolabios Mitra Analitika</strong>. Pengajuan dari <strong>{{ $rfq->company_name }}</strong> telah berhasil kami terima.
      </div>

      <div class="card-info">
        <table>
          <tr>
            <td class="label">Nomor Pengajuan:</td>
            <td class="val" style="color: #FF4950; font-size: 15px;">{{ $rfq->rfq_number }}</td>
          </tr>
          <tr>
            <td class="label">Nama Instansi:</td>
            <td class="val">{{ $rfq->company_name }}</td>
          </tr>
          <tr>
            <td class="label">Tanggal Pengajuan:</td>
            <td class="val">{{ $rfq->created_at ? $rfq->created_at->format('d F Y, H:i') : date('d F Y') }} WIB</td>
          </tr>
        </table>
      </div>

      <div style="font-weight: 600; font-size: 14px; margin-bottom: 10px; color: #1e293b;">Rincian Produk yang Diajukan:</div>
      <table class="table-items">
        <thead>
          <tr>
            <th>Produk</th>
            <th>No. Katalog</th>
            <th style="text-align: center;">Qty</th>
          </tr>
        </thead>
        <tbody>
          @foreach($rfq->items as $item)
          <tr>
            <td><strong>{{ $item->product_title }}</strong></td>
            <td>{{ $item->catalog_no ?: '-' }}</td>
            <td style="text-align: center;">{{ $item->quantity }} Unit</td>
          </tr>
          @endforeach
        </tbody>
      </table>

      <div style="background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 6px; padding: 14px; font-size: 13px; color: #166534; line-height: 1.5;">
        Tim sales kami akan segera menghubungi Anda melalui email atau WhatsApp di nomor <strong>{{ $rfq->phone_wa }}</strong> untuk memberikan penawaran resmi.
      </div>
    </div>

    <div class="footer">
      <strong>PT. Prolabios Mitra Analitika</strong><br>
      Komp. Cibinong Griya Asri Blok A9/10, Bogor, Jawa Barat 16913<br>
      Email: marketing@prolabios.com | WA: 0821-8792-9433
    </div>
  </div>
</body>
</html>
