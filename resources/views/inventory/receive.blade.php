@extends('layouts.app')

@section('title', 'Recibir Stock')

@section('content')

<div class="container py-4">
<div class="row justify-content-center">
<div class="col-md-8">
<div class="card shadow-sm">
<div class="card-header">
<h5>Registrar Entrada de Stock</h5>
</div>
<div class="card-body">

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <h6 class="alert-heading">Por favor corrige los siguientes errores:</h6>
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                
                @if(session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif

                <form action="{{ route('inventory.receive.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label for="presentation_id" class="form-label">Producto (Presentación) *</label>
                        <select class="form-select @error('presentation_id') is-invalid @enderror" id="presentation_id" name="presentation_id" required>
                            <option value="" disabled selected>-- Seleccione un producto --</option>
                            
                            @foreach($presentations as $presentation)
                                <option value="{{ $presentation->id }}" {{ old('presentation_id') == $presentation->id ? 'selected' : '' }}>
                                    {{ $presentation->item?->name ?? 'N/A' }} ({{ $presentation->sku ?? 'SKU' }}) - {{ $presentation->description }}
                                </option>
                            @endforeach
                        </select>
                        @error('presentation_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="storage_zone_id" class="form-label">Zona de Almacenamiento (Destino) *</label>
                        <select class="form-select @error('storage_zone_id') is-invalid @enderror" id="storage_zone_id" name="storage_zone_id" required>
                            <option value="" disabled selected>-- Seleccione una zona --</option>
                            @foreach($storageZones as $zone)
                                <option value="{{ $zone->id }}" {{ old('storage_zone_id') == $zone->id ? 'selected' : '' }}>
                                    {{ $zone->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('storage_zone_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="supplier_id" class="form-label">Proveedor *</label>
                        <select class="form-select @error('supplier_id') is-invalid @enderror" id="supplier_id" name="supplier_id" required>
                            <option value="" disabled selected>-- Seleccione un proveedor --</option>
                            @foreach(App\Models\Supplier::orderBy('name')->get() as $supplier)
                                <option value="{{ $supplier->id }}" {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>
                                    {{ $supplier->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('supplier_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="quantity" class="form-label">Cantidad Recibida *</label>
                        <input type="number" class="form-control @error('quantity') is-invalid @enderror" id="quantity" name="quantity" value="{{ old('quantity') }}" min="1" required>
                        @error('quantity')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="total_cost" class="form-label">Costo Total del Lote *</label>
                        <input type="number" step="0.01" class="form-control @error('total_cost') is-invalid @enderror" id="total_cost" name="total_cost" value="{{ old('total_cost') }}" min="0" required>
                        @error('total_cost')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="unit_cost" class="form-label">Costo Unitario (calculado automáticamente)</label>
                        <input type="number" step="0.01" class="form-control" id="unit_cost" disabled>
                    </div>
                    
                    <div class="mb-3">
                        <label for="notes" class="form-label">Notas (Opcional)</label>
                        <textarea class="form-control @error('notes') is-invalid @enderror" id="notes" name="notes" rows="3">{{ old('notes') }}</textarea>
                        @error('notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="text-end">
                        <a href="{{ route('inventory') }}" class="btn btn-secondary">Cancelar</a>
                        <button type="submit" class="btn btn-success">Registrar Entrada</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const quantityInput = document.getElementById('quantity');
    const totalCostInput = document.getElementById('total_cost');
    const unitCostInput = document.getElementById('unit_cost');

    function calculateUnitCost() {
        const quantity = parseFloat(quantityInput.value) || 0;
        const totalCost = parseFloat(totalCostInput.value) || 0;
        
        if (quantity > 0 && totalCost > 0) {
            const unitCost = totalCost / quantity;
            unitCostInput.value = unitCost.toFixed(2);
        } else {
            unitCostInput.value = '';
        }
    }

    quantityInput.addEventListener('input', calculateUnitCost);
    totalCostInput.addEventListener('input', calculateUnitCost);
});
</script>
@endpush