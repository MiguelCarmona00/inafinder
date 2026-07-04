<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Regate</title>
    <link href="/../css/output.css" rel="stylesheet">
    <link rel="icon" type="image/x-icon" href="imgs/src/logo.ico">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body class="bg-primary font-sans text-white min-h-screen pt-16">

    <?php include '../app/views/partials/navbar.php'; ?>

    <div class="container mx-auto px-4 py-8">
        <div class="max-w-4xl mx-auto">
            <h1 class="text-3xl font-bold mb-6 text-center">Editar Regate</h1>

            <div class="flex justify-center mb-6">
                <a href="/regatesList" class="px-6 py-3 bg-warning text-white rounded-lg hover:bg-yellow-600 transition-colors font-medium focus:ring-2 focus:ring-warning">
                    📋 Ver Lista de Regates
                </a>
            </div>

            <form action="/regatesEdit/<?php echo htmlspecialchars($data['regate']['id_supertecnica']); ?>" method="POST" enctype="multipart/form-data" id="mainForm">
                <?php echo \App\Core\Csrf::field(); ?>
                <div id="formsContainer">
                    <!-- Formulario de edición -->
                    <div class="regate-form bg-darker rounded-lg p-6 mb-6 shadow-[0_2px_8px_rgba(0,0,0,0.3)] relative" data-form-index="0">
                        
                        <div class="space-y-4">
                            <!-- Imagen actual -->
                            <?php if (!empty($data['regate']['imagen'])): ?>
                                <div class="text-center mb-4">
                                    <p class="text-sm text-light-gray mb-2">Imagen actual:</p>
                                    <img src="../imgs/uploads/regates/<?php echo htmlspecialchars($data['regate']['imagen']); ?>" 
                                         alt="<?php echo htmlspecialchars($data['regate']['nombre']); ?>"
                                         class="w-20 h-20 mx-auto rounded-lg object-cover border-2 border-success">
                                </div>
                            <?php endif; ?>

                            <!-- Nueva imagen (opcional) -->
                            <div>
                                <label class="block text-sm font-medium text-white mb-2">
                                    Nueva Imagen (opcional - deja vacío para mantener la actual)
                                </label>
                                <input type="file" name="regates[0][imagen]" accept="image/*" 
                                       class="block w-full text-white bg-gray border border-light-gray rounded-lg p-2 focus:border-success focus:ring-1 focus:ring-success transition-colors" />
                                <p class="text-xs text-light-gray mt-1">Formatos permitidos: JPG, PNG, WEBP</p>
                            </div>

                            <!-- Nombre -->
                            <div>
                                <label class="block text-sm font-medium text-white mb-2">
                                    Nombre
                                </label>
                                <input type="text" name="regates[0][nombre]" 
                                       placeholder="Ej: Regate Elástico, Amago de Cuerpo..."
                                       class="w-full p-3 bg-gray text-white border border-light-gray rounded-lg focus:border-success focus:ring-1 focus:ring-success transition-colors placeholder-light-gray"
                                       value="<?php echo htmlspecialchars($data['regate']['nombre'] ?? ''); ?>">
                                <p class="text-xs text-light-gray mt-1">Deja vacío para mantener el valor actual</p>
                            </div>

                            <!-- Elemento -->
                            <div>
                                <label class="block text-sm font-medium text-white mb-2">
                                    Elemento
                                </label>
                                <select name="regates[0][elemento]" 
                                        class="w-full p-3 bg-gray text-white border border-light-gray rounded-lg focus:border-success focus:ring-1 focus:ring-success transition-colors">
                                    <option value="">-- Mantener elemento actual --</option>
                                    <option value="fuego" <?php echo ($data['regate']['elemento'] ?? '') === 'fuego' ? 'selected' : ''; ?>>🔥 Fuego</option>
                                    <option value="aire" <?php echo ($data['regate']['elemento'] ?? '') === 'aire' ? 'selected' : ''; ?>>💨 Aire</option>
                                    <option value="bosque" <?php echo ($data['regate']['elemento'] ?? '') === 'bosque' ? 'selected' : ''; ?>>🌲 Bosque</option>
                                    <option value="montaña" <?php echo ($data['regate']['elemento'] ?? '') === 'montaña' ? 'selected' : ''; ?>>⛰️ Montaña</option>
                                    <option value="neutro" <?php echo ($data['regate']['elemento'] ?? '') === 'neutro' ? 'selected' : ''; ?>>🌌 Neutro</option>
                                </select>
                            </div>

                            <!-- Potencia -->
                            <div>
                                <label class="block text-sm font-medium text-white mb-2">
                                    Potencia
                                </label>
                                <input type="number" name="regates[0][potencia]" min="0" max="999"
                                       placeholder="Ej: 65"
                                       class="w-full p-3 bg-gray text-white border border-light-gray rounded-lg focus:border-success focus:ring-1 focus:ring-success transition-colors placeholder-light-gray"
                                       value="<?php echo htmlspecialchars($data['regate']['potencia'] ?? ''); ?>">
                                <p class="text-xs text-light-gray mt-1">Deja vacío para mantener el valor actual</p>
                            </div>

                            <!-- Costo -->
                            <div>
                                <label class="block text-sm font-medium text-white mb-2">
                                    Costo
                                </label>
                                <input type="number" name="regates[0][costo]" min="0" max="999"
                                       placeholder="Ej: 18"
                                       class="w-full p-3 bg-gray text-white border border-light-gray rounded-lg focus:border-success focus:ring-1 focus:ring-success transition-colors placeholder-light-gray"
                                       value="<?php echo htmlspecialchars($data['regate']['costo'] ?? ''); ?>">
                                <p class="text-xs text-light-gray mt-1">Deja vacío para mantener el valor actual</p>
                            </div>

                            <!-- Dificultad -->
                            <div>
                                <label class="block text-sm font-medium text-white mb-2">
                                    Dificultad
                                </label>
                                <input type="number" name="regates[0][dificultad]"
                                       placeholder="Ej: 4"
                                       class="w-full p-3 bg-gray text-white border border-light-gray rounded-lg focus:border-success focus:ring-1 focus:ring-success transition-colors placeholder-light-gray"
                                       value="<?php echo htmlspecialchars($data['regate']['dificultad'] ?? ''); ?>">
                            </div>

                            <!-- Daño a EG -->
                            <div>
                                <label class="block text-sm font-medium text-white mb-2">
                                    Daño a EG
                                </label>
                                <input type="number" name="regates[0][eg_damage]" min="0" max="999"
                                       placeholder="Ej: 15"
                                       class="w-full p-3 bg-gray text-white border border-light-gray rounded-lg focus:border-success focus:ring-1 focus:ring-success transition-colors placeholder-light-gray"
                                       value="<?php echo htmlspecialchars($data['regate']['eg_damage'] ?? ''); ?>">
                                <p class="text-xs text-light-gray mt-1">Deja vacío para mantener el valor actual</p>
                            </div>

                            <!-- Riesgo -->
                            <div>
                                <label class="block text-sm font-medium text-white mb-2">
                                    Riesgo
                                </label>
                                <select name="regates[0][riesgo]" 
                                        class="w-full p-3 bg-gray text-white border border-light-gray rounded-lg focus:border-success focus:ring-1 focus:ring-success transition-colors">
                                    <option value="">-- Mantener riesgo actual --</option>
                                    <option value="0" <?php echo ($data['regate']['riesgo'] ?? '') === '0' ? 'selected' : ''; ?>>⚪ Ninguno (0)</option>
                                    <option value="1" <?php echo ($data['regate']['riesgo'] ?? '') === '1' ? 'selected' : ''; ?>>🟢 Bajo (1)</option>
                                    <option value="2" <?php echo ($data['regate']['riesgo'] ?? '') === '2' ? 'selected' : ''; ?>>🟡 Medio (2)</option>
                                    <option value="3" <?php echo ($data['regate']['riesgo'] ?? '') === '3' ? 'selected' : ''; ?>>🔴 Alto (3)</option>
                                </select>
                            </div>

                            <!-- Número de Jugadores -->
                            <div>
                                <label class="block text-sm font-medium text-white mb-2">
                                    Número de Jugadores
                                </label>
                                <select name="regates[0][n_jugadores]" 
                                        class="w-full p-3 bg-gray text-white border border-light-gray rounded-lg focus:border-success focus:ring-1 focus:ring-success transition-colors">
                                    <option value="">-- Mantener número actual --</option>
                                    <option value="1" <?php echo ($data['regate']['n_jugadores'] ?? '') === '1' ? 'selected' : ''; ?>>👤 1 Jugador</option>
                                    <option value="2" <?php echo ($data['regate']['n_jugadores'] ?? '') === '2' ? 'selected' : ''; ?>>👥 2 Jugadores</option>
                                    <option value="3" <?php echo ($data['regate']['n_jugadores'] ?? '') === '3' ? 'selected' : ''; ?>>👥👤 3 Jugadores</option>
                                </select>
                            </div>  
                        </div>
                    </div>
                </div>

                <!-- Botones principales -->
                <div class="flex gap-4 pt-4 justify-center">
                    <button type="submit" 
                            class="px-8 py-3 bg-success text-white rounded-lg hover:bg-green-600 transition-colors font-medium focus:ring-2 focus:ring-success focus:ring-offset-2 focus:ring-offset-darker">
                        ⚽ Guardar Cambios
                    </button>
                    <a href="/regatesList" 
                       class="px-8 py-3 bg-gray text-white rounded-lg hover:bg-light-gray transition-colors font-medium text-center focus:ring-2 focus:ring-gray focus:ring-offset-2 focus:ring-offset-darker">
                        Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>

</body>
</html>