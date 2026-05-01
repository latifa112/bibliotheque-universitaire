<?php
require_once ROOT . '/app/core/Mailer.php';

class UserController extends Controller {
    
public function login() {
    // Vérifier si déjà connecté
    if (isset($_SESSION['user_id'])) {
        header('Location: /dashboard');
        exit();
    }
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $input = json_decode(file_get_contents('php://input'), true);
        $email = $input['email'] ?? $_POST['email'] ?? '';
        $password = $input['password'] ?? $_POST['password'] ?? '';
        
        $user = new User();
        $userData = $user->authenticate($email, $password);
        
        if ($userData) {
            $_SESSION['user_id'] = $userData['id'];
            $_SESSION['user_name'] = ($userData['first_name'] ?? '') . ' ' . ($userData['last_name'] ?? '');
            $_SESSION['user_role'] = $userData['role'];
            $_SESSION['user_email'] = $userData['email'];
            $_SESSION['username'] = $userData['username'];
            
            echo json_encode(['success' => true, 'redirect' => '/dashboard']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Email ou mot de passe incorrect']);
        }
        exit();
    }
    
    // Afficher la page de login
    include ROOT . '/app/views/auth/login.php';
    exit();
}
    
    public function register() {
        if ($this->isLoggedIn()) {
            $this->redirect('/dashboard');
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $input = json_decode(file_get_contents('php://input'), true);
            
            // Récupérer le rôle - défaut étudiant si non spécifié
            $role = 'etudiant';
            if (isset($input['role']) && !empty($input['role'])) {
                if ($input['role'] === 'professeur') {
                    $role = 'professeur';
                } else {
                    $role = 'etudiant';
                }
            }
            
            // Générer un username basé sur l'email
            $username = explode('@', $input['email'])[0];
            $user = new User();
            $baseUsername = $username;
            $counter = 1;
            while ($user->findByUsername($username)) {
                $username = $baseUsername . $counter;
                $counter++;
            }
            
            $data = [
                'username' => $username,
                'email' => $input['email'] ?? $_POST['email'] ?? '',
                'password' => $input['password'] ?? $_POST['password'] ?? '',
                'first_name' => $input['first_name'] ?? $_POST['first_name'] ?? '',
                'last_name' => $input['last_name'] ?? $_POST['last_name'] ?? '',
                'role' => $role,
                'field_of_study' => $input['field_of_study'] ?? null
            ];
            
            if ($data['password'] !== ($input['confirm_password'] ?? $_POST['confirm_password'] ?? '')) {
                echo json_encode(['success' => false, 'message' => 'Les mots de passe ne correspondent pas']);
                exit;
            }
            
            if ($user->findByEmail($data['email'])) {
                echo json_encode(['success' => false, 'message' => 'Cet email est déjà utilisé']);
                exit;
            }
            
            if ($user->createUser($data)) {
                echo json_encode(['success' => true, 'redirect' => '/login']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Erreur lors de l\'inscription']);
            }
            exit;
        }
        
        include ROOT . '/app/views/auth/register.php';
        exit;
    }

    public function logout() {
        // Sauvegarder la langue avant de détruire la session
        $lang = $_SESSION['lang'] ?? $_COOKIE['bibliogest_lang'] ?? 'fr';
        
        // Détruire la session
        session_destroy();
        
        // Recréer une nouvelle session avec la langue sauvegardée
        session_start();
        $_SESSION['lang'] = $lang;
        
        $this->redirect('/login');
    }

public function profile() {
    if (!$this->isLoggedIn()) {
        $this->redirect('/login');
    }
    
    $user = new User();
    $userData = $user->findById($_SESSION['user_id']);
    
    $loan = new Loan();
    $loans = $loan->getUserLoans($_SESSION['user_id']);
    $total_loans = count($loans);
    $active_loans = 0;
    foreach ($loans as $l) {
        if ($l['status'] == 'en_cours') $active_loans++;
    }
    
    $this->viewWithSidebar('users/profile', [
        'user' => $userData,
        'total_loans' => $total_loans,
        'active_loans' => $active_loans,
        'activePage' => 'profile'
    ]);
}
    
    public function exportData() {
        if (!$this->isLoggedIn()) {
            $this->redirect('/login');
        }
        
        $user = new User();
        $userData = $user->findById($_SESSION['user_id']);
        
        $loan = new Loan();
        $loans = $loan->getUserLoans($_SESSION['user_id']);
        
        $reservation = new Reservation();
        $reservations = $reservation->getUserReservations($_SESSION['user_id']);
        
        $data = [
            'user' => [
                'id' => $userData['id'],
                'username' => $userData['username'],
                'email' => $userData['email'],
                'first_name' => $userData['first_name'],
                'last_name' => $userData['last_name'],
                'role' => $userData['role'],
                'created_at' => $userData['created_at']
            ],
            'loans' => $loans,
            'reservations' => $reservations,
            'export_date' => date('Y-m-d H:i:s')
        ];
        
        $json = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        
        header('Content-Type: application/json');
        header('Content-Disposition: attachment; filename="bibliogest_export_' . $_SESSION['user_id'] . '_' . date('Ymd') . '.json"');
        echo $json;
        exit;
    }
    
    public function updateProfile() {
        if (!$this->isLoggedIn()) {
            $this->json(['success' => false, 'message' => 'Non connecté']);
            return;
        }
        
        $data = json_decode(file_get_contents('php://input'), true);
        
        $user = new User();
        $result = $user->updateProfile($_SESSION['user_id'], $data);
        
        if ($result) {
            $_SESSION['user_name'] = $data['first_name'] . ' ' . $data['last_name'];
            $_SESSION['user_email'] = $data['email'];
            
            $this->json(['success' => true, 'message' => 'Profil mis à jour']);
        } else {
            $this->json(['success' => false, 'message' => 'Erreur lors de la mise à jour']);
        }
    }
    
    public function updatePassword() {
        if (!$this->isLoggedIn()) {
            $this->json(['success' => false, 'message' => 'Non connecté']);
            return;
        }
        
        $data = json_decode(file_get_contents('php://input'), true);
        $currentPassword = $data['current_password'];
        $newPassword = $data['new_password'];
        
        $user = new User();
        $userData = $user->findById($_SESSION['user_id']);
        
        if (!password_verify($currentPassword, $userData['password'])) {
            $this->json(['success' => false, 'message' => 'Mot de passe actuel incorrect']);
            return;
        }
        
        if (strlen($newPassword) < 6) {
            $this->json(['success' => false, 'message' => 'Le mot de passe doit contenir au moins 6 caractères']);
            return;
        }
        
        if ($user->changePassword($_SESSION['user_id'], $newPassword)) {
            $this->json(['success' => true, 'message' => 'Mot de passe modifié avec succès']);
        } else {
            $this->json(['success' => false, 'message' => 'Erreur lors du changement']);
        }
    }
    
    public function forgotPassword() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = json_decode(file_get_contents('php://input'), true);
            $email = $data['email'] ?? '';
            
            $user = new User();
            $userData = $user->findByEmail($email);
            
            if (!$userData) {
                echo json_encode(['success' => false, 'message' => 'Aucun compte trouvé avec cet email']);
                exit;
            }
            
            $token = bin2hex(random_bytes(32));
            $expires = date('Y-m-d H:i:s', strtotime('+1 hour'));
            
            $result = $user->setResetToken($userData['id'], $token, $expires);
            
            if (!$result) {
                echo json_encode(['success' => false, 'message' => 'Erreur lors de la génération du token']);
                exit;
            }
            
            $resetLink = "http://localhost/reset-password?token=" . $token;
            $name = $userData['first_name'] . ' ' . $userData['last_name'];
            
            $mailer = new Mailer();
            $mailer->sendResetPassword($email, $name, $resetLink);
            
            echo json_encode([
                'success' => true, 
                'message' => 'Un lien de réinitialisation a été envoyé à votre adresse email.',
                'reset_link' => $resetLink
            ]);
            exit;
        }
        
        include ROOT . '/app/views/auth/forgot-password.php';
        exit;
    }
    
    public function resetPassword() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = json_decode(file_get_contents('php://input'), true);
            $token = $data['token'] ?? '';
            $newPassword = $data['password'] ?? '';
            
            $user = new User();
            $userData = $user->findByResetToken($token);
            
            if (!$userData) {
                echo json_encode(['success' => false, 'message' => 'Lien invalide ou expiré']);
                exit;
            }
            
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
            $result = $user->update($userData['id'], ['password' => $hashedPassword]);
            
            if ($result) {
                $user->clearResetToken($userData['id']);
                echo json_encode(['success' => true, 'message' => 'Mot de passe réinitialisé avec succès']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Erreur lors de la réinitialisation']);
            }
            exit;
        }
        
        include ROOT . '/app/views/auth/reset-password.php';
        exit;
    }
    //supprimer le compte 
public function deleteAccount() {
    header('Content-Type: application/json');
    
    if (!$this->isLoggedIn()) {
        echo json_encode(['success' => false, 'message' => 'Non connecté']);
        return;
    }
    
    $userId = $_SESSION['user_id'];
    $loan = new Loan();
    $userLoans = $loan->getUserLoans($userId);
    
    // Vérifier s'il y a des emprunts en cours
    $hasActiveLoans = false;
    $activeLoansList = [];
    foreach ($userLoans as $loanItem) {
        if ($loanItem['status'] == 'en_cours') {
            $hasActiveLoans = true;
            $activeLoansList[] = $loanItem['title'];
        }
    }
    
    if ($hasActiveLoans) {
        $booksList = implode(', ', $activeLoansList);
        echo json_encode(['success' => false, 'message' => '❌ Vous devez retourner les livres suivants avant de supprimer votre compte : ' . $booksList]);
        return;
    }
    
    // Vérifier s'il y a des réservations actives
    $reservationModel = new Reservation();
    $userReservations = $reservationModel->getUserReservations($userId);
    $hasActiveReservations = false;
    foreach ($userReservations as $res) {
        if ($res['status'] == 'active') {
            $hasActiveReservations = true;
            break;
        }
    }
    
    if ($hasActiveReservations) {
        echo json_encode(['success' => false, 'message' => '❌ Vous devez annuler toutes vos réservations avant de supprimer votre compte']);
        return;
    }
    
    $db = Database::getInstance()->getConnection();
    
    try {
        $db->beginTransaction();
        
        // Supprimer les notifications
        $stmt = $db->prepare("DELETE FROM notifications WHERE user_id = :user_id");
        $stmt->execute([':user_id' => $userId]);
        
        // Supprimer les réservations
        $stmt = $db->prepare("DELETE FROM reservations WHERE user_id = :user_id");
        $stmt->execute([':user_id' => $userId]);
        
        // Supprimer les emprunts (déjà retournés)
        $stmt = $db->prepare("DELETE FROM loans WHERE user_id = :user_id");
        $stmt->execute([':user_id' => $userId]);
        
        // Supprimer l'utilisateur
        $user = new User();
        $result = $user->delete($userId);
        
        if ($result) {
            $db->commit();
            session_destroy();
            echo json_encode(['success' => true, 'message' => '✅ Votre compte a été supprimé avec succès']);
        } else {
            $db->rollBack();
            echo json_encode(['success' => false, 'message' => '❌ Erreur lors de la suppression du compte']);
        }
        
    } catch (Exception $e) {
        $db->rollBack();
        echo json_encode(['success' => false, 'message' => '❌ Erreur : ' . $e->getMessage()]);
    }
}
    // ========== FIN DE LA NOUVELLE MÉTHODE ==========
    
    private function countActiveLoans($loans) {
        $count = 0;
        foreach ($loans as $loan) {
            if ($loan['status'] == 'en_cours') {
                $count++;
            }
        }
        return $count;
    }

    // méthode de vérification 
    public function checkBeforeDelete() {
    header('Content-Type: application/json');
    
    if (!$this->isLoggedIn()) {
        echo json_encode(['can_delete' => false, 'message' => 'Non connecté']);
        return;
    }
    
    $userId = $_SESSION['user_id'];
    $loan = new Loan();
    $userLoans = $loan->getUserLoans($userId);
    
    $hasActiveLoans = false;
    foreach ($userLoans as $loanItem) {
        if ($loanItem['status'] == 'en_cours') {
            $hasActiveLoans = true;
            break;
        }
    }
    
    if ($hasActiveLoans) {
        echo json_encode(['can_delete' => false, 'message' => '❌ Vous devez retourner tous vos livres avant de supprimer votre compte']);
        return;
    }
    
    $reservationModel = new Reservation();
    $userReservations = $reservationModel->getUserReservations($userId);
    $hasActiveReservations = false;
    foreach ($userReservations as $res) {
        if ($res['status'] == 'active') {
            $hasActiveReservations = true;
            break;
        }
    }
    
    if ($hasActiveReservations) {
        echo json_encode(['can_delete' => false, 'message' => '❌ Vous devez annuler toutes vos réservations avant de supprimer votre compte']);
        return;
    }
    
    echo json_encode(['can_delete' => true, 'message' => 'ok']);
}
}
?>
