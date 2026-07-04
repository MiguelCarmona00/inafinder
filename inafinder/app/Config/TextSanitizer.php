<?php

namespace App\Config;

class TextSanitizer
{
    /**
     * Sanitiza el nombre removiendo tildes, convirtiendo a minúsculas y removiendo caracteres especiales
     */
    public static function sanitizarNombre($nombre)
    {
        // Convertir a minúsculas
        $nombre = strtolower($nombre);
        
        // Remover tildes y caracteres especiales
        $caracteresEspeciales = [
            'á' => 'a', 'à' => 'a', 'ä' => 'a', 'â' => 'a', 'ā' => 'a', 'ã' => 'a',
            'é' => 'e', 'è' => 'e', 'ë' => 'e', 'ê' => 'e', 'ē' => 'e',
            'í' => 'i', 'ì' => 'i', 'ï' => 'i', 'î' => 'i', 'ī' => 'i',
            'ó' => 'o', 'ò' => 'o', 'ö' => 'o', 'ô' => 'o', 'ō' => 'o', 'õ' => 'o',
            'ú' => 'u', 'ù' => 'u', 'ü' => 'u', 'û' => 'u', 'ū' => 'u',
            'ñ' => 'n', 'ç' => 'c'
        ];
        
        $nombre = strtr($nombre, $caracteresEspeciales);
        
        // Remover espacios y caracteres no alfanuméricos
        $nombre = preg_replace('/[^a-z0-9]/', '', $nombre);
        
        return $nombre;
    }
}

?>