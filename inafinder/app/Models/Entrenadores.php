<?php

namespace App\Models;

class Entrenadores extends DBAbstractModel
{
    // Atributos de la clase (se asignan con fill())
    protected $id_ent;
    protected $nombre;
    protected $imagen;

    // Obtener todos los usuarios
    public function getAll()
    {
        $this->query = "SELECT * FROM entrenadores";
        $this->get_results_from_query();
        return $this->rows;
    }

    public function get($id = '')
    {
        $this->query = "SELECT * FROM entrenadores WHERE id_ent = :id";
        $this->parametros = ['id' => $id];
        $this->get_results_from_query();
        if (count($this->rows) == 1) {
            foreach ($this->rows[0] as $propiedad => $valor) {
                $this->$propiedad = $valor;
            }
            $this->mensaje = 'entrenador encontrada';
        } else {
            $this->mensaje = 'entrenador no encontrada';
        }
        return $this->rows[0] ?? null;
    }



    public function set()
    {
        $this->query = "INSERT INTO entrenadores (nombre, imagen) VALUES (:nombre, :imagen)";
        $this->parametros = [
            'nombre' => $this->nombre,
            'imagen' => $this->imagen
        ];
        $this->get_results_from_query();
        $this->mensaje = 'Entrenador creada correctamente';
    }


    public function edit()
    {
        $this->query = "UPDATE entrenadores SET nombre = :nombre, imagen = :imagen WHERE id_ent = :id_ent";
        $this->parametros = [
            'id_ent' => $this->id_ent,
            'nombre' => $this->nombre,
            'imagen' => $this->imagen
        ];
        $this->get_results_from_query();
        $this->mensaje = 'Entrenador actualizada correctamente';
    }

    public function delete($id_entrenador = '')
    {
        $this->query = "DELETE FROM entrenadores WHERE id_ent = :id_ent";
        $this->parametros = ['id_ent' => $id_entrenador];
        $this->get_results_from_query();
        $this->mensaje = 'Entrenador eliminado correctamente';
    }

    public function buscarEntrenadores($termino)
    {
        $this->query = "SELECT * FROM entrenadores WHERE nombre LIKE :termino ORDER BY nombre ASC";
        $this->parametros = ['termino' => '%' . $termino . '%'];
        $this->get_results_from_query();
        return $this->rows;
    }
}
