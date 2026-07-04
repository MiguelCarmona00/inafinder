<?php

namespace App\Controllers;

class TirosController extends TecnicaCrudController
{
    protected $tipo = 'tiro';
    protected $plural = 'tiros';
    protected $singular = 'tiro';
    protected $fijos = ['riesgo' => 0, 'eg_damage' => 0];

    // El formulario trae el subtipo en un radio; si llega vacío se conserva el actual
    protected function subtipoEdicion(array $d, array $actual)
    {
        return (isset($d['subtipo']) && trim($d['subtipo']) !== '')
            ? htmlspecialchars(trim($d['subtipo']))
            : $actual['subtipo'];
    }
}
