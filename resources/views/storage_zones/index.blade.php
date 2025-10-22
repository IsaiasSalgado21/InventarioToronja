@extends('layouts.app')

@section('title', 'Zonas de Almacenamiento')

@section('content')
<div class="container py-4">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>Zonas de Almacenamiento</h3>
        <a href="{{ route('storage_zones.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Crear Nueva Zona
        </a>
    </div>
    <p class="text-muted">Gestiona tus zonas de almacenamiento y su capacidad.</p>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
        
        @forelse ($zones as $zone)
            <div class="col">
                <div class="card h-100 shadow-sm">
                    <div class="card-header">
                        <h5 class="mb-0">{{ $zone->name }}</h5>
                    </div>
                    <div class="card-body">
                        <p class="small text-muted">{{ $zone->description }}</p>
                        <p class="mb-1">
                            <strong>Dimensiones:</strong> {{ $zone->dimension_x ?? 'N/A' }}m x {{ $zone->dimension_y ?? 'N/A' }}m
                        </p>
                        <p>
                            <strong>Capacidad:</strong> {{ $zone->capacity_m2 ?? 'N/A' }} m²
                        </p>
                        
                        @php
                            $occupied = $zone->item_locations_sum_occupied_m2 ?? 0;
                            $capacity = $zone->capacity_m2 ?? 1; // Evita división por cero
                            $percentage = ($capacity > 0) ? ($occupied / $capacity) * 100 : 0;
                        @endphp
                        
                        <label class="form-label small">Capacidad Ocupada ({{ number_format($percentage, 0) }}%)</label>
                        <div class="progress" style="height: 15px;">
                            <div class="progress-bar" role="progressbar" style="width: {{ $percentage }}%;"></div>
                        </div>
                    </div>
                    
                    <div class="card-footer text-end bg-white border-top-0 pt-0">
                        <a href="{{ route('storage_zones.show', $zone) }}" class="btn btn-info btn-sm">
                            Ver Detalle
                        </a>
                        <a href="{{ route('storage_zones.edit', $zone) }}" class="btn btn-warning btn-sm">
                            Editar
                        </a>
                        <form action="{{ route('storage_zones.destroy', $zone) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro?')">
                                Eliminar
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info">
                    No hay zonas registradas.
                    <a href="{{ route('storage_zones.create') }}">¡Crea la primera!</a>
                </div>
            </div>
        @endforelse

    </div> </div>
@endsection