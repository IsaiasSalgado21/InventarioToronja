@extends('layouts.app')

@section('title', 'Storage Zones')

@section('content')
<div class="container py-4">
    <h3>Storage Zones</h3>
    <p class="text-muted">Overview of storage zones and occupied space.</p>

    @foreach($zones as $zone)
        <div class="card mb-3 shadow-sm">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5>{{ $zone->zone_name }}</h5>
                <span class="badge bg-info">
                    {{ number_format($zone->occupied_m2, 2) }} / {{ number_format($zone->capacity_m2, 2) }} m²
                </span>
            </div>
            <div class="card-body">
                <p>{{ $zone->description }}</p>
                <p>Dimensions: {{ $zone->dimension_x }}m x {{ $zone->dimension_y }}m</p>
                <p>Total units stored: {{ $zone->total_units }}</p>

                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>SKU</th>
                            <th>Description</th>
                            <th>Stored Quantity</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(isset($presentations[$zone->id]))
                            @foreach($presentations[$zone->id] as $presentation)
                                <tr>
                                    <td>{{ $presentation->sku }}</td>
                                    <td>{{ $presentation->presentation_description }}</td>
                                    <td>{{ $presentation->stored_quantity }}</td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="3" class="text-center">No items in this zone</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    @endforeach
</div>
@endsection
