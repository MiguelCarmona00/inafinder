<?php

namespace App\Core;

class Csrf
{
    // Devuelve el token CSRF de la sesión, generándolo si aún no existe
    public static function token()
    {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }

    // Campo oculto para incluir dentro de los formularios POST
    public static function field()
    {
        return '<input type="hidden" name="csrf_token" value="' . htmlspecialchars(self::token(), ENT_QUOTES, 'UTF-8') . '">';
    }

    // Expone el token a JavaScript (peticiones AJAX y formularios dinámicos)
    public static function scriptTag()
    {
        return '<script>window.CSRF_TOKEN = ' . json_encode(self::token()) . ';</script>';
    }

    // Valida el token recibido por POST o por la cabecera X-CSRF-Token
    public static function validarPeticion()
    {
        $recibido = $_POST['csrf_token'] ?? ($_SERVER['HTTP_X_CSRF_TOKEN'] ?? '');
        return is_string($recibido) && $recibido !== '' && hash_equals(self::token(), $recibido);
    }
}
