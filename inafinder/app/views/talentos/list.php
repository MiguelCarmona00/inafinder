<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Talentos</title>
    <link href="/../css/output.css" rel="stylesheet">
    <?php include '../app/views/partials/sweetalert.php'; ?>
    <link rel="icon" type="image/x-icon" href="imgs/src/logo.ico">
</head>
<body class="bg-primary font-sans text-white min-h-screen pt-16">

    <?php include '../app/views/partials/navbar.php'; ?>

    <div class="container mx-auto px-4 py-8">
        <div class="max-w-6xl mx-auto">
            <div class="flex justify-between items-center mb-8">
                <h1 class="text-3xl font-bold">Gestión de Talentos</h1>
                <a href="/talentos" class="px-6 py-3 bg-success text-white rounded-lg hover:bg-green-600 transition-colors font-medium focus:ring-2 focus:ring-success">
                    ➕ Añadir Nuevos Talentos
                </a>
            </div>

            <!-- Resultados -->
            <div class="bg-darker rounded-lg p-6 shadow-[0_2px_8px_rgba(0,0,0,0.3)]">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-xl font-bold">
                            Todos los Talentos
                    </h2>
                    <span class="text-light-gray">
                        <?php echo count($data['talentos']); ?> talentos encontrados
                    </span>
                </div>

                <?php if (!empty($data['talentos'])): ?>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <?php foreach ($data['talentos'] as $talento): ?>
                            <div class="bg-gray rounded-lg p-6 shadow-[0_2px_8px_rgba(0,0,0,0.3)] hover:shadow-[0_4px_12px_rgba(0,0,0,0.4)] transition-all duration-300">
                                <div class="mb-4">
                                    <h3 class="text-lg font-bold mb-2 text-success">
                                        ⚡ <?php echo htmlspecialchars($talento['nombre']); ?>
                                    </h3>
                                    <div class="text-sm text-light-gray mb-3">
                                        ID: #<?php echo htmlspecialchars($talento['id_talento']); ?>
                                    </div>
                                </div>

                                <!-- Información del talento -->
                                <div class="space-y-2 text-sm mb-4">
                                    <div class="flex justify-between">
                                        <span class="text-light-gray">Tipo:</span>
                                        <span class="font-medium text-warning">
                                            <?php 
                                            $iconosTipo = [
                                                'Mejora de capacidades' => '💪',
                                                'Mejora de espíritu guerrero' => '🔥',
                                                'Mejora de ventajas' => '⭐',
                                                'Mejora de supertécnicas' => '⚡',
                                                'Mejora de recompensa' => '🏆',
                                                'Mejora de campo' => '🏟️',
                                                'Mejora de duelos' => '⚔️'
                                            ];
                                            echo ($iconosTipo[$talento['tipo']] ?? '📋') . ' ' . htmlspecialchars($talento['tipo']);
                                            ?>
                                        </span>
                                    </div>

                                    <div class="flex justify-between">
                                        <span class="text-light-gray">Afecta a:</span>
                                        <span class="font-medium text-info">
                                            <?php 
                                            $iconosAfecta = [
                                                'Usuario' => '👤',
                                                'Oponente' => '🎯',
                                                'Companieros asistidos' => '👥',
                                                'Todo el equipo' => '👥👥'
                                            ];

                                            // Corregir el texto para mostrar
                                            $textoAfecta = $talento['afecta'];
                                            if ($textoAfecta === 'Companieros asistidos') {
                                                $textoAfecta = 'Compañeros asistidos';
                                            }

                                            echo ($iconosAfecta[$talento['afecta']] ?? '👤') . ' ' . htmlspecialchars($textoAfecta);
                                            ?>
                                        </span>
                                    </div>
                                </div>

                                <?php if (!empty($talento['descripcion'])): ?>
                                    <div class="mb-4">
                                        <h4 class="text-sm font-medium text-white mb-2">Descripción:</h4>
                                        <p class="text-sm text-light-gray leading-relaxed">
                                            <?php echo nl2br(htmlspecialchars($talento['descripcion'])); ?>
                                        </p>
                                    </div>
                                <?php else: ?>
                                    <div class="mb-4">
                                        <p class="text-sm text-light-gray italic">
                                            Sin descripción disponible
                                        </p>
                                    </div>
                                <?php endif; ?>

                                <!-- Botones de acción (para futuras funcionalidades) -->
                                <div class="flex gap-2 mt-4">
                                    <a href="/talentosEdit/<?php echo urlencode($talento['id_talento']); ?>" 
                                        class="px-3 py-1 bg-warning text-white rounded text-xs hover:bg-yellow-600 transition-colors hover:cursor-pointer flex items-center"
                                        title="Editar talento">
                                         ✏️ Editar
                                    </a>
                                    <button onclick="confirmarEliminacion('talento', <?php echo (int) $talento['id_talento']; ?>, '<?php echo htmlspecialchars($talento['nombre'], ENT_QUOTES); ?>')" class="px-3 py-1 bg-error text-white rounded text-xs hover:bg-red-600 transition-colors hover:cursor-pointer"  title="Eliminar talento">
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

    <script src="../js/stt.js"></script>
    <script src="../js/confirmDelete.js"></script>

</body>
</html>