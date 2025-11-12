@extends('layouts.app')

@section('title', 'Presentaciones de Productos')

@section('content')
<div class="container py-4">
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <h6 class="card-title">Buscar y Filtrar Presentaciones</h6>
            <form action="{{ route('presentations.index') }}" method="GET" class="row g-3">
                
                <div class="col-md-4">
                    <label for="sku" class="form-label">SKU</label>
                    <input type="text" class="form-control" id="sku" name="sku" value="{{ $request->sku ?? '' }}" placeholder="Buscar SKU...">
                </div>
                
                <div class="col-md-4">
                    <label for="item_id" class="form-label">Item (Producto General)</label>
                    <select class="form-select" id="item_id" name="item_id">
                        <option value="">-- Todos los Items --</option>
                        @foreach($items as $item)
                            <option value="{{ $item->id }}" @selected($request->item_id == $item->id)>
                                {{ $item->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4">
                    <label for="archetype" class="form-label">Arquetipo / Modelo</label>
                    <input type="text" class="form-control" id="archetype" name="archetype" value="{{ $request->archetype ?? '' }}" placeholder="Ej: Clásica 11oz...">
                </div>
                
                <div class="col-md-4">
                    <label for="quality" class="form-label">Calidad</label>
                    <select class="form-select" id="quality" name="quality">
                        <option value="">-- Todas --</option>
                        @foreach($calidadesUnicas as $quality)
                            <option value="{{ $quality->quality }}" @selected($request->quality == $quality->quality)>
                                {{ $quality->quality }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div class="col-md-4">
                    <label for="stock_status" class="form-label">Estado de Stock</label>
                    <select class="form-select" id="stock_status" name="stock_status">
                        <option value="">-- Todos --</option>
                        <option value="low" @selected($request->stock_status == 'low')>Stock Bajo (<= Mínimo)</option>
                        <option value="out" @selected($request->stock_status == 'out')>Agotado (Stock 0)</option>
                    </select>
                </div>
                
                <div class="col-md-4 d-flex align-items-end">
                    <a href="{{ route('presentations.index') }}" class="btn btn-secondary me-2">Limpiar</a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-search"></i> Buscar
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>Presentaciones de Productos</h3>
        <a href="{{ route('presentations.create') }}" class="btn btn-primary">
             <i class="bi bi-plus-circle"></i> Crear Nueva Presentación
        </a>
    </div>
    <p class="text-muted">Lista de todos los tipos de "paquetes" o formatos en los que manejas tus productos (Items).</p>

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