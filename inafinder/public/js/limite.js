function cambiarLimite(limite) {
    // Obtener la URL actual sin parámetros
    const url = new URL(window.location);
    
    // Actualizar o añadir el parámetro limite
    url.searchParams.set('limite', limite);
    
    // Recargar la página con el nuevo parámetro
    window.location.href = url.toString();
}

// Cargar el valor seleccionado al cargar la página
document.addEventListener('DOMContentLoaded', function() {
    const selector = document.getElementById('limite-selector');
    const urlParams = new URLSearchParams(window.location.search);
    const limiteActual = urlParams.get('limite') || '25';
    
    // Establecer el valor seleccionado
    selector.value = limiteActual;
});