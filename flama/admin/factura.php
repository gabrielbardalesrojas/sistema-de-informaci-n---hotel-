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

    // Resumen de métodos de pago
  // Resumen de métodos de pago sin repetir
  $sql_metodo_pago = "SELECT metodo_pago, COUNT(*) AS cantidad FROM reservas WHERE YEAR(check_in) = :anio AND MONTH(check_in) = :mes GROUP BY metodo_pago";
  $stmt_metodo_pago = $conn->prepare($sql_metodo_pago);
  $stmt_metodo_pago->execute(['anio' => $anio, 'mes' => $mes_num]);
  $metodos_pago = $stmt_metodo_pago->fetchAll(PDO::FETCH_KEY_PAIR);
  $metodo_pago_resumen = json_encode($metodos_pago);


    // Habitaciones disponibles y alquiladas
    $sql_habitaciones = "SELECT 
                            SUM(CASE WHEN estado = 'Disponible' THEN 1 ELSE 0 END) AS habitaciones_disponibles,
                            SUM(CASE WHEN estado = 'Ocupada' THEN 1 ELSE 0 END) AS habitaciones_alquiladas
                         FROM habitaciones";
    $stmt_habitaciones = $conn->prepare($sql_habitaciones);
    $stmt_habitaciones->execute();
    $habitaciones = $stmt_habitaciones->fetch(PDO::FETCH_ASSOC);

    // Crear un arreglo con los datos calculados para el mes
    $facturas[] = [
        'anio' => $anio,
        'mes' => $mes_nombre,
        'total_comprobantes' => $total_comprobantes,
        'num_clientes' => $num_clientes,
        'total_dinero' => $total_dinero,
        'impuestos' => $impuestos,
        'total_ganancias' => $total_ganancias,
        'metodo_pago' => $metodo_pago_resumen,
        'habitaciones_disponibles' => $habitaciones['habitaciones_disponibles'],
        'habitaciones_alquiladas' => $habitaciones['habitaciones_alquiladas'],
    ];
}
?>

<div class="table-container">
    <h1>FACTURACIÓN POR MES</h1>
    <div class="action-bar">
        <label for="year-select">Escoge Año:</label>
        <select id="year-select" name="anio" onchange="consultarAnio()">
            <?php for ($year = date("Y") - 5; $year <= date("Y") + 5; $year++): ?>
                <option value="<?= $year ?>" <?= $year == $anio ? 'selected' : '' ?>><?= $year ?></option>
            <?php endfor; ?>
        </select>
    </div>
    <div class="tabl">
        <table class="reservation-table">
            <thead>
                <tr>
                    <th>Año</th>
                    <th>Mes</th>
                    <th>Total de Comprobantes</th>
                    <th>Número de Clientes</th>
                    <th>Total del Dinero</th>
                    <th>Impuestos</th>
                    <th>Total Ganancias</th>
                    <th>Resumen de Método de Pago</th>
                    <th>Habitaciones Disponibles</th>
                    <th>Habitaciones Alquiladas</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($facturas as $index => $factura): ?>
    <?php
    // Obtener el mes actual
    $mes_actual = date("m");
    // Verificar si el mes de la factura corresponde al mes actual
    $es_mes_actual = ($mes_actual == str_pad($index + 1, 2, '0', STR_PAD_LEFT)); // +1 porque $index empieza en 0
    ?>
    <tr style="<?= $es_mes_actual ? 'background-color: #06dd18;' : '' ?>"> <!-- Color verde claro si es el mes actual -->
        <td><?= htmlspecialchars($factura['anio']) ?></td>
        <td><?= htmlspecialchars($factura['mes']) ?></td>
        <td><?= htmlspecialchars($factura['total_comprobantes']) ?></td>
        <td><?= htmlspecialchars($factura['num_clientes']) ?></td>
        <td>S/ <?= htmlspecialchars(number_format($factura['total_dinero'], 2)) ?></td>
        <td>S/ <?= htmlspecialchars(number_format($factura['impuestos'], 2)) ?></td>
        <td>S/ <?= htmlspecialchars(number_format($factura['total_ganancias'], 2)) ?></td>
        <td><?= htmlspecialchars($factura['metodo_pago']) ?></td>
        <td><?= htmlspecialchars($habitaciones['habitaciones_disponibles']) ?></td>
        <td><?= htmlspecialchars($habitaciones['habitaciones_alquiladas']) ?></td>
    </tr>
<?php endforeach; ?>


            </tbody>
        </table>
    </div>
</div>

<script>
function consultarAnio() {
    const anio = document.getElementById('year-select').value;
    window.location.href = `?anio=${anio}`;
}
</script>
<?php include '../partes/footeradmin.php'; ?>