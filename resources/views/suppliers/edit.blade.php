@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Editar Proveedor</h5>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('suppliers.update', $supplier->id) }}">
                        @csrf
                        @method('PATCH')

                        <div class="mb-3">
                            <label for="name" class="form-label">Nombre</label>
                            <input id="name" name="name" type="text" class="form-control" value="{{ old('name', $supplier->name) }}" required maxlength="150">
                        </div>

                        <div class="mb-3">
                            <label for="contact" class="form-label">Contacto</label>
                            <input id="contact" name="contact" type="text" class="form-control" value="{{ old('contact', $supplier->contact) }}" maxlength="150">
                        </div>

                        <div class="mb-3">
                            <label for="phone" class="form-label">Teléfono</label>
                            <input id="phone" name="phone" type="text" class="form-control" value="{{ old('phone', $supplier->phone) }}" maxlength="50">
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input id="email" name="email" type="email" class="form-control" value="{{ old('email', $supplier->email) }}" maxlength="150">
                        </div>

                        <div class="mb-3">
                            <label for="address" class="form-label">Dirección</label>
                            <input id="address" name="address" type="text" class="form-control" value="{{ old('address', $supplier->address) }}" maxlength="255">
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('suppliers.show', $supplier->id) }}" class="btn btn-secondary">Cancelar</a>
                            <button class="btn btn-primary">Guardar cambios</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
