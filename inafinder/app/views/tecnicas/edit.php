<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Técnica</title>
    <link href="/../css/output.css" rel="stylesheet">
    <?php include '../app/views/partials/sweetalert.php'; ?>
    <link rel="icon" type="image/x-icon" href="imgs/src/logo.ico">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body class="bg-primary font-sans text-white min-h-screen pt-16">

    <?php include '../app/views/partials/navbar.php'; ?>

    <div class="container mx-auto px-4 py-8">
        <div class="max-w-4xl mx-auto">
            <h1 class="text-3xl font-bold mb-6 text-center">Editar Técnica</h1>

            <div class="flex justify-center mb-6">
                <a href="/tecnicasList" class="px-6 py-3 bg-warning text-white rounded-lg hover:bg-yellow-600 transition-colors font-medium focus:ring-2 focus:ring-warning">
                    📋 Ver Lista de Técnicas
                </a>
            </div>

            <form action="/tecnicasEdit/<?php echo htmlspecialchars($data['tecnica']['id_supertecnica']); ?>" method="POST" enctype="multipart/form-data" id="mainForm">
                <?php echo \App\Core\Csrf::field(); ?>
                <div id="formsContainer">
                    <!-- Formulario de edición -->
                    <div class="tecnica-form bg-darker rounded-lg p-6 mb-6 shadow-[0_2px_8px_rgba(0,0,0,0.3)] relative" data-form-index="0">
                        
                        <div class="space-y-4">
                            <!-- Mostrar imagen actual si existe -->
                            <?php if (!empty($data['tecnica']['imagen'])): ?>
                                <div class="text-center mb-4">
                                    <p class="text-sm text-light-gray mb-2">Imagen actual:</p>
                                    <img src="../imgs/uploads/<?php echo e($data['tecnica']['tipo_principal']); ?>s/<?php echo htmlspecialchars($data['tecnica']['imagen']); ?>" 
                                         alt="<?php echo htmlspecialchars($data['tecnica']['nombre']); ?>"
                                         class="w-20 h-20 mx-auto rounded-lg object-cover border-2 border-success">
                                </div>
                            <?php endif; ?>

                            <!-- Mostrar tipo de técnica (solo lectura) -->
                            <div>
                                <label class="block text-sm font-medium text-white mb-2">
                                    Tipo de Técnica (no se puede cambiar)
                                </label>
                                <div class="w-full bg-gray-600 text-gray-300 border border-gray-500 rounded-lg p-2">
                                    <?php echo e(ucfirst($data['tecnica']['tipo_principal'])); ?>
                                </div>
                                <input type="hidden" name="tecnicas[0][tipo_principal]" value="<?php echo htmlspecialchars($data['tecnica']['tipo_principal']); ?>">
                            </div>

                            <!-- Nombre -->
                            <div>
                                <label class="block text-sm font-medium text-white mb-2">
                                    Nombre <span class="text-error">*</span>
                                </label>
                                <input type="text" name="tecnicas[0][nombre]" required 
                                       value="<?php echo htmlspecialchars($data['tecnica']['nombre']); ?>"
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
                                    <option value="viento" <?php echo ($data['tecnica']['elemento'] == 'viento') ? 'selected' : ''; ?>>🌪️ Viento</option>
                                    <option value="fuego" <?php echo ($data['tecnica']['elemento'] == 'fuego') ? 'selected' : ''; ?>>🔥 Fuego</option>
                                    <option value="tierra" <?php echo ($data['tecnica']['elemento'] == 'tierra') ? 'selected' : ''; ?>>🌍 Tierra</option>
                                    <option value="bosque" <?php echo ($data['tecnica']['elemento'] == 'bosque') ? 'selected' : ''; ?>>🌲 Bosque</option>
                                    <option value="montaña" <?php echo ($data['tecnica']['elemento'] == 'montaña') ? 'selected' : ''; ?>>⛰️ Montaña</option>
                                    <option value="neutro" <?php echo ($data['tecnica']['elemento'] == 'neutro') ? 'selected' : ''; ?>>🌌 Neutro</option>
                                </select>
                            </div>

                            <!-- Potencia -->
                            <div>
                                <label class="block text-sm font-medium text-white mb-2">
                                    Potencia <span class="text-error">*</span>
                                </label>
                                <input type="number" name="tecnicas[0][potencia]" required min="0" max="999"
                                       value="<?php echo htmlspecialchars($data['tecnica']['potencia']); ?>"
                                       class="w-full bg-gray text-white border border-light-gray rounded-lg p-2 focus:border-success focus:ring-1 focus:ring-success transition-colors" 
                                       placeholder="Potencia de la técnica" />
                            </div>

                            <!-- Costo -->
                            <div>
                                <label class="block text-sm font-medium text-white mb-2">
                                    Costo de TM <span class="text-error">*</span>
                                </label>
                                <input type="number" name="tecnicas[0][costo]" required min="0" max="999"
                                       value="<?php echo htmlspecialchars($data['tecnica']['costo']); ?>"
                                       class="w-full bg-gray text-white border border-light-gray rounded-lg p-2 focus:border-success focus:ring-1 focus:ring-success transition-colors" 
                                       placeholder="Costo en TM" />
                            </div>

                            <!-- Cambiar imagen -->
                            <div>
                                <label class="block text-sm font-medium text-white mb-2">
                                    Cambiar Imagen
                                </label>
                                <input type="file" name="tecnicas[0][imagen]" accept="image/*"
                                       class="w-full bg-gray text-white border border-light-gray rounded-lg p-2 focus:border-success focus:ring-1 focus:ring-success transition-colors file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-success file:text-white hover:file:bg-green-600" />
                                <p class="text-xs text-light-gray mt-1">Formatos: JPG, PNG, GIF. Tamaño máximo: 2MB. Dejar vacío para mantener la imagen actual.</p>
                            </div>

                            <!-- Campos específicos para TIROS -->
                            <?php if ($data['tecnica']['tipo_principal'] == 'tiro'): ?>
                                <div class="tiro-fields">
                                    <div class="space-y-4">
                                        <!-- Subtipo para tiros (radio buttons) -->
                                        <div>
                                            <label class="block text-sm font-medium text-white mb-2">
                                                Subtipo <span class="text-error">*</span>
                                            </label>
                                            <div class="grid grid-cols-2 gap-3">
                                                <label class="flex items-center p-3 bg-gray rounded-lg border border-light-gray hover:border-success transition-colors cursor-pointer">
                                                    <input type="radio" name="tecnicas[0][subtipo]" value="cadena" required 
                                                           <?php echo ($data['tecnica']['subtipo'] == 'cadena') ? 'checked' : ''; ?>
                                                           class="mr-3 text-success focus:ring-success focus:ring-2">
                                                    <span class="text-white">⛓️ Cadena</span>
                                                </label>
                                                <label class="flex items-center p-3 bg-gray rounded-lg border border-light-gray hover:border-success transition-colors cursor-pointer">
                                                    <input type="radio" name="tecnicas[0][subtipo]" value="normal" required 
                                                           <?php echo ($data['tecnica']['subtipo'] == 'normal') ? 'checked' : ''; ?>
                                                           class="mr-3 text-success focus:ring-success focus:ring-2">
                                                    <span class="text-white">⚽ Normal</span>
                                                </label>
                                                <label class="flex items-center p-3 bg-gray rounded-lg border border-light-gray hover:border-success transition-colors cursor-pointer">
                                                    <input type="radio" name="tecnicas[0][subtipo]" value="bloqueo" required 
                                                           <?php echo ($data['tecnica']['subtipo'] == 'bloqueo') ? 'checked' : ''; ?>
                                                           class="mr-3 text-success focus:ring-success focus:ring-2">
                                                    <span class="text-white">🛡️ Bloqueo</span>
                                                </label>
                                                <label class="flex items-center p-3 bg-gray rounded-lg border border-light-gray hover:border-success transition-colors cursor-pointer">
                                                    <input type="radio" name="tecnicas[0][subtipo]" value="lejano" required 
                                                           <?php echo ($data['tecnica']['subtipo'] == 'lejano') ? 'checked' : ''; ?>
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
                                                   value="<?php echo htmlspecialchars($data['tecnica']['dificultad']); ?>"
                                                   class="w-full bg-gray text-white border border-light-gray rounded-lg p-2 focus:border-success focus:ring-1 focus:ring-success transition-colors" 
                                                   placeholder="Nivel de dificultad" />
                                        </div>

                                        <!-- Aturdimiento -->
                                        <div>
                                            <label class="block text-sm font-medium text-white mb-2">
                                                Aturdimiento <span class="text-error">*</span>
                                            </label>
                                            <input type="number" name="tecnicas[0][aturdimiento]" required
                                                   value="<?php echo htmlspecialchars($data['tecnica']['aturdimiento']); ?>"
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
                                                <option value="1" <?php echo ($data['tecnica']['n_jugadores'] == 1) ? 'selected' : ''; ?>>👤 1 Jugador</option>
                                                <option value="2" <?php echo ($data['tecnica']['n_jugadores'] == 2) ? 'selected' : ''; ?>>👥 2 Jugadores</option>
                                                <option value="3" <?php echo ($data['tecnica']['n_jugadores'] == 3) ? 'selected' : ''; ?>>👥👤 3 Jugadores</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <!-- Campos específicos para REGATES -->
                            <?php if ($data['tecnica']['tipo_principal'] == 'regate'): ?>
                                <div class="regate-fields">
                                    <div class="space-y-4">
                                        <!-- Dificultad -->
                                        <div>
                                            <label class="block text-sm font-medium text-white mb-2">
                                                Dificultad <span class="text-error">*</span>
                                            </label>
                                            <input type="number" name="tecnicas[0][dificultad]" required
                                                   value="<?php echo htmlspecialchars($data['tecnica']['dificultad']); ?>"
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
                                                <option value="0" <?php echo ($data['tecnica']['riesgo'] == 0) ? 'selected' : ''; ?>>⚪ Ninguno (0)</option>
                                                <option value="1" <?php echo ($data['tecnica']['riesgo'] == 1) ? 'selected' : ''; ?>>🟢 Bajo (1)</option>
                                                <option value="2" <?php echo ($data['tecnica']['riesgo'] == 2) ? 'selected' : ''; ?>>🟡 Medio (2)</option>
                                                <option value="3" <?php echo ($data['tecnica']['riesgo'] == 3) ? 'selected' : ''; ?>>🔴 Alto (3)</option>
                                            </select>
                                        </div>

                                        <!-- Número de jugadores -->
                                        <div>
                                            <label class="block text-sm font-medium text-white mb-2">
                                                Número de Jugadores <span class="text-error">*</span>
                                            </label>
                                            <select name="tecnicas[0][n_jugadores]" required
                                                    class="w-full bg-gray text-white border border-light-gray rounded-lg p-2 focus:border-success focus:ring-1 focus:ring-success transition-colors">
                                                <option value="1" <?php echo ($data['tecnica']['n_jugadores'] == 1) ? 'selected' : ''; ?>>👤 1 Jugador</option>
                                                <option value="2" <?php echo ($data['tecnica']['n_jugadores'] == 2) ? 'selected' : ''; ?>>👥 2 Jugadores</option>
                                                <option value="3" <?php echo ($data['tecnica']['n_jugadores'] == 3) ? 'selected' : ''; ?>>👥👤 3 Jugadores</option>
                                            </select>
                                        </div>

                                        <!-- Daño a EG -->
                                        <div>
                                            <label class="block text-sm font-medium text-white mb-2">
                                                Daño a EG <span class="text-error">*</span>
                                            </label>
                                            <input type="number" name="tecnicas[0][eg_damage]" required min="0"
                                                   value="<?php echo htmlspecialchars($data['tecnica']['eg_damage']); ?>"
                                                   class="w-full bg-gray text-white border border-light-gray rounded-lg p-2 focus:border-success focus:ring-1 focus:ring-success transition-colors" 
                                                   placeholder="Daño a Espíritu de Guerrero" />
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <!-- Campos específicos para BLOQUEOS -->
                            <?php if ($data['tecnica']['tipo_principal'] == 'bloqueo'): ?>
                                <div class="bloqueo-fields">
                                    <div class="space-y-4">
                                        <!-- Checkbox para bloqueo -->
                                        <div class="flex items-center">
                                            <input type="checkbox" name="tecnicas[0][es_bloqueo]" value="bloqueo" 
                                                   <?php echo ($data['tecnica']['subtipo'] == 'bloqueo') ? 'checked' : ''; ?>
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
                                                   value="<?php echo htmlspecialchars($data['tecnica']['dificultad']); ?>"
                                                   class="w-full bg-gray text-white border border-light-gray rounded-lg p-2 focus:border-success focus:ring-1 focus:ring-success transition-colors" 
                                                   placeholder="Nivel de dificultad" />
                                        </div>

                                        <!-- Aturdimiento -->
                                        <div>
                                            <label class="block text-sm font-medium text-white mb-2">
                                                Aturdimiento <span class="text-error">*</span>
                                            </label>
                                            <input type="number" name="tecnicas[0][aturdimiento]" required
                                                   value="<?php echo htmlspecialchars($data['tecnica']['aturdimiento']); ?>"
                                                   class="w-full bg-gray text-white border border-light-gray rounded-lg p-2 focus:border-success focus:ring-1 focus:ring-success transition-colors" 
                                                   placeholder="Ej: -1, 0, 2..." />
                                        </div>

                                        <!-- Daño a EG -->
                                        <div>
                                            <label class="block text-sm font-medium text-white mb-2">
                                                Daño a EG <span class="text-error">*</span>
                                            </label>
                                            <input type="number" name="tecnicas[0][eg_damage]" required min="0"
                                                   value="<?php echo htmlspecialchars($data['tecnica']['eg_damage']); ?>"
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
                                                <option value="1" <?php echo ($data['tecnica']['n_jugadores'] == 1) ? 'selected' : ''; ?>>👤 1 Jugador</option>
                                                <option value="2" <?php echo ($data['tecnica']['n_jugadores'] == 2) ? 'selected' : ''; ?>>👥 2 Jugadores</option>
                                                <option value="3" <?php echo ($data['tecnica']['n_jugadores'] == 3) ? 'selected' : ''; ?>>👥👤 3 Jugadores</option>
                                            </select>
                                        </div>

                                        <!-- Riesgo -->
                                        <div>
                                            <label class="block text-sm font-medium text-white mb-2">
                                                Riesgo <span class="text-error">*</span>
                                            </label>
                                            <select name="tecnicas[0][riesgo]" required
                                                    class="w-full bg-gray text-white border border-light-gray rounded-lg p-2 focus:border-success focus:ring-1 focus:ring-success transition-colors">
                                                <option value="0" <?php echo ($data['tecnica']['riesgo'] == 0) ? 'selected' : ''; ?>>⚪ Ninguno (0)</option>
                                                <option value="1" <?php echo ($data['tecnica']['riesgo'] == 1) ? 'selected' : ''; ?>>🟢 Bajo (1)</option>
                                                <option value="2" <?php echo ($data['tecnica']['riesgo'] == 2) ? 'selected' : ''; ?>>🟡 Medio (2)</option>
                                                <option value="3" <?php echo ($data['tecnica']['riesgo'] == 3) ? 'selected' : ''; ?>>🔴 Alto (3)</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <!-- Campos específicos para PARADAS -->
                            <?php if ($data['tecnica']['tipo_principal'] == 'parada'): ?>
                                <div class="parada-fields">
                                    <div class="space-y-4">
                                        <!-- Checkbox para despeje -->
                                        <div class="flex items-center">
                                            <input type="checkbox" name="tecnicas[0][es_despeje]" value="despeje"
                                                   <?php echo ($data['tecnica']['subtipo'] == 'despeje') ? 'checked' : ''; ?>
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
                                                   value="<?php echo htmlspecialchars($data['tecnica']['dificultad']); ?>"
                                                   class="w-full bg-gray text-white border border-light-gray rounded-lg p-2 focus:border-success focus:ring-1 focus:ring-success transition-colors" 
                                                   placeholder="Nivel de dificultad" />
                                        </div>

                                        <!-- Aturdimiento -->
                                        <div>
                                            <label class="block text-sm font-medium text-white mb-2">
                                                Aturdimiento <span class="text-error">*</span>
                                            </label>
                                            <input type="number" name="tecnicas[0][aturdimiento]" required
                                                   value="<?php echo htmlspecialchars($data['tecnica']['aturdimiento']); ?>"
                                                   class="w-full bg-gray text-white border border-light-gray rounded-lg p-2 focus:border-success focus:ring-1 focus:ring-success transition-colors" 
                                                   placeholder="Ej: -1, 0, 2..." />
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>

                        </div>
                    </div>
                </div>

                <!-- Botones de acción -->
                <div class="flex justify-center gap-4">
                    <button type="submit" class="px-8 py-3 bg-success text-white rounded-lg hover:bg-green-600 transition-colors font-medium focus:ring-2 focus:ring-success">
                        💾 Actualizar Técnica
                    </button>
                    <a href="/tecnicasList" class="px-8 py-3 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors font-medium focus:ring-2 focus:ring-gray-500">
                        ❌ Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Validación del formulario
        document.getElementById('mainForm').addEventListener('submit', function(e) {
            const nombre = document.querySelector('input[name*="[nombre]"]').value.trim();
            
            if (!nombre) {
                e.preventDefault();
                Swal.fire('Campo requerido', 'El nombre de la técnica es obligatorio.', 'warning');
            }
        });
    </script>

</body>
</html>
