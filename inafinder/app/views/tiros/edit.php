<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Tiro</title>
    <link href="/../css/output.css" rel="stylesheet">
    <link rel="icon" type="image/x-icon" href="imgs/src/logo.ico">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body class="bg-primary font-sans text-white min-h-screen pt-16">

    <?php include '../app/views/partials/navbar.php'; ?>

    <div class="container mx-auto px-4 py-8">
        <div class="max-w-4xl mx-auto">
            <h1 class="text-3xl font-bold mb-6 text-center">Editar Tiro</h1>

            <div class="flex justify-center mb-6">
                <a href="/tirosList" class="px-6 py-3 bg-warning text-white rounded-lg hover:bg-yellow-600 transition-colors font-medium focus:ring-2 focus:ring-warning">
                    📋 Ver Lista de Tiros
                </a>
            </div>

            <form action="/tirosEdit/<?php echo htmlspecialchars($data['tiro']['id_supertecnica']); ?>" method="POST" enctype="multipart/form-data" id="mainForm">
                <?php echo \App\Core\Csrf::field(); ?>
                <div id="formsContainer">
                    <!-- Formulario de edición -->
                    <div class="tiro-form bg-darker rounded-lg p-6 mb-6 shadow-[0_2px_8px_rgba(0,0,0,0.3)] relative" data-form-index="0">
                        
                        <div class="space-y-4">
                            <!-- Imagen actual -->
                            <?php if (!empty($data['tiro']['imagen'])): ?>
                                <div class="text-center mb-4">
                                    <p class="text-sm text-light-gray mb-2">Imagen actual:</p>
                                    <img src="../imgs/uploads/tiros/<?php echo htmlspecialchars($data['tiro']['imagen']); ?>" 
                                         alt="<?php echo htmlspecialchars($data['tiro']['nombre']); ?>"
                                         class="w-20 h-20 mx-auto rounded-lg object-cover border-2 border-success">
                                </div>
                            <?php endif; ?>

                            <!-- Nueva imagen (opcional) -->
                            <div>
                                <label class="block text-sm font-medium text-white mb-2">
                                    Nueva Imagen (opcional - deja vacío para mantener la actual)
                                </label>
                                <input type="file" name="tiros[0][imagen]" accept="image/*" 
                                       class="block w-full text-white bg-gray border border-light-gray rounded-lg p-2 focus:border-success focus:ring-1 focus:ring-success transition-colors" />
                                <p class="text-xs text-light-gray mt-1">Formatos permitidos: JPG, PNG, WEBP</p>
                            </div>

                            <!-- Nombre -->
                            <div>
                                <label class="block text-sm font-medium text-white mb-2">
                                    Nombre
                                </label>
                                <input type="text" name="tiros[0][nombre]" 
                                       placeholder="Ej: Tiro Galáctico, Disparo Cañón..."
                                       class="w-full p-3 bg-gray text-white border border-light-gray rounded-lg focus:border-success focus:ring-1 focus:ring-success transition-colors placeholder-light-gray"
                                       value="<?php echo htmlspecialchars($data['tiro']['nombre'] ?? ''); ?>">
                                <p class="text-xs text-light-gray mt-1">Deja vacío para mantener el valor actual</p>
                            </div>

                            <!-- Elemento -->
                            <div>
                                <label class="block text-sm font-medium text-white mb-2">
                                    Elemento
                                </label>
                                <select name="tiros[0][elemento]" 
                                        class="w-full p-3 bg-gray text-white border border-light-gray rounded-lg focus:border-success focus:ring-1 focus:ring-success transition-colors">
                                    <option value="">-- Mantener elemento actual --</option>
                                    <option value="fuego" <?php echo ($data['tiro']['elemento'] ?? '') === 'fuego' ? 'selected' : ''; ?>>🔥 Fuego</option>
                                    <option value="aire" <?php echo ($data['tiro']['elemento'] ?? '') === 'aire' ? 'selected' : ''; ?>>💨 Aire</option>
                                    <option value="bosque" <?php echo ($data['tiro']['elemento'] ?? '') === 'bosque' ? 'selected' : ''; ?>>🌲 Bosque</option>
                                    <option value="montaña" <?php echo ($data['tiro']['elemento'] ?? '') === 'montaña' ? 'selected' : ''; ?>>⛰️ Montaña</option>
                                    <option value="neutro" <?php echo ($data['tiro']['elemento'] ?? '') === 'neutro' ? 'selected' : ''; ?>>🌌 Neutro</option>
                                </select>
                            </div>

                            <!-- Subtipo -->
                            <div>
                                <label class="block text-sm font-medium text-white mb-2">
                                    Subtipo
                                </label>
                                <div class="grid grid-cols-2 gap-3">
                                    <label class="flex items-center p-3 bg-gray rounded-lg border border-light-gray hover:border-success transition-colors cursor-pointer">
                                        <input type="radio" name="tiros[0][subtipo]" value="largo" 
                                               <?php echo ($data['tiro']['subtipo'] ?? '') === 'largo' ? 'checked' : ''; ?>
                                               class="mr-3 text-success focus:ring-success focus:ring-2">
                                        <span class="text-white">🏹 Largo</span>
                                    </label>
                                    <label class="flex items-center p-3 bg-gray rounded-lg border border-light-gray hover:border-success transition-colors cursor-pointer">
                                        <input type="radio" name="tiros[0][subtipo]" value="bloqueo" 
                                               <?php echo ($data['tiro']['subtipo'] ?? '') === 'bloqueo' ? 'checked' : ''; ?>
                                               class="mr-3 text-success focus:ring-success focus:ring-2">
                                        <span class="text-white">🛡️ Bloqueo</span>
                                    </label>
                                    <label class="flex items-center p-3 bg-gray rounded-lg border border-light-gray hover:border-success transition-colors cursor-pointer">
                                        <input type="radio" name="tiros[0][subtipo]" value="cadena" 
                                               <?php echo ($data['tiro']['subtipo'] ?? '') === 'cadena' ? 'checked' : ''; ?>
                                               class="mr-3 text-success focus:ring-success focus:ring-2">
                                        <span class="text-white">⛓️ Cadena</span>
                                    </label>
                                    <label class="flex items-center p-3 bg-gray rounded-lg border border-light-gray hover:border-success transition-colors cursor-pointer">
                                        <input type="radio" name="tiros[0][subtipo]" value="normal" 
                                               <?php echo ($data['tiro']['subtipo'] ?? '') === 'normal' ? 'checked' : ''; ?>
                                               class="mr-3 text-success focus:ring-success focus:ring-2">
                                        <span class="text-white">⚽ Normal</span>
                                    </label>
                                </div>
                                <p class="text-xs text-light-gray mt-1">Selecciona un subtipo para cambiar el actual</p>
                            </div>

                            <!-- Potencia -->
                            <div>
                                <label class="block text-sm font-medium text-white mb-2">
                                    Potencia
                                </label>
                                <input type="number" name="tiros[0][potencia]" min="0" max="999"
                                       placeholder="Ej: 95"
                                       class="w-full p-3 bg-gray text-white border border-light-gray rounded-lg focus:border-success focus:ring-1 focus:ring-success transition-colors placeholder-light-gray"
                                       value="<?php echo htmlspecialchars($data['tiro']['potencia'] ?? ''); ?>">
                                <p class="text-xs text-light-gray mt-1">Deja vacío para mantener el valor actual</p>
                            </div>

                            <!-- Costo -->
                            <div>
                                <label class="block text-sm font-medium text-white mb-2">
                                    Costo
                                </label>
                                <input type="number" name="tiros[0][costo]" min="0" max="999"
                                       placeholder="Ej: 25"
                                       class="w-full p-3 bg-gray text-white border border-light-gray rounded-lg focus:border-success focus:ring-1 focus:ring-success transition-colors placeholder-light-gray"
                                       value="<?php echo htmlspecialchars($data['tiro']['costo'] ?? ''); ?>">
                                <p class="text-xs text-light-gray mt-1">Deja vacío para mantener el valor actual</p>
                            </div>

                            <!-- Dificultad -->
                            <div>
                                <label class="block text-sm font-medium text-white mb-2">
                                    Dificultad
                                </label>
                                <input type="number" name="tiros[0][dificultad]"
                                       placeholder="Ej: 5"
                                       class="w-full p-3 bg-gray text-white border border-light-gray rounded-lg focus:border-success focus:ring-1 focus:ring-success transition-colors placeholder-light-gray"
                                       value="<?php echo htmlspecialchars($data['tiro']['dificultad'] ?? ''); ?>">
                                <p class="text-xs text-light-gray mt-1">Deja vacío para mantener el valor actual. Nivel de dificultad (sin límites)</p>
                            </div>

                            <!-- Aturdimiento -->
                            <div>
                                <label class="block text-sm font-medium text-white mb-2">
                                    Aturdimiento <span class="text-error">*</span>
                                </label>
                                <input type="number" name="tiros[0][aturdimiento]" required
                                       placeholder="Ej: -1, 0, 2..."
                                       class="w-full p-3 bg-gray text-white border border-light-gray rounded-lg focus:border-success focus:ring-1 focus:ring-success transition-colors placeholder-light-gray" value="<?php echo htmlspecialchars($data['tiro']['aturdimiento'] ?? ''); ?>">
                                <p class="text-xs text-light-gray mt-1">Puede ser negativo (reduce aturdimiento), cero o positivo</p>
                            </div>

                            <!-- Número de Jugadores -->
                            <div>
                                <label class="block text-sm font-medium text-white mb-2">
                                    Número de Jugadores
                                </label>
                                <select name="tiros[0][n_jugadores]" 
                                        class="w-full p-3 bg-gray text-white border border-light-gray rounded-lg focus:border-success focus:ring-1 focus:ring-success transition-colors">
                                    <option value="">-- Mantener número actual --</option>
                                    <option value="1" <?php echo ($data['tiro']['n_jugadores'] ?? '') === '1' ? 'selected' : ''; ?>>👤 1 Jugador</option>
                                    <option value="2" <?php echo ($data['tiro']['n_jugadores'] ?? '') === '2' ? 'selected' : ''; ?>>👥 2 Jugadores</option>
                                    <option value="3" <?php echo ($data['tiro']['n_jugadores'] ?? '') === '3' ? 'selected' : ''; ?>>👥👤 3 Jugadores</option>
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
                    <a href="/tirosList" 
                       class="px-8 py-3 bg-gray text-white rounded-lg hover:bg-light-gray transition-colors font-medium text-center focus:ring-2 focus:ring-gray focus:ring-offset-2 focus:ring-offset-darker">
                        Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>

</body>
</html>