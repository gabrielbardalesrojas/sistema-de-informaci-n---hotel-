// Detectar cuando la sección de ubicación está en el viewport
function isInViewport(element) {
    const rect = element.getBoundingClientRect();
    return (
      rect.top >= 0 &&
      rect.left >= 0 &&
      rect.bottom <= (window.innerHeight || document.documentElement.clientHeight) &&
      rect.right <= (window.innerWidth || document.documentElement.clientWidth)
    );
  }
  
  document.addEventListener("scroll", function() {
    const locationSection = document.querySelector(".location-section");
    if (isInViewport(locationSection)) {
      locationSection.classList.add("visible");
    }
  });
  

  // Array de imágenes de fondo
const images = [
    'images/hotel1.jpg',
    'images/hotel2.jpg',
    'images/hotel3.jpg',
  ];
  
  // Selección del contenedor
  const backgroundContainer = document.querySelector('.location-section');
  
  let currentIndex = 0;
  
  // Función para cambiar la imagen de fondo
  function changeBackground() {
    backgroundContainer.style.backgroundImage = `url(${images[currentIndex]})`;
    currentIndex = (currentIndex + 1) % images.length;
  }
  
  // Cambiar la imagen de fondo cada 5 segundos
  setInterval(changeBackground, 5000);
  
  // Iniciar la primera imagen
  changeBackground();
  