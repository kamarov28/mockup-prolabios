<?php

namespace App\Services;

use App\Enums\RfqStatus;
use App\Models\Rfq;
use Illuminate\Database\Eloquent\Collection;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class RfqExportService
{
    /**
     * Build a formatted PhpSpreadsheet workbook from a collection of RFQs.
     *
     * @param  Collection<int, Rfq>  $rfqs
     */
    public function buildSpreadsheet(Collection $rfqs): Spreadsheet
    {
        $spreadsheet = new Spreadsheet;
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Data Rekap RFQ');

        // Freeze pane di baris ke-4 agar header kolom tabel selalu terlihat saat scroll
        $sheet->freezePane('A4');

        // Document Meta Title
        $sheet->setCellValue('A1', 'PT. PROLABIOS MITRA ANALITIKA — REKAPITULASI PENGAJUAN RFQ');
        $sheet->mergeCells('A1:N1');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(12)->setColor(new Color('0F172A'));

        $sheet->setCellValue('A2', 'Diekspor: '.now()->translatedFormat('d F Y, H:i').' WIB | Total RFQ: '.$rfqs->count().' Pengajuan');
        $sheet->mergeCells('A2:N2');
        $sheet->getStyle('A2')->getFont()->setSize(9)->setColor(new Color('64748B'));

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
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '1E293B']],
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

            $blockBg = ($rfqIndex % 2 === 1) ? 'FFFFFF' : 'F8FAFC';

            $statusColor = $rfq->status instanceof RfqStatus
                ? $rfq->status->color()
                : match ($rfq->status) {
                    Rfq::STATUS_QUOTED => '0369A1',
                    Rfq::STATUS_CONTACTED => 'D97706',
                    Rfq::STATUS_CLOSED => '475569',
                    default => '15803D',
                };

            $sheet->setCellValueExplicit("A{$startRow}", $rfq->rfq_number, DataType::TYPE_STRING);
            $sheet->setCellValue("B{$startRow}", $rfq->created_at ? $rfq->created_at->format('d/m/Y H:i') : '-');
            $sheet->setCellValueExplicit("C{$startRow}", $rfq->status_label, DataType::TYPE_STRING);
            $sheet->setCellValueExplicit("D{$startRow}", $rfq->company_name, DataType::TYPE_STRING);
            $sheet->setCellValueExplicit("E{$startRow}", $rfq->name, DataType::TYPE_STRING);
            $sheet->setCellValueExplicit("F{$startRow}", $rfq->email, DataType::TYPE_STRING);
            $sheet->setCellValueExplicit("G{$startRow}", (string) $rfq->phone_wa, DataType::TYPE_STRING);
            $sheet->setCellValueExplicit("M{$startRow}", $rfq->notes ?: '-', DataType::TYPE_STRING);
            $sheet->setCellValueExplicit("N{$startRow}", $rfq->admin_notes ?: '-', DataType::TYPE_STRING);

            if ($itemCount > 1) {
                foreach (['A', 'B', 'C', 'D', 'E', 'F', 'G', 'M', 'N'] as $col) {
                    $sheet->mergeCells("{$col}{$startRow}:{$col}{$endRow}");
                }
            }

            if ($items->isEmpty()) {
                $sheet->setCellValue("H{$startRow}", '-');
                $sheet->setCellValue("I{$startRow}", '(Tidak ada item terlampir)');
                $sheet->setCellValue("J{$startRow}", 0);
                $sheet->setCellValue("K{$startRow}", '-');
                $sheet->setCellValue("L{$startRow}", '-');
            } else {
                foreach ($items as $idx => $item) {
                    $currentRow = $startRow + $idx;
                    $price = (float) ($item->original_price ?? 0);
                    $qty = (int) ($item->quantity ?? 1);
                    $subtotal = $price * $qty;

                    $catalogNo = $item->catalog_no ?: ($item->product->catalog ?? '-');
                    $productName = $item->product_title ?: ($item->product->title ?? '-');

                    $sheet->setCellValueExplicit("H{$currentRow}", $catalogNo, DataType::TYPE_STRING);
                    $sheet->setCellValueExplicit("I{$currentRow}", $productName, DataType::TYPE_STRING);
                    $sheet->setCellValue("J{$currentRow}", $qty);
                    $sheet->setCellValue("K{$currentRow}", $price > 0 ? $price : '-');
                    $sheet->setCellValue("L{$currentRow}", $subtotal > 0 ? $subtotal : '-');

                    if ($price > 0) {
                        $sheet->getStyle("K{$currentRow}")->getNumberFormat()->setFormatCode('"Rp" #,##0');
                    }
                    if ($subtotal > 0) {
                        $sheet->getStyle("L{$currentRow}")->getNumberFormat()->setFormatCode('"Rp" #,##0');
                    }
                }
            }

            $sheet->getStyle("A{$startRow}:N{$endRow}")->applyFromArray([
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => $blockBg]],
                'alignment' => [
                    'vertical' => Alignment::VERTICAL_TOP,
                    'wrapText' => true,
                ],
                'font' => ['size' => 9.5],
            ]);

            $sheet->getStyle("A{$startRow}:C{$endRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("A{$startRow}")->getFont()->setBold(true)->setColor(new Color('0369A1'));
            $sheet->getStyle("C{$startRow}")->getFont()->setBold(true)->setColor(new Color($statusColor));
            $sheet->getStyle("J{$startRow}:L{$endRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
            $sheet->getStyle("H{$startRow}:H{$endRow}")->getFont()->setBold(true)->setColor(new Color('475569'));

            $sheet->getStyle("A{$startRow}:N{$endRow}")->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN)->getColor()->setRGB('E2E8F0');
            $sheet->getStyle("A{$endRow}:N{$endRow}")->getBorders()->getBottom()->setBorderStyle(Border::BORDER_MEDIUM)->getColor()->setRGB('94A3B8');

            $row = $endRow + 1;
        }

        $columnWidths = [
            'A' => 18, 'B' => 16, 'C' => 14, 'D' => 26, 'E' => 22, 'F' => 26, 'G' => 18,
            'H' => 16, 'I' => 34, 'J' => 8, 'K' => 18, 'L' => 20, 'M' => 26, 'N' => 26,
        ];
        foreach ($columnWidths as $col => $width) {
            $sheet->getColumnDimension($col)->setWidth($width);
        }

        return $spreadsheet;
    }
}
