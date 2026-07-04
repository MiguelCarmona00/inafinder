<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Entrenadores</title>
    <link href="/../css/output.css" rel="stylesheet">
    <?php include '../app/views/partials/sweetalert.php'; ?>
    <link rel="icon" type="image/x-icon" href="imgs/src/logo.ico">
</head>
<body class="bg-primary font-sans text-white min-h-screen pt-16">

    <?php include '../app/views/partials/navbar.php'; ?>

    <div class="container mx-auto px-4 py-8">
        <div class="max-w-6xl mx-auto">
            <div class="flex justify-between items-center mb-8">
                <h1 class="text-3xl font-bold">Gestión de Entrenadores</h1>
                <a href="/entrenadores" class="px-6 py-3 bg-success text-white rounded-lg hover:bg-green-600 transition-colors font-medium focus:ring-2 focus:ring-success">
                    ➕ Añadir Nuevos Entrenadores
                </a>
            </div>

            <!-- Resultados -->
            <div class="bg-darker rounded-lg p-6 shadow-[0_2px_8px_rgba(0,0,0,0.3)]">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-xl font-bold">
                            Todos los Entrenadores
                    </h2>
                    <span class="text-light-gray">
                        <?php echo count($data['entrenadores'] ?? []); ?> entrenadores encontrados
                    </span>
                </div>

                <?php if (!empty($data['entrenadores'])): ?>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <?php foreach ($data['entrenadores'] as $entrenador): ?>
                            <div class="bg-gray rounded-lg p-6 shadow-[0_2px_8px_rgba(0,0,0,0.3)] hover:shadow-[0_4px_12px_rgba(0,0,0,0.4)] transition-all duration-300">
                                <!-- Imagen del entrenador -->
                                <?php if (!empty($entrenador['imagen'])): ?>
                                    <div class="mb-4 text-center">
                                        <img src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 80 80'%3E%3Crect width='80' height='80' fill='%23374151'/%3E%3C/svg%3E" 
                                             data-src="../imgs/uploads/entrenadores/<?php echo htmlspecialchars($entrenador['imagen']); ?>" 
                                             alt="<?php echo htmlspecialchars($entrenador['nombre']); ?>"
                                             class="lazy-image w-20 h-20 mx-auto rounded-lg object-cover border-2 border-success transition-opacity duration-300 opacity-50"
                                             loading="lazy">
                                    </div>
                                <?php endif; ?>

                                <div class="mb-4">
                                    <h3 class="text-lg font-bold mb-2 text-success">
                                        ⚽ <?php echo htmlspecialchars($entrenador['nombre']); ?>
                                    </h3>
                                    <div class="text-sm text-light-gray mb-3">
                                        ID: #<?php echo htmlspecialchars($entrenador['id_ent']); ?>
                                    </div>
                                </div>

                                <!-- Botones de acción -->
                                <?php if($_SESSION['perfil'] === 'admin'): ?>
                                <div class="flex gap-2 mt-4">
                                    <a href="/entrenadoresEdit/<?php echo urlencode($entrenador['id_ent']); ?>" 
                                        class="px-3 py-1 bg-warning text-white rounded text-xs hover:bg-yellow-600 transition-colors hover:cursor-pointer flex items-center"
                                        title="Editar entrenador">
                                        ✏️ Editar
                                    </a>
                                    <button onclick="confirmarEliminacion('entrenador', '<?php echo (int) $entrenador['id_ent']; ?>', '<?php echo htmlspecialchars($entrenador['nombre'], ENT_QUOTES); ?>')"
                                            class="px-3 py-1 bg-error text-white rounded text-xs hover:bg-red-600 transition-colors hover:cursor-pointer" 
                                            title="Eliminar entrenador">
                                        🗑️ Eliminar
                                    </button>
                                </div>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>

                <?php else: ?>
                    <!-- Estado vacío -->
                    <div class="text-center py-12">
                        <div class="text-6xl mb-4">⚽</div>
                        <?php if (!empty($_GET['buscar'])): ?>
                            <h3 class="text-xl font-bold mb-2">No se encontraron entrenadores</h3>
                            <p class="text-light-gray mb-6">
                                No hay entrenadores que coincidan con "<?php echo htmlspecialchars($_GET['buscar']); ?>"
                            </p>
                            <a href="/entrenadoresList" 
                               class="inline-block px-6 py-3 bg-gray text-white rounded-lg hover:bg-light-gray transition-colors font-medium">
                                Ver todos los entrenadores
                            </a>
                        <?php else: ?>
                            <h3 class="text-xl font-bold mb-2">No hay entrenadores registrados</h3>
                            <p class="text-light-gray mb-6">
                                Comienza añadiendo el primer entrenador al sistema
                            </p>
                            <a href="/entrenadores" 
                               class="inline-block px-6 py-3 bg-success text-white rounded-lg hover:bg-green-600 transition-colors font-medium">
                                ➕ Añadir Primer Entrenador
                            </a>
                        <?php endif; ?>
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