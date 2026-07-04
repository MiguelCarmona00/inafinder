<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($data['jugador']['nombre']); ?> - Detalles del Jugador</title>
    <link href="/../css/output.css" rel="stylesheet">
    <link rel="icon" type="image/x-icon" href="/../imgs/src/logo.ico">
</head>
<body class="bg-primary font-sans text-white min-h-screen pt-16">

    <?php include '../app/views/partials/navbar.php'; ?>

    <div class="container mx-auto px-4 py-8">
        <div class="max-w-4xl mx-auto">
            
            <!-- Botón volver -->
            <a href="/mercado" class="inline-flex items-center gap-2 text-success hover:text-green-400 mb-6 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Volver al mercado
            </a>

            <!-- Card principal del jugador -->
            <div class="bg-darker rounded-lg shadow-2xl overflow-hidden">
                
                <!-- Header con imagen y nombre -->
                <div class="bg-gradient-to-r from-gray to-darker p-8 text-center relative">
                    <?php if ($data['jugador']['esFichado']): ?>
                        <span class="absolute top-4 right-4 bg-error text-white text-sm font-bold px-4 py-2 rounded-full">
                            ✅ Fichado
                        </span>
                    <?php else: ?>
                        <span class="absolute top-4 right-4 bg-success text-white text-sm font-bold px-4 py-2 rounded-full">
                            💰 Disponible
                        </span>
                    <?php endif; ?>
                    
                    <img src="/../imgs/uploads/jugadores/<?php echo htmlspecialchars($data['jugador']['imagen']); ?>" 
                         alt="<?php echo htmlspecialchars($data['jugador']['nombre']); ?>"
                         class="w-32 h-32 rounded-full mx-auto mb-4 border-4 border-secondary shadow-lg">
                    
                    <h1 class="text-4xl font-bold mb-2"><?php echo htmlspecialchars($data['jugador']['nombre']); ?></h1>
                    
                    <p class="text-xl text-gray-300 capitalize">
                        <?php echo htmlspecialchars($data['jugador']['posicion']); ?> | 
                        <?php echo htmlspecialchars($data['jugador']['elemento']); ?>
                    </p>
                </div>

                <!-- Información básica -->
                <div class="p-8">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                        
                        <!-- Precio -->
                        <div class="bg-gray rounded-lg p-4 text-center">
                            <p class="text-sm text-gray-400 mb-2">💰 Precio</p>
                            <div class="flex justify-center items-center gap-2">
                                <p class="text-2xl font-bold"><?php echo htmlspecialchars($data['jugador']['precio_cantidad']); ?></p>
                                <img src="/../imgs/uploads/coins/<?php echo htmlspecialchars($data['jugador']['precio_tipo']); ?>.webp" 
                                     alt="<?php echo htmlspecialchars($data['jugador']['precio_tipo']); ?>" 
                                     class="w-6 h-6">
                            </div>
                        </div>

                        <!-- Edad -->
                        <div class="bg-gray rounded-lg p-4 text-center">
                            <p class="text-sm text-gray-400 mb-2">🎂 Edad</p>
                            <p class="text-2xl font-bold capitalize">
                                <?php 
                                    $edades = ['primero' => '1º', 'segundo' => '2º', 'tercero' => '3º', 'adulto' => 'Adulto'];
                                    echo $edades[$data['jugador']['edad']] ?? htmlspecialchars($data['jugador']['edad']); 
                                ?>
                            </p>
                        </div>

                        <!-- Género -->
                        <div class="bg-gray rounded-lg p-4 text-center">
                            <p class="text-sm text-gray-400 mb-2">⚧ Género</p>
                            <p class="text-2xl font-bold capitalize">
                                <?php 
                                    $generos = ['masculino' => 'Masculino', 'femenino' => 'Femenino'];
                                    echo $generos[$data['jugador']['genero']] ?? htmlspecialchars($data['jugador']['genero']); 
                                ?>
                            </p>
                        </div>

                        <!-- Posición -->
                        <div class="bg-gray rounded-lg p-4 text-center">
                            <p class="text-sm text-gray-400 mb-2">⚽ Posición</p>
                            <p class="text-2xl font-bold capitalize">
                                <?php 
                                    $posiciones = [
                                        'portero' => 'Portero', 
                                        'defensa' => 'Defensa', 
                                        'centrocampista' => 'Centrocampista', 
                                        'delantero' => 'Delantero'
                                    ];
                                    echo $posiciones[$data['jugador']['posicion']] ?? htmlspecialchars($data['jugador']['posicion']); 
                                ?>
                            </p>
                        </div>

                    </div>

                    <!-- Estadísticas detalladas -->
                    <div class="mb-8">
                        <h2 class="text-2xl font-bold mb-4 border-b border-gray pb-2">📈 Estadísticas</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">

                            <div class="bg-gray rounded-lg p-4 flex justify-between items-center">
                                <p class="text-gray-300">PE</p>
                                <p class="text-xl font-bold"><?php echo htmlspecialchars($data['jugador']['pe']); ?></p>
                            </div>

                            <div class="bg-gray rounded-lg p-4 flex justify-between items-center">
                                <p class="text-gray-300">PT</p>
                                <p class="text-xl font-bold"><?php echo htmlspecialchars($data['jugador']['pt']); ?></p>
                            </div>

                            <div class="bg-gray rounded-lg p-4 flex justify-between items-center">
                                <p class="text-gray-300">Tiro</p>
                                <p class="text-xl font-bold"><?php echo htmlspecialchars($data['jugador']['tiro']); ?></p>
                            </div>

                            <div class="bg-gray rounded-lg p-4 flex justify-between items-center">
                                <p class="text-gray-300">Regate</p>
                                <p class="text-xl font-bold"><?php echo htmlspecialchars($data['jugador']['regate']); ?></p>
                            </div>
                            
                            <div class="bg-gray rounded-lg p-4 flex justify-between items-center">
                                <span class="text-gray-300">🛡️ Defensa</span>
                                <span class="text-xl font-bold"><?php echo htmlspecialchars($data['jugador']['defensa']); ?></span>
                            </div>

                            <div class="bg-gray rounded-lg p-4 flex justify-between items-center">
                                <span class="text-gray-300">🎮 Control</span>
                                <span class="text-xl font-bold"><?php echo htmlspecialchars($data['jugador']['control']); ?></span>
                            </div>

                            <div class="bg-gray rounded-lg p-4 flex justify-between items-center">
                                <span class="text-gray-300">⚡ Técnica</span>
                                <span class="text-xl font-bold"><?php echo htmlspecialchars($data['jugador']['técnica'] ?? $data['jugador']['tecnica']); ?></span>
                            </div>

                            <div class="bg-gray rounded-lg p-4 flex justify-between items-center">
                                <span class="text-gray-300">🏃 Rapidez</span>
                                <span class="text-xl font-bold"><?php echo htmlspecialchars($data['jugador']['rapidez']); ?></span>
                            </div>

                            <div class="bg-gray rounded-lg p-4 flex justify-between items-center">
                                <span class="text-gray-300">💪 Aguante</span>
                                <span class="text-xl font-bold"><?php echo htmlspecialchars($data['jugador']['aguante']); ?></span>
                            </div>

                            <div class="bg-gray rounded-lg p-4 flex justify-between items-center">
                                <span class="text-gray-300">🍀 Suerte</span>
                                <span class="text-xl font-bold"><?php echo htmlspecialchars($data['jugador']['suerte']); ?></span>
                            </div>

                            <div class="bg-gray rounded-lg p-4 flex justify-between items-center md:col-span-2">
                                <span class="text-gray-300">🦅 Libertad</span>
                                <span class="text-xl font-bold"><?php echo htmlspecialchars($data['jugador']['libertad']); ?></span>
                            </div>

                        </div>
                    </div>

                    <!-- Botones de acción -->
                    <div class="flex gap-4 justify-center mt-8">
                        <a href="/mercado" 
                           class="px-6 py-3 bg-gray hover:bg-gray-600 text-white rounded-lg font-medium transition-colors">
                            ← Volver al Mercado
                        </a>
                    </div>

                </div>

            </div>

        </div>
    </div>

</body>
</html>
