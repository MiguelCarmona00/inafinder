<?php

namespace App\Controllers;

use App\Models\Entrenadores;

class EntrenadoresController extends BaseController
{
    private const UPLOAD_DIR = '../public/imgs/uploads/entrenadores/';

    public function EntrenadoresAction()
    {
        $entrenadores = Entrenadores::getInstancia();
        $data = [];

        if (!empty($_POST)) {
            $entrenadoresInsertados = 0;

            foreach ($_POST['entrenadores'] as $index => $entrenadorData) {
                $entrenadorProcesado = [];
                foreach ($entrenadorData as $campo => $valor) {
                    $entrenadorProcesado[$campo] = htmlspecialchars(trim($valor));
                }

                $entrenadorProcesado['imagen'] = $entrenadorProcesado['imagen'] ?? '';
                if (!empty($_FILES['entrenadores']['name'][$index]['imagen'])) {
                    $subida = $this->subirImagen(
                        self::UPLOAD_DIR,
                        $_FILES['entrenadores']['name'][$index]['imagen'],
                        $_FILES['entrenadores']['tmp_name'][$index]['imagen'],
                        $entrenadorProcesado['nombre']
                    );
                    if ($subida === null) {
                        echo "<script>alert('Error al subir imagen para entrenador: {$entrenadorProcesado['nombre']}');</script>";
                        continue;
                    }
                    $entrenadorProcesado['imagen'] = $subida;
                }

                try {
                    $entrenadores->fill($entrenadorProcesado)->set();
                    $entrenadoresInsertados++;
                } catch (\Exception $e) {
                    echo "<script>alert('Error al insertar entrenador: {$entrenadorProcesado['nombre']}');</script>";
                }
            }

            echo "<script>
                alert('Se han insertado {$entrenadoresInsertados} entrenadores correctamente.');
                window.location.href = '/entrenadoresList';
            </script>";
            exit();
        }

        $this->renderHTML('../app/views/entrenadores/new.php', $data);
    }

    public function EntrenadoresListAction()
    {
        $entrenadores = Entrenadores::getInstancia();
        $data = [];

        $busqueda = $_GET['buscar'] ?? '';
        if (!empty($busqueda)) {
            $data['entrenadores'] = $entrenadores->buscarEntrenadores($busqueda);
        } else {
            $data['entrenadores'] = $entrenadores->getAll();
        }

        $this->renderHTML('../app/views/entrenadores/list.php', $data);
    }

    public function EntrenadoresEditAction()
    {
        $entrenadores = Entrenadores::getInstancia();
        $data = [];

        $idEntrenador = (int) explode('/', $_SERVER['REQUEST_URI'])[2];

        if (!empty($_POST)) {
            $entrenadorActual = $entrenadores->get($idEntrenador);

            $nombrePost = trim($_POST['entrenador'][0]['nombre'] ?? '');
            $nombre = $nombrePost !== '' ? htmlspecialchars($nombrePost) : $entrenadorActual['nombre'];

            $imagen = $entrenadorActual['imagen'] ?? '';
            if (!empty($_FILES['entrenadores']['name'][0]['imagen'])) {
                $subida = $this->subirImagen(
                    self::UPLOAD_DIR,
                    $_FILES['entrenadores']['name'][0]['imagen'],
                    $_FILES['entrenadores']['tmp_name'][0]['imagen'],
                    $nombre
                );
                if ($subida !== null) {
                    $this->borrarImagen(self::UPLOAD_DIR, $imagen);
                    $imagen = $subida;
                } else {
                    echo "<script>alert('Error al subir la nueva imagen.');</script>";
                }
            }

            try {
                $entrenadores->fill([
                    'id_ent' => $idEntrenador,
                    'nombre' => $nombre,
                    'imagen' => $imagen,
                ])->edit();
                echo "<script>
                    alert('Entrenador actualizado correctamente.');
                    window.location.href = '/entrenadoresList';
                </script>";
                exit();
            } catch (\Exception $e) {
                echo "<script>alert('Error al actualizar entrenador.');</script>";
            }
        }

        $data['entrenador'] = $entrenadores->get($idEntrenador);
        $this->renderHTML('../app/views/entrenadores/edit.php', $data);
    }

    public function EntrenadoresDeleteAction()
    {
        $entrenadores = Entrenadores::getInstancia();

        $idEntrenador = (int) explode('/', $_SERVER['REQUEST_URI'])[2];
        $entrenador = $entrenadores->get($idEntrenador);

        if (!$entrenador) {
            echo "<script>
            alert('El entrenador no existe o ya ha sido eliminado.');
            window.location.href = '/entrenadoresList';
            </script>";
            exit();
        }

        try {
            $this->borrarImagen(self::UPLOAD_DIR, $entrenador['imagen'] ?? '');
            $entrenadores->delete($idEntrenador);
            echo "<script>
                alert('Entrenador \"{$entrenador['nombre']}\" eliminado correctamente.');
                window.location.href = '/entrenadoresList';
            </script>";
            exit();
        } catch (\Exception $e) {
            echo "<script>
                alert('Error al eliminar el entrenador.');
                window.history.back();
            </script>";
            exit();
        }
    }
}
