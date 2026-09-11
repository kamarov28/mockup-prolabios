@extends('errors.layout')

@section('title', 'Terlalu Banyak Permintaan (429)')
@section('badge', 'Error 429')
@section('code', '429')
@section('heading', 'Terlalu Banyak Permintaan')
@section('message', 'Sistem kami mendeteksi terlalu banyak permintaan dari alamat IP Anda dalam waktu singkat. Untuk menjaga kestabilan dan keamanan layanan, silakan tunggu sekitar 1 menit sebelum mencoba kembali.')
