@extends('layouts.app')

@section('title', 'Fakultas MIPA - Sistem Informasi Event')

@section('faculty-name', 'MIPA')

@section('content')
<div class="row align-items-center">
    <div class="col-lg-6">
        <h1 class="display-4 fw-bold mb-4">Sistem Informasi Event Fakultas MIPA</h1>
        <p class="lead mb-4">Jangan lewatkan event-event menarik yang diadakan oleh Fakultas MIPA dan Universitas.
            Dapatkan informasi terbaru dan daftarkan diri Anda secara online!</p>
        <div class="d-grid gap-2 d-md-flex justify-content-md-start">
            <a href="/faculty/events" class="btn btn-primary btn-lg px-4 me-md-2">Event Fakultas</a>
            <a href="/university/events" class="btn btn-outline-secondary btn-lg px-4">Event Universitas</a>
        </div>
    </div>
    <div class="col-lg-6 mt-4 mt-lg-0">
        <img src="https://source.unsplash.com/random/900×700/?university" class="img-fluid rounded shadow"
            alt="Fakultas MIPA">
    </div>
</div>

<div class="row mt-5">
    <div class="col-md-4 mb-4">
        <div class="card h-100 border-0 shadow-sm">
            <div class="card-body text-center">
                <div class="mb-3">
                    <i class="fas fa-calendar-alt fa-3x text-primary"></i>
                </div>
                <h5 class="card-title">Informasi Event Terkini</h5>
                <p class="card-text">Dapatkan informasi lengkap mengenai event fakultas dan universitas yang akan
                    datang.</p>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-4">
        <div class="card h-100 border-0 shadow-sm">
            <div class="card-body text-center">
                <div class="mb-3">
                    <i class="fas fa-user-graduate fa-3x text-primary"></i>
                </div>
                <h5 class="card-title">Pendaftaran Online</h5>
                <p class="card-text">Daftarkan diri Anda ke berbagai event melalui sistem pendaftaran online yang
                    terintegrasi.</p>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-4">
        <div class="card h-100 border-0 shadow-sm">
            <div class="card-body text-center">
                <div class="mb-3">
                    <i class="fas fa-bell fa-3x text-primary"></i>
                </div>
                <h5 class="card-title">Notifikasi Event</h5>
                <p class="card-text">Dapatkan pemberitahuan tentang event baru yang sesuai dengan minat dan jurusan
                    Anda.</p>
            </div>
        </div>
    </div>
</div>
@endsection