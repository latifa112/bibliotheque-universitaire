<?php
class LoanController extends Controller {
    
    public function index() {
        if (!$this->isLoggedIn()) {
            $this->redirect('/login');
        }
        
        $loan = new Loan();
        
        // Si admin, voir tous les emprunts de tous les utilisateurs
        if ($this->isAdmin()) {
            $loans = $loan->getAllLoans(); // Tous les emprunts
        } else {
            $loans = $loan->getUserLoans($_SESSION['user_id']); // Ses propres emprunts
        }
        
        // Compter les emprunts actifs
        $activeLoans = 0;
        foreach ($loans as $l) {
            if ($l['status'] == 'en_cours') $activeLoans++;
        }
        
        // Récupérer le nombre total de livres
        $book = new Book();
        $allBooks = $book->findAll();
        $totalBooks = count($allBooks);
        
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
        
        $this->viewWithSidebar('loans/index', [
            'loans' => $loans,
            'totalBooks' => $totalBooks,
            'activeLoans' => $activeLoans,
            'totalUsers' => $totalUsers,
            'totalReservations' => $totalReservations,
            'isAdmin' => $this->isAdmin(),
            'activePage' => 'loans'
        ]);
    }
    
    // ========== BORROW CORRIGÉ ==========
    public function borrow() {
        // Désactiver l'affichage des erreurs
        error_reporting(0);
        ini_set('display_errors', 0);
        
        header('Content-Type: application/json');
        
        // Nettoyer les buffers
        while (ob_get_level() > 0) {
            ob_end_clean();
        }
        
        if (!$this->isLoggedIn()) {
            echo json_encode(['success' => false, 'message' => 'Non connecté']);
            exit;
        }
        
        $data = json_decode(file_get_contents('php://input'), true);
        $bookId = $data['book_id'] ?? null;
        
        if (!$bookId) {
            echo json_encode(['success' => false, 'message' => 'ID du livre manquant']);
            exit;
        }
        
        $book = new Book();
        $bookData = $book->findById($bookId);
        
        if (!$bookData || $bookData['available_quantity'] <= 0) {
            echo json_encode(['success' => false, 'message' => 'Ce livre n\'est pas disponible']);
            exit;
        }
        
        $loan = new Loan();
        
        // Vérification : déjà emprunté ce livre ?
        if ($loan->hasUserBorrowedBook($_SESSION['user_id'], $bookId)) {
            echo json_encode(['success' => false, 'message' => '❌ Vous avez déjà emprunté ce livre !']);
            exit;
        }
        
        // Vérification : limite d'emprunts
        $userLoans = $loan->getUserLoans($_SESSION['user_id']);
        $activeLoansCount = 0;
        foreach ($userLoans as $l) {
            if ($l['status'] == 'en_cours') $activeLoansCount++;
        }
        
        $maxLoans = ($_SESSION['user_role'] == 'professeur') ? 5 : 3;
        
        if ($activeLoansCount >= $maxLoans) {
            echo json_encode(['success' => false, 'message' => "❌ Limite atteinte : {$maxLoans} emprunts maximum"]);
            exit;
        }
        
        if ($loan->createLoan($_SESSION['user_id'], $bookId)) {
            // Supprimer la réservation active si elle existe
            $reservation = new Reservation();
            $reservation->markAsCompleted($bookId, $_SESSION['user_id']);
            
            $notification = new Notification();
            $notification->createNotification(
                $_SESSION['user_id'],
                'success',
                'Emprunt réussi',
                'Vous avez emprunté "' . $bookData['title'] . '". Retour prévu dans 14 jours.',
                '/loans'
            );
            
            echo json_encode(['success' => true, 'message' => '✅ Livre emprunté avec succès', 'reload' => true]);
            exit;
        } else {
            echo json_encode(['success' => false, 'message' => '❌ Erreur lors de l\'emprunt']);
            exit;
        }
    }
    
    // ========== RETURN CORRIGÉ ==========
    public function returnBook() {
        // Désactiver l'affichage des erreurs
        error_reporting(0);
        ini_set('display_errors', 0);
        
        header('Content-Type: application/json');
        
        // Nettoyer les buffers
        while (ob_get_level() > 0) {
            ob_end_clean();
        }
        
        if (!$this->isLoggedIn()) {
            echo json_encode(['success' => false, 'message' => 'Non connecté']);
            exit;
        }
        
        $data = json_decode(file_get_contents('php://input'), true);
        $loanId = $data['loan_id'] ?? null;
        
        if (!$loanId) {
            echo json_encode(['success' => false, 'message' => 'ID du prêt manquant']);
            exit;
        }
        
        $loan = new Loan();
        $loanData = $loan->findById($loanId);
        
        if (!$loanData || $loanData['user_id'] != $_SESSION['user_id']) {
            echo json_encode(['success' => false, 'message' => 'Prêt non trouvé']);
            exit;
        }
        
        $bookId = $loanData['book_id'];
        $bookTitle = $loanData['title'] ?? '';
        
        if ($loan->returnBook($loanId, $_SESSION['user_id'])) {
            $book = new Book();
            $bookData = $book->findById($bookId);
            $title = $bookData['title'] ?? $bookTitle;
            
            $notification = new Notification();
            $notification->createNotification(
                $_SESSION['user_id'],
                'info',
                'Retour effectué',
                'Vous avez retourné "' . $title . '". Merci !',
                '/loans'
            );
            
            // ========== NOTIFICATION POUR LE PROCHAIN RÉSERVATAIRE ==========
            $reservation = new Reservation();
            $nextReservation = $reservation->getNextReservation($bookId);
            
            if ($nextReservation && !empty($nextReservation['user_id'])) {
                $notification->createNotification(
                    $nextReservation['user_id'],
                    'success',
                    'Livre disponible',
                    'Le livre "' . $title . '" que vous avez réservé est maintenant disponible ! Connectez-vous pour l\'emprunter.',
                    '/books/show/' . $bookId
                );
            }
            
            echo json_encode(['success' => true, 'message' => '✅ Livre retourné avec succès', 'reload' => true]);
            exit;
        } else {
            echo json_encode(['success' => false, 'message' => '❌ Erreur lors du retour']);
            exit;
        }
    }
}
?>