// Función para abrir y cerrar el menú de opciones del chatbot
function toggleOptions() {
    const options = document.getElementById('chatbot-options');
    options.style.display = options.style.display === 'none' ? 'block' : 'none';
}

// Función para mostrar/ocultar preguntas frecuentes
function toggleFAQ() {
    const faq = document.getElementById('faq-suboptions');
    faq.style.display = faq.style.display === 'none' ? 'block' : 'none';
}

// Función para cambiar el tema
function toggleTheme() 
    {
    const body = document.body;
    body.classList.toggle('dark-theme'); // Asume que ya tienes un tema oscuro en CSS
    }

// Función para abrir el chatbot
function toggleChatbot() {
    const chatWindow = document.getElementById('chat-window');
    
    // Alterna la visibilidad de la ventana del chatbot
    if (chatWindow.style.display === 'none' || chatWindow.style.display === '') {
        chatWindow.style.display = 'block';  // Muestra el chatbot
    } else {
        chatWindow.style.display = 'none';   // Oculta el chatbot
    }
}
