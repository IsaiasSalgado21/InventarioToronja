@extends('layouts.app')

@section('title', 'Transferir Stock')

@section('content')

<div class="container py-4">
<div class="row justify-content-center">
<div class="col-md-8">
<div class="card shadow-sm">
<div class="card-header">
<h5>Transferir Stock entre Zonas</h5>
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

                <form action="{{ route('inventory.transfer.store') }}" method="POST">
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
                        <div class="form-text">
                            Solo se muestran productos con stock (stock_current > 0).
                        </div>
                        @error('presentation_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="origin_zone_id" class="form-label">Zona de Origen *</label>
                            <select class="form-select @error('origin_zone_id') is-invalid @enderror" id="origin_zone_id" name="origin_zone_id" required>
                                <option value="" disabled selected>-- Seleccione zona origen --</option>
                                @foreach($storageZones as $zone)
                                    <option value="{{ $zone->id }}" {{ old('origin_zone_id') == $zone->id ? 'selected' : '' }}>
                                        {{ $zone->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('origin_zone_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="dest_zone_id" class="form-label">Zona de Destino *</label>
                            <select class="form-select @error('dest_zone_id') is-invalid @enderror" id="dest_zone_id" name="dest_zone_id" required>
                                <option value="" disabled selected>-- Seleccione zona destino --</option>
                                @foreach($storageZones as $zone)
                                    <option value="{{ $zone->id }}" {{ old('dest_zone_id') == $zone->id ? 'selected' : '' }}>
                                        {{ $zone->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('dest_zone_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="quantity" class="form-label">Cantidad a Transferir *</label>
                        <input type="number" class="form-control @error('quantity') is-invalid @enderror" id="quantity" name="quantity" value="{{ old('quantity') }}" min="1" required>
                        <div class="form-text">
                            Asegúrate de que la zona de origen tenga esta cantidad disponible.
                        </div>
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
                        <button type="submit" class="btn btn-info">Realizar Transferencia</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


</div>
@endsection