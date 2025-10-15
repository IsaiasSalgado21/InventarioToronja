@extends('layouts.app')

@section('title', 'Item Details')

@section('content')
<div class="card shadow-sm">
    <div class="card-body">
        <h4>Item #{{ $item->id }} - {{ $item->name }}</h4>

        <dl class="row">
            <dt class="col-sm-3">Name</dt><dd class="col-sm-9">{{ $item->name }}</dd>
            <dt class="col-sm-3">Description</dt><dd class="col-sm-9">{{ $item->description }}</dd>
            <dt class="col-sm-3">Category</dt><dd class="col-sm-9">{{ $item->category_name ?? '-' }}</dd>
            <dt class="col-sm-3">Supplier</dt><dd class="col-sm-9">{{ $item->supplier_name ?? '-' }}</dd>
            <dt class="col-sm-3">ABC Class</dt><dd class="col-sm-9">{{ $item->abc_class ?? '-' }}</dd>
            <dt class="col-sm-3">Expiry Date</dt><dd class="col-sm-9">{{ $item->expiry_date ?? '-' }}</dd>
        </dl>

        <a href="{{ route('items.edit', $item->id) }}" class="btn btn-warning">Edit</a>
        <a href="{{ route('items.index') }}" class="btn btn-secondary">Back</a>
    </div>
</div>
@endsection
