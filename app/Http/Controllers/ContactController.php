<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class ContactController extends Controller
{
    public function submit(
        Request $request,
        \App\Services\GoogleSheetsService $sheetsService,
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
            // 2. Tentukan Alamat Email Penerima dari .env
            $recipient = env("MAIL_TO_ADDRESS", "marketing@prolabios.com");

            // 3. Ubah bodi menjadi teks mengalir biasa, samarkan format label kaku agar lolos filter spam Google
            $body =
                "Halo, ada pesan masuk dari " .
                $validated["nama"] .
                " (" .
                $validated["email"] .
                ").\n\n" .
                "Nomor kontak yang bisa dihubungi adalah " .
                ($validated["telepon"] ?: "-") .
                " dari instansi " .
                ($validated["perusahaan"] ?: "-") .
                ".\n\n" .
                "Mengenai perihal " .
                $validated["subjek_label"] .
                ", berikut adalah pesannya:\n" .
                $validated["pesan"];

            // Eksekusi pengiriman email asli
            Mail::raw($body, function ($message) use ($recipient) {
                $message
                    ->to($recipient)
                    // Ubah subjek menjadi kalimat biasa, hindari kata "New Inquiry" atau format bot sistem
                    ->subject("Pesan baru dari pengunjung website");
            });

            // 4. Catat data ke Google Sheets (Komentari dulu agar tidak mengganggu durasi)
            // try {
            //     $sheetsService->appendInquiry($validated);
            // } catch (\Exception $sheetException) {
            //     Log::warning('Google Sheets bermasalah atau belum dikonfigurasi: ' . $sheetException->getMessage());
            // }

            // 5. Kembalikan Response Sukses
            return response()->json([
                "success" => true,
                "message" =>
                    "Pesan Anda berhasil terkirim! Tim kami akan menghubungi Anda sesegera mungkin.",
            ]);
        } catch (\Exception $e) {
            // Mencatat detail error lengkap ke file storage/logs/laravel.log
            Log::error("Gagal mengirim email lewat form: " . $e->getMessage(), [
                "exception" => $e,
                "trace" => $e->getTraceAsString(),
            ]);

            return response()->json(
                [
                    "success" => false,
                    "message" => "Gagal mengirim pesan: " . $e->getMessage(),
                ],
                500,
            );
        }
    }
}
