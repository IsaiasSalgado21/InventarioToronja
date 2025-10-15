@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="card shadow-sm">
    <div class="card-body text-center">
        <h3>Welcome, {{ auth()->user()->name }} </h3>
        <p class="text-muted">You have successfully logged in.</p>
    </div>
</div>
@endsection
