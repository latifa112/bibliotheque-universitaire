<?php
class HomeController extends Controller {
    public function index() {
        if ($this->isLoggedIn()) {
            header('Location: /dashboard');
            exit;
        } else {
            header('Location: /login');
            exit;
        }
    }
    
public function dashboard() {
    if (!$this->isLoggedIn()) {
        header('Location: /login');
        exit;
    }
    
    $book = new Book();
    $loan = new Loan();
    $user = new User();
    $reservation = new Reservation();
    
    // Récupérer tous les livres
    $allBooks = $book->findAll();
    $totalBooks = count($allBooks);
    
    // Récupérer les emprunts de l'utilisateur
    $userLoans = $loan->getUserLoans($_SESSION['user_id']);
    $activeLoans = 0;
    foreach ($userLoans as $l) {
        if ($l['status'] == 'en_cours') $activeLoans++;
    }
    
    // Récupérer les réservations actives de l'utilisateur
    $userReservations = $reservation->getUserReservations($_SESSION['user_id']);
    $totalReservations = 0;
    foreach ($userReservations as $r) {
        if ($r['status'] == 'active') $totalReservations++;
    }
    
    // Compter le nombre total d'utilisateurs
    $allUsers = $user->findAll();
    $totalUsers = count($allUsers);
    
    // Compter le nombre total d'utilisateurs actifs
    $activeUsers = 0;
    foreach ($allUsers as $u) {
        if ($u['status'] == 'actif') $activeUsers++;
    }
    
    $stats = [
        'total_books' => $totalBooks,
        'my_loans' => $activeLoans,  // ← Changé: utilise $activeLoans au lieu de count($userLoans)
        'active_users' => $activeUsers,
        'reservations' => $totalReservations
    ];
    
    $recent_books = array_slice($allBooks, 0, 4);
    
    $this->view('home/dashboard', [
        'stats' => $stats,
        'user' => $_SESSION,
        'recent_books' => $recent_books,
        'totalBooks' => $totalBooks,
        'activeLoans' => $activeLoans,
        'totalUsers' => $totalUsers,
        'totalReservations' => $totalReservations
    ]);
}
}
?>