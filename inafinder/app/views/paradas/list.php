<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Paradas</title>
    <link href="/../css/output.css" rel="stylesheet">
    <?php include '../app/views/partials/sweetalert.php'; ?>
    <link rel="icon" type="image/x-icon" href="imgs/src/logo.ico">
</head>
<body class="bg-primary font-sans text-white min-h-screen pt-16">

    <?php include '../app/views/partials/navbar.php'; ?>

    <div class="container mx-auto px-4 py-8">
        <div class="max-w-6xl mx-auto">
            <div class="flex justify-between items-center mb-8">
                <h1 class="text-3xl font-bold">Gestión de Paradas</h1>
                <a href="/paradas" class="px-6 py-3 bg-success text-white rounded-lg hover:bg-green-600 transition-colors font-medium focus:ring-2 focus:ring-success">
                    ➕ Añadir Nuevas Paradas
                </a>
            </div>

            <!-- Resultados -->
            <div class="bg-darker rounded-lg p-6 shadow-[0_2px_8px_rgba(0,0,0,0.3)]">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-xl font-bold">
                            Todas las Paradas
                    </h2>
                    <span class="text-light-gray">
                        <?php echo count($data['paradas'] ?? []); ?> paradas encontradas
                    </span>
                </div>

                <?php if (!empty($data['paradas'])): ?>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <?php foreach ($data['paradas'] as $parada): ?>
                            <div class="bg-gray rounded-lg p-6 shadow-[0_2px_8px_rgba(0,0,0,0.3)] hover:shadow-[0_4px_12px_rgba(0,0,0,0.4)] transition-all duration-300">
                                <!-- Imagen de la parada -->
                                <?php if (!empty($parada['imagen'])): ?>
                                    <div class="mb-4 text-center">
                                        <img src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 80 80'%3E%3Crect width='80' height='80' fill='%23374151'/%3E%3C/svg%3E" 
                                             data-src="../imgs/uploads/paradas/<?php echo htmlspecialchars($parada['imagen']); ?>" 
                                             alt="<?php echo htmlspecialchars($parada['nombre']); ?>"
                                             class="lazy-image w-20 h-20 mx-auto rounded-lg object-cover border-2 border-success transition-opacity duration-300 opacity-50"
                                             loading="lazy">
                                    </div>
                                <?php endif; ?>

                                <div class="mb-4">
                                    <h3 class="text-lg font-bold mb-2 text-success">
                                        🛡️ <?php echo htmlspecialchars($parada['nombre']); ?>
                                    </h3>
                                    <div class="text-sm text-light-gray mb-3">
                                        ID: #<?php echo htmlspecialchars($parada['id_supertecnica']); ?>
                                    </div>
                                </div>

                                <!-- Información de la parada -->
                                <div class="space-y-2 text-sm mb-4">
                                    <div class="flex justify-between">
                                        <span class="text-light-gray">Elemento:</span>
                                        <span class="font-medium">
                                            <?php 
                                            $iconosElemento = [
                                                'fuego' => '🔥',
                                                'aire' => '💨',
                                                'bosque' => '🌲',
                                                'montaña' => '⛰️'
                                            ];
                                            echo ($iconosElemento[$parada['elemento']] ?? '') . ' ' . ucfirst(htmlspecialchars($parada['elemento']));
                                            ?>
                                        </span>
                                    </div>
                                    
                                    <?php if (!empty($parada['subtipo'])): ?>
                                        <div class="flex justify-between">
                                            <span class="text-light-gray">Subtipo:</span>
                                            <span class="font-medium text-warning">
                                                🦶 <?php echo htmlspecialchars($parada['subtipo']); ?>
                                            </span>
                                        </div>
                                    <?php endif; ?>

                                    <div class="flex justify-between">
                                        <span class="text-light-gray">Potencia:</span>
                                        <span class="font-medium text-success">
                                            ⚡ <?php echo htmlspecialchars($parada['potencia']); ?>
                                        </span>
                                    </div>

                                    <div class="flex justify-between">
                                        <span class="text-light-gray">Aturdimiento:</span>
                                        <span class="font-medium <?php echo $parada['aturdimiento'] >= 0 ? 'text-error' : 'text-success'; ?>">
                                            <?php 
                                            $aturdimiento = (int)$parada['aturdimiento'];
                                            if ($aturdimiento > 0) {
                                                echo '🔴 +' . $aturdimiento;
                                            } elseif ($aturdimiento < 0) {
                                                echo '🟢 ' . $aturdimiento;
                                            } else {
                                                echo '⚪ 0';
                                            }
                                            ?>
                                        </span>
                                    </div>

                                     <div class="flex justify-between">
                                        <span class="text-light-gray">Costo:</span>
                                        <span class="font-medium text-warning">
                                            💰 <?php echo htmlspecialchars($parada['costo']); ?> PT
                                        </span>
                                    </div>

                                    <div class="flex justify-between">
                                        <span class="text-light-gray">Dificultad:</span>
                                        <span class="font-medium text-info">
                                            📊 <?php echo htmlspecialchars($parada['dificultad']); ?>
                                        </span>
                                    </div>

                                </div>

                                <!-- Botones de acción -->
                                <div class="flex gap-2 mt-4">
                                    <a href="/paradasEdit/<?php echo urlencode($parada['id_supertecnica']); ?>" 
                                        class="px-3 py-1 bg-warning text-white rounded text-xs hover:bg-yellow-600 transition-colors hover:cursor-pointer flex items-center"
                                        title="Editar parada">
                                        ✏️ Editar
                                    </a>
                                    <button onclick="confirmarEliminacion('parada', <?php echo (int) $parada['id_supertecnica']; ?>, '<?php echo htmlspecialchars($parada['nombre'], ENT_QUOTES); ?>')"
                                            class="px-3 py-1 bg-error text-white rounded text-xs hover:bg-red-600 transition-colors hover:cursor-pointer" 
                                            title="Eliminar parada">
                                        🗑️ Eliminar
                                    </button>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Botón flotante para volver arriba -->
    <button id="scrollToTop" class="fixed  bottom-6 right-6 m-2 z-50 p-3 bg-success text-white rounded-full shadow-lg hover:bg-green-600 transition-all duration-300 opacity-0 invisible hover:scale-110 focus:ring-2 focus:ring-success">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path>
        </svg>
    </button>

    <script src="../js/ll.js"></script>
    <script src="../js/stt.js"></script>
    <script src="../js/confirmDelete.js"></script>

</body>
</html>