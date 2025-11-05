@extends('layouts.app')

@section('title', 'Reporte: Análisis de Márgenes')

@section('content')
<div class="container py-4">
    <div class="card shadow-sm mb-4">
        <div class="card-header">
            <h5 class="mb-0">Reporte de Análisis de Márgenes de Ganancia</h5>
        </div>
        <div class="card-body">
            <p class="text-muted">Compara el Precio de Venta actual de cada producto con su Costo Promedio de compra para estimar la rentabilidad.</p>

            <!-- Tarjetas de Resumen -->
            <div class="row mb-3">
                <div class="col-md-4">
                    <div class="card text-white bg-success">
                        <div class="card-body">
                            <h6 class="card-title">Valor Total de Venta (Stock Actual)</h6>
                            <h4>${{ number_format($totalValorVenta, 2) }}</h4>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card text-white bg-danger">
                        <div class="card-body">
                            <h6 class="card-title">Valor Total de Costo (Stock Actual)</h6>
                            <h4>${{ number_format($totalValorCosto, 2) }}</h4>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card text-white bg-primary">
                        <div class="card-body">
                            <h6 class="card-title">Ganancia Potencial Total</h6>
                            <h4>${{ number_format($totalGananciaPotencial, 2) }}</h4>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Fin Tarjetas -->

        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>SKU</th>
                        <th>Presentación</th>
                        <th>Stock Actual</th>
                        <th>Precio Venta</th>
                        <th>Costo Promedio</th>
                        <th>Ganancia (Unidad)</th>
                        <th>Margen (%)</th>
                        <th>Valor Venta (Total)</th>
                        <th>Valor Costo (Total)</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($presentations as $p)
                        <tr>
                            <td>{{ $p->sku }}</td>
                            <td>{{ $p->description ?? $p->item?->name }}</td>
                            <td><strong>{{ $p->stock_current }}</strong></td>

                            {{-- Precio de Venta --}}
                            <td class_text-success fw-bold">${{ number_format($p->unit_price, 2) }}</td>

                            {{-- Costo Promedio --}}
                            <td class_text-danger">${{ number_format($p->inventory_movements_avg_unit_cost, 2) }}</td>

                            {{-- Ganancia por Unidad --}}
                            <td class_text-primary fw-bold">${{ number_format($p->ganancia_por_unidad, 2) }}</td>

                            {{-- Margen % --}}
                            <td>
                                @php $color = $p->margen_porcentaje > 20 ? 'text-success' : 'text-warning'; @endphp
                                <span class="{{ $color }}">{{ number_format($p->margen_porcentaje, 1) }}%</span>
                            </td>

                            {{-- Totales --}}
                            <td>${{ number_format($p->valor_total_venta, 2) }}</td>
                            <td>${{ number_format($p->valor_total_costo, 2) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center text-muted">No hay datos suficientes para calcular márgenes.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="d-flex justify-content-center">
                {{ $presentations->links() }}
            </div>
        </div>
    </div>
</div>
@endsection