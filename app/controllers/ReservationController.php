<?php
class ReservationController extends Controller {
    
    public function index() {
        if (!$this->isLoggedIn()) {
            $this->redirect('/login');
        }
        
        $activePage = 'reservations';
        $reservation = new Reservation();
        
        if ($this->isAdmin()) {
            $reservations = $reservation->getAllReservations();
            $isAdmin = true;
        } else {
            $reservations = $reservation->getUserReservations($_SESSION['user_id']);
            $isAdmin = false;
        }
        
        $totalReservations = 0;
        foreach ($reservations as $r) {
            if ($r['status'] == 'active') $totalReservations++;
        }
        
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
        
        $this->viewWithSidebar('reservations/index', [
            'reservations' => $reservations,
            'isAdmin' => $isAdmin,
            'totalBooks' => $totalBooks,
            'activeLoans' => $activeLoans,
            'totalUsers' => $totalUsers,
            'totalReservations' => $totalReservations,
            'activeReservations' => $totalReservations,
            'activePage' => $activePage
        ]);
    }
    
    // ========== RESERVE CORRIGÉ AVEC VÉRIFICATION ==========
    public function reserve() {
        // Désactiver l'affichage des erreurs
        error_reporting(0);
        ini_set('display_errors', 0);
        
        header('Content-Type: application/json');
        
        while (ob_get_level() > 0) {
            ob_end_clean();
        }
        
        if (!$this->isLoggedIn()) {
            echo json_encode(['success' => false, 'message' => 'Vous devez être connecté pour réserver']);
            exit;
        }
        
        $data = json_decode(file_get_contents('php://input'), true);
        $bookId = $data['book_id'] ?? null;
        
        if (!$bookId) {
            echo json_encode(['success' => false, 'message' => 'ID du livre manquant']);
            exit;
        }
        
        $loan = new Loan();
        
        // ========== VÉRIFICATION : L'utilisateur a-t-il déjà emprunté ce livre ? ==========
        if ($loan->hasUserBorrowedBook($_SESSION['user_id'], $bookId)) {
            echo json_encode(['success' => false, 'message' => '❌ Vous ne pouvez pas réserver un livre que vous avez déjà emprunté !']);
            exit;
        }
        
        $reservation = new Reservation();
        $result = $reservation->createReservation($_SESSION['user_id'], $bookId);
        
        if ($result) {
            $db = Database::getInstance()->getConnection();
            $stmt = $db->prepare("SELECT title, author FROM books WHERE id = :id");
            $stmt->execute([':id' => $bookId]);
            $book = $stmt->fetch(PDO::FETCH_ASSOC);
            
            $notification = new Notification();
            $notification->createNotification(
                $_SESSION['user_id'],
                'reserve_success',
                '🔔 Réservation confirmée',
                "Vous avez réservé le livre '{$book['title']}'. Vous serez notifié quand il sera disponible.",
                "/reservations"
            );
            
            echo json_encode(['success' => true, 'message' => '✅ Livre réservé avec succès !', 'reload' => true]);
            exit;
        } else {
            echo json_encode(['success' => false, 'message' => '❌ Vous avez déjà réservé ce livre ou erreur lors de la réservation']);
            exit;
        }
    }
    
    public function cancel() {
        header('Content-Type: application/json');
        
        while (ob_get_level() > 0) {
            ob_end_clean();
        }
        
        if (!$this->isLoggedIn()) {
            echo json_encode(['success' => false, 'message' => 'Non connecté']);
            exit;
        }
        
        $data = json_decode(file_get_contents('php://input'), true);
        $reservationId = $data['reservation_id'] ?? null;
        
        if (!$reservationId) {
            echo json_encode(['success' => false, 'message' => 'ID de réservation manquant']);
            exit;
        }
        
        $reservation = new Reservation();
        $result = $reservation->cancelReservation($reservationId, $_SESSION['user_id']);
        
        if ($result) {
            echo json_encode(['success' => true, 'message' => '✅ Réservation annulée avec succès', 'reload' => true]);
            exit;
        } else {
            echo json_encode(['success' => false, 'message' => '❌ Erreur lors de l\'annulation']);
            exit;
        }
    }
}
?>