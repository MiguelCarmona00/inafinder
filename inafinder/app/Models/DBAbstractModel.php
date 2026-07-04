<?php
namespace App\Models;
abstract class DBAbstractModel
{
    private static $db_host = DBHOST;
    private static $db_user = DBUSER;
    private static $db_pass = DBPASS;
    private static $db_name = DBNAME;
    private static $db_port = DBPORT;
    protected $mensaje = '';
    protected $conn;
    protected $query;
    protected $parametros = array();
    protected $rows = array();

    abstract protected function get();
    abstract protected function set();
    abstract protected function edit();
    abstract protected function delete();

    // Una instancia por clase de modelo (sustituye al getInstancia() que
    // repetía cada modelo)
    private static $instancias = [];

    public static function getInstancia()
    {
        $clase = static::class;
        if (!isset(self::$instancias[$clase])) {
            self::$instancias[$clase] = new $clase();
        }
        return self::$instancias[$clase];
    }

    public function __clone()
    {
        trigger_error('La clonación no es permitida!.', E_USER_ERROR);
    }

    // Asigna de golpe los atributos del modelo desde un array campo => valor
    // (sustituye a las decenas de setters manuales de cada modelo); las claves
    // que no correspondan a un atributo declarado se ignoran
    public function fill(array $datos)
    {
        foreach ($datos as $campo => $valor) {
            if (property_exists($this, $campo)) {
                $this->$campo = $valor;
            }
        }
        return $this;
    }

    // Conexión única compartida por todos los modelos durante la petición;
    // necesaria para que las transacciones abarquen consultas de varios modelos
    private static $shared_conn = null;

    protected function open_connection()
    {
        if (self::$shared_conn === null) {
            $dsn = 'mysql:host=' . self::$db_host . ';' . 'dbname=' . self::$db_name . ';' . 'port=' . self::$db_port . ';charset=utf8mb4';
            try {
                self::$shared_conn = new \PDO($dsn, self::$db_user, self::$db_pass, array(
                    \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                ));
            } catch (\PDOException $e) {
                error_log('Conexión fallida: ' . $e->getMessage());
                http_response_code(500);
                exit('Error de conexión con la base de datos');
            }
        }
        $this->conn = self::$shared_conn;
        return $this->conn;
    }

    public function lastInsert()
    {
        return $this->conn->lastInsertId();
    }

    // Los errores de consulta (PDOException) suben hasta el manejador global de
    // index.php, que los loguea y muestra una página de error genérica: nunca
    // se imprimen detalles internos al usuario.
    protected function get_results_from_query()
    {
        $this->open_connection();
        $_stmt = $this->conn->prepare($this->query);
        if (preg_match_all('/(:\w+)/', $this->query, $_named, PREG_PATTERN_ORDER)) {
            $_named = array_pop($_named);
            foreach ($_named as $_param) {
                $_stmt->bindValue($_param, $this->parametros[substr($_param, 1)]);
            }
        }
        $_stmt->execute();
        $this->rows = $_stmt->fetchAll(\PDO::FETCH_ASSOC);
        $_stmt->closeCursor();
    }
}
?>
