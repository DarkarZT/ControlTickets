document.addEventListener('DOMContentLoaded', () => {
    // Obtener el tema guardado en localStorage o establecer el tema claro como predeterminado
    const savedTheme = localStorage.getItem('theme') || 'light';
    
    // Aplicar el tema guardado
    if (savedTheme === 'dark') {
        document.body.classList.add('dark-mode');
    }

    // Crear un botón para cambiar el tema
    const themeToggleButton = document.createElement('button');
    themeToggleButton.id = 'theme-toggle-button'; // Asignar un ID para que el CSS pueda estilizarlo
    themeToggleButton.textContent = savedTheme === 'dark' ? '☀️' : '🌙'; // Icono inicial basado en el tema guardado

    // Aplicar estilos directamente para asegurarse de que sea visible
    themeToggleButton.style.position = 'fixed';
    themeToggleButton.style.bottom = '20px';
    themeToggleButton.style.right = '20px';
    themeToggleButton.style.backgroundColor = 'var(--toggle-bg-color)';
    themeToggleButton.style.color = 'var(--toggle-text-color)';
    themeToggleButton.style.border = 'none';
    themeToggleButton.style.borderRadius = '8px';
    themeToggleButton.style.padding = '8px 16px';
    themeToggleButton.style.cursor = 'pointer';
    themeToggleButton.style.zIndex = '1000';

    // Añadir el botón al body del documento
    document.body.appendChild(themeToggleButton);

    // Agregar un event listener para cambiar el tema cuando se haga clic en el botón
    themeToggleButton.addEventListener('click', () => {
        const isDarkMode = document.body.classList.toggle('dark-mode');
        localStorage.setItem('theme', isDarkMode ? 'dark' : 'light');
        themeToggleButton.textContent = isDarkMode ? '☀️' : '🌙'; // Cambiar el icono
    });
});
