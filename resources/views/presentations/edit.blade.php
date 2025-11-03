@extends('layouts.app')

@section('title', 'Editar Presentación: ' . $presentation->sku)

@section('content')
<div class="container py-4">
    <div class="card shadow-sm">
        <div class="card-header">
            <h5>Editar Presentación de Producto</h5>
        </div>
        <div class="card-body">

            @if ($errors->any())
                <div class="alert alert-danger">
                     <h6 class="alert-heading">Por favor corrige los siguientes errores:</h6>
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- El formulario apunta a 'update' y usa el método PUT --}}
            <form action="{{ route('presentations.update', ['presentation' => $presentation]) }}" method="POST">
                @csrf
                @method('PUT')

                {{-- Item (Concepto) --}}
                <div class="mb-3">
                    <label for="item_id_select" class="form-label">Item (Producto General) *</label>
                    <div class="input-group">
                        {{-- Usamos old() y el valor del modelo $presentation --}}
                        <select id="item_id_select" name="item_id" class="form-select @error('item_id') is-invalid @enderror" required>
                            <option value="" disabled>-- Seleccione el Item --</option>
                            @foreach($items as $item)
                                <option value="{{ $item->id }}" @selected(old('item_id', $presentation->item_id) == $item->id)>
                                    {{ $item->name }}
                                </option>
                            @endforeach
                        </select>
                        {{-- El botón y el modal de Item siguen funcionando igual --}}
                        <button class="btn btn-outline-secondary" type="button" data-bs-toggle="modal" data-bs-target="#addItemModal" title="Crear Nuevo Item">
                            <i class="bi bi-plus-lg"></i> +
                        </button>
                        @error('item_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                     <small class="text-muted">Selecciona el producto general al que pertenece esta presentación.</small>
                </div>

                {{-- SKU --}}
                <div class="mb-3">
                    <label for="sku" class="form-label">SKU (Código único) *</label>
                    <input type="text" id="sku" name="sku" class="form-control @error('sku') is-invalid @enderror" value="{{ old('sku', $presentation->sku) }}" required>
                     <small class="text-muted">Ej: CAM-NEG-M, TAZ-BLA-11OZ</small>
                    @error('sku') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                {{-- Descripción --}}
                <div class="mb-3">
                    <label for="description" class="form-label">Descripción de la Presentación</label>
                    <textarea id="description" name="description" class="form-control @error('description') is-invalid @enderror" rows="2">{{ old('description', $presentation->description) }}</textarea>
                     <small class="text-muted">Ej: Camisa Negra Talla M, Caja 36 Tazas Blancas Sublimables</small>
                    @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                {{-- Arquetipo (Nuevo campo) --}}
                <div class="mb-3">
                    <label for="archetype" class="form-label">Arquetipo / Modelo</label>
                    <input type="text" id="archetype" name="archetype" class="form-control @error('archetype') is-invalid @enderror" value="{{ old('archetype', $presentation->archetype) }}">
                    <small class="text-muted">Ej: "Taza Clásica 11oz", "Camisa Yazbek Peso Completo"</small>
                    @error('archetype') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                {{-- Calidad (Nuevo campo) --}}
                <div class="mb-3">
                    <label for="quality" class="form-label">Calidad</label>
                    <input type="text" id="quality" name="quality" class="form-control @error('quality') is-invalid @enderror" value="{{ old('quality', $presentation->quality) }}">
                    <small class="text-muted">Ej: "A", "AA", "Premium", "Económica"</small>
                    @error('quality') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                 <div class="row">
                     {{-- Unidades por Presentación --}}
                    <div class="col-md-4 mb-3">
                        <label for="units_per_presentation" class="form-label">Unidades por Presentación</label>
                        <input type="number" id="units_per_presentation" name="units_per_presentation" class="form-control @error('units_per_presentation') is-invalid @enderror" value="{{ old('units_per_presentation', $presentation->units_per_presentation) }}" min="1">
                         <small class="text-muted">Ej: 1 (si es una pieza), 36 (si es una caja)</small>
                        @error('units_per_presentation') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                     {{-- Unidad Base --}}
                     <div class="col-md-4 mb-3">
                        <label for="base_unit" class="form-label">Unidad Base</label>
                        <input type="text" id="base_unit" name="base_unit" class="form-control @error('base_unit') is-invalid @enderror" value="{{ old('base_unit', $presentation->base_unit) }}">
                         <small class="text-muted">Ej: pieza, caja, metro, kg</small>
                        @error('base_unit') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    {{-- Stock Mínimo --}}
                    <div class="col-md-4 mb-3">
                        <label for="stock_minimum" class="form-label">Stock Mínimo</label>
                        <input type="number" id="stock_minimum" name="stock_minimum" class="form-control @error('stock_minimum') is-invalid @enderror" value="{{ old('stock_minimum', $presentation->stock_minimum) }}" min="0">
                         <small class="text-muted">Para alertas de bajo inventario.</small>
                        @error('stock_minimum') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                {{-- Precio Unitario --}}
                <div class="mb-3">
                    <label for="unit_price" class="form-label">Precio Unitario</label>
                    <div class="input-group">
                        <span class="input-group-text">$</span>
                        <input type="number" step="0.01" id="unit_price" name="unit_price" class="form-control @error('unit_price') is-invalid @enderror" value="{{ old('unit_price', $presentation->unit_price) }}" min="0">
                    </div>
                    @error('unit_price') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                
                {{-- Espacio por Unidad (m2) --}}
                <div class="mb-3">
                    <label for="m2_per_unit" class="form-label">Espacio por Unidad (m²) - (Opcional)</label>
                    <input type="number" step="0.0001" id="m2_per_unit" name="m2_per_unit" class="form-control @error('m2_per_unit') is-invalid @enderror" value="{{ old('m2_per_unit', $presentation->m2_per_unit) }}" min="0">
                    <small class="text-muted">Opcional: Si quieres rastrear el espacio físico, define cuántos m² ocupa UNA unidad.</small>
                    @error('m2_per_unit') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                {{-- Aviso sobre el stock actual --}}
                <div class="alert alert-warning small mt-4">
                    Nota: El Stock Actual (<strong>{{ $presentation->stock_current }}</strong>) no se puede modificar aquí. 
                    Se actualiza automáticamente usando las acciones de "Recibir Stock" o "Registrar Salida" en la pantalla de Inventario.
                </div>

                {{-- Botones --}}
                <div class="d-flex gap-2 mt-4">
                    {{-- Botón Cancelar ahora apunta a la vista de detalle (o a index si prefieres) --}}
                    <a href="{{ route('presentations.show', $presentation) }}" class="btn btn-secondary">Cancelar</a>
                    <button type="submit" class="btn btn-primary">Actualizar Presentación</button>
                </div>
            </form>

        </div>
    </div>
</div>

{{-- ---------------------------------------------------------------------- --}}
{{-- MODAL PARA CREACIÓN RÁPIDA DE ITEM (Es idéntico al de create.blade.php) --}}
{{-- ---------------------------------------------------------------------- --}}
<div class="modal fade" id="addItemModal" tabindex="-1" aria-labelledby="addItemModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addItemModalLabel">Crear Nuevo Item (Producto General)</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addItemForm">
                    @csrf
                    <div class="alert alert-danger d-none" id="itemErrors"></div>

                    {{-- Nombre del Item --}}
                    <div class="mb-3">
                        <label for="item_name" class="form-label">Nombre del Item *</label>
                        <input type="text" class="form-control" id="item_name" name="name" required>
                         <small class="text-muted">Ej: Camisa Polo, Taza Blanca, Vinil Adhesivo</small>
                    </div>

                    {{-- Descripción del Item --}}
                    <div class="mb-3">
                        <label for="item_description" class="form-label">Descripción General</label>
                        <textarea class="form-control" id="item_description" name="description" rows="2"></textarea>
                    </div>

                     <div class="mb-3">
                         <label for="item_category_id" class="form-label">Categoría</label>
                         <select id="item_category_id" name="category_id" class="form-select">
                             <option value="">-- Opcional --</option>
                             {{-- 
                             @foreach($categories as $c)
                                 <option value="{{ $c->id }}">{{ $c->name }}</option>
                             @endforeach 
                             --}}
                         </select>
                     </div>
                     <div class="mb-3">
                         <label for="item_supplier_id" class="form-label">Proveedor</label>
                         <select id="item_supplier_id" name="supplier_id" class="form-select">
                              <option value="">-- Opcional --</Vistas>
                             {{-- 
                             @foreach($suppliers as $s)
                                 <option value="{{ $s->id }}">{{ $s->name }}</option>
                             @endforeach 
                             --}}
                         </select>
                     </div>

                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="saveItemBtn">Guardar Item</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
{{-- El script para el modal de Item es idéntico al de create.blade.php --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const itemModalElement = document.getElementById('addItemModal');
        const itemModal = new bootstrap.Modal(itemModalElement);
        const itemForm = document.getElementById('addItemForm');
        const saveItemBtn = document.getElementById('saveItemBtn');
        const itemSelect = document.getElementById('item_id_select'); // El select principal
        const itemErrorsDiv = document.getElementById('itemErrors');

        saveItemBtn.addEventListener('click', function () {
            const formData = new FormData(itemForm);
            const csrfToken = itemForm.querySelector('input[name="_token"]').value;

            itemErrorsDiv.classList.add('d-none');
            itemErrorsDiv.innerHTML = '';

            fetch('{{ route("items.ajax.store") }}', { // RUTA AJAX PARA ITEM
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                },
                body: formData
            })
            .then(response => {
                 if (!response.ok) {
                     return response.json().then(errorData => { throw { status: response.status, data: errorData }; });
                 }
                 return response.json();
            })
            .then(data => {
                if (data.success && data.item) {
                    const newItem = data.item;
                    const newOption = new Option(newItem.name, newItem.id, false, true); // text, value, defaultSelected, selected
                    itemSelect.appendChild(newOption);
                    itemSelect.value = newItem.id; // Selecciona el nuevo
                    itemForm.reset();
                    itemModal.hide();
                } else {
                     itemErrorsDiv.innerHTML = data.message || 'An unexpected error occurred.';
                     itemErrorsDiv.classList.remove('d-none');
                }
            })
            .catch(error => {
                 console.error('Error creating item:', error);
                 let errorMsg = 'Request failed. Please check console.';
                 if (error.status === 422 && error.data && error.data.errors) {
                    let errorHtml = '<ul>';
                    for (const field in error.data.errors) {
                        error.data.errors[field].forEach(errMsg => { errorHtml += `<li>${errMsg}</li>`; });
                    }
                    errorHtml += '</ul>';
                    errorMsg = errorHtml;
                 } else if (error.data && error.data.message) {
                     errorMsg = error.data.message;
                 }
                 itemErrorsDiv.innerHTML = errorMsg;
                 itemErrorsDiv.classList.remove('d-none');
            });
        });

        itemModalElement.addEventListener('hidden.bs.modal', function () {
            itemErrorsDiv.classList.add('d-none');
            itemErrorsDiv.innerHTML = '';
            itemForm.reset();
        });
    });
</script>
@endpush