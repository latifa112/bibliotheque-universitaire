<?php
/**
 * Script CRON pour détecter les emprunts en retard
 * À exécuter quotidiennement (ex: tous les jours à 00h00)
 * 
 * Installation CRON:
 * 0 0 * * * php /var/www/html/bibliogest/cron/check_overdue.php
 */

require_once dirname(__DIR__) . '/config/config.php';
require_once ROOT . '/app/core/Database.php';
require_once ROOT . '/app/models/Notification.php';

// Fonction de log
function logMessage($message) {
    $logFile = dirname(__DIR__) . '/logs/overdue.log';
    $timestamp = date('Y-m-d H:i:s');
    file_put_contents($logFile, "[$timestamp] $message\n", FILE_APPEND);
}

logMessage("=== DÉBUT DU CRON DE DÉTECTION DES RETARDS ===");

try {
    $db = Database::getInstance()->getConnection();
    
    // 1. Trouver les emprunts en retard (date d'échéance dépassée)
    $stmt = $db->prepare("
        SELECT l.*, u.email, u.first_name, u.last_name, b.title, b.id as book_id
        FROM loans l
        JOIN users u ON l.user_id = u.id
        JOIN books b ON l.book_id = b.id
        WHERE l.status = 'en_cours' AND l.due_date < NOW()
    ");
    $stmt->execute();
    $overdueLoans = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    logMessage("Nombre d'emprunts en retard détectés : " . count($overdueLoans));
    
    $notification = new Notification();
    
    foreach ($overdueLoans as $loan) {
        // Calculer le nombre de jours de retard
        $dueDate = new DateTime($loan['due_date']);
        $now = new DateTime();
        $interval = $now->diff($dueDate);
        $daysLate = $interval->days;
        
        // Mettre à jour le statut de l'emprunt
        $update = $db->prepare("UPDATE loans SET status = 'en_retard' WHERE id = ?");
        $update->execute([$loan['id']]);
        
        // Créer une notification pour l'utilisateur
        $message = "⚠️ Le livre '{$loan['title']}' est en retard de {$daysLate} jour(s). Veuillez le retourner rapidement pour éviter des pénalités.";
        
        $notification->createNotification(
            $loan['user_id'],
            'overdue',
            '⚠️ Livre en retard',
            $message,
            "/loans"
        );
        
        logMessage("Notification envoyée à l'utilisateur ID {$loan['user_id']} pour le livre '{$loan['title']}' (retard: {$daysLate} jours)");
    }
    
    // 2. Nettoyer les réservations expirées (plus de 7 jours)
    $stmt = $db->prepare("
        UPDATE reservations 
        SET status = 'expired' 
        WHERE status = 'active' AND expiry_date < NOW()
    ");
    $stmt->execute();
    $expiredCount = $stmt->rowCount();
    
    if ($expiredCount > 0) {
        logMessage("Réservations expirées nettoyées : {$expiredCount}");
    }
    
    logMessage("=== FIN DU CRON ===");
    
} catch (Exception $e) {
    logMessage("ERREUR : " . $e->getMessage());
}
?>