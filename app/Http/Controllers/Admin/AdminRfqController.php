<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Rfq;
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

    public function destroy(int $id)
    {
        $rfq = Rfq::findOrFail($id);
        $rfq->items()->delete();
        $rfq->delete();

        return redirect()->route('admin.rfqs.index')
            ->with('success', 'Data pengajuan RFQ berhasil dihapus.');
    }
}
