@extends('layouts.app')

@section('title', 'Zonas de Almacenamiento')

@section('content')

<div class="container py-4">

<div class="d-flex justify-content-between align-items-center mb-3">
    <h3>Zonas de Almacenamiento</h3>
    @can('is-admin')
    <a href="{{ route('storage_zones.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Crear Nueva Zona
    </a>
    @endcan
</div>
<p class="text-muted">Gestiona tus zonas de almacenamiento y su capacidad.</p>


<div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
    
    @forelse ($zones as $zone)
        <div class="col">
            <div class="card h-100 shadow-sm">
                <div class="card-header">
                    <h5 class="mb-0">{{ $zone->name }}</h5>
                </div>
                
                <div class="card-body d-flex flex-column">
                    <p class="small text-muted">{{ $zone->description }}</p>

                    <p class="mb-2 small">
                        <strong>Dimensiones:</strong> {{ $zone->dimension_x ?? 'N/A' }}m x {{ $zone->dimension_y ?? 'N/A' }}m
                        (<strong>Capacidad:</strong> {{ $zone->capacity_m2 ?? 0 }} m²)
                    </p>

                    @php
                        // Valores traídos desde el controlador (withSum)
                        $total_units = (int) ($zone->item_locations_sum_stored_quantity ?? 0);
                        $capacity_units = (int) ($zone->capacity_units ?? 0);

                        $occupied_m2 = (float) ($zone->item_locations_sum_occupied_m2 ?? 0);
                        $capacity_m2 = (float) ($zone->capacity_m2 ?? 0);

                        // Porcentajes
                        $unit_percentage = ($capacity_units > 0) ? ($total_units / $capacity_units) * 100 : 0;
                        $space_percentage = ($capacity_m2 > 0) ? ($occupied_m2 / $capacity_m2) * 100 : 0;

                        // Normalizamos y límites
                        $unit_percentage_display = $capacity_units > 0 ? min(100, $unit_percentage) : 0;
                        $space_percentage_display = $capacity_m2 > 0 ? min(100, $space_percentage) : 0;

                        // Colores y etiquetas según estado (UNIDADES)
                        if ($capacity_units <= 0) {
                            $unit_bar_class = 'bg-light';
                            $unit_text_class = 'text-muted';
                            $unit_status = 'Sin límite';
                        } else {
                            if ($unit_percentage > 100) {
                                $unit_bar_class = 'bg-danger progress-bar-striped progress-bar-animated';
                                $unit_text_class = 'text-danger';
                                $unit_status = 'Sobrecapacidad';
                            } elseif ($unit_percentage >= 91) {
                                $unit_bar_class = 'bg-danger';
                                $unit_text_class = 'text-danger';
                                $unit_status = 'Crítico';
                            } elseif ($unit_percentage >= 71) {
                                $unit_bar_class = 'bg-warning';
                                $unit_text_class = 'text-warning';
                                $unit_status = 'Advertencia';
                            } else {
                                $unit_bar_class = 'bg-success';
                                $unit_text_class = 'text-success';
                                $unit_status = 'Suficiente';
                            }
                        }

                        // Colores y etiquetas según estado (ESPACIO)
                        if ($capacity_m2 <= 0) {
                            $space_bar_class = 'bg-light';
                            $space_text_class = 'text-muted';
                            $space_status = 'Sin límite';
                        } else {
                            if ($space_percentage > 100) {
                                $space_bar_class = 'bg-danger progress-bar-striped progress-bar-animated';
                                $space_text_class = 'text-danger';
                                $space_status = 'Sobrecapacidad';
                            } elseif ($space_percentage >= 91) {
                                $space_bar_class = 'bg-danger';
                                $space_text_class = 'text-danger';
                                $space_status = 'Crítico';
                            } elseif ($space_percentage >= 71) {
                                $space_bar_class = 'bg-warning';
                                $space_text_class = 'text-warning';
                                $space_status = 'Advertencia';
                            } else {
                                $space_bar_class = 'bg-info';
                                $space_text_class = 'text-info';
                                $space_status = 'Suficiente';
                            }
                        }
                    @endphp

                    <!-- BARRA 1: Ocupación por UNIDADES (con badge de estado) -->
                    <div class="mb-2">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <div>
                                <label class="form-label small fw-bold mb-0"><i class="bi bi-boxes"></i> Ocupación (unidades)</label>
                                <div class="small text-muted">
                                    @if($capacity_units > 0)
                                        {{ $total_units }} / {{ $capacity_units }}
                                    @else
                                        {{ $total_units }} unidades
                                    @endif
                                </div>
                            </div>
                            <div class="text-end">
                                <span class="badge {{ $unit_text_class }}">{{ $unit_status }}</span>
                                @if($capacity_units > 0)
                                    <div class="small text-muted">{{ number_format($unit_percentage,0) }}%</div>
                                @endif
                            </div>
                        </div>

                        @if($capacity_units > 0)
                            <div class="progress mb-1" style="height: 18px;" title="{{ number_format($unit_percentage,0) }}% ({{ $total_units }} / {{ $capacity_units }})">
                                <div class="progress-bar {{ $unit_bar_class }}" role="progressbar"
                                     style="width: {{ $unit_percentage_display }}%;"
                                     aria-valuenow="{{ $unit_percentage_display }}" aria-valuemin="0" aria-valuemax="100">
                                </div>
                            </div>
                        @else
                            <div class="progress mb-1" style="height: 12px;">
                                <div class="progress-bar bg-light text-dark" style="width:100%">{{ $total_units }} unidades</div>
                            </div>
                        @endif
                    </div>

                    <!-- BARRA 2: Ocupación por ESPACIO (m²) (con badge de estado) -->
                    <div class="mb-2 mt-2">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <div>
                                <label class="form-label small fw-bold mb-0"><i class="bi bi-rulers"></i> Ocupación (m²)</label>
                                <div class="small text-muted">
                                    @if($capacity_m2 > 0)
                                        {{ number_format($occupied_m2,2) }} / {{ number_format($capacity_m2,2) }} m²
                                    @else
                                        {{ number_format($occupied_m2,2) }} m²
                                    @endif
                                </div>
                            </div>
                            <div class="text-end">
                                <span class="badge {{ $space_text_class }}">{{ $space_status }}</span>
                                @if($capacity_m2 > 0)
                                    <div class="small text-muted">{{ number_format($space_percentage,0) }}%</div>
                                @endif
                            </div>
                        </div>

                        @if($capacity_m2 > 0)
                            <div class="progress" style="height: 12px;" title="{{ number_format($space_percentage,0) }}% ({{ number_format($occupied_m2,2) }} / {{ number_format($capacity_m2,2) }} m²)">
                                <div class="progress-bar {{ $space_bar_class }}" role="progressbar"
                                     style="width: {{ $space_percentage_display }}%;"
                                     aria-valuenow="{{ $space_percentage_display }}" aria-valuemin="0" aria-valuemax="100">
                                </div>
                            </div>
                        @else
                            <small class="text-muted">No se definió capacidad en m² para esta zona.</small>
                        @endif
                    </div>
                </div>
                {{-- ------ FIN: card-body con 2 barras ------ --}}
                
                <div class="card-footer text-end bg-white border-top-0 pt-0">
                    {{-- Botón Ver Detalle --}}
                    <a href="{{ route('storage_zones.show', ['storage_zone' => $zone]) }}" class="btn btn-info btn-sm">
                        Ver Detalle
                    </a>
                    {{-- Botón Editar --}}
                    <a href="{{ route('storage_zones.edit', ['storage_zone' => $zone]) }}" class="btn btn-warning btn-sm">
                        Editar
                    </a>

                    {{-- Lógica para deshabilitar el botón Eliminar --}}
                    @php
                        $stockEnZona = $zone->item_locations_sum_stored_quantity ?? 0;
                    @endphp

                    <form action="{{ route('storage_zones.destroy', ['storage_zone' => $zone]) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')

                        <button type="submit" class="btn btn-danger btn-sm"
                            @if($stockEnZona > 0) disabled @endif
                            title="{{ $stockEnZona > 0 ? 'No se puede eliminar, la zona todavía tiene stock' : 'Eliminar esta zona' }}"
                            onclick="return confirm('¿Estás seguro de eliminar esta zona?')">
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

</div>
</div>

@endsection