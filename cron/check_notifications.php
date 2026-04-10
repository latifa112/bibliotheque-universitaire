#!/usr/bin/env php
<?php
/**
 * Script CRON pour les notifications automatiques
 * À exécuter quotidiennement (tous les jours à 8h00)
 * 
 * Installation CRON:
 * 0 8 * * * php /var/www/html/bibliogest/cron/check_notifications.php
 */

error_reporting(E_ALL);
ini_set('display_errors', 0);

define('ROOT', dirname(__DIR__));
require_once ROOT . '/config/database.php';
require_once ROOT . '/app/core/Model.php';
require_once ROOT . '/app/models/Notification.php';

function logMessage($message) {
    $logFile = ROOT . '/logs/notifications.log';
    $timestamp = date('Y-m-d H:i:s');
    @file_put_contents($logFile, "[$timestamp] $message\n", FILE_APPEND);
    echo "[$timestamp] $message\n";
}

logMessage("=== DEBUT DU SCRIPT NOTIFICATIONS ===");

try {
    $db = Database::getInstance()->getConnection();
    $notification = new Notification();
    
    // ========== 1. NOTIFICATION POUR RETOUR IMMINENT (3 jours ou moins) ==========
    logMessage("Verification des retours imminents...");
    
    $stmt = $db->prepare("
        SELECT 
            l.*, 
            b.title as book_title,
            b.author,
            u.first_name,
            u.last_name,
            u.email,
            DATEDIFF(l.due_date, CURDATE()) as days_left
        FROM loans l
        JOIN books b ON l.book_id = b.id
        JOIN users u ON l.user_id = u.id
        WHERE l.status = 'en_cours' 
        AND l.due_date >= CURDATE()
        AND DATEDIFF(l.due_date, CURDATE()) <= 3
        AND DATEDIFF(l.due_date, CURDATE()) > 0
    ");
    $stmt->execute();
    $upcomingLoans = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    logMessage("Emprunts avec retour imminent: " . count($upcomingLoans));
    
    foreach ($upcomingLoans as $loan) {
        $daysLeft = $loan['days_left'];
        $message = "Le livre '" . $loan['book_title'] . "' doit etre retourne dans " . $daysLeft . " jour(s).";
        
        if ($daysLeft == 1) {
            $title = "Dernier jour ! Retour demain";
        } elseif ($daysLeft == 2) {
            $title = "Retour dans 2 jours";
        } else {
            $title = "Rappel : retour imminent";
        }
        
        $notification->createNotification(
            $loan['user_id'],
            'warning',
            $title,
            $message,
            "/loans"
        );
        
        logMessage("Notification envoyee a l'utilisateur " . $loan['user_id'] . " pour '" . $loan['book_title'] . "' (J-$daysLeft)");
    }
    
    // ========== 2. NOTIFICATION POUR RETARD (date depassee) ==========
    logMessage("Verification des retards...");
    
    $stmt = $db->prepare("
        SELECT 
            l.*, 
            b.title as book_title,
            b.author,
            u.first_name,
            u.last_name,
            u.email,
            DATEDIFF(CURDATE(), l.due_date) as days_late
        FROM loans l
        JOIN books b ON l.book_id = b.id
        JOIN users u ON l.user_id = u.id
        WHERE l.status = 'en_cours' 
        AND l.due_date < CURDATE()
    ");
    $stmt->execute();
    $overdueLoans = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    logMessage("Emprunts en retard: " . count($overdueLoans));
    
    foreach ($overdueLoans as $loan) {
        $daysLate = $loan['days_late'];
        
        // Mettre a jour le statut en 'en_retard' si necessaire
        if ($loan['status'] != 'en_retard') {
            $update = $db->prepare("UPDATE loans SET status = 'en_retard' WHERE id = ?");
            $update->execute([$loan['id']]);
        }
        
        $message = "Le livre '" . $loan['book_title'] . "' est en retard de " . $daysLate . " jour(s). Veuillez le retourner rapidement.";
        
        if ($daysLate >= 7) {
            $title = "RETARD CRITIQUE - " . $daysLate . " jours";
        } elseif ($daysLate >= 3) {
            $title = "Retard important - " . $daysLate . " jours";
        } else {
            $title = "Livre en retard";
        }
        
        $notification->createNotification(
            $loan['user_id'],
            'danger',
            $title,
            $message,
            "/loans"
        );
        
        logMessage("Notification de retard envoyee a l'utilisateur " . $loan['user_id'] . " pour '" . $loan['book_title'] . "' (+$daysLate jours)");
    }
    
    // ========== 3. NETTOYER LES ANCIENNES NOTIFICATIONS ==========
    $stmt = $db->prepare("
        DELETE FROM notifications 
        WHERE created_at < DATE_SUB(NOW(), INTERVAL 30 DAY)
    ");
    $stmt->execute();
    $deletedCount = $stmt->rowCount();
    
    if ($deletedCount > 0) {
        logMessage("Nettoyage: $deletedCount anciennes notifications supprimees");
    }
    
    logMessage("=== FIN DU SCRIPT ===");
    
} catch (Exception $e) {
    logMessage("ERREUR: " . $e->getMessage());
}
?>