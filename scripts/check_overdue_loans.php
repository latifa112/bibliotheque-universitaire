#!/usr/bin/env php
<?php
define('ROOT', dirname(__DIR__));
require_once ROOT . '/config/database.php';
require_once ROOT . '/app/core/Model.php';
require_once ROOT . '/app/models/Notification.php';

$db = Database::getInstance()->getConnection();
$notification = new Notification();

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

foreach ($overdueLoans as $loan) {
    $dueDate = new DateTime($loan['due_date']);
    $now = new DateTime();
    $interval = $now->diff($dueDate);
    $daysLate = $interval->days;
    
    // Mettre à jour le statut de l'emprunt
    $update = $db->prepare("UPDATE loans SET status = 'en_retard' WHERE id = ?");
    $update->execute([$loan['id']]);
    
    // Créer une notification avec createNotification
    $message = "Le livre '{$loan['title']}' de {$loan['author']} est en retard de {$daysLate} jour(s). Veuillez le retourner rapidement.";
    $notification->createNotification(
        $loan['user_id'],
        'danger',
        'Livre en retard',
        $message,
        '/loans'
    );
    
    // Envoyer un email (optionnel)
    $to = $loan['email'];
    $subject = "Rappel : Livre en retard - BiblioGest";
    $body = "Bonjour {$loan['first_name']} {$loan['last_name']},\n\n";
    $body .= "Le livre '{$loan['title']}' que vous avez emprunté est actuellement en retard de {$daysLate} jour(s).\n";
    $body .= "Date d'échéance : " . date('d/m/Y', strtotime($loan['due_date'])) . "\n";
    $body .= "Veuillez le retourner dès que possible.\n\n";
    $body .= "Cordialement,\nL'équipe BiblioGest";
    
    @mail($to, $subject, $body, "From: notifications@bibliogest.com");
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
    $notification->createNotification(
        $loan['user_id'],
        'warning',
        'Retour imminent',
        $message,
        '/loans'
    );
    
    // Envoyer un email
    $to = $loan['email'];
    $subject = "Rappel : Retour imminent - BiblioGest";
    $body = "Bonjour {$loan['first_name']} {$loan['last_name']},\n\n";
    $body .= "Le livre '{$loan['title']}' que vous avez emprunté doit être retourné dans {$daysLeft} jour(s).\n";
    $body .= "Date d'échéance : " . date('d/m/Y', strtotime($loan['due_date'])) . "\n\n";
    $body .= "Cordialement,\nL'équipe BiblioGest";
    
    @mail($to, $subject, $body, "From: notifications@bibliogest.com");
}

echo "Verification terminee. " . count($overdueLoans) . " retards, " . count($approachingLoans) . " rappels envoyes.\n";