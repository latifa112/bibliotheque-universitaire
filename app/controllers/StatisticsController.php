<?php
class StatisticsController extends Controller {
    
    public function index() {
        // Vérifier si l'utilisateur est connecté
        if (!$this->isLoggedIn()) {
            $this->redirect('/login');
        }
        
        // Vérifier si l'utilisateur est admin (sinon rediriger)
        if (!$this->isAdmin()) {
            $this->redirect('/dashboard');
            return;
        }
        
        $book = new Book();
        $loan = new Loan();
        $user = new User();
        
        // ========== STATISTIQUES DES LIVRES ==========
        $allBooks = $book->findAll();
        $totalBooks = count($allBooks);
        
        $availableBooks = 0;
        foreach ($allBooks as $b) {
            if ($b['available_quantity'] > 0) {
                $availableBooks++;
            }
        }
        
        // ========== STATISTIQUES DES UTILISATEURS ==========
        $allUsers = $user->findAll();
        $totalUsers = count($allUsers);
        $activeUsers = 0;
        foreach ($allUsers as $u) {
            if ($u['status'] == 'actif') {
                $activeUsers++;
            }
        }
        
        // ========== STATISTIQUES DES EMPRUNTS ==========
        $allLoans = $loan->getAllLoans();
        $totalLoans = count($allLoans);
        $activeLoans = 0;
        $returnedLoans = 0;
        $overdueLoans = 0;
        
        foreach ($allLoans as $l) {
            if ($l['status'] == 'en_cours') {
                $activeLoans++;
            } elseif ($l['status'] == 'retourne') {
                $returnedLoans++;
            } elseif ($l['status'] == 'en_retard') {
                $overdueLoans++;
            }
        }
        
        // ========== TOP 5 LIVRES ==========
        $topBooks = $loan->getMostBorrowedBooks(5);
        
        // ========== STATISTIQUES MENSUELLES ==========
        $monthlyStats = $loan->getMonthlyStats();
        
        // ========== STATISTIQUES PAR CATÉGORIE ==========
        $categoryStats = $book->getCategoryStats();
        
        // ========== TOP 5 LECTEURS ==========
        $topUsers = $user->getTopBorrowers(5);

        // Logs pour debug (optionnel)
        // error_log("MonthlyStats: " . print_r($monthlyStats, true));
        // error_log("CategoryStats: " . print_r($categoryStats, true));
        
        $this->viewWithSidebar('statistics/index', [
            'totalBooks' => $totalBooks,
            'availableBooks' => $availableBooks,
            'totalUsers' => $totalUsers,
            'activeUsers' => $activeUsers,
            'totalLoans' => $totalLoans,
            'activeLoans' => $activeLoans,
            'returnedLoans' => $returnedLoans,
            'overdueLoans' => $overdueLoans,
            'topBooks' => $topBooks,
            'monthlyStats' => $monthlyStats,
            'categoryStats' => $categoryStats,
            'topUsers' => $topUsers,
            'activePage' => 'statistics'
        ]);
    }
}
?>