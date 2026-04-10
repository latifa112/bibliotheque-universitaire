<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

define('ROOT', dirname(__FILE__));
require_once ROOT . '/config/database.php';
require_once ROOT . '/app/core/Model.php';
require_once ROOT . '/app/models/Reservation.php';

session_start();

echo "<h1>🔧 Test d'annulation de réservation</h1>";

if (!isset($_SESSION['user_id'])) {
    echo "<p style='color:orange'>⚠️ Vous n'êtes pas connecté !</p>";
    echo "<a href='/login' style='background:#6366f1; color:white; padding:10px 20px; text-decoration:none; border-radius:10px;'>🔐 Se connecter</a>";
    exit;
}

$userId = $_SESSION['user_id'];
echo "<p>✅ Utilisateur connecté ID: <strong>$userId</strong></p>";
echo "<p>Rôle: <strong>" . ($_SESSION['user_role'] ?? 'inconnu') . "</strong></p>";

$reservation = new Reservation();

// Récupérer toutes les réservations de l'utilisateur
$reservations = $reservation->getUserReservations($userId);

echo "<h2>📋 Vos réservations :</h2>";

if (empty($reservations)) {
    echo "<p style='color:orange'>Aucune réservation trouvée.</p>";
    echo "<p>👉 Allez d'abord réserver un livre dans le <a href='/books'>catalogue</a></p>";
} else {
    echo "<table border='1' cellpadding='10' style='border-collapse:collapse; width:100%;'>";
    echo "<tr style='background:#333; color:white;'>";
    echo "<th>ID</th><th>Livre</th><th>Auteur</th><th>Statut</th><th>Date réservation</th><th>Action</th>";
    echo "</tr>";
    
    foreach ($reservations as $r) {
        $statusClass = $r['status'] == 'active' ? '🟢 Actif' : ($r['status'] == 'cancelled' ? '🔴 Annulé' : '⚪ Expiré');
        echo "<tr>";
        echo "<td>{$r['id']}</td>";
        echo "<td><strong>" . htmlspecialchars($r['title']) . "</strong></td>";
        echo "<td>" . htmlspecialchars($r['author']) . "</td>";
        echo "<td>{$statusClass}</td>";
        echo "<td>" . date('d/m/Y H:i', strtotime($r['reservation_date'])) . "</td>";
        echo "<td>";
        if ($r['status'] == 'active') {
            echo "<button onclick='cancelReservation({$r['id']})' style='background:#ef4444; color:white; border:none; padding:5px 10px; border-radius:5px; cursor:pointer;'>❌ Annuler</button>";
        } else {
            echo "<span style='color:gray'>Non annulable</span>";
        }
        echo "</td>";
        echo "</tr>";
    }
    echo "</table>";
}

// Formulaire de test manuel
echo "<h2>✏️ Test manuel :</h2>";
echo "<form method='POST' style='margin-top:20px; padding:15px; background:#1e293b; border-radius:10px;'>";
echo "<label>ID de la réservation à annuler : </label>";
echo "<input type='number' name='reservation_id' required style='padding:8px; border-radius:5px; border:1px solid #ccc;'>";
echo "<button type='submit' name='action' value='cancel' style='background:#ef4444; color:white; padding:8px 15px; border:none; border-radius:5px; margin-left:10px; cursor:pointer;'>Annuler</button>";
echo "</form>";

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] == 'cancel') {
    $reservationId = $_POST['reservation_id'] ?? null;
    if ($reservationId) {
        echo "<h3>📝 Résultat :</h3>";
        $result = $reservation->cancelReservation($reservationId, $userId);
        if ($result) {
            echo "<p style='color:green; background:#064e3b; padding:10px; border-radius:5px;'>✅ SUCCÈS : La réservation ID $reservationId a été annulée !</p>";
            echo "<script>setTimeout(() => location.reload(), 1500);</script>";
        } else {
            echo "<p style='color:red; background:#7f1d1d; padding:10px; border-radius:5px;'>❌ ÉCHEC : Impossible d'annuler la réservation ID $reservationId</p>";
            echo "<p>Vérifiez que :</p>";
            echo "<ul><li>La réservation existe</li><li>Elle vous appartient</li><li>Son statut est 'active'</li></ul>";
        }
    }
}

// Afficher les logs d'erreur
echo "<h3>📄 Logs d'erreur PHP :</h3>";
echo "<pre style='background:#0f172a; padding:10px; border-radius:5px; overflow:auto; max-height:200px;'>";
$logFile = ini_get('error_log');
if (file_exists($logFile)) {
    $logs = shell_exec("tail -20 " . escapeshellarg($logFile));
    echo htmlspecialchars($logs);
} else {
    echo "Aucun log trouvé. Vérifiez votre configuration PHP.";
}
echo "</pre>";
?>

<script>
function cancelReservation(id) {
    if (confirm('Annuler cette réservation ?')) {
        // Créer un formulaire invisible
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '';
        
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'reservation_id';
        input.value = id;
        
        const actionInput = document.createElement('input');
        actionInput.type = 'hidden';
        actionInput.name = 'action';
        actionInput.value = 'cancel';
        
        form.appendChild(input);
        form.appendChild(actionInput);
        document.body.appendChild(form);
        form.submit();
    }
}
</script>

<style>
    body { font-family: Arial, sans-serif; max-width: 1200px; margin: 0 auto; padding: 20px; background: #0f172a; color: white; }
    input, button { font-size: 14px; }
    table { background: #1e293b; }
    td, th { padding: 10px; }
    a { color: #6366f1; text-decoration: none; }
    a:hover { text-decoration: underline; }
</style>