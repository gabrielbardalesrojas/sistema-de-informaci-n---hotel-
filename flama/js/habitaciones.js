// Datos de cada card
const cardsData = [
    {
      title: "Habitación Individual (Single Room)",
      description: "Diseñada para una persona, equipada con una cama individual. Ideal para viajeros solos.",
      color: "orange",
      image: "images/solo.jpeg"
    },
    {
      title: "Habitación Doble (Double Room)",
      description: "Incluye una cama doble o dos camas individuales (twin) y está pensada para dos personas.",
      color: "yellow",
      image: "images/doble.jpeg"
    },
    {
      title: "Suite",
      description: "Habitación más espaciosa y lujosa, con áreas de sala de estar y dormitorio separadas. Incluye servicios adicionales como minibar, jacuzzi, y vistas especiales.",
      color: "blue",
      image: "images/suite.jpeg",
    },
    {
      title: "Habitación Familiar (Family Room",
      description: "Diseñada para familias, con espacio para varias camas o literas y a veces con servicios adicionales para niños.",
      color: "red",
      image: "images/family.jpeg"
    },
    {
        title: "Habitación Deluxe",
        description: "Similar a una habitación doble, pero con mobiliario y servicios de mayor calidad y espacio adicional.",
        color: "blue",
        image: "images/deluxe.jpeg"
      },
      {
        title: "Penthouse",
        description: "Ubicada en el último piso del hotel, ofrece vistas panorámicas y servicios de lujo, ideal para estancias exclusivas.",
        color: "red",
        image: "images/penthouse.jpeg"
      }
  ];
  
  let currentCard = 0;
  
  // Función para mostrar una card específica
  function showCard(index) {
    currentCard = index;
    const titleElement = document.getElementById("card-title");
    const descriptionElement = document.getElementById("card-description");
    const largeCard = document.getElementById("large-card");
    
  
    titleElement.textContent = cardsData[currentCard].title;
    descriptionElement.textContent = cardsData[currentCard].description;
  
    // Cambiar la imagen de fondo según la card
    largeCard.style.backgroundImage = `url(${cardsData[currentCard].image})`;
  
    // Actualizar la clase 'active' en las small cards
    document.querySelectorAll(".card").forEach((card, i) => {
      card.classList.toggle("active", i === currentCard);
    });
  }
  
  // Función para ir a la siguiente card
  function nextCard() {
    currentCard = (currentCard + 1) % cardsData.length;
    showCard(currentCard);
  }
  
  // Función para ir a la card anterior
  function prevCard() {
    currentCard = (currentCard - 1 + cardsData.length) % cardsData.length;
    showCard(currentCard);
  }
  
  // Inicializar la card inicial
  showCard(currentCard);
  
  function openForm() {
    document.getElementById("roomForm").style.display = "block";
}

function closeForm() {
    document.getElementById("roomForm").style.display = "none";
}
