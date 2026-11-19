<?php

namespace App\Http\Controllers\Admin;

use App\Enums\RfqStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateRfqRequest;
use App\Models\Rfq;
use App\Services\AuditLogger;
use App\Services\RfqExportService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
                    'rfqs' => $allRfqs->filter(fn (Rfq $rfq) => ($rfq->status instanceof RfqStatus ? $rfq->status->value : $rfq->status) === $key),
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

    public function export(Request $request, RfqExportService $exportService)
    {
        $rfqs = $this->buildFilteredQuery($request)->with('items.product')->get();
        $filename = 'rekap-rfq-prolabios-'.now()->format('Ymd-His').'.xlsx';
        $spreadsheet = $exportService->buildSpreadsheet($rfqs);

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
            'status' => $rfq->status instanceof RfqStatus ? $rfq->status->value : $rfq->status,
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

    public function bulkDestroy(Request $request)
    {
        $validated = $request->validate([
            'ids' => 'required|array|min:1',
            'ids.*' => 'integer|exists:rfqs,id',
        ]);

        $ids = $validated['ids'];
        $count = 0;

        DB::transaction(function () use ($ids, &$count) {
            DB::table('rfq_items')->whereIn('rfq_id', $ids)->delete();
            $count = Rfq::whereIn('id', $ids)->delete();
        });

        AuditLogger::log('rfq.bulk_delete', 'Rfq', null, [
            'count' => $count,
            'ids' => $ids,
        ]);

        return redirect()->route('admin.rfqs.index')
            ->with('success', "{$count} pengajuan RFQ berhasil dihapus.");
    }
}
