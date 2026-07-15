<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactMail;

class ContactController extends Controller
{
    public function submit(Request $request, \App\Services\GoogleSheetsService $sheetsService)
    {
        // 1. Validasi Input Formulir
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'telepon' => 'nullable|string|max:50',
            'perusahaan' => 'nullable|string|max:255',
            'subjek' => 'required|string|max:50',
            'pesan' => 'required|string',
        ]);

        // Mapping Label Subjek agar terlihat rapi di email
        $subjekLabels = [
            'inquiry' => 'Pertanyaan Produk',
            'quotation' => 'Permintaan Penawaran Harga',
            'service' => 'Service Request / Perbaikan',
            'consultation' => 'Konsultasi Teknis',
            'labdesign' => 'Desain Laboratorium',
            'other' => 'Lainnya',
        ];
        $validated['subjek_label'] = $subjekLabels[$validated['subjek']] ?? $validated['subjek'];

        try {
            // 2. Tentukan Alamat Email Penerima (Email perusahaan/admin)
            // Mengambil email dari konfigurasi .env atau default lisa.aryadi@prolabios.com
            $recipient = env('MAIL_TO_ADDRESS', 'marketing@prolabios.com');

            // 3. Kirim Email Notifikasi menggunakan Class Mailable
            Mail::to($recipient)->send(new ContactMail($validated));

            // 4. Catat data ke Google Sheets (jika dikonfigurasi)
            $sheetsService->appendInquiry($validated);

            // 5. Kembalikan Response Sukses (format JSON untuk Ajax)
            return response()->json([
                'success' => true,
                'message' => 'Pesan Anda berhasil terkirim! Tim kami akan menghubungi Anda sesegera mungkin.'
            ]);
        } catch (\Exception $e) {
            // Log error jika pengiriman gagal
            \Illuminate\Support\Facades\Log::error('Gagal mengirim email kontak: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Maaf, terjadi kesalahan teknis saat mengirim email. Silakan coba kembali beberapa saat lagi.'
            ], 500);
        }
    }
}
