/**
 * Función para confirmar el reembolso de un jugador
 * @param {number} idJugador - ID del jugador a reembolsar
 * @param {string} nombreJugador - Nombre del jugador para mostrar en la confirmación
 */
function confirmarReembolso(idJugador, nombreJugador) {
    // Mostrar alerta de confirmación con SweetAlert2
    Swal.fire({
        title: '¿Confirmar reembolso?',
        html: `
            <p>¿Estás seguro de que quieres reembolsar al jugador <strong>"${nombreJugador}"</strong>?</p>
            <br>
            <div class="text-left">
                <p><strong>Esta acción:</strong></p>
                <ul style="list-style-type: none; text-align:left; margin-left: 1.3em;">
                    <li>Eliminará al jugador de tu equipo</li>
                    <li>Te devolverá el 100% del precio pagado</li>
                    <li>No se puede deshacer</li>
                </ul>
            </div>
        `,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Reembolsar',
        cancelButtonText: 'Cancelar',
        reverseButtons: true,
        focusCancel: true
    }).then((result) => {
        if (result.isConfirmed) {
            // Mostrar loading mientras se procesa
            Swal.fire({
                title: 'Procesando reembolso...',
                text: 'Por favor espera',
                allowOutsideClick: false,
                allowEscapeKey: false,
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            
            // POST con token CSRF (la ruta de reembolso ya no acepta GET)
            enviarPost(`/reembolso/${idJugador}`);
        }
    });
}