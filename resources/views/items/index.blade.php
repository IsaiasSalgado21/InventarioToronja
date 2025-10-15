@extends('layouts.app')

@section('title', 'Items')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4>Items</h4>
    <a href="{{ route('items.create') }}" class="btn btn-primary">Add Item</a>
</div>

@include('partials.alerts') 

<div class="card shadow-sm">
    <div class="card-body table-responsive">
        <table class="table table-striped table-hover">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Category</th>
                    <th>Supplier</th>
                    <th>ABC</th>
                    <th>Expiry</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($items as $it)
                <tr>
                    <td>{{ $it->id }}</td>
                    <td>{{ $it->name }}</td>
                    <td>{{ $it->category_name ?? '-' }}</td>
                    <td>{{ $it->supplier_name ?? '-' }}</td>
                    <td>{{ $it->abc_class ?? '-' }}</td>
                    <td>{{ $it->expiry_date ? \Carbon\Carbon::parse($it->expiry_date)->format('Y-m-d') : '-' }}</td>
                    <td>
                        <a href="{{ route('items.show', $it->id) }}" class="btn btn-sm btn-info">View</a>
                        <a href="{{ route('items.edit', $it->id) }}" class="btn btn-sm btn-warning">Edit</a>

                        <form action="{{ route('items.destroy', $it->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this item?');">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger">Delete</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="text-center">No items found</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
