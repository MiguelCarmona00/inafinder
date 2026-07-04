<?php

namespace App\Controllers;

class ParadasController extends TecnicaCrudController
{
    protected $tipo = 'parada';
    protected $plural = 'paradas';
    protected $singular = 'parada';
    protected $fijos = ['riesgo' => 0, 'eg_damage' => 0];
    protected $defaultsNuevo = ['n_jugadores' => 0];

    // Checkbox del formulario: marcado = despeje, sin marcar = normal
    protected function subtipoEdicion(array $d, array $actual)
    {
        return (($d['subtipo'] ?? '') === 'despeje') ? 'despeje' : 'normal';
    }
}
