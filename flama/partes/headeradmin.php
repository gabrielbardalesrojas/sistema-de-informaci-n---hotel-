
<?php include('session.php'); ?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DASHBOARD</title>
    <link rel="stylesheet" href="../css/home.css">
    <link rel="stylesheet" href="../css/reserva.css">
    <link rel="stylesheet" href="../css/analisis.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="sweetalert2.min.css">
    <link rel="icon" href="../images/solo.jpeg" type="image/jpeg">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
           .chart-container {
        width: 90%; /* Aumenta el ancho del contenedor */
        height: auto;
        max-width: 1000px; /* Establece un tamaño máximo para pantallas más grandes */
        margin: 20px auto; /* Centra el gráfico con un espacio superior */
    }

    canvas {
        width: 100% !important; /* Asegura que el canvas ocupe todo el ancho del contenedor */
        height: 450px !important; /* Aumenta la altura del canvas */
    }

    .chart-title {
        font-size: 24px; /* Tamaño de fuente más grande para el título */
        font-weight: bold;
        text-align: center;
        margin-bottom: 20px;
    }

    </style>
</head>
<body>

<div class="sidebar">
<div class="sidebar-header">
            <h2>PALMERAS</h2>
            <p>Administrador</p>
        </div>
        <ul class="sidebar-menu">
            <li onclick="window.location='reservas.php'"><i class="fas fa-tachometer-alt"></i> Reservas</li>
            <li onclick="window.location='habitaciones.php'"><i class="fas fa-bell"></i> Habitaciones</li>
            <li onclick="window.location='clientes.php'"><i class="fas fa-chart-bar"></i> Clientes</li>
            <li onclick="window.location='analisis.php'"><i class="fas fa-chart-pie"></i>Estadísticas y reportes</li>
            <li onclick="window.location='factura.php'"><i class="fas fa-wallet"></i> Facturación y Contabilidad</li>
            <li onclick="window.location='servicios.php'"><i class="fas fa-cog"></i> Servicios Adicionales</li>
            <li onclick="window.location='personales.php'"><i class="fas fa-cog"></i> Personales</li>
            <li onclick="window.location='../partes/logout.php'"><i class="fas fa-ticket-alt"></i>Cerrar</li>
            
        </ul>
        
</div>