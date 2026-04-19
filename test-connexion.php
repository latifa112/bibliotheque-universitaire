<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Connexion directe à la base
try {
    $pdo = new PDO('mysql:host=localhost;dbname=bibliogest', 'bibliogest_user', 'bibliogest123');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "✅ Connexion base de données réussie<br><br>";
} catch (PDOException $e) {
    die("❌ Erreur connexion base: " . $e->getMessage());
}

session_start();

echo "<h2>🔍 Diagnostic complet de connexion</h2>";

// 1. Voir la structure de la table
echo "<h3>1. Structure de la table users :</h3>";
$stmt = $pdo->query("DESCRIBE users");
$columns = $stmt->fetchAll();
echo "<table border='1' cellpadding='5'>";
echo "<tr><th>Colonne</th><th>Type</th></tr>";
foreach ($columns as $col) {
    echo "<tr><td>" . $col['Field'] . "</td><td>" . $col['Type'] . "</td></tr>";
}
echo "</table><br>";

// 2. Afficher tous les utilisateurs
echo "<h3>2. Liste des utilisateurs :</h3>";
$stmt = $pdo->query("SELECT * FROM users");
$users = $stmt->fetchAll();

if (count($users) == 0) {
    echo "❌ Aucun utilisateur trouvé.<br>";
} else {
    echo "<table border='1' cellpadding='5'>";
    echo "<tr>";
    // Afficher les en-têtes
    if (count($users) > 0) {
        foreach (array_keys($users[0]) as $col) {
            echo "<th>" . $col . "</th>";
        }
    }
    echo "</tr>";
    
    foreach ($users as $user) {
        echo "<tr>";
        foreach ($user as $value) {
            echo "<td>" . htmlspecialchars(substr($value ?? '', 0, 30)) . "</td>";
        }
        echo "</tr>";
    }
    echo "</table>";
}

// 3. Tester la connexion avec admin@test.com
echo "<h3>3. Test de connexion :</h3>";

$email = 'admin@test.com';
$password = 'admin123';

$stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
$stmt->execute([$email]);
$user = $stmt->fetch();

if ($user) {
    echo "✅ Utilisateur trouvé : " . $user['email'] . "<br>";
    
    // Afficher le nom (peut être name, first_name, etc.)
    $userName = $user['name'] ?? ($user['first_name'] . ' ' . ($user['last_name'] ?? ''));
    echo "Nom: " . $userName . "<br>";
    echo "Rôle: " . ($user['role'] ?? 'N/A') . "<br>";
    
    if (password_verify($password, $user['password'])) {
        echo "<span style='color:green;font-size:18px;'>✅✅✅ MOT DE PASSE VALIDE ! ✅✅✅</span><br>";
        
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $userName;
        $_SESSION['user_role'] = $user['role'];
        
        echo "<br><a href='/dashboard' style='font-size:18px;'>➡️ Aller au tableau de bord</a>";
    } else {
        echo "<span style='color:red;font-size:18px;'>❌ MOT DE PASSE INVALIDE !</span><br>";
    }
} else {
    echo "❌ Utilisateur admin@test.com non trouvé.<br>";
    
    // Créer l'utilisateur avec les bonnes colonnes
    $hash = password_hash('admin123', PASSWORD_DEFAULT);
    
    // Vérifier quelles colonnes existent
    $columns = array_keys($users[0] ?? []);
    
    if (in_array('name', $columns)) {
        $stmt = $pdo->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
        $stmt->execute(['Administrateur', 'admin@test.com', $hash, 'admin']);
    } elseif (in_array('first_name', $columns)) {
        $stmt = $pdo->prepare("INSERT INTO users (first_name, last_name, email, password, role) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute(['Admin', 'Test', 'admin@test.com', $hash, 'admin']);
    }
    echo "✅ Utilisateur admin créé ! Rafraîchissez la page.<br>";
}
?>