@extends('admin.layout')

@section('title', 'Manajemen Buletin & Siaran Sektor')

@section('content')
<div class="admin-content-wrap">

  <x-admin.page-header
    title="Manajemen Buletin &amp; Siaran Sektor"
    subtitle="Kelola basis data pelanggan institusi laboratorium dan kirimkan pembaruan produk / regulasi tersegmentasi per sektor industri."
    badge="Communication Hub">
    <x-slot:actions>
      <a href="{{ route('admin.newsletter.create') }}" class="admin-btn admin-btn-primary">
        <i data-lucide="send"></i> Buat Siaran Pesan Baru
      </a>
    </x-slot:actions>
  </x-admin.page-header>

  <!-- Metric Badges -->
  <div class="row g-3 mb-4">
    <div class="col-sm-6 col-xl-3">
      <div class="card p-3 border-0" style="background: #FFFFFF; border: 1px solid var(--color-border) !important; border-radius: 8px;">
        <span class="small text-muted fw-bold text-uppercase" style="font-size: 0.72rem;">Pelanggan Aktif</span>
        <div class="h3 fw-bold mb-0 mt-1" style="color: var(--color-accent, #A6171C);">{{ $totalSubscribers }}</div>
        <span class="small text-muted" style="font-size: 0.76rem;">Institusi &amp; analis lab</span>
      </div>
    </div>
    <div class="col-sm-6 col-xl-3">
      <div class="card p-3 border-0" style="background: #FFFFFF; border: 1px solid var(--color-border) !important; border-radius: 8px;">
        <span class="small text-muted fw-bold text-uppercase" style="font-size: 0.72rem;">Total Siaran Terkirim</span>
        <div class="h3 fw-bold mb-0 mt-1" style="color: #0F172A;">{{ $totalBroadcasts }}</div>
        <span class="small text-muted" style="font-size: 0.76rem;">Kampanye email resmi</span>
      </div>
    </div>
  </div>

  <!-- Subscribers Table -->
  <div class="card border-0 mb-4" style="background: #FFFFFF; border: 1px solid var(--color-border) !important; border-radius: 10px; overflow: hidden;">
    <div class="p-3 border-bottom d-flex align-items-center justify-content-between flex-wrap gap-2" style="background: var(--color-surface-2);">
      <h2 class="fs-6 fw-bold mb-0 text-dark">Daftar Pelanggan Terdaftar</h2>
      
      <!-- Sector Filter Form -->
      <form action="{{ route('admin.newsletter.index') }}" method="GET" class="d-flex align-items-center gap-2 m-0">
        <select name="sector" class="form-select form-select-sm" onchange="this.form.submit()" style="font-size: 0.8rem; width: 190px;">
          <option value="">-- Semua Sektor --</option>
          @foreach($sectors as $sec)
            <option value="{{ $sec->id }}" {{ $sectorFilter === $sec->id ? 'selected' : '' }}>{{ $sec->name }}</option>
          @endforeach
        </select>
        @if($sectorFilter)
          <a href="{{ route('admin.newsletter.index') }}" class="admin-btn admin-btn-outline admin-btn-sm" title="Reset filter">Reset</a>
        @endif
      </form>
    </div>

    <div class="table-responsive">
      <table class="table align-middle mb-0" style="font-size: 0.84rem;">
        <thead class="table-light">
          <tr>
            <th>NAMA &amp; EMAIL</th>
            <th>PERUSAHAAN / INSTANSI</th>
            <th>KOTA</th>
            <th>SEKTOR MINAT</th>
            <th>STATUS</th>
            <th>TERDAFTAR</th>
          </tr>
        </thead>
        <tbody>
          @forelse($subscribers as $sub)
            <tr>
              <td>
                <strong class="d-block text-dark">{{ $sub->full_name }}</strong>
                <span class="text-muted small">{{ $sub->email }}</span>
              </td>
              <td>{{ $sub->company }}</td>
              <td>{{ $sub->city ?: '—' }}</td>
              <td>
                <div class="d-flex flex-wrap gap-1">
                  @foreach($sub->sectors as $s)
                    <span class="badge bg-light text-dark border" style="font-size: 0.68rem; font-weight: 500;">
                      {{ $s->name }}
                    </span>
                  @endforeach
                </div>
              </td>
              <td>
                @if($sub->is_active)
                  <span class="badge bg-success-subtle text-success" style="font-size: 0.72rem;">Aktif</span>
                @else
                  <span class="badge bg-secondary-subtle text-muted" style="font-size: 0.72rem;">Unsubscribed</span>
                @endif
              </td>
              <td class="text-muted small">{{ $sub->created_at->format('d/m/Y') }}</td>
            </tr>
          @empty
            <tr>
              <td colspan="6" class="text-center py-4 text-muted">Belum ada pelanggan terdaftar di sektor ini.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    @if($subscribers->hasPages())
      <div class="p-3 border-top">
        {{ $subscribers->links() }}
      </div>
    @endif
  </div>

  <!-- Recent Broadcasts History -->
  <div class="card border-0" style="background: #FFFFFF; border: 1px solid var(--color-border) !important; border-radius: 10px; overflow: hidden;">
    <div class="p-3 border-bottom" style="background: var(--color-surface-2);">
      <h2 class="fs-6 fw-bold mb-0 text-dark">Riwayat Siaran Email (Broadcasts)</h2>
    </div>

    <div class="table-responsive">
      <table class="table align-middle mb-0" style="font-size: 0.84rem;">
        <thead class="table-light">
          <tr>
            <th>SUBJEK PESAN</th>
            <th>TARGET SEKTOR</th>
            <th>TIPE</th>
            <th>PENERIMA</th>
            <th>PENGIRIM</th>
            <th>WAKTU KIRIM</th>
          </tr>
        </thead>
        <tbody>
          @forelse($broadcasts as $bc)
            <tr>
              <td>
                <strong class="text-dark">{{ $bc->subject }}</strong>
              </td>
              <td>
                @if($bc->targetSector)
                  <span class="badge bg-primary-subtle text-danger border" style="font-size: 0.72rem;">{{ $bc->targetSector->name }}</span>
                @else
                  <span class="badge bg-secondary-subtle text-muted" style="font-size: 0.72rem;">Semua Sektor</span>
                @endif
              </td>
              <td>
                <span class="badge bg-light text-dark border" style="font-size: 0.72rem;">{{ strtoupper(str_replace('_', ' ', $bc->type)) }}</span>
              </td>
              <td><span class="fw-semibold">{{ $bc->recipients_count }}</span> kontak</td>
              <td>{{ $bc->sender?->name ?? 'Sistem' }}</td>
              <td class="text-muted small">{{ $bc->created_at->format('d/m/Y H:i') }}</td>
            </tr>
          @empty
            <tr>
              <td colspan="6" class="text-center py-4 text-muted">Belum ada siaran email yang dikirim.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    @if($broadcasts->hasPages())
      <div class="p-3 border-top">
        {{ $broadcasts->links() }}
      </div>
    @endif
  </div>

</div>
@endsection
