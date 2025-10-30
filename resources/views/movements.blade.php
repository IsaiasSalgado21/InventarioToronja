@extends('layouts.app')

@section('title', 'Movimientos de Inventario')

@section('content')
<div class="container py-4">
    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5>Movimientos de Inventario</h5>
            <a href="#" class="btn btn-primary btn-sm disabled">Registrar Movimiento</a>
        </div>

        <div class="card-body table-responsive">
            @if($movements->isEmpty())
                <p class="text-center text-muted">No hay movimientos registrados.</p>
            @else
                <table class="table table-hover table-bordered align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Tipo</th>
                            <th>SKU Presentación</th>
                            <th>Descripción</th>
                            <th>Cantidad</th>
                            <th>Fecha</th>
                            <th>Usuario</th>
                            <th>Notas</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($movements as $m)
                            <tr>
                                <td>{{ $m->id }}</td>
                                <td>
                                    @if($m->type === 'entrada')
                                    <span class="badge bg-success">Entrada</span>
                                
                                    @elseif($m->type === 'transferencia')
                                        <span class="badge bg-info">Transferencia</span>
                                    
                                    @elseif($m->type === 'salida') {{-- Asumiendo que tendrás un tipo 'salida' --}}
                                        <span class="badge bg-danger">Salida</span>
                                    
                                    @else {{-- Si es un tipo desconocido --}}
                                        <span class="badge bg-secondary">{{ $m->type }}</span>
                                    @endif
                                </td>
                                <td>{{ $m->presentation->sku ?? '-' }}</td>
                            <td>{{ $m->presentation->description ?? '-' }}</td>
                            <td>
                                <strong class="{{ $m->type === 'entrada' ? 'text-success' : ($m->type === 'salida' ? 'text-danger' : '') }}">
                                    {{ $m->quantity }}
                                </strong>
                            </td>
                            <td>{{ \Carbon\Carbon::parse($m->movement_date)->format('d/m/Y H:i') }}</td>
                            <td>{{ $m->user->name ?? '—' }}</td>
                            <td>{{ $m->notes ?? '—' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>
</div>
@endsection
