@extends('layouts.app')

@section('title', 'Items')

@section('content')
<div class="container py-4">
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <h6 class="card-title">Buscar y Filtrar Items</h6>
            <form action="{{ route('items.index') }}" method="GET" class="row g-3 align-items-end">
                
                <div class="col-md-3">
                    <label for="name" class="form-label">Nombre del Item</label>
                    <input type="text" class="form-control" id="name" name="name" value="{{ $request->name ?? '' }}" placeholder="Ej: Taza, Playera...">
                </div>

                <div class="col-md-3">
                    <label for="category_id" class="form-label">Categoría</label>
                    <select class="form-select" id="category_id" name="category_id">
                        <option value="">-- Todas --</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" @selected($request->category_id == $category->id)>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div class="col-md-3">
                    <label for="arquetipo" class="form-label">Arquetipo / Modelo</label>
                    <input type="text" class="form-control" id="arquetipo" name="arquetipo" value="{{ $request->arquetipo ?? '' }}" placeholder="Ej: Clásica 11oz, Yazbek...">
                </div>
                
                <div class="col-md-3">
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

                <div class="col-12 text-end">
                    <a href="{{ route('items.index') }}" class="btn btn-secondary">Limpiar</a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-search"></i> Buscar
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4>Items (Conceptos de Producto)</h4>
        <a href="{{ route('items.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Crear Nuevo Item
        </a>
    </div>

    
    <div class="card shadow-sm">
        <div class="card-body table-responsive">
            <table class="table table-striped table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Nombre (Concepto)</th>
                        <th>Categoría</th>
                        <th>Clase ABC</th>
                        <th>Fecha Exp. (Referencia)</th>
                        <th>Stock Total (Unidades)</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($items as $it)
                    <tr>
                        <td>{{ $it->id }}</td>
                        <td>
                            <a href="{{ route('items.show', $it) }}">{{ $it->name }}</a>
                        </td>
                        <td>{{ $it->category?->name ?? '-' }}</td>
                        <td>{{ $it->abc_class ?? '-' }}</td>
                        <td>{{ $it->expiry_date ? \Carbon\Carbon::parse($it->expiry_date)->format('Y-m-d') : '-' }}</td>
                        <td><strong>{{ $it->stock_total }}</strong></td>
                        <td>
                            <a href="{{ route('items.show', ['item' => $it]) }}" class="btn btn-sm btn-info" title="Ver"> Ver
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('items.edit', ['item' => $it]) }}" class="btn btn-sm btn-warning" title="Editar"> Editar
                                <i class="bi bi-pencil"></i>
                            </a>

                            <form action="{{ route('items.destroy', ['item' => $it]) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Eliminar este Item? Esto borrará lógicamente el concepto.');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" title="Eliminar">Eliminar
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted">No se encontraron items.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
            
            <div class="d-flex justify-content-center">
            </div>
            
        </div>
    </div>
</div>
@endsection