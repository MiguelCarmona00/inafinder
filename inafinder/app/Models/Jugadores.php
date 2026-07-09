<?php

namespace App\Models;

class Jugadores extends DBAbstractModel
{
    // Atributos de la clase (se asignan con fill())
    protected $id_jugador;
    protected $imagen;
    protected $nombre;
    protected $posicion;
    protected $elemento;
    protected $precio_cantidad;
    protected $precio_tipo;
    protected $edad;
    protected $genero;
    protected $PE;
    protected $PT;
    protected $tiro;
    protected $regate;
    protected $defensa;
    protected $control;
    protected $tecnica;
    protected $rapidez;
    protected $aguante;
    protected $suerte;
    protected $libertad;

    public function getAll()
    {
        $this->query = "SELECT * FROM jugadores";
        $this->get_results_from_query();
        return $this->rows;
    }

    public function get($id = '')
    {
        $this->query = "SELECT * FROM jugadores WHERE id_jugador = :id_jugador";
        $this->parametros['id_jugador'] = $id;
        $this->get_results_from_query();
        return $this->rows;
    }
    
    // Para obtener jugadores que no están fichados (no aparecen en fichajes)
    public function getNoFichados()
    {
        $this->query = "SELECT j.* FROM jugadores j
                       LEFT JOIN fichajes v ON j.id_jugador = v.id_jugador
                       WHERE v.id_jugador IS NULL ORDER BY j.id_jugador ASC";
        $this->get_results_from_query();
        return $this->rows;
    }

    // Para obtener jugadores que están fichados (aparecen en fichajes)
    public function getFichados()
    {
        $this->query = "SELECT j.* FROM jugadores j
                          INNER JOIN fichajes v ON j.id_jugador = v.id_jugador
                          ORDER BY j.id_jugador ASC";
        $this->get_results_from_query();
        return $this->rows;
    }

    // Para comprobar si un jugador está fichado
    public function isFichado($id_jugador) 
    {
        $this->query = "SELECT COUNT(*) as count FROM fichajes WHERE id_jugador = :id_jugador";
        $this->parametros['id_jugador'] = $id_jugador;
        $this->get_results_from_query();
        return $this->rows[0]['count'] > 0;
    }


    public function set(){
        $this->query = "INSERT INTO jugadores (imagen, nombre, posicion, elemento, precio_cantidad, precio_tipo, edad, genero, PE, PT, tiro, regate, defensa, control, tecnica, rapidez, aguante, suerte, libertad)
                        VALUES (:imagen, :nombre, :posicion, :elemento, :precio_cantidad, :precio_tipo, :edad, :genero, :PE, :PT, :tiro, :regate, :defensa, :control, :tecnica, :rapidez, :aguante, :suerte, :libertad)";
        $this->parametros = [
            'imagen' => $this->imagen,
            'nombre' => $this->nombre,
            'posicion' => $this->posicion,
            'elemento' => $this->elemento,
            'precio_cantidad' => $this->precio_cantidad,
            'precio_tipo' => $this->precio_tipo,
            'edad' => $this->edad,
            'genero' => $this->genero,
            'PE' => $this->PE,
            'PT' => $this->PT,
            'tiro' => $this->tiro,
            'regate' => $this->regate,
            'defensa' => $this->defensa,
            'control' => $this->control,
            'tecnica' => $this->tecnica,
            'rapidez' => $this->rapidez,
            'aguante' => $this->aguante,
            'suerte' => $this->suerte,
            'libertad' => $this->libertad
        ];
        $this->get_results_from_query();
        return $this->rows;
    }


     public function edit()
    {
        $this->query = "UPDATE jugadores SET imagen = :imagen, nombre = :nombre, posicion = :posicion, elemento = :elemento, precio_cantidad = :precio_cantidad, precio_tipo = :precio_tipo, edad = :edad, genero = :genero, PE = :PE, PT = :PT, tiro = :tiro, regate = :regate, defensa = :defensa, control = :control, tecnica = :tecnica, rapidez = :rapidez, aguante = :aguante, suerte = :suerte, libertad = :libertad WHERE id_jugador = :id_jugador";
        $this->parametros = [
            'id_jugador' => $this->id_jugador,
            'imagen' => $this->imagen,
            'nombre' => $this->nombre,
            'posicion' => $this->posicion,
            'elemento' => $this->elemento,
            'precio_cantidad' => $this->precio_cantidad,
            'precio_tipo' => $this->precio_tipo,
            'edad' => $this->edad,
            'genero' => $this->genero,
            'PE' => $this->PE,
            'PT' => $this->PT,
            'tiro' => $this->tiro,
            'regate' => $this->regate,
            'defensa' => $this->defensa,
            'control' => $this->control,
            'tecnica' => $this->tecnica,
            'rapidez' => $this->rapidez,
            'aguante' => $this->aguante,
            'suerte' => $this->suerte,
            'libertad' => $this->libertad
        ];
        $this->get_results_from_query();
        $this->mensaje = 'Jugador actualizado correctamente';
    }

    // Eliminar usuario
    public function delete($id_jugador = '')
    {
        $this->query = "DELETE FROM jugadores WHERE id_jugador = :id_jugador";
        $this->parametros = ['id_jugador' => $id_jugador];
        $this->get_results_from_query();
        $this->mensaje = 'Jugador eliminado correctamente';
    }

    public function getJugadorCompleto($id_jugador)
    {
        $this->query = "SELECT * FROM jugadores WHERE id_jugador = :id_jugador";
        $this->parametros = ['id_jugador' => $id_jugador];
        $this->get_results_from_query();
        return $this->rows[0] ?? null;
    }


    public function getJugadores()
    {
        $this->query = "SELECT * FROM jugadores ORDER BY nombre ASC";
        $this->get_results_from_query();
        return $this->rows;
    }

    public function buscarJugadores($termino)
    {
        $this->query = "SELECT * FROM jugadores WHERE nombre LIKE :termino ORDER BY nombre ASC";
        $this->parametros = ['termino' => '%' . $termino . '%'];
        $this->get_results_from_query();
        return $this->rows;
    }

    public function getJugadoresConTecnicas($limit = null, $offset = 0)
    {

        // Coalesce: Asegurarse de que las columnas de técnicas y talentos no sean nulas
        // Esto es importante para evitar errores al concatenar cadenas
        // Asegurarse de que las columnas de técnicas y talentos no sean nulas
        // Utilizar COALESCE para proporcionar un valor por defecto
        // Trae todos los datos del jugador j* más dos campos adicionales: tecnicas y talentos, generados por subconsultas

        // PRIMER LEFT JOIN: Agrupa las técnicas de los jugadores
        // Utiliza GROUP_CONCAT para concatenar las técnicas de cada jugador en una sola cadena
        
        // SEGUNDO LEFT JOIN: Agrupa los talentos de los jugadores
        // Utiliza GROUP_CONCAT para concatenar los talentos de cada jugador en una sola cadena

        $limitClause = '';
        if ($limit !== null && $limit > 0) {
            $limitClause = " LIMIT " . intval($limit);
            if ($offset > 0) {
                $limitClause .= " OFFSET " . intval($offset);
            }
        }

        $this->query = "
            SELECT 
                j.*,
                COALESCE(tecnicas_data.tecnicas, '') as tecnicas,
                COALESCE(talentos_data.talentos, '') as talentos
            FROM jugadores j
            LEFT JOIN (
                SELECT 
                    js.id_jugador,
                    GROUP_CONCAT(
                        CONCAT('tecnica:', s.nombre, ':', js.nivel, ':', js.id_supertecnica)
                        ORDER BY js.nivel ASC 
                        SEPARATOR '|'
                    ) as tecnicas
                FROM jugadores_supertecnicas js
                INNER JOIN supertecnicas s ON js.id_supertecnica = s.id_supertecnica
                GROUP BY js.id_jugador
            ) tecnicas_data ON j.id_jugador = tecnicas_data.id_jugador
            LEFT JOIN (
                SELECT 
                    jt.id_jugador,
                    GROUP_CONCAT(
                        CONCAT('talento:', t.nombre, ':', jt.nivel, ':', jt.id_talento)
                        ORDER BY jt.nivel ASC 
                        SEPARATOR '|'
                    ) as talentos
                FROM jugadores_talentos jt
                INNER JOIN talentos t ON jt.id_talento = t.id_talento
                GROUP BY jt.id_jugador
            ) talentos_data ON j.id_jugador = talentos_data.id_jugador
            ORDER BY j.nombre ASC" . $limitClause;
        $this->get_results_from_query();
        return $this->rows;
    }

    public function getTotalJugadores()
    {
        $this->query = "SELECT COUNT(*) as total FROM jugadores";
        $this->get_results_from_query();
        return $this->rows[0]['total'];
    }

    public function buscarJugadoresConTecnicas($termino)
    {

        // Coalesce: Asegurarse de que las columnas de técnicas y talentos no sean nulas
        // Esto es importante para evitar errores al concatenar cadenas
        // Asegurarse de que las columnas de técnicas y talentos no sean nulas
        // Utilizar COALESCE para proporcionar un valor por defecto
        // Trae todos los datos del jugador j* más dos campos adicionales: tecnicas y talentos, generados por subconsultas

        // PRIMER LEFT JOIN: Agrupa las técnicas de los jugadores
        // Utiliza GROUP_CONCAT para concatenar las técnicas de cada jugador en una sola cadena
        
        // SEGUNDO LEFT JOIN: Agrupa los talentos de los jugadores
        // Utiliza GROUP_CONCAT para concatenar los talentos de cada jugador en una sola cadena

        $this->query = "
            SELECT 
                j.*,
                COALESCE(tecnicas_data.tecnicas, '') as tecnicas,
                COALESCE(talentos_data.talentos, '') as talentos
            FROM jugadores j
            LEFT JOIN (
                SELECT 
                    js.id_jugador,
                    GROUP_CONCAT(
                        CONCAT('tecnica:', s.nombre, ':', js.nivel, ':', js.id_supertecnica)
                        ORDER BY js.nivel ASC 
                        SEPARATOR '|'
                    ) as tecnicas
                FROM jugadores_supertecnicas js
                INNER JOIN supertecnicas s ON js.id_supertecnica = s.id_supertecnica
                GROUP BY js.id_jugador
            ) tecnicas_data ON j.id_jugador = tecnicas_data.id_jugador
            LEFT JOIN (
                SELECT 
                    jt.id_jugador,
                    GROUP_CONCAT(
                        CONCAT('talento:', t.nombre, ':', jt.nivel, ':', jt.id_talento)
                        ORDER BY jt.nivel ASC 
                        SEPARATOR '|'
                    ) as talentos
                FROM jugadores_talentos jt
                INNER JOIN talentos t ON jt.id_talento = t.id_talento
                GROUP BY jt.id_jugador
            ) talentos_data ON j.id_jugador = talentos_data.id_jugador
            WHERE j.nombre LIKE :termino
            ORDER BY j.nombre ASC
        ";
        $this->parametros = ['termino' => '%' . $termino . '%'];
        $this->get_results_from_query();

        return $this->rows;
    }

    // Búsqueda con filtros múltiples
    public function getJugadoresConFiltros($filtros = [])
    {
        $where = [];
        $params = [];
        
        // Filtros básicos
        if (!empty($filtros['nombre'])) {
            $where[] = "j.nombre LIKE :nombre";
            $params['nombre'] = '%' . $filtros['nombre'] . '%';
        }
        
        if (!empty($filtros['posicion'])) {
            $where[] = "j.posicion = :posicion";
            $params['posicion'] = $filtros['posicion'];
        }
        
        if (!empty($filtros['elemento'])) {
            $where[] = "j.elemento = :elemento";
            $params['elemento'] = $filtros['elemento'];
        }
        
        if (!empty($filtros['edad'])) {
            $where[] = "j.edad = :edad";
            $params['edad'] = $filtros['edad'];
        }
        
        if (!empty($filtros['genero'])) {
            $where[] = "j.genero = :genero";
            $params['genero'] = $filtros['genero'];
        }
        
        // Filtros de precio
        if (!empty($filtros['precio_tipo'])) {
            $where[] = "j.precio_tipo = :precio_tipo";
            $params['precio_tipo'] = $filtros['precio_tipo'];
        }
        
        if (!empty($filtros['precio_min'])) {
            $where[] = "j.precio_cantidad >= :precio_min";
            $params['precio_min'] = (int)$filtros['precio_min'];
        }
        
        if (!empty($filtros['precio_max'])) {
            $where[] = "j.precio_cantidad <= :precio_max";
            $params['precio_max'] = (int)$filtros['precio_max'];
        }
        
        // Filtros avanzados (estadísticas)
        $estadisticasMap = [
            'pe' => 'pe',
            'pt' => 'pt', 
            'tiro' => 'tiro',
            'regate' => 'regate',
            'defensa' => 'defensa',
            'control' => 'control',
            'tecnica' => 'tecnica',
            'rapidez' => 'rapidez',
            'aguante' => 'aguante',
            'suerte' => 'suerte',
            'libertad' => 'libertad'
        ];
        
        foreach ($estadisticasMap as $paramName => $columnName) {
            if (!empty($filtros[$paramName . '_min'])) {
                $where[] = "j.{$columnName} >= :{$paramName}_min";
                $params[$paramName . '_min'] = (int)$filtros[$paramName . '_min'];
            }
            
            if (!empty($filtros[$paramName . '_max'])) {
                $where[] = "j.{$columnName} <= :{$paramName}_max";
                $params[$paramName . '_max'] = (int)$filtros[$paramName . '_max'];
            }
        }
        
        // Filtro para mostrar solo no fichados
        if (!empty($filtros['solo_disponibles']) && $filtros['solo_disponibles'] == 'true') {
            $where[] = "v.id_jugador IS NULL";
        }
        
        $whereClause = '';
        if (!empty($where)) {
            $whereClause = 'WHERE ' . implode(' AND ', $where);
        }

        // Orden opcional (claves 'orden' y 'direccion' en $filtros). Whitelist
        // de columnas para no interpolar nombres de columna ni dirección sin
        // validar en el SQL.
        $columnasOrden = [
            'id' => 'j.id_jugador',
            'nombre' => 'j.nombre',
            'precio' => 'j.precio_cantidad',
            'pe' => 'j.pe',
            'pt' => 'j.pt',
            'tiro' => 'j.tiro',
            'regate' => 'j.regate',
            'defensa' => 'j.defensa',
            'control' => 'j.control',
            'tecnica' => 'j.tecnica',
            'rapidez' => 'j.rapidez',
            'aguante' => 'j.aguante',
            'suerte' => 'j.suerte',
            'libertad' => 'j.libertad',
        ];
        $columnaOrden = $columnasOrden[$filtros['orden'] ?? 'id'] ?? 'j.id_jugador';
        $direccionOrden = strtoupper($filtros['direccion'] ?? 'ASC') === 'DESC' ? 'DESC' : 'ASC';

        // Paginación opcional (claves 'limite' y 'offset' en $filtros)
        $limitClause = '';
        if (!empty($filtros['limite']) && (int) $filtros['limite'] > 0) {
            $limitClause = ' LIMIT ' . (int) $filtros['limite'];
            if (!empty($filtros['offset']) && (int) $filtros['offset'] > 0) {
                $limitClause .= ' OFFSET ' . (int) $filtros['offset'];
            }
        }

        $this->query = "
            SELECT j.*
            FROM jugadores j
            LEFT JOIN fichajes v ON j.id_jugador = v.id_jugador
            {$whereClause}
            ORDER BY {$columnaOrden} {$direccionOrden}
        " . $limitClause;

        $this->parametros = $params;
        $this->get_results_from_query();
        
        return $this->rows;
    }


}
