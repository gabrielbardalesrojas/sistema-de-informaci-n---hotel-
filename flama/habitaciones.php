<!-- index.php -->
<?php include 'partes/header.php'; ?>

<div class="carousel-containe">
    <!-- Indicadores -->
    <div class="indicator-container">
      <div class="indicator red"></div>
      <div class="indicator orange"></div>
      <div class="indicator yellow"></div>
      <div class="indicator blue"></div>
    </div>

    <!-- Contenido ampliado -->
    <div class="large-card" id="large-card">
        <div class="descrip">
        <h2 id="card-title"></h2>
        <p id="card-description"></p>  
        </div>
      
    </div>

    <!-- Cards pequeñas -->
    <div class="small-cards">
      <div class="card orange active" onclick="showCard(0)"></div>
      <div class="card yellow" onclick="showCard(1)"></div>
      <div class="card blue" onclick="showCard(2)"></div>
      <div class="card red" onclick="showCard(3)"></div>
    </div>

    <!-- Botones de navegación -->
    <div class="carousel-controls">
      <button class="carousel-btn prev" onclick="prevCard()">&#10094;</button>
      <button class="carousel-btn next" onclick="nextCard()">&#10095;</button>
    </div>
  </div>

  
<script src="js/habitaciones.js"></script>
<?php include 'partes/footer.php'; ?>