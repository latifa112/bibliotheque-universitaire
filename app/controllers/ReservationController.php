<?php
class ReservationController extends Controller {
    
    public function index() {
        if (!$this->isLoggedIn()) {
            $this->redirect('/login');
        }
        
        $reservation = new Reservation();
        $reservations = $reservation->getUserReservations($_SESSION['user_id']);
        
        // Compter les réservations actives
        $totalReservations = 0;
        foreach ($reservations as $r) {
            if ($r['status'] == 'active') $totalReservations++;
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
        
        $user = new User();
        $allUsers = $user->findAll();
        $totalUsers = count($allUsers);
        
        $this->view('reservations/index', [
            'reservations' => $reservations,
            'totalBooks' => $totalBooks,
            'activeLoans' => $activeLoans,
            'totalUsers' => $totalUsers,
            'totalReservations' => $totalReservations,
            'activeReservations' => $totalReservations
        ]);
    }
    
    // MÉTHODE RESERVE AJOUTÉE
    public function reserve() {
        if (!$this->isLoggedIn()) {
            $this->json(['success' => false, 'message' => 'Non connecté']);
            return;
        }
        
        $data = json_decode(file_get_contents('php://input'), true);
        $bookId = $data['book_id'] ?? null;
        
        if (!$bookId) {
            $this->json(['success' => false, 'message' => 'ID du livre manquant']);
            return;
        }
        
        $reservation = new Reservation();
        $result = $reservation->createReservation($_SESSION['user_id'], $bookId);
        
        if ($result) {
            $this->json(['success' => true, 'message' => 'Livre réservé avec succès']);
        } else {
            $this->json(['success' => false, 'message' => 'Vous avez déjà réservé ce livre ou erreur lors de la réservation']);
        }
    }
    
    public function cancel() {
        if (!$this->isLoggedIn()) {
            $this->json(['success' => false, 'message' => 'Non connecté']);
            return;
        }
        
        $data = json_decode(file_get_contents('php://input'), true);
        $reservationId = $data['reservation_id'] ?? null;
        
        if (!$reservationId) {
            $this->json(['success' => false, 'message' => 'ID de réservation manquant']);
            return;
        }
        
        $reservation = new Reservation();
        $result = $reservation->cancelReservation($reservationId, $_SESSION['user_id']);
        
        if ($result) {
            $this->json(['success' => true, 'message' => 'Réservation annulée']);
        } else {
            $this->json(['success' => false, 'message' => 'Erreur lors de l\'annulation']);
        }
    }
}
?>