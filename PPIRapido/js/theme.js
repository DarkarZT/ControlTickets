// Verificar y aplicar el tema guardado en localStorage
document.addEventListener('DOMContentLoaded', () => {
    const savedTheme = localStorage.getItem('theme') || 'light';
    if (savedTheme === 'dark') {
        document.body.classList.add('dark-mode');
        document.getElementById('theme-toggle').textContent = '☀️' ;
    }
});

// Cambiar entre modos oscuro y claro
document.addEventListener('DOMContentLoaded', () => {
    const toggleButton = document.getElementById('theme-toggle');
    if (toggleButton) {
        toggleButton.addEventListener('click', function () {
            document.body.classList.toggle('dark-mode');

            const isDarkMode = document.body.classList.contains('dark-mode');
            localStorage.setItem('theme', isDarkMode ? 'dark' : 'light');

            const currentTheme = isDarkMode ? '☀️' : '🌙';
            this.textContent = currentTheme;
        });
    }
});
