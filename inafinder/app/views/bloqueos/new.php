<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Añadir Bloqueos</title>
    <link href="/../css/output.css" rel="stylesheet">
    <link rel="icon" type="image/x-icon" href="imgs/src/logo.ico">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body class="bg-primary font-sans text-white min-h-screen pt-16">

    <?php include '../app/views/partials/navbar.php'; ?>


    <div class="container mx-auto px-4 py-8">
        <div class="max-w-4xl mx-auto">
            <!-- Botón para ver lista de bloqueos -->
            <div class="flex justify-center mb-6">
                <a href="/bloqueosList" class="bg-info px-6 py-3 bg-warning text-white rounded-lg hover:bg-yellow-600 transition-colors font-medium focus:ring-2 focus:ring-warning">
                    📋 Ver Lista de Bloqueos
                </a>
            </div>
            
            <h1 class="text-3xl font-bold mb-6 text-center">Añadir Bloqueos</h1>

            <form action="/bloqueos" method="POST" enctype="multipart/form-data" id="mainForm">
                <?php echo \App\Core\Csrf::field(); ?>
                <div id="formsContainer">
                    <!-- Primer formulario -->
                    <div class="bloqueo-form bg-darker rounded-lg p-6 mb-6 shadow-[0_2px_8px_rgba(0,0,0,0.3)] relative" data-form-index="0">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-xl font-bold">Bloqueo #1</h3>
                            <button type="button" class="removeForm text-error hover:text-red-400 text-2xl hidden" title="Eliminar formulario">
                                ❌
                            </button>
                        </div>
                        
                        <div class="space-y-4">
                            <!-- Imagen -->
                            <div>
                                <label class="block text-sm font-medium text-white mb-2">
                                    Imagen <span class="text-error">*</span>
                                </label>
                                <input type="file" name="bloqueos[0][imagen]" accept="image/*" required 
                                       class="block w-full text-white bg-gray border border-light-gray rounded-lg p-2 focus:border-success focus:ring-1 focus:ring-success transition-colors" />
                                <p class="text-xs text-light-gray mt-1">Formatos permitidos: JPG, PNG, WEBP</p>
                            </div>

                            <!-- Nombre -->
                            <div>
                                <label class="block text-sm font-medium text-white mb-2">
                                    Nombre <span class="text-error">*</span>
                                </label>
                                <input type="text" name="bloqueos[0][nombre]" required 
                                       placeholder="Ej: Muro de hierro, Paisaje helado..."
                                       class="w-full p-3 bg-gray text-white border border-light-gray rounded-lg focus:border-success focus:ring-1 focus:ring-success transition-colors placeholder-light-gray">
                            </div>

                            <!-- Elemento -->
                            <div>
                                <label class="block text-sm font-medium text-white mb-2">
                                    Elemento <span class="text-error">*</span>
                                </label>
                                <select name="bloqueos[0][elemento]" required 
                                        class="w-full p-3 bg-gray text-white border border-light-gray rounded-lg focus:border-success focus:ring-1 focus:ring-success transition-colors">
                                    <option value="" disabled selected>Selecciona un elemento</option>
                                    <option value="fuego">🔥 Fuego</option>
                                    <option value="aire">💨 Aire</option>
                                    <option value="bosque">🌲 Bosque</option>
                                    <option value="montaña">⛰️ Montaña</option>
                                    <option value="neutro">🌌 Neutro</option>
                                </select>
                            </div>

                            <!-- Subtipo -->
                            <div>
                                <span class="block text-sm font-medium text-white mb-2">Subtipo</span>
                                <div class="flex items-center">
                                    <label class="inline-flex items-center">
                                        <input type="checkbox" name="bloqueos[0][subtipo]" value="bloqueo" 
                                               class="form-checkbox text-success bg-gray border-light-gray focus:ring-success rounded" />
                                        <span class="ml-2 text-white">🛡️ Bloqueo</span>
                                    </label>
                                </div>
                                <p class="text-xs text-light-gray mt-1">Si no está marcado, será un bloqueo normal</p>
                            </div>
                            

                            <!-- Potencia -->
                            <div>
                                <label class="block text-sm font-medium text-white mb-2">
                                    Potencia <span class="text-error">*</span>
                                </label>
                                <input type="number" name="bloqueos[0][potencia]" required min="0" max="999"
                                       placeholder="Ej: 75"
                                       class="w-full p-3 bg-gray text-white border border-light-gray rounded-lg focus:border-success focus:ring-1 focus:ring-success transition-colors placeholder-light-gray">
                            </div>

                            <!-- Costo -->
                            <div>
                                <label class="block text-sm font-medium text-white mb-2">
                                    Costo <span class="text-error">*</span>
                                </label>
                                <input type="number" name="bloqueos[0][costo]" required min="0" max="999"
                                       placeholder="Ej: 20"
                                       class="w-full p-3 bg-gray text-white border border-light-gray rounded-lg focus:border-success focus:ring-1 focus:ring-success transition-colors placeholder-light-gray">
                            </div>

                            <!-- Dificultad -->
                            <div>
                                <label class="block text-sm font-medium text-white mb-2">
                                    Dificultad <span class="text-error">*</span>
                                </label>
                                <input type="number" name="bloqueos[0][dificultad]" required
                                       placeholder="Ej: 3"
                                       class="w-full p-3 bg-gray text-white border border-light-gray rounded-lg focus:border-success focus:ring-1 focus:ring-success transition-colors placeholder-light-gray">
                            </div>

                            <!-- Daño a EG -->
                            <div>
                                <label class="block text-sm font-medium text-white mb-2">
                                    Daño a EG <span class="text-error">*</span>
                                </label>
                                <input type="number" name="bloqueos[0][Eg_damage]" required min="0" max="999"
                                       placeholder="Ej: 20"
                                       class="w-full p-3 bg-gray text-white border border-light-gray rounded-lg focus:border-success focus:ring-1 focus:ring-success transition-colors placeholder-light-gray">
                            </div>

                            <!-- Riesgo -->
                            <div>
                                <label class="block text-sm font-medium text-white mb-2">
                                    Riesgo <span class="text-error">*</span>
                                </label>
                                <select name="bloqueos[0][riesgo]" required 
                                        class="w-full p-3 bg-gray text-white border border-light-gray rounded-lg focus:border-success focus:ring-1 focus:ring-success transition-colors">
                                    <option value="" disabled selected>Selecciona el nivel de riesgo</option>
                                    <option value="0">⚪ Ninguno (0)</option>
                                    <option value="1">🟢 Bajo (1)</option>
                                    <option value="2">🟡 Medio (2)</option>
                                    <option value="3">🔴 Alto (3)</option>
                                </select>
                            </div>

                            <!-- Número de Jugadores -->
                            <div>
                                <label class="block text-sm font-medium text-white mb-2">
                                    Número de Jugadores <span class="text-error">*</span>
                                </label>
                                <select name="bloqueos[0][n_jugadores]" required 
                                        class="w-full p-3 bg-gray text-white border border-light-gray rounded-lg focus:border-success focus:ring-1 focus:ring-success transition-colors">
                                    <option value="" disabled selected>Selecciona el número de jugadores</option>
                                    <option value="1">👤 1 Jugador</option>
                                    <option value="2">👥 2 Jugadores</option>
                                    <option value="3">👥👤 3 Jugadores</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Botones principales -->
                <div class="flex gap-4 pt-4 justify-center">
                    <button type="submit" 
                            class="px-8 py-3 bg-success text-white rounded-lg hover:bg-green-600 transition-colors font-medium focus:ring-2 focus:ring-success focus:ring-offset-2 focus:ring-offset-darker hover:cursor-pointer">
                        🛡️ Añadir Todos los Bloqueos
                    </button>
                    <button type="button" id="addForm" class="px-6 py-3 bg-info text-white rounded-lg hover:bg-green-600 transition-colors font-medium focus:ring-2 focus:ring-success hover:cursor-pointer">
                        ➕ Añadir Otro Bloqueo
                    </button>
                    <span class="ml-4 text-light-gray self-center">
                        Formularios: <span id="formCounter">1</span>/10
                    </span>
                </div>

                <!-- Botón para añadir formularios -->
                <div class="flex justify-center mt-3">
                    <a href="/bloqueosList" class="px-8 py-3 bg-gray text-white rounded-lg hover:bg-light-gray transition-colors font-medium text-center focus:ring-2 focus:ring-gray focus:ring-offset-2 focus:ring-offset-darker">
                        Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Botón flotante para volver arriba -->
    <button id="scrollToTop" class="fixed bottom-6 right-6 p-3 bg-success text-white rounded-lg shadow-lg hover:bg-green-600 transition-colors z-50" style="opacity: 0; visibility: hidden;">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path>
        </svg>
    </button>

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
            $('.bloqueo-form').each(function(index) {
                $(this).attr('data-form-index', index);
                $(this).find('h3').text('Bloqueo #' + (index + 1));
                
                // Actualizar nombres de los campos
                $(this).find('input, select').each(function() {
                    const name = $(this).attr('name');
                    if (name && name.includes('bloqueos[')) {
                        const newName = name.replace(/bloqueos\[\d+\]/, 'bloqueos[' + index + ']');
                        $(this).attr('name', newName);
                    }
                });
            });
        }

        // Añadir nuevo formulario
        $('#addForm').click(function() {
            if (formCount < maxForms) {
                const newForm = $('.bloqueo-form').first().clone();
                
                // Limpiar valores del formulario clonado
                newForm.find('input[type="text"], input[type="number"], input[type="file"]').val('');
                newForm.find('select').prop('selectedIndex', 0);
                newForm.find('input[type="checkbox"]').prop('checked', false);
                
                // Añadir al contenedor
                $('#formsContainer').append(newForm);
                
                formCount++;
                updateFormIndices();
                updateCounter();
                toggleRemoveButtons();
                
                // Scroll suave al nuevo formulario
                $('html, body').animate({
                    scrollTop: newForm.offset().top - 100
                }, 500);
            }
        });

        // Eliminar formulario
        $(document).on('click', '.removeForm', function() {
            if (formCount > 1) {
                $(this).closest('.bloqueo-form').fadeOut(300, function() {
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
                '¿Estás seguro de que quieres añadir este bloqueo?' : 
                `¿Estás seguro de que quieres añadir estos ${formCount} bloqueos?`;
            
            if (!confirm(message)) {
                e.preventDefault();
            }
        });

        // Botón para volver arriba
        const scrollToTopBtn = $('#scrollToTop');
        
        // Mostrar/ocultar botón según scroll
        $(window).scroll(function() {
            const scrollTop = $(window).scrollTop();
            
            if (scrollTop > 200) {
                scrollToTopBtn.css({
                    'opacity': '1',
                    'visibility': 'visible'
                });
            } else {
                scrollToTopBtn.css({
                    'opacity': '0',
                    'visibility': 'hidden'
                });
            }
        });

        // Funcionalidad del botón volver arriba
        scrollToTopBtn.click(function() {
            $('html, body').animate({
                scrollTop: 0
            }, 600);
        });

        // Inicializar estado
        updateCounter();
        toggleRemoveButtons();
    });
    </script>

</body>
</html>