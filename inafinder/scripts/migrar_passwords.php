<?php
// Migración puntual: re-hashea las contraseñas guardadas en texto plano.
// Ejecutar una vez: docker exec -w /var/www/html inafinder_app php scripts/migrar_passwords.php
require __DIR__ . '/../bootstrap.php';

$dsn = 'mysql:host=' . DBHOST . ';dbname=' . DBNAME . ';port=' . DBPORT . ';charset=utf8mb4';
$pdo = new PDO($dsn, DBUSER, DBPASS, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);

$usuarios = $pdo->query("SELECT id_usuario, usuario, password FROM usuarios")->fetchAll(PDO::FETCH_ASSOC);
$migrados = 0;

foreach ($usuarios as $u) {
    $info = password_get_info((string) $u['password']);
    if (!empty($info['algo'])) {
        echo "  - {$u['usuario']}: ya tiene hash, se omite\n";
        continue;
    }
    $stmt = $pdo->prepare("UPDATE usuarios SET password = :password WHERE id_usuario = :id");
    $stmt->execute([
        'password' => password_hash($u['password'], PASSWORD_DEFAULT),
        'id' => $u['id_usuario'],
    ]);
    echo "  - {$u['usuario']}: migrada a hash\n";
    $migrados++;
}

echo "Contraseñas migradas: $migrados de " . count($usuarios) . "\n";
