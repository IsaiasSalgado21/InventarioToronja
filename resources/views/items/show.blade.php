@extends('layouts.app')

@section('title', 'Item Details')

@section('content')
<div class="card shadow-sm mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5>Item: {{ $item->name }}</h5>
        <a href="{{ route('items.edit', $item->id) }}" class="btn btn-warning btn-sm">Editar Item</a>
    </div>
    <div class="card-body">
        <p><strong>Descripción:</strong> {{ $item->description ?? '-' }}</p>
        <p><strong>Categoría:</strong> {{ $item->category->name ?? '-' }}</p>
        <p><strong>Proveedor:</strong> {{ $item->supplier->name ?? '-' }}</p>
        <p><strong>Clase ABC:</strong> {{ $item->abc_class ?? '-' }}</p>
        <p><strong>Fecha Expiración:</strong> {{ $item->expiry_date ?? '-' }}</p>
        <p><strong>Stock Total (todas las presentaciones):</strong> {{ $item->stock_total }}</p>
    </div>
</div>

<div class="card shadow-sm">
    <div class="card-header">
        <h5>Presentaciones de este Item</h5>
    </div>
    <div class="card-body">
        @forelse($item->presentations as $presentation)
            <div class="border rounded p-3 mb-3">
                <h6>{{ $presentation->description }} (SKU: {{ $presentation->sku }})</h6>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item d-flex justify-content-between">
                        <strong>Stock Total (esta presentación):</strong>
                        <span>{{ $presentation->stock_current }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between">
                        <strong>Stock Mínimo:</strong>
                        <span>{{ $presentation->stock_minimum }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between">
                        <strong>Precio Unitario:</strong>
                        <span>${{ $presentation->unit_price }}</span>
                    </li>
                </ul>

                <h6 class="mt-3">Ubicaciones:</h6>
                <table class="table table-sm table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th>Zona de Almacenamiento</th>
                            <th>Cantidad Guardada</th>
                            <th>m² Ocupados</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($presentation->itemLocations as $location)
                            <tr>
                                <td>{{ $location->storageZone->name ?? 'Zona Eliminada' }}</td>
                                <td>{{ $location->stored_quantity }}</td>
                                <td>{{ $location->occupied_m2 }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center">Esta presentación no tiene ubicación asignada.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        @empty
            <p class="text-center">Este item no tiene presentaciones registradas.</p>
        @endforelse
    </div>
</div>
@endsection
