@extends('layouts.app')

@section('title', 'Reporte: Historial de Precios de Venta')

@push('styles')
{{-- Estilos adicionales si fueran necesarios --}}
@endpush

@section('content')
<div class="container py-4">

    <!-- Tarjeta de Filtros -->
    <div class="card shadow-sm mb-4">
        <div class="card-header">
            <h5 class="mb-0">Reporte de Historial de Precios de Venta</h5>
        </div>
        <div class="card-body">
            <p class="text-muted small">
                Este reporte es una auditoría de todos los cambios manuales realizados al "Precio de Venta" de las presentaciones.
                Para ver el "Costo de Compra", utilice el reporte de Comparación de Costos.
            </p>

            <form action="{{ route('reports.price-history') }}" method="GET" class="row g-3">
                
                <!-- Filtro: Rango de Fechas (Inicio) -->
                <div class="col-md-3">
                    <label for="start_date" class="form-label">Desde</label>
                    <input type="date" class="form-control" id="start_date" name="start_date" value="{{ $request->start_date ?? '' }}">
                </div>
                
                <!-- Filtro: Rango de Fechas (Fin) -->
                <div class="col-md-3">
                    <label for="end_date" class="form-label">Hasta</label>
                    <input type="date" class="form-control" id="end_date" name="end_date" value="{{ $request->end_date ?? '' }}">
                </div>

                <!-- Filtro: Presentación (Producto) -->
                <div class="col-md-4">
                    <label for="presentation_id" class="form-label">Filtrar por Producto</label>
                    <select class="form-select" id="presentation_id" name="presentation_id">
                        <option value="">-- Todos los Productos --</option>
                        @foreach($presentations as $p)
                            <option value="{{ $p->id }}" @selected($request->presentation_id == $p->id)>
                                {{ $p->sku }} ({{ $p->item?->name }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Filtro: Usuario -->
                <div class="col-md-2">
                    <label for="user_id" class="form-label">Por Usuario</label>
                    <select class="form-select" id="user_id" name="user_id">
                        <option value="">-- Todos --</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" @selected($request->user_id == $user->id)>
                                {{ $user->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Botones de Acción -->
                <div class="col-12 text-end">
                    <a href="{{ route('reports.price-history') }}" class="btn btn-secondary">Limpiar</a>
                    <button type="submit" class="btn btn-primary">Filtrar</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Contenedor de la Gráfica (Solo aparece si se filtra por 1 producto) -->
    @if(isset($chartData) && $chartData)
    <div class="card shadow-sm mb-4">
        <div class="card-header">
            Evolución del Precio de Venta: <strong>{{ $historyLogs->first()->presentation->sku ?? 'Producto' }}</strong>
        </div>
        <div class="card-body">
            <canvas id="priceChart"></canvas>
        </div>
    </div>
    @else
    <div class="alert alert-info mb-4">
        Para ver la gráfica, selecciona un solo producto en el filtro "Filtrar por Producto" y asegúrate de que existan al menos 2 registros de cambios de precio (historical entries) para esa presentación.
        <div class="mt-2 small text-muted">Si solo hay 0 o 1 cambios registrados no se mostrará la gráfica.</div>
    </div>
    @endif


    <!-- Tarjeta Principal: Lista de Movimientos -->
    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Registros de Cambios de Precio</h5>
        </div>

        <div class="card-body table-responsive">
            
            @if($historyLogs->isEmpty())
                <div class="alert alert-info text-center">
                    No se encontraron cambios de precio para los filtros seleccionados.
                </div>
            @else
                <table class="table table-hover table-bordered align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Fecha de Cambio</th>
                            <th>SKU</th>
                            <th>Presentación</th>
                            <th>Usuario</th>
                            <th>Precio Antiguo</th>
                            <th>Precio Nuevo</th>
                            <th>Cambio ($)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($historyLogs as $log)
                            <tr>
                                <td>{{ $log->changed_at->format('d/m/Y H:i A') }}</td>
                                <td>{{ $log->presentation?->sku ?? 'N/A' }}</td>
                                <td>{{ $log->presentation?->description ?? $log->presentation?->item?->name ?? 'N/A' }}</td>
                                <td>{{ $log->user?->name ?? 'Sistema' }}</td>
                                
                                {{-- Precios --}}
                                <td>${{ number_format($log->old_price, 2) }}</td>
                                <td class="fw-bold">${{ number_format($log->new_price, 2) }}</td>
                                
                                {{-- Lógica para mostrar el cambio --}}
                                @php
                                    $diff = $log->new_price - $log->old_price;
                                    $color = $diff > 0 ? 'text-success' : ($diff < 0 ? 'text-danger' : 'text-muted');
                                    $icon = $diff > 0 ? 'bi-arrow-up' : ($diff < 0 ? 'bi-arrow-down' : 'bi-dash');
                                @endphp
                                <td class="{{ $color }} fw-bold">
                                    <i class="bi {{ $icon }}"></i> ${{ number_format(abs($diff), 2) }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                {{-- Paginación (con los filtros aplicados) --}}
                <div class="d-flex justify-content-center">
                    {{ $historyLogs->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<!-- Load Chart.js from CDN (simple and reliable) -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.5.1/dist/chart.umd.min.js"></script>
<script>
    // Solo ejecutar el script si el controlador nos envió datos para la gráfica
    @if(isset($chartData) && $chartData)
        document.addEventListener('DOMContentLoaded', function () {
            console.log('Chart Data:', @json($chartData)); // Debug data

            const canvas = document.getElementById('priceChart');
            if (!canvas) {
                console.error('Canvas element not found');
                return;
            }

            const ctx = canvas.getContext('2d');
            if (!ctx) {
                console.error('Canvas 2D context not available');
                return;
            }

            // Convertir los datos de PHP (Blade) a JSON (JavaScript)
            const chartLabels = @json($chartData['labels']);
            const chartValues = @json($chartData['data']);

            // Crear la gráfica básica
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: chartLabels,
                    datasets: [{
                        label: 'Precio de Venta Histórico',
                        data: chartValues,
                        fill: false,
                        borderColor: 'rgb(54, 162, 235)',
                        tension: 0.1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false
                }
            });
        });
    @else
        // No hay datos para la gráfica
        console.log('No chartData provided');
    @endif

    // Quick check: show Chart constructor in console (shouldn't be undefined)
    console.log('Chart available:', typeof Chart !== 'undefined');
</script>
@endpush