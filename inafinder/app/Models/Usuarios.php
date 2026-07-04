<?php

namespace App\Models;

class Usuarios extends DBAbstractModel
{
    // Atributos de la clase
    private $id_usuario;
    private $nombre;
    private $apellidos;
    private $email;
    private $password;
    private $rol;
    
    private $usuario;

    private $nombre_equipo;

    //setters

    public function setNombre($nombre)
    {
        $this->nombre = $nombre;
    }

    public function setApellidos($apellidos)
    {
        $this->apellidos = $apellidos;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function setPassword($password)
    {
        $this->password = $password;
    }

    public function setRol($rol)
    {
        $this->rol = $rol;
    }

    public function setUsuario($usuario)
    {
        $this->usuario = $usuario;
    }

    public function setNombreEquipo($nombre_equipo)
    {
        $this->nombre_equipo = $nombre_equipo;
    }


    // Obtener todos los usuarios
    public function getAll()
    {
        $this->query = "SELECT * FROM usuarios";
        $this->get_results_from_query();
        return $this->rows;
    }

    // Para obtener un usuario por id
    public function get($id = '')
    {
        $this->query = "SELECT * FROM usuarios WHERE id_usuario = :id_usuario";
        $this->parametros = ['id_usuario' => $id];
        $this->get_results_from_query();
        if (count($this->rows) == 1) {
            foreach ($this->rows[0] as $propiedad => $valor) {
                $this->$propiedad = $valor;
            }
            $this->mensaje = 'Usuario encontrade';
        } else {
            $this->mensaje = 'Usuario no encontrado';
        }
        return $this->rows[0] ?? null;
    }



    public function set(){}


    public function edit(){}


    // Eliminar usuario
    public function delete($id_usuario = ''){}

    // Iniciar sesión: recupera el usuario y verifica la contraseña con password_verify
    public function login($emailOrUsername = '', $password = '')
    {
        $this->query = "SELECT * FROM usuarios WHERE email = :emailOrUsername OR usuario = :emailOrUsername";
        $this->parametros = ['emailOrUsername' => $emailOrUsername];
        $this->get_results_from_query();

        foreach ($this->rows as $usuario) {
            $almacenada = (string) $usuario['password'];

            if (password_verify($password, $almacenada)) {
                if (password_needs_rehash($almacenada, PASSWORD_DEFAULT)) {
                    $this->actualizarPassword($usuario['id_usuario'], password_hash($password, PASSWORD_DEFAULT));
                }
                return [$usuario];
            }

            // Compatibilidad con contraseñas antiguas guardadas en texto plano:
            // si coincide, se migra a hash en este mismo login
            $infoHash = password_get_info($almacenada);
            if (empty($infoHash['algo']) && $almacenada !== '' && hash_equals($almacenada, $password)) {
                $this->actualizarPassword($usuario['id_usuario'], password_hash($password, PASSWORD_DEFAULT));
                return [$usuario];
            }
        }

        return [];
    }

    public function actualizarPassword($id_usuario, $hash)
    {
        $this->query = "UPDATE usuarios SET password = :password WHERE id_usuario = :id_usuario";
        $this->parametros = [
            'password' => $hash,
            'id_usuario' => $id_usuario
        ];
        $this->get_results_from_query();
    }





}
