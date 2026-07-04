<?php

namespace App\Controllers;

use App\Models\Usuarios;
use App\Models\Talentos;

class TalentosController extends BaseController
{
    public function IndexAction()
    {
        $usuarios = Usuarios::getInstancia();
        $talentos = Talentos::getInstancia();
        $data = [];

        if (!empty($_POST)) {
            
            // Procesar cada talento del array
            $talentosProcesados = [];
            
            foreach ($_POST['talentos'] as $index => $talentoData) {
                $talentoProcesado = [];
                
                // Procesar cada campo del talento
                foreach ($talentoData as $campo => $valor) {
                    $talentoProcesado[$campo] = htmlspecialchars(trim($valor));
                }
                
                $talentosProcesados[] = $talentoProcesado;
            }
            
            // Insertar cada talento en la base de datos
            $talentosInsertados = 0;
            foreach ($talentosProcesados as $talento) {
                $talentos->fill([
                    'nombre' => $talento['nombre'],
                    'descripcion' => $talento['descripcion'] ?? '',
                    'tipo' => $talento['tipo'],
                    'afecta' => $talento['afecta'],
                ]);

                try {
                    $talentos->set();
                    $talentosInsertados++;
                } catch (\Exception $e) {
                    echo "<script>alert('Error al insertar talento: {$talento['nombre']}');</script>";
                }
            }
            
            // Mostrar mensaje de éxito
            echo "<script>
                alert('Se han insertado {$talentosInsertados} talentos correctamente.');
                window.location.href = '/talentos';
            </script>";
            exit();
        }

        $this->renderHTML('../app/views/talentos/new.php', $data);
    }

    public function ListAction()
    {
        $talentos = Talentos::getInstancia();
        $data = [];

        // Verificar si hay búsqueda
        $busqueda = $_GET['buscar'] ?? '';
        
        if (!empty($busqueda)) {
            $data['talentos'] = $talentos->buscar($busqueda);
        } else {
            $data['talentos'] = $talentos->getAll();
        }

        $this->renderHTML('../app/views/talentos/list.php', $data);
    }

    public function EditAction()
    {
        $talentos = Talentos::getInstancia();
        $data = [];

        $idTalento =  explode('/', string: $_SERVER['REQUEST_URI'])[2];

        
        // Verificar si se envió el formulario
        if (!empty($_POST)) {
            $talentoData = $_POST['talentos'][0];
            
            $talentos->fill([
                'nombre' => htmlspecialchars(trim($talentoData['nombre'])),
                'descripcion' => htmlspecialchars(trim($talentoData['descripcion'])),
                'tipo' => htmlspecialchars(trim($talentoData['tipo'])),
                'afecta' => htmlspecialchars(trim($talentoData['afecta'])),
            ]);

            try {
                $talentos->edit($idTalento);
                echo "<script>
                    alert('Talento actualizado correctamente.');
                    window.location.href = '/talentosList';
                </script>";
                exit();
            } catch (\Exception $e) {
                echo "<script>alert('Error al actualizar talento: {$e->getMessage()}');</script>";
            }
        }

        // Cargar el talento a editar
        
        $data['talento'] = $talentos->get($idTalento);
        $this->renderHTML('../app/views/talentos/edit.php', $data);
    }

    public function DeleteAction()
    {
        $talentos = Talentos::getInstancia();
        
        // Obtener el ID del talento de la URL
        $idTalento = explode('/', $_SERVER['REQUEST_URI'])[2];
        $idTalento = (int)$idTalento;
        
        // Obtener información del talento antes de eliminarlo
        $talento = $talentos->get($idTalento);
        
        if (!$talento) {
            echo "<script>
                alert('El talento no existe o ya ha sido eliminado.');
                window.location.href = '/talentosList';
            </script>";
            exit();
        }
        
        try {
            $talentos->delete($idTalento);
            echo "<script>
                alert('Talento \"{$talento['nombre']}\" eliminado correctamente.');
                window.location.href = '/talentosList';
            </script>";
            exit();
        } catch (\Exception $e) {
            echo "<script>
                alert('Error al eliminar el talento: {$e->getMessage()}');
                window.history.back();
            </script>";
            exit();
        }
    }
}

?>