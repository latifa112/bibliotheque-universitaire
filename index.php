<?php
define('ROOT', dirname(__FILE__));
define('APP_ROOT', ROOT . '/app');

// Inclure la configuration de la base de données d'abord
require_once ROOT . '/config/database.php';

// Autoloader
spl_autoload_register(function($class) {
    $paths = [
        APP_ROOT . '/controllers/',
        APP_ROOT . '/models/',
        APP_ROOT . '/core/'
    ];
    
    foreach ($paths as $path) {
        $file = $path . $class . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});

// Démarrer la session
session_start();

// Définir le charset pour les réponses JSON
header('Content-Type: text/html; charset=utf-8');

require_once ROOT . '/app/core/Language.php';
Language::getInstance();

// Router
$router = new Router();

// Récupérer l'URL
$url = isset($_GET['url']) ? $_GET['url'] : '';

// Lancer le routage
$router->dispatch($url);
?>