@extends('layouts.app')

@section('title', 'Registrar Salida de Stock')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h5>Registrar Salida de Stock</h5>
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

                    <form action="{{ route('inventory.remove.store') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label for="presentation_id" class="form-label">Producto (Presentación) *</label>
                            <select class="form-select @error('presentation_id') is-invalid @enderror" id="presentation_id" name="presentation_id" required>
                                <option value="" disabled selected>-- Seleccione un producto --</option>
                                {{-- $presentations viene del InventoryController@showRemoveForm --}}
                                @foreach($presentations as $presentation)
                                    <option value="{{ $presentation->id }}" {{ old('presentation_id') == $presentation->id ? 'selected' : '' }}>
                                        {{ $presentation->item?->name ?? 'N/A' }} ({{ $presentation->sku ?? 'SKU' }}) - {{ $presentation->description }}
                                    </option>
                                @endforeach
                            </select>
                            <small class="text-muted">Solo se muestran productos con stock disponible.</small>
                            @error('presentation_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror {{-- <-- ¡CORREGIDO! Antes decía @endZonaselect --}}
                        </div>

                        <div class="mb-3">
                            <label for="storage_zone_id" class="form-label">Zona de Almacenamiento (Origen) *</label>
                            <select class="form-select @error('storage_zone_id') is-invalid @enderror" id="storage_zone_id" name="storage_zone_id" required>
                                <option value="" disabled selected>-- Seleccione una zona --</option>
                                {{-- $storageZones viene del InventoryController@showRemoveForm --}}
                                @foreach($storageZones as $zone)
                                    <option value="{{ $zone->id }}" {{ old('storage_zone_id') == $zone->id ? 'selected' : '' }}>
                                        {{ $zone->name }}
                                    </option>
                                @endforeach
                            </select>
                            <small class="text-muted">¿De dónde estás sacando el producto?</small>
                            @error('storage_zone_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- CAMPO NUEVO Y MUY IMPORTANTE --}}
                        <div class="mb-3">
                            <label for="type" class="form-label">Motivo / Tipo de Salida *</label>
                            <select class="form-select @error('type') is-invalid @enderror" id="type" name="type" required>
                                <option value="" disabled selected>-- Seleccione un motivo --</option>
                                <option value="venta" {{ old('type') == 'venta' ? 'selected' : '' }}>Venta</option>
                                <option value="caducado" {{ old('type') == 'caducado' ? 'selected' : '' }}>Caducado / Merma</option>
                                <option value="ajuste_salida" {{ old('type') == 'ajuste_salida' ? 'selected' : '' }}>Ajuste Manual (Pérdida)</option>
                                <option value="otro" {{ old('type') == 'otro' ? 'selected' : '' }}>Otro</option>
                            </select>
                            @error('type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="quantity" class="form-label">Cantidad a Sacar *</label>
                            <input type="number" class="form-control @error('quantity') is-invalid @enderror" id="quantity" name="quantity" value="{{ old('quantity') }}" min="1" required>
                             <small class="text-muted">La cantidad no puede ser mayor al stock de la zona seleccionada.</small>
                            @error('quantity')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="notes" class="form-label">Notas (Opcional)</label>
                            <textarea class="form-control @error('notes') is-invalid @enderror" id="notes" name="notes" rows="3">{{ old('notes') }}</textarea>
                            <small class="text-muted">Ej: Venta Folio #12345, Ajuste por rotura, Caducado lote XYZ.</small>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="text-end">
                            <a href="{{ route('inventory') }}" class="btn btn-secondary">Cancelar</a>
                            <button type="submit" class="btn btn-danger">Registrar Salida</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection