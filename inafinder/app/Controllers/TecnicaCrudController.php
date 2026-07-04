<?php

namespace App\Controllers;

use App\Models\Tecnicas;

// CRUD común de los cuatro catálogos de supertécnicas (tiros, paradas, bloqueos
// y regates): antes cada uno tenía su propio controller clon de ~270 líneas.
// Cada subclase solo declara su tipo, sus claves y sus campos fijos.
abstract class TecnicaCrudController extends BaseController
{
    protected $tipo;       // valor de tipo_principal: 'tiro', 'parada', 'bloqueo' o 'regate'
    protected $plural;     // clave de $_POST/$_FILES, carpeta de uploads/vistas y prefijo de rutas: 'tiros'
    protected $singular;   // clave de $data en la vista de edición: 'tiro'
    protected $fijos = [];         // campos forzados para este tipo (alta y edición), p. ej. ['riesgo' => 0]
    protected $defaultsNuevo = []; // defaults del alta que difieren de los comunes

    // Campos editables comunes a todos los tipos (imagen y subtipo se tratan aparte)
    private const CAMPOS = ['nombre', 'elemento', 'potencia', 'aturdimiento', 'costo', 'dificultad', 'riesgo', 'n_jugadores', 'eg_damage'];
    private const DEFAULTS = ['nombre' => '', 'elemento' => '', 'potencia' => 0, 'aturdimiento' => 0, 'costo' => 0, 'dificultad' => 0, 'riesgo' => 0, 'n_jugadores' => 1, 'eg_damage' => 0];

    // Subtipo al editar: cada tipo lo resuelve a su manera (radio, checkbox o fijo)
    abstract protected function subtipoEdicion(array $d, array $actual);

    // Subtipo al crear: campo 'subtipo' del formulario o 'normal'
    protected function subtipoNuevo(array $d)
    {
        if (array_key_exists('subtipo', $this->fijos)) {
            return $this->fijos['subtipo'];
        }
        return !empty($d['subtipo']) ? $d['subtipo'] : 'normal';
    }

    // Hook para corregir claves torcidas del formulario antes de procesar
    protected function normalizar(array $d)
    {
        return $d;
    }

    protected function uploadDir()
    {
        return '../public/imgs/uploads/' . $this->plural . '/';
    }

    public function AddAction()
    {
        $tecnicas = Tecnicas::getInstancia();

        if (!empty($_POST)) {
            $defaults = array_merge(self::DEFAULTS, $this->defaultsNuevo);
            $insertadas = 0;

            foreach ($_POST[$this->plural] as $index => $itemData) {
                $d = [];
                foreach ($itemData as $campo => $valor) {
                    $d[$campo] = htmlspecialchars(trim($valor));
                }
                $d = $this->normalizar($d);

                $valores = ['tipo_principal' => $this->tipo, 'subtipo' => $this->subtipoNuevo($d)];
                foreach (self::CAMPOS as $campo) {
                    if (array_key_exists($campo, $this->fijos)) {
                        $valores[$campo] = $this->fijos[$campo];
                    } elseif (isset($d[$campo]) && $d[$campo] !== '') {
                        $valores[$campo] = $d[$campo];
                    } else {
                        $valores[$campo] = $defaults[$campo];
                    }
                }

                $valores['imagen'] = '';
                if (!empty($_FILES[$this->plural]['name'][$index]['imagen'])) {
                    $subida = $this->subirImagen(
                        $this->uploadDir(),
                        $_FILES[$this->plural]['name'][$index]['imagen'],
                        $_FILES[$this->plural]['tmp_name'][$index]['imagen'],
                        $valores['nombre']
                    );
                    if ($subida === null) {
                        echo "<script>alert('Error al subir imagen para {$this->singular}: {$valores['nombre']}');</script>";
                        continue;
                    }
                    $valores['imagen'] = $subida;
                }

                try {
                    $tecnicas->fill($valores)->set();
                    $insertadas++;
                } catch (\Exception $e) {
                    echo "<script>alert('Error al insertar {$this->singular}: {$valores['nombre']}');</script>";
                }
            }

            echo "<script>
                alert('Se han insertado {$insertadas} {$this->plural} correctamente.');
                window.location.href = '/{$this->plural}List';
            </script>";
            exit();
        }

        $this->renderHTML('../app/views/' . $this->plural . '/new.php', []);
    }

    public function ListAction()
    {
        $tecnicas = Tecnicas::getInstancia();
        $data = [];

        $busqueda = $_GET['buscar'] ?? '';
        if (!empty($busqueda)) {
            $data[$this->plural] = $tecnicas->buscarPorTipo($this->tipo, $busqueda);
        } else {
            $data[$this->plural] = $tecnicas->getPorTipo($this->tipo);
        }

        $this->renderHTML('../app/views/' . $this->plural . '/list.php', $data);
    }

    public function EditAction()
    {
        $tecnicas = Tecnicas::getInstancia();
        $data = [];

        $id = (int) explode('/', $_SERVER['REQUEST_URI'])[2];

        if (!empty($_POST)) {
            $d = $this->normalizar($_POST[$this->plural][0]);
            $actual = $tecnicas->get($id);

            // Campo enviado y no vacío → nuevo valor; vacío → se conserva el actual
            $valores = ['id_supertecnica' => $id, 'tipo_principal' => $this->tipo];
            foreach (self::CAMPOS as $campo) {
                if (array_key_exists($campo, $this->fijos)) {
                    $valores[$campo] = $this->fijos[$campo];
                } elseif (isset($d[$campo]) && trim($d[$campo]) !== '') {
                    $valores[$campo] = htmlspecialchars(trim($d[$campo]));
                } else {
                    $valores[$campo] = $actual[$campo];
                }
            }
            $valores['subtipo'] = $this->subtipoEdicion($d, $actual);

            $imagenActual = $actual['imagen'] ?? '';
            $valores['imagen'] = $imagenActual;
            if (!empty($_FILES[$this->plural]['name'][0]['imagen'])) {
                $subida = $this->subirImagen(
                    $this->uploadDir(),
                    $_FILES[$this->plural]['name'][0]['imagen'],
                    $_FILES[$this->plural]['tmp_name'][0]['imagen'],
                    $valores['nombre']
                );
                if ($subida !== null) {
                    $valores['imagen'] = $subida;
                    $this->borrarImagen($this->uploadDir(), $imagenActual);
                } else {
                    echo "<script>alert('Error al subir la nueva imagen.');</script>";
                }
            }

            try {
                $tecnicas->fill($valores)->edit();
                echo "<script>
                    alert('Técnica actualizada correctamente.');
                    window.location.href = '/{$this->plural}List';
                </script>";
                exit();
            } catch (\Exception $e) {
                echo "<script>alert('Error al actualizar: técnica no guardada.');</script>";
            }
        }

        $data[$this->singular] = $tecnicas->get($id);
        $this->renderHTML('../app/views/' . $this->plural . '/edit.php', $data);
    }

    public function DeleteAction()
    {
        $tecnicas = Tecnicas::getInstancia();

        $id = (int) explode('/', $_SERVER['REQUEST_URI'])[2];
        $item = $tecnicas->get($id);

        if (!$item) {
            echo "<script>
                alert('La técnica no existe o ya ha sido eliminada.');
                window.location.href = '/{$this->plural}List';
            </script>";
            exit();
        }

        try {
            $this->borrarImagen($this->uploadDir(), $item['imagen'] ?? '');
            $tecnicas->delete($id);
            echo "<script>
                alert('Técnica \"{$item['nombre']}\" eliminada correctamente.');
                window.location.href = '/{$this->plural}List';
            </script>";
            exit();
        } catch (\Exception $e) {
            echo "<script>
                alert('Error al eliminar la técnica.');
                window.history.back();
            </script>";
            exit();
        }
    }
}
