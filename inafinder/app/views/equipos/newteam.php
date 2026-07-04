<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="icon" type="image/x-icon" href="imgs/src/logo.ico">
    <link href="/../css/output.css" rel="stylesheet">
    <?php include '../app/views/partials/navbar.php'; ?>

</head>
<body class="bg-primary font-sans text-white flex justify-center items-center h-screen m-0">

    <form class="bg-darker p-8 rounded-lg shadow-[0_2px_8px_rgba(0,0,0,0.3)] flex flex-col gap-4 min-w-[300px]" method="POST" action="/registerTeam" enctype="multipart/form-data">
        <?php echo \App\Core\Csrf::field(); ?>
        <div class="flex items-center gap-2 mb-4">
            <img src="imgs/src/logo.webp" alt="Logo" class="w-6 h-6 object-contain">
            <h1 class="text-xl font-bold">Inafinder - Crear Nuevo Equipo</h1>
        </div>

        <!-- Creamos el formulario con el input del nombre y el input de la foto del escudo -->
        <div class="mb-4">
            <label for="nombre" class="block text-sm font-medium">Nombre del Equipo</label>
            <input type="text" id="nombre" name="nombre" required class="mt-1 p-2 border-b border-gray-300 w-full" value="<?php echo isset($_POST['nombre']) ? htmlspecialchars($_POST['nombre']) : ''; ?>">
        </div>

        <div class="mb-4">
            <label for="escudo" class="block text-sm font-medium">Escudo del Equipo</label>
            <input type="file" id="escudo" name="escudo" accept="image/*" required class="mt-1 p-2 border-b border-gray-300 w-full">
        </div>

        <?php if(!empty($data['error'])): ?>
            <p class="text-red-500 text-sm font-bold"><?php echo e($data['error']); ?></p>
        <?php endif; ?>

        <button type="submit" class="bg-blue-500 text-white p-2 rounded-md">Crear Equipo</button>

    </form>
</body>
</html>