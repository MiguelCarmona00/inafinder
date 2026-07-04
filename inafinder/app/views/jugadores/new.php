<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Añadir Jugadores</title>
    <link href="/../css/output.css" rel="stylesheet">
    <link rel="icon" type="image/x-icon" href="imgs/src/logo.ico">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body class="bg-primary font-sans text-white min-h-screen pt-16">

    <?php include '../app/views/partials/navbar.php'; ?>

    <div class="container mx-auto px-4 py-8">
        <div class="max-w-4xl mx-auto">
            <h1 class="text-3xl font-bold mb-6 text-center">Añadir Jugadores</h1>
            
            <!-- Botón para añadir formularios -->
            <div class="flex justify-center mb-6">
                <div class="flex justify-center mb-6">
                    <a href="/mercado" class="bg-info px-6 py-3 bg-warning text-white rounded-lg hover:bg-yellow-600 transition-colors font-medium focus:ring-2 focus:ring-warning">
                        📋 Ir a Mercado
                    </a>
                </div>
            </div>

            <form action="/jugadores" method="POST" enctype="multipart/form-data" id="mainForm">
                <?php echo \App\Core\Csrf::field(); ?>
                <div id="formsContainer">
                    <!-- Primer formulario -->
                    <div class="jugador-form bg-darker rounded-lg p-6 mb-6 shadow-[0_2px_8px_rgba(0,0,0,0.3)] relative" data-form-index="0">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-xl font-bold">Jugador #1</h3>
                            <button type="button" class="removeForm text-error hover:text-red-400 text-2xl hidden" title="Eliminar formulario">
                                ❌
                            </button>
                        </div>
                        
                        <div class="space-y-4">
                            <!-- Foto del jugador -->
                            <div>
                                <label class="block text-sm font-medium text-white mb-2">Foto del Jugador</label>
                                <input type="file" name="jugadores[0][imagen]" accept="image/*" required 
                                       class="block w-full text-sm text-light-gray file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-gray file:text-white hover:file:bg-light-gray file:cursor-pointer bg-gray rounded-lg border border-light-gray focus:border-success focus:ring-1 focus:ring-success transition-colors">
                            </div>

                            <!-- Nombre -->
                            <div>
                                <label class="block text-sm font-medium text-white mb-2">Nombre</label>
                                <input type="text" name="jugadores[0][nombre]" required 
                                       class="w-full p-3 bg-gray text-white border border-light-gray rounded-lg focus:border-success focus:ring-1 focus:ring-success transition-colors placeholder-light-gray">
                            </div>

                            <!-- Posición -->
                            <div>
                                <label class="block text-sm font-medium text-white mb-2">Posición</label>
                                <select name="jugadores[0][posicion]" required 
                                        class="w-full p-3 bg-gray text-white border border-light-gray rounded-lg focus:border-success focus:ring-1 focus:ring-success transition-colors">
                                    <option value="portero">Portero</option>
                                    <option value="">Selecciona una posición</option>
                                    <option value="defensa">Defensa</option>
                                    <option value="centro">Centro</option>
                                    <option value="delantero">Delantero</option>
                                </select>
                            </div>

                            <!-- Elemento -->
                            <div>
                                <label class="block text-sm font-medium text-white mb-2">Elemento</label>
                                <select name="jugadores[0][elemento]" required 
                                        class="w-full p-3 bg-gray text-white border border-light-gray rounded-lg focus:border-success focus:ring-1 focus:ring-success transition-colors">
                                    <option value="montaña">⛰️ Montaña</option>
                                    <option value="">Selecciona un elemento</option>
                                    <option value="fuego">🔥 Fuego</option>
                                    <option value="aire">💨 Aire</option>
                                    <option value="bosque">🌲 Bosque</option>
                                </select>
                            </div>

                            <!-- Edad de jugador -->
                            <div>
                                <label class="block text-sm font-medium text-white mb-2">Edad/Curso del Jugador</label>
                                <select name="jugadores[0][edad]" required 
                                        class="w-full p-3 bg-gray text-white border border-light-gray rounded-lg focus:border-success focus:ring-1 focus:ring-success transition-colors">
                                    <option value="">Selecciona un tipo</option>
                                    <option value="primero">1️⃣ Primero</option>
                                    <option value="segundo">2️⃣ Segundo</option>
                                    <option value="tercero">3️⃣ Tercero</option>
                                    <option value="escolar">👲🏻 Escolar</option>
                                    <option value="adulto">👨🏻 Adulto</option>
                                    <option value="desconocido">❓ Desconocido</option>
                                </select>
                            </div>

                            <!-- Genero de jugador -->
                            <div>
                                <label class="block text-sm font-medium text-white mb-2">Género del Jugador</label>
                                <select name="jugadores[0][genero]" required 
                                        class="w-full p-3 bg-gray text-white border border-light-gray rounded-lg focus:border-success focus:ring-1 focus:ring-success transition-colors">
                                    <option value="">Selecciona un tipo</option>
                                    <option value="masculino">♂️ Masculino</option>
                                    <option value="femenino">♀️ Femenino</option>
                                    <option value="desconocido">❓ Desconocido</option>
                                </select>
                            </div>

                            <!-- Precio -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-white mb-2">Cantidad de Monedas</label>
                                    <input type="number" name="jugadores[0][precio_cantidad]" min="1" required 
                                           class="w-full p-3 bg-gray text-white border border-light-gray rounded-lg focus:border-success focus:ring-1 focus:ring-success transition-colors placeholder-light-gray">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-white mb-2">Tipo de Monedas</label>
                                    <select name="jugadores[0][precio_tipo]" required 
                                            class="w-full p-3 bg-gray text-white border border-light-gray rounded-lg focus:border-success focus:ring-1 focus:ring-success transition-colors">
                                        <option value="">Selecciona el tipo</option>
                                        <option value="doradas">🟡 Doradas</option>
                                        <option value="moradas">🟣 Moradas</option>
                                        <option value="plateadas">⚪ Plateadas</option>
                                        <option value="rojas">🔴 Rojas</option>
                                        <option value="azules">🔵 Azules</option>
                                    </select>
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-white mb-2">Estadísticas</label>
                                <textarea name="jugadores[0][estadisticas]" rows="12" placeholder="GP: 142&#10;TP: 118&#10;Kick: 149&#10;Dribbling: 112&#10;Block: 83&#10;Catch: 75&#10;Technique: 90&#10;Speed: 140 (150)&#10;Stamina: 88&#10;Lucky: 104&#10;Freedom: 220" 
                                        class="w-full p-3 bg-gray text-white border border-light-gray rounded-lg focus:border-success focus:ring-1 focus:ring-success transition-colors placeholder-light-gray resize-vertical"></textarea>
                                <p class="text-xs text-light-gray mt-1">Formato: NombreStat: Valor. Una por línea. Los números entre paréntesis se ignoran.</p>
                            </div>

                            <!-- Técnicas del Jugador -->
                            <div class="mt-6">
                                <h4 class="text-lg font-bold mb-4 text-success">⚡ Técnicas del Jugador</h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <!-- Técnica 1 -->
                                    <div class="bg-gray p-4 rounded-lg">
                                        <h5 class="font-medium mb-2 text-info">Técnica 1</h5>
                                        <div class="space-y-2">
                                            <div class="relative">
                                                <input type="text" 
                                                    name="jugadores[0][tecnicas][tecnica_1_nombre]" 
                                                    placeholder="Buscar técnica..."
                                                    class="tecnica-input w-full p-2 bg-darker text-white border border-light-gray rounded focus:border-success focus:ring-1 focus:ring-success transition-colors"
                                                    data-index="1"
                                                    data-jugador="0">
                                                <input type="hidden" name="jugadores[0][tecnicas][tecnica_1_id]" class="tecnica-id">
                                                <input type="hidden" name="jugadores[0][tecnicas][tecnica_1_tabla]" class="tecnica-tabla">
                                                <div class="tecnica-suggestions absolute z-10 w-full bg-darker border border-light-gray rounded mt-1 hidden max-h-48 overflow-y-auto"></div>
                                            </div>
                                            <input type="number" 
                                                name="jugadores[0][tecnicas][tecnica_1_nivel]" 
                                                placeholder="Nivel (opcional)"
                                                min="1"
                                                class="w-full p-2 bg-darker text-white border border-light-gray rounded focus:border-success focus:ring-1 focus:ring-success transition-colors">
                                        </div>
                                    </div>

                                    <!-- Técnica 2 -->
                                    <div class="bg-gray p-4 rounded-lg">
                                        <h5 class="font-medium mb-2 text-info">Técnica 2</h5>
                                        <div class="space-y-2">
                                            <div class="relative">
                                                <input type="text" 
                                                    name="jugadores[0][tecnicas][tecnica_2_nombre]" 
                                                    placeholder="Buscar técnica..."
                                                    class="tecnica-input w-full p-2 bg-darker text-white border border-light-gray rounded focus:border-success focus:ring-1 focus:ring-success transition-colors"
                                                    data-index="2"
                                                    data-jugador="0">
                                                <input type="hidden" name="jugadores[0][tecnicas][tecnica_2_id]" class="tecnica-id">
                                                <input type="hidden" name="jugadores[0][tecnicas][tecnica_2_tabla]" class="tecnica-tabla">
                                                <div class="tecnica-suggestions absolute z-10 w-full bg-darker border border-light-gray rounded mt-1 hidden max-h-48 overflow-y-auto"></div>
                                            </div>
                                            <input type="number" 
                                                name="jugadores[0][tecnicas][tecnica_2_nivel]" 
                                                placeholder="Nivel (opcional)"
                                                min="1"
                                                class="w-full p-2 bg-darker text-white border border-light-gray rounded focus:border-success focus:ring-1 focus:ring-success transition-colors">
                                        </div>
                                    </div>

                                    <!-- Técnica 3 -->
                                    <div class="bg-gray p-4 rounded-lg">
                                        <h5 class="font-medium mb-2 text-info">Técnica 3</h5>
                                        <div class="space-y-2">
                                            <div class="relative">
                                                <input type="text" 
                                                    name="jugadores[0][tecnicas][tecnica_3_nombre]" 
                                                    placeholder="Buscar técnica..."
                                                    class="tecnica-input w-full p-2 bg-darker text-white border border-light-gray rounded focus:border-success focus:ring-1 focus:ring-success transition-colors"
                                                    data-index="3"
                                                    data-jugador="0">
                                                <input type="hidden" name="jugadores[0][tecnicas][tecnica_3_id]" class="tecnica-id">
                                                <input type="hidden" name="jugadores[0][tecnicas][tecnica_3_tabla]" class="tecnica-tabla">
                                                <div class="tecnica-suggestions absolute z-10 w-full bg-darker border border-light-gray rounded mt-1 hidden max-h-48 overflow-y-auto"></div>
                                            </div>
                                            <input type="number" 
                                                name="jugadores[0][tecnicas][tecnica_3_nivel]" 
                                                placeholder="Nivel (opcional)"
                                                min="1"
                                                class="w-full p-2 bg-darker text-white border border-light-gray rounded focus:border-success focus:ring-1 focus:ring-success transition-colors">
                                        </div>
                                    </div>

                                    <!-- Técnica 4 -->
                                    <div class="bg-gray p-4 rounded-lg">
                                        <h5 class="font-medium mb-2 text-info">Técnica 4</h5>
                                        <div class="space-y-2">
                                            <div class="relative">
                                                <input type="text" 
                                                    name="jugadores[0][tecnicas][tecnica_4_nombre]" 
                                                    placeholder="Buscar técnica..."
                                                    class="tecnica-input w-full p-2 bg-darker text-white border border-light-gray rounded focus:border-success focus:ring-1 focus:ring-success transition-colors"
                                                    data-index="4"
                                                    data-jugador="0">
                                                <input type="hidden" name="jugadores[0][tecnicas][tecnica_4_id]" class="tecnica-id">
                                                <input type="hidden" name="jugadores[0][tecnicas][tecnica_4_tabla]" class="tecnica-tabla">
                                                <div class="tecnica-suggestions absolute z-10 w-full bg-darker border border-light-gray rounded mt-1 hidden max-h-48 overflow-y-auto"></div>
                                            </div>
                                            <input type="number" 
                                                name="jugadores[0][tecnicas][tecnica_4_nivel]" 
                                                placeholder="Nivel (opcional)"
                                                min="1"
                                                class="w-full p-2 bg-darker text-white border border-light-gray rounded focus:border-success focus:ring-1 focus:ring-success transition-colors">
                                        </div>
                                    </div>

                                    <!-- Técnica 5 -->
                                    <div class="bg-gray p-4 rounded-lg">
                                        <h5 class="font-medium mb-2 text-info">Técnica 5</h5>
                                        <div class="space-y-2">
                                            <div class="relative">
                                                <input type="text" 
                                                    name="jugadores[0][tecnicas][tecnica_5_nombre]" 
                                                    placeholder="Buscar técnica..."
                                                    class="tecnica-input w-full p-2 bg-darker text-white border border-light-gray rounded focus:border-success focus:ring-1 focus:ring-success transition-colors"
                                                    data-index="5"
                                                    data-jugador="0">
                                                <input type="hidden" name="jugadores[0][tecnicas][tecnica_5_id]" class="tecnica-id">
                                                <input type="hidden" name="jugadores[0][tecnicas][tecnica_5_tabla]" class="tecnica-tabla">
                                                <div class="tecnica-suggestions absolute z-10 w-full bg-darker border border-light-gray rounded mt-1 hidden max-h-48 overflow-y-auto"></div>
                                            </div>
                                            <input type="number" 
                                                name="jugadores[0][tecnicas][tecnica_5_nivel]" 
                                                placeholder="Nivel (opcional)"
                                                min="1"
                                                class="w-full p-2 bg-darker text-white border border-light-gray rounded focus:border-success focus:ring-1 focus:ring-success transition-colors">
                                        </div>
                                    </div>

                                    <!-- Técnica 6 -->
                                    <div class="bg-gray p-4 rounded-lg">
                                        <h5 class="font-medium mb-2 text-info">Técnica 6</h5>
                                        <div class="space-y-2">
                                            <div class="relative">
                                                <input type="text" 
                                                    name="jugadores[0][tecnicas][tecnica_6_nombre]" 
                                                    placeholder="Buscar técnica..."
                                                    class="tecnica-input w-full p-2 bg-darker text-white border border-light-gray rounded focus:border-success focus:ring-1 focus:ring-success transition-colors"
                                                    data-index="6"
                                                    data-jugador="0">
                                                <input type="hidden" name="jugadores[0][tecnicas][tecnica_6_id]" class="tecnica-id">
                                                <input type="hidden" name="jugadores[0][tecnicas][tecnica_6_tabla]" class="tecnica-tabla">
                                                <div class="tecnica-suggestions absolute z-10 w-full bg-darker border border-light-gray rounded mt-1 hidden max-h-48 overflow-y-auto"></div>
                                            </div>
                                            <input type="number" 
                                                name="jugadores[0][tecnicas][tecnica_6_nivel]" 
                                                placeholder="Nivel (opcional)"
                                                min="1"
                                                class="w-full p-2 bg-darker text-white border border-light-gray rounded focus:border-success focus:ring-1 focus:ring-success transition-colors">
                                        </div>
                                    </div>
                                </div>
                            </div>


                        </div>
                    </div>
                </div>

                <!-- Botones principales -->
                <div class="flex gap-4 pt-4 justify-center">
                    <button type="button" id="addForm" class="bg-info px-6 py-3 bg-success text-white rounded-lg hover:bg-green-600 transition-colors font-medium focus:ring-2 focus:ring-success">
                        ➕ Añadir Otro Jugador
                    </button>
                    <button type="submit" 
                            class="px-8 py-3 bg-success text-white rounded-lg hover:bg-green-600 transition-colors font-medium focus:ring-2 focus:ring-success focus:ring-offset-2 focus:ring-offset-darker">
                        Añadir Todos los Jugadores
                    </button>
                    <span class="ml-4 text-light-gray self-center">
                        Formularios: <span id="formCounter">1</span>/10
                    </span>
                </div>

                <div class="flex justify-center mt-3">
                     <a href="/mercado" 
                        class="px-8 py-3 bg-gray text-white rounded-lg hover:bg-light-gray transition-colors font-medium text-center focus:ring-2 focus:ring-gray focus:ring-offset-2 focus:ring-offset-darker">
                        Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            let formCount = 1;
            const maxForms = 10;

            // Función para actualizar el contador
            function updateCounter() {
                $('#formCounter').text(formCount);
                $('#addForm').prop('disabled', formCount >= maxForms);
                
                if (formCount >= maxForms) {
                    $('#addForm').addClass('bg-gray hover:bg-gray cursor-not-allowed').removeClass('bg-success hover:bg-green-600');
                } else {
                    $('#addForm').addClass('bg-success hover:bg-green-600').removeClass('bg-gray hover:bg-gray cursor-not-allowed');
                }
            }

            // Función para mostrar/ocultar botones de eliminar
            function toggleRemoveButtons() {
                if (formCount > 1) {
                    $('.removeForm').removeClass('hidden');
                } else {
                    $('.removeForm').addClass('hidden');
                }
            }

            // Función para actualizar índices de los formularios
            function updateFormIndices() {
                $('.jugador-form').each(function(index) {
                    $(this).attr('data-form-index', index);
                    $(this).find('h3').text('Jugador #' + (index + 1));
                    
                    // Actualizar nombres de los campos
                    $(this).find('input, select, textarea').each(function() {
                        const name = $(this).attr('name');
                        if (name && name.includes('jugadores[')) {
                            const newName = name.replace(/jugadores\[\d+\]/, 'jugadores[' + index + ']');
                            $(this).attr('name', newName);
                        }
                    });

                    // Actualizar data-jugador en inputs de técnicas
                    $(this).find('.tecnica-input').each(function() {
                        $(this).attr('data-jugador', index);
                    });
                });
            }

            // Añadir nuevo formulario
            $('#addForm').click(function() {
                if (formCount < maxForms) {
                    const newForm = $('.jugador-form').first().clone();
                    
                    // Limpiar valores del formulario clonado
                    newForm.find('input[type="text"], input[type="number"], input[type="file"]').val('');
                    newForm.find('select').prop('selectedIndex', 0);
                    newForm.find('textarea').val('');
                    newForm.find('.tecnica-id').val('');
                    newForm.find('.tecnica-suggestions').empty().addClass('hidden');
                    
                    // Añadir al contenedor
                    $('#formsContainer').append(newForm);
                    
                    formCount++;
                    updateFormIndices();
                    updateCounter();
                    toggleRemoveButtons();
                    
                    // Reinicializar autocompletado para el nuevo formulario
                    initAutocompleteTecnicas();
                    
                    // Scroll suave al nuevo formulario
                    $('html, body').animate({
                        scrollTop: newForm.offset().top - 100
                    }, 500);
                }
            });

            // Eliminar formulario
            $(document).on('click', '.removeForm', function() {
                if (formCount > 1) {
                    $(this).closest('.jugador-form').fadeOut(300, function() {
                        $(this).remove();
                        formCount--;
                        updateFormIndices();
                        updateCounter();
                        toggleRemoveButtons();
                    });
                }
            });

            // Confirmación antes de enviar
            $('#mainForm').submit(function(e) {
                const message = formCount === 1 ? 
                    '¿Estás seguro de que quieres añadir este jugador?' : 
                    `¿Estás seguro de que quieres añadir estos ${formCount} jugadores?`;
                
                if (!confirm(message)) {
                    e.preventDefault();
                }
            });

            // Autocompletado de técnicas
            function initAutocompleteTecnicas() {
                // Remover eventos previos para evitar duplicados
                $(document).off('input', '.tecnica-input');
                $(document).off('click', '.suggestion-item');
                $(document).off('blur', '.tecnica-input');

                // Autocompletado
                $(document).on('input', '.tecnica-input', function() {
                    const $input = $(this);
                    const $suggestions = $input.siblings('.tecnica-suggestions');
                    const $hiddenId = $input.siblings('.tecnica-id');
                    const $hiddenTabla = $input.siblings('.tecnica-tabla');
                    const termino = $input.val().trim();

                    if (termino.length < 2) {
                        $suggestions.empty().addClass('hidden');
                        $hiddenId.val('');
                        $hiddenTabla.val('');
                        return;
                    }

                    $.ajax({
                        url: '/tecnicasBuscar',
                        method: 'GET',
                        data: { termino: termino },
                        dataType: 'json',
                        success: function(data) {
                            $suggestions.empty();
                            
                            if (data.length > 0) {
                                data.forEach(function(item) {
                                    const tipoIcon = {
                                        'tiro': '⚽',
                                        'regate': '🏃',
                                        'bloqueo': '🛡️',
                                        'parada': '🥅',
                                        'talento': '⭐'
                                    };
                                    
                                    // Crear HTML con imagen para técnicas (no talentos)
                                    let suggestionHtml = '';
                                    
                                    if (item.tipo !== 'talento') {
                                        let carpetaImg = '';
                                        switch(item.tipo) {
                                            case 'tiro':
                                                carpetaImg = 'tiros';
                                                break;
                                            case 'regate':
                                                carpetaImg = 'regates';
                                                break;
                                            case 'bloqueo':
                                                carpetaImg = 'bloqueos';
                                                break;
                                            case 'parada':
                                                carpetaImg = 'paradas';
                                                break;
                                            default:
                                                carpetaImg = 'tiros'; // fallback
                                        }

                                        // Mostrar miniatura para técnicas
                                        // console.log('Mostrando técnica:', item.imagen, 'Tipo:', item.tipo, 'Carpeta:', carpetaImg);

                                        suggestionHtml = `
                                            <div class="flex items-center justify-between gap-2">
                                            <div>
                                                <span class="font-medium">${tipoIcon[item.tipo] || '🔧'} ${item.nombre}</span><br>
                                                <span class="text-xs text-light-gray">${item.tipo} (${item.tabla})</span>
                                            </div>
                                            <img src="../imgs/uploads/${carpetaImg}/${item.imagen}" 
                                                 alt="${item.nombre}" 
                                                 class="w-32 h-16 rounded object-center flex-shrink-0"
                                                 onerror="this.style.display='none'">
                                            </div>
                                        `;

                                    } else {
                                        // Solo texto para talentos
                                        suggestionHtml = `
                                            <div>
                                                <span class="font-medium">${tipoIcon[item.tipo] || '🔧'} ${item.nombre}</span><br>
                                                <span class="text-xs text-light-gray">${item.tipo} (${item.tabla})</span>
                                            </div>
                                        `;
                                    }
                                    
                                    const $suggestion = $('<div>')
                                        .addClass('suggestion-item p-2 hover:bg-light-gray cursor-pointer border-b border-light-gray last:border-b-0')
                                        .html(suggestionHtml)
                                        .attr('data-id', item.id)
                                        .attr('data-nombre', item.nombre)
                                        .attr('data-tabla', item.tabla);
                                    
                                    $suggestions.append($suggestion);
                                });
                                $suggestions.removeClass('hidden');
                            } else {
                                $suggestions.addClass('hidden');
                            }
                        },
                        error: function() {
                            $suggestions.empty().addClass('hidden');
                        }
                    });
                });

                // Seleccionar sugerencia
                $(document).on('click', '.suggestion-item', function() {
                    const $item = $(this);
                    const $suggestions = $item.parent();
                    const $input = $suggestions.siblings('.tecnica-input');
                    const $hiddenId = $suggestions.siblings('.tecnica-id');
                    const $hiddenTabla = $suggestions.siblings('.tecnica-tabla');
                    
                    $input.val($item.attr('data-nombre'));
                    $hiddenId.val($item.attr('data-id'));
                    $hiddenTabla.val($item.attr('data-tabla'));
                    $suggestions.empty().addClass('hidden');
                    
                    // Añadir debug para verificar que los valores se están estableciendo
                    // console.log('Técnica seleccionada:', {
                    //     nombre: $item.attr('data-nombre'),
                    //     id: $item.attr('data-id'),
                    //     tabla: $item.attr('data-tabla')
                    // });
                });

                // Ocultar sugerencias al perder foco
                $(document).on('blur', '.tecnica-input', function() {
                    const $input = $(this);
                    const $suggestions = $input.siblings('.tecnica-suggestions');
                    
                    setTimeout(function() {
                        $suggestions.empty().addClass('hidden');
                    }, 200);
                });
            }

            // Inicializar todo
            updateCounter();
            toggleRemoveButtons();
            initAutocompleteTecnicas();
        });
        </script>
</body>
</html>