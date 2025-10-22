@extends('layouts.app')

@section('title', 'Crear Zona de Almacenamiento')

@section('content')

<div class="container py-4">
<div class="row justify-content-center">
<div class="col-md-8">
<div class="card shadow-sm">
<div class="card-header">
<h5>Crear Nueva Zona de Almacenamiento</h5>
</div>
<div class="card-body">
<form action="{{ route('storage_zones.store') }}" method="POST">
@csrf

                    <div class="mb-3">
                        <label for="name" class="form-label">Nombre *</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Descripción</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="dimension_x" class="form-label">Dimensión X (m)</label>
                            <input type="number" step="0.01" class="form-control @error('dimension_x') is-invalid @enderror" id="dimension_x" name="dimension_x" value="{{ old('dimension_x') }}">
                            @error('dimension_x')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="dimension_y" class="form-label">Dimensión Y (m)</label>
                            <input type="number" step="0.01" class="form-control @error('dimension_y') is-invalid @enderror" id="dimension_y" name="dimension_y" value="{{ old('dimension_y') }}">
                            @error('dimension_y')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="capacity_m2" class="form-label">Capacidad Total (m²)</label>
                            <input type="number" step="0.01" class="form-control @error('capacity_m2') is-invalid @enderror" id="capacity_m2" name="capacity_m2" value="{{ old('capacity_m2') }}">
                            @error('capacity_m2')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <a href="{{ route('storage_zones.index') }}" class="btn btn-secondary">Cancelar</a>
                    <button type="submit" class="btn btn-primary">Guardar Zona</button>
                </form>
            </div>
        </div>
    </div>
</div>


</div>
@endsection