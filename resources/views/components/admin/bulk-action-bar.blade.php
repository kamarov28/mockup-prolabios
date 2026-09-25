@props(['route', 'label' => 'data'])

<form id="bulkDeleteForm" action="{{ $route }}" method="POST" style="display: none;">
  @csrf
</form>

<div id="bulkActionBar" class="admin-bulk-action-bar" style="display: none;">
  <div class="d-flex align-items-center gap-2">
    <span class="admin-bulk-pill-count" id="bulkSelectCount">0</span>
    <span class="admin-bulk-pill-label">{{ $label }} terpilih</span>
  </div>

  <div class="admin-bulk-pill-divider"></div>

  <div class="d-flex align-items-center gap-2">
    <button type="button" class="admin-btn admin-bulk-pill-btn-cancel" id="bulkCancelBtn">
      Batal
    </button>
    <button type="button" class="admin-btn admin-btn-danger admin-bulk-pill-btn-delete" id="bulkDeleteBtn">
      <i data-lucide="trash-2"></i> Hapus Terpilih
    </button>
  </div>
</div>
