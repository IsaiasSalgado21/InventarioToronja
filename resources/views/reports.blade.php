@extends('layouts.app')

@section('title', 'Central de Reportes')

@section('content')
<div class="container py-4">
    
    <!-- Encabezado de la Página -->
    <div class="mb-4">
        <h3>Central de Reportes</h3>
        <p class="text-muted">Selecciona un reporte para analizar el rendimiento y estado de tu inventario.</p>
    </div>

    <!-- Grid de Tarjetas de Reportes -->
    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">

        <!-- Opción 1: Reporte de Comparación de Precios (El que acabamos de crear) -->
        <div class="col">
            <div class="card h-100 shadow-sm">
                <div class="card-body d-flex flex-column">
                    <div class="mb-3">
                        <i class="bi bi-graph-up-arrow" style="font-size: 2rem; color: var(--bs-primary);"></i>
                    </div>
                    <h5 class="card-title">Comparación de Costos</h5>
                    <p class="card-text text-muted small flex-grow-1">
                        Analiza qué proveedor te ha ofrecido el mejor costo (promedio, mínimo y máximo) por cada producto que has recibido en tu inventario.
                    </p>
                    <a href="{{ route('reports.price-comparison') }}" class="btn btn-primary mt-auto">
                        Generar Reporte
                    </a>
                </div>
            </div>
        </div>

        <div class="col">
            <div class="card h-100 shadow-sm">
                <div class="card-body d-flex flex-column">
                    <div class="mb-3">
                        <i class="bi bi-cash-coin" style="font-size: 2rem; color: var(--bs-success);"></i>
                    </div>
                    <h5 class="card-title">Análisis de Márgenes</h5>
                    <p class="card-text text-muted small flex-grow-1">
                        Compara el precio de venta de tus productos con su costo promedio para ver la rentabilidad.
                    </p>
                    <a href="{{ route('reports.margin-analysis') }}" class="btn btn-primary mt-auto">
                        Generar Reporte
                    </a>
                </div>
            </div>
        </div>

        <div class="col">
            <div class="card h-100 shadow-sm">
                <div class="card-body d-flex flex-column">
                        <i class="bi bi-box-arrow-up-right" style="font-size: 2rem; color: var(--bs-danger);"></i>
                    <h5 class="card-title">Reporte de Movimientos (Salidas/Mermas)</h5>
                    <p class="card-text text-muted small flex-grow-1">
                        Filtra y analiza todas las salidas del inventario, incluyendo ventas, mermas, caducados y ajustes.
                    </p>
                    <a href="{{ route('movements') }}" class="btn btn-primary mt-auto">
                        Abrir Reporte
                    </a>
                </div>
            </div>
        </div>

        <div class="col">
            <div class="card h-100 shadow-sm">
                <div class="card-body d-flex flex-column">
                    <div class="mb-3">
                        <i class="bi bi-graph-down" style="font-size: 2rem; color: var(--bs-info);"></i>
                    </div>
                    <h5 class="card-title">Auditoría de Precios de Venta</h5>
                    <p class="card-text text-muted small flex-grow-1">
                        Rastrea quién cambió el precio de venta de un producto, cuándo lo hizo, y de cuánto a cuánto cambió.
                    </p>
                    <a href="{{ route('reports.price-history') }}" class="btn btn-primary mt-auto">
                        Generar Reporte
                    </a>
                </div>
            </div>
        </div>

        <!-- Opción 2: Placeholder para un futuro reporte -->
        <div class="col">
            <div class="card h-100 shadow-sm">
                <div class="card-body d-flex flex-column">
                    <div class="mb-3">
                        <i class="bi bi-box-seam" style="font-size: 2rem; color: var(--bs-secondary);"></i>
                    </div>
                    <h5 class="card-title">Reporte de Stock Actual</h5>
                    <p class="card-text text-muted small flex-grow-1">
                        Muestra el valor total de tu inventario actual, agrupado por categoría o proveedor. (Próximamente)
                    </p>
                    <a href="#" class="btn btn-secondary disabled mt-auto">
                        Próximamente
                    </a>
                </div>
            </div>
        </div>

        <!-- Opción 3: Placeholder para un futuro reporte -->
        <div class="col">
            <div class="card h-100 shadow-sm">
                <div class="card-body d-flex flex-column">
                    <div class="mb-3">
                        <i class="bi bi-calendar-event" style="font-size: 2rem; color: var(--bs-secondary);"></i>
                    </div>
                    <h5 class="card-title">Movimientos por Fecha</h5>
                    <p class="card-text text-muted small flex-grow-1">
                        Genera un listado de todas las entradas, salidas y transferencias dentro de un rango de fechas específico. (Próximamente)
                    </p>
                    <a href="#" class="btn btn-secondary disabled mt-auto">
                        Próximamente
                    </a>
                </div>
            </div>
        </div>
        
    </div> <!-- fin .row -->

</div>
@endsection