<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateRfqRequest;
use App\Models\Rfq;
use App\Services\AuditLogger;
use Illuminate\Http\Request;

class AdminRfqController extends Controller
{
    public function index(Request $request)
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

        $rfqs = $query->paginate(15)->withQueryString();

        return view('admin.rfqs.index', compact('rfqs'));
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
