// Función para consultar por el mes seleccionado en el menú desplegable
function consultarMes() {
    const mes = document.getElementById("month-select").value;
    if (mes) {
        // Redirige a la misma página con el parámetro "mes"
        window.location.href = `?mes=${mes}`;
    }
}

// Función para consultar por una fecha específica seleccionada en el input de tipo date
function consultarFecha() {
    const fecha = document.getElementById("date-select").value;
    if (fecha) {
        // Redirige a la misma página con el parámetro "fecha"
        window.location.href = `?fecha=${fecha}`;
    }
}

// Establecer el mes actual en el selector de mes al cargar la página
document.addEventListener("DOMContentLoaded", function() {
    const monthSelect = document.getElementById("month-select");
    const currentMonth = new Date().getMonth() + 1; // getMonth() devuelve el mes de 0 a 11
    monthSelect.value = currentMonth.toString().padStart(2, '0'); // Asegura el formato "01", "02", etc.
});
