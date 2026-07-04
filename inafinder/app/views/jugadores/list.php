<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Jugadores</title>
    <link href="/../css/output.css" rel="stylesheet">
    <?php include '../app/views/partials/sweetalert.php'; ?>
    <link rel="icon" type="image/x-icon" href="imgs/src/logo.ico">
</head>
<body class="bg-primary font-sans text-white min-h-screen pt-16">

    <?php include '../app/views/partials/navbar.php'; ?>

    <div class="container mx-auto px-4 py-8">
        <div class="max-w-6xl mx-auto">

            <div class="flex justify-between items-center mb-8">
                <h1 class="text-3xl font-bold">Gestión de Jugadores</h1>
                <a href="/jugadores" class="px-6 py-3 bg-success text-white rounded-lg hover:bg-green-600 transition-colors font-medium focus:ring-2 focus:ring-success">
                    ➕ Añadir Nuevos Jugadores
                </a>
            </div>

            <!-- Selector de límite -->
            <div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-darker rounded-lg p-4 shadow-[0_2px_8px_rgba(0,0,0,0.3)]">
                <div class="flex items-center gap-3">
                    <label for="limite-selector" class="text-sm font-medium text-light-gray">
                        Mostrar:
                    </label>
                    <select id="limite-selector" 
                            class="bg-gray text-white border border-success rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-success focus:border-transparent transition-all duration-300"
                            onchange="cambiarLimite(this.value)">
                        <option value="25" <?php echo (!isset($_GET['limite']) || $_GET['limite'] == 25) ? 'selected' : ''; ?>>25 jugadores</option>
                        <option value="50" <?php echo (isset($_GET['limite']) && $_GET['limite'] == 50) ? 'selected' : ''; ?>>50 jugadores</option>
                        <option value="75" <?php echo (isset($_GET['limite']) && $_GET['limite'] == 75) ? 'selected' : ''; ?>>75 jugadores</option>
                        <option value="100" <?php echo (isset($_GET['limite']) && $_GET['limite'] == 100) ? 'selected' : ''; ?>>100 jugadores</option>
                        <option value="150" <?php echo (isset($_GET['limite']) && $_GET['limite'] == 150) ? 'selected' : ''; ?>>150 jugadores</option>
                        <option value="200" <?php echo (isset($_GET['limite']) && $_GET['limite'] == 200) ? 'selected' : ''; ?>>200 jugadores</option>
                        <option value="250" <?php echo (isset($_GET['limite']) && $_GET['limite'] == 250) ? 'selected' : ''; ?>>250 jugadores</option>
                        <option value="500" <?php echo (isset($_GET['limite']) && $_GET['limite'] == 500) ? 'selected' : ''; ?>>500 jugadores</option>
                        <option value="1000" <?php echo (isset($_GET['limite']) && $_GET['limite'] == 1000) ? 'selected' : ''; ?>>1000 jugadores</option>
                        <option value="0" <?php echo (isset($_GET['limite']) && $_GET['limite'] == 0) ? 'selected' : ''; ?>>Todos (<?php echo (int) ($data['total_jugadores'] ?? 2000); ?>)</option>
                    </select>
                </div>
                
                <div class="text-sm text-light-gray">
                    <?php
                    $limiteActual = isset($_GET['limite']) ? intval($_GET['limite']) : 25;
                    $totalJugadores = $data['total_jugadores'] ?? 0;
                    $jugadoresMostrados = count($data['jugadores'] ?? []);
                    $paginaActual = (int) ($data['pagina_actual'] ?? 1);
                    $totalPaginas = ($limiteActual > 0) ? max(1, (int) ceil($totalJugadores / $limiteActual)) : 1;

                    if ($limiteActual == 0 || ($totalPaginas == 1 && $limiteActual >= $totalJugadores)) {
                        echo "Mostrando todos los <span class='text-success font-medium'>$jugadoresMostrados</span> jugadores";
                    } else {
                        echo "Mostrando <span class='text-success font-medium'>$jugadoresMostrados</span> de <span class='text-warning font-medium'>$totalJugadores</span> jugadores";
                    }
                    ?>
                </div>

                <?php if ($totalPaginas > 1): ?>
                    <!-- Paginación -->
                    <div class="flex items-center gap-2 text-sm">
                        <?php if ($paginaActual > 1): ?>
                            <a href="?limite=<?php echo $limiteActual; ?>&pagina=<?php echo $paginaActual - 1; ?>"
                               class="px-3 py-1 bg-gray rounded hover:bg-success transition-colors">&larr; Anterior</a>
                        <?php endif; ?>
                        <span class="text-light-gray">Página <?php echo $paginaActual; ?> de <?php echo $totalPaginas; ?></span>
                        <?php if ($paginaActual < $totalPaginas): ?>
                            <a href="?limite=<?php echo $limiteActual; ?>&pagina=<?php echo $paginaActual + 1; ?>"
                               class="px-3 py-1 bg-gray rounded hover:bg-success transition-colors">Siguiente &rarr;</a>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Resultados -->
            <div class="bg-darker rounded-lg p-6 shadow-[0_2px_8px_rgba(0,0,0,0.3)]">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-xl font-bold">
                            Todos los Jugadores
                    </h2>
                    <span class="text-light-gray">
                        <?php echo count($data['jugadores'] ?? []); ?> jugadores encontrados
                    </span>
                </div>

                <?php if (!empty($data['jugadores'])): ?>
                    <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 xl:grid-cols-6 gap-4">
                        <?php foreach ($data['jugadores'] as $jugador): ?>
                            <div class="bg-gray rounded-lg p-4 shadow-[0_2px_8px_rgba(0,0,0,0.3)] hover:shadow-[0_4px_12px_rgba(0,0,0,0.4)] transition-all duration-300">
                                
                                <!-- Imagen del jugador -->
                                <?php if (!empty($jugador['imagen'])): ?>
                                    <div class="mb-3 text-center">
                                        <img src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 60 60'%3E%3Crect width='60' height='60' fill='%23374151'/%3E%3C/svg%3E" 
                                             data-src="../imgs/uploads/jugadores/<?php echo htmlspecialchars($jugador['imagen']); ?>" 
                                             alt="<?php echo htmlspecialchars($jugador['nombre']); ?>"
                                             class="lazy-image w-16 h-16 mx-auto rounded-lg object-cover border-2 border-success transition-opacity duration-300 opacity-50"
                                             loading="lazy">
                                    </div>
                                <?php else: ?>
                                    <div class="mb-3 text-center">
                                        <div class="w-16 h-16 mx-auto rounded-lg bg-darker border-2 border-success flex items-center justify-center text-xl">
                                            ⚽
                                        </div>
                                    </div>
                                <?php endif; ?>

                                <!-- Información básica -->
                                <div class="mb-3">
                                    <h3 class="text-sm font-bold mb-1 text-success text-center">
                                        <?php echo html_entity_decode(htmlspecialchars($jugador['nombre']), ENT_QUOTES, 'UTF-8'); ?>
                                    </h3>
                                    <div class="text-xs text-light-gray text-center mb-2">
                                        ID: #<?php echo htmlspecialchars($jugador['id_jugador']); ?>
                                    </div>
                                </div>

                                <!-- Datos básicos del jugador (más compactos) -->
                                <div class="space-y-1 text-xs mb-3">
                                    <div class="flex justify-between">
                                        <span class="text-light-gray">Pos:</span>
                                        <img src="../imgs/uploads/posiciones/<?php echo htmlspecialchars($jugador['posicion']); ?>.webp" alt="<?php echo htmlspecialchars($jugador['posicion']); ?>" class="inline w-8 h-4 align-text-bottom">
                                    </div>

                                    <div class="flex justify-between">
                                        <span class="text-light-gray">Edad:</span>
                                        <img src="../imgs/uploads/edades/<?php echo htmlspecialchars($jugador['edad']); ?>.webp" 
                                            alt="<?php echo htmlspecialchars($jugador['edad']); ?>" 
                                            class="inline w-8 h-4 align-text-bottom">
                                    </div>

                                    <div class="flex justify-between">
                                        <span class="text-light-gray">Precio:</span>
                                        <span class="font-medium text-warning">
                                            <?php 
                                            echo '<span class=px-1>' . htmlspecialchars($jugador['precio_cantidad']) . '</span><img src="../imgs/uploads/coins/' . htmlspecialchars($jugador['precio_tipo']) . '.webp" alt="' . htmlspecialchars($jugador['precio_tipo']) . '" class="inline w-4 h-4 align-text-bottom"> ';
                                            ?>
                                        </span>
                                    </div>

                                    <div class="flex justify-between">
                                        <span class="text-light-gray">Elem:</span>
                                        <img src="../imgs/uploads/elementos/<?php echo htmlspecialchars($jugador['elemento']); ?>.webp" 
                                            alt="<?php echo htmlspecialchars($jugador['elemento']); ?>" 
                                            class="inline w-4 h-4 align-text-bottom">
                                    </div>

                                    <div class="flex justify-between">
                                        <span class="text-light-gray">Género:</span>
                                        <img src="../imgs/uploads/generos/<?php echo htmlspecialchars($jugador['genero']); ?>.webp" alt="<?php echo htmlspecialchars($jugador['genero']); ?>" class="inline w-4 h-4 align-text-bottom">
                                    </div>
                                </div>

                                <!-- Todas las estadísticas juntas (muy compactas) -->
                                <div class="mb-3">
                                    <h4 class="text-xs font-bold mb-2 text-success text-center">📊 Estadísticas</h4>
                                    <div class="grid grid-cols-2 gap-1 text-xs">
                                        <div class="flex justify-between bg-darker p-1 rounded">
                                            <span class="text-light-gray">PE:</span>
                                            <span class="font-medium text-orange-400"><?php echo htmlspecialchars($jugador['pe'] ?? 0); ?></span>
                                        </div>
                                        <div class="flex justify-between bg-darker p-1 rounded">
                                            <span class="text-light-gray">PT:</span>
                                            <span class="font-medium text-green-400"><?php echo htmlspecialchars($jugador['pt'] ?? 0); ?></span>
                                        </div>
                                        <div class="flex justify-between bg-darker p-1 rounded">
                                            <span class="text-light-gray">Tiro:</span>
                                            <span class="font-medium"><?php echo htmlspecialchars($jugador['tiro'] ?? 0); ?></span>
                                        </div>
                                        <div class="flex justify-between bg-darker p-1 rounded">
                                            <span class="text-light-gray">Reg:</span>
                                            <span class="font-medium"><?php echo htmlspecialchars($jugador['regate'] ?? 0); ?></span>
                                        </div>
                                        <div class="flex justify-between bg-darker p-1 rounded">
                                            <span class="text-light-gray">Def:</span>
                                            <span class="font-medium"><?php echo htmlspecialchars($jugador['defensa'] ?? 0); ?></span>
                                        </div>
                                        <div class="flex justify-between bg-darker p-1 rounded">
                                            <span class="text-light-gray">Con:</span>
                                            <span class="font-medium"><?php echo htmlspecialchars($jugador['control'] ?? 0); ?></span>
                                        </div>
                                        <div class="flex justify-between bg-darker p-1 rounded">
                                            <span class="text-light-gray">Téc:</span>
                                            <span class="font-medium"><?php echo htmlspecialchars($jugador['tecnica'] ?? 0); ?></span>
                                        </div>
                                        <div class="flex justify-between bg-darker p-1 rounded">
                                            <span class="text-light-gray">Rap:</span>
                                            <span class="font-medium"><?php echo htmlspecialchars($jugador['rapidez'] ?? 0); ?></span>
                                        </div>
                                        <div class="flex justify-between bg-darker p-1 rounded">
                                            <span class="text-light-gray">Agu:</span>
                                            <span class="font-medium"><?php echo htmlspecialchars($jugador['aguante'] ?? 0); ?></span>
                                        </div>
                                        <div class="flex justify-between bg-darker p-1 rounded">
                                            <span class="text-light-gray">Sue:</span>
                                            <span class="font-medium"><?php echo htmlspecialchars($jugador['suerte'] ?? 0); ?></span>
                                        </div>
                                        <div class="flex justify-between bg-darker p-1 rounded col-span-2">
                                            <span class="text-light-gray">Libertad:</span>
                                            <span class="font-medium text-warning"><?php echo htmlspecialchars($jugador['libertad'] ?? 0); ?></span>
                                        </div>
                                    </div>
                                </div>

                                <?php if (!empty($jugador['tecnicas'])): ?>
                                    <div class="mb-3">
                                        <h4 class="text-xs font-bold mb-1 text-success text-center">⚡ Técnicas & Talentos</h4>
                                        <div class="space-y-1">
                                            <?php foreach (array_slice($jugador['tecnicas'], 0, 4) as $tecnica): ?>
                                                <div class="bg-darker p-1 rounded text-xs">
                                                    <div class="flex items-center justify-between gap-1">
                                                        <span class="font-medium text-white flex-1 leading-tight break-words">
                                                            <?php 
                                                            $icono = ($tecnica['tipo'] === 'talento') ? '⭐' : '🔧';
                                                            echo $icono . ' ' . htmlspecialchars($tecnica['nombre_tecnica']); 
                                                            ?>
                                                        </span>
                                                        <span class="text-warning font-bold text-xs whitespace-nowrap bg-gray-400 rounded p-1">
                                                            Nv <?php echo htmlspecialchars($tecnica['nivel']); ?>
                                                        </span>
                                                    </div>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                <?php else: ?>
                                    <div class="mb-3">
                                        <h4 class="text-xs font-bold mb-1 text-success text-center">⚡ Técnicas & Talentos</h4>
                                        <div class="text-xs text-light-gray italic text-center">
                                            Sin técnicas
                                        </div>
                                    </div>
                                <?php endif; ?>

                                <!-- Botones de acción (más pequeños) -->
                                <div class="flex gap-1">
                                    <a href="/jugadoresEdit/<?php echo urlencode($jugador['id_jugador']); ?>" 
                                        class="flex-1 px-2 py-1 bg-warning text-white rounded text-xs hover:bg-yellow-600 transition-colors text-center"
                                        title="Editar jugador">
                                        ✏️
                                    </a>
                                    <button onclick="confirmarEliminacion('jugador', <?php echo (int) $jugador['id_jugador']; ?>, '<?php echo htmlspecialchars($jugador['nombre'], ENT_QUOTES); ?>')"
                                            class="flex-1 px-2 py-1 bg-error text-white rounded text-xs hover:bg-red-600 transition-colors" 
                                            title="Eliminar jugador">
                                        🗑️
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
    <script src="../js/limite.js"></script>

</body>
</html>