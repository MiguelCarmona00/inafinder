<?php

namespace App\Config;

class ImageConverter
{
    /**
     * Valida una imagen subida inspeccionando su contenido real (no el MIME que
     * declara el navegador, que es falsificable). Devuelve el MIME real
     * ('image/png' o 'image/webp') o null si el archivo no es válido:
     * no es una subida real, tiene error, supera $maxBytes o no es PNG/WebP.
     */
    public static function validarEscudo($archivo, $maxBytes = 2097152)
    {
        if (!is_array($archivo)
            || ($archivo['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK
            || !is_uploaded_file($archivo['tmp_name'] ?? '')) {
            return null;
        }

        if (($archivo['size'] ?? 0) > $maxBytes || filesize($archivo['tmp_name']) > $maxBytes) {
            return null;
        }

        $info = @getimagesize($archivo['tmp_name']);
        if ($info === false) {
            return null;
        }

        return in_array($info['mime'], ['image/png', 'image/webp'], true) ? $info['mime'] : null;
    }

    /**
     * Convierte una imagen a formato WebP
     */
    public static function convertirAWebP($rutaOrigen, $rutaDestino)
    {
        // Verificar que la extensión GD esté disponible
        if (!extension_loaded('gd')) {
            return false;
        }

        // Obtener información de la imagen
        $infoImagen = @getimagesize($rutaOrigen);
        if ($infoImagen === false) {
            return false;
        }

        $tipoMime = $infoImagen['mime'];
        
        // Crear la imagen desde el archivo según su tipo
        switch ($tipoMime) {
            case 'image/jpeg':
                $imagen = imagecreatefromjpeg($rutaOrigen);
                break;
            case 'image/png':
                $imagen = imagecreatefrompng($rutaOrigen);
                break;
            case 'image/gif':
                $imagen = imagecreatefromgif($rutaOrigen);
                break;
            default:
                return false;
        }

        if ($imagen === false) {
            return false;
        }

        // Preservar transparencia para PNG
        if ($tipoMime === 'image/png') {
            imagepalettetotruecolor($imagen);
            imagealphablending($imagen, true);
            imagesavealpha($imagen, true);
        }

        // Convertir a WebP con calidad del 90%
        $resultado = imagewebp($imagen, $rutaDestino, 90);
        
        // Liberar memoria
        imagedestroy($imagen);
        
        return $resultado;
    }
}

?>