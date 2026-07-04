<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Equipo</title>
    <link rel="icon" type="image/x-icon" href="imgs/src/logo.ico">
    <link href="/../css/output.css" rel="stylesheet">
    <?php include '../app/views/partials/navbar.php'; ?>

</head>
<body class="bg-primary font-sans text-white flex justify-center items-center h-screen m-0">

    <form class="bg-darker p-8 rounded-lg shadow-[0_2px_8px_rgba(0,0,0,0.3)] flex flex-col gap-4 min-w-[300px]" method="POST" action="/editTeam" enctype="multipart/form-data">
        <?php echo \App\Core\Csrf::field(); ?>
        <div class="flex items-center gap-2 mb-4">
            <img src="imgs/src/logo.webp" alt="Logo" class="w-6 h-6 object-contain">
            <h1 class="text-xl font-bold">Inafinder - Editar Equipo</h1>
        </div>

        <!-- Mostrar información actual del equipo -->
        <div class="mb-4 text-center">
            <?php if (!empty($data['equipo']['escudo'])): ?>
                <img src="imgs/uploads/escudos/<?php echo htmlspecialchars($data['equipo']['escudo']); ?>" 
                     alt="Escudo actual" 
                     class="w-16 h-16 object-contain mx-auto mb-2 rounded-lg">
                <p class="text-sm text-gray-300">Escudo actual</p>
            <?php endif; ?>
        </div>

        <!-- Formulario con los campos del nombre y el escudo -->
        <div class="mb-4">
            <label for="nombre" class="block text-sm font-medium">Nombre del Equipo</label>
            <input type="text" 
                   id="nombre" 
                   name="nombre" 
                   required 
                   class="mt-1 p-2 border-b border-gray-300 w-full bg-darker text-white" 
                   value="<?php echo isset($_POST['nombre']) ? htmlspecialchars($_POST['nombre']) : htmlspecialchars($data['equipo']['nombre_equipo'] ?? ''); ?>">
        </div>

        <div class="mb-4">
            <label for="escudo" class="block text-sm font-medium">Nuevo Escudo del Equipo</label>
            <input type="file" 
                   id="escudo" 
                   name="escudo" 
                   accept="image/png" 
                   class="mt-1 p-2 border-b border-gray-300 w-full bg-darker text-white file:bg-blue-500 file:text-white file:border-none file:rounded file:px-4 file:py-2 file:mr-4">
            <p class="text-xs text-gray-400 mt-1">Deja vacío si no quieres cambiar el escudo actual. Solo archivos PNG o WebP.</p>
        </div>

        <?php if(!empty($data['error'])): ?>
            <p class="text-red-500 text-sm font-bold"><?php echo htmlspecialchars($data['error']); ?></p>
        <?php endif; ?>

        <?php if(!empty($data['success'])): ?>
            <p class="text-green-500 text-sm font-bold"><?php echo htmlspecialchars($data['success']); ?></p>
        <?php endif; ?>

        <div class="flex gap-4">
            <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white p-2 rounded-md flex-1 transition-colors">
                Guardar Cambios
            </button>
            <a href="/" class="bg-gray-500 hover:bg-gray-600 text-white p-2 rounded-md flex-1 text-center transition-colors">
                Cancelar
            </a>
        </div>

    </form>
</body>
</html>
