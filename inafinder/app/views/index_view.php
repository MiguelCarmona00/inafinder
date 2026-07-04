<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil</title>
    <link href="/../css/output.css" rel="stylesheet">
    <link href="../js/swal/sweetalert2.min.css" rel="stylesheet">
    <link rel="icon" type="image/x-icon" href="imgs/src/logo.ico">
    <script src="../js/swal/sweetalert2.all.min.js"></script>
</head>
<body class="bg-primary font-sans text-white min-h-screen pt-16">
    
    <?php include '../app/views/partials/navbar.php'; ?>
    
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-4xl mx-auto">

        <?php //var_dump($_SESSION); ?>
            
            <!-- Header del usuario -->
            <div class="text-center mb-8 flex justify-evenly items-center">
                <img src="<?php echo 'imgs/uploads/escudos/'.  htmlspecialchars($_SESSION['user']['equipo'][0]['escudo']); ?>" alt="" class="w-30 h-30 mb-4">
                <h1 class="text-3xl font-bold mb-4">
                    <?php echo htmlspecialchars($_SESSION['user']['equipo'][0]['nombre_equipo']); ?>
                </h1>
            </div>

            <!-- Monedero del usuario -->
            <div class="bg-darker rounded-lg p-6 mb-8">
                <h3 class="text-xl font-bold mb-4">💰 Monedero</h3>
                <div class="flex flex-wrap gap-4 justify-center">
                    <?php foreach ($data['monedas'] as $tipo => $cantidad): ?>
                        <div class="bg-gray rounded-lg p-4 text-center min-w-[100px]">
                            <img src="../imgs/uploads/coins/<?php echo e($tipo); ?>.webp"
                                 alt="<?php echo e(ucfirst($tipo)); ?>"
                                 class="align-center mx-auto mb-1 w-5 h-5">
                            <div class="font-bold text-lg"><?php echo htmlspecialchars($cantidad); ?></div>
                            <div class="text-sm text-light-gray"><?php echo e(ucfirst($tipo)); ?></div>
                        </div>
                    <?php endforeach; ?> 
                </div>
            </div>
            
            <!-- Mis Jugadores -->
            <div class="bg-darker rounded-lg p-6 mb-8">
                <h2 class="text-2xl font-bold mb-6">⚽ Jugadores</h2>
                
                <?php if (!empty($data['jugadores'])): ?>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <?php foreach ($data['jugadores'] as $jugador): ?>
                            <div class="bg-gray rounded-lg p-6 shadow-[0_2px_8px_rgba(0,0,0,0.3)]">
                                <h3 class="text-xl font-bold mb-4 text-center">
                                    <?php echo htmlspecialchars($jugador[0]['nombre']);?>
                                </h3>
                                
                                <div class="space-y-2 text-sm mb-4">
                                    <div class="flex justify-between">
                                        <span class="text-light-gray">Posición:</span>
                                        <img src="../imgs/uploads/posiciones/<?php echo htmlspecialchars($jugador[0]['posicion']); ?>.webp" alt="<?php echo htmlspecialchars($jugador[0]['posicion']); ?>" class="inline w-8 h-4 align-text-bottom">
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-light-gray">Elemento:</span>
                                        <img src="../imgs/uploads/elementos/<?php echo htmlspecialchars($jugador[0]['elemento']); ?>.webp" 
                                                 alt="<?php echo htmlspecialchars($jugador[0]['elemento']); ?>" 
                                                 class="inline w-5 h-5 align-text-bottom">
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-light-gray">Edad:</span>
                                        <img src="../imgs/uploads/edades/<?php echo htmlspecialchars($jugador[0]['edad']); ?>.webp" 
                                             alt="<?php echo htmlspecialchars($jugador[0]['edad']); ?>" 
                                             class="inline w-8 h-4 align-text-bottom">
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-light-gray">Género:</span>
                                        <img src="../imgs/uploads/generos/<?php echo htmlspecialchars($jugador[0]['genero']); ?>.webp" alt="<?php echo htmlspecialchars($jugador[0]['genero']); ?>" class="inline w-4 h-4 align-text-bottom">
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-light-gray">Precio pagado:</span>
                                        <span class="font-medium">
                                            <span class="px-2"><?php echo htmlspecialchars($jugador[0]['precio_cantidad']); ?></span>
                                            <img src="../imgs/uploads/coins/<?php echo htmlspecialchars($jugador[0]['precio_tipo']); ?>.webp" 
                                                 alt="<?php echo htmlspecialchars($jugador[0]['precio_tipo']); ?>" 
                                                 class="inline w-4 h-4 align-text-bottom">
                                        </span>
                                    </div>
                                </div>
                                
                                <!-- Fecha de fichaje -->
                                <div class="border-t border-light-gray pt-3 mt-3">
                                    <div class="flex justify-between items-center">
                                        <span class="text-light-gray text-sm">📅 Fichado el:</span>
                                        <span class="font-medium text-sm text-success">
                                            <?php 
                                            $fecha = new DateTime($jugador[0]['fecha_fichaje']);
                                            echo $fecha->format('d/m/Y H:i');
                                            ?>
                                        </span>
                                    </div>
                                    
                                    <!-- Botón de reembolso -->
                                    <div class="mt-3 text-center">
                                        <button onclick="confirmarReembolso(<?php echo htmlspecialchars($jugador[0]['id_jugador']); ?>, '<?php echo htmlspecialchars($jugador[0]['nombre']); ?>')" 
                                                class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-lg transition-colors duration-200 text-sm cursor-pointer">
                                            Reembolsar
                                        </button>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="text-center flex flex-row items-center justify-center">
                        <p class="text-light-gray text-lg py-3 ">No tienes jugadores fichados aún.</p>
                    </div>
                <?php endif; ?>
            </div>
            
        </div>
    </div>
    <script src="js/reembolso.js"></script>
    
    <?php if (isset($_SESSION['mensaje_reembolso'])): ?>
    <script>
        <?php if ($_SESSION['mensaje_reembolso']['tipo'] === 'exito'): ?>
            Swal.fire({
                title: '¡Reembolso exitoso!',
                text: '<?php echo addslashes($_SESSION['mensaje_reembolso']['texto']); ?>',
                icon: 'success',
                confirmButtonColor: '#10b981',
                confirmButtonText: '👍 Entendido',
                timer: 3000,
                timerProgressBar: true
            });
        <?php else: ?>
            Swal.fire({
                title: 'Error en el reembolso',
                text: '<?php echo addslashes($_SESSION['mensaje_reembolso']['texto']); ?>',
                icon: 'error',
                confirmButtonColor: '#ef4444',
                confirmButtonText: '🔄 Reintentar',
                showCancelButton: true,
                cancelButtonText: '🏠 Volver al inicio',
                cancelButtonColor: '#6b7280'
            });
        <?php endif; ?>
    </script>
    <?php unset($_SESSION['mensaje_reembolso']); ?>
    <?php endif; ?>
</body>
</html>