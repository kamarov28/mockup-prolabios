<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <style>
    body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; background: #F8FAFC; color: #0F172A; margin: 0; padding: 24px 12px; }
    .email-container { max-width: 600px; margin: 0 auto; background: #FFFFFF; border: 1px solid #E2E8F0; border-radius: 8px; overflow: hidden; }
    .email-header { background: #A6171C; color: #FFFFFF; padding: 24px; text-align: center; }
    .email-header h1 { margin: 0; font-size: 20px; font-weight: bold; letter-spacing: 0.5px; }
    .email-header p { margin: 4px 0 0; font-size: 12px; opacity: 0.9; }
    .email-body { padding: 30px 24px; font-size: 14px; line-height: 1.6; color: #334155; }
    .sector-badge { display: inline-block; background: #FEF2F2; color: #A6171C; border: 1px solid #FECACA; padding: 3px 10px; border-radius: 4px; font-size: 11px; font-weight: bold; text-transform: uppercase; margin-bottom: 16px; }
    .email-content { margin: 16px 0; font-size: 14.5px; }
    .email-footer { background: #F1F5F9; border-top: 1px solid #E2E8F0; padding: 20px 24px; font-size: 11.5px; color: #64748B; text-align: center; line-height: 1.5; }
    .email-footer a { color: #A6171C; text-decoration: underline; }
  </style>
</head>
<body>
  <div class="email-container">
    <div class="email-header">
      <h1>PT. PROLABIOS MITRA ANALITIKA</h1>
      <p>Pembaruan Teknis &amp; Buletin Laboratorium</p>
    </div>
    <div class="email-body">
      @if(!empty($sectorName))
        <div class="sector-badge">Target Sektor: {{ $sectorName }}</div>
      @else
        <div class="sector-badge">Buletin Umum Seluruh Sektor</div>
      @endif

      <p>Halo, <strong>{{ $subscriber->full_name }}</strong> ({{ $subscriber->company }}),</p>

      <div class="email-content">
        {!! $contentHtml !!}
      </div>

      <p style="margin-top: 24px; font-size: 13px; color: #64748B;">
        Salam hangat,<br>
        <strong>Tim Teknis &amp; Produk PT. Prolabios Mitra Analitika</strong><br>
        Telepon: (021) 3874-1447 / (021) 8792-9433 • marketing@prolabios.com
      </p>
    </div>
    <div class="email-footer">
      <p style="margin: 0 0 8px 0;">
        Anda menerima email ini karena institusi Anda terdaftar pada sistem pembaruan sektor Prolabios.
      </p>
      <p style="margin: 0;">
        <a href="{{ route('newsletter.account') }}">Ubah Pilihan Sektor</a> &bull;
        <a href="{{ route('newsletter.unsubscribe', ['token' => $subscriber->unsubscribe_token]) }}">Berhenti Berlangganan</a>
      </p>
    </div>
  </div>
</body>
</html>
