#!/usr/bin/env php
<?php
define('ROOT', dirname(__DIR__));
require_once ROOT . '/app/core/Database.php';
require_once ROOT . '/app/models/Notification.php';

$db = Database::getInstance()->getConnection();

// Trouver les emprunts en retard
$stmt = $db->prepare("
    SELECT l.*, u.email, u.first_name, u.last_name, b.title, b.author
    FROM loans l
    JOIN users u ON l.user_id = u.id
    JOIN books b ON l.book_id = b.id
    WHERE l.status = 'en_cours' 
    AND l.due_date < CURDATE()
");
$stmt->execute();
$overdueLoans = $stmt->fetchAll(PDO::FETCH_ASSOC);

$notification = new Notification();

foreach ($overdueLoans as $loan) {
    $daysLate = (new DateTime())->diff(new DateTime($loan['due_date']))->days;
    
    // Créer une notification dans la base de données
    $message = "Le livre '{$loan['title']}' de {$loan['author']} est en retard de {$daysLate} jour(s). Veuillez le retourner rapidement.";
    $notification->addOverdueNotification($loan['user_id'], 'overdue', $message, $loan['id']);
    
    // Envoyer un email (optionnel)
    $to = $loan['email'];
    $subject = "Rappel : Livre en retard - BiblioGest";
    $body = "Bonjour {$loan['first_name']} {$loan['last_name']},\n\n";
    $body .= "Le livre '{$loan['title']}' que vous avez emprunté est actuellement en retard de {$daysLate} jour(s).\n";
    $body .= "Date d'échéance : " . date('d/m/Y', strtotime($loan['due_date'])) . "\n";
    $body .= "Veuillez le retourner dès que possible pour éviter des pénalités.\n\n";
    $body .= "Cordialement,\nL'équipe BiblioGest";
    
    mail($to, $subject, $body, "From: notifications@bibliogest.com");
}

// Trouver les emprunts qui approchent de l'échéance (3 jours ou moins)
$stmt = $db->prepare("
    SELECT l.*, u.email, u.first_name, u.last_name, b.title, b.author,
           DATEDIFF(l.due_date, CURDATE()) as days_left
    FROM loans l
    JOIN users u ON l.user_id = u.id
    JOIN books b ON l.book_id = b.id
    WHERE l.status = 'en_cours' 
    AND l.due_date >= CURDATE()
    AND DATEDIFF(l.due_date, CURDATE()) <= 3
    AND DATEDIFF(l.due_date, CURDATE()) > 0
");
$stmt->execute();
$approachingLoans = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($approachingLoans as $loan) {
    $daysLeft = $loan['days_left'];
    
    // Créer une notification
    $message = "Le livre '{$loan['title']}' doit être retourné dans {$daysLeft} jour(s).";
    $notification->addOverdueNotification($loan['user_id'], 'reminder', $message, $loan['id']);
    
    // Envoyer un email
    $to = $loan['email'];
    $subject = "Rappel : Retour imminent - BiblioGest";
    $body = "Bonjour {$loan['first_name']} {$loan['last_name']},\n\n";
    $body .= "Le livre '{$loan['title']}' que vous avez emprunté doit être retourné dans {$daysLeft} jour(s).\n";
    $body .= "Date d'échéance : " . date('d/m/Y', strtotime($loan['due_date'])) . "\n\n";
    $body .= "Cordialement,\nL'équipe BiblioGest";
    
    mail($to, $subject, $body, "From: notifications@bibliogest.com");
}

echo "Vérification terminée. " . count($overdueLoans) . " retards, " . count($approachingLoans) . " rappels envoyés.\n";