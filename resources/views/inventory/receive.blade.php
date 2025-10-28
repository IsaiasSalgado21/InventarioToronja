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
                        <label for="quantity" class="form-label">Cantidad Recibida *</label>
                        <input type="number" class="form-control @error('quantity') is-invalid @enderror" id="quantity" name="quantity" value="{{ old('quantity') }}" min="1" required>
                        @error('quantity')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
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