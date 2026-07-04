<?php

namespace App\Models;

class Monederos extends DBAbstractModel
{
    // Atributos de la clase
    private $id_equipo;
    private $tipo_moneda;
    private $cantidad;

    // Setters
    public function setIdEquipo($id_equipo)
    {
        $this->id_equipo = $id_equipo;
    }

    public function setTipoMoneda($tipo_moneda)
    {
        $this->tipo_moneda = $tipo_moneda;
    }

    public function setCantidad($cantidad)
    {
        $this->cantidad = $cantidad;
    }


    // Obtener todos los monederos
    public function getAll()
    {
        $this->query = "SELECT * FROM monedas_equipo";
        $this->get_results_from_query();
        return $this->rows;
    }

    // Para obtener un monedero por id de equipo
    public function get($id = '')
    {
        $this->query = "SELECT * FROM monedas_equipo WHERE id_equipo = :id_equipo";
        $this->parametros = ['id_equipo' => $id];
        $this->get_results_from_query();
        return $this->rows ?? null;
    }



    public function set(){
        $this->query = "INSERT INTO monedas_equipo (id_equipo, tipo_moneda, cantidad) 
                        VALUES (:id_equipo, :tipo_moneda, :cantidad)";
        $this->parametros = [
            'id_equipo' => $this->id_equipo,
            'tipo_moneda' => $this->tipo_moneda,
            'cantidad' => $this->cantidad
        ];
        $this->get_results_from_query();
        return $this->rows ?? null;
    }


    public function edit($id_equipo = '', $tipo_moneda = '', $nueva_cantidad = ''){
        $this->query = "UPDATE monedas_equipo SET cantidad = :cantidad WHERE id_equipo = :id_equipo AND tipo_moneda = :tipo_moneda";
        $this->parametros = [
            'id_equipo' => $id_equipo,
            'tipo_moneda' => $tipo_moneda,
            'cantidad' => $nueva_cantidad
        ];
        $this->get_results_from_query();
        return $this->rows ?? null;
    }


    // Eliminar monedero
    public function delete($id_equipo = ''){}





}
