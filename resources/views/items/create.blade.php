@extends('layouts.app')

@section('title', 'Add Item')

@section('content')
<div class="card shadow-sm">
    <div class="card-body">
        <h4 class="mb-3">Add New Item</h4>

        {{-- Mostrar errores de validación --}}
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $e)
                        <li>{{ $e }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('items.store') }}" method="POST">
            @csrf

            {{-- Nombre del producto --}}
            <div class="mb-3">
                <label class="form-label">Name</label>
                <input name="name" class="form-control" value="{{ old('name') }}" required>
            </div>

            {{-- Descripción --}}
            <div class="mb-3">
                <label class="form-label">Description</label>
                <textarea name="description" class="form-control">{{ old('description') }}</textarea>
            </div>

            {{-- Categoría --}}
            <div class="mb-3">
                <label class="form-label">Category</label>
                <select name="category_id" class="form-select">
                    <option value="">-- none --</option>
                    @foreach($categories as $c)
                        <option value="{{ $c->id }}" @selected(old('category_id') == $c->id)>
                            {{ $c->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Proveedor --}}
            <div class="mb-3">
                <label class="form-label">Supplier</label>
                <select name="supplier_id" class="form-select">
                    <option value="">-- none --</option>
                    @foreach($suppliers as $s)
                        <option value="{{ $s->id }}" @selected(old('supplier_id') == $s->id)>
                            {{ $s->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Zona de almacenamiento (Storage Zone) --}}
            <div class="mb-3">
                <label class="form-label">Storage Zone</label>
                <select name="storage_zone_id" class="form-select" required>
                    <option value="">-- select zone --</option>
                    @foreach($storageZones as $z)
                        <option value="{{ $z->id }}" @selected(old('storage_zone_id') == $z->id)>
                            {{ $z->name }}
                        </option>
                    @endforeach
                </select>
                <small class="text-muted">Select where this item will be stored physically.</small>
            </div>

            {{-- Clasificación ABC --}}
            <div class="mb-3">
                <label class="form-label">ABC Class</label>
                <select name="abc_class" class="form-select">
                    <option value="">-- none --</option>
                    <option value="A" @selected(old('abc_class')=='A')>A</option>
                    <option value="B" @selected(old('abc_class')=='B')>B</option>
                    <option value="C" @selected(old('abc_class')=='C')>C</option>
                </select>
            </div>

            {{-- Fecha de expiración --}}
            <div class="mb-3">
                <label class="form-label">Expiry Date</label>
                <input type="date" name="expiry_date" class="form-control" value="{{ old('expiry_date') }}">
            </div>

            {{-- Botones --}}
            <div class="d-flex gap-2">
                <a href="{{ route('items.index') }}" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-success">Save</button>
            </div>
        </form>
    </div>
</div>
@endsection
