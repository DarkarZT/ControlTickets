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
function sendMessage(userMessage) {
    const chatBody = document.getElementById('chat-body');

    // Agregar mensaje del usuario
    const userMessageDiv = document.createElement('div');
    userMessageDiv.className = 'user-message';
    userMessageDiv.innerHTML = `<span class="message">${userMessage}</span>`;
    chatBody.appendChild(userMessageDiv);

    // Respuesta del bot con preguntas clickeables
    if (userMessage === 'Tengo una pregunta') {
        const botResponseDiv = document.createElement('div');
        botResponseDiv.className = 'bot-message';
        botResponseDiv.innerHTML = `
            <span class="message">
                🤖 Aquí tienes algunas preguntas frecuentes:<br>
                <a href="#" class="faq-link" onclick="showFAQInfo('quienes')">- ¿Quiénes somos?</a><br>
                <a href="#" class="faq-link" onclick="showFAQInfo('quehacemos')">- ¿Qué hacemos?</a><br>
                <a href="#" class="faq-link" onclick="showFAQInfo('horarios')">- ¿Cuáles son nuestros horarios?</a><br>
                <a href="#" class="faq-link" onclick="showFAQInfo('ubicacion')">- ¿Dónde estamos ubicados?</a><br>
                <a href="#" class="faq-link" onclick="showFAQInfo('contacto')">- ¿Cómo puedo contactarlos?</a>
            </span>
        `;
        chatBody.appendChild(botResponseDiv);
    }

    // Hacer scroll al final del chat
    chatBody.scrollTop = chatBody.scrollHeight;
}

function showFAQInfo(question) {
    const chatBody = document.getElementById('chat-body');
    let answer = '';

    switch (question) {
        case 'quienes':
            answer = 'Somos una empresa dedicada a ofrecer soluciones innovadoras y tecnológicas.';
            break;
        case 'quehacemos':
            answer = 'Nos especializamos en brindar soporte y desarrollo de aplicaciones web.';
            break;
        case 'horarios':
            answer = 'Nuestro horario de atención es de lunes a viernes, de 8:00 AM a 6:00 PM.';
            break;
        case 'ubicacion':
            answer = 'Nos encontramos ubicados en Rionegro, en el centro de la ciudad.';
            break;
        case 'contacto':
            answer = 'Puedes contactarnos al correo contacto@pingüichat.com o al teléfono +57 300 123 4567.';
            break;
        default:
            answer = 'Lo siento, no tengo información sobre esa pregunta.';
    }

    // Mostrar la respuesta del bot
    const botResponseDiv = document.createElement('div');
    botResponseDiv.className = 'bot-message';
    botResponseDiv.innerHTML = `<span class="message">🤖 ${answer}</span>`;
    chatBody.appendChild(botResponseDiv);

    // Hacer scroll al final del chat
    chatBody.scrollTop = chatBody.scrollHeight;
}
