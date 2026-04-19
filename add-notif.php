<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
define('ROOT', dirname(__FILE__));
require_once ROOT . '/config/database.php';
require_once ROOT . '/app/core/Model.php';
require_once ROOT . '/app/models/Notification.php';

session_start();

if (!isset($_SESSION['user_id'])) {
    echo "Connectez-vous d'abord !";
    exit;
}

$userId = $_SESSION['user_id'];
$notification = new Notification();

$notifications = [
    ['success', 'Bienvenue', 'Bienvenue sur BiblioGest !', '/dashboard'],
    ['info', 'Nouveau livre', 'Un nouveau livre a ete ajoute.', '/books'],
    ['warning', 'Retour imminent', 'Votre emprunt se termine dans 3 jours.', '/loans'],
];

foreach ($notifications as $notif) {
    $notification->createNotification($userId, $notif[0], $notif[1], $notif[2], $notif[3]);
}

echo "Notifications ajoutees ! <a href='/dashboard'>Retour</a>";
?>
