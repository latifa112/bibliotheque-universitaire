<?php
class SettingsController extends Controller {
    
    public function index() {
        if (!$this->isLoggedIn()) {
            $this->redirect('/login');
        }
        
        // Récupérer les données utilisateur
        $userModel = new User();
        $userData = $userModel->findById($_SESSION['user_id']);
        
        // Préparer l'array $user avec toutes les clés nécessaires
        $user = [
            'first_name' => $userData['first_name'] ?? '',
            'last_name' => $userData['last_name'] ?? '',
            'email' => $userData['email'] ?? $_SESSION['user_email'] ?? '',
            'username' => $userData['username'] ?? $_SESSION['username'] ?? '',
            'created_at' => $userData['created_at'] ?? date('Y-m-d H:i:s')
        ];
        
        // Récupérer les statistiques
        $book = new Book();
        $allBooks = $book->findAll();
        $totalBooks = count($allBooks);
        
        $loan = new Loan();
        $userLoans = $loan->getUserLoans($_SESSION['user_id']);
        $activeLoans = 0;
        foreach ($userLoans as $l) {
            if ($l['status'] == 'en_cours') $activeLoans++;
        }
        $total_loans = count($userLoans);
        $active_loans = $activeLoans;
        
        $reservation = new Reservation();
        $userReservations = $reservation->getUserReservations($_SESSION['user_id']);
        $totalReservations = 0;
        foreach ($userReservations as $r) {
            if ($r['status'] == 'active') $totalReservations++;
        }
        
        $userModelAll = new User();
        $allUsers = $userModelAll->findAll();
        $totalUsers = count($allUsers);
        
        $preferences = $_SESSION['preferences'] ?? ['notifications' => true, 'language' => 'fr', 'theme' => 'dark'];
        
        $this->view('settings/index', [
            'user' => $user,
            'totalBooks' => $totalBooks,
            'activeLoans' => $activeLoans,
            'totalUsers' => $totalUsers,
            'totalReservations' => $totalReservations,
            'total_loans' => $total_loans,
            'active_loans' => $active_loans,
            'member_since' => $user['created_at'],
            'preferences' => $preferences
        ]);
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
            $_SESSION['user_name'] = ($data['first_name'] ?? '') . ' ' . ($data['last_name'] ?? '');
            $_SESSION['user_email'] = $data['email'] ?? '';
            
            $this->json(['success' => true, 'message' => 'Profil mis à jour avec succès']);
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
        $currentPassword = $data['current_password'] ?? '';
        $newPassword = $data['new_password'] ?? '';
        
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
    
    public function updatePreferences() {
        if (!$this->isLoggedIn()) {
            $this->json(['success' => false, 'message' => 'Non connecté']);
            return;
        }
        
        $data = json_decode(file_get_contents('php://input'), true);
        
        $_SESSION['preferences'] = [
            'notifications' => $data['notifications'] ?? true,
            'language' => $data['language'] ?? 'fr',
            'theme' => $data['theme'] ?? 'dark'
        ];
        
        $this->json([
            'success' => true, 
            'message' => 'Préférences enregistrées',
            'theme' => $data['theme'] ?? 'dark',
            'language' => $data['language'] ?? 'fr'
        ]);
    }
}
?>