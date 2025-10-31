@extends('layouts.app')

@section('title', $zone->name)

@section('content')

<div class="container py-4">

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="card shadow-sm mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <div>
            <h5 class="mb-0">{{ $zone->name }}</h5>
            <small class="text-muted">{{ $zone->description }}</small>
        </div>
        <div>
            <a href="{{ route('storage_zones.edit', $zone) }}" class="btn btn-warning">Editar</a>
            <a href="{{ route('storage_zones.index') }}" class="btn btn-secondary">Volver a la Lista</a>
        </div>
    </div>
    <div class="card-body">
        <p>Dimensiones: {{ $zone->dimension_x ?? 'N/A' }}m x {{ $zone->dimension_y ?? 'N/A' }}m</p>
        
        @php
            // El controlador debió cargar esto con loadSum()
            // Si esto falla, el error está en tu StorageZoneController@show
            $occupied = $zone->item_locations_sum_occupied_m2 ?? 0;
            $capacity = $zone->capacity_m2 ?? 1;
            $percentage = ($capacity > 0) ? ($occupied / $capacity) * 100 : 0;
            $totalUnits = $zone->item_locations_sum_stored_quantity ?? 0;

            // Valores para la barra de UNIDADES
            $capacityUnits = (int) ($zone->capacity_units ?? 0);
            $unitsPercentage = ($capacityUnits > 0) ? min(100, ($totalUnits / $capacityUnits) * 100) : 0;
            // color según porcentaje
            if ($capacityUnits <= 0) {
                $unitsBarClass = 'bg-light';
                $unitsStatus = 'Sin límite';
            } elseif ($totalUnits > $capacityUnits) {
                $unitsBarClass = 'bg-danger progress-bar-striped progress-bar-animated';
                $unitsStatus = 'Sobrecapacidad';
            } elseif ($unitsPercentage >= 91) {
                $unitsBarClass = 'bg-danger';
                $unitsStatus = 'Crítico';
            } elseif ($unitsPercentage >= 71) {
                $unitsBarClass = 'bg-warning';
                $unitsStatus = 'Advertencia';
            } else {
                $unitsBarClass = 'bg-success';
                $unitsStatus = 'Suficiente';
            }
        @endphp

        <!-- BARRA: UNIDADES (stock) -->
        <div class="mb-3">
            <label class="form-label">Stock por Unidades</label>
            @if($capacityUnits > 0)
                <div class="d-flex justify-content-between mb-1">
                    <small class="text-muted">{{ $totalUnits }} / {{ $capacityUnits }} unidades</small>
                    <small class="text-muted">{{ number_format($unitsPercentage, 0) }}%</small>
                </div>
                <div class="progress" style="height: 18px;" title="{{ number_format($unitsPercentage,0) }}% ({{ $totalUnits }} / {{ $capacityUnits }})">
                    <div class="progress-bar {{ $unitsBarClass }}" role="progressbar"
                         style="width: {{ $unitsPercentage }}%;" aria-valuenow="{{ $unitsPercentage }}" aria-valuemin="0" aria-valuemax="100">
                    </div>
                </div>
                <small class="text-muted">Estado: <strong>{{ $unitsStatus }}</strong></small>
            @else
                <p class="mb-1"><strong>{{ $totalUnits }}</strong> unidades</p>
                <small class="text-muted">No se ha definido un límite de unidades para esta zona.</small>
            @endif
        </div>

        <!-- BARRA: ESPACIO (m²) -->
        <label class="form-label">Capacidad Ocupada ({{ number_format($occupied, 2) }} / {{ number_format($capacity, 2) }} m²)</label>
        <div class="progress mb-2" style="height: 20px;">
            <div class="progress-bar" role="progressbar" style="width: {{ min(100, $percentage) }}%;" aria-valuenow="{{ $occupied }}" aria-valuemin="0" aria-valuemax="{{ $capacity }}">
                {{ number_format($percentage, 1) }}%
            </div>
        </div>
        <small class="text-muted">Total de unidades almacenadas: <strong>{{ $totalUnits }}</strong></small>

        <h6 class="mt-4">Productos en esta Zona</h6>
        <table class="table table-sm table-striped table-hover">
            <thead class="table-light">
                <tr>
                    <th>SKU</th>
                    <th>Producto (Presentación)</th>
                    <th>Item (Concepto)</th>
                    <th>Cantidad</th>
                </tr>
            </thead>
            <tbody>
                <!-- El controlador pasó la variable $locations -->
                @forelse ($locations as $location)
                    <tr>
            
                        <td>{{ $location->presentation?->sku ?? 'N/A' }}</td>
                        <td>{{ $location->presentation?->description ?? 'N/A' }}</td>
                        <td>{{ $location->presentation?->item?->name ?? 'N/A' }}</td>
                        
                        <td><strong>{{ $location->stored_quantity }}</strong></td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center text-muted">No hay productos en esta zona</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>


</div>
@endsection