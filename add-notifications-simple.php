<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

define('ROOT', dirname(__FILE__));
require_once ROOT . '/config/database.php';
require_once ROOT . '/app/core/Model.php';
require_once ROOT . '/app/models/Notification.php';

session_start();

// Utiliser l'ID de l'utilisateur connecté
if (isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];
    echo "<h2>Ajout de notifications pour l'utilisateur connecté (ID: $userId)</h2>";
} else {
    echo "<p style='color:red'>Vous devez être connecté !</p>";
    echo "<a href='/login'>Se connecter</a>";
    exit;
}

$notification = new Notification();

// Notifications SANS émojis
$notifications = [
    ['success', 'Bienvenue', 'Bienvenue sur BiblioGest ! Decouvrez notre catalogue.', '/dashboard'],
    ['info', 'Nouveau livre', 'Un nouveau livre "Architecture moderne" vient d\'etre ajoute.', '/books'],
    ['warning', 'Retour imminent', 'Votre emprunt "Histoire des civilisations" se termine dans 3 jours.', '/loans'],
    ['danger', 'Rappel important', 'N\'oubliez pas de retourner vos livres avant la date limite.', '/loans'],
    ['success', 'Emprunt reussi', 'Vous avez emprunte "L\'intelligence artificielle pour les nuls".', '/loans'],
    ['info', 'Nouvelle fonctionnalite', 'Le chatbot est maintenant disponible ! Posez-lui vos questions.', '#'],
    ['warning', 'Retour en retard', 'Votre livre "Physique quantique" est en retard.', '/loans'],
    ['success', 'Objectif atteint', 'Vous avez lu 10 livres cette annee ! Felicitations !', '/dashboard'],
];

$count = 0;
foreach ($notifications as $notif) {
    $result = $notification->createNotification(
        $userId,
        $notif[0],
        $notif[1],
        $notif[2],
        $notif[3]
    );
    
    if ($result) {
        echo "<p>✅ " . $notif[1] . "</p>";
        $count++;
    } else {
        echo "<p>❌ Erreur: " . $notif[1] . "</p>";
    }
}

echo "<h3>Total: $count notifications ajoutees</h3>";
echo "<p><a href='/dashboard'>Retour au dashboard</a></p>";
?>
