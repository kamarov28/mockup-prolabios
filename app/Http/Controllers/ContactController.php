<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Jobs\SendContactEmailJob;
use App\Models\ContactInquiry;
use Illuminate\Support\Str;

class ContactController extends Controller
{
    public function submit(Request $request)
    {
        // 1. Validasi Input Formulir
        $validated = $request->validate([
            "nama" => "required|string|max:255",
            "email" => "required|email|max:255",
            "telepon" => "nullable|string|max:50",
            "perusahaan" => "nullable|string|max:255",
            "subjek" => "required|string|max:50",
            "pesan" => "required|string|max:5000",
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

        $correlationId = (string) Str::uuid();
        $validated["correlation_id"] = $correlationId;

        try {
            // Log dispatch event
            Log::channel('contact')->info("Memulai proses kirim pesan kontak secara asinkron (dispatching jobs).", [
                'correlation_id' => $correlationId,
            ]);

            // 2. Simpan payload terenkripsi ke database
            // Strip any HTML tags from the message to avoid XSS when rendered in emails
            $validated['pesan'] = strip_tags($validated['pesan'] ?? '');
            $inquiry = ContactInquiry::create(['payload' => $validated]);

            // 3. Kirim email secara asinkron lewat queue (hanya mengirimkan ID referensi)
            SendContactEmailJob::dispatch($inquiry->id);

            // 4. Kembalikan Response Sukses
            return response()->json([
                "success" => true,
                "message" =>
                    "Pesan Anda berhasil terkirim! Tim kami akan menghubungi Anda sesegera mungkin.",
            ]);
        } catch (\Exception $e) {
            // Mencatat detail error secara aman (hanya menyertakan trace lengkap jika debug aktif)
            $logContext = [
                'correlation_id' => $correlationId,
                'exception_class' => get_class($e),
                'exception_code' => $e->getCode(),
            ];

            if (config('app.debug')) {
                $logContext['exception'] = $e;
                $logContext['trace'] = $e->getTraceAsString();
            }

            Log::channel('contact')->error("Gagal mengirim email lewat form.", $logContext);

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
