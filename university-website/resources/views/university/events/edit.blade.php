@extends('layouts.app')


@section('title', 'Edit Event Universitas')


@section('university-name', 'Nusantara')


@section('content')
<div class="card shadow-sm">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0"><i class="fas fa-edit me-2"></i>Edit Event Universitas</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('university.events.update', $event['id']) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="title" class="form-label">Judul Event <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title"
                        value="{{ old('title', $event['title']) }}" required>
                    @error('title')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>


                <div class="col-md-6 mb-3">
                    <label for="location" class="form-label">Lokasi <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('location') is-invalid @enderror" id="location"
                        name="location" value="{{ old('location', $event['location']) }}" required>
                    @error('location')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>


                <div class="col-md-6 mb-3">
                    <label for="start_datetime" class="form-label">Waktu Mulai <span
                            class="text-danger">*</span></label>
                    <input type="datetime-local" class="form-control @error('start_datetime') is-invalid @enderror"
                        id="start_datetime" name="start_datetime"
                        value="{{ old('start_datetime', \Carbon\Carbon::parse($event['start_datetime'])->format('Y-m-d\TH:i')) }}"
                        required>
                    @error('start_datetime')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>


                <div class="col-md-6 mb-3">
                    <label for="end_datetime" class="form-label">Waktu Selesai <span
                            class="text-danger">*</span></label>
                    <input type="datetime-local" class="form-control @error('end_datetime') is-invalid @enderror"
                        id="end_datetime" name="end_datetime"
                        value="{{ old('end_datetime', \Carbon\Carbon::parse($event['end_datetime'])->format('Y-m-d\TH:i')) }}"
                        required>
                    @error('end_datetime')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>


                <div class="col-md-6 mb-3">
                    <label for="category_id" class="form-label">Kategori</label>
                    <select class="form-select @error('category_id') is-invalid @enderror" id="category_id"
                        name="category_id">
                        <option value="">Pilih Kategori</option>
                        <option value="1" {{ old('category_id', $event['category_id']) == 1 ? 'selected' : '' }}>Seminar
                        </option>
                        <option value="2" {{ old('category_id', $event['category_id']) == 2 ? 'selected' : '' }}>
                            Workshop</option>
                        <option value="3" {{ old('category_id', $event['category_id']) == 3 ? 'selected' : '' }}>
                            Kompetisi</option>
                        <option value="4" {{ old('category_id', $event['category_id']) == 4 ? 'selected' : '' }}>Kuliah
                            Umum</option>
                    </select>
                    @error('category_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>


                <div class="col-md-6 mb-3">
                    <label for="quota" class="form-label">Kuota Peserta</label>
                    <input type="number" class="form-control @error('quota') is-invalid @enderror" id="quota"
                        name="quota" value="{{ old('quota', $event['quota']) }}" min="1">
                    @error('quota')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>


                <div class="col-md-6 mb-3">
                    <label for="registration_deadline" class="form-label">Batas Pendaftaran</label>
                    <input type="datetime-local"
                        class="form-control @error('registration_deadline') is-invalid @enderror"
                        id="registration_deadline" name="registration_deadline"
                        value="{{ old('registration_deadline', $event['registration_deadline'] ? \Carbon\Carbon::parse($event['registration_deadline'])->format('Y-m-d\TH:i') : '') }}">
                    @error('registration_deadline')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>


                <div class="col-12 mb-3">
                    <label for="description" class="form-label">Deskripsi <span class="text-danger">*</span></label>
                    <textarea class="form-control @error('description') is-invalid @enderror" id="description"
                        name="description" rows="5" required>{{ old('description', $event['description']) }}</textarea>
                    @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>


            <div class="d-flex justify-content-between mt-3">
                <a href="{{ route('university.events.show', $event['id']) }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-1"></i> Kembali
                </a>
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-save me-1"></i> Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection