<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

define('ROOT', dirname(__FILE__));
require_once ROOT . '/config/database.php';
require_once ROOT . '/app/core/Model.php';
require_once ROOT . '/app/models/Notification.php';

session_start();

// Utiliser l'ID de l'utilisateur connecté ou l'admin par défaut
if (isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];
    echo "<p>Utilisateur connecté ID: $userId</p>";
} else {
    $userId = 5; // ID de admin
    echo "<p>Utilisation de l'admin ID: $userId</p>";
}

$notification = new Notification ();

// Créer une notification de test
$result = $notification->createNotification(
    $userId,
    'success',
    'Test Notification',
    'Ceci est une notification de test pour vérifier que le système fonctionne.',
    '/dashboard'
);

if ($result) {
    echo "<p style='color:green'>✅ Notification créée avec succès pour l'utilisateur ID: $userId</p>";
} else {
    echo "<p style='color:red'>❌ Erreur lors de la création</p>";
}

// Afficher les notifications de cet utilisateur
$notifications = $notification->getUserNotifications($userId);
echo "<h2>Notifications pour l'utilisateur ID: $userId</h2>";
echo "<ul>";
foreach ($notifications as $notif) {
    echo "<li><strong>" . $notif['title'] . "</strong> - " . $notif['message'] . " (" . $notif['created_at'] . ")</li>";
}
echo "</ul>";
?>