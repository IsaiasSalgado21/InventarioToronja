@extends('layouts.app')

@section('title', 'Inventario')

@section('content')

<div class="container-fluid py-4">

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif
@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="card shadow-sm">
    <div class="card-header d-flex flex-wrap justify-content-between align-items-center">
        <div>
            <h3 class="mb-0">Inventario General</h3>
            <p class="text-muted mb-0">Vista detallada de cada producto en cada zona de almacenamiento.</p>
        </div>
        <div class="mt-2 mt-md-0">
            <a href="{{ route('inventory.receive.form') }}" class="btn btn-success me-2">
                <i class="bi bi-plus-circle"></i> Recibir Stock
            </a>
            <a href="{{ route('inventory.transfer.form') }}" class="btn btn-info me-2">
                <i class="bi bi-arrow-left-right"></i> Transferir Stock
            </a>
            <a href="{{ route('presentations.create') }}" class="btn btn-primary">
                Crear Presentación
            </a>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle mt-4">
                <thead class="table-dark">
                    <tr>
                        <th>SKU</th>
                        <th>Presentación (Producto)</th>
                        <th>Item (Concepto)</th>
                        <th>Categoría</th>
                        <th>Proveedor</th>
                        <th>Zona Almacenada</th>
                        <th>Stock en Zona</th>
                    </tr>
                </thead>
                <tbody>
                    
                    @forelse ($locations as $location)
                    
                        <tr>
                            <td>{{ $location->presentation?->sku ?? 'N/A' }}</td>
                            <td>{{ $location->presentation?->description ?? $location->presentation?->item?->name ?? 'N/A' }}</td>
                            <td>{{ $location->presentation?->item?->name ?? 'N/A' }}</td>
                            <td>{{ $location->presentation?->item?->category?->name ?? '-' }}</td>
                            <td>{{ $location->presentation?->item?->supplier?->name ?? '-' }}</td>
                            <td>
                                <a href="{{ route('storage_zones.show', $location->storageZone) }}">
                                    {{ $location->storageZone?->name ?? 'N/A' }}
                                </a>
                            </td>
                            <td>
                                <strong>{{ $location->stored_quantity }}</strong>
                            </td>
                            <!-- <td>
                                <a href="#" class="btn btn-sm btn-light">Mover</a>
                            </td> -->
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted">No hay items registrados en ninguna zona de almacenamiento.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-center">
            {{ $locations->links() }}
        </div>
        
    </div>
</div>


</div>
@endsection