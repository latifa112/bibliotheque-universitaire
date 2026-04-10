<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

define('ROOT', dirname(__FILE__));
require_once ROOT . '/config/database.php';
require_once ROOT . '/app/core/Model.php';
require_once ROOT . '/app/models/Notification.php';

echo "<h1>Test simple de notification</h1>";

$notification = new Notification();

$result = $notification->createNotification(
    7,  // Utilisateur qui a réservé (ID 7)
    'success',
    'Livre disponible',  // Sans emoji
    'Le livre que vous avez reserve est maintenant disponible. Connectez-vous pour l emprunter.',
    '/books/show/1'
);

if ($result) {
    echo "<p style='color:green'>✅ Notification creee avec succes</p>";
} else {
    echo "<p style='color:red'>❌ Erreur lors de la creation</p>";
}

// Afficher les notifications pour l'utilisateur 7
$db = Database::getInstance()->getConnection();
$stmt = $db->prepare("SELECT id, title, message, created_at FROM notifications WHERE user_id = 7 ORDER BY id DESC LIMIT 5");
$stmt->execute();
$notifications = $stmt->fetchAll();

echo "<h2>Notifications pour l'utilisateur 7</h2>";
foreach ($notifications as $n) {
    echo "<div style='border:1px solid #ccc; padding:10px; margin:5px; border-radius:5px'>";
    echo "<strong>{$n['title']}</strong><br>";
    echo "{$n['message']}<br>";
    echo "<small>{$n['created_at']}</small>";
    echo "</div>";
}
?>