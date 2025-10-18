@extends('layouts.app')

@section('title', 'Management')

@section('content')
<div class="container py-4">
    <h3>Management</h3>
    <p class="text-muted">Manage users, categories, suppliers, and presentations.</p>

    {{-- ================== USUARIOS ================== --}}
    <div class="mt-4">
        <h4>Usuarios</h4>
        <a href="#" class="btn btn-primary mb-2">Agregar Usuario</a>
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Email</th>
                    <th>Rol</th>
                    <th>Estado</th>
                    <th>Creación</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                <tr>
                    <td>{{ $user->id }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->role }}</td>
                    <td>{{ $user->status }}</td>
                    <td>{{ $user->created_at }}</td>
                    <td>
                        <a href="#" class="btn btn-sm btn-info">Ver</a>
                        <a href="#" class="btn btn-sm btn-warning">Editar</a>
                        <a href="#" class="btn btn-sm btn-danger">Eliminar</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- ================== CATEGORÍAS ================== --}}
    <div class="mt-5">
        <h4>Categorías</h4>
        <a href="#" class="btn btn-primary mb-2">Agregar Categoría</a>
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($categories as $category)
                <tr>
                    <td>{{ $category->id }}</td>
                    <td>{{ $category->name }}</td>
                    <td>{{ $category->description }}</td>
                    <td>
                        <a href="#" class="btn btn-sm btn-info">Ver</a>
                        <a href="#" class="btn btn-sm btn-warning">Editar</a>
                        <a href="#" class="btn btn-sm btn-danger">Eliminar</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- ================== PROVEEDORES ================== --}}
    <div class="mt-5">
        <h4>Proveedores</h4>
        <a href="#" class="btn btn-primary mb-2">Agregar Proveedor</a>
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Contacto</th>
                    <th>Teléfono</th>
                    <th>Email</th>
                    <th>Dirección</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($suppliers as $supplier)
                <tr>
                    <td>{{ $supplier->id }}</td>
                    <td>{{ $supplier->name }}</td>
                    <td>{{ $supplier->contact }}</td>
                    <td>{{ $supplier->phone }}</td>
                    <td>{{ $supplier->email }}</td>
                    <td>{{ $supplier->address }}</td>
                    <td>
                        <a href="#" class="btn btn-sm btn-info">Ver</a>
                        <a href="#" class="btn btn-sm btn-warning">Editar</a>
                        <a href="#" class="btn btn-sm btn-danger">Eliminar</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- ================== PRESENTACIONES ================== --}}
    <div class="mt-5">
        <h4>Presentaciones</h4>
        <a href="#" class="btn btn-primary mb-2">Agregar Presentación</a>
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>SKU</th>
                    <th>Descripción</th>
                    <th>Stock Actual</th>
                    <th>Precio Unitario</th>
                    <th>Item</th>
                    <th>Categoría</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($presentations as $presentation)
                <tr>
                    <td>{{ $presentation->id }}</td>
                    <td>{{ $presentation->sku }}</td>
                    <td>{{ $presentation->description }}</td>
                    <td>{{ $presentation->stock_current }}</td>
                    <td>{{ $presentation->unit_price }}</td>
                    <td>{{ $presentation->item_name }}</td>
                    <td>{{ $presentation->category_name }}</td>
                    <td>
                        <a href="#" class="btn btn-sm btn-info">Ver</a>
                        <a href="#" class="btn btn-sm btn-warning">Editar</a>
                        <a href="#" class="btn btn-sm btn-danger">Eliminar</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

</div>
@endsection
