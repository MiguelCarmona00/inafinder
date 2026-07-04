<?php

namespace App\Models;

class JugadoresTalentos extends DBAbstractModel
{
    // Atributos de la clase (se asignan con fill())
    protected $id_jugador_talento;
    protected $id_jugador;
    protected $id_talento;
    protected $nivel;

    public function getAll()
    {
        $this->query = "SELECT * FROM jugadores_talentos";
        $this->get_results_from_query();
        return $this->rows;
    }

    public function get($id = '')
    {
        $this->query = "SELECT * FROM jugadores_talentos WHERE id_jugador_talento = :id_jugador_talento";
        $this->parametros = ['id_jugador_talento' => $id];
        $this->get_results_from_query();
        return $this->rows[0] ?? null;
    }

    public function set()
    {
        $this->query = "INSERT INTO jugadores_talentos (id_jugador, id_talento, nivel) VALUES (:id_jugador, :id_talento, :nivel)";
        $this->parametros = [
            'id_jugador' => $this->id_jugador,
            'id_talento' => $this->id_talento,
            'nivel' => $this->nivel
        ];
        $this->get_results_from_query();
        $this->mensaje = 'Talento del jugador creado correctamente';
    }

    public function edit()
    {
        $this->query = "UPDATE jugadores_talentos SET id_jugador = :id_jugador, id_talento = :id_talento, nivel = :nivel WHERE id_jugador_talento = :id_jugador_talento";
        $this->parametros = [
            'id_jugador_talento' => $this->id_jugador_talento,
            'id_jugador' => $this->id_jugador,
            'id_talento' => $this->id_talento,
            'nivel' => $this->nivel
        ];
        $this->get_results_from_query();
        $this->mensaje = 'Talento del jugador actualizado correctamente';
    }

    public function delete($id_jugador_talento = '')
    {
        $this->query = "DELETE FROM jugadores_talentos WHERE id_jugador_talento = :id_jugador_talento";
        $this->parametros = ['id_jugador_talento' => $id_jugador_talento];
        $this->get_results_from_query();
        $this->mensaje = 'Talento del jugador eliminado correctamente';
    }

    public function getTalentosPorJugador($id_jugador)
    {
        $this->query = "SELECT jt.*, t.nombre as nombre_talento FROM jugadores_talentos jt 
                       INNER JOIN talentos t ON jt.id_talento = t.id_talento 
                       WHERE jt.id_jugador = :id_jugador";
        $this->parametros = ['id_jugador' => $id_jugador];
        $this->get_results_from_query();
        return $this->rows;
    }
}

?>