@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="container py-4">
    
    <!-- NUEVAS TARJETAS KPI (Indicadores Clave de Rendimiento) -->
    <div class="row mb-4">
        <!-- KPI 1: Alertas de Stock Bajo -->
        <div class="col-md-3">
            <div class="card text-white {{ $totalStockAlerts > 0 ? 'bg-danger' : 'bg-secondary' }} shadow-sm">
                <div class="card-body">
                    <h5>Alertas de Stock Bajo</h5>
                    <h3>{{ $totalStockAlerts ?? 0 }}</h3>
                    <small>Productos bajo el mínimo</small>
                </div>
            </div>
        </div>
        <!-- KPI 2: Valor Total del Inventario (Costo) -->
        <div class="col-md-3">
            <div class="card text-white bg-primary shadow-sm">
                <div class="card-body">
                    <h5>Valor Inventario (Costo)</h5>
                    <h3>${{ number_format($totalValorCosto ?? 0, 2) }}</h3>
                    <small>Costo total del stock actual</small>
                </div>
            </div>
        </div>
        <!-- KPI 3: Ganancia Potencial -->
        <div class="col-md-3">
            <div class="card text-white bg-success shadow-sm">
                <div class="card-body">
                    <h5>Ganancia Potencial</h5>
                    <h3>${{ number_format($totalGananciaPotencial ?? 0, 2) }}</h3>
                    <small>Valor de Venta - Valor de Costo</small>
                </div>
            </div>
        </div>
        <!-- KPI 4: Merma (Pérdidas) del Mes -->
        <div class="col-md-3">
            <div class="card text-white bg-warning shadow-sm">
                <div class="card-body">
                    <h5>Merma (Este Mes)</h5>
                    <h3>${{ number_format($totalMermaMes ?? 0, 2) }}</h3>
                    <small>Costo de stock perdido/caducado</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Fila de Gráficas (Torta y Barras) -->
    <div class="row mb-4">
        <!-- GRÁFICA 1: Valor del Inventario por Categoría (Torta) -->
        <div class="col-lg-5">
            <div class="card shadow-sm h-100">
                <div class="card-header">
                    Valor del Inventario (Costo) por Categoría
                </div>
                <div class="card-body">
                    <canvas id="valorChart"></canvas>
                </div>
            </div>
        </div>

        <!-- GRÁFICA 2: Productos con Stock Bajo (Barras) -->
        <div class="col-lg-7">
            <div class="card shadow-sm h-100">
                <div class="card-header">
                    Top 5 Productos con Stock Bajo
                </div>
                <div class="card-body">
                    <canvas id="stockBajoChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Fila de Gráfica (Líneas) -->
    <div class="row mb-4">
        <!-- GRÁFICA 3: Flujo de Mercancía (Líneas) -->
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header">
                    Flujo de Mercancía (Últimos 6 meses)
                </div>
                <div class="card-body">
                    <canvas id="flujoChart"></canvas>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

{{-- ---------------------------------------------------------------------- --}}
{{-- SCRIPTS (JavaScript para dibujar las gráficas)                        --}}
{{-- ---------------------------------------------------------------------- --}}
@push('scripts')
{{-- Chart.js se carga desde tu app.blade.php --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    // Espera a que el DOM esté cargado
    document.addEventListener('DOMContentLoaded', function () {
        
        // Función para formatear números como moneda
        const formatCurrency = (value) => new Intl.NumberFormat('es-MX', { style: 'currency', currency: 'MXN' }).format(value);

        // --- GRÁFICA 1: Valor del Inventario por Categoría (Torta) ---
        const ctxValor = document.getElementById('valorChart');
        if (ctxValor) {
            new Chart(ctxValor, {
                type: 'doughnut', // Gráfica de donas
                data: {
                    labels: @json($chartValorLabels),
                    datasets: [{
                        label: 'Valor de Inventario',
                        data: @json($chartValorData),
                        backgroundColor: [ // Colores de ejemplo
                            'rgb(54, 162, 235)',
                            'rgb(255, 99, 132)',
                            'rgb(255, 205, 86)',
                            'rgb(75, 192, 192)',
                            'rgb(153, 102, 255)'
                        ],
                        hoverOffset: 4
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: { position: 'top' },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return ` ${context.label}: ${formatCurrency(context.raw)}`;
                                }
                            }
                        }
                    }
                }
            });
        }

        // --- GRÁFICA 2: Productos con Stock Bajo (Barras) ---
        const ctxStockBajo = document.getElementById('stockBajoChart');
        if (ctxStockBajo) {
            new Chart(ctxStockBajo, {
                type: 'bar', // Gráfica de barras
                data: {
                    labels: @json($chartStockBajoLabels),
                    datasets: [
                        {
                            label: 'Stock Actual',
                            data: @json($chartStockBajoData_Actual),
                            backgroundColor: 'rgba(255, 99, 132, 0.5)', // Rojo
                            borderColor: 'rgb(255, 99, 132)',
                            borderWidth: 1
                        },
                        {
                            label: 'Stock Mínimo',
                            data: @json($chartStockBajoData_Minimo),
                            backgroundColor: 'rgba(54, 162, 235, 0.5)', // Azul
                            borderColor: 'rgb(54, 162, 235)',
                            borderWidth: 1
                        }
                    ]
                },
                options: {
                    indexAxis: 'y', // Hace la gráfica horizontal (mejor para listas)
                    responsive: true,
                    scales: {
                        x: { beginAtZero: true }
                    },
                    plugins: {
                        legend: { position: 'bottom' }
                    }
                }
            });
        }

        // --- GRÁFICA 3: Flujo de Mercancía (Líneas) ---
        const ctxFlujo = document.getElementById('flujoChart');
        if (ctxFlujo) {
            new Chart(ctxFlujo, {
                type: 'line', // Gráfica de líneas
                data: {
                    labels: @json($chartFlujoLabels),
                    datasets: [
                        {
                            label: 'Gastos (Costo de Entradas)',
                            data: @json($chartFlujoData_Gastos),
                            borderColor: 'rgb(54, 162, 235)', // Azul
                            backgroundColor: 'rgba(54, 162, 235, 0.1)',
                            fill: true,
                            tension: 0.1
                        },
                        {
                            label: 'Costo de Ventas (COGS)', 
                            data: @json($chartFlujoData_Ventas), 
                            borderColor: 'rgb(75, 192, 192)', 
                            backgroundColor: 'rgba(75, 192, 192, 0.1)',
                            fill: true,
                            tension: 0.1
                        },
                        { 
                            label: 'Pérdidas (Mermas/Ajustes)',
                            data: @json($chartFlujoData_Perdidas), 
                            borderColor: 'rgb(255, 99, 132)',
                            backgroundColor: 'rgba(255, 99, 132, 0.1)',
                            fill: true,
                            tension: 0.1
                        }
                    ]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: { callback: (value) => formatCurrency(value) } // Formato de moneda
                        }
                    },
                    plugins: {
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return ` ${context.dataset.label}: ${formatCurrency(context.raw)}`;
                                }
                            }
                        }
                    }
                }
            });
        }
    });
</script>
@endpush