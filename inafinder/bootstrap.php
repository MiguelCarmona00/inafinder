<?php 
    require 'vendor/autoload.php'; 
    use Dotenv\Dotenv;
    
    $dotenv = Dotenv::createImmutable(__DIR__);
    $dotenv->load();

    date_default_timezone_set('Europe/Madrid');
    define('DBHOST', $_ENV['DBHOST']);
    define('DBNAME', $_ENV['DBNAME']);
    define('DBUSER', $_ENV['DBUSER']);
    define('DBPASS', $_ENV['DBPASS']);
    define('DBPORT', $_ENV['DBPORT']);
    define('SMTP_SERVER', $_ENV['SMTP_SERVER']);
    define('SMTP_USER', $_ENV['SMTP_USER']);
    define('SMTP_PASS', $_ENV['SMTP_PASS']);

    // Escapa un valor para imprimirlo en HTML (atajo para las vistas)
    function e($valor)
    {
        return htmlspecialchars((string) ($valor ?? ''), ENT_QUOTES, 'UTF-8');
    }
?>
