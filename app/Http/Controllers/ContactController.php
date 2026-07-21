<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Jobs\SendContactEmailJob;
use App\Jobs\SyncGoogleSheetsJob;

class ContactController extends Controller
{
    public function submit(
        Request $request,
        ?\App\Services\GoogleSheetsService $sheetsService = null
    ) {
        // 1. Validasi Input Formulir
        $validated = $request->validate([
            "nama" => "required|string|max:255",
            "email" => "required|email|max:255",
            "telepon" => "nullable|string|max:50",
            "perusahaan" => "nullable|string|max:255",
            "subjek" => "required|string|max:50",
            "pesan" => "required|string",
        ]);

        // Mapping Label Subjek agar terlihat rapi di email
        $subjekLabels = [
            "inquiry" => "Pertanyaan Produk",
            "quotation" => "Permintaan Penawaran Harga",
            "service" => "Service Request / Perbaikan",
            "consultation" => "Konsultasi Teknis",
            "labdesign" => "Desain Laboratorium",
            "other" => "Lainnya",
        ];
        $validated["subjek_label"] =
            $subjekLabels[$validated["subjek"]] ?? $validated["subjek"];

        try {
            // 2. Kirim email secara asinkron lewat queue
            SendContactEmailJob::dispatch($validated);

            // 3. Catat data ke Google Sheets secara asinkron (lewat queue)
            SyncGoogleSheetsJob::dispatch($validated);

            // 5. Kembalikan Response Sukses
            return response()->json([
                "success" => true,
                "message" =>
                    "Pesan Anda berhasil terkirim! Tim kami akan menghubungi Anda sesegera mungkin.",
            ]);
        } catch (\Exception $e) {
            // Mencatat detail error secara aman (hanya menyertakan trace lengkap jika debug aktif)
            $logContext = [
                'exception_class' => get_class($e),
                'exception_code' => $e->getCode(),
            ];

            if (config('app.debug')) {
                $logContext['exception'] = $e;
                $logContext['trace'] = $e->getTraceAsString();
            }

            Log::error("Gagal mengirim email lewat form: " . $e->getMessage(), $logContext);

            return response()->json(
                [
                    "success" => false,
                    "message" => "Maaf, terjadi kesalahan teknis saat mengirim pesan. Silakan coba beberapa saat lagi.",
                ],
                500,
            );
        }
    }
}
