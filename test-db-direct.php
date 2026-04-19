<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h2>Test direct base de données</h2>";

try {
    // Connexion directe avec root
    $pdo = new PDO('mysql:host=localhost;dbname=bibliogest', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "✅ Connexion OK<br>";
    
    // Vérifier si table users existe
    $stmt = $pdo->query("SHOW TABLES LIKE 'users'");
    if($stmt->rowCount() == 0) {
        echo "❌ La table 'users' n'existe pas!<br>";
    } else {
        echo "✅ Table 'users' existe<br>";
        
        // Lire les utilisateurs
        $stmt = $pdo->query("SELECT * FROM users");
        $users = $stmt->fetchAll();
        
        if(count($users) == 0) {
            echo "❌ Aucun utilisateur dans la table<br>";
            
            // Créer un utilisateur
            $hash = password_hash('admin123', PASSWORD_DEFAULT);
            $sql = "INSERT INTO users (name, email, password, role) VALUES ('Admin', 'admin@test.com', '$hash', 'admin')";
            $pdo->exec($sql);
            echo "✅ Utilisateur admin@test.com créé avec mot de passe admin123<br>";
        } else {
            echo "👥 " . count($users) . " utilisateur(s) trouvé(s):<br>";
            foreach($users as $u) {
                echo "- " . $u['email'] . " (" . $u['role'] . ")<br>";
            }
        }
        
        // Tester la connexion
        echo "<hr><h3>Test de connexion:</h3>";
        
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = 'admin@test.com'");
        $stmt->execute();
        $user = $stmt->fetch();
        
        if($user) {
            if(password_verify('admin123', $user['password'])) {
                echo "<span style='color:green;font-size:20px;'>✅ SUCCÈS: Connexion possible avec admin@test.com / admin123</span>";
            } else {
                echo "<span style='color:red;'>❌ ÉCHEC: Mot de passe incorrect</span>";
            }
        } else {
            echo "<span style='color:red;'>❌ Utilisateur admin@test.com non trouvé</span>";
        }
    }
    
} catch(PDOException $e) {
    echo "❌ Erreur: " . $e->getMessage();
}
?>