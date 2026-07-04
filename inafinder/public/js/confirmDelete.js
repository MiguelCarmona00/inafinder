function confirmarEliminacion(tipo, id, nombre) {
    let mensaje = `¿Estás seguro de que quieres eliminar ${tipo} ${id} "${nombre}"?`;
    let detalles = 'Esta acción no se puede deshacer.';
    
    if (tipo === 'jugador') {
        detalles += '<br><br>Eliminará también todas sus técnicas asociadas.';
    }
    detalles += '<br><br>Es posible que otras tablas se vean afectadas al eliminar.';
    
    Swal.fire({
        title: `Eliminar ${tipo}`,
        html: `
            <p>${mensaje}</p>
            <div class="mt-3 text-sm text-gray-600">
                ${detalles}
            </div>
        `,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc2626',
        cancelButtonColor: '#6b7280',
        confirmButtonText: '🗑️ Sí, eliminar',
        cancelButtonText: '❌ Cancelar',
        reverseButtons: true,
        focusCancel: true
    }).then((result) => {
        if (result.isConfirmed) {
            // Mostrar loading durante la eliminación
            Swal.fire({
                title: `Eliminando ${tipo}...`,
                text: 'Por favor espera',
                allowOutsideClick: false,
                allowEscapeKey: false,
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            
            // Rutas cuyo plural no es "tipo + s"
            const rutasEspeciales = {
                jugador: 'jugadoresDelete',
                entrenador: 'entrenadoresDelete'
            };
            const ruta = rutasEspeciales[tipo] || `${tipo}sDelete`;

            // POST con token CSRF (las rutas de borrado ya no aceptan GET)
            enviarPost(`/${ruta}/${id}`);
        }
    });
}

// Función específica para técnicas
function confirmarEliminacionTecnica(id, nombre) {
    Swal.fire({
        title: '¿Estás seguro?',
        text: `¿Quieres eliminar la técnica "${nombre}"? Esta acción no se puede deshacer.`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc2626',
        cancelButtonColor: '#6b7280',
        confirmButtonText: '🗑️ Sí, eliminar',
        cancelButtonText: '❌ Cancelar',
        reverseButtons: true,
        focusCancel: true
    }).then((result) => {
        if (result.isConfirmed) {
            // Mostrar loading durante la eliminación
            Swal.fire({
                title: 'Eliminando técnica...',
                text: 'Por favor espera',
                allowOutsideClick: false,
                allowEscapeKey: false,
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            
            enviarPost(`/tecnicasDelete/${id}`);
        }
    });
}

window.confirmarEliminacion = confirmarEliminacion;
window.confirmarEliminacionTecnica = confirmarEliminacionTecnica;