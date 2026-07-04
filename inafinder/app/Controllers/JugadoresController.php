<?php

namespace App\Controllers;

use App\Models\Usuarios;
use App\Models\JugadoresSupertecnicas;
use App\Models\JugadoresTalentos;
use App\Models\Ventas;
use App\Models\Jugadores;
use App\Models\Fichajes;
use App\Models\Monederos;
use Exception;

class JugadoresController extends BaseController
{
    public function IndexAction()
    {
        $jugadores = Jugadores::getInstancia();
        $jugadoresSupertecnicas = JugadoresSupertecnicas::getInstancia();
        $jugadoresTalentos = JugadoresTalentos::getInstancia(); // Instanciar modelo
        $data = [];

        if (!empty($_POST)) {
            
            // Procesar cada jugador del array
            $jugadoresProcesados = [];
            
            foreach ($_POST['jugadores'] as $index => $jugadorData) {
                $jugadorProcesado = [];
                
                // Procesar cada campo del jugador
                foreach ($jugadorData as $campo => $valor) {
                    if ($campo !== 'estadisticas' && $campo !== 'tecnicas') {
                        $jugadorProcesado[$campo] = htmlspecialchars(trim($valor));
                    }
                }
                
                // Procesar imagen
                if (!empty($_FILES['jugadores']['name'][$index]['imagen'])) {
                    $subida = $this->subirImagen(
                        '../public/imgs/uploads/jugadores/',
                        $_FILES['jugadores']['name'][$index]['imagen'],
                        $_FILES['jugadores']['tmp_name'][$index]['imagen'],
                        $jugadorProcesado['nombre']
                    );
                    if ($subida === null) {
                        echo "<script>alert('Error al subir imagen para jugador: {$jugadorProcesado['nombre']}');</script>";
                        continue;
                    }
                    $jugadorProcesado['imagen'] = $subida;
                }
                
                // Procesar estadísticas
                if (!empty($jugadorData['estadisticas'])) {
                    $estadisticas = $this->procesarEstadisticas($jugadorData['estadisticas']);
                    $jugadorProcesado = array_merge($jugadorProcesado, $estadisticas);
                }
                
                // Procesar técnicas (guardar para más tarde)
                $jugadorProcesado['tecnicas'] = $jugadorData['tecnicas'] ?? [];
                
                $jugadoresProcesados[] = $jugadorProcesado;
            }
            
            // Insertar cada jugador en la base de datos
            $jugadoresInsertados = 0;

            
            foreach ($jugadoresProcesados as $jugador) {

                $jugadores->fill([
                    'imagen' => $jugador['imagen'] ?? '',
                    'nombre' => $jugador['nombre'],
                    'posicion' => $jugador['posicion'],
                    'elemento' => $jugador['elemento'],
                    'precio_cantidad' => $jugador['precio_cantidad'],
                    'precio_tipo' => $jugador['precio_tipo'],
                    'edad' => $jugador['edad'],
                    'genero' => $jugador['genero'],
                    'PE' => $jugador['pe'] ?? 0,
                    'PT' => $jugador['pt'] ?? 0,
                    'tiro' => $jugador['tiro'] ?? 0,
                    'regate' => $jugador['regate'] ?? 0,
                    'defensa' => $jugador['defensa'] ?? 0,
                    'control' => $jugador['control'] ?? 0,
                    'tecnica' => $jugador['tecnica'] ?? 0,
                    'rapidez' => $jugador['rapidez'] ?? 0,
                    'aguante' => $jugador['aguante'] ?? 0,
                    'suerte' => $jugador['suerte'] ?? 0,
                    'libertad' => $jugador['libertad'] ?? 0,
                ]);

                try {
                    $jugadores->set();
                    $idJugador = $jugadores->lastInsert();
                    
                    // Insertar técnicas y talentos del jugador
                    $this->insertarTecnicasYTalentosJugador($idJugador, $jugador['tecnicas'], $jugadoresSupertecnicas, $jugadoresTalentos);
                    
                    $jugadoresInsertados++;
                } catch (Exception $e) {
                    echo "<script>alert('Error al insertar jugador: {$jugador['nombre']}');</script>";
                }
            }
            
            // Mostrar mensaje de éxito
            echo "<script>
                alert('Se han insertado {$jugadoresInsertados} jugadores correctamente.');
                window.location.href = '/jugadoresList';
            </script>";
            exit();
        }

        $this->renderHTML('../app/views/jugadores/new.php', $data);
    }
    
    private function procesarEstadisticas($estadisticasTexto)
    {
        $traduccion = [
            'GP' => 'pe',
            'TP' => 'pt',
            'Kick' => 'tiro',
            'Dribbling' => 'regate',
            'Block' => 'defensa',
            'Catch' => 'control',
            'Technique' => 'tecnica',
            'Speed' => 'rapidez',
            'Stamina' => 'aguante',
            'Lucky' => 'suerte',
            'Freedom' => 'libertad'
        ];
        
        $estadisticas = [];
        $lineas = explode("\n", $estadisticasTexto);
        
        foreach ($lineas as $linea) {
            $linea = trim($linea);
            if (empty($linea)) continue;
            
            // Buscar patrón: Nombre: Número (Número opcional)
            if (preg_match('/^(\w+):\s*(\d+)/', $linea, $matches)) {
                $nombreOriginal = $matches[1];
                $valor = (int)$matches[2];
                
                if (isset($traduccion[$nombreOriginal])) {
                    $estadisticas[$traduccion[$nombreOriginal]] = $valor;
                }
            }
        }
        
        return $estadisticas;
    }
    
    private function insertarTecnicasYTalentosJugador($idJugador, $tecnicas, $jugadoresSupertecnicas, $jugadoresTalentos)
    {
        for ($i = 1; $i <= 6; $i++) {
            $nombreTecnica = $tecnicas["tecnica_{$i}_nombre"] ?? '';
            $nivelTecnica = $tecnicas["tecnica_{$i}_nivel"] ?? '';
            $idTecnica = $tecnicas["tecnica_{$i}_id"] ?? '';
            $tablaTecnica = $tecnicas["tecnica_{$i}_tabla"] ?? '';
            
            // Solo procesar si hay datos completos (nombre, id y tabla)
            if (!empty($nombreTecnica) && !empty($idTecnica) && !empty($tablaTecnica)) {
                
                // Determinar si es técnica o talento según la tabla
                if ($tablaTecnica === 'supertecnicas') {
                    // Es una técnica
                    $jugadoresSupertecnicas->fill([
                        'id_jugador' => $idJugador,
                        'id_supertecnica' => $idTecnica,
                        'nivel' => $nivelTecnica ?: 1,
                    ]);

                    try {
                        $jugadoresSupertecnicas->set();
                        error_log("DEBUG: Técnica insertada - {$nombreTecnica} (ID: {$idTecnica}, Nivel: " . ($nivelTecnica ?: 1) . ")");
                    } catch (Exception $e) {
                        error_log("Error al insertar técnica {$nombreTecnica} para jugador {$idJugador}: " . $e->getMessage());
                    }
                    
                } elseif ($tablaTecnica === 'talentos') {
                    // Es un talento
                    $jugadoresTalentos->fill([
                        'id_jugador' => $idJugador,
                        'id_talento' => $idTecnica,
                        'nivel' => $nivelTecnica ?: 1,
                    ]);

                    try {
                        $jugadoresTalentos->set();
                        error_log("DEBUG: Talento insertado - {$nombreTecnica} (ID: {$idTecnica}, Nivel: " . ($nivelTecnica ?: 1) . ")");
                    } catch (Exception $e) {
                        error_log("Error al insertar talento {$nombreTecnica} para jugador {$idJugador}: " . $e->getMessage());
                    }
                }
            } else {
                // Debug para campos incompletos
                if (!empty($nombreTecnica) || !empty($idTecnica)) {
                    error_log("DEBUG: Técnica {$i} incompleta - Nombre: '{$nombreTecnica}', ID: '{$idTecnica}', Tabla: '{$tablaTecnica}'");
                }
            }
        }
    }

    public function ListAction()
    {
        $jugadoresModel = Jugadores::getInstancia();
        $data = [];

        // Obtener el límite desde GET, por defecto 25
        $limite = isset($_GET['limite']) ? intval($_GET['limite']) : 25;
        
        // Si el límite es 0 o negativo, obtener todos
        if ($limite <= 0) {
            $limite = null;
        }

        // Página actual (?pagina=N) → OFFSET sobre el límite elegido
        $pagina = max(1, intval($_GET['pagina'] ?? 1));
        $offset = ($limite !== null) ? ($pagina - 1) * $limite : 0;

        // Obtener el total de jugadores para mostrar la información
        $data['total_jugadores'] = $jugadoresModel->getTotalJugadores();
        $data['limite_actual'] = $limite;
        $data['pagina_actual'] = $pagina;

        $data['jugadores'] = $jugadoresModel->getJugadoresConTecnicas($limite, $offset);

        // Para listar, necesitamos procesar manualmente
        // paso por referencia "&" para que los cambios se reflejen en el array original
        foreach ($data['jugadores'] as &$jugador) {
            $tecnicasProcesadas = [];

            // Guardar las cadenas originales antes de sobrescribir
            $tecnicasOriginales = $jugador['tecnicas'] ?? '';
            $talentosOriginales = $jugador['talentos'] ?? '';

            
            // Procesar técnicas
            if (!empty($tecnicasOriginales)) {
                $tecnicasArray = explode('|', $tecnicasOriginales);
                foreach ($tecnicasArray as $tecnica) {
                    if (!empty($tecnica)) {
                        $partes = explode(':', $tecnica);
                        if (count($partes) === 4) {
                            $tecnicasProcesadas[] = [
                                'nombre_tecnica' => $partes[1],
                                'nivel' => (int)$partes[2],
                                'tipo' => $partes[0], // 'tecnica'
                                'id' => $partes[3]
                            ];
                        }
                    }
                }
            }
            
            // Procesar talentos
            if (!empty($talentosOriginales)) {
                $talentosArray = explode('|', $talentosOriginales);
                foreach ($talentosArray as $talento) {
                    if (!empty($talento)) {
                        $partes = explode(':', $talento);
                        if (count($partes) === 4) {
                            $tecnicasProcesadas[] = [
                                'nombre_tecnica' => $partes[1],
                                'nivel' => (int)$partes[2],
                                'tipo' => $partes[0], // 'talento'
                                'id' => (int)$partes[3]
                            ];
                        }
                    }
                }
            }

            // Asignar el array procesado al final
            $jugador['tecnicas'] = $tecnicasProcesadas;

            // Ordenar técnicas por nivel ascendente
            usort($jugador['tecnicas'], function ($a, $b) {
                return $a['nivel'] <=> $b['nivel'];
            });
            
            // Limpiar campos temporales
            unset($jugador['tecnicas_procesadas']);
        }

        $this->renderHTML('../app/views/jugadores/list.php', $data);
    }

    public function EditAction()
    {
        $jugadores = Jugadores::getInstancia();
        $jugadoresSupertecnicas = JugadoresSupertecnicas::getInstancia();
        $jugadoresTalentos = JugadoresTalentos::getInstancia();
        $data = [];

        // Obtener ID del jugador desde la URL
        $id_jugador = explode('/', $_SERVER['REQUEST_URI'])[2];
        $id_jugador = (int)$id_jugador;


        // Cargar datos del jugador
        $jugador = $jugadores->getJugadorCompleto($id_jugador);
        if (!$jugador) {
            echo "<script>alert('Jugador no encontrado'); window.location.href = '/jugadoresList';</script>";
            exit();
        }

        // Cargar técnicas y talentos del jugador
        $tecnicas = $jugadoresSupertecnicas->getTecnicasPorJugador($id_jugador);
        $talentos = $jugadoresTalentos->getTalentosPorJugador($id_jugador);

        // Combinar técnicas y talentos
        $jugador['tecnicas'] = [];
        foreach ($tecnicas as $tecnica) {
            $jugador['tecnicas'][] = [
                'id' => $tecnica['id_supertecnica'],
                'nombre' => $tecnica['nombre_tecnica'],
                'nivel' => $tecnica['nivel'],
                'tipo' => 'tecnica',
                'tabla' => 'supertecnicas'
            ];
        }
        foreach ($talentos as $talento) {
            $jugador['tecnicas'][] = [
                'id' => $talento['id_talento'],
                'nombre' => $talento['nombre_talento'],
                'nivel' => $talento['nivel'],
                'tipo' => 'talento',
                'tabla' => 'talentos'
            ];
        }

        if (!empty($_POST)) {
            // Procesar actualización
            $jugadorData = $_POST['jugador'] ?? [];
            
            // Procesar imagen si se subió una nueva (la anterior solo se borra
            // si la nueva se movió bien)
            $imagenActual = $jugador['imagen'];
            if (!empty($_FILES['imagen']['name'])) {
                $uploadDir = '../public/imgs/uploads/jugadores/';
                $subida = $this->subirImagen(
                    $uploadDir,
                    $_FILES['imagen']['name'],
                    $_FILES['imagen']['tmp_name'],
                    $jugadorData['nombre']
                );
                if ($subida !== null) {
                    $this->borrarImagen($uploadDir, $imagenActual);
                    $imagenActual = $subida;
                }
            }

            // Procesar estadísticas
            $estadisticas = [];
            if (!empty($jugadorData['estadisticas'])) {
                $estadisticas = $this->procesarEstadisticas($jugadorData['estadisticas']);
            }

            // Actualizar jugador
            $jugadores->fill([
                'id_jugador' => $id_jugador,
                'imagen' => $imagenActual,
                'nombre' => $jugadorData['nombre'],
                'posicion' => $jugadorData['posicion'],
                'elemento' => $jugadorData['elemento'],
                'precio_cantidad' => $jugadorData['precio_cantidad'],
                'precio_tipo' => $jugadorData['precio_tipo'],
                'edad' => $jugadorData['edad'],
                'genero' => $jugadorData['genero'],
                'PE' => $estadisticas['pe'] ?? $jugador['pe'],
                'PT' => $estadisticas['pt'] ?? $jugador['pt'],
                'tiro' => $estadisticas['tiro'] ?? $jugador['tiro'],
                'regate' => $estadisticas['regate'] ?? $jugador['regate'],
                'defensa' => $estadisticas['defensa'] ?? $jugador['defensa'],
                'control' => $estadisticas['control'] ?? $jugador['control'],
                'tecnica' => $estadisticas['tecnica'] ?? $jugador['tecnica'],
                'rapidez' => $estadisticas['rapidez'] ?? $jugador['rapidez'],
                'aguante' => $estadisticas['aguante'] ?? $jugador['aguante'],
                'suerte' => $estadisticas['suerte'] ?? $jugador['suerte'],
                'libertad' => $estadisticas['libertad'] ?? $jugador['libertad'],
            ]);

            try {
                $jugadores->edit();

                // Eliminar técnicas y talentos anteriores
                $this->eliminarTecnicasYTalentosJugador($id_jugador, $jugadoresSupertecnicas, $jugadoresTalentos);

                // Insertar nuevas técnicas y talentos
                $tecnicasData = $jugadorData['tecnicas'] ?? [];
                $this->insertarTecnicasYTalentosJugador($id_jugador, $tecnicasData, $jugadoresSupertecnicas, $jugadoresTalentos);

                echo "<script>
                    alert('Jugador actualizado correctamente.');
                    window.location.href = '/jugadoresList';
                </script>";
                exit();
            } catch (Exception $e) {
                echo "<script>alert('Error al actualizar jugador: " . $e->getMessage() . "');</script>";
            }
        }

        $data['jugador'] = $jugador;
        $this->renderHTML('../app/views/jugadores/edit.php', $data);
    }

    public function DeleteAction()
    {
        $jugadores = Jugadores::getInstancia();
        $jugadoresSupertecnicas = JugadoresSupertecnicas::getInstancia();
        $jugadoresTalentos = JugadoresTalentos::getInstancia();

        // Obtener ID del jugador desde la URL
        $id_jugador = explode('/', $_SERVER['REQUEST_URI'])[2];
        $id_jugador = (int)$id_jugador;

        // Verificar que el jugador existe
        $jugador = $jugadores->getJugadorCompleto($id_jugador);
        if (!$jugador) {
            echo "<script>alert('Jugador no encontrado'); window.location.href = '/jugadoresList';</script>";
            exit();
        }

        try {
            // Eliminar técnicas y talentos del jugador
            $this->eliminarTecnicasYTalentosJugador($id_jugador, $jugadoresSupertecnicas, $jugadoresTalentos);

            // Eliminar imagen si existe
            if (!empty($jugador['imagen'])) {
                $imagePath = '../public/imgs/uploads/jugadores/' . $jugador['imagen'];
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
            }

            // Eliminar jugador
            $jugadores->delete($id_jugador);

            echo "<script>
                alert('Jugador eliminado correctamente.');
                window.location.href = '/jugadoresList';
            </script>";
            exit();
        } catch (Exception $e) {
            echo "<script>
                alert('Error al eliminar jugador: " . $e->getMessage() . "');
                window.location.href = '/jugadoresList';
            </script>";
            exit();
        }
    }

    private function eliminarTecnicasYTalentosJugador($idJugador, $jugadoresSupertecnicas, $jugadoresTalentos)
    {
        // Obtener todas las técnicas del jugador para eliminarlas
        $tecnicas = $jugadoresSupertecnicas->getTecnicasPorJugador($idJugador);
        foreach ($tecnicas as $tecnica) {
            $jugadoresSupertecnicas->delete($tecnica['id_jugador_supertecnica']);
        }

        // Obtener todos los talentos del jugador para eliminarlos
        $talentos = $jugadoresTalentos->getTalentosPorJugador($idJugador);
        foreach ($talentos as $talento) {
            $jugadoresTalentos->delete($talento['id_jugador_talento']);
        }
    }

    public function ReembolsoAction()
    {
        $fichajes = Fichajes::getInstancia();

        // Obtener ID del jugador desde la URL
        $id_jugador = (int) explode('/', $_SERVER['REQUEST_URI'])[2];

        // Obtener ID del equipo del usuario actual
        $id_equipo = $_SESSION['user']['equipo'][0]['id_equipo'];

        // Reembolso atómico contra la BD (transacción + DELETE condicionado)
        $resultado = $fichajes->devolver($id_equipo, $id_jugador);

        // La sesión se refresca siempre con el saldo real de la BD
        $this->refrescarMonedasSesion($id_equipo);

        if ($resultado['success']) {
            $_SESSION['mensaje_reembolso'] = [
                'tipo' => 'exito',
                'texto' => "¡Reembolso exitoso! Has recibido de vuelta {$resultado['cantidad']} {$resultado['tipo_moneda']} en tu monedero."
            ];
        } else {
            $mensajes = [
                'not_found' => 'Jugador no encontrado',
                'not_owned' => 'Este jugador no pertenece a tu equipo',
                'unknown' => 'Error al procesar el reembolso'
            ];
            $_SESSION['mensaje_reembolso'] = [
                'tipo' => 'error',
                'texto' => $mensajes[$resultado['error']] ?? $mensajes['unknown']
            ];
        }

        header('Location: /');
        exit();
    }

    
    
}

?>