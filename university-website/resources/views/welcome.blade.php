@extends('layouts.app')

@section('title', 'Universitas Kita - Informasi Event')

@section('university-name', 'Kita')

@section('content')
<div class="row align-items-center">
    <div class="col-lg-6">
        <h1 class="display-4 fw-bold mb-4">Sistem Informasi Event Universitas Kita</h1>
        <p class="lead mb-4">Pusat informasi dan pendaftaran berbagai kegiatan kampus. Dapatkan informasi terbaru
            tentang seminar, workshop, dan berbagai acara menarik lainnya.</p>
        <div class="d-grid gap-2 d-md-flex justify-content-md-start">
            <a href="/university/events" class="btn btn-primary btn-lg px-4 me-md-2">Lihat Semua Event</a>
            <a href="#upcoming" class="btn btn-outline-secondary btn-lg px-4">Event Mendatang</a>
        </div>
    </div>
    <div class="col-lg-6 mt-4 mt-lg-0">
        <img src="https://source.unsplash.com/random/900×700/?university-event" class="img-fluid rounded shadow"
            alt="Universitas Kita">
    </div>
</div>

<div class="row mt-5" id="upcoming">
    <div class="col-12 mb-4">
        <h2 class="border-bottom pb-2">Event Mendatang</h2>
    </div>

    @for ($i = 1; $i <= 3; $i++) <div class="col-md-4 mb-4">
        <div class="card event-card h-100 shadow-sm">
            <div class="position-relative">
                <img src="https://source.unsplash.com/random/600×400/?event-{{ $i }}" class="card-img-top"
                    alt="Event Image">
                <div class="position-absolute top-0 end-0 m-2">
                    <span class="badge bg-primary py-2 px-3">Seminar</span>
                </div>
            </div>
            <div class="card-body d-flex flex-column">
                <h5 class="card-title">Event Universitas #{{ $i }}</h5>
                <div class="mb-3">
                    <i class="fas fa-map-marker-alt me-2 text-secondary"></i>Auditorium Utama
                </div>
                <div class="mb-3">
                    <i
                        class="fas fa-calendar-alt me-2 text-secondary"></i>{{ date('d M Y', strtotime('+'. $i .' days')) }}
                </div>
                <p class="card-text flex-grow-1">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed vitae
                    justo eu nisi tincidunt tincidunt.</p>
            </div>
            <div class="card-footer bg-white border-top-0">
                <div class="d-grid">
                    <a href="/university/events/{{$i}}" class="btn btn-primary">
                        <i class="fas fa-info-circle me-1"></i> Detail
                    </a>
                </div>
            </div>
        </div>
</div>
@endfor
</div>

<div class="row mt-5">
    <div class="col-12 mb-4">
        <h2 class="border-bottom pb-2">Kategori Event</h2>
    </div>

    <div class="col-md-3 mb-4">
        <div class="card text-center h-100 border-0 shadow-sm">
            <div class="card-body">
                <div class="mb-3">
                    <i class="fas fa-chalkboard-teacher fa-3x text-primary"></i>
                </div>
                <h5 class="card-title">Seminar</h5>
                <p class="card-text">Berbagai seminar dengan pembicara ahli dari berbagai bidang.</p>
                <a href="#" class="btn btn-outline-primary">Lihat Seminar</a>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-4">
        <div class="card text-center h-100 border-0 shadow-sm">
            <div class="card-body">
                <div class="mb-3">
                    <i class="fas fa-laptop-code fa-3x text-primary"></i>
                </div>
                <h5 class="card-title">Workshop</h5>
                <p class="card-text">Workshop praktis untuk meningkatkan keterampilan dan pengetahuan.</p>
                <a href="#" class="btn btn-outline-primary">Lihat Workshop</a>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-4">
        <div class="card text-center h-100 border-0 shadow-sm">
            <div class="card-body">
                <div class="mb-3">
                    <i class="fas fa-trophy fa-3x text-primary"></i>
                </div>
                <h5 class="card-title">Kompetisi</h5>
                <p class="card-text">Berbagai kompetisi untuk mengasah kemampuan dan meraih prestasi.</p>
                <a href="#" class="btn btn-outline-primary">Lihat Kompetisi</a>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-4">
        <div class="card text-center h-100 border-0 shadow-sm">
            <div class="card-body">
                <div class="mb-3">
                    <i class="fas fa-microphone-alt fa-3x text-primary"></i>
                </div>
                <h5 class="card-title">Kuliah Umum</h5>
                <p class="card-text">Kuliah terbuka dengan topik-topik terkini dan menarik.</p>
                <a href="#" class="btn btn-outline-primary">Lihat Kuliah</a>
            </div>
        </div>
    </div>
</div>

<div class="row mt-5 align-items-center bg-light p-4 rounded">
    <div class="col-md-6">
        <h2>Ingin Menyelenggarakan Event?</h2>
        <p>Fakultas, Departemen, dan Unit Kerja dapat menyelenggarakan event dengan mengajukan proposal melalui sistem
            kami.</p>
        <a href="/login" class="btn btn-primary">Login untuk Mendaftar</a>
    </div>
    <div class="col-md-6">
        <img src="https://source.unsplash.com/random/600×400/?meeting" class="img-fluid rounded shadow"
            alt="Organizing Event">
    </div>
</div>
@endsection