<?php

namespace App\Controllers;

class RegatesController extends TecnicaCrudController
{
    protected $tipo = 'regate';
    protected $plural = 'regates';
    protected $singular = 'regate';
    protected $fijos = ['subtipo' => 'normal', 'aturdimiento' => 0];

    protected function subtipoEdicion(array $d, array $actual)
    {
        return 'normal';
    }
}
