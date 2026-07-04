<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Talento</title>
    <link href="/../css/output.css" rel="stylesheet">
    <link rel="icon" type="image/x-icon" href="imgs/src/logo.ico">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body class="bg-primary font-sans text-white min-h-screen pt-16">

    <?php include '../app/views/partials/navbar.php'; ?>

    <div class="container mx-auto px-4 py-8">
        <div class="max-w-4xl mx-auto">
            <h1 class="text-3xl font-bold mb-6 text-center">Editar Talento</h1>

            <div class="flex justify-center mb-6">
                <a href="/talentosList" class="bg-info px-6 py-3 bg-warning text-white rounded-lg hover:bg-yellow-600 transition-colors font-medium focus:ring-2 focus:ring-warning">
                    📋 Ver Lista de Talentos
                </a>
            </div>

            <form action="/talentosEdit/<?php echo (int) $data['talento']['id_talento']; ?>" method="POST" id="mainForm">
                <?php echo \App\Core\Csrf::field(); ?>
                <div id="formsContainer">
                    <!-- Primer formulario -->
                    <div class="talento-form bg-darker rounded-lg p-6 mb-6 shadow-[0_2px_8px_rgba(0,0,0,0.3)] relative" data-form-index="0">
                        
                        <div class="space-y-4">
                            <!-- Nombre -->
                            <div>
                                <label class="block text-sm font-medium text-white mb-2">
                                    Nombre del Talento <span class="text-error">*</span>
                                </label>
                                <input type="text" name="talentos[0][nombre]" required 
                                       placeholder="Ej: Mejor Garantía, Sangre, sudor y llanto..."
                                       class="w-full p-3 bg-gray text-white border border-light-gray rounded-lg focus:border-success focus:ring-1 focus:ring-success transition-colors placeholder-light-gray" value="<?php echo htmlspecialchars($data['talento']['nombre'] ?? ''); ?>">
                            </div>

                            <!-- Descripción -->
                            <div>
                                <label class="block text-sm font-medium text-white mb-2">
                                    Descripción (Opcional)
                                </label>
                                <textarea name="talentos[0][descripcion]" rows="4" 
                                          placeholder="Describe las características del talento, su poder, elementos que utiliza, etc..."
                                          class="w-full p-3 bg-gray text-white border border-light-gray rounded-lg focus:border-success focus:ring-1 focus:ring-success transition-colors placeholder-light-gray resize-vertical"><?php echo htmlspecialchars($data['talento']['descripcion'] ?? ''); ?></textarea>
                                <p class="text-xs text-light-gray mt-1">Puedes escribir una descripción detallada del talento y sus efectos</p>
                            </div>

                             <!-- Tipo -->
                            <div>
                                <label class="block text-sm font-medium text-white mb-2">
                                    Tipo de Talento <span class="text-error">*</span>
                                </label>
                                <select name="talentos[0][tipo]" required 
                                        class="w-full p-3 bg-gray text-white border border-light-gray rounded-lg focus:border-success focus:ring-1 focus:ring-success transition-colors">
                                    <option value="">Selecciona el tipo...</option>
                                    <option value="Mejora de capacidades" <?php echo ($data['talento']['tipo'] ?? '') === 'Mejora de capacidades' ? 'selected' : ''; ?>>Mejora de capacidades</option>
                                    <option value="Mejora de espíritu guerrero" <?php echo ($data['talento']['tipo'] ?? '') === 'Mejora de espíritu guerrero' ? 'selected' : ''; ?>>Mejora de espíritu guerrero</option>
                                    <option value="Mejora de ventajas" <?php echo ($data['talento']['tipo'] ?? '') === 'Mejora de ventajas' ? 'selected' : ''; ?>>Mejora de ventajas</option>
                                    <option value="Mejora de supertécnicas" <?php echo ($data['talento']['tipo'] ?? '') === 'Mejora de supertécnicas' ? 'selected' : ''; ?>>Mejora de supertécnicas</option>
                                    <option value="Mejora de recompensa" <?php echo ($data['talento']['tipo'] ?? '') === 'Mejora de recompensa' ? 'selected' : ''; ?>>Mejora de recompensa</option>
                                    <option value="Mejora de campo" <?php echo ($data['talento']['tipo'] ?? '') === 'Mejora de campo' ? 'selected' : ''; ?>>Mejora de campo</option>
                                    <option value="Mejora de duelos" <?php echo ($data['talento']['tipo'] ?? '') === 'Mejora de duelos' ? 'selected' : ''; ?>>Mejora de duelos</option>
                                </select>
                            </div>

                            <!-- Afecta -->
                            <div>
                                <label class="block text-sm font-medium text-white mb-2">
                                    A quién afecta <span class="text-error">*</span>
                                </label>
                                <select name="talentos[0][afecta]" required 
                                        class="w-full p-3 bg-gray text-white border border-light-gray rounded-lg focus:border-success focus:ring-1 focus:ring-success transition-colors">
                                    <option value="">Selecciona a quién afecta...</option>
                                    <option value="Usuario" <?php echo ($data['talento']['afecta'] ?? '') === 'Usuario' ? 'selected' : ''; ?>>Usuario</option>
                                    <option value="Oponente" <?php echo ($data['talento']['afecta'] ?? '') === 'Oponente' ? 'selected' : ''; ?>>Oponente</option>
                                    <option value="Companieros asistidos" <?php echo ($data['talento']['afecta'] ?? '') === 'Companieros asistidos' ? 'selected' : ''; ?>>Compañeros asistidos</option>
                                    <option value="Todo el equipo" <?php echo ($data['talento']['afecta'] ?? '') === 'Todo el equipo' ? 'selected' : ''; ?>>Todo el equipo</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Botones principales -->
                <div class="flex gap-4 pt-4 justify-center">
                    <button type="submit" 
                            class="px-8 py-3 bg-success text-white rounded-lg hover:bg-green-600 transition-colors font-medium focus:ring-2 focus:ring-success focus:ring-offset-2 focus:ring-offset-darker">
                        ⚡ Guardar
                    </button>
                    <a href="/talentosList" 
                       class="px-8 py-3 bg-gray text-white rounded-lg hover:bg-light-gray transition-colors font-medium text-center focus:ring-2 focus:ring-gray focus:ring-offset-2 focus:ring-offset-darker">
                        Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>


</body>
</html>