@extends('layouts.app')


@section('title', 'Event Universitas')


@section('faculty-name', 'MIPA')


@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Event Universitas</h1>
</div>


<div class="row">
    <div class="col-md-3 mb-4">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Filter Event</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('university.events.index') }}" method="GET">
                    <div class="mb-3">
                        <label class="form-label">Kategori</label>
                        <select name="category" class="form-select">
                            <option value="">Semua Kategori</option>
                            <option value="1">Seminar</option>
                            <option value="2">Workshop</option>
                            <option value="3">Kompetisi</option>
                            <option value="4">Kuliah Umum</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tanggal Mulai</label>
                        <input type="date" name="start_date" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tanggal Selesai</label>
                        <input type="date" name="end_date" class="form-control">
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Terapkan Filter</button>
                </form>
            </div>
        </div>
    </div>


    <div class="col-md-9">
        @if(count($universityEvents ?? []) > 0)
        <div class="row">
            @foreach($universityEvents as $event)
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card event-card h-100 shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h5 class="card-title mb-0 text-truncate">{{ $event['title'] }}</h5>
                    </div>
                    <div class="card-body d-flex flex-column">
                        <div class="mb-3">
                            <i class="fas fa-map-marker-alt me-2 text-secondary"></i>{{ $event['location'] }}
                        </div>
                        <div class="mb-3">
                            <i class="fas fa-calendar-alt me-2 text-secondary"></i>
                            {{ \Carbon\Carbon::parse($event['start_datetime'])->format('d M Y - H:i') }}
                        </div>
                        <p class="card-text flex-grow-1">{{ \Str::limit($event['description'], 100) }}</p>
                        <div class="d-flex justify-content-between mt-auto">
                            <span class="badge bg-info">
                                {{ $event['category']['name'] ?? 'Umum' }}
                            </span>
                            @if(isset($event['quota']))
                            <span class="badge bg-secondary">
                                Kuota: {{ $event['quota'] }}
                            </span>
                            @endif
                        </div>
                    </div>
                    <div class="card-footer bg-white border-top-0">
                        <div class="d-grid">
                            <a href="{{ route('university.events.show', $event['id']) }}" class="btn btn-primary">
                                <i class="fas fa-info-circle me-1"></i> Detail
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="alert alert-info">
            <i class="fas fa-info-circle me-2"></i> Tidak ada event universitas saat ini.
        </div>
        @endif
    </div>
</div>
@endsection