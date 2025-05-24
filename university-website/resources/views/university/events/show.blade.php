@extends('layouts.app')

@section('title', $event['title'] . ' - Event Universitas')

@section('university-name', 'Nusantara')

@section('content')
<div class="card shadow-sm mb-4">
    <div class="card-body">
        <div class="row">
            <div class="col-md-8">
                <nav aria-label="breadcrumb" class="mb-3">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('university.events.index') }}">Event
                                Universitas</a>
                        </li>
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

                @auth
                <div class="mt-4">
                    <div class="d-flex gap-2">
                        <a href="{{ route('university.events.edit', $event['id']) }}" class="btn btn-warning">
                            <i class="fas fa-edit me-1"></i> Edit Event
                        </a>
                        <button type="button" class="btn btn-danger" data-bs-toggle="modal"
                            data-bs-target="#deleteModal">
                            <i class="fas fa-trash me-1"></i> Hapus Event
                        </button>
                    </div>
                </div>
                @endauth
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
                                <span>Penyelenggara: Universitas</span>
                            </li>
                            <li class="list-group-item d-flex align-items-center">
                                <i class="fas fa-phone me-2 text-secondary"></i>
                                <span>Kontak: (021) 12345678</span>
                            </li>
                            <li class="list-group-item d-flex align-items-center">
                                <i class="fas fa-envelope me-2 text-secondary"></i>
                                <span>Email: info@univ.ac.id</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Registrants (if admin) -->
@auth
<div class="card shadow-sm">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0"><i class="fas fa-users me-2"></i>Daftar Peserta</h5>
    </div>
    <div class="card-body">
        @if(count($event['registrations'] ?? []) > 0)
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>NIM</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Tanggal Daftar</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($event['registrations'] as $index => $registration)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $registration['student_id'] }}</td>
                        <td>{{ $registration['student_name'] }}</td>
                        <td>{{ $registration['student_email'] }}</td>
                        <td>{{ \Carbon\Carbon::parse($registration['registration_date'])->format('d M Y H:i') }}</td>
                        <td>
                            <span
                                class="badge bg-{{ $registration['status'] == 'approved' ? 'success' : ($registration['status'] == 'rejected' ? 'danger' : 'warning') }}">
                                {{ ucfirst($registration['status']) }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="alert alert-info">
            <i class="fas fa-info-circle me-2"></i> Belum ada peserta yang mendaftar.
        </div>
        @endif
    </div>
</div>
@endauth

<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Konfirmasi Hapus</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                Apakah Anda yakin ingin menghapus event "{{ $event['title'] }}"?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <form action="{{ route('university.events.destroy', $event['id']) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Hapus</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection