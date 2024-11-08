document.querySelectorAll('.toggle-form').forEach(button => {
    button.addEventListener('click', () => {
        const target = button.getAttribute('data-target');
        const formContainer = document.querySelector('.form-container');
        
        if (target === 'signup') {
            formContainer.classList.add('show-signup');
        } else {
            formContainer.classList.remove('show-signup');
        }
    });
});


 // Verificar si el parámetro de error está en la URL
 const urlParams = new URLSearchParams(window.location.search);
 if (urlParams.has('error')) {
     Swal.fire({
         title: 'Error',
         text: 'Credenciales incorrectas. Inténtalo de nuevo.',
         icon: 'error',
         confirmButtonText: 'OK'
     });
 }