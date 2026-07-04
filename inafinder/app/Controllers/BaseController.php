<?php

namespace App\Controllers;
use App\Controllers\DefaultController;
use App\Models\Monederos;

class BaseController{
    public function renderHTML($fileName, $data=[]){
        include($fileName);
    }

    // Recarga en sesión el monedero del equipo leyéndolo de la BD (fuente de verdad)
    protected function refrescarMonedasSesion($id_equipo){
        $monedero = Monederos::getInstancia()->get($id_equipo);
        $_SESSION['user']['monedas'] = [];
        foreach ($monedero as $fila) {
            $_SESSION['user']['monedas'][$fila['tipo_moneda']] = (int) $fila['cantidad'];
        }
    }

    // Mueve una imagen subida a $dir (creándolo si no existe) con nombre
    // "NombreSaneado_uniqid_original"; devuelve el nombre de archivo o null si falla.
    // Sustituye al bloque de subida que repetían todos los controllers de catálogo.
    protected function subirImagen($dir, $nombreOriginal, $tmpName, $nombreBase)
    {
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
        $nombreSanitizado = preg_replace('/[^A-Za-z0-9_\-]/', '', str_replace(' ', '', $nombreBase));
        $fileName = $nombreSanitizado . '_' . uniqid() . '_' . $nombreOriginal;
        if (move_uploaded_file($tmpName, $dir . $fileName)) {
            return $fileName;
        }
        return null;
    }

    protected function borrarImagen($dir, $nombre)
    {
        if (!empty($nombre) && file_exists($dir . $nombre)) {
            unlink($dir . $nombre);
        }
    }
}

?>
