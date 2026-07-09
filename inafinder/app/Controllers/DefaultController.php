<?php

namespace App\Controllers;

use App\Models\Usuarios;
use App\Models\Fichajes;
use App\Models\Jugadores;
use App\Models\Monederos;
use App\Models\Equipos;

class DefaultController extends BaseController
{
    public function IndexAction()
    {
        // Instanciamos la clase usuarios
        $usuarios = Usuarios::getInstancia();
        $fichajes = Fichajes::getInstancia();
        $jugadores = Jugadores::getInstancia();
        $monederos = Monederos::getInstancia();
        $equipos = Equipos::getInstancia();
        $data = [];

        // Obtenemos todos los fichajes del usuario en sesión

        $_SESSION['user']['equipo'] = $equipos->getByUser($_SESSION['id']);

        // Guardamos el id del equipo en una variable accesible

        $idEquipo = $_SESSION['user']['equipo'][0]['id_equipo'];

        // Obtenemos todos los fichajes del equipo
        
        $data['fichajes'] = $fichajes->getByTeam($idEquipo);
        
        // Obtenemos los jugadores por id que devuelva la consulta anterior
        
        if (!empty($data['fichajes'])) {
            $data['jugadores'] = [];
            foreach ($data['fichajes'] as $venta) {
                $jugador = $jugadores->get($venta['id_jugador']);
                if ($jugador) {
                    $jugador[0]['fecha_fichaje'] = $venta['fecha_compra'];
                    $data['jugadores'][] = $jugador;
                }
            }
        } else {
            $data['jugadores'] = [];
        }


        // Refrescamos el monedero en sesión desde la BD y lo pasamos a la vista
        $this->refrescarMonedasSesion($idEquipo);
        $data['monedas'] = $_SESSION['user']['monedas'];

        $this->renderHTML('../app/views/index_view.php', $data);
    }

    public function MercadoAction(){
        
        $jugadores = Jugadores::getInstancia();

        $data = [];
        
        // Obtenemos todos los jugadores que no han sido fichados
        $data['jugadores']['jugadoresNoFichados'] = $jugadores->getNoFichados();
        $data['jugadores']['jugadoresFichados'] = $jugadores->getFichados();

        // var_dump($data);


        $this->renderHTML('../app/views/mercado_view.php', $data);
    }

    public function JugadorDetalleAction(){
        
        $jugadores = Jugadores::getInstancia();
        
        // Obtenemos el id del jugador de la URL
        $idJugador = explode('/', $_SERVER['REQUEST_URI'])[3];
        $idJugador = (int)$idJugador;
        
        // Obtenemos el jugador por id
        $jugador = $jugadores->get($idJugador);
        
        if (!$jugador) {
            header('Location: /mercado');
            exit();
        }
        
        $data['jugador'] = $jugador[0];
        
        // Verificar si está fichado
        $data['jugador']['esFichado'] = $jugadores->isFichado($idJugador);
        
        $this->renderHTML('../app/views/jugador_view.php', $data);
    }

    public function FicharAction()
    {
        $fichajes = Fichajes::getInstancia();
        $equipos = Equipos::getInstancia();

        $esAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH'])
            && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';

        // obtenemos el id del jugador de la URL
        $idJugador = (int) explode('/', $_SERVER['REQUEST_URI'])[2];

        // Obtenemos el equipo del usuario
        $equipo = $equipos->getByUser($_SESSION['id']);
        if (empty($equipo)) {
            if ($esAjax) {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'error' => 'no_team', 'message' => 'Necesitas crear un equipo antes de fichar.']);
            } else {
                header('Location: /registerTeam');
            }
            exit();
        }
        $idEquipo = $equipo[0]['id_equipo'];

        // Compra atómica contra la BD (transacción + descuento condicionado)
        $resultado = $fichajes->comprar($idEquipo, $idJugador);

        // La sesión se refresca siempre con el saldo real de la BD
        $this->refrescarMonedasSesion($idEquipo);

        $mensajes = [
            'already_signed' => 'Este jugador ya está fichado.',
            'insufficient_funds' => 'No tienes suficiente presupuesto para fichar a este jugador.',
            'not_found' => 'El jugador no existe.',
            'unknown' => 'Error al procesar el fichaje.',
        ];

        if ($esAjax) {
            header('Content-Type: application/json');
            if ($resultado['success']) {
                echo json_encode([
                    'success' => true,
                    'message' => '¡Jugador fichado exitosamente!',
                    'player' => $resultado['jugador'],
                    'monedas' => $_SESSION['user']['monedas'],
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'error' => $resultado['error'],
                    'message' => $mensajes[$resultado['error']] ?? $mensajes['unknown'],
                ]);
            }
            exit();
        }

        // Navegación no-AJAX: volvemos al mercado
        header('Location: /mercado');
        exit();
    }


    public function CheckUpdatesAction()
    {
        header('Content-Type: application/json');
        
        $jugadores = Jugadores::getInstancia();
        
        // Si hay parámetros de filtros, usar la función de filtros
        if (!empty($_GET) && (isset($_GET['nombre']) || isset($_GET['posicion']) || isset($_GET['elemento']) ||
            isset($_GET['edad']) || isset($_GET['genero']) || isset($_GET['precio_tipo']) ||
            isset($_GET['precio_min']) || isset($_GET['precio_max']) || isset($_GET['solo_disponibles']) ||
            isset($_GET['pe_min']) || isset($_GET['pt_min']) || isset($_GET['tiro_min']) ||
            isset($_GET['regate_min']) || isset($_GET['defensa_min']) || isset($_GET['orden']))) {
            
            // Procesar filtros
            $filtros = [];
            
            // Filtros básicos
            if (!empty($_GET['nombre'])) $filtros['nombre'] = $_GET['nombre'];
            if (!empty($_GET['posicion'])) $filtros['posicion'] = $_GET['posicion'];
            if (!empty($_GET['elemento'])) $filtros['elemento'] = $_GET['elemento'];
            if (!empty($_GET['edad'])) $filtros['edad'] = $_GET['edad'];
            if (!empty($_GET['genero'])) $filtros['genero'] = $_GET['genero'];
            if (!empty($_GET['precio_tipo'])) $filtros['precio_tipo'] = $_GET['precio_tipo'];
            if (!empty($_GET['precio_min'])) $filtros['precio_min'] = $_GET['precio_min'];
            if (!empty($_GET['precio_max'])) $filtros['precio_max'] = $_GET['precio_max'];
            
            // Filtros avanzados
            $estadisticas = ['pe', 'pt', 'tiro', 'regate', 'defensa', 'control', 'tecnica', 'rapidez', 'aguante', 'suerte', 'libertad'];
            foreach ($estadisticas as $stat) {
                if (!empty($_GET[$stat . '_min'])) $filtros[$stat . '_min'] = $_GET[$stat . '_min'];
                if (!empty($_GET[$stat . '_max'])) $filtros[$stat . '_max'] = $_GET[$stat . '_max'];
            }
            
            if (!empty($_GET['solo_disponibles'])) $filtros['solo_disponibles'] = $_GET['solo_disponibles'];

            // Orden opcional (whitelist real de columnas en Jugadores::getJugadoresConFiltros)
            if (!empty($_GET['orden'])) $filtros['orden'] = $_GET['orden'];
            if (!empty($_GET['direccion'])) $filtros['direccion'] = $_GET['direccion'];

            // Paginación opcional (?limite=N&offset=M); sin ella se mantiene el
            // comportamiento de siempre (todos los resultados del filtro)
            if (!empty($_GET['limite'])) $filtros['limite'] = (int) $_GET['limite'];
            if (!empty($_GET['offset'])) $filtros['offset'] = (int) $_GET['offset'];

            // Realizar la búsqueda con filtros
            $resultados = $jugadores->getJugadoresConFiltros($filtros);
            
            // Obtener todos los IDs de jugadores fichados de una vez
            $jugadoresFichados = $jugadores->getFichados();
            $idsFichados = array_column($jugadoresFichados, 'id_jugador');
            
            // Separar en fichados y no fichados
            $fichados = [];
            $noFichados = [];
            
            foreach ($resultados as $jugador) {
                if (in_array($jugador['id_jugador'], $idsFichados)) {
                    $fichados[] = $jugador;
                } else {
                    $noFichados[] = $jugador;
                }
            }
            
            $data = [
                'jugadoresNoFichados' => $noFichados,
                'jugadoresFichados' => $fichados,
                'total' => count($resultados),
                'timestamp' => time(),
                'filtered' => true
            ];
            
        } else {
            // Comportamiento normal sin filtros
            $data = [
                'jugadoresNoFichados' => $jugadores->getNoFichados(),
                'jugadoresFichados' => $jugadores->getFichados(),
                'timestamp' => time(),
                'filtered' => false
            ];
        }

        // ETag sobre los datos (sin el timestamp, que cambia siempre): el polling
        // de mercado.js manda If-None-Match y cuando nada ha cambiado respondemos
        // 304 sin cuerpo en vez de re-serializar y re-enviar todo el listado
        $etag = '"' . md5(json_encode([$data['jugadoresNoFichados'], $data['jugadoresFichados']])) . '"';
        header('Cache-Control: private, no-cache');
        header('ETag: ' . $etag);
        if (($_SERVER['HTTP_IF_NONE_MATCH'] ?? '') === $etag) {
            http_response_code(304);
            exit();
        }

        echo json_encode($data);
        exit();
    }
}

?>