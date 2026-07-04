<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Añadir Técnicas</title>
    <link href="/../css/output.css" rel="stylesheet">
    <?php include '../app/views/partials/sweetalert.php'; ?>
    <link rel="icon" type="image/x-icon" href="imgs/src/logo.ico">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body class="bg-primary font-sans text-white min-h-screen pt-16">

    <?php include '../app/views/partials/navbar.php'; ?>

    <div class="container mx-auto px-4 py-8">
        <div class="max-w-4xl mx-auto">
            <!-- Botón para ver lista de técnicas -->
            <div class="flex justify-center mb-6">
                <a href="/tecnicasList" class="bg-info px-6 py-3 bg-warning text-white rounded-lg hover:bg-yellow-600 transition-colors font-medium focus:ring-2 focus:ring-warning">
                    📋 Ver Lista de Técnicas
                </a>
            </div>
            
            <h1 class="text-3xl font-bold mb-6 text-center">Añadir Técnicas</h1>

            <form action="/newTecnicas" method="POST" enctype="multipart/form-data" id="mainForm">
                <?php echo \App\Core\Csrf::field(); ?>
                <div id="formsContainer">
                    <!-- Primer formulario -->
                    <div class="tecnica-form bg-darker rounded-lg p-6 mb-6 shadow-[0_2px_8px_rgba(0,0,0,0.3)] relative" data-form-index="0">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-xl font-bold">Técnica #1</h3>
                            <button type="button" class="removeForm text-error hover:text-red-400 text-2xl hidden" title="Eliminar formulario">
                                ❌
                            </button>
                        </div>
                        
                        <div class="space-y-4">
                            <!-- Selector de tipo de técnica -->
                            <div>
                                <label class="block text-sm font-medium text-white mb-2">
                                    Tipo de Técnica <span class="text-error">*</span>
                                </label>
                                <select name="tecnicas[0][tipo_principal]" required 
                                        class="tipo-selector w-full bg-gray text-white border border-light-gray rounded-lg p-2 focus:border-success focus:ring-1 focus:ring-success transition-colors" 
                                        onchange="toggleFieldsByType(this, 0)">
                                    <option value="">-- Seleccionar tipo --</option>
                                    <option value="tiro">Tiro</option>
                                    <option value="regate">Regate</option>
                                    <option value="bloqueo">Bloqueo</option>
                                    <option value="parada">Parada</option>
                                </select>
                            </div>

                            <!-- Nombre -->
                            <div>
                                <label class="block text-sm font-medium text-white mb-2">
                                    Nombre <span class="text-error">*</span>
                                </label>
                                <input type="text" name="tecnicas[0][nombre]" required 
                                       class="w-full bg-gray text-white border border-light-gray rounded-lg p-2 focus:border-success focus:ring-1 focus:ring-success transition-colors" 
                                       placeholder="Nombre de la técnica" />
                            </div>

                            <!-- Elemento -->
                            <div>
                                <label class="block text-sm font-medium text-white mb-2">
                                    Elemento <span class="text-error">*</span>
                                </label>
                                <select name="tecnicas[0][elemento]" required 
                                        class="w-full bg-gray text-white border border-light-gray rounded-lg p-2 focus:border-success focus:ring-1 focus:ring-success transition-colors">
                                    <option value="">Selecciona elemento</option>
                                    <option value="aire">🌪️ Aire</option>
                                    <option value="fuego">🔥 Fuego</option>
                                    <option value="bosque">🌲 Bosque</option>
                                    <option value="montaña">⛰️ Montaña</option>
                                    <option value="neutro">🌌 Neutro</option>
                                </select>
                            </div>

                            <!-- Potencia -->
                            <div>
                                <label class="block text-sm font-medium text-white mb-2">
                                    Potencia <span class="text-error">*</span>
                                </label>
                                <input type="number" name="tecnicas[0][potencia]" required min="0" max="999"
                                       class="w-full bg-gray text-white border border-light-gray rounded-lg p-2 focus:border-success focus:ring-1 focus:ring-success transition-colors" 
                                       placeholder="Potencia de la técnica" />
                            </div>

                            <!-- Costo -->
                            <div>
                                <label class="block text-sm font-medium text-white mb-2">
                                    Costo de PT <span class="text-error">*</span>
                                </label>
                                <input type="number" name="tecnicas[0][costo]" required min="0" max="999"
                                       class="w-full bg-gray text-white border border-light-gray rounded-lg p-2 focus:border-success focus:ring-1 focus:ring-success transition-colors" 
                                       placeholder="Costo en PT" />
                            </div>

                            <!-- Imagen -->
                            <div>
                                <label class="block text-sm font-medium text-white mb-2">
                                    Imagen de la Técnica
                                </label>
                                <input type="file" name="tecnicas[0][imagen]" accept="image/*"
                                       class="w-full bg-gray text-white border border-light-gray rounded-lg p-2 focus:border-success focus:ring-1 focus:ring-success transition-colors file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-success file:text-white hover:file:bg-green-600" />
                                <p class="text-xs text-light-gray mt-1">Formatos: JPG, PNG, GIF. Tamaño máximo: 2MB</p>
                            </div>

                            <!-- Campos específicos para TIROS -->
                            <div class="tiro-fields" style="display: none;">
                                <div class="space-y-4">
                                    <!-- Subtipo para tiros (radio buttons) -->
                                    <div>
                                        <label class="block text-sm font-medium text-white mb-2">
                                            Subtipo <span class="text-error">*</span>
                                        </label>
                                        <div class="grid grid-cols-2 gap-3">
                                            <label class="flex items-center p-3 bg-gray rounded-lg border border-light-gray hover:border-success transition-colors cursor-pointer">
                                                <input type="radio" name="tecnicas[0][subtipo]" value="cadena" required 
                                                       class="mr-3 text-success focus:ring-success focus:ring-2">
                                                <span class="text-white">⛓️ Cadena</span>
                                            </label>
                                            <label class="flex items-center p-3 bg-gray rounded-lg border border-light-gray hover:border-success transition-colors cursor-pointer">
                                                <input type="radio" name="tecnicas[0][subtipo]" value="normal" required 
                                                       class="mr-3 text-success focus:ring-success focus:ring-2">
                                                <span class="text-white">⚽ Normal</span>
                                            </label>
                                            <label class="flex items-center p-3 bg-gray rounded-lg border border-light-gray hover:border-success transition-colors cursor-pointer">
                                                <input type="radio" name="tecnicas[0][subtipo]" value="bloqueo" required 
                                                       class="mr-3 text-success focus:ring-success focus:ring-2">
                                                <span class="text-white">🛡️ Bloqueo</span>
                                            </label>
                                            <label class="flex items-center p-3 bg-gray rounded-lg border border-light-gray hover:border-success transition-colors cursor-pointer">
                                                <input type="radio" name="tecnicas[0][subtipo]" value="lejano" required 
                                                       class="mr-3 text-success focus:ring-success focus:ring-2">
                                                <span class="text-white">🏹 Lejano</span>
                                            </label>
                                        </div>
                                    </div>

                                    <!-- Dificultad -->
                                    <div>
                                        <label class="block text-sm font-medium text-white mb-2">
                                            Dificultad <span class="text-error">*</span>
                                        </label>
                                        <input type="number" name="tecnicas[0][dificultad]" required
                                               class="w-full bg-gray text-white border border-light-gray rounded-lg p-2 focus:border-success focus:ring-1 focus:ring-success transition-colors" 
                                               placeholder="Nivel de dificultad" />
                                    </div>

                                    <!-- Aturdimiento -->
                                    <div>
                                        <label class="block text-sm font-medium text-white mb-2">
                                            Aturdimiento <span class="text-error">*</span>
                                        </label>
                                        <input type="number" name="tecnicas[0][aturdimiento]" required
                                               class="w-full bg-gray text-white border border-light-gray rounded-lg p-2 focus:border-success focus:ring-1 focus:ring-success transition-colors" 
                                               placeholder="Ej: -1, 0, 2..." />
                                    </div>

                                    <!-- Número de jugadores -->
                                    <div>
                                        <label class="block text-sm font-medium text-white mb-2">
                                            Número de Jugadores <span class="text-error">*</span>
                                        </label>
                                        <select name="tecnicas[0][n_jugadores]" required 
                                                class="w-full bg-gray text-white border border-light-gray rounded-lg p-2 focus:border-success focus:ring-1 focus:ring-success transition-colors">
                                            <option value="">Selecciona número de jugadores</option>
                                            <option value="1">👤 1 Jugador</option>
                                            <option value="2">👥 2 Jugadores</option>
                                            <option value="3">👥👤 3 Jugadores</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- Campos específicos para REGATES -->
                            <div class="regate-fields" style="display: none;">
                                <div class="space-y-4">
                                    <!-- Dificultad -->
                                    <div>
                                        <label class="block text-sm font-medium text-white mb-2">
                                            Dificultad <span class="text-error">*</span>
                                        </label>
                                        <input type="number" name="tecnicas[0][dificultad]" required
                                               class="w-full bg-gray text-white border border-light-gray rounded-lg p-2 focus:border-success focus:ring-1 focus:ring-success transition-colors" 
                                               placeholder="Nivel de dificultad" />
                                    </div>

                                    <!-- Riesgo -->
                                    <div>
                                        <label class="block text-sm font-medium text-white mb-2">
                                            Riesgo <span class="text-error">*</span>
                                        </label>
                                        <select name="tecnicas[0][riesgo]" required
                                                class="w-full bg-gray text-white border border-light-gray rounded-lg p-2 focus:border-success focus:ring-1 focus:ring-success transition-colors">
                                            <option value="">Selecciona nivel de riesgo</option>
                                            <option value="0">⚪ Ninguno (0)</option>
                                            <option value="1">🟢 Bajo (1)</option>
                                            <option value="2">🟡 Medio (2)</option>
                                            <option value="3">🔴 Alto (3)</option>
                                        </select>
                                    </div>

                                    <!-- Número de jugadores -->
                                    <div>
                                        <label class="block text-sm font-medium text-white mb-2">
                                            Número de Jugadores <span class="text-error">*</span>
                                        </label>
                                        <select name="tecnicas[0][n_jugadores]" required
                                                class="w-full bg-gray text-white border border-light-gray rounded-lg p-2 focus:border-success focus:ring-1 focus:ring-success transition-colors">
                                            <option value="">Selecciona número de jugadores</option>
                                            <option value="1">👤 1 Jugador</option>
                                            <option value="2">👥 2 Jugadores</option>
                                            <option value="3">👥👤 3 Jugadores</option>
                                        </select>
                                    </div>

                                    <!-- Daño a EG -->
                                    <div>
                                        <label class="block text-sm font-medium text-white mb-2">
                                            Daño a EG <span class="text-error">*</span>
                                        </label>
                                        <input type="number" name="tecnicas[0][eg_damage]" required min="0"
                                               class="w-full bg-gray text-white border border-light-gray rounded-lg p-2 focus:border-success focus:ring-1 focus:ring-success transition-colors" 
                                               placeholder="Daño a Espíritu de Guerrero" />
                                    </div>
                                </div>
                            </div>

                            <!-- Campos específicos para BLOQUEOS -->
                            <div class="bloqueo-fields" style="display: none;">
                                <div class="space-y-4">
                                    <!-- Checkbox para bloqueo -->
                                    <div class="flex items-center">
                                        <input type="checkbox" name="tecnicas[0][es_bloqueo]" value="bloqueo" 
                                               class="mr-2 text-success focus:ring-success focus:ring-2">
                                        <label class="text-sm font-medium text-white">
                                            🛡️ Es un bloqueo
                                        </label>
                                    </div>

                                    <!-- Dificultad -->
                                    <div>
                                        <label class="block text-sm font-medium text-white mb-2">
                                            Dificultad <span class="text-error">*</span>
                                        </label>
                                        <input type="number" name="tecnicas[0][dificultad]" required
                                               class="w-full bg-gray text-white border border-light-gray rounded-lg p-2 focus:border-success focus:ring-1 focus:ring-success transition-colors" 
                                               placeholder="Nivel de dificultad" />
                                    </div>

                                    <!-- Aturdimiento -->
                                    <div>
                                        <label class="block text-sm font-medium text-white mb-2">
                                            Aturdimiento <span class="text-error">*</span>
                                        </label>
                                        <input type="number" name="tecnicas[0][aturdimiento]" required
                                               class="w-full bg-gray text-white border border-light-gray rounded-lg p-2 focus:border-success focus:ring-1 focus:ring-success transition-colors" 
                                               placeholder="Ej: -1, 0, 2..." />
                                    </div>

                                    <!-- Daño a EG -->
                                    <div>
                                        <label class="block text-sm font-medium text-white mb-2">
                                            Daño a EG <span class="text-error">*</span>
                                        </label>
                                        <input type="number" name="tecnicas[0][eg_damage]" required min="0"
                                               class="w-full bg-gray text-white border border-light-gray rounded-lg p-2 focus:border-success focus:ring-1 focus:ring-success transition-colors" 
                                               placeholder="Daño a Espíritu de Guerrero" />
                                    </div>

                                    <!-- Número de jugadores -->
                                    <div>
                                        <label class="block text-sm font-medium text-white mb-2">
                                            Número de Jugadores <span class="text-error">*</span>
                                        </label>
                                        <select name="tecnicas[0][n_jugadores]" required
                                                class="w-full bg-gray text-white border border-light-gray rounded-lg p-2 focus:border-success focus:ring-1 focus:ring-success transition-colors">
                                            <option value="">Selecciona número de jugadores</option>
                                            <option value="1">👤 1 Jugador</option>
                                            <option value="2">👥 2 Jugadores</option>
                                            <option value="3">👥👤 3 Jugadores</option>
                                        </select>
                                    </div>

                                    <!-- Riesgo -->
                                    <div>
                                        <label class="block text-sm font-medium text-white mb-2">
                                            Riesgo <span class="text-error">*</span>
                                        </label>
                                        <select name="tecnicas[0][riesgo]" required
                                                class="w-full bg-gray text-white border border-light-gray rounded-lg p-2 focus:border-success focus:ring-1 focus:ring-success transition-colors">
                                            <option value="">Selecciona nivel de riesgo</option>
                                            <option value="0">⚪ Ninguno (0)</option>
                                            <option value="1">🟢 Bajo (1)</option>
                                            <option value="2">🟡 Medio (2)</option>
                                            <option value="3">🔴 Alto (3)</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- Campos específicos para PARADAS -->
                            <div class="parada-fields" style="display: none;">
                                <div class="space-y-4">
                                    <!-- Checkbox para despeje -->
                                    <div class="flex items-center">
                                        <input type="checkbox" name="tecnicas[0][es_despeje]" value="despeje" 
                                               class="mr-2 text-success focus:ring-success focus:ring-2">
                                        <label class="text-sm font-medium text-white">
                                            🦶 Es un despeje
                                        </label>
                                    </div>

                                    <!-- Dificultad -->
                                    <div>
                                        <label class="block text-sm font-medium text-white mb-2">
                                            Dificultad <span class="text-error">*</span>
                                        </label>
                                        <input type="number" name="tecnicas[0][dificultad]" required
                                               class="w-full bg-gray text-white border border-light-gray rounded-lg p-2 focus:border-success focus:ring-1 focus:ring-success transition-colors" 
                                               placeholder="Nivel de dificultad" />
                                    </div>

                                    <!-- Aturdimiento -->
                                    <div>
                                        <label class="block text-sm font-medium text-white mb-2">
                                            Aturdimiento <span class="text-error">*</span>
                                        </label>
                                        <input type="number" name="tecnicas[0][aturdimiento]" required
                                               class="w-full bg-gray text-white border border-light-gray rounded-lg p-2 focus:border-success focus:ring-1 focus:ring-success transition-colors" 
                                               placeholder="Ej: -1, 0, 2..." />
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <!-- Botones de control -->
                <div class="flex justify-between items-center mb-6">
                    <button type="button" id="addForm" class="px-6 py-3 bg-success text-white rounded-lg hover:bg-green-600 transition-colors font-medium focus:ring-2 focus:ring-success">
                        ➕ Añadir Otra Técnica
                    </button>
                    
                    <div class="text-sm text-light-gray">
                        Formularios: <span id="formCount">1</span>/10
                    </div>
                </div>

                <!-- Botón de envío -->
                <div class="text-center">
                    <button type="submit" class="px-8 py-4 bg-info text-white font-bold rounded-lg hover:bg-yellow-500 transition-colors focus:ring-2 focus:ring-warning text-lg">
                        💾 Guardar Todas las Técnicas
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        let formCount = 1;
        const maxForms = 10;

        // Función para mostrar/ocultar campos según el tipo de técnica
        function toggleFieldsByType(selectElement, formIndex) {
            const form = selectElement.closest('.tecnica-form');
            const tipo = selectElement.value;
            
            // Ocultar todos los campos específicos
            form.querySelectorAll('.tiro-fields, .regate-fields, .bloqueo-fields, .parada-fields').forEach(field => {
                field.style.display = 'none';
            });
            
            // Mostrar campos según el tipo seleccionado
            if (tipo) {
                const fieldsToShow = form.querySelector(`.${tipo}-fields`);
                if (fieldsToShow) {
                    fieldsToShow.style.display = 'block';
                }
            }
        }

        // Función para agregar nuevo formulario
        document.getElementById('addForm').addEventListener('click', function() {
            if (formCount >= maxForms) {
                Swal.fire('Límite alcanzado', `Solo puedes añadir hasta ${maxForms} técnicas a la vez.`, 'warning');
                return;
            }

            const container = document.getElementById('formsContainer');
            const firstForm = container.querySelector('.tecnica-form');
            const newForm = firstForm.cloneNode(true);
            
            // Actualizar índice y título
            newForm.setAttribute('data-form-index', formCount);
            newForm.querySelector('h3').textContent = `Técnica #${formCount + 1}`;
            
            // Limpiar valores
            newForm.querySelectorAll('input, select').forEach(input => {
                if (input.type === 'checkbox') {
                    input.checked = false;
                } else {
                    input.value = '';
                }
                // Actualizar names
                input.name = input.name.replace(/\[\d+\]/, `[${formCount}]`);
            });
            
            // Mostrar botón de eliminar
            newForm.querySelector('.removeForm').classList.remove('hidden');
            
            // Ocultar campos específicos
            newForm.querySelectorAll('.tiro-fields, .regate-fields, .bloqueo-fields, .parada-fields').forEach(field => {
                field.style.display = 'none';
            });
            
            // Actualizar onchange del selector
            const tipoSelector = newForm.querySelector('.tipo-selector');
            tipoSelector.setAttribute('onchange', `toggleFieldsByType(this, ${formCount})`);
            
            container.appendChild(newForm);
            formCount++;
            updateFormCount();
            
            // Scroll al nuevo formulario
            newForm.scrollIntoView({ behavior: 'smooth', block: 'start' });
        });

        // Función para eliminar formulario
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('removeForm')) {
                if (formCount <= 1) {
                    Swal.fire('No se puede eliminar', 'Debe haber al menos una técnica.', 'warning');
                    return;
                }
                
                Swal.fire({
                    title: '¿Estás seguro?',
                    text: "¿Quieres eliminar este formulario?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Sí, eliminar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        e.target.closest('.tecnica-form').remove();
                        formCount--;
                        updateFormCount();
                        reindexForms();
                    }
                });
            }
        });

        // Actualizar contador de formularios
        function updateFormCount() {
            document.getElementById('formCount').textContent = formCount;
            document.getElementById('addForm').style.display = formCount >= maxForms ? 'none' : 'block';
        }

        // Reindexar formularios después de eliminar
        function reindexForms() {
            const forms = document.querySelectorAll('.tecnica-form');
            forms.forEach((form, index) => {
                form.setAttribute('data-form-index', index);
                form.querySelector('h3').textContent = `Técnica #${index + 1}`;
                
                // Actualizar names de inputs
                form.querySelectorAll('input, select').forEach(input => {
                    input.name = input.name.replace(/\[\d+\]/, `[${index}]`);
                });
                
                // Actualizar onchange del selector
                const tipoSelector = form.querySelector('.tipo-selector');
                tipoSelector.setAttribute('onchange', `toggleFieldsByType(this, ${index})`);
                
                // Mostrar/ocultar botón eliminar
                const removeBtn = form.querySelector('.removeForm');
                if (index === 0 && forms.length === 1) {
                    removeBtn.classList.add('hidden');
                } else {
                    removeBtn.classList.remove('hidden');
                }
            });
        }

        // Validación del formulario
        document.getElementById('mainForm').addEventListener('submit', function(e) {
            const forms = document.querySelectorAll('.tecnica-form');
            let hasValidForm = false;
            
            forms.forEach(form => {
                const nombre = form.querySelector('input[name*="[nombre]"]').value.trim();
                const tipo = form.querySelector('select[name*="[tipo_principal]"]').value;
                
                if (nombre && tipo) {
                    hasValidForm = true;
                }
            });
            
            if (!hasValidForm) {
                e.preventDefault();
                Swal.fire('Formulario incompleto', 'Debes completar al menos una técnica con nombre y tipo.', 'warning');
            }
        });
    </script>

</body>
</html>
