<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mercado</title>
    <link href="/../css/output.css" rel="stylesheet">
    <link href="../js/swal/sweetalert2.min.css" rel="stylesheet">
    <link rel="icon" type="image/x-icon" href="imgs/src/logo.ico">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="../js/swal/sweetalert2.all.min.js"></script>
</head>
<body class="bg-primary font-sans text-white min-h-screen pt-16">

    <?php include '../app/views/partials/navbar.php'; ?>

    <div class="container mx-auto px-4 py-8">
        <div class="max-w-6xl mx-auto">
            <h1 class="text-3xl font-bold mb-6 text-center">Mercado de Jugadores</h1>
            
            <!-- Indicador de estado -->
            <?php if($_SESSION['perfil'] == 'admin'): ?>
            <div id="statusIndicator" class="mb-4 p-3 rounded-lg bg-success text-center">
                <span id="statusText">🟢 Conectado - Actualizaciones en tiempo real</span>
            </div>
            <?php endif; ?>

            <!-- Mensaje de resultados -->
            <div id="resultadosInfo" class="mb-4 p-3 bg-darker rounded-lg text-center hidden">
                <span id="resultadosTexto"></span>
            </div>

            <!-- Mensaje de no encontrado -->
            <div id="noResultados" class="mb-4 p-8 bg-darker rounded-lg text-center hidden">
                <div class="text-6xl mb-4">🔍</div>
                <h3 class="text-xl font-bold mb-2">No se encontraron jugadores</h3>
                <p class="text-gray-400">Intenta ajustar los filtros de búsqueda</p>
            </div>

            <!-- Botón toggle para mostrar/ocultar monedero -->
            <div id="menuToggle" class="fixed top-27 left-4 z-40">
                <button id="toggleWalletBtn" class="bg-darker rounded-lg p-2 hover:bg-gray transition-colors duration-200 shadow-lg cursor-pointer">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                </button>
            </div>
            
            <!-- Monedero en posición fija vertical (oculto por defecto) -->
            <div id="walletContainer" class="fixed top-40 -left-64 z-40 w-40 bg-darker rounded-r-lg shadow-2xl transition-all duration-500 ease-in-out">
                <div class="p-4">
                    <h3 class="text-lg font-bold mb-4 text-center border-b border-gray pb-2">💰 Monedero</h3>
                    <div class="flex flex-col gap-3">
                        <?php foreach ($_SESSION['user']['monedas'] as $tipo => $cantidad): ?>
                            <div class="bg-gray rounded-lg p-3 flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <div class="w-6 h-6">
                                        <img src="../imgs/uploads/coins/<?php echo e($tipo); ?>.webp"
                                             alt="<?php echo e(ucfirst($tipo)); ?>"
                                             class="w-full h-full object-contain">
                                    </div>
                                </div>
                                <div class="font-bold text-lg"><?php echo htmlspecialchars($cantidad); ?></div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <!-- Botón toggle para mostrar/ocultar filtros -->
            <div id="filtersToggle" class="fixed top-27 right-4 z-40">
                <button id="toggleFiltersBtn" class="bg-darker rounded-lg p-2 hover:bg-gray transition-colors duration-200 shadow-lg cursor-pointer">
                    <svg class="w-6 h-6 text-white" id="filtersToggleIcon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
            </div>

            <!-- Panel de filtros en posición fija vertical (oculto por defecto) -->
            <div id="filtersPanel" class="fixed top-27 -right-[30rem] z-40 w-96 max-w-[90vw] h-[calc(100vh-9rem)] bg-darker rounded-l-lg shadow-2xl transition-all duration-500 ease-in-out flex flex-col overflow-hidden">
                <div class="p-4 border-b border-gray shrink-0 flex items-center justify-between">
                    <h2 class="text-lg font-semibold">🔍 Filtros</h2>
                    <!-- Solo en móvil: tocar fuera del panel para cerrarlo es
                         incómodo (el panel ocupa casi toda la pantalla) -->
                    <button id="closeFiltersBtn" class="md:hidden text-white hover:text-error transition-colors duration-200">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <div class="flex-1 overflow-y-auto p-4">
                    <!-- Filtro por nombre -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium mb-2">Nombre</label>
                        <input type="text" id="filtroNombre" placeholder="Buscar por nombre..."
                               class="w-full px-3 py-2 bg-gray border border-gray rounded-lg text-white focus:border-success focus:outline-none">
                    </div>

                    <!-- Ordenar por -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium mb-2">Ordenar por</label>
                        <select id="filtroOrden" class="w-full px-3 py-2 bg-gray border border-gray rounded-lg text-white focus:border-success focus:outline-none">
                            <option value="id_asc">Predeterminado</option>
                            <option value="nombre_asc">Nombre (A-Z)</option>
                            <option value="precio_asc">Precio: menor a mayor</option>
                            <option value="precio_desc">Precio: mayor a menor</option>
                            <option value="pe_desc">PE: mayor a menor</option>
                            <option value="pt_desc">PT: mayor a menor</option>
                            <option value="tiro_desc">Tiro: mayor a menor</option>
                            <option value="regate_desc">Regate: mayor a menor</option>
                            <option value="defensa_desc">Defensa: mayor a menor</option>
                            <option value="control_desc">Control: mayor a menor</option>
                            <option value="tecnica_desc">Técnica: mayor a menor</option>
                            <option value="rapidez_desc">Rapidez: mayor a menor</option>
                            <option value="aguante_desc">Aguante: mayor a menor</option>
                            <option value="suerte_desc">Suerte: mayor a menor</option>
                            <option value="libertad_desc">Libertad: mayor a menor</option>
                        </select>
                    </div>

                    <!-- Filtro por posición -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium mb-2">Posición</label>
                        <select id="filtroPosicion" class="w-full px-3 py-2 bg-gray border border-gray rounded-lg text-white focus:border-success focus:outline-none">
                            <option value="">Todas las posiciones</option>
                            <option value="portero">Portero</option>
                            <option value="defensa">Defensa</option>
                            <option value="centrocampista">Centrocampista</option>
                            <option value="delantero">Delantero</option>
                        </select>
                    </div>

                    <!-- Filtro por elemento -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium mb-2">Elemento</label>
                        <select id="filtroElemento" class="w-full px-3 py-2 bg-gray border border-gray rounded-lg text-white focus:border-success focus:outline-none">
                            <option value="">Todos los elementos</option>
                            <option value="fuego">Fuego</option>
                            <option value="aire">Aire</option>
                            <option value="bosque">Bosque</option>
                            <option value="montaña">Montaña</option>
                        </select>
                    </div>

                    <!-- Filtro por edad -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium mb-2">Edad</label>
                        <select id="filtroEdad" class="w-full px-3 py-2 bg-gray border border-gray rounded-lg text-white focus:border-success focus:outline-none">
                            <option value="">Todas las edades</option>
                            <option value="primero">Primero</option>
                            <option value="segundo">Segundo</option>
                            <option value="tercero">Tercero</option>
                            <option value="adulto">Adulto</option>
                            <option value="escolar">Escolar</option>
                            <option value="desconocido">Desconocido</option>
                        </select>
                    </div>

                    <!-- Filtro por género -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium mb-2">Género</label>
                        <select id="filtroGenero" class="w-full px-3 py-2 bg-gray border border-gray rounded-lg text-white focus:border-success focus:outline-none">
                            <option value="">Todos los géneros</option>
                            <option value="masculino">Masculino</option>
                            <option value="femenino">Femenino</option>
                            <option value="desconocido">Desconocido</option>
                        </select>
                    </div>

                    <!-- Filtro por tipo de moneda -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium mb-2">Tipo de Moneda</label>
                        <select id="filtroPrecioTipo" class="w-full px-3 py-2 bg-gray border border-gray rounded-lg text-white focus:border-success focus:outline-none">
                            <option value="">Todas las monedas</option>
                            <option value="doradas">Doradas</option>
                            <option value="moradas">Moradas</option>
                            <option value="plateadas">Plateadas</option>
                            <option value="azules">Azules</option>
                            <option value="rojas">Rojas</option>
                        </select>
                    </div>

                    <!-- Rango de precio -->
                    <div class="grid grid-cols-2 gap-2 mb-4">
                        <div>
                            <label class="block text-sm font-medium mb-2">Precio Mínimo</label>
                            <input type="number" id="filtroPrecioMin" placeholder="0" min="0"
                                   class="w-full px-3 py-2 bg-gray border border-gray rounded-lg text-white focus:border-success focus:outline-none">
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-2">Precio Máximo</label>
                            <input type="number" id="filtroPrecioMax" placeholder="999" min="0"
                                   class="w-full px-3 py-2 bg-gray border border-gray rounded-lg text-white focus:border-success focus:outline-none">
                        </div>
                    </div>

                    <div class="mb-2">
                        <label class="flex items-center gap-2">
                            <input type="checkbox" id="soloDisponibles" checked class="rounded bg-gray border-gray focus:border-success">
                            <span class="text-sm">Solo mostrar jugadores disponibles</span>
                        </label>
                    </div>

                    <!-- Filtros avanzados (estadísticas) -->
                    <div class="border-t border-gray pt-4 mt-4">
                        <h3 class="text-md font-semibold mb-3">⚙️ Avanzados (Estadísticas)</h3>

                        <div class="flex flex-col gap-4">
                            <!-- PE -->
                            <div>
                                <label class="block text-sm font-medium mb-2">PE (Puntos de Energía)</label>
                                <div class="flex gap-2">
                                    <input type="number" id="peMin" placeholder="Min" min="0" max="999"
                                           class="w-full px-3 py-2 bg-gray border border-gray rounded-lg text-white focus:border-success focus:outline-none">
                                    <input type="number" id="peMax" placeholder="Max" min="0" max="999"
                                           class="w-full px-3 py-2 bg-gray border border-gray rounded-lg text-white focus:border-success focus:outline-none">
                                </div>
                            </div>

                            <!-- PT -->
                            <div>
                                <label class="block text-sm font-medium mb-2">PT (Puntos de Técnica)</label>
                                <div class="flex gap-2">
                                    <input type="number" id="ptMin" placeholder="Min" min="0" max="999"
                                           class="w-full px-3 py-2 bg-gray border border-gray rounded-lg text-white focus:border-success focus:outline-none">
                                    <input type="number" id="ptMax" placeholder="Max" min="0" max="999"
                                           class="w-full px-3 py-2 bg-gray border border-gray rounded-lg text-white focus:border-success focus:outline-none">
                                </div>
                            </div>

                            <!-- Tiro -->
                            <div>
                                <label class="block text-sm font-medium mb-2">Tiro</label>
                                <div class="flex gap-2">
                                    <input type="number" id="tiroMin" placeholder="Min" min="0" max="999"
                                           class="w-full px-3 py-2 bg-gray border border-gray rounded-lg text-white focus:border-success focus:outline-none">
                                    <input type="number" id="tiroMax" placeholder="Max" min="0" max="999"
                                           class="w-full px-3 py-2 bg-gray border border-gray rounded-lg text-white focus:border-success focus:outline-none">
                                </div>
                            </div>

                            <!-- Regate -->
                            <div>
                                <label class="block text-sm font-medium mb-2">Regate</label>
                                <div class="flex gap-2">
                                    <input type="number" id="regateMin" placeholder="Min" min="0" max="999"
                                           class="w-full px-3 py-2 bg-gray border border-gray rounded-lg text-white focus:border-success focus:outline-none">
                                    <input type="number" id="regateMax" placeholder="Max" min="0" max="999"
                                           class="w-full px-3 py-2 bg-gray border border-gray rounded-lg text-white focus:border-success focus:outline-none">
                                </div>
                            </div>

                            <!-- Defensa -->
                            <div>
                                <label class="block text-sm font-medium mb-2">Defensa</label>
                                <div class="flex gap-2">
                                    <input type="number" id="defensaMin" placeholder="Min" min="0" max="999"
                                           class="w-full px-3 py-2 bg-gray border border-gray rounded-lg text-white focus:border-success focus:outline-none">
                                    <input type="number" id="defensaMax" placeholder="Max" min="0" max="999"
                                           class="w-full px-3 py-2 bg-gray border border-gray rounded-lg text-white focus:border-success focus:outline-none">
                                </div>
                            </div>

                            <!-- Control -->
                            <div>
                                <label class="block text-sm font-medium mb-2">Control</label>
                                <div class="flex gap-2">
                                    <input type="number" id="controlMin" placeholder="Min" min="0" max="999"
                                           class="w-full px-3 py-2 bg-gray border border-gray rounded-lg text-white focus:border-success focus:outline-none">
                                    <input type="number" id="controlMax" placeholder="Max" min="0" max="999"
                                           class="w-full px-3 py-2 bg-gray border border-gray rounded-lg text-white focus:border-success focus:outline-none">
                                </div>
                            </div>

                            <!-- Técnica -->
                            <div>
                                <label class="block text-sm font-medium mb-2">Técnica</label>
                                <div class="flex gap-2">
                                    <input type="number" id="tecnicaMin" placeholder="Min" min="0" max="999"
                                           class="w-full px-3 py-2 bg-gray border border-gray rounded-lg text-white focus:border-success focus:outline-none">
                                    <input type="number" id="tecnicaMax" placeholder="Max" min="0" max="999"
                                           class="w-full px-3 py-2 bg-gray border border-gray rounded-lg text-white focus:border-success focus:outline-none">
                                </div>
                            </div>

                            <!-- Rapidez -->
                            <div>
                                <label class="block text-sm font-medium mb-2">Rapidez</label>
                                <div class="flex gap-2">
                                    <input type="number" id="rapidezMin" placeholder="Min" min="0" max="999"
                                           class="w-full px-3 py-2 bg-gray border border-gray rounded-lg text-white focus:border-success focus:outline-none">
                                    <input type="number" id="rapidezMax" placeholder="Max" min="0" max="999"
                                           class="w-full px-3 py-2 bg-gray border border-gray rounded-lg text-white focus:border-success focus:outline-none">
                                </div>
                            </div>

                            <!-- Aguante -->
                            <div>
                                <label class="block text-sm font-medium mb-2">Aguante</label>
                                <div class="flex gap-2">
                                    <input type="number" id="aguanteMin" placeholder="Min" min="0" max="999"
                                           class="w-full px-3 py-2 bg-gray border border-gray rounded-lg text-white focus:border-success focus:outline-none">
                                    <input type="number" id="aguanteMax" placeholder="Max" min="0" max="999"
                                           class="w-full px-3 py-2 bg-gray border border-gray rounded-lg text-white focus:border-success focus:outline-none">
                                </div>
                            </div>

                            <!-- Suerte -->
                            <div>
                                <label class="block text-sm font-medium mb-2">Suerte</label>
                                <div class="flex gap-2">
                                    <input type="number" id="suerteMin" placeholder="Min" min="0" max="999"
                                           class="w-full px-3 py-2 bg-gray border border-gray rounded-lg text-white focus:border-success focus:outline-none">
                                    <input type="number" id="suerteMax" placeholder="Max" min="0" max="999"
                                           class="w-full px-3 py-2 bg-gray border border-gray rounded-lg text-white focus:border-success focus:outline-none">
                                </div>
                            </div>

                            <!-- Libertad -->
                            <div>
                                <label class="block text-sm font-medium mb-2">Libertad</label>
                                <div class="flex gap-2">
                                    <input type="number" id="libertadMin" placeholder="Min" min="0" max="999"
                                           class="w-full px-3 py-2 bg-gray border border-gray rounded-lg text-white focus:border-success focus:outline-none">
                                    <input type="number" id="libertadMax" placeholder="Max" min="0" max="999"
                                           class="w-full px-3 py-2 bg-gray border border-gray rounded-lg text-white focus:border-success focus:outline-none">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Acciones fijas (no hacen scroll con el contenido) -->
                <div class="p-4 border-t border-gray shrink-0">
                    <button id="limpiarFiltros" class="w-full px-4 py-2 bg-error text-white rounded-lg hover:bg-red-600 transition-colors">
                        Limpiar filtros
                    </button>
                </div>
            </div>

            <!-- Contenedor de jugadores disponibles -->
            <div class="mb-8">
                <div id="jugadoresNoFichados" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 custom-grid-responsive"></div>
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
    <script src="../js/filtros.js"></script>
    <script src="../js/mercado.js"></script>
    <script src="../js/monedero.js"></script>

</body>
</html>