@extends('layouts.app')

@section('title', $event['title'] . ' - Event Universitas')

@section('faculty-name', 'MIPA')

@section('content')
<div class="card shadow-sm mb-4">
    <div class="card-body">
        <div class="row">
            <div class="col-md-8">
                <nav aria-label="breadcrumb" class="mb-3">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('university.events.index') }}">Event
                                Universitas</a></li>
                        <li class="breadcrumb-item active">{{ $event['title'] }}</li>
                    </ol>
                </nav>

                <h1 class="mb-3">{{ $event['title'] }}</h1>

                <div class="d-flex flex-wrap gap-3 mb-4">
                    <span class="badge bg-primary py-2 px-3">
                        <i class="fas fa-tag me-1"></i> {{ $event['category']['name'] ?? 'Umum' }}
                    </span>
                    <span class="badge bg-info py-2 px-3">
                        <i class="fas fa-map-marker-alt me-1"></i> {{ $event['location'] }}
                    </span>
                    @if(isset($event['quota']))
                    <span class="badge bg-secondary py-2 px-3">
                        <i class="fas fa-users me-1"></i> Kuota: {{ $event['quota'] }}
                    </span>
                    @endif
                </div>

                <div class="mb-4">
                    <h5 class="text-primary"><i class="far fa-calendar-alt me-2"></i>Waktu Pelaksanaan</h5>
                    <p class="lead">
                        {{ \Carbon\Carbon::parse($event['start_datetime'])->format('d M Y - H:i') }}
                        hingga
                        {{ \Carbon\Carbon::parse($event['end_datetime'])->format('d M Y - H:i') }}
                    </p>
                </div>

                <div class="mb-4">
                    <h5 class="text-primary"><i class="fas fa-align-left me-2"></i>Deskripsi</h5>
                    <p>{!! nl2br(e($event['description'])) !!}</p>
                </div>

                @if(isset($event['registration_deadline']))
                <div class="mb-4">
                    <h5 class="text-primary"><i class="fas fa-clock me-2"></i>Batas Pendaftaran</h5>
                    <p>{{ \Carbon\Carbon::parse($event['registration_deadline'])->format('d M Y - H:i') }}</p>
                </div>
                @endif
            </div>

            <div class="col-md-4 mt-4 mt-md-0">
                <div class="card border-primary mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="fas fa-user-plus me-2"></i>Pendaftaran Event</h5>
                    </div>
                    <div class="card-body">
                        @if(\Carbon\Carbon::now() < \Carbon\Carbon::parse($event['registration_deadline'] ??
                            $event['start_datetime'])) <form
                            action="{{ route('university.events.register', $event['id']) }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="student_id" class="form-label">NIM</label>
                                <input type="text" class="form-control" id="student_id" name="student_id" required>
                            </div>
                            <div class="mb-3">
                                <label for="student_name" class="form-label">Nama Lengkap</label>
                                <input type="text" class="form-control" id="student_name" name="student_name" required>
                            </div>
                            <div class="mb-3">
                                <label for="student_email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="student_email" name="student_email"
                                    required>
                            </div>
                            <button type="submit" class="btn btn-success w-100">
                                <i class="fas fa-check-circle me-1"></i> Daftar Sekarang
                            </button>
                            </form>
                            @else
                            <div class="alert alert-warning mb-0">
                                <i class="fas fa-exclamation-circle me-2"></i> Pendaftaran sudah ditutup.
                            </div>
                            @endif
                    </div>
                </div>

                <div class="card border-info">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Informasi Tambahan</h5>
                    </div>
                    <div class="card-body">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex align-items-center">
                                <i class="fas fa-building me-2 text-secondary"></i>
                                <span>Penyelenggara: Universitas Nusantara</span>
                            </li>
                            <li class="list-group-item d-flex align-items-center">
                                <i class="fas fa-phone me-2 text-secondary"></i>
                                <span>Kontak: (021) 87654321</span>
                            </li>
                            <li class="list-group-item d-flex align-items-center">
                                <i class="fas fa-envelope me-2 text-secondary"></i>
                                <span>Email: info@universitas.ac.id</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection