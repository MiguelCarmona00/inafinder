document.addEventListener('DOMContentLoaded', function() {
    const timerElement = document.getElementById('timer');
    
    if (!timerElement) {
        return;
    }
    
    let timeRemaining = parseInt(timerElement.getAttribute('data-time'));
    
    function updateTimer() {
        if (timeRemaining <= 0) {
            // El tiempo ha expirado, recargar la página
            location.reload();
            return;
        }
        
        // Calcular minutos y segundos
        const minutes = Math.floor(timeRemaining / 60);
        const seconds = timeRemaining % 60;
        
        // Formatear el tiempo con ceros a la izquierda
        const formattedTime = `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
        
        // Actualizar el elemento del timer
        timerElement.textContent = formattedTime;
        
        // Reducir el tiempo restante
        timeRemaining--;
        
        // Programar la siguiente actualización
        setTimeout(updateTimer, 1000);
    }
    
    // Iniciar el temporizador
    updateTimer();
    
    // Agregar efecto visual cuando quedan menos de 30 segundos
    function addUrgencyEffect() {
        if (timeRemaining <= 30 && timeRemaining > 0) {
            timerElement.classList.add('animate-pulse');
            timerElement.style.color = '#ff6b6b';
        }
    }
    
    // Verificar el efecto de urgencia cada segundo
    setInterval(addUrgencyEffect, 1000);
});