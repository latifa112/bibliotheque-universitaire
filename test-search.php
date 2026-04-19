<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

define('ROOT', dirname(__FILE__));
require_once ROOT . '/config/database.php';
require_once ROOT . '/app/core/Model.php';
require_once ROOT . '/app/models/Book.php';

session_start();

echo "<h1>Test de recherche</h1>";

$book = new Book();
$keyword = "intelligence";
echo "<h2>Recherche du mot: $keyword</h2>";

$results = $book->search($keyword);

echo "<pre>";
print_r($results);
echo "</pre>";

echo "<p>Nombre de résultats: " . count($results) . "</p>";
?>
