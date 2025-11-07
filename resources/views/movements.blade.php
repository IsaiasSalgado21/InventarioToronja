@extends('layouts.app')

@section('title', 'Movimientos de Inventario')

@section('content')
<div class="container py-4">

    <!-- Tarjeta de Filtros -->
    <div class="card shadow-sm mb-4">
        <div class="card-header">
            <h5>Filtrar Movimientos</h5>
        </div>
        <div class="card-body">
            {{-- El formulario usa GET para que los filtros se muestren en la URL --}}
            <form action="{{ route('movements') }}" method="GET" class="row g-3">
                
                <!-- Filtro: Rango de Fechas (Inicio) -->
                <div class="col-md-3">
                    <label for="start_date" class="form-label">Desde</label>
                    <input type="date" class="form-control" id="start_date" name="start_date" value="{{ $request->start_date }}">
                </div>
                
                <!-- Filtro: Rango de Fechas (Fin) -->
                <div class="col-md-3">
                    <label for="end_date" class="form-label">Hasta</label>
                    <input type="date" class="form-control" id="end_date" name="end_date" value="{{ $request->end_date }}">
                </div>

                <!-- Filtro: Tipo de Movimiento -->
                <div class="col-md-3">
                    <label for="type" class="form-label">Tipo de Movimiento</label>
                    <select class="form-select" id="type" name="type">
                        <option value="">-- Todos los Tipos --</option>
                        @foreach($types as $type)
                            <option value="{{ $type }}" @selected($request->type == $type)>
                                {{-- Capitaliza el nombre para que se vea bien --}}
                                {{ Str::ucfirst($type) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Filtro: Presentación (Producto) -->
                <div class="col-md-3">
                    <label for="presentation_id" class="form-label">Producto</label>
                    <select class="form-select" id="presentation_id" name="presentation_id">
                        <option value="">-- Todos los Productos --</option>
                        @foreach($presentations as $p)
                            <option value="{{ $p->id }}" @selected($request->presentation_id == $p->id)>
                                {{ $p->sku }} ({{ $p->item?->name }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Botones de Acción -->
                <div class="col-12 text-end">
                    <a href="{{ route('movements') }}" class="btn btn-secondary">Limpiar Filtros</a>
                    <button type="submit" class="btn btn-primary">Filtrar</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Tarjetas de Resumen (basado en los filtros aplicados) -->
    <div class="row mb-4 g-3">
        <div class="col-md-3">
            <div class="card text-white bg-danger">
                <div class="card-body">
                    <h6 class="card-title">Costo Total de Mermas</h6>
                    <h4>${{ number_format($totalLossCost, 2) }}</h4>
                    <small>{{ $totalLossUnits }} unidades perdidas</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-primary">
                <div class="card-body">
                    <h6 class="card-title">Costo de Ventas (COGS)</h6>
                    <h4>${{ number_format($totalSalesCost, 2) }}</h4>
                    <small>{{ $totalSalesUnits }} unidades vendidas</small>
                </div>
            </div>
        </div>
        <!-- Puedes añadir más tarjetas si lo deseas, ej. Total Gasto en Compras -->
    </div>


    <!-- Tarjeta Principal: Lista de Movimientos -->
    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5>Historial de Movimientos</h5>
            {{-- El botón de "Registrar Movimiento" se quita, ya que se hace desde Inventario --}}
        </div>

        <div class="card-body table-responsive">
            
            @if($movements->isEmpty())
                <div class="alert alert-info text-center">
                    No se encontraron movimientos para los filtros seleccionados.
                </div>
            @else
                <table class="table table-hover table-bordered align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Fecha</th>
                            <th>Tipo</th>
                            <th>SKU</th>
                            <th>Producto</th>
                            <th>Cantidad</th>
                            <th>Costo Unit.</th>
                            <th>Costo Total</th>
                            <th>Proveedor</th>
                            <th>Usuario</th>
                            <th>Notas</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($movements as $m)
                            <tr>
                                <td>{{ $m->id }}</td>
                                <td>{{ \Carbon\Carbon::parse($m->movement_date)->format('d/m/Y H:i') }}</td>
                                <td>
                                    @php
                                        // Lógica de badges de color por tipo
                                        $badge_color = 'bg-secondary'; // Default
                                        $type_name = Str::ucfirst($m->type);
                                        
                                        if ($m->type == 'entrada') {
                                            $badge_color = 'bg-success';
                                        } elseif ($m->type == 'transferencia') {
                                            $badge_color = 'bg-info';
                                        } elseif (in_array($m->type, ['venta', 'salida'])) {
                                            // 'salida' es sinónimo de venta en algunos seeders/procesos
                                            $badge_color = 'bg-primary';
                                            $type_name = 'Venta';
                                        } elseif (in_array($m->type, ['caducado', 'ajuste_salida', 'merma_recepcion', 'merma', 'otro'])) {
                                            $badge_color = 'bg-danger';
                                            $type_name = 'Merma (' . $type_name . ')';
                                        }
                                    @endphp
                                    <span class="badge {{ $badge_color }}">{{ $type_name }}</span>
                                </td>
                                <td>{{ $m->presentation?->sku ?? '-' }}</td>
                                <td>{{ $m->presentation?->item?->name ?? 'N/A' }} - {{ $m->presentation?->description ?? '-' }}</td>
                                <td>
                                    @php
                                        // Lógica de color y signo para la cantidad
                                        $quantity_color = 'text-dark';
                                        $quantity_prefix = '';
                                        
                                        if ($m->type == 'entrada') {
                                            $quantity_color = 'text-success';
                                            $quantity_prefix = '+';
                                        } elseif (in_array($m->type, ['venta', 'salida', 'caducado', 'ajuste_salida', 'merma_recepcion', 'merma', 'otro'])) {
                                            $quantity_color = 'text-danger';
                                            $quantity_prefix = '-';
                                        }
                                        // 'transferencia' se queda neutral
                                    @endphp
                                    <strong class="{{ $quantity_color }}">
                                        {{ $quantity_prefix }}{{ $m->quantity }}
                                    </strong>
                                </td>
                                <td>${{ number_format($m->unit_cost, 2) }}</td>
                                <td>${{ number_format($m->unit_cost * $m->quantity, 2) }}</td>
                                <td>{{ $m->supplier?->name ?? '—' }}</td> {{-- Columna de Proveedor --}}
                                <td>{{ $m->user?->name ?? '—' }}</td>
                                <td>{{ $m->notes ?? '—' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                {{-- Paginación (con los filtros aplicados) --}}
                <div class="d-flex justify-content-center">
                    {{ $movements->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection