<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Jugador</title>
    <link href="/../css/output.css" rel="stylesheet">
    <link rel="icon" type="image/x-icon" href="imgs/src/logo.ico">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body class="bg-primary font-sans text-white min-h-screen pt-16">

    <?php include '../app/views/partials/navbar.php'; ?>

    <div class="container mx-auto px-4 py-8">
        <div class="max-w-4xl mx-auto">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-3xl font-bold">Editar Jugador</h1>
                <a href="/jugadoresList" class="px-6 py-3 bg-gray text-white rounded-lg hover:bg-light-gray transition-colors font-medium">
                    ← Volver a Lista
                </a>
            </div>

            <form action="/jugadoresEdit/<?php echo (int) $data['jugador']['id_jugador']; ?>" method="POST" enctype="multipart/form-data" id="editForm">
                <?php echo \App\Core\Csrf::field(); ?>
                <div class="bg-darker rounded-lg p-6 mb-6 shadow-[0_2px_8px_rgba(0,0,0,0.3)]">
                    <h3 class="text-xl font-bold mb-4"><?php echo htmlspecialchars($data['jugador']['nombre']); ?></h3>
                    
                    <div class="space-y-4">
                        <!-- Foto actual y nueva -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-white mb-2">Foto Actual</label>
                                <?php if (!empty($data['jugador']['imagen'])): ?>
                                    <img src="../imgs/uploads/jugadores/<?php echo htmlspecialchars($data['jugador']['imagen']); ?>" 
                                         alt="<?php echo htmlspecialchars($data['jugador']['nombre']); ?>"
                                         class="w-32 h-32 object-cover border-2 border-success rounded-lg">
                                <?php else: ?>
                                    <div class="w-32 h-32 bg-darker border-2 border-success rounded-lg flex items-center justify-center text-4xl">
                                        ⚽
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-white mb-2">Nueva Foto (opcional)</label>
                                <input type="file" name="imagen" accept="image/*" 
                                       class="block w-full text-sm text-light-gray file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-gray file:text-white hover:file:bg-light-gray file:cursor-pointer bg-gray rounded-lg border border-light-gray focus:border-success focus:ring-1 focus:ring-success transition-colors">
                                <p class="text-xs text-light-gray mt-1">Deja vacío para mantener la imagen actual</p>
                            </div>
                        </div>

                        <!-- Nombre -->
                        <div>
                            <label class="block text-sm font-medium text-white mb-2">Nombre</label>
                            <input type="text" name="jugador[nombre]" 
                                   value="<?php echo htmlspecialchars($data['jugador']['nombre']); ?>"
                                   class="w-full p-3 bg-gray text-white border border-light-gray rounded-lg focus:border-success focus:ring-1 focus:ring-success transition-colors placeholder-light-gray">
                        </div>

                        <!-- Posición -->
                        <div>
                            <label class="block text-sm font-medium text-white mb-2">Posición</label>
                            <select name="jugador[posicion]" 
                                    class="w-full p-3 bg-gray text-white border border-light-gray rounded-lg focus:border-success focus:ring-1 focus:ring-success transition-colors">
                                <option value="">Selecciona una posición</option>
                                <option value="portero" <?php echo ($data['jugador']['posicion'] === 'Portero') ? 'selected' : ''; ?>>Portero</option>
                                <option value="defensa" <?php echo ($data['jugador']['posicion'] === 'Defensa') ? 'selected' : ''; ?>>Defensa</option>
                                <option value="centro" <?php echo ($data['jugador']['posicion'] === 'centro') ? 'selected' : ''; ?>>Centro</option>
                                <option value="delantero" <?php echo ($data['jugador']['posicion'] === 'delantero') ? 'selected' : ''; ?>>Delantero</option>
                            </select>
                        </div>

                        <!-- Elemento -->
                        <div>
                            <label class="block text-sm font-medium text-white mb-2">Elemento</label>
                            <select name="jugador[elemento]" 
                                    class="w-full p-3 bg-gray text-white border border-light-gray rounded-lg focus:border-success focus:ring-1 focus:ring-success transition-colors">
                                <option value="">Selecciona un elemento</option>
                                <option value="fuego" <?php echo ($data['jugador']['elemento'] === 'fuego') ? 'selected' : ''; ?>>🔥 Fuego</option>
                                <option value="aire" <?php echo ($data['jugador']['elemento'] === 'aire') ? 'selected' : ''; ?>>💨 Aire</option>
                                <option value="bosque" <?php echo ($data['jugador']['elemento'] === 'bosque') ? 'selected' : ''; ?>>🌲 Bosque</option>
                                <option value="montaña" <?php echo ($data['jugador']['elemento'] === 'montaña') ? 'selected' : ''; ?>>⛰️ Montaña</option>
                            </select>
                        </div>

                        <!-- Precio -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-white mb-2">Cantidad de Monedas</label>
                                <input type="number" name="jugador[precio_cantidad]" min="1"  
                                       value="<?php echo htmlspecialchars($data['jugador']['precio_cantidad']); ?>"
                                       class="w-full p-3 bg-gray text-white border border-light-gray rounded-lg focus:border-success focus:ring-1 focus:ring-success transition-colors placeholder-light-gray">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-white mb-2">Tipo de Monedas</label>
                                <select name="jugador[precio_tipo]"  
                                        class="w-full p-3 bg-gray text-white border border-light-gray rounded-lg focus:border-success focus:ring-1 focus:ring-success transition-colors">
                                    <option value="">Selecciona el tipo</option>
                                    <option value="doradas" <?php echo ($data['jugador']['precio_tipo'] === 'doradas') ? 'selected' : ''; ?>>🟡 Doradas</option>
                                    <option value="moradas" <?php echo ($data['jugador']['precio_tipo'] === 'moradas') ? 'selected' : ''; ?>>🟣 Moradas</option>
                                    <option value="plateadas" <?php echo ($data['jugador']['precio_tipo'] === 'plateadas') ? 'selected' : ''; ?>>⚪ Plateadas</option>
                                    <option value="rojas" <?php echo ($data['jugador']['precio_tipo'] === 'rojas') ? 'selected' : ''; ?>>🔴 Rojas</option>
                                    <option value="azules" <?php echo ($data['jugador']['precio_tipo'] === 'azules') ? 'selected' : ''; ?>>🔵 Azules</option>
                                </select>
                            </div>
                        </div>

                        <!-- Edad y Género -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-white mb-2">Edad/Curso del Jugador</label>
                                <select name="jugador[edad]" 
                                        class="w-full p-3 bg-gray text-white border border-light-gray rounded-lg focus:border-success focus:ring-1 focus:ring-success transition-colors">
                                    <option value="">Selecciona un tipo</option>
                                    <option value="primero" <?php echo ($data['jugador']['edad'] === 'primero') ? 'selected' : ''; ?>>1️⃣ Primero</option>
                                    <option value="segundo" <?php echo ($data['jugador']['edad'] === 'segundo') ? 'selected' : ''; ?>>2️⃣ Segundo</option>
                                    <option value="tercero" <?php echo ($data['jugador']['edad'] === 'tercero') ? 'selected' : ''; ?>>3️⃣ Tercero</option>
                                    <option value="escolar" <?php echo ($data['jugador']['edad'] === 'escolar') ? 'selected' : ''; ?>>👲🏻 Escolar</option>
                                    <option value="adulto" <?php echo ($data['jugador']['edad'] === 'adulto') ? 'selected' : ''; ?>>👨🏻 Adulto</option>
                                    <option value="desconocido" <?php echo ($data['jugador']['edad'] === 'desconocido') ? 'selected' : ''; ?>>❓ Desconocido</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-white mb-2">Género del Jugador</label>
                                <select name="jugador[genero]" 
                                        class="w-full p-3 bg-gray text-white border border-light-gray rounded-lg focus:border-success focus:ring-1 focus:ring-success transition-colors">
                                    <option value="">Selecciona un tipo</option>
                                    <option value="masculino" <?php echo ($data['jugador']['genero'] === 'masculino') ? 'selected' : ''; ?>>♂️ Masculino</option>
                                    <option value="femenino" <?php echo ($data['jugador']['genero'] === 'femenino') ? 'selected' : ''; ?>>♀️ Femenino</option>
                                    <option value="desconocido" <?php echo ($data['jugador']['genero'] === 'desconocido') ? 'selected' : ''; ?>>❓ Desconocido</option>
                                </select>
                            </div>
                        </div>

                        <!-- Estadísticas -->
                        <div>
                            <label class="block text-sm font-medium text-white mb-2">Estadísticas</label>
                            <textarea name="jugador[estadisticas]" rows="12" 
                                    placeholder="GP: <?php echo (int) $data['jugador']['pe']; ?>&#10;TP: <?php echo (int) $data['jugador']['pt']; ?>&#10;Kick: <?php echo (int) $data['jugador']['tiro']; ?>&#10;Dribbling: <?php echo (int) $data['jugador']['regate']; ?>&#10;Block: <?php echo (int) $data['jugador']['defensa']; ?>&#10;Catch: <?php echo (int) $data['jugador']['control']; ?>&#10;Technique: <?php echo (int) $data['jugador']['tecnica']; ?>&#10;Speed: <?php echo (int) $data['jugador']['rapidez']; ?>&#10;Stamina: <?php echo (int) $data['jugador']['aguante']; ?>&#10;Lucky: <?php echo (int) $data['jugador']['suerte']; ?>&#10;Freedom: <?php echo (int) $data['jugador']['libertad']; ?>"
                                    class="w-full p-3 bg-gray text-white border border-light-gray rounded-lg focus:border-success focus:ring-1 focus:ring-success transition-colors placeholder-light-gray resize-vertical"></textarea>
                            <p class="text-xs text-light-gray mt-1">Formato: NombreStat: Valor. Una por línea. Deja vacío para mantener estadísticas actuales.</p>
                        </div>

                        <!-- Técnicas actuales -->
                        <div class="mt-6">
                            <h4 class="text-lg font-bold mb-4 text-success">⚡ Técnicas y Talentos Actuales</h4>
                            <?php if (!empty($data['jugador']['tecnicas'])): ?>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                    <?php foreach ($data['jugador']['tecnicas'] as $index => $tecnica): ?>
                                        <div class="bg-gray p-3 rounded-lg">
                                            <div class="flex items-center justify-between">
                                                <span class="font-medium">
                                                    <?php echo ($tecnica['tipo'] === 'talento') ? '⭐' : '🔧'; ?>
                                                    <?php echo htmlspecialchars($tecnica['nombre']); ?>
                                                </span>
                                                <span class="text-warning font-bold">
                                                    Nv <?php echo htmlspecialchars($tecnica['nivel']); ?>
                                                </span>
                                            </div>
                                            <div class="text-xs text-light-gray mt-1">
                                                <?php echo e(ucfirst($tecnica['tipo'])); ?>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php else: ?>
                                <p class="text-light-gray italic mb-4">Este jugador no tiene técnicas o talentos asignados.</p>
                            <?php endif; ?>

                            <div class="bg-info bg-blue-900 p-4 rounded-lg">
                                <h5 class="font-bold mb-2">🔄 Actualizar Técnicas</h5>
                                <p class="text-sm mb-4">Para modificar las técnicas, cambia los valores existentes o déjalos vacíos para eliminarlos.</p>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <?php 
                                    // Rellenar hasta 6 técnicas, completando con vacías si es necesario
                                    $tecnicasCompletas = array_pad($data['jugador']['tecnicas'] ?? [], 6, null);
                                    for ($i = 1; $i <= 6; $i++): 
                                        $tecnicaActual = $tecnicasCompletas[$i-1] ?? null;
                                    ?>
                                        <div class="bg-gray p-4 rounded-lg">
                                            <h6 class="font-medium mb-2 text-info">Técnica <?php echo $i; ?></h6>
                                            <div class="space-y-2">
                                                <div class="relative">
                                                    <input type="text" 
                                                        name="jugador[tecnicas][tecnica_<?php echo $i; ?>_nombre]" 
                                                        placeholder="Buscar técnica..."
                                                        value="<?php echo $tecnicaActual ? htmlspecialchars($tecnicaActual['nombre']) : ''; ?>"
                                                        class="tecnica-input w-full p-2 bg-darker text-white border border-light-gray rounded focus:border-success focus:ring-1 focus:ring-success transition-colors"
                                                        data-index="<?php echo $i; ?>"
                                                        data-jugador="0">
                                                    <input type="hidden" 
                                                        name="jugador[tecnicas][tecnica_<?php echo $i; ?>_id]" 
                                                        class="tecnica-id"
                                                        value="<?php echo $tecnicaActual ? htmlspecialchars($tecnicaActual['id']) : ''; ?>">
                                                    <input type="hidden" 
                                                        name="jugador[tecnicas][tecnica_<?php echo $i; ?>_tabla]" 
                                                        class="tecnica-tabla"
                                                        value="<?php echo $tecnicaActual ? htmlspecialchars($tecnicaActual['tabla']) : ''; ?>">
                                                    <div class="tecnica-suggestions absolute z-10 w-full bg-darker border border-light-gray rounded mt-1 hidden max-h-48 overflow-y-auto"></div>
                                                </div>
                                                <input type="number" 
                                                    name="jugador[tecnicas][tecnica_<?php echo $i; ?>_nivel]" 
                                                    placeholder="Nivel (opcional)"
                                                    value="<?php echo $tecnicaActual ? htmlspecialchars($tecnicaActual['nivel']) : ''; ?>"
                                                    min="1"
                                                    class="w-full p-2 bg-darker text-white border border-light-gray rounded focus:border-success focus:ring-1 focus:ring-success transition-colors">
                                            </div>
                                        </div>
                                    <?php endfor; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Botones principales -->
                <div class="flex gap-4 pt-4 justify-center">
                    <button type="submit" 
                            class="px-8 py-3 bg-success text-white rounded-lg hover:bg-green-600 transition-colors font-medium focus:ring-2 focus:ring-success focus:ring-offset-2 focus:ring-offset-darker">
                        💾 Guardar Cambios
                    </button>
                    <a href="/jugadoresList" 
                       class="px-8 py-3 bg-gray text-white rounded-lg hover:bg-light-gray transition-colors font-medium text-center focus:ring-2 focus:ring-gray focus:ring-offset-2 focus:ring-offset-darker">
                        Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            // Autocompletado de técnicas
            function initAutocompleteTecnicas() {
                // Autocompletado
                $(document).on('input', '.tecnica-input', function() {
                    const $input = $(this);
                    const $suggestions = $input.siblings('.tecnica-suggestions');
                    const $hiddenId = $input.siblings('.tecnica-id');
                    const $hiddenTabla = $input.siblings('.tecnica-tabla');
                    const termino = $input.val().trim();

                    if (termino.length < 2) {
                        $suggestions.empty().addClass('hidden');
                        // Solo limpiar si el campo estaba completamente vacío
                        if (termino.length === 0) {
                            $hiddenId.val('');
                            $hiddenTabla.val('');
                        }
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
                                    
                                    const $suggestion = $('<div>')
                                        .addClass('suggestion-item p-2 hover:bg-light-gray cursor-pointer border-b border-light-gray last:border-b-0')
                                        .html(`<span class="font-medium">${tipoIcon[item.tipo] || '🔧'} ${item.nombre}</span><br><span class="text-xs text-light-gray">${item.tipo} (${item.tabla})</span>`)
                                        .data('id', item.id)
                                        .data('nombre', item.nombre)
                                        .data('tabla', item.tabla);
                                    
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
                    
                    $input.val($item.data('nombre'));
                    $hiddenId.val($item.data('id'));
                    $hiddenTabla.val($item.data('tabla'));
                    $suggestions.empty().addClass('hidden');
                });

                // Ocultar sugerencias al perder foco
                $(document).on('blur', '.tecnica-input', function() {
                    const $input = $(this);
                    const $suggestions = $input.siblings('.tecnica-suggestions');
                    
                    setTimeout(function() {
                        $suggestions.empty().addClass('hidden');
                    }, 200);
                });

                // Función para limpiar técnica
                $(document).on('dblclick', '.tecnica-input', function() {
                    const $input = $(this);
                    const $hiddenId = $input.siblings('.tecnica-id');
                    const $hiddenTabla = $input.siblings('.tecnica-tabla');
                    
                    if (confirm('¿Quieres limpiar esta técnica?')) {
                        $input.val('');
                        $hiddenId.val('');
                        $hiddenTabla.val('');
                        $input.siblings('.tecnica-suggestions').empty().addClass('hidden');
                    }
                });
            }

            // Confirmación antes de enviar
            $('#editForm').submit(function(e) {
                if (!confirm('¿Estás seguro de que quieres guardar los cambios?')) {
                    e.preventDefault();
                }
            });

            // Inicializar autocompletado
            initAutocompleteTecnicas();

            // Agregar botones de limpiar técnicas
            $('.tecnica-input').each(function() {
                const $input = $(this);
                const $container = $input.parent();
                
                if ($input.val()) {
                    $container.append('<div class="text-xs mt-1"><button type="button" class="clear-tecnica text-error hover:underline">Doble clic para limpiar</button></div>');
                }
            });

        });
    </script>
</body>
</html>