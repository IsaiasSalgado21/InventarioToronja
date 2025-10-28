@extends('layouts.app')

@section('title', 'Edit Item')

@section('content')
<div class="card shadow-sm">
    <div class="card-body">
        <h4 class="mb-3">Edit Item #{{ $item->id }}</h4>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">@foreach ($errors->all() as $e) <li>{{ $e }}</li> @endforeach</ul>
            </div>
        @endif

        <form action="{{ route('items.update', $item->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="form-label">Name</label>
                <input name="name" class="form-control" value="{{ old('name', $item->name) }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Description</label>
                <textarea name="description" class="form-control">{{ old('description', $item->description) }}</textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">Category</label>
                <select name="category_id" class="form-select">
                    <option value="">-- none --</option>
                    @foreach($categories as $c)
                        <option value="{{ $c->id }}" @selected(old('category_id', $item->category_id) == $c->id)>{{ $c->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Supplier</label>
                <select name="supplier_id" class="form-select">
                    <option value="">-- none --</option>
                    @foreach($suppliers as $s)
                        <option value="{{ $s->id }}" @selected(old('supplier_id', $item->supplier_id) == $s->id)>{{ $s->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">ABC Class</label>
                <select name="abc_class" class="form-select">
                    <option value="">-- none --</option>
                    <option value="A" @selected(old('abc_class', $item->abc_class)=='A')>A</option>
                    <option value="B" @selected(old('abc_class', $item->abc_class)=='B')>B</option>
                    <option value="C" @selected(old('abc_class', $item->abc_class)=='C')>C</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Expiry Date</label>
                <input type="date" name="expiry_date" class="form-control" value="{{ old('expiry_date', $item->expiry_date) }}">
            </div>

            <div class="d-flex gap-2">
                <a href="{{ route('dashboard') }}" class="btn btn-secondary">Cancel</a>
                <button class="btn btn-primary">Update</button>
            </div>
        </form>
    </div>
</div>
@endsection
