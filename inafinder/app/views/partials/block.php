<div class="bg-red-600 p-8 rounded-lg shadow-[0_2px_8px_rgba(0,0,0,0.3)] flex flex-col gap-4 min-w-[300px] text-center">
    <div class="flex items-center gap-2 mb-4 justify-center">
        <img src="imgs/src/logo.webp" alt="Logo" class="w-6 h-6 object-contain">
        <h1 class="text-xl font-bold">Acceso Bloqueado</h1>
    </div>
    <p class="text-lg font-bold">Has excedido el número máximo de intentos de login.</p>
    <p class="text-sm">Tu acceso está bloqueado por seguridad.</p>
    <div class="bg-red-800 p-4 rounded">
        <p class="text-sm">Tiempo restante:</p>
        <p id="timer" class="text-2xl font-bold" data-time="<?php echo (int) $data['tiempoRestante']; ?>">
            05:00
        </p>
    </div>
    <p class="text-xs">Podrás intentar iniciar sesión nuevamente cuando el temporizador llegue a cero.</p>
</div>