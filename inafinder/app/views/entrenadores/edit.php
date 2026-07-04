<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Entrenador</title>
    <link href="/../css/output.css" rel="stylesheet">
    <link rel="icon" type="image/x-icon" href="imgs/src/logo.ico">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body class="bg-primary font-sans text-white min-h-screen pt-16">

    <?php include '../app/views/partials/navbar.php'; ?>

    <div class="container mx-auto px-4 py-8">
        <div class="max-w-4xl mx-auto">
            <h1 class="text-3xl font-bold mb-6 text-center">Editar Entrenador</h1>

            <div class="flex justify-center mb-6">
                <a href="/entrenadoresList" class="px-6 py-3 bg-warning text-white rounded-lg hover:bg-yellow-600 transition-colors font-medium focus:ring-2 focus:ring-warning">
                    📋 Ver Lista de Entrenadores
                </a>
            </div>

            <form action="/entrenadoresEdit/<?php echo htmlspecialchars($data['entrenador']['id_ent']); ?>" method="POST" enctype="multipart/form-data" id="mainForm">
                <?php echo \App\Core\Csrf::field(); ?>
                <div id="formsContainer">
                    <!-- Formulario de edición -->
                    <div class="entrenador-form bg-darker rounded-lg p-6 mb-6 shadow-[0_2px_8px_rgba(0,0,0,0.3)] relative" data-form-index="0">
                        
                        <div class="space-y-4">
                            <!-- Imagen actual -->
                            <?php if (!empty($data['entrenador']['imagen'])): ?>
                                <div class="text-center mb-4">
                                    <p class="text-sm text-light-gray mb-2">Imagen actual:</p>
                                    <img src="../imgs/uploads/entrenadores/<?php echo htmlspecialchars($data['entrenador']['imagen']); ?>" 
                                         alt="<?php echo htmlspecialchars($data['entrenador']['nombre']); ?>"
                                         class="w-20 h-20 mx-auto rounded-lg object-cover border-2 border-success">
                                </div>
                            <?php endif; ?>

                            <!-- Nueva imagen (opcional) -->
                            <div>
                                <label class="block text-sm font-medium text-white mb-2">
                                    Nueva Imagen (opcional - deja vacío para mantener la actual)
                                </label>
                                <input type="file" name="entrenadores[0][imagen]" accept="image/*" 
                                       class="block w-full text-white bg-gray border border-light-gray rounded-lg p-2 focus:border-success focus:ring-1 focus:ring-success transition-colors" />
                                <p class="text-xs text-light-gray mt-1">Formatos permitidos: JPG, PNG, WEBP</p>
                            </div>

                            <!-- Nombre -->
                            <div>
                                <label class="block text-sm font-medium text-white mb-2">
                                    Nombre
                                </label>
                                <input type="text" name="entrenador[0][nombre]" 
                                       class="w-full p-3 bg-gray text-white border border-light-gray rounded-lg focus:border-success focus:ring-1 focus:ring-success transition-colors placeholder-light-gray"
                                       value="<?php echo htmlspecialchars($data['entrenador']['nombre'] ?? ''); ?>">
                                <p class="text-xs text-light-gray mt-1">Deja vacío para mantener el valor actual</p>
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
                    <a href="/entrenadoresList" 
                       class="px-8 py-3 bg-gray text-white rounded-lg hover:bg-light-gray transition-colors font-medium text-center focus:ring-2 focus:ring-gray focus:ring-offset-2 focus:ring-offset-darker">
                        Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>

</body>
</html>