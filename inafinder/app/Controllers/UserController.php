<?php

namespace App\Controllers;

use App\Models\Usuarios;
use App\Models\Monederos;
use App\Models\Equipos;
use App\Models\LoginIntentos;
use App\Config\TextSanitizer;
use App\Config\ImageConverter;


class UserController extends BaseController
{
    public function LoginAction()
    {
        $data = [];
        $data['email'] = $data['password'] = '';
        $procesaForm = false;
        $usuarios = Usuarios::getInstancia();
        $equipos = Equipos::getInstancia();
        $intentosLogin = LoginIntentos::getInstancia();

        // Configuración de intentos limitados (persistidos en BD por usuario + IP,
        // no se evaden borrando la cookie de sesión)
        $maxIntentos = 5;
        $tiempoBloqueo = 300; // 5 minutos en segundos
        $ip = $_SERVER['REMOTE_ADDR'] ?? '';

        if (!empty($_POST)) {

            $data['email'] = trim($_POST['email'] ?? '');
            $data['password'] = $_POST['password'] ?? '';

            $procesaForm = true;

            // Cambiar la validación para permitir tanto email como username
            if (empty($data['email'])) {
                $data['errorLogin'] = "El email o nombre de usuario no puede estar vacío.";
                $procesaForm = false;
            }

            if (empty($data['password'])) {
                $data['errorLogin'] = "La contraseña no puede estar vacía.";
                $procesaForm = false;
            }

        }

        // Identificador que intenta entrar, truncado al tamaño de la columna
        $identificador = mb_strtolower(mb_substr($data['email'], 0, 150));

        if ($procesaForm) {
            // ¿Sigue bloqueado este usuario+IP?
            $restante = $intentosLogin->segundosBloqueado($identificador, $ip);
            if ($restante > 0) {
                $data['bloqueado'] = true;
                $data['tiempoRestante'] = $restante;
                $this->renderHTML('../app/views/login_view.php', $data);
                return;
            }

            $resultado = $usuarios->login($data['email'], $data['password']);

            if ($resultado) {
                // Login exitoso: limpiar intentos y regenerar el id de sesión
                // para evitar fijación de sesión (los datos de sesión se conservan)
                $intentosLogin->resetear($identificador, $ip);
                session_regenerate_id(true);

                $_SESSION['user'] = [
                    'email' => $resultado[0]['email'],
                    'nombre' => $resultado[0]['nombre'],
                ];

                $_SESSION['id'] = $resultado[0]['id_usuario'];

                // Obtenemos el equipo por el id del usuario
                $_SESSION['user']['equipo'] = $equipos->getByUser($resultado[0]['id_usuario']);

                // Si no tiene equipo, le asignamos un perfil temporal
                if (empty($_SESSION['user']['equipo'])) {
                    $_SESSION['perfil'] = 'temp';
                } else {
                    $_SESSION['perfil'] = $resultado[0]['rol'];
                }

                // Si no tiene equipo, redirigir a crear equipo
                $equipoUsuario = $equipos->getByUser($resultado[0]['id_usuario']);
                if (empty($equipoUsuario)) {
                    header("Location: /registerTeam");
                    exit();
                }

                // Guardamos la información del equipo 
                $resultado[0]['equipo'] = $equipos->getByUser($resultado[0]['id_usuario']);

                // Cargamos el monedero del equipo leyéndolo de la BD
                $this->refrescarMonedasSesion($resultado[0]['equipo'][0]['id_equipo']);

                header("Location: /");
                exit();
            } else {
                // Login fallido: registrar el intento en BD
                $fallo = $intentosLogin->registrarFallo($identificador, $ip, $maxIntentos, $tiempoBloqueo);

                if ($fallo['restante'] > 0) {
                    // Bloqueado tras agotar los intentos
                    $data['bloqueado'] = true;
                    $data['tiempoRestante'] = $fallo['restante'];
                } else {
                    $intentosRestantes = $maxIntentos - $fallo['intentos'];
                    $data['errorLogin'] = "Usuario o contraseña incorrectos. Te quedan " . $intentosRestantes . " intentos.";
                    $data['intentosRestantes'] = $intentosRestantes;
                }
            }
        }



        $this->renderHTML('../app/views/login_view.php', $data);
    }

    public function LogoutAction()
    {
        session_destroy();
        header("Location: /login");
        exit();
    }

    // Valida (por contenido real, no por el MIME del navegador) y guarda el
    // escudo subido como WebP. Devuelve el nombre de archivo generado, o null
    // dejando el motivo en $error.
    private function guardarEscudo($escudo, $nombreEquipo, &$error)
    {
        $error = null;
        $mime = ImageConverter::validarEscudo($escudo);
        if ($mime === null) {
            $error = "El escudo debe ser una imagen PNG o WebP de menos de 2 MB.";
            return null;
        }

        $uploadDir = '../public/imgs/uploads/escudos/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        // Sanitizar el nombre: minúsculas, sin tildes, sin espacios, solo alfanuméricos
        $fileName = TextSanitizer::sanitizarNombre($nombreEquipo) . '_' . uniqid() . '.webp';
        $uploadPath = $uploadDir . $fileName;

        // PNG se convierte a WebP; WebP se guarda tal cual (su contenido ya está validado)
        $guardado = $mime === 'image/png'
            ? ImageConverter::convertirAWebP($escudo['tmp_name'], $uploadPath)
            : move_uploaded_file($escudo['tmp_name'], $uploadPath);

        if (!$guardado) {
            $error = "Error al procesar la imagen del escudo.";
            return null;
        }

        return $fileName;
    }

    public function RegisterTeamAction()
    {
        // Si el usuario ya tiene equipo, redirigir a la página principal
        if (!empty($_SESSION['user']['equipo'])) {
            header("Location: /");
            exit();
        }

        $data = [];


        // Lógica para procesar el formulario de creación de equipo
        if (!empty($_POST)) {
            // flag para procesar el formulario
            $procesaForm = true;

            // Procesar el formulario
            $nombreEquipo = $_POST['nombre'] ?? '';
            $escudoEquipo = $_FILES['escudo'] ?? null;

            // Validar el nombre, solo puede enviar caracteres alfanuméricos y espacios, contando tildes
            if (!preg_match('/^[\p{L}0-9 ]+$/u', $nombreEquipo)) {
                $data['error'] = "El nombre del equipo solo puede contener caracteres alfanuméricos y espacios.";
                $procesaForm = false;
            }

            // Validar la imagen por su contenido real (el type que manda el navegador es falsificable)
            if ($escudoEquipo && !empty($escudoEquipo['tmp_name'])
                && ImageConverter::validarEscudo($escudoEquipo) === null) {
                $data['error'] = "El escudo debe ser una imagen PNG o WebP de menos de 2 MB.";
                $procesaForm = false;
            }


            if($procesaForm) {

                $equipos = Equipos::getInstancia();
                $monedero = Monederos::getInstancia();

                // Procesamos la imagen: se valida el contenido y se guarda como WebP
                if ($escudoEquipo && !empty($escudoEquipo['tmp_name'])) {
                    $fileName = $this->guardarEscudo($escudoEquipo, $nombreEquipo, $errorEscudo);
                    if ($fileName !== null) {
                        $equipos->setEscudo($fileName);
                    } else {
                        echo "<script>alert('" . htmlspecialchars($errorEscudo, ENT_QUOTES) . "');</script>";
                    }
                }

                // Guardamos el equipo en la base de datos
                $equipos->setNombreEquipo($nombreEquipo);
                $equipos->setIdUsuario($_SESSION['id']);
                $nuevoEquipoId = $equipos->set();

                $equipo = $equipos->getByUser($_SESSION['id']);

                // guardamos en una variable el id del equipo
                $idEquipo = $equipo[0]['id_equipo'];

                if (!$nuevoEquipoId) {
                    // Crear el monedero inicial para el nuevo equipo
                    $monedasIniciales = [
                        'doradas' => 10,
                        'moradas' => 15,
                        'plateadas' => 20,
                        'rojas' => 25,
                        'azules' => 30
                    ];

                    foreach ($monedasIniciales as $tipoMoneda => $cantidad) {
                        $monedero->setIdEquipo($idEquipo);
                        $monedero->setTipoMoneda($tipoMoneda);
                        $monedero->setCantidad($cantidad);
                        $monedero->set();
                    }

                    // Guardamos el monedero en la sesión
                    $_SESSION['user']['monedas'] = $monedasIniciales;

                    // Asignamos el perfil de usuario normal
                    $_SESSION['perfil'] = 'user';

                    // Redirigir a la página principal
                    header("Location: /");
                    exit();
                } else {
                    $data['error'] = "Error al crear el equipo. Inténtalo de nuevo.";
                }
            }
            

        
        }

        $this->renderHTML('../app/views/equipos/newteam.php', $data);

    }

    public function EditTeamAction()
    {
        $data = [];
        $equipos = Equipos::getInstancia();

        // Obtener información actual del equipo
        $equipoActual = $equipos->getByUser($_SESSION['id']);

        $data['equipo'] = $equipoActual[0];

        // Procesar formulario si se envió
        if (!empty($_POST)) {
            $procesaForm = true;

            // Obtener datos del formulario
            $nombreEquipo = $_POST['nombre'] ?? '';
            $escudoEquipo = $_FILES['escudo'] ?? null;

            // Validar el nombre del equipo
            if (empty($nombreEquipo)) {
                $data['error'] = "El nombre del equipo no puede estar vacío.";
                $procesaForm = false;
            } elseif (!preg_match('/^[\p{L}0-9 ]+$/u', $nombreEquipo)) {
                $data['error'] = "El nombre del equipo solo puede contener caracteres alfanuméricos y espacios.";
                $procesaForm = false;
            }

            // Validar imagen si se proporcionó (por contenido real, no por el type del navegador)
            if ($escudoEquipo && !empty($escudoEquipo['tmp_name'])) {
                if (ImageConverter::validarEscudo($escudoEquipo) === null) {
                    $data['error'] = "El escudo debe ser una imagen PNG o WebP de menos de 2 MB.";
                    $procesaForm = false;
                }
            }

            if ($procesaForm) {
                // Preparar datos para actualizar
                $equipos->setNombreEquipo($nombreEquipo);
                $equipos->setIdUsuario($_SESSION['id']);

                // Procesar nueva imagen si se proporcionó
                if ($escudoEquipo && !empty($escudoEquipo['tmp_name'])) {
                    $fileName = $this->guardarEscudo($escudoEquipo, $nombreEquipo, $errorEscudo);
                    if ($fileName !== null) {
                        // Eliminar imagen anterior si existe
                        if (!empty($data['equipo']['escudo'])) {
                            $imagenAnterior = '../public/imgs/uploads/escudos/' . $data['equipo']['escudo'];
                            if (file_exists($imagenAnterior)) {
                                unlink($imagenAnterior);
                            }
                        }
                        $equipos->setEscudo($fileName);
                    } else {
                        $data['error'] = $errorEscudo;
                        $procesaForm = false;
                    }
                } else {
                    // Mantener el escudo actual si no se proporciona uno nuevo
                    $equipos->setEscudo($data['equipo']['escudo']);
                }

                if ($procesaForm) {
                    // Actualizar el equipo en la base de datos
                    $equipos->setIdEquipo($data['equipo']['id_equipo']);
                    $resultado = $equipos->edit();

                    if ($resultado) {
                        // Actualizar la información del equipo en la sesión
                        $_SESSION['user']['equipo'] = $equipos->getByUser($_SESSION['id']);
                        $data['success'] = "Equipo actualizado correctamente.";
                        
                        // Actualizar datos para mostrar en el formulario
                        $equipoActual = $equipos->getByUser($_SESSION['id']);
                        $data['equipo'] = $equipoActual[0];
                    } else {
                        $data['error'] = "Error al actualizar el equipo. Inténtalo de nuevo.";
                    }
                }
            }
        }

        $this->renderHTML('../app/views/equipos/edit.php', $data);
    }

}

?>