<?php

namespace App\Models;

class Fichajes extends DBAbstractModel
{
    // Atributos de la clase
    private $id_fichaje;
    private $id_equipo;
    private $id_jugador;
    private $fecha_compra;

    //setters

    public function setIdEquipo($id_equipo)
    {
        $this->id_equipo = $id_equipo;
    }

    public function setIdJugador($id_jugador)
    {
        $this->id_jugador = $id_jugador;
    }

    public function setFechaCompra($fecha_compra)
    {
        $this->fecha_compra = $fecha_compra;
    }


    // Obtener todos los usuarios
    public function getAll()
    {
        $this->query = "SELECT * FROM fichajes";
        $this->get_results_from_query();
        return $this->rows;
    }

    // Para obtener un usuario por id
    public function get($id = '')
    {
    }

    public function getByTeam($id_equipo = ''){
        $this->query = "SELECT * FROM fichajes WHERE id_equipo = :id_equipo";
        $this->parametros['id_equipo'] = $id_equipo;
        $this->get_results_from_query();
        return $this->rows;
    }

    // REALIZAR UNA VENTA
    public function set(){
        $this->query = "INSERT INTO fichajes (id_equipo, id_jugador, fecha_compra) VALUES (:id_equipo, :id_jugador, :fecha_compra)";
        $this->parametros['id_equipo'] = $this->id_equipo;
        $this->parametros['id_jugador'] = $this->id_jugador;
        $this->parametros['fecha_compra'] = date('Y-m-d H:i:s');
        $this->get_results_from_query();
    }


    public function edit(){}


    // Eliminar fichaje por id_equipo e id_jugador
    public function delete($id_equipo = '', $id_jugador = '')
    {
        $this->query = "DELETE FROM fichajes WHERE id_equipo = :id_equipo AND id_jugador = :id_jugador";
        $this->parametros = [
            'id_equipo' => $id_equipo,
            'id_jugador' => $id_jugador
        ];
        $this->get_results_from_query();
        $this->mensaje = 'Fichaje eliminado correctamente';
    }

    // Método para obtener un fichaje específico
    public function getFichaje($id_equipo, $id_jugador)
    {
        $this->query = "SELECT * FROM fichajes WHERE id_equipo = :id_equipo AND id_jugador = :id_jugador";
        $this->parametros = [
            'id_equipo' => $id_equipo,
            'id_jugador' => $id_jugador
        ];
        $this->get_results_from_query();
        return $this->rows[0] ?? null;
    }

    // Compra de un jugador en una sola transacción: el saldo se descuenta con un
    // UPDATE condicionado (fuente de verdad: la BD, nunca la sesión) y la clave
    // única de fichajes.id_jugador impide compras dobles en peticiones simultáneas.
    // Devuelve ['success' => bool, 'error' => ?string, 'jugador' => ?string]
    public function comprar($id_equipo, $id_jugador)
    {
        $pdo = $this->open_connection();
        try {
            $pdo->beginTransaction();

            $stmt = $pdo->prepare("SELECT nombre, precio_cantidad, precio_tipo FROM jugadores WHERE id_jugador = :id_jugador");
            $stmt->execute(['id_jugador' => $id_jugador]);
            $jugador = $stmt->fetch(\PDO::FETCH_ASSOC);
            if (!$jugador) {
                $pdo->rollBack();
                return ['success' => false, 'error' => 'not_found'];
            }

            $stmt = $pdo->prepare("UPDATE monedas_equipo
                                   SET cantidad = cantidad - :precio
                                   WHERE id_equipo = :id_equipo AND tipo_moneda = :tipo_moneda AND cantidad >= :precio");
            $stmt->execute([
                'precio' => $jugador['precio_cantidad'],
                'id_equipo' => $id_equipo,
                'tipo_moneda' => $jugador['precio_tipo'],
            ]);
            if ($stmt->rowCount() === 0) {
                $pdo->rollBack();
                return ['success' => false, 'error' => 'insufficient_funds', 'jugador' => $jugador['nombre']];
            }

            $stmt = $pdo->prepare("INSERT INTO fichajes (id_equipo, id_jugador) VALUES (:id_equipo, :id_jugador)");
            $stmt->execute([
                'id_equipo' => $id_equipo,
                'id_jugador' => $id_jugador,
            ]);

            $pdo->commit();
            return ['success' => true, 'jugador' => $jugador['nombre']];
        } catch (\PDOException $e) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            // 1062 = clave duplicada: otro equipo fichó al jugador antes
            if (($e->errorInfo[1] ?? null) == 1062) {
                return ['success' => false, 'error' => 'already_signed'];
            }
            error_log('Error en fichaje: ' . $e->getMessage());
            return ['success' => false, 'error' => 'unknown'];
        }
    }

    // Reembolso en una sola transacción: el DELETE condicionado garantiza que el
    // fichaje pertenece al equipo y que no puede reembolsarse dos veces.
    // Devuelve ['success' => bool, 'error' => ?string, 'jugador', 'cantidad', 'tipo_moneda']
    public function devolver($id_equipo, $id_jugador)
    {
        $pdo = $this->open_connection();
        try {
            $pdo->beginTransaction();

            $stmt = $pdo->prepare("SELECT nombre, precio_cantidad, precio_tipo FROM jugadores WHERE id_jugador = :id_jugador");
            $stmt->execute(['id_jugador' => $id_jugador]);
            $jugador = $stmt->fetch(\PDO::FETCH_ASSOC);
            if (!$jugador) {
                $pdo->rollBack();
                return ['success' => false, 'error' => 'not_found'];
            }

            $stmt = $pdo->prepare("DELETE FROM fichajes WHERE id_equipo = :id_equipo AND id_jugador = :id_jugador");
            $stmt->execute([
                'id_equipo' => $id_equipo,
                'id_jugador' => $id_jugador,
            ]);
            if ($stmt->rowCount() === 0) {
                $pdo->rollBack();
                return ['success' => false, 'error' => 'not_owned'];
            }

            $stmt = $pdo->prepare("INSERT INTO monedas_equipo (id_equipo, tipo_moneda, cantidad)
                                   VALUES (:id_equipo, :tipo_moneda, :cantidad)
                                   ON DUPLICATE KEY UPDATE cantidad = cantidad + VALUES(cantidad)");
            $stmt->execute([
                'id_equipo' => $id_equipo,
                'tipo_moneda' => $jugador['precio_tipo'],
                'cantidad' => $jugador['precio_cantidad'],
            ]);

            $pdo->commit();
            return [
                'success' => true,
                'jugador' => $jugador['nombre'],
                'cantidad' => $jugador['precio_cantidad'],
                'tipo_moneda' => $jugador['precio_tipo'],
            ];
        } catch (\PDOException $e) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            error_log('Error en reembolso: ' . $e->getMessage());
            return ['success' => false, 'error' => 'unknown'];
        }
    }



}
