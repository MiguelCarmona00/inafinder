<?php

namespace App\Controllers;

class BloqueosController extends TecnicaCrudController
{
    protected $tipo = 'bloqueo';
    protected $plural = 'bloqueos';
    protected $singular = 'bloqueo';
    protected $fijos = ['aturdimiento' => 0];

    // El formulario de alta envía 'Eg_damage' con mayúscula
    protected function normalizar(array $d)
    {
        if (isset($d['Eg_damage']) && !isset($d['eg_damage'])) {
            $d['eg_damage'] = $d['Eg_damage'];
        }
        return $d;
    }

    // Checkbox del formulario: marcado = bloqueo, sin marcar = normal
    protected function subtipoEdicion(array $d, array $actual)
    {
        return (($d['subtipo'] ?? '') === 'bloqueo') ? 'bloqueo' : 'normal';
    }
}
