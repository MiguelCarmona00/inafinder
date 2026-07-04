<?php

namespace App\Controllers;

use App\Models\Usuarios;
use App\Models\Jugadores;
use App\Models\Monederos;
use App\Models\Tecnicas;
use App\Models\Talentos;
use Exception;

class TecnicasController extends BaseController
{

    public function TecnicasAddAction()
    {
        $tecnicas = Tecnicas::getInstancia();
        $data = [];

        if (!empty($_POST)) {
            
            // Procesar cada técnica del array
            $tecnicasProcesadas = [];
            
            foreach ($_POST['tecnicas'] as $index => $tecnicaData) {
                if (!empty(trim($tecnicaData['nombre']))) {
                    // Validar que se haya seleccionado un tipo
                    if (empty($tecnicaData['tipo_principal'])) {
                        continue; // Skip esta técnica si no tiene tipo
                    }
                    
                    // Manejar imagen
                    $imagenNombre = '';
                    if (isset($_FILES['tecnicas']['name'][$index]['imagen']) &&
                        $_FILES['tecnicas']['error'][$index]['imagen'] == 0) {

                        $uploadDir = '../public/imgs/uploads/' . $tecnicaData['tipo_principal'] . 's/';
                        $imagenNombre = $this->subirImagen(
                            $uploadDir,
                            $_FILES['tecnicas']['name'][$index]['imagen'],
                            $_FILES['tecnicas']['tmp_name'][$index]['imagen'],
                            trim($tecnicaData['nombre'])
                        ) ?? '';
                    }
                    
                    $tecnicaProcesada = [
                        'nombre' => trim($tecnicaData['nombre']),
                        'elemento' => trim($tecnicaData['elemento']) ?: 'normal',
                        'tipo_principal' => $tecnicaData['tipo_principal'],
                        'potencia' => !empty(trim($tecnicaData['potencia'])) ? intval($tecnicaData['potencia']) : 0,
                        'costo' => !empty(trim($tecnicaData['costo'])) ? intval($tecnicaData['costo']) : 0,
                        'imagen' => $imagenNombre
                    ];
                    
                    // Configurar campos según el tipo de técnica
                    switch ($tecnicaData['tipo_principal']) {
                        case 'tiro':
                            $tecnicaProcesada['subtipo'] = !empty(trim($tecnicaData['subtipo'])) ? trim($tecnicaData['subtipo']) : 'normal';
                            $tecnicaProcesada['dificultad'] = !empty(trim($tecnicaData['dificultad'])) ? intval($tecnicaData['dificultad']) : 0;
                            $tecnicaProcesada['aturdimiento'] = !empty(trim($tecnicaData['aturdimiento'])) ? intval($tecnicaData['aturdimiento']) : 0;
                            $tecnicaProcesada['n_jugadores'] = !empty(trim($tecnicaData['n_jugadores'])) ? intval($tecnicaData['n_jugadores']) : 1;
                            $tecnicaProcesada['riesgo'] = 0;
                            $tecnicaProcesada['eg_damage'] = 0;
                            break;
                            
                        case 'regate':
                            $tecnicaProcesada['subtipo'] = 'normal';
                            $tecnicaProcesada['dificultad'] = !empty(trim($tecnicaData['dificultad'])) ? intval($tecnicaData['dificultad']) : 0;
                            $tecnicaProcesada['riesgo'] = isset($tecnicaData['riesgo']) && $tecnicaData['riesgo'] !== '' ? intval($tecnicaData['riesgo']) : 0;
                            $tecnicaProcesada['n_jugadores'] = !empty(trim($tecnicaData['n_jugadores'])) ? intval($tecnicaData['n_jugadores']) : 1;
                            $tecnicaProcesada['eg_damage'] = !empty(trim($tecnicaData['eg_damage'])) ? intval($tecnicaData['eg_damage']) : 0;
                            $tecnicaProcesada['aturdimiento'] = 0;
                            break;
                            
                        case 'bloqueo':
                            $tecnicaProcesada['subtipo'] = (isset($tecnicaData['es_bloqueo']) && $tecnicaData['es_bloqueo'] === 'bloqueo') ? 'bloqueo' : 'normal';
                            $tecnicaProcesada['dificultad'] = !empty(trim($tecnicaData['dificultad'])) ? intval($tecnicaData['dificultad']) : 0;
                            $tecnicaProcesada['aturdimiento'] = trim($tecnicaData['aturdimiento']) !== '' ? intval($tecnicaData['aturdimiento']) : 0;
                            $tecnicaProcesada['eg_damage'] = !empty(trim($tecnicaData['eg_damage'])) ? intval($tecnicaData['eg_damage']) : 0;
                            $tecnicaProcesada['n_jugadores'] = !empty(trim($tecnicaData['n_jugadores'])) ? intval($tecnicaData['n_jugadores']) : 1;
                            $tecnicaProcesada['riesgo'] = isset($tecnicaData['riesgo']) && $tecnicaData['riesgo'] !== '' ? intval($tecnicaData['riesgo']) : 0;
                            break;
                            
                        case 'parada':
                            $tecnicaProcesada['subtipo'] = (isset($tecnicaData['es_despeje']) && $tecnicaData['es_despeje'] === 'despeje') ? 'despeje' : 'normal';
                            $tecnicaProcesada['dificultad'] = !empty(trim($tecnicaData['dificultad'])) ? intval($tecnicaData['dificultad']) : 0;
                            $tecnicaProcesada['aturdimiento'] = trim($tecnicaData['aturdimiento']) !== '' ? intval($tecnicaData['aturdimiento']) : 0;
                            $tecnicaProcesada['riesgo'] = 0;
                            $tecnicaProcesada['n_jugadores'] = 1;
                            $tecnicaProcesada['eg_damage'] = 0;
                            break;
                    }
                    
                    $tecnicasProcesadas[] = $tecnicaProcesada;
                }
            }
            
            // Insertar cada técnica en la base de datos (las claves del array
            // procesado coinciden con los atributos del modelo)
            $tecnicasInsertadas = 0;
            foreach ($tecnicasProcesadas as $tecnica) {
                $tecnicas->fill($tecnica)->set();
                $tecnicasInsertadas++;
            }
            
            // Mostrar mensaje de éxito
            echo "<script>
                Swal.fire('¡Éxito!', '{$tecnicasInsertadas} técnicas creadas correctamente', 'success').then(() => {
                    window.location.href = '/tecnicasList';
                });
            </script>";
            exit();
        }

        $this->renderHTML('../app/views/tecnicas/new.php', $data);
    }

    // Autocompletado de técnicas/talentos para los formularios de jugadores
    // (antes vivía en public/ajax_buscar_tecnicas.php, fuera del router y sin control de perfil)
    public function TecnicasBuscarAction()
    {
        header('Content-Type: application/json');

        $termino = trim($_GET['termino'] ?? '');
        if ($termino === '') {
            echo json_encode([]);
            exit();
        }

        $resultados = [];

        foreach (Tecnicas::getInstancia()->buscarTodas($termino) as $tecnica) {
            $resultados[] = [
                'id' => $tecnica['id_supertecnica'],
                'nombre' => $tecnica['nombre'],
                'tipo' => $tecnica['tipo_principal'],
                'imagen' => $tecnica['imagen'],
                'tabla' => 'supertecnicas'
            ];
        }

        foreach (Talentos::getInstancia()->buscarParaAutocompletar($termino) as $talento) {
            $resultados[] = [
                'id' => $talento['id'],
                'nombre' => $talento['nombre'],
                'tipo' => $talento['tipo'],
                'tabla' => 'talentos'
            ];
        }

        echo json_encode($resultados);
        exit();
    }

    public function TecnicasListAction()
    {
        $tecnicas = Tecnicas::getInstancia();
        $data = [];

        // Parámetros de búsqueda y límite
        $busqueda = $_GET['buscar'] ?? '';
        $limite = isset($_GET['limite']) ? intval($_GET['limite']) : 25;
        
        if (!empty($busqueda)) {
            $data['tecnicas'] = $tecnicas->buscarTodas($busqueda);
            $data['total_tecnicas'] = count($data['tecnicas']);
        } else {
            $todasTecnicas = $tecnicas->getAllTecnicas();
            $data['total_tecnicas'] = count($todasTecnicas);
            
            // Aplicar límite si no es 0 (todos)
            if ($limite > 0) {
                $data['tecnicas'] = array_slice($todasTecnicas, 0, $limite);
            } else {
                $data['tecnicas'] = $todasTecnicas;
            }
        }

        $this->renderHTML('../app/views/tecnicas/list.php', $data);
    }

    public function TecnicasEditAction()
    {
        $tecnicas = Tecnicas::getInstancia();
        $data = [];

        $idTecnica = explode('/', $_SERVER['REQUEST_URI'])[2];
        $idTecnica = (int)$idTecnica;

        // Verificar si se envió el formulario
        if (!empty($_POST)) {
            $tecnicaData = $_POST['tecnicas'][0];

            // Obtener datos actuales de la técnica
            $tecnicaActual = $tecnicas->get($idTecnica);

            // Campo enviado y no vacío → nuevo valor; vacío → se conserva el actual.
            // El tipo principal no se puede cambiar en edición.
            $valores = [
                'id_supertecnica' => $idTecnica,
                'tipo_principal' => $tecnicaActual['tipo_principal'],
            ];
            foreach (['nombre', 'elemento'] as $campo) {
                $valores[$campo] = !empty(trim($tecnicaData[$campo] ?? '')) ? trim($tecnicaData[$campo]) : $tecnicaActual[$campo];
            }
            foreach (['potencia', 'costo', 'dificultad', 'n_jugadores', 'eg_damage'] as $campo) {
                $valores[$campo] = !empty(trim($tecnicaData[$campo] ?? '')) ? intval($tecnicaData[$campo]) : $tecnicaActual[$campo];
            }
            $valores['aturdimiento'] = trim($tecnicaData['aturdimiento'] ?? '') !== '' ? intval($tecnicaData['aturdimiento']) : $tecnicaActual['aturdimiento'];
            $valores['riesgo'] = (isset($tecnicaData['riesgo']) && $tecnicaData['riesgo'] !== '') ? intval($tecnicaData['riesgo']) : $tecnicaActual['riesgo'];

            // Ajustes por tipo: subtipo y campos que ese tipo no usa
            switch ($tecnicaActual['tipo_principal']) {
                case 'tiro':
                    $valores['subtipo'] = !empty(trim($tecnicaData['subtipo'] ?? '')) ? trim($tecnicaData['subtipo']) : $tecnicaActual['subtipo'];
                    $valores['riesgo'] = 0;
                    $valores['eg_damage'] = 0;
                    break;

                case 'regate':
                    $valores['subtipo'] = 'normal';
                    $valores['aturdimiento'] = 0;
                    break;

                case 'bloqueo':
                    $valores['subtipo'] = (isset($tecnicaData['es_bloqueo']) && $tecnicaData['es_bloqueo'] === 'bloqueo') ? 'bloqueo' : 'normal';
                    $valores['aturdimiento'] = 0;
                    break;

                case 'parada':
                    $valores['subtipo'] = (isset($tecnicaData['es_despeje']) && $tecnicaData['es_despeje'] === 'despeje') ? 'despeje' : 'normal';
                    $valores['riesgo'] = 0;
                    $valores['n_jugadores'] = 1;
                    $valores['eg_damage'] = 0;
                    break;
            }

            // Manejar imagen: se conserva la actual salvo que llegue una nueva
            // (y la vieja solo se borra si la nueva se movió bien)
            $valores['imagen'] = $tecnicaActual['imagen'];

            if (isset($_FILES['tecnicas']['name'][0]['imagen']) &&
                $_FILES['tecnicas']['error'][0]['imagen'] == 0) {

                $uploadDir = '../public/imgs/uploads/' . $tecnicaActual['tipo_principal'] . 's/';
                $subida = $this->subirImagen(
                    $uploadDir,
                    $_FILES['tecnicas']['name'][0]['imagen'],
                    $_FILES['tecnicas']['tmp_name'][0]['imagen'],
                    $valores['nombre']
                );
                if ($subida !== null) {
                    $this->borrarImagen($uploadDir, $tecnicaActual['imagen']);
                    $valores['imagen'] = $subida;
                }
            }

            $tecnicas->fill($valores)->edit();
            
            echo "<script>
                Swal.fire('¡Éxito!', 'Técnica actualizada correctamente', 'success').then(() => {
                    window.location.href = '/tecnicasList';
                });
            </script>";
            exit();
        }

        // Cargar la técnica a editar
        $data['tecnica'] = $tecnicas->get($idTecnica);
        $this->renderHTML('../app/views/tecnicas/edit.php', $data);
    }

    public function TecnicasDeleteAction()
    {
        $tecnicas = Tecnicas::getInstancia();
        
        // Obtener el ID de la técnica de la URL
        $idTecnica = explode('/', $_SERVER['REQUEST_URI'])[2];
        $idTecnica = (int)$idTecnica;
        
        // Obtener información de la técnica antes de eliminarla
        $tecnica = $tecnicas->get($idTecnica);
        
        if (!$tecnica) {
            echo "<script>
                Swal.fire('Error', 'Técnica no encontrada', 'error').then(() => {
                    window.location.href = '/tecnicasList';
                });
            </script>";
            exit();
        }
        
        try {
            $tecnicas->delete($idTecnica);
            echo "<script>
                Swal.fire('¡Éxito!', 'Técnica eliminada correctamente', 'success').then(() => {
                    window.location.href = '/tecnicasList';
                });
            </script>";
            exit();
        } catch (Exception $e) {
            echo "<script>
                Swal.fire('Error', 'No se pudo eliminar la técnica', 'error').then(() => {
                    window.location.href = '/tecnicasList';
                });
            </script>";
            exit();
        }
    }
}

?>