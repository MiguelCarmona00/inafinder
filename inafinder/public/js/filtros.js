// Filtros del mercado de jugadores
let filtrosActivos = false;
let timeoutBusqueda = null;

$(document).ready(function() {
    initFiltros();
});

function initFiltros() {
    // Toggle para filtros básicos
    $('#toggleBasicFilters').click(function() {
        const container = $('#basicFiltersContainer');
        const icon = $('#basicFiltersIcon');
        
        container.slideToggle(300);
        icon.toggleClass('rotate-90');
    });

    // Toggle para filtros avanzados
    $('#toggleAdvancedFilters').click(function() {
        const container = $('#advancedFiltersContainer');
        const icon = $('#advancedFiltersIcon');
        
        container.slideToggle(300);
        icon.toggleClass('rotate-90');
    });

    // Búsqueda en tiempo real para el nombre
    $('#filtroNombre').on('input', function() {
        clearTimeout(timeoutBusqueda);
        timeoutBusqueda = setTimeout(function() {
            aplicarFiltrosAuto();
        }, 500); // Esperar 500ms después de que el usuario deje de escribir
    });

    // Aplicar filtros automáticamente en cambios de select
    $('#filtroPosicion, #filtroElemento, #filtroEdad, #filtroGenero, #filtroPrecioTipo').change(function() {
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

    // Botón aplicar filtros
    $('#aplicarFiltros').click(function() {
        aplicarFiltros();
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

    return filtros;
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

    // Resetear checkbox
    $('#soloDisponibles').prop('checked', true);

    // Cargar todos los jugadores
    filtrosActivos = false;
    cargarTodosLosJugadores();
    
    // Ocultar información de resultados
    $('#resultadosInfo').addClass('hidden');
    $('#noResultados').addClass('hidden');
}

function cargarTodosLosJugadores() {
    // Usar la función original del mercado.js
    if (typeof loadJugadores === 'function') {
        loadJugadores();
    }
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