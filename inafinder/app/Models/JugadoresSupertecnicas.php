<?php

namespace App\Models;

class JugadoresSupertecnicas extends DBAbstractModel
{
    // Atributos de la clase (se asignan con fill())
    protected $id_jugador_supertecnica;
    protected $id_jugador;
    protected $id_supertecnica;
    protected $nivel;

    public function getAll()
    {
        $this->query = "SELECT * FROM jugadores_supertecnicas";
        $this->get_results_from_query();
        return $this->rows;
    }

    public function get($id = '')
    {
        $this->query = "SELECT * FROM jugadores_supertecnicas WHERE id_jugador_supertecnica = :id_jugador_supertecnica";
        $this->parametros = ['id_jugador_supertecnica' => $id];
        $this->get_results_from_query();
        return $this->rows[0] ?? null;
    }

    public function set()
    {
        $this->query = "INSERT INTO jugadores_supertecnicas (id_jugador, id_supertecnica, nivel) VALUES (:id_jugador, :id_supertecnica, :nivel)";
        $this->parametros = [
            'id_jugador' => $this->id_jugador,
            'id_supertecnica' => $this->id_supertecnica,
            'nivel' => $this->nivel
        ];
        $this->get_results_from_query();
        $this->mensaje = 'Técnica del jugador creada correctamente';
    }

    public function edit()
    {
        $this->query = "UPDATE jugadores_supertecnicas SET id_jugador = :id_jugador, id_supertecnica = :id_supertecnica, nivel = :nivel WHERE id_jugador_supertecnica = :id_jugador_supertecnica";
        $this->parametros = [
            'id_jugador_supertecnica' => $this->id_jugador_supertecnica,
            'id_jugador' => $this->id_jugador,
            'id_supertecnica' => $this->id_supertecnica,
            'nivel' => $this->nivel
        ];
        $this->get_results_from_query();
        $this->mensaje = 'Técnica del jugador actualizada correctamente';
    }

    public function delete($id_jugador_supertecnica = '')
    {
        $this->query = "DELETE FROM jugadores_supertecnicas WHERE id_jugador_supertecnica = :id_jugador_supertecnica";
        $this->parametros = ['id_jugador_supertecnica' => $id_jugador_supertecnica];
        $this->get_results_from_query();
        $this->mensaje = 'Técnica del jugador eliminada correctamente';
    }

    public function getTecnicasPorJugador($id_jugador)
    {
        $this->query = "SELECT js.*, s.nombre as nombre_tecnica FROM jugadores_supertecnicas js 
                    INNER JOIN supertecnicas s ON js.id_supertecnica = s.id_supertecnica 
                    WHERE js.id_jugador = :id_jugador 
                    ORDER BY js.nivel ASC, s.nombre ASC";
        $this->parametros = ['id_jugador' => $id_jugador];
        $this->get_results_from_query();
        return $this->rows;
    }
}

?>