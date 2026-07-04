$(document).ready(function() {
    let walletVisible = false;
    
    // Función para alternar la visibilidad del monedero con animación deslizante
    $('#toggleWalletBtn').click(function() {
        const walletContainer = $('#walletContainer');
        const button = $(this);
        const buttonIcon = button.find('svg path');
        
        if (!walletVisible) {
            // Mostrar el monedero deslizándolo desde la izquierda
            walletContainer.css('left', '0px');
            button.addClass('bg-success').removeClass('bg-darker');
            // Cambiar icono a "cerrar" (X)
            buttonIcon.attr('d', 'M6 18L18 6M6 6l12 12');
            walletVisible = true;
        } else {
            // Ocultar el monedero deslizándolo hacia la izquierda
            walletContainer.css('left', '-256px'); // -64 en rem = -256px
            button.addClass('bg-darker').removeClass('bg-success');
            // Cambiar icono de vuelta al "plus"
            buttonIcon.attr('d', 'M12 6v6m0 0v6m0-6h6m-6 0H6');
            walletVisible = false;
        }
    });
    
    // Cerrar el monedero si se hace clic fuera de él
    $(document).click(function(event) {
        if (walletVisible && 
            !$(event.target).closest('#walletContainer').length && 
            !$(event.target).closest('#toggleWalletBtn').length) {
            $('#toggleWalletBtn').click();
        }
    });
    
    // Manejar el redimensionamiento de la ventana (cerrar en pantallas muy pequeñas)
    $(window).resize(function() {
        if ($(window).width() < 480 && walletVisible) {
            $('#toggleWalletBtn').click();
        }
    });
});