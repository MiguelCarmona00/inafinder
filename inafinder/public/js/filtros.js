// Filtros del mercado de jugadores
let filtrosActivos = false;
let timeoutBusqueda = null;
let filtrosPanelVisible = false;

$(document).ready(function() {
    initFiltros();
});

function initFiltros() {
    // Toggle del panel de filtros (mismo patrón que el monedero: panel fijo
    // deslizante, esta vez desde la derecha)
    $('#toggleFiltersBtn').click(function() {
        const panel = $('#filtersPanel');
        const button = $(this);
        const icon = $('#filtersToggleIcon path');

        if (!filtrosPanelVisible) {
            panel.css('right', '0px');
            button.addClass('bg-success').removeClass('bg-darker');
            icon.attr('d', 'M6 18L18 6M6 6l12 12');
            filtrosPanelVisible = true;
        } else {
            // -30rem = -480px: más que el ancho del panel (w-96 = 384px) a
            // propósito, para que el shadow-2xl no se vea sangrando en el
            // borde derecho de la pantalla cuando está "cerrado"
            panel.css('right', '-480px');
            button.addClass('bg-darker').removeClass('bg-success');
            icon.attr('d', 'M4 6h16M4 12h16M4 18h16');
            filtrosPanelVisible = false;
        }
    });

    // Botón de cerrar dentro del panel (solo visible en móvil, ver md:hidden
    // en la vista): tocar fuera del panel para cerrarlo es incómodo cuando
    // ocupa casi toda la pantalla
    $('#closeFiltersBtn').click(function() {
        $('#toggleFiltersBtn').click();
    });

    // Cerrar el panel si se hace clic fuera de él
    $(document).click(function(event) {
        if (filtrosPanelVisible &&
            !$(event.target).closest('#filtersPanel').length &&
            !$(event.target).closest('#toggleFiltersBtn').length) {
            $('#toggleFiltersBtn').click();
        }
    });

    // Cerrar en pantallas muy pequeñas al redimensionar
    $(window).resize(function() {
        if ($(window).width() < 480 && filtrosPanelVisible) {
            $('#toggleFiltersBtn').click();
        }
    });

    // Búsqueda en tiempo real para el nombre
    $('#filtroNombre').on('input', function() {
        clearTimeout(timeoutBusqueda);
        timeoutBusqueda = setTimeout(function() {
            aplicarFiltrosAuto();
        }, 500); // Esperar 500ms después de que el usuario deje de escribir
    });

    // Aplicar filtros automáticamente en cambios de select
    $('#filtroPosicion, #filtroElemento, #filtroEdad, #filtroGenero, #filtroPrecioTipo, #filtroOrden').change(function() {
        aplicarFiltrosAuto();
    });

    // Aplicar filtros automáticamente en cambios de precio
    $('#filtroPrecioMin, #filtroPrecioMax').on('input', function() {
        clearTimeout(timeoutBusqueda);
        timeoutBusqueda = setTimeout(function() {
            aplicarFiltrosAuto();
        }, 800);
    });

    // Aplicar filtros automáticamente en filtros avanzados
    $('[id$="Min"], [id$="Max"]').on('input', function() {
        clearTimeout(timeoutBusqueda);
        timeoutBusqueda = setTimeout(function() {
            aplicarFiltrosAuto();
        }, 800);
    });

    // Checkbox solo disponibles
    $('#soloDisponibles').change(function() {
        aplicarFiltrosAuto();
    });

    // Botón limpiar filtros
    $('#limpiarFiltros').click(function() {
        limpiarFiltros();
    });
}

function aplicarFiltrosAuto() {
    // Solo aplicar si hay algún filtro activo
    if (hayFiltrosActivos()) {
        aplicarFiltros();
    } else {
        // Si no hay filtros, cargar todos los jugadores
        cargarTodosLosJugadores();
    }
}

function hayFiltrosActivos() {
    // Un orden distinto del predeterminado también cuenta como filtro activo:
    // fuerza la ruta de getJugadoresConFiltros(), que no tiene el LIMIT 10 sin
    // ordenar de getNoFichados() usado en la carga sin filtros.
    if ($('#filtroOrden').val() !== 'id_asc') return true;

    // Verificar filtros básicos
    if ($('#filtroNombre').val() !== '') return true;
    if ($('#filtroPosicion').val() !== '') return true;
    if ($('#filtroElemento').val() !== '') return true;
    if ($('#filtroEdad').val() !== '') return true;
    if ($('#filtroGenero').val() !== '') return true;
    if ($('#filtroPrecioTipo').val() !== '') return true;
    if ($('#filtroPrecioMin').val() !== '') return true;
    if ($('#filtroPrecioMax').val() !== '') return true;

    // Verificar filtros avanzados
    const estadisticas = ['pe', 'pt', 'tiro', 'regate', 'defensa', 'control', 'tecnica', 'rapidez', 'aguante', 'suerte', 'libertad'];
    for (let stat of estadisticas) {
        if ($('#' + stat + 'Min').val() !== '') return true;
        if ($('#' + stat + 'Max').val() !== '') return true;
    }

    return false;
}

function aplicarFiltros() {
    filtrosActivos = true;
    
    // Mostrar indicador de carga
    mostrarCargando();

    // Recopilar todos los filtros
    const filtros = recopilarFiltros();

    // Realizar petición AJAX
    $.ajax({
        url: '/checkUpdates',
        method: 'GET',
        data: filtros,
        dataType: 'json',
        success: function(response) {
            // Actualizar la vista con los resultados
            actualizarResultados(response);
            mostrarInfoResultados(response.total);
        },
        error: function(xhr, status, error) {
            console.error('Error al aplicar filtros:', error);
            console.error('Status:', status);
            console.error('Response text:', xhr.responseText);
            console.error('Status code:', xhr.status);
        },
        complete: function() {
            ocultarCargando();
        }
    });
}

function recopilarFiltros() {
    const filtros = {};

    // Filtros básicos
    if ($('#filtroNombre').val()) filtros.nombre = $('#filtroNombre').val();
    if ($('#filtroPosicion').val()) filtros.posicion = $('#filtroPosicion').val();
    if ($('#filtroElemento').val()) filtros.elemento = $('#filtroElemento').val();
    if ($('#filtroEdad').val()) filtros.edad = $('#filtroEdad').val();
    if ($('#filtroGenero').val()) filtros.genero = $('#filtroGenero').val();
    if ($('#filtroPrecioTipo').val()) filtros.precio_tipo = $('#filtroPrecioTipo').val();
    if ($('#filtroPrecioMin').val()) filtros.precio_min = $('#filtroPrecioMin').val();
    if ($('#filtroPrecioMax').val()) filtros.precio_max = $('#filtroPrecioMax').val();

    // Filtros avanzados
    const estadisticas = ['pe', 'pt', 'tiro', 'regate', 'defensa', 'control', 'tecnica', 'rapidez', 'aguante', 'suerte', 'libertad'];
    estadisticas.forEach(function(stat) {
        if ($('#' + stat + 'Min').val()) filtros[stat + '_min'] = $('#' + stat + 'Min').val();
        if ($('#' + stat + 'Max').val()) filtros[stat + '_max'] = $('#' + stat + 'Max').val();
    });

    // Solo disponibles
    if ($('#soloDisponibles').is(':checked')) {
        filtros.solo_disponibles = 'true';
    }

    // Orden (valor combinado "campo_direccion", p.ej. "precio_desc")
    const orden = $('#filtroOrden').val();
    if (orden && orden !== 'id_asc') {
        const separador = orden.lastIndexOf('_');
        filtros.orden = orden.substring(0, separador);
        filtros.direccion = orden.substring(separador + 1);
    }

    return filtros;
}

// El servidor manda fichados y no fichados en dos listas separadas. Para el
// orden "estándar" (predeterminado, por ID) hace falta mezclarlas e
// intercalarlas por id_jugador: si solo se concatenan (no fichados primero,
// fichados después) los fichados quedan agrupados al final en vez de en su
// posición natural. Usado por mercado.js (carga inicial/polling) y por
// cargarTodosLosJugadores() (Limpiar filtros) para que ambos coincidan.
function combinarJugadoresPorId(data) {
    const combinados = [];

    if (data.jugadoresNoFichados) {
        data.jugadoresNoFichados.forEach(function(jugador) {
            combinados.push({ ...jugador, esFichado: false });
        });
    }

    if (data.jugadoresFichados) {
        data.jugadoresFichados.forEach(function(jugador) {
            combinados.push({ ...jugador, esFichado: true });
        });
    }

    combinados.sort(function(a, b) {
        return a.id_jugador - b.id_jugador;
    });

    return combinados;
}

function actualizarResultados(data) {
    // Limpiar contenedor
    $('#jugadoresNoFichados').empty();

    if (data.total === 0) {
        // Mostrar mensaje de no resultados
        $('#noResultados').removeClass('hidden');
        $('#resultadosInfo').addClass('hidden');
    } else {
        // Ocultar mensaje de no resultados
        $('#noResultados').addClass('hidden');
        $('#resultadosInfo').removeClass('hidden');

        // Mostrar jugadores filtrados
        if (data.jugadoresNoFichados && data.jugadoresNoFichados.length > 0) {
            data.jugadoresNoFichados.forEach(function(jugador) {
                const card = createJugadorCard(jugador, false);
                $('#jugadoresNoFichados').append(card);
            });
        }

        if (data.jugadoresFichados && data.jugadoresFichados.length > 0) {
            data.jugadoresFichados.forEach(function(jugador) {
                const card = createJugadorCard(jugador, true);
                $('#jugadoresNoFichados').append(card);
            });
        }
    }
}

function mostrarInfoResultados(total) {
    let mensaje = '';
    if (total === 0) {
        mensaje = 'No se encontraron jugadores con los filtros aplicados';
    } else if (total === 1) {
        mensaje = 'Se encontró 1 jugador';
    } else {
        mensaje = `Se encontraron ${total} jugadores`;
    }
    
    $('#resultadosTexto').text(mensaje);
}

function limpiarFiltros() {
    // Limpiar todos los campos
    $('#filtroNombre').val('');
    $('#filtroPosicion').val('');
    $('#filtroElemento').val('');
    $('#filtroEdad').val('');
    $('#filtroGenero').val('');
    $('#filtroPrecioTipo').val('');
    $('#filtroPrecioMin').val('');
    $('#filtroPrecioMax').val('');

    // Limpiar filtros avanzados
    const estadisticas = ['pe', 'pt', 'tiro', 'regate', 'defensa', 'control', 'tecnica', 'rapidez', 'aguante', 'suerte', 'libertad'];
    estadisticas.forEach(function(stat) {
        $('#' + stat + 'Min').val('');
        $('#' + stat + 'Max').val('');
    });

    // Resetear checkbox (sin marcar, para que se vea la lista completa:
    // disponibles + fichados)
    $('#soloDisponibles').prop('checked', false);

    // Resetear orden
    $('#filtroOrden').val('id_asc');

    // Cargar todos los jugadores
    filtrosActivos = false;
    cargarTodosLosJugadores();
    
    // Ocultar información de resultados
    $('#resultadosInfo').addClass('hidden');
    $('#noResultados').addClass('hidden');
}

function cargarTodosLosJugadores() {
    // No reutilizar loadJugadores()/updateJugadores() de mercado.js: ese parche
    // incremental está pensado para el polling (solo actualiza tarjetas ya
    // presentes en el DOM) y no vacía el contenedor, así que si veníamos de un
    // filtrado (el DOM solo tiene ese subconjunto) nunca se reconstruye la
    // vista completa. Tampoco reutilizamos actualizarResultados() tal cual:
    // esta vista es el orden "estándar" (por ID, fichados intercalados), no un
    // resultado de filtro/orden explícito.
    mostrarCargando();

    $.ajax({
        url: '/checkUpdates',
        method: 'GET',
        dataType: 'json',
        success: function(response) {
            $('#jugadoresNoFichados').empty();

            const combinados = combinarJugadoresPorId(response);
            let html = '';
            combinados.forEach(function(jugador) {
                html += createJugadorCard(jugador, jugador.esFichado);
            });
            $('#jugadoresNoFichados').html(html);

            // Vista sin filtros: no mostrar el cuadro de "resultados" (no hay
            // ningún filtro que "explicar")
            $('#resultadosInfo').addClass('hidden');
            $('#noResultados').addClass('hidden');
        },
        complete: function() {
            ocultarCargando();
        }
    });
}

function mostrarCargando() {
    $('#jugadoresNoFichados').html(`
        <div class="col-span-full flex items-center justify-center py-8">
            <div class="flex items-center gap-3">
                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-success"></div>
                <span class="text-lg">Buscando jugadores...</span>
            </div>
        </div>
    `);
}

function ocultarCargando() {
    // La función actualizarResultados se encarga de limpiar el contenido
}

// Función para verificar si hay filtros activos (para uso desde mercado.js)
function estaFiltrandoActivo() {
    return filtrosActivos;
}

// Función para reactivar filtros después de una actualización automática
function reaplicarFiltros() {
    if (filtrosActivos && hayFiltrosActivos()) {
        aplicarFiltros();
    }
}