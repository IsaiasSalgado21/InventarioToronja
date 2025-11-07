@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Proveedor: {{ $supplier->name }}</h5>
                    <div>
                        <a href="{{ route('suppliers.index') }}" class="btn btn-sm btn-secondary">Volver</a>
                        <a href="{{ route('suppliers.edit', $supplier->id) }}" class="btn btn-sm btn-primary">Editar</a>
                    </div>
                </div>

                <div class="card-body">
                    <dl class="row mb-0">
                        <dt class="col-sm-4">Contacto</dt>
                        <dd class="col-sm-8">{{ $supplier->contact ?? '-' }}</dd>

                        <dt class="col-sm-4">Teléfono</dt>
                        <dd class="col-sm-8">{{ $supplier->phone ?? '-' }}</dd>

                        <dt class="col-sm-4">Email</dt>
                        <dd class="col-sm-8">{{ $supplier->email ?? '-' }}</dd>

                        <dt class="col-sm-4">Dirección</dt>
                        <dd class="col-sm-8">{{ $supplier->address ?? '-' }}</dd>

                        <dt class="col-sm-4">Creado</dt>
                        <dd class="col-sm-8">{{ $supplier->created_at ? $supplier->created_at->format('Y-m-d H:i') : '-' }}</dd>

                        <dt class="col-sm-4">Actualizado</dt>
                        <dd class="col-sm-8">{{ $supplier->updated_at ? $supplier->updated_at->format('Y-m-d H:i') : '-' }}</dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
