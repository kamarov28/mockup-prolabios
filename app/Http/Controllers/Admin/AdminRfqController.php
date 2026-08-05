<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\SendQuotationResponseEmailJob;
use App\Mail\QuotationResponseMail;
use App\Models\Rfq;
use App\Models\RfqItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class AdminRfqController extends Controller
{
    public function index(Request $request)
    {
        $query = Rfq::with('items')->orderBy('id', 'desc');

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->filled('s')) {
            $s = $request->input('s');
            $query->where(function($q) use ($s) {
                $q->where('rfq_number', 'like', "%{$s}%")
                  ->orWhere('company_name', 'like', "%{$s}%")
                  ->orWhere('pic_name', 'like', "%{$s}%")
                  ->orWhere('email', 'like', "%{$s}%");
            });
        }

        $rfqs = $query->paginate(15);
        return view('admin.rfq.index', compact('rfqs'));
    }

    public function respond(int $id)
    {
        $rfq = Rfq::with('items')->findOrFail($id);
        return view('admin.rfq.respond', compact('rfq'));
    }

    public function updateQuotation(Request $request, int $id)
    {
        $rfq = Rfq::with('items')->findOrFail($id);

        $validated = $request->validate([
            'valid_until'           => 'required|date|after_or_equal:today',
            'admin_response_notes' => 'nullable|string',
            'items'                 => 'required|array',
            'items.*.offered_price' => 'required|numeric|min:0',
        ]);

        $totalOffered = 0;

        foreach ($validated['items'] as $itemId => $itemData) {
            $rfqItem = RfqItem::where('rfq_id', $rfq->id)->where('id', $itemId)->first();
            if ($rfqItem) {
                $offered = (float)$itemData['offered_price'];
                $subtotal = $offered * $rfqItem->quantity;
                $rfqItem->update([
                    'offered_price' => $offered,
                    'subtotal'      => $subtotal,
                ]);
                $totalOffered += $subtotal;
            }
        }

        $rfq->update([
            'status'               => 'quotation_sent',
            'total_offered_amount' => $totalOffered,
            'admin_response_notes' => $validated['admin_response_notes'] ?? null,
            'valid_until'          => $validated['valid_until'],
        ]);

        // Dispatch Feedback Email Job to Corporate Buyer (asynchronous delivery)
        try {
            SendQuotationResponseEmailJob::dispatch($rfq->id);
            $msg = 'Penawaran resmi berhasil disimpan & antrean email feedback sedang diproses untuk korporasi (' . $rfq->email . ')!';
        } catch (\Throwable $e) {
            \Log::error('Failed to dispatch quotation email job: ' . $e->getMessage());
            $msg = 'Penawaran resmi berhasil disimpan! (Catatan: Antrean email notifikasi gagal dijadwalkan, periksa log/koneksi queue).';
        }

        return redirect()->route('admin.rfq')->with('success', $msg);
    }

    public function destroy(int $id)
    {
        $rfq = Rfq::findOrFail($id);
        $rfq->delete();
        return redirect()->route('admin.rfq')->with('success', 'Pengajuan penawaran berhasil dihapus.');
    }

    public function guide()
    {
        return view('admin.guide');
    }
}
