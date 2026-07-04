<?php

namespace App\Models;

class Tecnicas extends DBAbstractModel
{
    // Atributos de la clase (se asignan con fill())
    protected $id_supertecnica;
    protected $nombre;
    protected $elemento;
    protected $tipo_principal;
    protected $subtipo;
    protected $potencia;
    protected $aturdimiento;
    protected $costo;
    protected $imagen;
    protected $dificultad;
    protected $riesgo;
    protected $n_jugadores;
    protected $eg_damage;

    // Obtener todos los usuarios
    public function getAll()
    {
        $this->query = "SELECT * FROM supertecnicas";
        $this->get_results_from_query();
        return $this->rows;
    }

    public function get($id = '')
    {
        $this->query = "SELECT * FROM supertecnicas WHERE id_supertecnica = :id_supertecnica";
        $this->parametros = ['id_supertecnica' => $id];
        $this->get_results_from_query();
        if (count($this->rows) == 1) {
            foreach ($this->rows[0] as $propiedad => $valor) {
                $this->$propiedad = $valor;
            }
            $this->mensaje = 'supertecnica encontrada';
        } else {
            $this->mensaje = 'supertecnica no encontrada';
        }
        return $this->rows[0] ?? null;
    }



    public function set()
    {
        $this->query = "INSERT INTO supertecnicas (nombre, elemento, tipo_principal, subtipo, potencia, aturdimiento, costo, imagen, dificultad, riesgo, n_jugadores, eg_damage) VALUES (:nombre, :elemento, :tipo_principal, :subtipo, :potencia, :aturdimiento, :costo, :imagen, :dificultad, :riesgo, :n_jugadores, :eg_damage)";
        $this->parametros = [
            'nombre' => $this->nombre,
            'elemento' => $this->elemento,
            'tipo_principal' => $this->tipo_principal,
            'subtipo' => $this->subtipo,
            'potencia' => $this->potencia,
            'aturdimiento' => $this->aturdimiento,
            'costo' => $this->costo,
            'imagen' => $this->imagen,
            'dificultad' => $this->dificultad ?? 0,
            'riesgo' => $this->riesgo ?? 1,
            'n_jugadores' => $this->n_jugadores ?? 1,
            'eg_damage' => $this->eg_damage ?? 0
        ];
        $this->get_results_from_query();
        $this->mensaje = 'Técnica creada correctamente';
    }


    public function edit()
    {
        $this->query = "UPDATE supertecnicas SET nombre = :nombre, elemento = :elemento, tipo_principal = :tipo_principal, subtipo = :subtipo, potencia = :potencia, aturdimiento = :aturdimiento, costo = :costo, imagen = :imagen, dificultad = :dificultad, riesgo = :riesgo, n_jugadores = :n_jugadores, eg_damage = :eg_damage WHERE id_supertecnica = :id_supertecnica";
        $this->parametros = [
            'id_supertecnica' => $this->id_supertecnica,
            'nombre' => $this->nombre,
            'elemento' => $this->elemento,
            'tipo_principal' => $this->tipo_principal,
            'subtipo' => $this->subtipo,
            'potencia' => $this->potencia,
            'aturdimiento' => $this->aturdimiento,
            'costo' => $this->costo,
            'imagen' => $this->imagen,
            'dificultad' => $this->dificultad ?? 0,
            'riesgo' => $this->riesgo ?? 1,
            'n_jugadores' => $this->n_jugadores ?? 1,
            'eg_damage' => $this->eg_damage ?? 0
        ];
        $this->get_results_from_query();
        $this->mensaje = 'Técnica actualizada correctamente';
    }

    public function delete($id_supertecnica = '')
    {
        $this->query = "DELETE FROM supertecnicas WHERE id_supertecnica = :id_supertecnica";
        $this->parametros = ['id_supertecnica' => $id_supertecnica];
        $this->get_results_from_query();
        $this->mensaje = 'Técnica eliminada correctamente';
    }

    // Listado y búsqueda por tipo (tiro, parada, bloqueo, regate); sustituyen
    // a los pares getTiros/buscarTiros, getParadas/buscarParadas, etc.
    public function getPorTipo($tipo)
    {
        $this->query = "SELECT * FROM supertecnicas WHERE tipo_principal = :tipo ORDER BY nombre ASC";
        $this->parametros = ['tipo' => $tipo];
        $this->get_results_from_query();
        return $this->rows;
    }

    public function buscarPorTipo($tipo, $termino)
    {
        $this->query = "SELECT * FROM supertecnicas WHERE tipo_principal = :tipo AND (nombre LIKE :termino OR elemento LIKE :termino) ORDER BY nombre ASC";
        $this->parametros = ['tipo' => $tipo, 'termino' => '%' . $termino . '%'];
        $this->get_results_from_query();
        return $this->rows;
    }

    public function buscarTodas($termino)
    {
        $this->query = "SELECT * FROM supertecnicas WHERE nombre LIKE :termino ORDER BY nombre ASC";
        $this->parametros = ['termino' => '%' . $termino . '%'];
        $this->get_results_from_query();
        return $this->rows;
    }

    public function getAllTecnicas()
    {
        $this->query = "SELECT * FROM supertecnicas ORDER BY nombre ASC";
        $this->get_results_from_query();
        return $this->rows;
    }
}
