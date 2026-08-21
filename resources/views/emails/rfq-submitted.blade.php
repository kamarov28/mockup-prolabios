<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Notifikasi Pengajuan Penawaran Baru</title>
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
    .badge-new { display: inline-block; padding: 3px 8px; background: #FF4950; color: #ffffff; font-size: 11px; font-weight: 700; border-radius: 4px; text-transform: uppercase; margin-left: 8px; vertical-align: middle; }
    .btn-action { display: inline-block; background: #0f172a; color: #ffffff !important; text-decoration: none; padding: 10px 18px; border-radius: 6px; font-size: 13px; font-weight: 600; margin-top: 10px; }
  </style>
</head>
<body>
  <!-- Preheader Text (Visible in email client preview) -->
  <div style="display:none;font-size:1px;color:#ffffff;line-height:1px;max-height:0px;max-width:0px;opacity:0;overflow:hidden;mso-hide:all;">
    Pengajuan Penawaran Baru: {{ $rfq->rfq_number }} dari {{ $rfq->name }} ({{ $rfq->company_name }}). Total {{ count($rfq->items) }} produk.
  </div>

  <div class="container">
    <div class="header">
      <h2>PT. PROLABIOS MITRA ANALITIKA</h2>
      <p>Internal Sales Notification &bull; Permintaan Baru</p>
    </div>
    
    <div class="body">
      <div class="greeting">Halo Tim Sales,</div>
      <div class="intro">
        Terdapat pengajuan penawaran baru yang masuk melalui website dari customer berikut:
      </div>

      <div class="card-info">
        <table>
          <tr>
            <td class="label">Nomor Pengajuan:</td>
            <td class="val">
              <span style="color: #FF4950; font-size: 15px;">{{ $rfq->rfq_number }}</span>
              <span class="badge-new">Baru</span>
            </td>
          </tr>
          <tr>
            <td class="label">Tanggal Pengajuan:</td>
            <td class="val">{{ $rfq->created_at ? $rfq->created_at->format('d F Y, H:i') : date('d F Y') }} WIB</td>
          </tr>
          <tr>
            <td class="label">Nama Pemohon:</td>
            <td class="val">{{ $rfq->name }}</td>
          </tr>
          <tr>
            <td class="label">Nama Instansi:</td>
            <td class="val">{{ $rfq->company_name }}</td>
          </tr>
          <tr>
            <td class="label">Email Customer:</td>
            <td class="val"><a href="mailto:{{ $rfq->email }}" style="color: #0284c7; text-decoration: none;">{{ $rfq->email }}</a></td>
          </tr>
          <tr>
            <td class="label">WhatsApp:</td>
            <td class="val">
              <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $rfq->phone_wa) }}" target="_blank" style="color: #16a34a; font-weight: 700; text-decoration: none;">
                {{ $rfq->phone_wa }} <span style="font-size: 11px; font-weight: normal; color: #16a34a;">(Chat WA &rarr;)</span>
              </a>
            </td>
          </tr>
          @if(!empty($rfq->notes))
          <tr>
            <td class="label">Catatan Customer:</td>
            <td class="val" style="font-style: italic; color: #475569;">{{ $rfq->notes }}</td>
          </tr>
          @endif
        </table>
      </div>

      <div style="font-weight: 600; font-size: 14px; margin-bottom: 10px; color: #1e293b;">Rincian Produk yang Diajukan:</div>
      <table class="table-items">
        <thead>
          <tr>
            <th>Produk</th>
            <th>No. Katalog</th>
            <th>Harga Katalog</th>
            <th style="text-align: center;">Qty</th>
          </tr>
        </thead>
        <tbody>
          @foreach($rfq->items as $item)
          <tr>
            <td><strong>{{ $item->product_title }}</strong></td>
            <td>{{ $item->catalog_no ?: '-' }}</td>
            <td>Rp {{ number_format($item->original_price, 0, ',', '.') }}</td>
            <td style="text-align: center;"><strong>{{ $item->quantity }} Unit</strong></td>
          </tr>
          @endforeach
        </tbody>
      </table>

      <div style="background: #eff6ff; border: 1px solid #bfdbfe; border-radius: 6px; padding: 14px; font-size: 13px; color: #1e40af; line-height: 1.5;">
        <strong>Tindakan Sales:</strong> Segera hubungi customer melalui WhatsApp atau Email untuk memberikan Surat Penawaran Resmi &amp; konfirmasi ketersediaan/estimasi waktu pengadaan.
      </div>
    </div>

    <div class="footer">
      <strong>PT. Prolabios Mitra Analitika</strong><br>
      Inquiry Notification System &bull; Automatic Sales Alert<br>
      Komp. Cibinong Griya Asri Blok A9/10, Bogor, Jawa Barat 16913
    </div>
  </div>
</body>
</html>
