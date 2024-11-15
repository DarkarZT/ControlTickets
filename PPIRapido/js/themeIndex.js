
document.addEventListener('DOMContentLoaded', () => {
    const themeToggleButton = document.createElement('button');
    themeToggleButton.id = 'theme-toggle-button';
    themeToggleButton.textContent = '🌙'; // Icono inicial para el modo claro

    const savedTheme = localStorage.getItem('theme') || '☀️';
    if (savedTheme === 'dark') {
        document.body.classList.add('dark-mode');
        themeToggleButton.textContent = '☀️'; // Sol para el modo oscuro
    }

    document.body.appendChild(themeToggleButton);

    themeToggleButton.addEventListener('click', () => {
        const isDarkMode = document.body.classList.toggle('dark-mode');
        localStorage.setItem('theme', isDarkMode ? 'dark' : 'light');
        themeToggleButton.textContent = isDarkMode ? '☀️' : '🌙'; // Cambiar ícono según el modo
    });
});
