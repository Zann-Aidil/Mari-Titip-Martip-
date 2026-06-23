@extends('layouts.app')

@section('title', 'Lokasi Titip')

@section('content')
<div class="row">
    <div class="col-md-3 mb-4">
        <div class="list-group shadow-sm">
            <a href="{{ route('user.dashboard') }}" class="list-group-item list-group-item-action">Dashboard</a>
            <a href="{{ route('user.lokasi') }}" class="list-group-item list-group-item-action active">Lokasi Titip</a>
            <a href="{{ route('user.tracking') }}" class="list-group-item list-group-item-action">Tracking Barang</a>
            <a href="{{ route('user.riwayat') }}" class="list-group-item list-group-item-action">Riwayat Titipan</a>
            <a href="{{ route('user.profil') }}" class="list-group-item list-group-item-action">Profil Pengguna</a>
        </div>
    </div>
    <div class="col-md-9">
        <h3 class="fw-bold mb-4">Pilih Lokasi Penitipan</h3>
        <div class="row">
            @forelse($locations as $lokasi)
            <div class="col-md-6 mb-4">
                <div class="card h-100 shadow-sm border-0">
                    <div class="card-body">
                        <h5 class="card-title fw-bold">{{ $lokasi->nama_lokasi }}</h5>
                        <p class="card-text text-muted">{{ $lokasi->alamat }}</p>
                        <a href="{{ route('user.lokasi.detail', $lokasi->id) }}" class="btn btn-outline-primary w-100">Lihat Detail & Titip</a>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12">
                <div class="alert alert-warning">Belum ada lokasi penitipan yang tersedia.</div>
            </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
