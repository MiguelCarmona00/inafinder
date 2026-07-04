<?php

namespace App\Models;

class Equipos extends DBAbstractModel
{
    // Atributos de la clase
    private $id_equipo;
    private $nombre_equipo;
    private $escudo;
    private $id_usuario;

    //setters

    public function setIdEquipo($id_equipo)
    {
        $this->id_equipo = $id_equipo;
    }

    public function setNombreEquipo($nombre_equipo)
    {
        $this->nombre_equipo = $nombre_equipo;
    }

    public function setEscudo($escudo)
    {
        $this->escudo = $escudo;
    }

    public function setIdUsuario($id_usuario)
    {
        $this->id_usuario = $id_usuario;
    }

    // Obtener todos los usuarios
    public function getAll()
    {
        $this->query = "SELECT * FROM fichajes";
        $this->get_results_from_query();
        return $this->rows;
    }

    // Para obtener un equipo por id
    public function get($id = '')
    {
        $this->query = "SELECT * FROM equipos WHERE id_equipo = :id_equipo";
        $this->parametros['id_equipo'] = $id;
        $this->get_results_from_query();
        return $this->rows;
    }

    // Obtener equipos por id de usuario
    public function getByUser($id_usuario = ''){
        $this->query = "SELECT * FROM equipos WHERE id_usuario = :id_usuario";
        $this->parametros['id_usuario'] = $id_usuario;
        $this->get_results_from_query();
        return $this->rows;
    }

    // NUEVO EQUIPO
    public function set(){
        $this->query = "INSERT INTO equipos (nombre_equipo, escudo, id_usuario) VALUES (:nombre_equipo, :escudo, :id_usuario)";
        $this->parametros['nombre_equipo'] = $this->nombre_equipo;
        $this->parametros['escudo'] = $this->escudo;
        $this->parametros['id_usuario'] = $this->id_usuario;
        $this->get_results_from_query();
    }

    public function edit()
    {
        $this->query = "UPDATE equipos SET nombre_equipo = :nombre_equipo, escudo = :escudo WHERE id_equipo = :id_equipo";
        $this->parametros['nombre_equipo'] = $this->nombre_equipo;
        $this->parametros['escudo'] = $this->escudo;
        $this->parametros['id_equipo'] = $this->id_equipo;
        $this->get_results_from_query();
        return true; // Retorna true si la actualización fue exitosa
    }


    // Eliminar usuario
    public function delete(){}



}
