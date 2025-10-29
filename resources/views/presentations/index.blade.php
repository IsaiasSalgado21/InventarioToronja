@extends('layouts.app')

@section('title', 'Presentaciones de Productos')

@section('content')
<div class="container py-4">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>Presentaciones de Productos</h3>
        <a href="{{ route('presentations.create') }}" class="btn btn-primary">
             <i class="bi bi-plus-circle"></i> Crear Nueva Presentación
        </a>
    </div>
    <p class="text-muted">Lista de todos los tipos de "paquetes" o formatos en los que manejas tus productos (Items).</p>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>SKU</th>
                        <th>Descripción</th>
                        <th>Item Asociado</th>
                        <th>Stock Actual Total</th>
                        <th>Stock Mínimo</th>
                        <th>Precio Unitario</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    {{-- La variable $presentations viene del PresentationController@index --}}
                    @forelse ($presentations as $presentation)
                        <tr class="{{ $presentation->stock_current <= $presentation->stock_minimum ? 'table-danger' : '' }}">
                            <td>{{ $presentation->id }}</td>
                            <td>{{ $presentation->sku }}</td>
                            <td>{{ $presentation->description }}</td>
                            <td>{{ $presentation->item?->name ?? 'N/A' }}</td> {{-- Accede al nombre del Item relacionado --}}
                            <td><strong>{{ $presentation->stock_current }}</strong></td>
                            <td>{{ $presentation->stock_minimum }}</td>
                            <td>${{ number_format($presentation->unit_price, 2) }}</td>
                            <td>
                                {{-- Botón Ver Detalle --}}
                                <a href="{{ route('presentations.show', $presentation) }}" class="btn btn-info btn-sm" title="Ver Detalles">
                                    <i class="bi bi-eye"></i> Ver
                                </a>
                                {{-- Botón Editar --}}
                                <a href="{{ route('presentations.edit', $presentation) }}" class="btn btn-warning btn-sm" title="Editar">
                                    <i class="bi bi-pencil"></i> Editar
                                </a>
                                {{-- Botón Eliminar (con formulario) --}}
                                <form action="{{ route('presentations.destroy', $presentation) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Estás seguro de eliminar esta presentación? Esto es un borrado lógico.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" title="Eliminar">
                                        <i class="bi bi-trash"></i> Eliminar
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted">No hay presentaciones registradas.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            {{-- Links de Paginación --}}
            <div class="d-flex justify-content-center">
                {{ $presentations->links() }}
            </div>
        </div>
    </div>
</div>
@endsection