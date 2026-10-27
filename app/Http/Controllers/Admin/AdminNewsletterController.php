<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\HtmlSanitizer;
use App\Http\Controllers\Controller;
use App\Jobs\SendNewsletterBroadcastJob;
use App\Models\NewsletterBroadcast;
use App\Models\NewsletterSubscriber;
use App\Models\Sector;
use App\Services\NewsletterImportService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AdminNewsletterController extends Controller
{
    public function __construct(
        protected NewsletterImportService $importService
    ) {}
    public function index(Request $request)
    {
        $sectors = Sector::orderBy('name')->get();
        $sectorFilter = $request->input('sector');

        $subscribersQuery = NewsletterSubscriber::with('sectors')->latest();
        if ($sectorFilter) {
            $subscribersQuery->whereHas('sectors', fn ($q) => $q->where('sectors.id', $sectorFilter));
        }
        $subscribers = $subscribersQuery->paginate(20)->withQueryString();

        $broadcasts = NewsletterBroadcast::with(['targetSector', 'sender'])->latest()->paginate(10, ['*'], 'broadcasts_page');

        $totalSubscribers = NewsletterSubscriber::where('is_active', true)->count();
        $totalBroadcasts = NewsletterBroadcast::count();

        return view('admin.newsletter.index', compact(
            'sectors',
            'subscribers',
            'broadcasts',
            'sectorFilter',
            'totalSubscribers',
            'totalBroadcasts'
        ));
    }

    public function createBroadcast()
    {
        $sectors = Sector::orderBy('name')->get();

        return view('admin.newsletter.create', compact('sectors'));
    }

    public function sendBroadcast(Request $request)
    {
        $validated = $request->validate([
            'subject' => ['required', 'string', 'max:255'],
            'target_sector_id' => ['nullable', 'string', 'exists:sectors,id'],
            'type' => ['required', 'string', 'in:announcement,product_release,regulatory_update,event'],
            'content' => ['required', 'string', 'min:10'],
        ], [
            'subject.required' => 'Subjek email wajib diisi.',
            'content.required' => 'Isi pesan broadcast wajib diisi.',
        ]);

        $broadcast = NewsletterBroadcast::create([
            'subject' => trim($validated['subject']),
            'target_sector_id' => $validated['target_sector_id'] ?: null,
            'type' => $validated['type'],
            'content' => HtmlSanitizer::clean($validated['content']),
            'sent_by_user_id' => Auth::id(),
            'recipients_count' => 0,
        ]);

        // Dispatch background queue job for safe email sending
        SendNewsletterBroadcastJob::dispatch($broadcast);

        $sectorLabel = $broadcast->targetSector?->name ?? 'Seluruh Sektor';

        return redirect()->route('admin.newsletter.index')->with('success', "Pesan siaran '{$broadcast->subject}' sedang dikirim ke pelanggan sektor {$sectorLabel} via background queue.");
    }

    /**
     * Download Excel template for importing newsletter subscribers.
     */
    public function downloadTemplate(): StreamedResponse
    {
        $spreadsheet = $this->importService->generateTemplate();
        $filename = 'template-import-pelanggan-buletin-prolabios.xlsx';

        return response()->streamDownload(function () use ($spreadsheet) {
            $writer = new Xlsx($spreadsheet);
            $writer->save('php://output');
        }, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Cache-Control' => 'max-age=0',
        ]);
    }

    /**
     * Handle bulk spreadsheet upload for newsletter subscribers.
     */
    public function import(Request $request)
    {
        $request->validate([
            'excel_file' => ['required', 'file', 'mimes:xlsx,xls,csv', 'max:10240'],
        ], [
            'excel_file.required' => 'File spreadsheet wajib diunggah.',
            'excel_file.mimes' => 'Format file harus berupa Excel (.xlsx, .xls) atau CSV (.csv).',
            'excel_file.max' => 'Ukuran file spreadsheet maksimal 10MB.',
        ]);

        try {
            $result = $this->importService->import($request->file('excel_file'));

            $msg = "Impor selesai: {$result['imported']} pelanggan berhasil ditambahkan, {$result['skipped']} data dilewati (duplikat).";
            if (!empty($result['errors'])) {
                $msg .= ' Beberapa baris bermasalah: ' . implode(' ', array_slice($result['errors'], 0, 3));
            }

            return redirect()->route('admin.newsletter.index')->with('success', $msg);
        } catch (\Throwable $e) {
            return redirect()->route('admin.newsletter.index')->with('error', 'Gagal memproses file spreadsheet: ' . $e->getMessage());
        }
    }
}
