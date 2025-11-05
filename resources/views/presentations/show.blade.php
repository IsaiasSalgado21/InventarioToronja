@extends('layouts.app')

@section('title', 'Detalle: ' . $presentation->sku)

@section('content')
<div class="container py-4">

    <!-- Notificación de Éxito -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Tarjeta Principal: Detalles de la Presentación -->
    <div class="card shadow-sm mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div>
                <h5 class="mb-0">Presentación: {{ $presentation->description }}</h5>
                <small class="text-muted">SKU: {{ $presentation->sku }}</small>
            </div>
            <div>
                <a href="{{ route('presentations.edit', ['presentation' => $presentation]) }}" class="btn btn-warning">
                    <i class="bi bi-pencil"></i> Editar
                </a>
                <a href="{{ route('presentations.index') }}" class="btn btn-secondary">Volver a la Lista</a>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <!-- Columna 1: Detalles del Producto -->
                <div class="col-md-6">
                    <h6 class="text-muted">Información del Producto</h6>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Item (Concepto):
                            <a href="{{ route('items.show', $presentation->item_id) }}">{{ $presentation->item?->name ?? 'N/A' }}</a>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Categoría:
                            <span>{{ $presentation->item?->category?->name ?? 'N/A' }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Arquetipo / Modelo:
                            <span class="fw-bold">{{ $presentation->archetype ?? 'N/A' }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Calidad:
                            <span class="fw-bold">{{ $presentation->quality ?? 'N/A' }}</span>
                        </li>
                    </ul>
                </div>
                <!-- Columna 2: Detalles Logísticos y de Precio -->
                <div class="col-md-6">
                    <h6 class="text-muted">Información Logística y de Venta</h6>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Precio de Venta (Actual):
                            <span class="fw-bold text-success">${{ number_format($presentation->unit_price, 2) }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Unidad Base:
                            <span>{{ $presentation->base_unit ?? 'N/A' }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Unidades por Presentación:
                            <span>{{ $presentation->units_per_presentation }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Espacio por Unidad (opcional):
                            <span>{{ $presentation->m2_per_unit ?? 0 }} m²</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Tarjeta Secundaria: Inventario y Ubicaciones -->
    <div class="card shadow-sm">
        <div class="card-header">
            <h5 class="mb-0">Inventario y Ubicaciones</h5>
        </div>
        <div class="card-body">
            <h6 class="text-muted">Resumen de Stock</h6>
            <div class="row align-items-center">
                <div class="col-md-3">
                    <div class="display-4 fw-bold">{{ $presentation->stock_current }}</div>
                    <div class="text-muted">Stock Total Actual</div>
                </div>
                <div class="col-md-9">
                    @php
                        $stock = $presentation->stock_current;
                        $min_stock = $presentation->stock_minimum;
                        $percentage = 0;
                        $bar_color = 'bg-success'; // Verde

                        if ($min_stock > 0) {
                            $percentage = ($stock / $min_stock) * 100;
                            if ($percentage > 100) $percentage = 100; // Tope
                            
                            if ($percentage <= 25) {
                                $bar_color = 'bg-danger'; // Rojo
                            } elseif ($percentage <= 50) {
                                $bar_color = 'bg-warning'; // Amarillo
                            }
                        } elseif ($stock > 0) {
                             $percentage = 100; // Si hay stock pero no mínimo, se considera "lleno"
                        } else {
                            $percentage = 0; // Si no hay stock ni mínimo
                            $bar_color = 'bg-secondary';
                        }
                    @endphp
                    <label class="form-label small">Nivel de Stock vs. Mínimo ({{ $min_stock }} unidades)</label>
                    <div class="progress" style="height: 20px;" title="Stock: {{ $stock }} / Mínimo: {{ $min_stock }}">
                        <div class="progress-bar {{ $bar_color }}" role="progressbar" style="width: {{ $percentage }}%;">
                            {{ $stock }} unidades
                        </div>
                    </div>
                    @if($stock <= $min_stock && $min_stock > 0)
                        <div class="alert alert-danger small p-2 mt-2">
                            ¡Stock bajo! El stock actual ({{ $stock }}) está por debajo del mínimo ({{ $min_stock }}).
                        </div>
                    @endif
                </div>
            </div>
            
            <hr>

            <h6 class="text-muted mt-4">Ubicaciones Físicas</h6>
            <div class="table-responsive">
                <table class="table table-sm table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Zona de Almacenamiento</th>
                            <th>Cantidad en Zona</th>
                            <th>Espacio Ocupado (m²)</th>
                            <th>Fecha Asignación</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- El controlador cargó 'itemLocations.storageZone' --}}
                        @forelse($presentation->itemLocations as $location)
                            <tr>
                                <td>
                                    {{-- Corregido para pasar el parámetro de ruta explícito --}}
                                    <a href="{{ route('storage_zones.show', ['storage_zone' => $location->storageZone]) }}">
                                        {{ $location->storageZone?->name ?? 'N/A' }}
                                    </a>
                                </td>
                                <td><strong>{{ $location->stored_quantity }}</strong></td>
                                <td>{{ number_format($location->occupied_m2, 2) }} m²</td>
                                <td>{{ $location->assigned_at ? \Carbon\Carbon::parse($location->assigned_at)->format('d/m/Y') : 'N/A' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted">Esta presentación no tiene stock asignado a ninguna zona.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>
@endsection