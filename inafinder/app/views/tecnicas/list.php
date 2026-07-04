<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Técnicas</title>
    <link href="/../css/output.css" rel="stylesheet">
    <?php include '../app/views/partials/sweetalert.php'; ?>
    <link rel="icon" type="image/x-icon" href="imgs/src/logo.ico">
</head>
<body class="bg-primary font-sans text-white min-h-screen pt-16">

    <?php include '../app/views/partials/navbar.php'; ?>

    <div class="container mx-auto px-4 py-8">
        <div class="max-w-6xl mx-auto">

            <div class="flex justify-between items-center mb-8">
                <h1 class="text-3xl font-bold">Gestión de Técnicas</h1>
                <a href="/newTecnicas" class="px-6 py-3 bg-success text-white rounded-lg hover:bg-green-600 transition-colors font-medium focus:ring-2 focus:ring-success">
                    ➕ Añadir Nuevas Técnicas
                </a>
            </div>

            <!-- Selector de límite y búsqueda -->
            <div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-darker rounded-lg p-4 shadow-[0_2px_8px_rgba(0,0,0,0.3)]">
                <div class="flex items-center gap-3">
                    <label for="limite-selector" class="text-sm font-medium text-light-gray">
                        Mostrar:
                    </label>
                    <select id="limite-selector" 
                            class="bg-gray text-white border border-success rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-success focus:border-transparent transition-all duration-300"
                            onchange="cambiarLimite(this.value)">
                        <option value="25" <?php echo (!isset($_GET['limite']) || $_GET['limite'] == 25) ? 'selected' : ''; ?>>25 técnicas</option>
                        <option value="50" <?php echo (isset($_GET['limite']) && $_GET['limite'] == 50) ? 'selected' : ''; ?>>50 técnicas</option>
                        <option value="75" <?php echo (isset($_GET['limite']) && $_GET['limite'] == 75) ? 'selected' : ''; ?>>75 técnicas</option>
                        <option value="100" <?php echo (isset($_GET['limite']) && $_GET['limite'] == 100) ? 'selected' : ''; ?>>100 técnicas</option>
                        <option value="150" <?php echo (isset($_GET['limite']) && $_GET['limite'] == 150) ? 'selected' : ''; ?>>150 técnicas</option>
                        <option value="200" <?php echo (isset($_GET['limite']) && $_GET['limite'] == 200) ? 'selected' : ''; ?>>200 técnicas</option>
                        <option value="0" <?php echo (isset($_GET['limite']) && $_GET['limite'] == 0) ? 'selected' : ''; ?>>Todas (<?php echo (int) ($data['total_tecnicas'] ?? 0); ?>)</option>
                    </select>
                </div>
                
                <div class="text-sm text-light-gray">
                    <?php 
                    $limiteActual = isset($_GET['limite']) ? intval($_GET['limite']) : 25;
                    $totalTecnicas = $data['total_tecnicas'] ?? 0;
                    $tecnicasMostradas = count($data['tecnicas'] ?? []);
                    
                    if ($limiteActual > 0 && $limiteActual < $totalTecnicas) {
                        echo "Mostrando {$tecnicasMostradas} de {$totalTecnicas} técnicas";
                    } else {
                        echo "Mostrando todas las técnicas ({$tecnicasMostradas})";
                    }
                    ?>
                </div>
            </div>

            <!-- Barra de búsqueda -->
            <div class="mb-6 bg-darker rounded-lg p-4 shadow-[0_2px_8px_rgba(0,0,0,0.3)]">
                <form method="GET" action="/tecnicasList" class="flex flex-col sm:flex-row gap-4">
                    <div class="flex-1">
                        <input type="text" 
                               name="buscar" 
                               value="<?php echo htmlspecialchars($_GET['buscar'] ?? ''); ?>"
                               placeholder="Buscar técnica por nombre..." 
                               class="w-full px-4 py-2 bg-gray text-white border border-success rounded-lg focus:ring-2 focus:ring-success focus:border-transparent transition-all duration-300">
                    </div>
                    <div class="flex gap-2">
                        <button type="submit" class="px-6 py-2 bg-success text-white rounded-lg hover:bg-green-600 transition-colors font-medium focus:ring-2 focus:ring-success">
                            🔍 Buscar
                        </button>
                        <?php if (!empty($_GET['buscar'])): ?>
                        <a href="/tecnicasList" class="px-6 py-2 bg-gray text-white rounded-lg hover:bg-gray-600 transition-colors font-medium focus:ring-2 focus:ring-gray-500">
                            ✖️ Limpiar
                        </a>
                        <?php endif; ?>
                    </div>
                </form>
            </div>

            <!-- Resultados -->
            <div class="bg-darker rounded-lg p-6 shadow-[0_2px_8px_rgba(0,0,0,0.3)]">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-xl font-bold">
                        <?php if (!empty($_GET['buscar'])): ?>
                            Resultados de búsqueda para: "<?php echo htmlspecialchars($_GET['buscar']); ?>"
                        <?php else: ?>
                            Todas las Técnicas
                        <?php endif; ?>
                    </h2>
                </div>

                <?php if (empty($data['tecnicas'])): ?>
                    <div class="text-center py-8">
                        <p class="text-light-gray text-lg">
                            <?php if (!empty($_GET['buscar'])): ?>
                                No se encontraron técnicas con ese nombre.
                            <?php else: ?>
                                No hay técnicas registradas.
                            <?php endif; ?>
                        </p>
                    </div>
                <?php else: ?>
                    <div class="overflow-x-auto">
                        <table class="w-full border-collapse">
                            <thead>
                                <tr class="border-b border-success">
                                    <th class="text-left p-4 font-semibold">ID</th>
                                    <th class="text-left p-4 font-semibold">Imagen</th>
                                    <th class="text-left p-4 font-semibold">Nombre</th>
                                    <th class="text-left p-4 font-semibold">Tipo</th>
                                    <th class="text-left p-4 font-semibold">Subtipo</th>
                                    <th class="text-left p-4 font-semibold">Elemento</th>
                                    <th class="text-left p-4 font-semibold">Potencia</th>
                                    <th class="text-left p-4 font-semibold">Costo</th>
                                    <th class="text-left p-4 font-semibold">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($data['tecnicas'] as $tecnica): ?>
                                    <tr class="border-b border-gray-700 hover:bg-gray-800 transition-colors">
                                        <td class="p-4"><?php echo (int) $tecnica['id_supertecnica']; ?></td>
                                        <td class="p-4">
                                            <?php if (!empty($tecnica['imagen'])): ?>
                                                <img src="/imgs/uploads/<?php echo e($tecnica['tipo_principal']); ?>s/<?php echo e($tecnica['imagen']); ?>"
                                                     alt="<?php echo htmlspecialchars($tecnica['nombre']); ?>" 
                                                     class="w-12 h-12 object-cover rounded-lg border border-success">
                                            <?php else: ?>
                                                <div class="w-12 h-12 bg-gray-600 rounded-lg border border-success flex items-center justify-center">
                                                    <span class="text-xs text-gray-400">Sin img</span>
                                                </div>
                                            <?php endif; ?>
                                        </td>
                                        <td class="p-4 font-medium"><?php echo htmlspecialchars($tecnica['nombre']); ?></td>
                                        <td class="p-4">
                                            <span class="px-2 py-1 rounded text-xs font-medium
                                                <?php 
                                                switch($tecnica['tipo_principal']) {
                                                    case 'tiro': echo 'bg-red-500/20 text-red-400 border border-red-500/30'; break;
                                                    case 'regate': echo 'bg-blue-500/20 text-blue-400 border border-blue-500/30'; break;
                                                    case 'bloqueo': echo 'bg-yellow-500/20 text-yellow-400 border border-yellow-500/30'; break;
                                                    case 'parada': echo 'bg-green-500/20 text-green-400 border border-green-500/30'; break;
                                                    default: echo 'bg-gray-500/20 text-gray-400 border border-gray-500/30';
                                                }
                                                ?>">
                                                <?php echo e(ucfirst($tecnica['tipo_principal'])); ?>
                                            </span>
                                        </td>
                                        <td class="p-4">
                                            <?php if ($tecnica['subtipo'] != 'normal'): ?>
                                                <span class="px-2 py-1 rounded text-xs font-medium bg-purple-500/20 text-purple-400 border border-purple-500/30">
                                                    <?php echo e(ucfirst($tecnica['subtipo'])); ?>
                                                </span>
                                            <?php else: ?>
                                                <span class="text-gray-400 text-xs">Normal</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="p-4">
                                            <span class="px-2 py-1 rounded text-xs font-medium
                                                <?php 
                                                switch($tecnica['elemento']) {
                                                    case 'fuego': echo 'bg-red-500/20 text-red-400 border border-red-500/30'; break;
                                                    case 'agua': echo 'bg-blue-500/20 text-blue-400 border border-blue-500/30'; break;
                                                    case 'viento': echo 'bg-green-500/20 text-green-400 border border-green-500/30'; break;
                                                    case 'tierra': echo 'bg-yellow-500/20 text-yellow-400 border border-yellow-500/30'; break;
                                                    case 'normal': echo 'bg-gray-500/20 text-gray-400 border border-gray-500/30'; break;
                                                    default: echo 'bg-gray-500/20 text-gray-400 border border-gray-500/30';
                                                }
                                                ?>">
                                                <?php echo e(ucfirst($tecnica['elemento'])); ?>
                                            </span>
                                        </td>
                                        <td class="p-4 font-mono"><?php echo e($tecnica['potencia']); ?></td>
                                        <td class="p-4 font-mono"><?php echo e($tecnica['costo']); ?></td>
                                        <td class="p-4">
                                            <div class="flex gap-2">
                                                <a href="/tecnicasEdit/<?php echo (int) $tecnica['id_supertecnica']; ?>" 
                                                   class="px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700 transition-colors text-sm">
                                                    ✏️ Editar
                                                </a>
                                                <button onclick="confirmarEliminacionTecnica(<?php echo (int) $tecnica['id_supertecnica']; ?>, '<?php echo htmlspecialchars($tecnica['nombre'], ENT_QUOTES); ?>')" 
                                                        class="px-3 py-1 bg-red-600 text-white rounded hover:bg-red-700 transition-colors text-sm">
                                                    🗑️ Eliminar
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script src="/js/limite.js"></script>
    <script src="/js/confirmDelete.js"></script>

</body>
</html>
