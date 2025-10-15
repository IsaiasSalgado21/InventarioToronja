@extends('layouts.app')

@section('title', 'Inventory')

@section('content')
<div class="card shadow-sm">
    <div class="card-body">
        <h3 class="mb-3">Inventory Overview</h3>
        <p class="text-muted">Visualiza todas las presentaciones de productos, su ubicación y cantidad en stock.</p>

        <table class="table table-hover align-middle mt-4">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>SKU</th>
                    <th>Producto</th>
                    <th>Categoría</th>
                    <th>Proveedor</th>
                    <th>Zona de Almacenamiento</th>
                    <th>Stock Actual</th>
                    <th>Stock Mínimo</th>
                    <th>Ocupado (m²)</th>
                    <th>Precio Unitario</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($inventory as $item)
                    <tr class="{{ $item->stock_current <= $item->stock_minimum ? 'table-danger' : '' }}">
                        <td>{{ $item->id }}</td>
                        <td>{{ $item->sku }}</td>
                        <td>{{ $item->item_name }}</td>
                        <td>{{ $item->category_name ?? '-' }}</td>
                        <td>{{ $item->supplier_name ?? '-' }}</td>
                        <td>{{ $item->storage_zone ?? 'No asignada' }}</td>
                        <td>{{ $item->stock_current }}</td>
                        <td>{{ $item->stock_minimum }}</td>
                        <td>{{ $item->occupied_m2 ?? '0.00' }}</td>
                        <td>${{ number_format($item->unit_price, 2) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="10" class="text-center text-muted">No hay datos en el inventario.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
