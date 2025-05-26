document.addEventListener('DOMContentLoaded', () => {
    const authContainer = document.getElementById('authContainer');
    const loginFormDisplay = document.getElementById('loginForm');
    const registerFormDisplay = document.getElementById('registerForm');
    const showRegisterLink = document.getElementById('showRegister');
    const showLoginLink = document.getElementById('showLogin');

    // Función para cambiar entre formularios con una pequeña animación
    function switchForms(formToShow, formToHide) {
        const formPanel = formToHide.parentElement;

        // Fijar altura actual para evitar "rebote"
        const currentHeight = formPanel.offsetHeight;
        formPanel.style.height = `${currentHeight}px`;

        formToHide.classList.add('hidden');

        setTimeout(() => {
            formToHide.style.display = 'none'; // Ocultar completamente después de la transición
            formToHide.classList.remove('hidden'); // Limpiar clase para la próxima vez

            formToShow.style.display = 'block'; // Hacer visible para la animación de entrada
            // Forzar reflujo para que la transición de entrada se aplique
            void formToShow.offsetWidth;
            formToShow.classList.remove('hidden'); // Asegura que no tenga hidden


            // Restablecer scroll del formulario interno
            const scrollableForm = formToShow.querySelector('form');
            if (scrollableForm) scrollableForm.scrollTop = 0;


            // Esperar a que el nuevo formulario esté visible para ajustar altura
            const newHeight = formToShow.offsetHeight;
            formPanel.style.height = `${newHeight}px`;

            // Quitar la altura fija después de la transición
            setTimeout(() => {
                formPanel.style.height = '';
            }, 400); // Debe coincidir con la duración de la transición en CSS
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



