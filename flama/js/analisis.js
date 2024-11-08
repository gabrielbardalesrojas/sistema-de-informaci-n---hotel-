let lineChart;

function consultar() {
  const month = document.getElementById("month-select").value;
  
  fetch(`get_data.php?month=${month}`)
    .then(response => response.json())
    .then(data => {
      actualizarDatos(data);
      generarGrafico(data.ganancias_diarias);
    });
}

function actualizarDatos(data) {
  document.getElementById("ganancias").querySelector("input").value = `S/ ${data.total_ganancia}`;
  document.getElementById("clientes").querySelector("input").value = data.total_clientes;
  document.getElementById("reservas").querySelector("input").value = data.total_reservas;
  document.getElementById("habitaciones").querySelector("input").value = data.total_habitaciones;
  document.getElementById("igv").querySelector("input").value = `S/ ${data.total_igv}`;
  document.getElementById("forma-pago").querySelector("input").value = data.forma_pago;
}

function generarGrafico(gananciasDiarias) {
  const labels = Array.from({length: gananciasDiarias.length}, (_, i) => i + 1);
  
  const ctx = document.getElementById("lineChart").getContext("2d");

  if (lineChart) {
    lineChart.destroy();
  }

  lineChart = new Chart(ctx, {
    type: 'line',
    data: {
      labels: labels,
      datasets: [{
        label: 'Ganancias Diarias',
        data: gananciasDiarias,
        backgroundColor: 'rgba(30, 144, 255, 0.3)',
        borderColor: 'rgba(30, 144, 255, 1)',
        borderWidth: 2,
        fill: true
      }]
    },
    options: {
      responsive: true,
      scales: {
        x: { title: { display: true, text: 'Día del Mes' } },
        y: { title: { display: true, text: 'Ganancia (S/)' } }
      }
    }
  });
}
