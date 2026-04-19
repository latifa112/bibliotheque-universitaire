<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

define('ROOT', dirname(__FILE__));
require_once ROOT . '/config/database.php';
require_once ROOT . '/app/core/Model.php';
require_once ROOT . '/app/models/Book.php';
require_once ROOT . '/app/models/Loan.php';
require_once ROOT . '/app/models/User.php';

echo "<h1>Test des statistiques</h1>";

// Livres
$book = new Book();
$allBooks = $book->findAll();
echo "<h2>Livres</h2>";
echo "Total livres: " . count($allBooks) . "<br>";

$availableBooks = 0;
foreach ($allBooks as $b) {
    if ($b['available_quantity'] > 0) $availableBooks++;
}
echo "Livres disponibles: " . $availableBooks . "<br>";

// Utilisateurs
$user = new User();
$allUsers = $user->findAll();
echo "<h2>Utilisateurs</h2>";
echo "Total utilisateurs: " . count($allUsers) . "<br>";

$activeUsers = 0;
foreach ($allUsers as $u) {
    if ($u['status'] == 'actif') $activeUsers++;
}
echo "Utilisateurs actifs: " . $activeUsers . "<br>";

// Emprunts
$loan = new Loan();
$allLoans = $loan->getAllLoans();
echo "<h2>Emprunts</h2>";
echo "Total emprunts: " . count($allLoans) . "<br>";

$activeLoans = 0;
$returnedLoans = 0;
$overdueLoans = 0;

foreach ($allLoans as $l) {
    if ($l['status'] == 'en_cours') $activeLoans++;
    elseif ($l['status'] == 'retourne') $returnedLoans++;
    elseif ($l['status'] == 'en_retard') $overdueLoans++;
}
echo "Emprunts en cours: " . $activeLoans . "<br>";
echo "Emprunts retournés: " . $returnedLoans . "<br>";
echo "Emprunts en retard: " . $overdueLoans . "<br>";

// Top livres
$topBooks = $loan->getMostBorrowedBooks(5);
echo "<h2>Top 5 livres</h2>";
echo "<pre>";
print_r($topBooks);
echo "</pre>";

// Top utilisateurs
$topUsers = $user->getTopBorrowers(5);
echo "<h2>Top 5 utilisateurs</h2>";
echo "<pre>";
print_r($topUsers);
echo "</pre>";
?>
