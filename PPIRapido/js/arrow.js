     // Si no hay historial, redirige al index.php
     if (!document.referrer) {
        document.querySelector('.back-arrow').setAttribute('href', 'index.php');
    }