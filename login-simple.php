<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Connexion directe à la base
$pdo = new PDO('mysql:host=localhost;dbname=bibliogest', 'bibliogest_user', 'bibliogest123');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

session_name('BIBLIOGEST_SESSION');
session_start();

// Vérifier si le formulaire a été soumis
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($user) {
        echo "<pre>";
        echo "Utilisateur trouvé !\n";
        echo "Email: " . $user['email'] . "\n";
        echo "Rôle: " . $user['role'] . "\n";
        echo "Statut: " . $user['status'] . "\n";
        
        if (password_verify($password, $user['password'])) {
            echo "✅ Mot de passe valide !\n";
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['first_name'] . ' ' . $user['last_name'];
            $_SESSION['user_role'] = $user['role'];
            $success = "Connexion réussie ! Redirection...";
            echo "</pre>";
            header('refresh:2; url=/dashboard');
        } else {
            echo "❌ Mot de passe invalide !\n";
            echo "Hash stocké: " . $user['password'] . "\n";
            $error = "Mot de passe incorrect";
            echo "</pre>";
        }
    } else {
        echo "❌ Utilisateur non trouvé avec l'email: " . $email . "\n";
        $error = "Email non trouvé";
        echo "</pre>";
    }
}

// Afficher tous les utilisateurs pour debug
$stmt = $pdo->query("SELECT id, email, first_name, last_name, role, status FROM users");
$users = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Connexion Simple - BiblioGest</title>
    <style>
        body { font-family: Arial; background: #0f172a; color: white; padding: 50px; }
        .container { max-width: 500px; margin: 0 auto; background: #1e293b; padding: 30px; border-radius: 10px; }
        input { width: 100%; padding: 10px; margin: 10px 0; border-radius: 5px; border: none; }
        button { background: #6366f1; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; }
        .error { color: #ef4444; margin: 10px 0; }
        .success { color: #10b981; margin: 10px 0; }
        table { width: 100%; margin-top: 20px; border-collapse: collapse; }
        th, td { padding: 8px; text-align: left; border-bottom: 1px solid #334155; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Connexion BiblioGest</h2>
        
        <?php if($error): ?>
            <div class="error">❌ <?php echo $error; ?></div>
        <?php endif; ?>
        
        <?php if($success): ?>
            <div class="success">✅ <?php echo $success; ?></div>
        <?php endif; ?>
        
        <form method="POST">
            <input type="email" name="email" placeholder="Email" required value="admin@test.com">
            <input type="password" name="password" placeholder="Mot de passe" required value="admin123">
            <button type="submit">Se connecter</button>
        </form>
        
        <h3>Comptes disponibles :</h3>
        <table>
            <tr>
                <th>Email</th>
                <th>Nom</th>
                <th>Rôle</th>
                <th>Statut</th>
            </tr>
            <?php foreach($users as $u): ?>
            <tr>
                <td><?php echo $u['email']; ?></td>
                <td><?php echo $u['first_name'] . ' ' . $u['last_name']; ?></td>
                <td><?php echo $u['role']; ?></td>
                <td><?php echo $u['status']; ?></td>
            </tr>
            <?php endforeach; ?>
        </table>
    </div>
</body>
</html>