<?php

namespace App\Models;

class Talentos extends DBAbstractModel
{
    // Atributos de la clase (se asignan con fill())
    protected $id_talento;
    protected $nombre;
    protected $descripcion;
    protected $tipo;
    protected $afecta;

    public function buscar($termino)
    {
        $this->query = "SELECT * FROM talentos WHERE nombre LIKE :termino OR descripcion LIKE :termino ORDER BY nombre ASC";
        $this->parametros = ['termino' => '%' . $termino . '%'];
        $this->get_results_from_query();
        return $this->rows;
    }

    public function getAll()
    {
        $this->query = "SELECT * FROM talentos ORDER BY nombre ASC";
        $this->get_results_from_query();
        return $this->rows;
    }

    public function get($id = '')
    {
        $this->query = "SELECT * FROM talentos WHERE id_talento = :id_talento";
        $this->parametros = ['id_talento' => $id];
        $this->get_results_from_query();
        return $this->rows[0] ?? null;
    }

    public function set()
    {
        $this->query = "INSERT INTO talentos (nombre, descripcion, tipo, afecta) VALUES (:nombre, :descripcion, :tipo, :afecta)";
        $this->parametros = [
            'nombre' => $this->nombre,
            'descripcion' => $this->descripcion,
            'tipo' => $this->tipo,
            'afecta' => $this->afecta
        ];
        $this->get_results_from_query();
        $this->mensaje = 'Talento creado correctamente';
    }

    public function edit($id_talento = '') {
        $this->query = "UPDATE talentos SET nombre = :nombre, descripcion = :descripcion, tipo = :tipo, afecta = :afecta WHERE id_talento = :id_talento";
        $this->parametros = [
            'id_talento' => $id_talento,
            'nombre' => $this->nombre,
            'descripcion' => $this->descripcion,
            'tipo' => $this->tipo,
            'afecta' => $this->afecta
        ];
        $this->get_results_from_query();
        $this->mensaje = 'Talento actualizado correctamente';
        return $this->rows;
    }

    public function delete($id_talento = '') {
        $this->query = "DELETE FROM talentos WHERE id_talento = :id_talento";
        $this->parametros = ['id_talento' => $id_talento];
        $this->get_results_from_query();
        $this->mensaje = 'Talento eliminado correctamente';
        return $this->rows;
    }

    public function buscarParaAutocompletar($termino)
    {
        $this->query = "SELECT id_talento as id, nombre, 'talento' as tipo FROM talentos WHERE nombre LIKE :termino ORDER BY nombre ASC LIMIT 10";
        $this->parametros = ['termino' => '%' . $termino . '%'];
        $this->get_results_from_query();
        return $this->rows;
    }
}

?>