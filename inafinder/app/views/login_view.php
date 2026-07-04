<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="icon" type="image/x-icon" href="imgs/src/logo.ico">
    <link href="/../css/output.css" rel="stylesheet">
    <?php if (!empty($data['bloqueado'])): ?>
    <script src="/js/loginTimer.js"></script>
    <?php endif; ?>
</head>
<body class="bg-primary font-sans text-white flex justify-center items-center h-screen m-0">

    <div class="flex flex-col lg:flex-row items-center justify-center gap-8 lg:gap-12 p-4">
        <?php if (!empty($data['bloqueado'])): ?>
            <?php include '../app/views/partials/block.php'; ?>
        <?php else: ?>
            <!-- Formulario de login normal -->
            <form class="bg-darker p-8 rounded-lg shadow-[0_2px_8px_rgba(0,0,0,0.3)] flex flex-col gap-4 min-w-[300px]" method="POST" action="/login">
                <?php echo \App\Core\Csrf::field(); ?>
                <div class="flex items-center gap-2 mb-4">
                    <img src="imgs/src/logo.webp" alt="Logo" class="w-6 h-6 object-contain">
                    <h1 class="text-xl font-bold">Inafinder - Login</h1>
                </div>
                
                <?php if (isset($data['intentosRestantes']) && $data['intentosRestantes'] < 5): ?>
                    <div class="bg-yellow-600 p-3 rounded text-sm text-center">
                        <p class="font-bold">⚠️ Advertencia</p>
                        <p>Te quedan <?php echo (int) $data['intentosRestantes']; ?> intento(s) antes del bloqueo</p>
                    </div>
                <?php endif; ?>
                
                <label for="username" class="text-sm">Email o Usuario</label>
                <input type="text" id="username" name="email" value="<?php echo htmlspecialchars($data['email'], ENT_QUOTES, 'UTF-8'); ?>" required
                       class="p-2 border-none rounded text-base text-gray-900 bg-white" 
                       placeholder="Email o nombre de usuario">
                
                <label for="password" class="text-sm">Contraseña</label>
                <input type="password" id="password" name="password" required
                       class="p-2 border-none rounded text-base text-gray-900 bg-white"
                       placeholder="Ingrese su contraseña">
                
                <button type="submit" class="p-2 border-none rounded bg-info text-white text-base cursor-pointer hover:bg-success-hover transition-colors">
                    Entrar
                </button>

                <?php if (!empty($data['errorLogin'])): ?>
                    <p class="text-red-500 text-sm font-bold"><?php echo e($data['errorLogin']); ?></p>
                <?php endif; ?>
            </form>
        <?php endif; ?>
    </div>
    
</body>
</html>