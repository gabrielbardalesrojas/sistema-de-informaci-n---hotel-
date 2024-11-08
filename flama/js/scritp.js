


// Guardar el título original de la página
const originalTitle = document.title;

// Detectar cuando la visibilidad de la página cambia
document.addEventListener('visibilitychange', () => {
    if (document.hidden) {
        // Cuando la pestaña está oculta, cambiar el título a "¡No te vayas!"
        document.title = "¡No te vayas!";
    } else {
        // Cuando el usuario vuelve a la pestaña, restaurar el título original
        document.title = originalTitle;
    }
});
