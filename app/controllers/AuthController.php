<?php
class AuthController {
    private $pdo;
    
    public function __construct() {
        global $pdo;
        $this->pdo = $pdo;
        session_name('BIBLIOGEST_SESSION');
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }
    
    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Récupérer les données JSON
            $data = json_decode(file_get_contents('php://input'), true);
            if (!$data) {
                $data = $_POST;
            }
            
            $email = $data['email'] ?? '';
            $password = $data['password'] ?? '';
            
            $stmt = $this->pdo->prepare("SELECT * FROM users WHERE email = ?");
            $stmt->execute([$email]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['name'];
                $_SESSION['user_email'] = $user['email'];
                $_SESSION['user_role'] = $user['role'];
                $_SESSION['preferences'] = [
                    'theme' => $user['theme'] ?? 'dark',
                    'language' => $user['language'] ?? 'fr'
                ];
                
                echo json_encode(['success' => true, 'redirect' => '/dashboard']);
                return;
            } else {
                echo json_encode(['success' => false, 'message' => 'Email ou mot de passe incorrect']);
                return;
            }
        }
        
        // Afficher la page de login
        require_once APP_ROOT . '/views/auth/login.php';
    }
    
    public function dashboard() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit();
        }
        
        // Compter les statistiques
        $stmt = $this->pdo->query("SELECT COUNT(*) as total FROM books");
        $totalBooks = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
        
        $stmt = $this->pdo->query("SELECT COUNT(*) as total FROM loans WHERE status = 'en_cours'");
        $activeLoans = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
        
        require_once APP_ROOT . '/views/dashboard/index.php';
    }
    
    public function logout() {
        session_destroy();
        header('Location: /login');
        exit();
    }
}
?>