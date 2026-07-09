let lastUpdate = 0;
let updateInterval;
let isUpdating = false;

$(document).ready(function() {
    // Cargar datos iniciales
    loadJugadores();
    
    // Iniciar polling cada 3 segundos
    startPolling();
});

function startPolling() {
    updateInterval = setInterval(function() {
        if (!isUpdating) {
            checkForUpdates();
        }
    }, 3000); // Cada 3 segundos
}

function stopPolling() {
    if (updateInterval) {
        clearInterval(updateInterval);
    }
}

function checkForUpdates() {
    isUpdating = true;
    
    // Si hay filtros activos, no actualizar automáticamente
    if (typeof estaFiltrandoActivo === 'function' && estaFiltrandoActivo()) {
        isUpdating = false;
        return;
    }
    
    $.ajax({
        url: '/checkUpdates',
        method: 'GET',
        dataType: 'json',
        // jQuery manda If-None-Match con el ETag de la última respuesta; si el
        // servidor contesta 304 (nada cambió) no hay cuerpo que repintar
        ifModified: true,
        success: function(response, textStatus) {
            if (textStatus !== 'notmodified' && response) {
                updateJugadores(response);
                lastUpdate = response.timestamp;
            }
            updateStatus('🟢 Conectado - Última actualización: ' + new Date().toLocaleTimeString());
        },
        error: function() {
            updateStatus('🔴 Error de conexión - Reintentando...', 'error');
        },
        complete: function() {
            isUpdating = false;
        }
    });
}

function loadJugadores() {
    // Si hay filtros activos, no cargar todos los jugadores
    if (typeof estaFiltrandoActivo === 'function' && estaFiltrandoActivo()) {
        return;
    }
    
    $.ajax({
        url: '/checkUpdates',
        method: 'GET',
        dataType: 'json',
        success: function(response) {
            updateJugadores(response);
            lastUpdate = response.timestamp;
        }
    });
}

function updateJugadores(data) {
    // Orden "estándar" (por ID, con fichados intercalados en su posición
    // natural en vez de agrupados al final) vía combinarJugadoresPorId()
    // (definida en filtros.js). Esta función solo se llama para la vista sin
    // filtros (carga inicial y polling), así que ese orden siempre aplica.
    // Se guarda en un Map (no un objeto plano) porque un objeto reordena
    // solo las claves que parecen enteros en orden ascendente al iterarlo,
    // lo que rompería el orden que acabamos de fijar a propósito.
    const todosLosJugadores = new Map();
    combinarJugadoresPorId(data).forEach(function(jugador) {
        todosLosJugadores.set(jugador.id_jugador, jugador);
    });

    // Actualizar cards existentes
    $('.jugador-card').each(function() {
        const cardId = $(this).data('id');
        const jugadorData = todosLosJugadores.get(cardId);

        if (jugadorData) {
            // Actualizar solo el botón si el estado cambió
            const currentButton = $(this).find('button, .w-full.py-3.px-4').last();
            const shouldBeFichado = jugadorData.esFichado;
            const currentlyFichado = currentButton.text().includes('Ya está fichado') || currentButton.text().includes('✅');
            
            if (shouldBeFichado && !currentlyFichado) {
                // Cambiar a estado fichado
                currentButton.replaceWith(`
                    <div class="w-full py-3 px-4 bg-error text-white rounded-lg text-center font-medium">
                        ✅ ¡Ya está fichado!
                    </div>
                `);
                
                // // Añadir efecto visual de actualización
                // $(this).addClass('ring-2 ring-error');
                // setTimeout(() => {
                //     $(this).removeClass('ring-2 ring-error');
                // }, 2000);
                
            } else if (!shouldBeFichado && currentlyFichado) {
                // Cambiar a estado disponible (en caso de que se libere)
                currentButton.replaceWith(`
                    <button onclick="ficharJugador(${jugadorData.id_jugador})" 
                        class="w-full py-3 px-4 bg-success text-white rounded-lg hover:bg-green-600 transition-colors font-medium focus:ring-2 focus:ring-success cursor-pointer">
                    💰 Fichar Jugador
                    </button>
                `);
                
                // Añadir efecto visual de actualización
                // $(this).addClass('ring-2 ring-success');
                // setTimeout(() => {
                //     $(this).removeClass('ring-2 ring-success');
                // }, 2000);
            }
            
            // Remover del mapa (ya procesado)
            todosLosJugadores.delete(cardId);
        }
    });

    // Añadir nuevos jugadores que no existían antes
    let nuevosJugadores = '';
    todosLosJugadores.forEach(function(jugador) {
        nuevosJugadores += createJugadorCard(jugador, jugador.esFichado);
    });
    
    if (nuevosJugadores) {
        $('#jugadoresNoFichados').append(nuevosJugadores);
    }
    
    // Actualizar mensajes de "no hay jugadores"
    const totalDisponibles = $('.jugador-card').filter(function() {
        return $(this).find('button').length > 0; // Tiene botón de fichar
    }).length;
    
    const totalFichados = $('.jugador-card').filter(function() {
        return $(this).find('button').length === 0; // No tiene botón de fichar
    }).length;
    
    if (totalDisponibles === 0) {
        $('#noDisponibles').removeClass('hidden');
    } else {
        $('#noDisponibles').addClass('hidden');
    }
}

function createJugadorCard(jugador, esFichado) {

    // Icono de estado de fichaje
    const estadoBadge = esFichado ? 
        '<span class="absolute top-2 left-2 bg-error text-white text-xs font-bold px-2 py-1 rounded-full z-10">✅ Fichado</span>' : 
        '<span class="absolute top-2 left-2 bg-success text-white text-xs font-bold px-2 py-1 rounded-full z-10">💰 Disponible</span>';

    return `
        <div class="relative bg-darker rounded-lg p-6 shadow-[0_2px_8px_rgba(0,0,0,0.3)] jugador-card transform transition-all duration-300 hover:scale-105 hover:shadow-[0_4px_16px_rgba(0,0,0,0.5)] cursor-pointer" 
             data-id="${jugador.id_jugador}" 
             data-jugador='${JSON.stringify(jugador).replace(/'/g, "&apos;")}'
             onclick="abrirModalJugador(${jugador.id_jugador})">
            
            ${estadoBadge}
            
            <!-- Botón de enlace a página de detalles (arriba derecha) -->
            <a href="/mercado/jugador/${jugador.id_jugador}" 
               target="_blank"
               onclick="event.stopPropagation()"
               class="absolute top-2 right-2 bg-gray hover:bg-success text-white rounded-full p-2 transition-colors z-10"
               title="Ver detalles completos">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                </svg>
            </a>
            
            <div class="mb-4">
                <img src="../imgs/uploads/jugadores/${jugador.imagen}" 
                     alt="${jugador.nombre}" 
                     class="w-19 h-19 rounded-full mx-auto mb-4 border-4 border-secondary">
                <h3 class="text-xl font-bold mb-2 text-center">${jugador.nombre}</h3>
            </div>
        </div>`;
}

function ficharJugador(idJugador) {
    // Obtener información del jugador desde el data-jugador JSON
    const card = $(`[data-id="${idJugador}"]`);
    const jugadorData = JSON.parse(card.attr('data-jugador').replace(/&apos;/g, "'"));
    
    const iconosMoneda = {
        'doradas': '<img src="../imgs/uploads/coins/doradas.webp" alt="Doradas" class="w-5 h-5 inline-block">',
        'moradas': '<img src="../imgs/uploads/coins/moradas.webp" alt="Moradas" class="w-5 h-5 inline-block">',
        'plateadas': '<img src="../imgs/uploads/coins/plateadas.webp" alt="Plateadas" class="w-5 h-5 inline-block">',
        'azules': '<img src="../imgs/uploads/coins/azules.webp" alt="Azules" class="w-5 h-5 inline-block">',
        'rojas': '<img src="../imgs/uploads/coins/rojas.webp" alt="Rojas" class="w-5 h-5 inline-block">'
    };
    
    console.log(idJugador, jugadorData);

    Swal.fire({
        title: '¿Fichar jugador?',
        html: `
            <div class="text-center">
                <p>¿Estás seguro de que quieres fichar a:</p>
                <h3 class="text-lg font-bold my-2">${jugadorData.nombre}</h3>
                <p class="text-sm text-gray-600">Precio: ${jugadorData.precio_cantidad} ${iconosMoneda[jugadorData.precio_tipo] || jugadorData.precio_tipo}</p>
            </div>
        `,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#10b981',
        cancelButtonColor: '#6b7280',
        confirmButtonText: '💰 Sí, fichar',
        cancelButtonText: '❌ Cancelar',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            // Mostrar loading
            Swal.fire({
                title: 'Procesando fichaje...',
                html: 'Por favor espera mientras procesamos el fichaje.',
                allowOutsideClick: false,
                allowEscapeKey: false,
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            // Hacer petición AJAX
            $.ajax({
                url: '/fichar/' + idJugador,
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-Token': csrfToken()
                },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        // Éxito
                        Swal.fire({
                            title: '¡Fichaje exitoso!',
                            text: `${response.player} ha sido fichado correctamente.`,
                            icon: 'success',
                            confirmButtonColor: '#10b981'
                        }).then(() => {
                            // Recargar la página o actualizar la vista
                            location.reload();
                        });
                    } else {
                        // Error desde el servidor
                        let title = 'Error';
                        let text = response.message || 'Error desconocido';
                        
                        switch(response.error) {
                            case 'already_signed':
                                title = 'Jugador ya fichado';
                                text = 'Este jugador ya está fichado por otro equipo.';
                                break;
                            case 'insufficient_funds':
                                title = 'Presupuesto insuficiente';
                                break;
                            default:
                                title = 'Error en el fichaje';
                        }
                        
                        Swal.fire({
                            title: title,
                            text: text,
                            icon: 'error',
                            confirmButtonColor: '#ef4444'
                        });
                    }
                },
                error: function(xhr, status, error) {
                    // Error de conexión o del servidor
                    Swal.fire({
                        title: 'Error de conexión',
                        text: 'No se pudo conectar con el servidor. Inténtalo de nuevo.',
                        icon: 'error',
                        confirmButtonColor: '#ef4444'
                    });
                }
            });
        }
    });
}

function updateStatus(message, type = 'success') {
    const statusEl = $('#statusIndicator');
    const statusText = $('#statusText');
    
    statusText.text(message);
    
    if (type === 'error') {
        statusEl.removeClass('bg-success').addClass('bg-error');
    } else {
        statusEl.removeClass('bg-error').addClass('bg-success');
    }
}

// Pausar polling cuando la pestaña no está activa (para ahorrar recursos)
document.addEventListener('visibilitychange', function() {
    if (document.hidden) {
        stopPolling();
        updateStatus('⏸️ Pausado - Pestaña inactiva');
    } else {
        startPolling();
        updateStatus('🟢 Conectado - Reanudando actualizaciones...');
    }
});

// Detener polling al salir de la página
window.addEventListener('beforeunload', function() {
    stopPolling();
});

// Función para abrir modal con información del jugador
function abrirModalJugador(idJugador) {
    const card = $(`.jugador-card[data-id="${idJugador}"]`);
    const jugadorData = JSON.parse(card.attr('data-jugador').replace(/&apos;/g, "'"));
    
    const iconosMoneda = {
        'doradas': '<img src="../imgs/uploads/coins/doradas.webp" alt="Doradas" class="w-5 h-5 inline-block">',
        'moradas': '<img src="../imgs/uploads/coins/moradas.webp" alt="Moradas" class="w-5 h-5 inline-block">',
        'plateadas': '<img src="../imgs/uploads/coins/plateadas.webp" alt="Plateadas" class="w-5 h-5 inline-block">',
        'azules': '<img src="../imgs/uploads/coins/azules.webp" alt="Azules" class="w-5 h-5 inline-block">',
        'rojas': '<img src="../imgs/uploads/coins/rojas.webp" alt="Rojas" class="w-5 h-5 inline-block">'
    };
    
    const traduccionEdad = {
        'primero': '1º',
        'segundo': '2º',
        'tercero': '3º',
        'adulto': 'Adulto'
    };
    
    const traduccionGenero = {
        'masculino': 'Masculino',
        'femenino': 'Femenino'
    };
    
    const modalHtml = `
        <div class="text-left">
            <div class="flex items-center gap-4 mb-4 border-b border-gray-600">
                <img src="../imgs/uploads/jugadores/${jugadorData.imagen}" 
                     alt="${jugadorData.nombre}" 
                     class="w-20 h-20">
                <div>
                    <h3 class="text-2xl font-bold">${jugadorData.nombre}</h3>
                    <p class="text-gray-400 capitalize">${jugadorData.posicion} | ${jugadorData.elemento}</p>
                </div>
            </div>
            
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <p class="text-sm text-gray-400">Precio</p>
                    <p class="font-bold text-lg">${jugadorData.precio_cantidad} ${iconosMoneda[jugadorData.precio_tipo] || jugadorData.precio_tipo}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-400">Edad</p>
                    <p class="font-bold capitalize">${traduccionEdad[jugadorData.edad] || jugadorData.edad}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-400">Género</p>
                    <p class="font-bold capitalize">${traduccionGenero[jugadorData.genero] || jugadorData.genero}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-400">Estado</p>
                    <p class="font-bold ${jugadorData.esFichado ? 'text-red-500' : 'text-green-500'}">
                        ${jugadorData.esFichado ? '✅ Fichado' : '💰 Disponible'}
                    </p>
                </div>
            </div>
            
            <div class="mb-4">
                <h4 class="text-lg font-semibold mb-2 border-b border-gray-600 pb-2">📊 Estadísticas</h4>
                <div class="grid grid-cols-2 gap-2 text-sm">
                    <div class="flex justify-between p-2 rounded">
                        <span class="text-gray-400">PE:</span>
                        <span class="font-bold">${jugadorData.pe || jugadorData.PE || 0}</span>
                    </div>
                    <div class="flex justify-between p-2 rounded">
                        <span class="text-gray-400">PT:</span>
                        <span class="font-bold">${jugadorData.pt || jugadorData.PT || 0}</span>
                    </div>
                    <div class="flex justify-between p-2 rounded">
                        <span class="text-gray-400">Tiro:</span>
                        <span class="font-bold">${jugadorData.tiro || 0}</span>
                    </div>
                    <div class="flex justify-between p-2 rounded">
                        <span class="text-gray-400">Regate:</span>
                        <span class="font-bold">${jugadorData.regate || 0}</span>
                    </div>
                    <div class="flex justify-between p-2 rounded">
                        <span class="text-gray-400">Defensa:</span>
                        <span class="font-bold">${jugadorData.defensa || 0}</span>
                    </div>
                    <div class="flex justify-between p-2 rounded">
                        <span class="text-gray-400">Control:</span>
                        <span class="font-bold">${jugadorData.control || 0}</span>
                    </div>
                    <div class="flex justify-between p-2 rounded">
                        <span class="text-gray-400">Técnica:</span>
                        <span class="font-bold">${jugadorData.tecnica || 0}</span>
                    </div>
                    <div class="flex justify-between p-2 rounded">
                        <span class="text-gray-400">Rapidez:</span>
                        <span class="font-bold">${jugadorData.rapidez || 0}</span>
                    </div>
                    <div class="flex justify-between p-2 rounded">
                        <span class="text-gray-400">Aguante:</span>
                        <span class="font-bold">${jugadorData.aguante || 0}</span>
                    </div>
                    <div class="flex justify-between p-2 rounded">
                        <span class="text-gray-400">Suerte:</span>
                        <span class="font-bold">${jugadorData.suerte || 0}</span>
                    </div>
                    <div class="flex justify-between p-2 rounded col-span-2">
                        <span class="text-gray-400">Libertad:</span>
                        <span class="font-bold">${jugadorData.libertad || 0}</span>
                    </div>
                </div>
            </div>
            
            ${jugadorData.esFichado ? `
                <div class="w-full py-3 px-4 bg-red-600 text-white rounded-lg text-center font-medium">
                    ✅ ¡Ya está fichado!
                </div>
            ` : `
                <button onclick="ficharJugador(${jugadorData.id_jugador});" 
                        class="w-full py-3 px-4 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors font-medium focus:ring-2 focus:ring-green-500 cursor-pointer">
                    💰 Fichar Jugador
                </button>
            `}
        </div>
    `;
    
    Swal.fire({
        title: 'Información del Jugador',
        html: modalHtml,
        width: '600px',
        confirmButtonColor: '#10b981',
        confirmButtonText: 'Cerrar',
        customClass: {
            popup: 'bg-gray-800 text-white'
        },
        background: '#1c1c1c',
        color: '#ffffff',
        showClass: {
            popup: 'swal2-show',
            backdrop: 'swal2-backdrop-show'
        },
        hideClass: {
            popup: 'swal2-hide',
            backdrop: 'swal2-backdrop-hide'
        },
        didOpen: () => {
            const popup = Swal.getPopup();
            popup.style.animation = 'expandFromCenter 0.3s ease-out';
        }
    });
    
    // Agregar estilos de animación si no existen
    if (!document.getElementById('modalAnimationStyles')) {
        const style = document.createElement('style');
        style.id = 'modalAnimationStyles';
        style.textContent = `
            @keyframes expandFromCenter {
                from {
                    width: 0;
                    opacity: 0;
                }
                to {
                    width: 600px;
                    opacity: 1;
                }
            }
            .swal2-hide {
                animation: swal2-hide 0.1s ease-out !important;
            }
        `;
        document.head.appendChild(style);
    }
}