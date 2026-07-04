<?php

// Cookie de sesión endurecida: inaccesible desde JS, no viaja en peticiones
// cross-site y solo por HTTPS cuando la conexión lo es
session_set_cookie_params([
    'lifetime' => 0,
    'path' => '/',
    'httponly' => true,
    'samesite' => 'Lax',
    'secure' => (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off'),
]);
session_start();
require_once "../bootstrap.php";
require_once "../vendor/autoload.php";

use App\Core\Router;
use App\Core\Csrf;
use App\Controllers\DefaultController;
use App\Controllers\UserController;
use App\Controllers\JugadoresController;
use App\Controllers\TalentosController;
use App\Controllers\ParadasController;
use App\Controllers\BloqueosController;
use App\Controllers\RegatesController;
use App\Controllers\TirosController;
use App\Controllers\EntrenadoresController;
use App\Controllers\TecnicasController;

if (!isset($_SESSION['id'])) {
    $_SESSION['id'] = '';
    $_SESSION['perfil'] = 'invitado';
    $_SESSION['user']['equipo'] = '';
}

// Rechazar cualquier POST que no traiga un token CSRF válido
// (campo csrf_token del formulario o cabecera X-CSRF-Token en AJAX)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !Csrf::validarPeticion()) {
    http_response_code(403);
    echo 'Petición rechazada: token CSRF inválido o ausente.';
    exit();
}

$router = new Router();

$router->add([  'name' => 'index',
                'path' => '/^\/$/',
                'action' => [DefaultController::class, 'IndexAction'],
                'perfil' => ['admin', 'user', 'modder']]);     

$router->add([  'name' => 'login',
                'path' => '/^\/login$/',
                'action' => [UserController::class, 'LoginAction']]);

$router->add([  'name' => 'logout',
                'path' => '/^\/logout$/',
                'action' => [UserController::class, 'LogoutAction']]);

$router->add([
                'name' => 'mercado',
                'path' => '/^\/mercado$/',
                'action' => [DefaultController::class, 'MercadoAction'],
                'perfil' => ['admin', 'user', 'modder']]);

$router->add([
                'name' => 'jugadorDetalle',
                'path' => '/^\/mercado\/jugador\/(\d+)$/',
                'action' => [DefaultController::class, 'JugadorDetalleAction'],
                'perfil' => ['admin', 'user', 'modder']]);

$router->add([  'name' => 'fichar',
                'path' => '/^\/fichar\/(\d+)$/',
                'action' => [DefaultController::class, 'FicharAction'],
                'method' => 'POST',
                'perfil' => ['admin', 'user', 'modder']]);

$router->add([
                'name' => 'checkUpdates',
                'path' => '/^\/checkUpdates(\?.*)?$/',
                'action' => [DefaultController::class, 'CheckUpdatesAction'],
                'perfil' => ['admin', 'user', 'modder']
            ]);


// Talentos routes

$router->add([ 'name' => 'talentos',
                'path' => '/^\/talentos$/',
                'action' => [TalentosController::class, 'IndexAction'],
                'perfil' => ['modder', 'admin']]);

$router->add([ 'name' => 'talentosList',
                'path' => '/^\/talentosList(\?.*)?$/',
                'action' => [TalentosController::class, 'ListAction'],
                'perfil' => ['modder', 'admin']]);

$router->add([ 'name' => 'talentosEdit',
                'path' => '/^\/talentosEdit\/(\d+)$/',
                'action' => [TalentosController::class, 'EditAction'],
                'perfil' => ['modder', 'admin']]);

$router->add([ 'name' => 'talentosDelete',
                'path' => '/^\/talentosDelete\/(\d+)$/',
                'action' => [TalentosController::class, 'DeleteAction'],
                'method' => 'POST',
                'perfil' => ['modder', 'admin']]);

// Tecnicas routes

$router->add([ 'name' => 'newTecnicas',
                'path' => '/^\/newTecnicas$/',
                'action' => [TecnicasController::class, 'TecnicasAddAction'],
                'perfil' => ['modder', 'admin']]);

$router->add([ 'name' => 'tecnicasList',
                'path' => '/^\/tecnicasList(\?.*)?$/',
                'action' => [TecnicasController::class, 'TecnicasListAction'],
                'perfil' => ['modder', 'admin']]);

$router->add([ 'name' => 'tecnicasEdit',
                'path' => '/^\/tecnicasEdit\/(\d+)$/',
                'action' => [TecnicasController::class, 'TecnicasEditAction'],
                'perfil' => ['modder', 'admin']]);

$router->add([ 'name' => 'tecnicasBuscar',
                'path' => '/^\/tecnicasBuscar(\?.*)?$/',
                'action' => [TecnicasController::class, 'TecnicasBuscarAction'],
                'perfil' => ['modder', 'admin']]);

$router->add([ 'name' => 'tecnicasDelete',
                'path' => '/^\/tecnicasDelete\/(\d+)$/',
                'action' => [TecnicasController::class, 'TecnicasDeleteAction'],
                'method' => 'POST',
                'perfil' => ['modder', 'admin']]);


// Paradas routes

// Los cuatro catálogos de supertécnicas comparten las acciones de
// TecnicaCrudController (Add/List/Edit/Delete). Antes /tiros y /regates
// referenciaban métodos inexistentes (TirosAddAction/RegatesAddAction) y daban 500.
$router->add([ 'name' => 'paradas',
                'path' => '/^\/paradas$/',
                'action' => [ParadasController::class, 'AddAction'],
                'perfil' => ['admin', 'modder']]);

$router->add([ 'name' => 'paradasList',
                'path' => '/^\/paradasList(\?.*)?$/',
                'action' => [ParadasController::class, 'ListAction'],
                'perfil' => ['admin', 'modder']]);

$router->add([ 'name' => 'paradasEdit',
                'path' => '/^\/paradasEdit\/(\d+)$/',
                'action' => [ParadasController::class, 'EditAction'],
                'perfil' => ['admin', 'modder']]);

$router->add([ 'name' => 'paradasDelete',
                'path' => '/^\/paradasDelete\/(\d+)$/',
                'action' => [ParadasController::class, 'DeleteAction'],
                'method' => 'POST',
                'perfil' => ['admin', 'modder']]);

// Bloqueos routes

$router->add([ 'name' => 'bloqueos',
                'path' => '/^\/bloqueos$/',
                'action' => [BloqueosController::class, 'AddAction'],
                'perfil' => ['admin', 'modder']]);

$router->add([ 'name' => 'bloqueosList',
                'path' => '/^\/bloqueosList(\?.*)?$/',
                'action' => [BloqueosController::class, 'ListAction'],
                'perfil' => ['admin', 'modder']]);

$router->add([ 'name' => 'bloqueosEdit',
                'path' => '/^\/bloqueosEdit\/(\d+)$/',
                'action' => [BloqueosController::class, 'EditAction'],
                'perfil' => ['admin', 'modder']]);

$router->add([ 'name' => 'bloqueosDelete',
                'path' => '/^\/bloqueosDelete\/(\d+)$/',
                'action' => [BloqueosController::class, 'DeleteAction'],
                'method' => 'POST',
                'perfil' => ['admin', 'modder']]);

// Regates routes

$router->add([ 'name' => 'regates',
                'path' => '/^\/regates$/',
                'action' => [RegatesController::class, 'AddAction'],
                'perfil' => ['admin', 'modder']]);

$router->add([ 'name' => 'regatesList',
                'path' => '/^\/regatesList(\?.*)?$/',
                'action' => [RegatesController::class, 'ListAction'],
                'perfil' => ['admin', 'modder']]);

$router->add([ 'name' => 'regatesEdit',
                'path' => '/^\/regatesEdit\/(\d+)$/',
                'action' => [RegatesController::class, 'EditAction'],
                'perfil' => ['admin', 'modder']]);

$router->add([ 'name' => 'regatesDelete',
                'path' => '/^\/regatesDelete\/(\d+)$/',
                'action' => [RegatesController::class, 'DeleteAction'],
                'method' => 'POST',
                'perfil' => ['admin', 'modder']]);

// Tiros routes

$router->add([ 'name' => 'tiros',
                'path' => '/^\/tiros$/',
                'action' => [TirosController::class, 'AddAction'],
                'perfil' => ['admin', 'modder']]);

$router->add([ 'name' => 'tirosList',
                'path' => '/^\/tirosList(\?.*)?$/',
                'action' => [TirosController::class, 'ListAction'],
                'perfil' => ['admin', 'modder']]);

$router->add([ 'name' => 'tirosEdit',
                'path' => '/^\/tirosEdit\/(\d+)$/',
                'action' => [TirosController::class, 'EditAction'],
                'perfil' => ['admin', 'modder']]);

$router->add([ 'name' => 'tirosDelete',
                'path' => '/^\/tirosDelete\/(\d+)$/',
                'action' => [TirosController::class, 'DeleteAction'],
                'method' => 'POST',
                'perfil' => ['admin', 'modder']]);

// Entrenadores routes

$router->add([ 'name' => 'entrenadores',
                'path' => '/^\/entrenadores$/',
                'action' => [EntrenadoresController::class, 'EntrenadoresAction'],
                'perfil' => ['admin', 'modder']]);

$router->add([ 'name' => 'entrenadoresList',
                'path' => '/^\/entrenadoresList(\?.*)?$/',
                'action' => [EntrenadoresController::class, 'EntrenadoresListAction'],
                'perfil' => ['admin', 'modder']]);

$router->add([ 'name' => 'entrenadoresEdit',
                'path' => '/^\/entrenadoresEdit\/(\d+)$/',
                'action' => [EntrenadoresController::class, 'EntrenadoresEditAction'],
                'perfil' => ['admin', 'modder']]);

$router->add([ 'name' => 'entrenadoresDelete',
                'path' => '/^\/entrenadoresDelete\/(\d+)$/',
                'action' => [EntrenadoresController::class, 'EntrenadoresDeleteAction'],
                'method' => 'POST',
                'perfil' => ['admin', 'modder']]);

// Jugadores routes

$router->add([  'name' => 'jugadores',
                'path' => '/^\/jugadores$/',
                'action' => [JugadoresController::class, 'IndexAction'],
                'perfil' => ['admin', 'modder']]);

$router->add([  'name' => 'jugadoresList',
                'path' => '/^\/jugadoresList(\?.*)?$/',
                'action' => [JugadoresController::class, 'ListAction'],
                'perfil' => ['admin', 'modder']]);

$router->add([  'name' => 'jugadoresEdit',
                'path' => '/^\/jugadoresEdit\/(\d+)$/',
                'action' => [JugadoresController::class, 'EditAction'],
                'perfil' => ['admin', 'modder']]);

$router->add([  'name' => 'jugadoresDelete',
                'path' => '/^\/jugadoresDelete\/(\d+)$/',
                'action' => [JugadoresController::class, 'DeleteAction'],
                'method' => 'POST',
                'perfil' => ['admin', 'modder']]);

$router->add([  'name' => 'reembolso',
                'path' => '/^\/reembolso\/(\d+)$/',
                'action' => [JugadoresController::class, 'ReembolsoAction'],
                'method' => 'POST',
                'perfil' => ['admin', 'user', 'modder']]);


// Ruta para crear un nuevo equipo
$router->add([  'name' => 'registerTeam',
                'path' => '/^\/registerTeam$/',
                'action' => [UserController::class, 'RegisterTeamAction'],
                'perfil' => ['admin', 'user', 'modder', 'temp']
            ]);

// Ruta para editar equipo
$router->add([  'name' => 'editTeam',
                'path' => '/^\/editTeam$/',
                'action' => [UserController::class, 'EditTeamAction'],
                'perfil' => ['admin', 'user', 'modder']
            ]);

$request = $_SERVER['REQUEST_URI'];
$route = $router->match($request);

if($route){
    // Las rutas que modifican datos exigen su método HTTP (POST)
    if (isset($route['method']) && $_SERVER['REQUEST_METHOD'] !== $route['method']) {
        http_response_code(405);
        echo 'Método no permitido';
        exit();
    }

     if (isset($route['perfil']) && !in_array($_SESSION['perfil'], $route['perfil'])) {
        
        // si no hay sesión, redirigir a login
        if($_SESSION['perfil'] == 'invitado'){
            header('Location: /login');
        } else {
            header('Location: /');
        }

        if (empty($_SESSION['user']['equipo']) && $_SESSION['perfil'] == 'temp') {
            header('Location: /registerTeam');
        }


        exit();
    } else{
        $controllerName = $route['action'][0];
        $actionName = $route['action'][1];
        $controller = new $controllerName;
        try {
            $controller->$actionName($request);
        } catch (\Throwable $e) {
            // Cualquier error no controlado (p. ej. PDOException) se loguea con su
            // traza y el usuario solo ve una página genérica
            error_log('Error no controlado en ' . $request . ': ' . $e);
            http_response_code(500);
            include '../app/views/error_view.php';
        }
    }
}else{
    http_response_code(404);
    echo "No route";
}
?>
