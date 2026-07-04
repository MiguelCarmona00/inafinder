<?php

namespace App\Models;

// Rate-limiting del login persistido en BD por (identificador, IP): a diferencia
// del contador en $_SESSION, no se evade borrando la cookie de sesión.
class LoginIntentos extends DBAbstractModel
{
    public function get($id = '') {}
    public function set() {}
    public function edit() {}
    public function delete($id = '') {}

    // Segundos de bloqueo que le quedan a este identificador+IP (0 = no bloqueado)
    public function segundosBloqueado($identificador, $ip)
    {
        $this->query = "SELECT COALESCE(GREATEST(TIMESTAMPDIFF(SECOND, NOW(), bloqueado_hasta), 0), 0) AS restante
                        FROM login_intentos
                        WHERE identificador = :identificador AND ip = :ip";
        $this->parametros = [
            'identificador' => $identificador,
            'ip' => $ip,
        ];
        $this->get_results_from_query();
        return (int) ($this->rows[0]['restante'] ?? 0);
    }

    // Registra un fallo de login. Si el bloqueo anterior ya expiró, el contador
    // vuelve a empezar; al llegar a $maxIntentos se fija bloqueado_hasta.
    // Devuelve ['intentos' => int, 'restante' => segundos de bloqueo (0 = sin bloqueo)]
    public function registrarFallo($identificador, $ip, $maxIntentos, $tiempoBloqueo)
    {
        $pdo = $this->open_connection();

        $stmt = $pdo->prepare(
            "SELECT intentos, (bloqueado_hasta IS NOT NULL AND bloqueado_hasta <= NOW()) AS expirado
             FROM login_intentos
             WHERE identificador = :identificador AND ip = :ip"
        );
        $stmt->execute(['identificador' => $identificador, 'ip' => $ip]);
        $fila = $stmt->fetch(\PDO::FETCH_ASSOC);

        $intentos = $fila ? ($fila['expirado'] ? 1 : (int) $fila['intentos'] + 1) : 1;
        $bloqueado = $intentos >= $maxIntentos;

        if ($fila) {
            $stmt = $pdo->prepare(
                "UPDATE login_intentos
                 SET intentos = :intentos,
                     bloqueado_hasta = IF(:bloqueado, DATE_ADD(NOW(), INTERVAL :tiempo_bloqueo SECOND), NULL)
                 WHERE identificador = :identificador AND ip = :ip"
            );
        } else {
            $stmt = $pdo->prepare(
                "INSERT INTO login_intentos (identificador, ip, intentos, bloqueado_hasta)
                 VALUES (:identificador, :ip, :intentos, IF(:bloqueado, DATE_ADD(NOW(), INTERVAL :tiempo_bloqueo SECOND), NULL))"
            );
        }
        $stmt->execute([
            'identificador' => $identificador,
            'ip' => $ip,
            'intentos' => $intentos,
            'bloqueado' => (int) $bloqueado,
            'tiempo_bloqueo' => $tiempoBloqueo,
        ]);

        return [
            'intentos' => $intentos,
            'restante' => $bloqueado ? $tiempoBloqueo : 0,
        ];
    }

    // Login correcto: se limpia el contador de este identificador+IP
    public function resetear($identificador, $ip)
    {
        $this->query = "DELETE FROM login_intentos WHERE identificador = :identificador AND ip = :ip";
        $this->parametros = [
            'identificador' => $identificador,
            'ip' => $ip,
        ];
        $this->get_results_from_query();
    }
}
