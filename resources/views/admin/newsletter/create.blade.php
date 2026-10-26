@extends('admin.layout')

@section('title', 'Kirim Siaran Email Baru')
@section('page_title', 'Buletin & Siaran Sektor')

@section('admin_content')
<div class="admin-content-wrap">

  <x-admin.page-header
    title="Buat Siaran Email Baru"
    subtitle="Kirimkan pengumuman produk baru, rilis regulasi ISO/BPOM, atau buletin teknis ke akun pelanggan sesuai sektor industri."
    badge="Email Broadcast">
    <x-slot:actions>
      <a href="{{ route('admin.newsletter.index') }}" class="admin-btn admin-btn-outline">
        <i data-lucide="arrow-left"></i> Kembali ke Daftar
      </a>
    </x-slot:actions>
  </x-admin.page-header>

  <div class="card p-4 border-0" style="background: #FFFFFF; border: 1px solid var(--color-border) !important; border-radius: 10px; max-width: 860px;">

    @if($errors->any())
      <div class="alert alert-danger mb-4" style="font-size: 0.85rem;">
        <ul class="mb-0 ps-3">
          @foreach($errors->all() as $err)
            <li>{{ $err }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <form action="{{ route('admin.newsletter.send') }}" method="POST">
      @csrf

      <div class="mb-3">
        <label for="subject" class="admin-form-label">Subjek Email <span class="text-danger">*</span></label>
        <input type="text" class="form-control" id="subject" name="subject" value="{{ old('subject') }}" required placeholder="Contoh: [Pembaruan Regulasi ISO 4833:2026] Metode Pengujian Mikrobiologi Terbaru">
      </div>

      <div class="row g-3 mb-3">
        <div class="col-md-6">
          <label for="target_sector_id" class="admin-form-label">Target Sektor Penerima</label>
          <select class="form-select" id="target_sector_id" name="target_sector_id">
            <option value="">-- Seluruh Sektor (Semua Pelanggan Aktif) --</option>
            @foreach($sectors as $sec)
              <option value="{{ $sec->id }}" {{ old('target_sector_id') === $sec->id ? 'selected' : '' }}>
                Sektor: {{ $sec->name }}
              </option>
            @endforeach
          </select>
          <div class="form-text small" style="font-size: 0.78rem;">Pilih sektor tertentu jika pesan ini spesifik untuk industri tersebut.</div>
        </div>

        <div class="col-md-6">
          <label for="type" class="admin-form-label">Tipe Pesan <span class="text-danger">*</span></label>
          <select class="form-select" id="type" name="type" required>
            <option value="announcement" {{ old('type') === 'announcement' ? 'selected' : '' }}>Pengumuman Umum</option>
            <option value="product_release" {{ old('type') === 'product_release' ? 'selected' : '' }}>Rilis Produk / Reagen Baru</option>
            <option value="regulatory_update" {{ old('type') === 'regulatory_update' ? 'selected' : '' }}>Pembaruan Regulasi / Standar ISO & BPOM</option>
            <option value="event" {{ old('type') === 'event' ? 'selected' : '' }}>Undangan Webinar / Event Ilmiah</option>
          </select>
        </div>
      </div>

      <div class="mb-4">
        <label for="content" class="admin-form-label">Isi Pesan Email <span class="text-danger">*</span></label>
        <textarea class="form-control" id="content" name="content" rows="10" required placeholder="Tuliskan isi informasi buletin resmi di sini. Tag HTML sederhana seperti <p>, <strong>, <ul>, <li>, dan <a> didukung secara otomatis.">{{ old('content') }}</textarea>
        <div class="form-text small" style="font-size: 0.78rem;">
          Pesan akan otomatis dibungkus dengan kop resmi PT. Prolabios Mitra Analitika dan tombol unsubscribe bertanda tangan aman.
        </div>
      </div>

      <div class="d-flex align-items-center justify-content-between pt-3 border-top" style="border-color: var(--color-border) !important;">
        <span class="small text-muted">Email akan dikirim secara asinkronus via sistem queue background.</span>
        <button type="submit" class="admin-btn admin-btn-primary px-4 py-2" onclick="return confirm('Apakah Anda yakin ingin menyiarkan email ini ke seluruh pelanggan pada sektor terpilih?');">
          <i data-lucide="send"></i> Siarkan Email Sekarang
        </button>
      </div>

    </form>

  </div>

</div>
@endsection
