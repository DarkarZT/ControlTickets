document.addEventListener('DOMContentLoaded', () => {
    const themeToggleButton = document.getElementById('theme-toggle-button');
    const savedTheme = localStorage.getItem('theme') || 'light';
    
    if (savedTheme === 'dark') {
        document.body.classList.add('dark-mode');
        themeToggleButton.textContent = '☀️ Cambiar Tema'; // Icono para el modo oscuro
    } else {
        themeToggleButton.textContent = '🌙 Cambiar Tema'; // Icono para el modo claro
    }

    themeToggleButton.addEventListener('click', () => {
        const isDarkMode = document.body.classList.toggle('dark-mode');
        localStorage.setItem('theme', isDarkMode ? 'dark' : 'light');
        themeToggleButton.textContent = isDarkMode ? '☀️ Cambiar Tema' : '🌙 Cambiar Tema'; // Cambia el ícono
    });
});
