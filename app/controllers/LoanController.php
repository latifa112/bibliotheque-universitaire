<?php
class LoanController extends Controller {
public function index() {
    if (!$this->isLoggedIn()) {
        $this->redirect('/login');
    }
    
    $loan = new Loan();
    $loans = $loan->getUserLoans($_SESSION['user_id']);
    
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
    
    $this->view('loans/index', [
        'loans' => $loans,
        'totalBooks' => $totalBooks,
        'activeLoans' => $activeLoans,
        'totalUsers' => $totalUsers,
        'totalReservations' => $totalReservations
    ]);
}
    
    public function borrow() {
        if (!$this->isLoggedIn()) {
            $this->json(['success' => false, 'message' => 'Non connecté']);
            return;
        }
        
        $data = json_decode(file_get_contents('php://input'), true);
        $bookId = $data['book_id'];
        
        $book = new Book();
        $bookData = $book->findById($bookId);
        
        $loan = new Loan();
        if ($loan->createLoan($_SESSION['user_id'], $bookId)) {
            $notification = new Notification();
            $notification->createNotification(
                $_SESSION['user_id'],
                'success',
                'Emprunt réussi',
                'Vous avez emprunté "' . $bookData['title'] . '". Retour prévu dans 14 jours.',
                '/loans'
            );
            
            $this->json(['success' => true, 'message' => 'Livre emprunté avec succès']);
        } else {
            $this->json(['success' => false, 'message' => 'Erreur lors de l\'emprunt']);
        }
    }
    
    public function returnBook() {
        if (!$this->isLoggedIn()) {
            $this->json(['success' => false, 'message' => 'Non connecté']);
            return;
        }
        
        $data = json_decode(file_get_contents('php://input'), true);
        $loanId = $data['loan_id'];
        
        $loan = new Loan();
        $loanData = $loan->findById($loanId);
        
        if ($loan->returnBook($loanId, $_SESSION['user_id'])) {
            $book = new Book();
            $bookData = $book->findById($loanData['book_id']);
            
            $notification = new Notification();
            $notification->createNotification(
                $_SESSION['user_id'],
                'info',
                'Retour effectué',
                'Vous avez retourné "' . $bookData['title'] . '". Merci !',
                '/loans'
            );
            
            $this->json(['success' => true, 'message' => 'Livre retourné avec succès']);
        } else {
            $this->json(['success' => false, 'message' => 'Erreur lors du retour']);
        }
    }
}
?>