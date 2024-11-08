<?php include '../partes/headeradmin.php'; ?>
<?php
// Incluir el archivo de conexión
include '../conexion.php';

// Obtener el año actual o el año seleccionado
$anio = isset($_GET['anio']) ? intval($_GET['anio']) : date("Y");

// Inicializar un arreglo para almacenar los datos de cada mes
$facturas = [];
$meses = [
    "01" => "Enero", "02" => "Febrero", "03" => "Marzo",
    "04" => "Abril", "05" => "Mayo", "06" => "Junio",
    "07" => "Julio", "08" => "Agosto", "09" => "Septiembre",
    "10" => "Octubre", "11" => "Noviembre", "12" => "Diciembre"
];

// Recorrer cada mes y obtener los datos correspondientes
foreach ($meses as $mes_num => $mes_nombre) {
    // Total de comprobantes
    $sql_comprobantes = "SELECT COUNT(*) AS total_comprobantes FROM reservas WHERE YEAR(check_in) = :anio AND MONTH(check_in) = :mes";
    $stmt_comprobantes = $conn->prepare($sql_comprobantes);
    $stmt_comprobantes->execute(['anio' => $anio, 'mes' => $mes_num]);
    $total_comprobantes = $stmt_comprobantes->fetchColumn();

    // Número de clientes únicos
    $sql_clientes = "SELECT COUNT(DISTINCT cliente_id) AS num_clientes FROM reservas WHERE YEAR(check_in) = :anio AND MONTH(check_in) = :mes";
    $stmt_clientes = $conn->prepare($sql_clientes);
    $stmt_clientes->execute(['anio' => $anio, 'mes' => $mes_num]);
    $num_clientes = $stmt_clientes->fetchColumn();

    // Total de dinero
    $sql_dinero = "SELECT COALESCE(SUM(total_pagado), 0) AS total_dinero FROM reservas WHERE YEAR(check_in) = :anio AND MONTH(check_in) = :mes";
    $stmt_dinero = $conn->prepare($sql_dinero);
    $stmt_dinero->execute(['anio' => $anio, 'mes' => $mes_num]);
    $total_dinero = $stmt_dinero->fetchColumn();

    // Calculo de impuestos y ganancias
    $impuestos = $total_dinero * 0.18;
    $total_ganancias = $total_dinero - $impuestos;

    // Almacenar los datos en el arreglo
    $facturas[] = [
        'mes' => $mes_nombre,
        'total_comprobantes' => $total_comprobantes,
        'num_clientes' => $num_clientes,
        'total_dinero' => $total_dinero,
        'impuestos' => $impuestos,
        'total_ganancias' => $total_ganancias
    ];
}
?>


<div class="chart-container" >
<div style="text-align: center; margin-bottom: 20px;">
    <label for="year-select">Escoge Año:</label>
    <select id="year-select" name="anio" onchange="consultarAnio()">
        <?php for ($year = date("Y") - 5; $year <= date("Y") + 5; $year++): ?>
            <option value="<?= $year ?>" <?= $year == $anio ? 'selected' : '' ?>><?= $year ?></option>
        <?php endfor; ?>
    </select>
</div>
    <h1 style="text-align: center;">Análisis Estadístico</h1>
    <canvas id="barChart"></canvas>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Obtener los datos desde PHP
    const facturas = <?= json_encode($facturas); ?>;

    // Extraer los nombres de los meses y los valores
    const meses = facturas.map(factura => factura.mes);
    const totalComprobantes = facturas.map(factura => factura.total_comprobantes);
    const numClientes = facturas.map(factura => factura.num_clientes);
    const totalDinero = facturas.map(factura => factura.total_dinero);
    const impuestos = facturas.map(factura => factura.impuestos);
    const totalGanancias = facturas.map(factura => factura.total_ganancias);

    // Crear el gráfico de barras
    const ctx = document.getElementById('barChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: meses,
            datasets: [
                {
                    label: 'Total de Comprobantes',
                    data: totalComprobantes,
                    backgroundColor: 'rgba(75, 192, 192, 0.5)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                },
                {
                    label: 'Número de Clientes',
                    data: numClientes,
                    backgroundColor: 'rgba(255, 159, 64, 0.5)',
                    borderColor: 'rgba(255, 159, 64, 1)',
                    borderWidth: 1
                },
                {
                    label: 'Total Dinero (S/)',
                    data: totalDinero,
                    backgroundColor: 'rgba(153, 102, 255, 0.5)',
                    borderColor: 'rgba(153, 102, 255, 1)',
                    borderWidth: 1
                },
                {
                    label: 'Impuestos (S/)',
                    data: impuestos,
                    backgroundColor: 'rgba(255, 99, 132, 0.5)',
                    borderColor: 'rgba(255, 99, 132, 1)',
                    borderWidth: 1
                },
                {
                    label: 'Total Ganancias (S/)',
                    data: totalGanancias,
                    backgroundColor: 'rgba(54, 162, 235, 0.5)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }
            ]
        },
        options: {
            responsive: true,
            scales: {
                x: { title: { display: true, text: 'Mes' } },
                y: { title: { display: true, text: 'Valor' }, beginAtZero: true }
            }
        }
    });

    function consultarAnio() {
        const anio = document.getElementById('year-select').value;
        window.location.href = `?anio=${anio}`;
    }
</script>

<?php include '../partes/footeradmin.php'; ?>
