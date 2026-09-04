<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateRfqRequest;
use App\Models\Rfq;
use App\Services\AuditLogger;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class AdminRfqController extends Controller
{
    public function index(Request $request)
    {
        $viewMode = $request->input('view', 'table');
        $query = $this->buildFilteredQuery($request);

        if ($viewMode === 'kanban') {
            $allRfqs = $query->with('items')->limit(300)->get();
            $kanbanColumns = [];
            foreach (Rfq::statusOptions() as $key => $label) {
                $kanbanColumns[$key] = [
                    'label' => $label,
                    'rfqs' => $allRfqs->where('status', $key),
                ];
            }

            return view('admin.rfqs.index', [
                'viewMode' => 'kanban',
                'kanbanColumns' => $kanbanColumns,
                'totalRfqs' => $allRfqs->count(),
            ]);
        }

        $rfqs = $query->paginate(15)->withQueryString();

        return view('admin.rfqs.index', [
            'viewMode' => 'table',
            'rfqs' => $rfqs,
        ]);
    }

    public function export(Request $request)
    {
        $rfqs = $this->buildFilteredQuery($request)->with('items.product')->get();
        $filename = 'rekap-rfq-prolabios-' . now()->format('Ymd-His') . '.xlsx';

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Data Rekap RFQ');

        // Freeze pane di baris ke-4 agar header kolom tabel selalu terlihat saat scroll
        $sheet->freezePane('A4');

        // Document Meta Title
        $sheet->setCellValue('A1', 'PT. PROLABIOS MITRA ANALITIKA — REKAPITULASI PENGAJUAN RFQ');
        $sheet->mergeCells('A1:N1');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(12)->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('0F172A'));

        $sheet->setCellValue('A2', 'Diekspor: ' . now()->translatedFormat('d F Y, H:i') . ' WIB | Total RFQ: ' . $rfqs->count() . ' Pengajuan');
        $sheet->mergeCells('A2:N2');
        $sheet->getStyle('A2')->getFont()->setSize(9)->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('64748B'));

        // Table Headers (Baris 3)
        $headers = [
            'A3' => 'NO. RFQ',
            'B3' => 'TANGGAL',
            'C3' => 'STATUS',
            'D3' => 'INSTANSI / PERUSAHAAN',
            'E3' => 'NAMA PIC',
            'F3' => 'EMAIL',
            'G3' => 'NO. WHATSAPP',
            'H3' => 'SKU / KATALOG',
            'I3' => 'NAMA BARANG / ITEM SPESIFIKASI',
            'J3' => 'QTY',
            'K3' => 'EST. HARGA (RP)',
            'L3' => 'SUBTOTAL (RP)',
            'M3' => 'CATATAN KLIEN',
            'N3' => 'CATATAN ADMIN',
        ];

        foreach ($headers as $cell => $text) {
            $sheet->setCellValue($cell, $text);
        }

        $sheet->getStyle('A3:N3')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF'], 'size' => 9],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '1E293B']], // Slate 800 profesional
            'alignment' => [
                'vertical' => Alignment::VERTICAL_CENTER,
                'horizontal' => Alignment::HORIZONTAL_LEFT,
                'wrapText' => true,
            ],
            'borders' => [
                'bottom' => ['borderStyle' => Border::BORDER_MEDIUM, 'color' => ['rgb' => '0F172A']],
            ],
        ]);

        $sheet->getStyle('A3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('B3:C3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('J3:L3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
        $sheet->getRowDimension(3)->setRowHeight(26);

        $row = 4;
        $rfqIndex = 0;

        foreach ($rfqs as $rfq) {
            $rfqIndex++;
            $items = $rfq->items;
            $itemCount = $items->count() > 0 ? $items->count() : 1;
            $startRow = $row;
            $endRow = $row + $itemCount - 1;

            // Palet selang-seling antar RFQ agar batas awal & akhir tiap pengajuan jelas terbaca
            $blockBg = ($rfqIndex % 2 === 1) ? 'FFFFFF' : 'F8FAFC'; // Putih murni vs Soft Slate

            $statusColor = match ($rfq->status) {
                Rfq::STATUS_QUOTED => '0369A1',     // Biru
                Rfq::STATUS_CONTACTED => 'D97706',  // Amber
                Rfq::STATUS_CLOSED => '475569',     // Muted Slate
                default => '15803D',                // Emerald (Baru)
            };

            // 1. Data Level RFQ (A - G, M, N)
            $sheet->setCellValueExplicit("A{$startRow}", $rfq->rfq_number, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $sheet->setCellValue("B{$startRow}", $rfq->created_at ? $rfq->created_at->format('d/m/Y H:i') : '-');
            $sheet->setCellValue("C{$startRow}", $rfq->status_label);
            $sheet->setCellValue("D{$startRow}", $rfq->company_name);
            $sheet->setCellValue("E{$startRow}", $rfq->name);
            $sheet->setCellValue("F{$startRow}", $rfq->email);
            $sheet->setCellValueExplicit("G{$startRow}", (string) $rfq->phone_wa, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $sheet->setCellValue("M{$startRow}", $rfq->notes ?: '-');
            $sheet->setCellValue("N{$startRow}", $rfq->admin_notes ?: '-');

            // Merge kolom induk jika RFQ punya banyak item barang
            if ($itemCount > 1) {
                $sheet->mergeCells("A{$startRow}:A{$endRow}");
                $sheet->mergeCells("B{$startRow}:B{$endRow}");
                $sheet->mergeCells("C{$startRow}:C{$endRow}");
                $sheet->mergeCells("D{$startRow}:D{$endRow}");
                $sheet->mergeCells("E{$startRow}:E{$endRow}");
                $sheet->mergeCells("F{$startRow}:F{$endRow}");
                $sheet->mergeCells("G{$startRow}:G{$endRow}");
                $sheet->mergeCells("M{$startRow}:M{$endRow}");
                $sheet->mergeCells("N{$startRow}:N{$endRow}");
            }

            // 2. Data Level Items (H - L)
            $rfqTotal = 0;
            if ($items->isEmpty()) {
                $sheet->setCellValue("H{$startRow}", '-');
                $sheet->setCellValue("I{$startRow}", '(Tidak ada item terlampir)');
                $sheet->setCellValue("J{$startRow}", 0);
                $sheet->setCellValue("K{$startRow}", '-');
                $sheet->setCellValue("L{$startRow}", '-');
                $currentRow = $startRow;
            } else {
                foreach ($items as $idx => $item) {
                    $currentRow = $startRow + $idx;
                    $price = (float) ($item->original_price ?? 0);
                    $qty = (int) ($item->quantity ?? 1);
                    $subtotal = $price * $qty;
                    $rfqTotal += $subtotal;

                    $catalogNo = $item->catalog_no ?: ($item->product?->catalog ?? '-');
                    $productName = $item->product_title ?: ($item->product?->title ?? '-');

                    $sheet->setCellValueExplicit("H{$currentRow}", $catalogNo, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
                    $sheet->setCellValue("I{$currentRow}", $productName);
                    $sheet->setCellValue("J{$currentRow}", $qty);
                    $sheet->setCellValue("K{$currentRow}", $price > 0 ? $price : '-');
                    $sheet->setCellValue("L{$currentRow}", $subtotal > 0 ? $subtotal : '-');

                    // Format angka
                    if ($price > 0) {
                        $sheet->getStyle("K{$currentRow}")->getNumberFormat()->setFormatCode('"Rp" #,##0');
                    }
                    if ($subtotal > 0) {
                        $sheet->getStyle("L{$currentRow}")->getNumberFormat()->setFormatCode('"Rp" #,##0');
                    }
                }
            }

            // 3. Styling Blok RFQ
            // Fill background grup
            $sheet->getStyle("A{$startRow}:N{$endRow}")->applyFromArray([
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => $blockBg]],
                'alignment' => [
                    'vertical' => Alignment::VERTICAL_TOP,
                    'wrapText' => true,
                ],
                'font' => ['size' => 9.5],
            ]);

            // Alignment spesifik
            $sheet->getStyle("A{$startRow}:C{$endRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("A{$startRow}")->getFont()->setBold(true)->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('0369A1'));
            $sheet->getStyle("C{$startRow}")->getFont()->setBold(true)->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color($statusColor));
            $sheet->getStyle("J{$startRow}:L{$endRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
            $sheet->getStyle("H{$startRow}:H{$endRow}")->getFont()->setBold(true)->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('475569'));

            // Border dalam (halus) dan border bawah penutup RFQ (tegas)
            $sheet->getStyle("A{$startRow}:N{$endRow}")->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN)->getColor()->setRGB('E2E8F0');
            $sheet->getStyle("A{$endRow}:N{$endRow}")->getBorders()->getBottom()->setBorderStyle(Border::BORDER_MEDIUM)->getColor()->setRGB('94A3B8');

            $row = $endRow + 1;
        }

        // Auto/Optimized Widths
        $sheet->getColumnDimension('A')->setWidth(18); // No RFQ
        $sheet->getColumnDimension('B')->setWidth(16); // Tanggal
        $sheet->getColumnDimension('C')->setWidth(14); // Status
        $sheet->getColumnDimension('D')->setWidth(26); // Perusahaan
        $sheet->getColumnDimension('E')->setWidth(22); // Nama PIC
        $sheet->getColumnDimension('F')->setWidth(26); // Email
        $sheet->getColumnDimension('G')->setWidth(18); // No WA
        $sheet->getColumnDimension('H')->setWidth(16); // SKU / Katalog
        $sheet->getColumnDimension('I')->setWidth(34); // Nama Barang
        $sheet->getColumnDimension('J')->setWidth(8);  // Qty
        $sheet->getColumnDimension('K')->setWidth(18); // Harga Satuan
        $sheet->getColumnDimension('L')->setWidth(20); // Subtotal
        $sheet->getColumnDimension('M')->setWidth(26); // Catatan Klien
        $sheet->getColumnDimension('N')->setWidth(26); // Catatan Admin

        return response()->streamDownload(function () use ($spreadsheet) {
            $writer = new Xlsx($spreadsheet);
            $writer->save('php://output');
        }, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Cache-Control' => 'max-age=0',
        ]);
    }

    private function buildFilteredQuery(Request $request)
    {
        $query = Rfq::with('items')->latest();

        $search = $request->input('s');
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('rfq_number', 'like', "%{$search}%")
                    ->orWhere('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('company_name', 'like', "%{$search}%")
                    ->orWhere('phone_wa', 'like', "%{$search}%");
            });
        }

        $status = $request->input('status');
        if ($status && array_key_exists($status, Rfq::statusOptions())) {
            $query->where('status', $status);
        }

        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        if ($startDate) {
            $query->whereDate('created_at', '>=', $startDate);
        }
        if ($endDate) {
            $query->whereDate('created_at', '<=', $endDate);
        }

        // Filter berdasarkan Produk (ID produk atau keyword nama/SKU produk)
        $productId = $request->input('product_id');
        $productQuery = $request->input('product_name');
        if ($productId) {
            $query->whereHas('items', function ($q) use ($productId) {
                $q->where('product_id', $productId);
            });
        } elseif ($productQuery) {
            $query->whereHas('items', function ($q) use ($productQuery) {
                $q->where('product_title', 'like', "%{$productQuery}%")
                    ->orWhere('catalog_no', 'like', "%{$productQuery}%");
            });
        }

        return $query;
    }

    public function show(int $id)
    {
        $rfq = Rfq::with(['items.product'])->findOrFail($id);

        return view('admin.rfqs.show', compact('rfq'));
    }

    public function update(UpdateRfqRequest $request, int $id)
    {
        $rfq = Rfq::findOrFail($id);

        $validated = $request->validated();

        $rfq->update([
            'status' => $validated['status'],
            'admin_notes' => $validated['admin_notes'] ?? null,
        ]);

        AuditLogger::log('rfq.update', 'Rfq', $id, [
            'rfq_number' => $rfq->rfq_number,
            'status' => $rfq->status,
        ]);

        return redirect()
            ->route('admin.rfqs.show', $rfq->id)
            ->with('success', 'Status & catatan internal RFQ berhasil disimpan.');
    }

    public function destroy(int $id)
    {
        $rfq = Rfq::findOrFail($id);
        $rfqNumber = $rfq->rfq_number;
        $rfq->items()->delete();
        $rfq->delete();

        AuditLogger::log('rfq.delete', 'Rfq', $id, [
            'rfq_number' => $rfqNumber,
        ]);

        return redirect()->route('admin.rfqs.index')
            ->with('success', 'Data pengajuan RFQ berhasil dihapus.');
    }
}
