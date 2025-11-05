@extends('layouts.app')

@section('title', 'Reporte: Comparación de Precios')

@section('content')
<div class="container py-4">

    <!-- Encabezado y Filtro -->
    <div class="card shadow-sm mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Reporte de Comparación de Costos por Proveedor</h5>
        </div>
        <div class="card-body">
            <p class="text-muted">Este reporte analiza el historial de "Recibir Stock" (movimientos de entrada) para mostrar qué proveedor ofrece el mejor costo promedio por cada producto.</p>
            
            <form action="{{ route('reports.price-comparison') }}" method="GET" class="row g-3 align-items-end">
                <div class="col-md-8">
                    <label for="presentation_id" class="form-label">Filtrar por Producto (Presentación)</label>
                    <select class="form-select" id="presentation_id" name="presentation_id">
                        <option value="">-- Todos los Productos --</option>
                        @foreach($presentations as $presentation)
                            <option value="{{ $presentation->id }}" @selected($selectedPresentationId == $presentation->id)>
                                {{ $presentation->item?->name }} ({{ $presentation->sku }}) - {{ $presentation->description }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-primary">Filtrar</button>
                    <a href="{{ route('reports.price-comparison') }}" class="btn btn-secondary">Limpiar</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabla de Resultados -->
    <div class="card shadow-sm">
        <div class="card-body table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th colspan="3">Producto</th>
                        <th colspan="5" class="text-center border-start">Datos de Compra</th>
                    </tr>
                    <tr>
                        <th>SKU</th>
                        <th>Presentación</th>
                        <th>Item</th>
                        <th class="border-start">Proveedor</th>
                        <th>Costo Promedio</th>
                        <th>Costo Mínimo</th>
                        <th>Costo Máximo</th>
                        <th>Nº Compras</th>
                        <th>Última Compra</th>
                    </tr>
                </thead>
                <tbody>
                    @php $currentPresentationId = null; @endphp

                    @forelse ($comparisonData as $data)
                        @php
                            $isBestPriceRow = false;
                            // Si el ID de presentación es diferente al anterior, esta es la primera fila
                            // Y como está ordenado por 'avg_cost', esta es la fila con el mejor precio.
                            if ($data->presentation_id != $currentPresentationId) {
                                $isBestPriceRow = true;
                                $currentPresentationId = $data->presentation_id;
                            }
                        @endphp
                        
                        {{-- Resalta la fila con el mejor precio (costo promedio más bajo) para ese producto --}}
                        <tr class="{{ $isBestPriceRow ? 'table-success' : '' }}">
                            <td>{{ $data->presentation?->sku ?? 'N/A' }}</td>
                            <td>{{ $data->presentation?->description ?? 'N/A' }}</td>
                            <td>{{ $data->presentation?->item?->name ?? 'N/A' }}</td>
                            
                            <td class="border-start">
                                <strong>{{ $data->supplier?->name ?? 'N/A' }}</strong>
                                @if($isBestPriceRow)
                                    <span class="badge bg-success ms-2">Mejor Opción</span>
                                @endif
                            </td>
                            
                            {{-- Costos --}}
                            <td class="fw-bold">${{ number_format($data->avg_cost, 2) }}</td>
                            <td>${{ number_format($data->min_cost, 2) }}</td>
                            <td>${{ number_format($data->max_cost, 2) }}</td>
                            
                            {{-- Datos de Compra --}}
                            <td>{{ $data->purchase_count }}</td>
                            <td>{{ $data->last_purchase_date ? \Carbon\Carbon::parse($data->last_purchase_date)->format('d/m/Y') : 'N/A' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center text-muted">
                                No se encontraron datos para el filtro seleccionado. Asegúrese de registrar las entradas de stock con su costo y proveedor.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection