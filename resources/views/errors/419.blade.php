@extends('errors.layout')

@section('title', 'Sesi Berakhir (419)')
@section('badge', 'Error 419')
@section('code', '419')
@section('heading', 'Sesi Halaman Berakhir')
@section('message', 'Sesi keamanan atau token formulir Anda telah kedaluwarsa karena tidak ada aktivitas dalam beberapa waktu. Silakan muat ulang halaman untuk memperbarui sesi Anda.')

@section('actions')
<button onclick="window.location.reload();" class="btn btn-secondary" style="border: 1px solid var(--color-slate-200);">
    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21.5 2v6h-6M21.34 15.57a10 10 0 1 1-.57-8.38l5.67-5.67"/></svg>
    Muat Ulang Halaman
</button>
@endsection
