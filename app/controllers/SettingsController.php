<?php
class SettingsController extends Controller {
    
public function index() {
    if (!$this->isLoggedIn()) {
        $this->redirect('/login');
    }
    
    // Récupérer le nombre total de livres
    $book = new Book();
    $allBooks = $book->findAll();
    $totalBooks = count($allBooks);
    
    // Récupérer les emprunts de l'utilisateur
    $loan = new Loan();
    $userLoans = $loan->getUserLoans($_SESSION['user_id']);
    $activeLoans = 0;
    foreach ($userLoans as $l) {
        if ($l['status'] == 'en_cours') $activeLoans++;
    }
    $totalLoans = count($userLoans);
    
    // Récupérer le nombre total d'utilisateurs
    $user = new User();
    $allUsers = $user->findAll();
    $totalUsers = count($allUsers);
    
    // Récupérer les réservations actives
    $reservation = new Reservation();
    $userReservations = $reservation->getUserReservations($_SESSION['user_id']);
    $totalReservations = 0;
    foreach ($userReservations as $r) {
        if ($r['status'] == 'active') $totalReservations++;
    }
    
    // Récupérer les données utilisateur pour la date d'inscription
    $userData = $user->findById($_SESSION['user_id']);
    
    $this->view('settings/index', [
        'totalBooks' => $totalBooks,
        'activeLoans' => $activeLoans,
        'totalUsers' => $totalUsers,
        'totalReservations' => $totalReservations,
        'total_loans' => $totalLoans,
        'active_loans' => $activeLoans,
        'user' => $userData,
        'member_since' => $userData['created_at'] ?? date('Y-m-d')
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
    
    public function updatePreferences() {
        if (!$this->isLoggedIn()) {
            $this->json(['success' => false, 'message' => 'Non connecté']);
            return;
        }
        
        $data = json_decode(file_get_contents('php://input'), true);
        
        if (isset($data['language']) && $data['language'] != ($_SESSION['preferences']['language'] ?? 'fr')) {
            $lang = Language::getInstance();
            $lang->setLanguage($data['language']);
        }
        
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