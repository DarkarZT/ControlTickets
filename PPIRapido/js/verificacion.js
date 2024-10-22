document.addEventListener('DOMContentLoaded', function() {
    const checkbox = document.getElementById('es_agente');
    const agenteFields = document.getElementById('agente-fields');

    checkbox.addEventListener('change', function() {
        if (checkbox.checked) {
            agenteFields.style.display = 'block';
        } else {
            agenteFields.style.display = 'none';
        }
    });
});