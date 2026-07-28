<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>OFFICIAL QUOTATION {{ $rfq->rfq_number }} - PT. PROLABIOS MITRA ANALITIKA</title>
    <style>
        body { font-family: 'Space Grotesk', 'Helvetica Neue', Arial, sans-serif; color: #111; margin: 0; padding: 20px; font-size: 13px; background: #fff; }
        .invoice-box { max-width: 800px; margin: auto; padding: 30px; border: 1px solid #eee; box-shadow: 0 0 10px rgba(0, 0, 0, .05); }
        .company-header { border-bottom: 2px solid #ff4950; padding-bottom: 15px; margin-bottom: 20px; }
        .company-header table { width: 100%; }
        .company-logo { font-size: 22px; font-weight: bold; color: #ff4950; text-transform: uppercase; letter-spacing: 1px; }
        .company-sub { font-size: 11px; color: #555; line-height: 1.4; }
        .quotation-title { font-size: 18px; font-weight: bold; text-align: center; margin-bottom: 20px; text-transform: uppercase; letter-spacing: 1px; color: #111; }
        .meta-table { width: 100%; margin-bottom: 20px; border-collapse: collapse; }
        .meta-table td { padding: 5px; vertical-align: top; }
        .items-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .items-table th, .items-table td { border: 1px solid #ddd; padding: 8px 10px; text-align: left; }
        .items-table th { background-color: #0c0d0e; color: #ffffff; font-size: 12px; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .total-row { font-weight: bold; background-color: #f9f9f9; }
        .terms { border: 1px solid #e0e0e0; padding: 12px; background: #fdfdfd; border-radius: 4px; margin-bottom: 30px; font-size: 11px; line-height: 1.5; }
        .signature-table { width: 100%; margin-top: 30px; }
        .signature-table td { width: 50%; text-align: center; vertical-align: top; }
        .signature-space { height: 70px; }
        @media print {
            .no-print { display: none !important; }
            .invoice-box { border: none; box-shadow: none; padding: 0; }
        }
    </style>
</head>
<body>

    <div class="no-print" style="max-width: 800px; margin: 0 auto 15px; text-align: right;">
        <button onclick="window.print()" style="padding: 10px 20px; background: #ff4950; color: #fff; border: none; border-radius: 4px; font-weight: bold; cursor: pointer;">
            🖨️ Cetak / Simpan PDF Dokumen Ini
        </button>
    </div>

    <div class="invoice-box">
        <!-- Header -->
        <div class="company-header">
            <table>
                <tr>
                    <td>
                        <div class="company-logo">PT. PROLABIOS MITRA ANALITIKA</div>
                        <div class="company-sub">
                            Professional, Robust, Offering the best<br>
                            Distributor Alat &amp; Reagen Mikrobiologi Laboratorium<br>
                            Email: sales@prolabios.com | Web: www.prolabios.com
                        </div>
                    </td>
                    <td class="text-right">
                        <div style="font-size: 16px; font-weight: bold; color: #ff4950;">SURAT PENAWARAN RESMI</div>
                        <div style="font-size: 12px; color: #555;">No: <strong>{{ $rfq->rfq_number }}</strong></div>
                        <div style="font-size: 11px; color: #777;">Tanggal: {{ $rfq->updated_at ? $rfq->updated_at->format('d/m/Y') : date('d/m/Y') }}</div>
                    </td>
                </tr>
            </table>
        </div>

        <div class="quotation-title">OFFICIAL QUOTATION (SURAT PENAWARAN HARGA)</div>

        <!-- Meta Info -->
        <table class="meta-table">
            <tr>
                <td style="width: 55%;">
                    <strong>KEPADA YTH:</strong><br>
                    <strong>{{ $rfq->company_name }}</strong><br>
                    Attn: {{ $rfq->pic_name }} ({{ $rfq->pic_position ?: 'Procurement' }})<br>
                    NPWP/NIB: {{ $rfq->company_tax_id ?: '-' }}<br>
                    Email: {{ $rfq->email }} | WA: {{ $rfq->phone_wa }}<br>
                    Alamat: {{ $rfq->address }}
                </td>
                <td style="width: 45%;">
                    <strong>KETERANGAN PENAWARAN:</strong><br>
                    Masa Berluku: {{ $rfq->valid_until ? $rfq->valid_until->format('d F Y') : '30 Hari dari Tanggal Terbit' }}<br>
                    Mata Uang: IDR (Rupiah)<br>
                    Status: <strong style="text-transform: uppercase;">{{ str_replace('_', ' ', $rfq->status) }}</strong>
                </td>
            </tr>
        </table>

        <!-- Table Items -->
        <table class="items-table">
            <thead>
                <tr>
                    <th class="text-center" style="width: 5%;">No</th>
                    <th style="width: 20%;">No. Katalog</th>
                    <th style="width: 40%;">Deskripsi Produk / Item</th>
                    <th class="text-center" style="width: 10%;">Qty</th>
                    <th class="text-right" style="width: 12.5%;">Harga (Rp)</th>
                    <th class="text-right" style="width: 12.5%;">Total (Rp)</th>
                </tr>
            </thead>
            <tbody>
                @foreach($rfq->items as $index => $item)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td>{{ $item->catalog_no ?: '-' }}</td>
                        <td><strong>{{ $item->product_title }}</strong></td>
                        <td class="text-center">{{ $item->quantity }} Unit</td>
                        <td class="text-right">{{ number_format($item->offered_price, 0, ',', '.') }}</td>
                        <td class="text-right">{{ number_format($item->subtotal, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
                <tr class="total-row">
                    <td colspan="5" class="text-right">TOTAL PENAWARAN:</td>
                    <td class="text-right" style="color: #ff4950;">Rp {{ number_format($rfq->total_offered_amount, 0, ',', '.') }}</td>
                </tr>
            </tbody>
        </table>

        @if($rfq->admin_response_notes)
            <div class="terms">
                <strong>Catatan Khusus Penawaran Sales:</strong><br>
                {{ $rfq->admin_response_notes }}
            </div>
        @endif

        <div class="terms">
            <strong>Syarat &amp; Ketentuan Penawaran:</strong>
            <ol style="margin: 5px 0 0; padding-left: 18px;">
                <li>Harga di atas belum/sudah termasuk PPN 11% sesuai regulasi perpajakan yang berlaku.</li>
                <li>Pengiriman barang akan dilakukan setelah penerbitan Purchase Order (PO) resmi disetujui.</li>
                <li>Sertifikat COA (Certificate of Analysis) / MSDS disertakan saat pengiriman barang.</li>
            </ol>
        </div>

        <!-- Signature -->
        <table class="signature-table">
            <tr>
                <td>
                    Disetujui Oleh Pembeli,<br>
                    <strong>{{ $rfq->company_name }}</strong>
                    <div class="signature-space"></div>
                    (_______________________)<br>
                    {{ $rfq->pic_name }}
                </td>
                <td>
                    Hormat Kami,<br>
                    <strong>PT. PROLABIOS MITRA ANALITIKA</strong>
                    <div class="signature-space"></div>
                    (_______________________)<br>
                    Tim Penawaran &amp; Sales Procurement
                </td>
            </tr>
        </table>
    </div>

</body>
</html>
