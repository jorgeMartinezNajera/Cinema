document.addEventListener('DOMContentLoaded', () => {
    const authContainer = document.getElementById('authContainer');
    const loginFormDisplay = document.getElementById('loginForm');
    const registerFormDisplay = document.getElementById('registerForm');
    const showRegisterLink = document.getElementById('showRegister');
    const showLoginLink = document.getElementById('showLogin');

    // Función para cambiar entre formularios con una pequeña animación
    function switchForms(formToShow, formToHide) {
        formToHide.classList.add('hidden');

        // Esperar a que termine la transición de salida antes de mostrar el nuevo
        setTimeout(() => {
            formToHide.style.display = 'none'; // Ocultar completamente después de la transición
            formToHide.classList.remove('hidden'); // Limpiar clase para la próxima vez

            formToShow.style.display = 'block'; // Hacer visible para la animación de entrada
            // Forzar reflujo para que la transición de entrada se aplique
            void formToShow.offsetWidth;
            formToShow.classList.remove('hidden'); // Asegura que no tenga hidden
        }, 400); // Debe coincidir con la duración de la transición en CSS
    }


    // Estado inicial: Mostrar formulario de inicio de sesión
    loginFormDisplay.style.display = 'block';
    registerFormDisplay.style.display = 'none';
    registerFormDisplay.classList.add('hidden'); // Para la animación inicial
    authContainer.classList.remove('register-active');

    showRegisterLink.addEventListener('click', (event) => {
        event.preventDefault();
        authContainer.classList.add('register-active');
        switchForms(registerFormDisplay, loginFormDisplay);
    });

    showLoginLink.addEventListener('click', (event) => {
        event.preventDefault();
        authContainer.classList.remove('register-active');
        switchForms(loginFormDisplay, registerFormDisplay);
    });
});