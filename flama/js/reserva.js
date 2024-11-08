function openForm() {
  document.getElementById("reservationForm").style.display = "block";
}

function closeForm() {
  document.getElementById("reservationForm").style.display = "none";
}





function calcularTotal() {
  const habitacionSelect = document.getElementById("habitacion_id");
  const checkInDate = new Date(document.getElementById("check_in").value);
  const checkOutDate = new Date(document.getElementById("check_out").value);
  const precioPorNoche = parseFloat(habitacionSelect.options[habitacionSelect.selectedIndex].getAttribute("data-precio"));

  // Calcular días de reserva
  const diasReservados = (checkOutDate - checkInDate) / (1000 * 60 * 60 * 24);

  if (!isNaN(precioPorNoche) && diasReservados > 0) {
      let total = precioPorNoche * diasReservados;

      // Sumar el costo de los servicios adicionales seleccionados
      const serviciosSelect = document.getElementById("servicios");
      for (const option of serviciosSelect.selectedOptions) {
          const precioServicio = parseFloat(option.getAttribute("dato-precio"));
          if (!isNaN(precioServicio)) {
              total += precioServicio;
          }
      }

      document.getElementById("total_pagado").value = `S/ ${total.toFixed(2)}`;
  } else {
      document.getElementById("total_pagado").value = "Error en fechas";
  }
}




