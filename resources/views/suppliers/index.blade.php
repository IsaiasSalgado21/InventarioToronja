@extends('layouts.app')

@section('title', 'Proveedores')

@section('content')
<div class="container py-4">

    <!-- Encabezado y Botón Crear -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>Gestión de Proveedores</h3>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createSupplierModal">
            <i class="bi bi-plus-circle"></i> Agregar Proveedor
        </button>
    </div>
    <p class="text-muted">Aquí puedes ver, crear, editar y eliminar proveedores.</p>


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
                        <th>Address</th>
                        <th>RFC</th>
                        <th class="text-end">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($suppliers as $supplier)
                        <tr>
                            <td>{{ $supplier->id }}</td>
                            <td>{{ $supplier->name }}</td>
                            <td>{{ $supplier->contact ?? '–' }}</td>
                            <td>{{ $supplier->phone ?? '–' }}</td>
                            <td>{{ $supplier->email ?? '–' }}</td>
                            <td>{{ $supplier->address ?? '–' }}</td>
                            <td>{{ $supplier->RFC ?? '–' }}</td>
                            <td class="text-end">
                                {{-- Botón Ver Detalle --}}
                                <a href="{{ route('suppliers.show', $supplier->id) }}" class="btn btn-info btn-sm" title="Ver Detalles">
                                    <i class="bi bi-eye"></i>
                                </a>
                                {{-- Botón Editar --}}
                                <a href="{{ route('suppliers.edit', $supplier->id) }}" class="btn btn-warning btn-sm" title="Editar">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                {{-- Botón Eliminar (con formulario) --}}
                                <form action="{{ route('suppliers.destroy', $supplier->id) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Estás seguro de eliminar a {{ $supplier->name }}? Esto es un borrado lógico.');">
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
            @if (isset($suppliers) && $suppliers->hasPages())
                <div class="d-flex justify-content-center">
                    {{ $suppliers->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal para Crear Proveedor -->
<div class="modal fade" id="createSupplierModal" tabindex="-1" aria-labelledby="createSupplierModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createSupplierModalLabel">Agregar Nuevo Proveedor</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="supplierForm">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="name" class="form-label">Nombre del Proveedor*</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                        <div class="invalid-feedback" id="nameError"></div>
                    </div>
                    <div class="mb-3">
                        <label for="contact" class="form-label">Persona de Contacto</label>
                        <input type="text" class="form-control" id="contact" name="contact">
                        <div class="invalid-feedback" id="contactError"></div>
                    </div>
                    <div class="mb-3">
                        <label for="phone" class="form-label">Teléfono</label>
                        <input type="tel" class="form-control" id="phone" name="phone">
                        <div class="invalid-feedback" id="phoneError"></div>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email">
                        <div class="invalid-feedback" id="emailError"></div>
                    </div>
                    <div class="mb-3">
                        <label for="address" class="form-label">Dirección</label>
                        <textarea class="form-control" id="address" name="address" rows="2"></textarea>
                        <div class="invalid-feedback" id="addressError"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('supplierForm');
    
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Reset previous error messages
        form.querySelectorAll('.invalid-feedback').forEach(div => div.textContent = '');
        form.querySelectorAll('.form-control').forEach(input => input.classList.remove('is-invalid'));
        
        const formData = new FormData(form);
        
        fetch('{{ route('suppliers.ajax.store') }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
            },
            credentials: 'same-origin'
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Show success message
                alert('Proveedor creado exitosamente');
                // Close modal
                bootstrap.Modal.getInstance(document.getElementById('createSupplierModal')).hide();
                // Reload page to show new supplier
                window.location.reload();
            } else {
                // Handle validation errors
                const errors = data.errors || {};
                Object.keys(errors).forEach(field => {
                    const input = document.getElementById(field);
                    const errorDiv = document.getElementById(field + 'Error');
                    if (input && errorDiv) {
                        input.classList.add('is-invalid');
                        errorDiv.textContent = errors[field][0];
                    }
                });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Ha ocurrido un error al crear el proveedor');
        });
    });
});
</script>
@endpush