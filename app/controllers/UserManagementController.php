<?php
class UserManagementController extends Controller {
    
    public function index() {
        if (!$this->isAdmin()) {
            $this->redirect('/dashboard');
        }
        
        $user = new User();
        $users = $user->getAllUsers();
        
        // Compter les statistiques
        $stats = [
            'total' => count($users),
            'active' => 0,
            'inactive' => 0,
            'admins' => 0,
            'students' => 0,
            'teachers' => 0
        ];
        
        foreach ($users as $u) {
            if ($u['status'] == 'actif') $stats['active']++;
            else $stats['inactive']++;
            
            if ($u['role'] == 'admin') $stats['admins']++;
            elseif ($u['role'] == 'etudiant') $stats['students']++;
            elseif ($u['role'] == 'professeur') $stats['teachers']++;
        }
        
        // Récupérer les données pour la sidebar
        $book = new Book();
        $allBooks = $book->findAll();
        $totalBooks = count($allBooks);
        
        $loan = new Loan();
        $userLoans = $loan->getUserLoans($_SESSION['user_id']);
        $activeLoans = 0;
        foreach ($userLoans as $l) {
            if ($l['status'] == 'en_cours') $activeLoans++;
        }
        
        $totalUsers = $stats['total'];
        
        $this->view('users/management', [
            'users' => $users,
            'stats' => $stats,
            'totalBooks' => $totalBooks,
            'activeLoans' => $activeLoans,
            'totalUsers' => $totalUsers
        ]);
    }
    
    public function toggleStatus() {
        if (!$this->isAdmin()) {
            $this->json(['success' => false, 'message' => 'Non autorisé']);
            return;
        }
        
        $data = json_decode(file_get_contents('php://input'), true);
        $userId = $data['user_id'] ?? null;
        $status = $data['status'] ?? null;
        
        $user = new User();
        $result = $user->updateStatus($userId, $status);
        
        if ($result) {
            $this->json(['success' => true, 'message' => 'Statut utilisateur mis à jour']);
        } else {
            $this->json(['success' => false, 'message' => 'Erreur lors de la mise à jour']);
        }
    }
    
    public function deleteUser() {
        if (!$this->isAdmin()) {
            $this->json(['success' => false, 'message' => 'Non autorisé']);
            return;
        }
        
        $data = json_decode(file_get_contents('php://input'), true);
        $userId = $data['user_id'] ?? null;
        
        $user = new User();
        $result = $user->delete($userId);
        
        if ($result) {
            $this->json(['success' => true, 'message' => 'Utilisateur supprimé']);
        } else {
            $this->json(['success' => false, 'message' => 'Erreur lors de la suppression']);
        }
    }
}
?>