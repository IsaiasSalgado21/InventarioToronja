@extends('layouts.app')

@section('title', 'Proveedores')

@section('content')
<div class="container py-4">

    <!-- Encabezado y Botón Crear -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>Gestión de Proveedores</h3>
        <a href="{{ route('suppliers.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Agregar Proveedor
        </a>
    </div>
    <p class="text-muted">Aquí puedes ver, crear, editar y eliminar proveedores.</p>

    <!-- Notificaciones -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Tabla de Proveedores -->
    <div class="card shadow-sm">
        <div class="card-body table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Contacto</th>
                        <th>Teléfono</th>
                        <th>Email</th>
                        <th class="text-end">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    {{-- La variable $suppliers viene del Controller@index y está paginada --}}
                    @forelse ($suppliers as $supplier)
                        <tr>
                            <td>{{ $supplier->id }}</td>
                            <td>{{ $supplier->name }}</td>
                            <td>{{ $supplier->contact ?? '–' }}</td>
                            <td>{{ $supplier->phone ?? '–' }}</td>
                            <td>{{ $supplier->email ?? '–' }}</td>
                            <td class="text-end">
                                {{-- Botón Ver Detalle --}}
                                <a href="{{ route('suppliers.show', ['supplier' => $supplier]) }}" class="btn btn-info btn-sm" title="Ver Detalles">
                                    <i class="bi bi-eye"></i>
                                </a>
                                {{-- Botón Editar --}}
                                <a href="{{ route('suppliers.edit', ['supplier' => $supplier]) }}" class="btn btn-warning btn-sm" title="Editar">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                {{-- Botón Eliminar (con formulario) --}}
                                <form action="{{ route('suppliers.destroy', ['supplier' => $supplier]) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Estás seguro de eliminar a {{ $supplier->name }}? Esto es un borrado lógico.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" title="Eliminar">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted">No hay proveedores registrados.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            {{-- Links de Paginación --}}
            @if ($suppliers->hasPages())
                <div class="d-flex justify-content-center">
                    {{ $suppliers->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection