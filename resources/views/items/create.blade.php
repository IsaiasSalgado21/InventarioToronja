@extends('layouts.app')

@section('title', 'Add Item')

@section('content')
<div class="container py-4">
    <div class="card shadow-sm">
        <div class="card-body">
            <h4 class="mb-3">Add New Item</h4>

            {{-- Mostrar errores de validación del formulario principal --}}
            @if ($errors->any())
                <div class="alert alert-danger">
                    <h6 class="alert-heading">Por favor corrige los siguientes errores:</h6>
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
                    <label for="name" class="form-label">Name *</label>
                    <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                {{-- Descripción --}}
                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea id="description" name="description" class="form-control @error('description') is-invalid @enderror" rows="3">{{ old('description') }}</textarea>
                    @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                {{-- Categoría --}}
                <div class="mb-3">
                    <label for="category_id_select" class="form-label">Category</label>
                    <div class="input-group">
                        <select id="category_id_select" name="category_id" class="form-select @error('category_id') is-invalid @enderror">
                            <option value="">-- none --</option>
                            {{-- La variable $categories viene del ItemController@create --}}
                            @foreach($categories as $c)
                                <option value="{{ $c->id }}" @selected(old('category_id') == $c->id)>
                                    {{ $c->name }}
                                </option>
                            @endforeach
                        </select>
                        {{-- Botón para abrir el modal de categoría --}}
                        <button class="btn btn-outline-secondary" type="button" data-bs-toggle="modal" data-bs-target="#addCategoryModal" title="Add New Category">
                            <i class="bi bi-plus-lg"></i> +
                        </button>
                        @error('category_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                {{-- Proveedor --}}
                <div class="mb-3">
                    <label for="supplier_id_select" class="form-label">Supplier</label>
                    <div class="input-group">
                        <select id="supplier_id_select" name="supplier_id" class="form-select @error('supplier_id') is-invalid @enderror">
                            <option value="">-- none --</option>
                            {{-- La variable $suppliers viene del ItemController@create --}}
                            @foreach($suppliers as $s)
                                <option value="{{ $s->id }}" @selected(old('supplier_id') == $s->id)>
                                    {{ $s->name }}
                                </option>
                            @endforeach
                        </select>
                        {{-- Botón para abrir el modal de proveedor --}}
                        <button class="btn btn-outline-secondary" type="button" data-bs-toggle="modal" data-bs-target="#addSupplierModal" title="Add New Supplier">
                            <i class="bi bi-plus-lg"></i> +
                        </button>
                        @error('supplier_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                {{-- Clasificación ABC --}}
                <div class="mb-3">
                    <label for="abc_class" class="form-label">ABC Class</label>
                    <select id="abc_class" name="abc_class" class="form-select @error('abc_class') is-invalid @enderror">
                        <option value="">-- none --</option>
                        <option value="A" @selected(old('abc_class')=='A')>A</option>
                        <option value="B" @selected(old('abc_class')=='B')>B</option>
                        <option value="C" @selected(old('abc_class')=='C')>C</option>
                    </select>
                    @error('abc_class') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                {{-- Fecha de expiración --}}
                <div class="mb-3">
                    <label for="expiry_date" class="form-label">Expiry Date</label>
                    <input type="date" id="expiry_date" name="expiry_date" class="form-control @error('expiry_date') is-invalid @enderror" value="{{ old('expiry_date') }}">
                    @error('expiry_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                {{-- Botones --}}
                <div class="d-flex gap-2 mt-4">
                    <a href="{{ route('dashboard') }}" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-success">Save Item</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ---------------------------------------------------------------------- --}}
{{-- MODALES PARA CREACIÓN RÁPIDA (Deben estar fuera del form principal)   --}}
{{-- ---------------------------------------------------------------------- --}}

<div class="modal fade" id="addCategoryModal" tabindex="-1" aria-labelledby="addCategoryModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addCategoryModalLabel">Add New Category</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                {{-- Formulario dentro del modal --}}
                <form id="addCategoryForm">
                    @csrf {{-- ¡Importante para la petición AJAX! --}}
                    <div class="alert alert-danger d-none" id="categoryErrors"></div> {{-- Para mostrar errores --}}
                    <div class="mb-3">
                        <label for="category_name" class="form-label">Name *</label>
                        <input type="text" class="form-control" id="category_name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="category_description" class="form-label">Description</label>
                        <textarea class="form-control" id="category_description" name="description" rows="3"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                {{-- Botón que activará el AJAX vía JavaScript --}}
                <button type="button" class="btn btn-primary" id="saveCategoryBtn">Save Category</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="addSupplierModal" tabindex="-1" aria-labelledby="addSupplierModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addSupplierModalLabel">Add New Supplier</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                {{-- Formulario dentro del modal --}}
                <form id="addSupplierForm">
                    @csrf {{-- ¡Importante para la petición AJAX! --}}
                    <div class="alert alert-danger d-none" id="supplierErrors"></div> {{-- Para mostrar errores --}}
                    <div class="mb-3">
                        <label for="supplier_name" class="form-label">Name *</label>
                        <input type="text" class="form-control" id="supplier_name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="supplier_contact" class="form-label">Contact</label>
                        <input type="text" class="form-control" id="supplier_contact" name="contact">
                    </div>
                    {{-- Puedes añadir más campos si quieres (phone, email, address) --}}
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                {{-- Botón que activará el AJAX vía JavaScript --}}
                <button type="button" class="btn btn-primary" id="saveSupplierBtn">Save Supplier</button>
            </div>
        </div>
    </div>
</div>
@endsection

{{-- ---------------------------------------------------------------------- --}}
{{-- SCRIPTS (JavaScript para manejar los modales y AJAX)                 --}}
{{-- ---------------------------------------------------------------------- --}}
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const categoryModalElement = document.getElementById('addCategoryModal');
        const categoryModal = new bootstrap.Modal(categoryModalElement);
        const categoryForm = document.getElementById('addCategoryForm');
        const saveCategoryBtn = document.getElementById('saveCategoryBtn');
        const categorySelect = document.getElementById('category_id_select');
        const categoryErrorsDiv = document.getElementById('categoryErrors');

        saveCategoryBtn.addEventListener('click', function () {
            const formData = new FormData(categoryForm);
            const csrfToken = categoryForm.querySelector('input[name="_token"]').value;

            categoryErrorsDiv.classList.add('d-none');
            categoryErrorsDiv.innerHTML = '';

            fetch('{{ route("categories.ajax.store") }}', { // Usa la ruta AJAX
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json', // Esperamos JSON de vuelta
                },
                body: formData
            })
            .then(response => {
                 if (!response.ok) {
                     return response.json().then(errorData => {
                         throw { status: response.status, data: errorData };
                     });
                 }
                 return response.json(); 
            })
            .then(data => {
                if (data.success && data.category) {
                    const newOption = new Option(data.category.name, data.category.id, false, true);
                    categorySelect.appendChild(newOption);
                    categorySelect.value = data.category.id; 
                    categoryForm.reset(); 
                    categoryModal.hide();
                } else {
                    categoryErrorsDiv.innerHTML = data.message || 'An unexpected error occurred.';
                    categoryErrorsDiv.classList.remove('d-none');
                }
            })
            .catch(error => {
                 console.error('Error:', error);
                 let errorMsg = 'Request failed. Please check console.';
                 if (error.status === 422 && error.data && error.data.errors) {
                    let errorHtml = '<ul>';
                    for (const field in error.data.errors) {
                        error.data.errors[field].forEach(errMsg => {
                            errorHtml += `<li>${errMsg}</li>`;
                        });
                    }
                    errorHtml += '</ul>';
                    errorMsg = errorHtml;
                 } else if (error.data && error.data.message) {
                     errorMsg = error.data.message; // Use server message if available
                 }
                 categoryErrorsDiv.innerHTML = errorMsg;
                 categoryErrorsDiv.classList.remove('d-none');
            });
        });

        categoryModalElement.addEventListener('hidden.bs.modal', function () {
            categoryErrorsDiv.classList.add('d-none');
            categoryErrorsDiv.innerHTML = '';
            categoryForm.reset();
        });


        const supplierModalElement = document.getElementById('addSupplierModal');
        const supplierModal = new bootstrap.Modal(supplierModalElement);
        const supplierForm = document.getElementById('addSupplierForm');
        const saveSupplierBtn = document.getElementById('saveSupplierBtn');
        const supplierSelect = document.getElementById('supplier_id_select');
        const supplierErrorsDiv = document.getElementById('supplierErrors');

        saveSupplierBtn.addEventListener('click', function () {
            const formData = new FormData(supplierForm);
            const csrfToken = supplierForm.querySelector('input[name="_token"]').value;

            supplierErrorsDiv.classList.add('d-none');
            supplierErrorsDiv.innerHTML = '';

            fetch('{{ route("suppliers.ajax.store") }}', { // Usa la ruta AJAX de supplier
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                },
                body: formData
            })
            .then(response => { // Check if response is ok
                 if (!response.ok) {
                     return response.json().then(errorData => {
                         throw { status: response.status, data: errorData };
                     });
                 }
                 return response.json();
            })
            .then(data => {
                if (data.success && data.supplier) {
                    const newOption = new Option(data.supplier.name, data.supplier.id, false, true);
                    supplierSelect.appendChild(newOption);
                    supplierSelect.value = data.supplier.id;
                    supplierForm.reset();
                    supplierModal.hide();
                } else {
                    supplierErrorsDiv.innerHTML = data.message || 'An unexpected error occurred.';
                    supplierErrorsDiv.classList.remove('d-none');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                let errorMsg = 'Request failed. Please check console.';
                 if (error.status === 422 && error.data && error.data.errors) {
                    let errorHtml = '<ul>';
                    for (const field in error.data.errors) {
                        error.data.errors[field].forEach(errMsg => {
                            errorHtml += `<li>${errMsg}</li>`;
                        });
                    }
                    errorHtml += '</ul>';
                    errorMsg = errorHtml;
                 } else if (error.data && error.data.message) {
                     errorMsg = error.data.message;
                 }
                supplierErrorsDiv.innerHTML = errorMsg;
                supplierErrorsDiv.classList.remove('d-none');
            });
        });

        supplierModalElement.addEventListener('hidden.bs.modal', function () {
            supplierErrorsDiv.classList.add('d-none');
            supplierErrorsDiv.innerHTML = '';
            supplierForm.reset();
        });

    });
</script>
@end