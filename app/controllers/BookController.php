<?php
class BookController extends Controller {
public function index() {
    if (!$this->isLoggedIn()) {
        $this->redirect('/login');
    }
    
    $book = new Book();
    $books = $book->findAll();
    $totalBooks = count($books);
    
    // Récupérer les données pour la sidebar
    $user = new User();
    $allUsers = $user->findAll();
    $totalUsers = count($allUsers);
    
    $loan = new Loan();
    $userLoans = $loan->getUserLoans($_SESSION['user_id']);
    $activeLoans = 0;
    foreach ($userLoans as $l) {
        if ($l['status'] == 'en_cours') $activeLoans++;
    }
    
    $reservation = new Reservation();
    $userReservations = $reservation->getUserReservations($_SESSION['user_id']);
    $totalReservations = 0;
    foreach ($userReservations as $r) {
        if ($r['status'] == 'active') $totalReservations++;
    }
    
    $this->view('books/index', [
        'books' => $books,
        'search' => '',
        'totalBooks' => $totalBooks,
        'activeLoans' => $activeLoans,
        'totalUsers' => $totalUsers,
        'totalReservations' => $totalReservations
    ]);
}
    
    public function search() {
        if (!$this->isLoggedIn()) {
            $this->redirect('/login');
        }
        
        $keyword = $_GET['q'] ?? '';
        $book = new Book();
        
        if (!empty($keyword)) {
            $books = $book->search($keyword);
        } else {
            $books = $book->findAll();
        }
        
        $totalBooks = count($books);
        
        // Récupérer les données pour la sidebar
        $user = new User();
        $allUsers = $user->findAll();
        $totalUsers = count($allUsers);
        
        $loan = new Loan();
        $userLoans = $loan->getUserLoans($_SESSION['user_id']);
        $activeLoans = 0;
        foreach ($userLoans as $l) {
            if ($l['status'] == 'en_cours') $activeLoans++;
        }
        
        $reservation = new Reservation();
        $userReservations = $reservation->getUserReservations($_SESSION['user_id']);
        $totalReservations = 0;
        foreach ($userReservations as $r) {
            if ($r['status'] == 'active') $totalReservations++;
        }
        
        $this->view('books/index', [
            'books' => $books,
            'search' => $keyword,
            'totalBooks' => $totalBooks,
            'activeLoans' => $activeLoans,
            'totalUsers' => $totalUsers,
            'totalReservations' => $totalReservations
        ]);
    }
    
    public function show($id) {
        if (!$this->isLoggedIn()) {
            $this->json(['success' => false, 'message' => 'Non connecté']);
            return;
        }
        
        $book = new Book();
        $bookData = $book->findById($id);
        
        if ($bookData) {
            $this->json(['success' => true, 'book' => $bookData]);
        } else {
            $this->json(['success' => false, 'message' => 'Livre non trouvé']);
        }
    }
    
    public function create() {
        if (!$this->isAdmin()) {
            $this->redirect('/dashboard');
            return;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $book = new Book();
            $data = [
                'title' => $_POST['title'],
                'author' => $_POST['author'],
                'isbn' => $_POST['isbn'] ?? '',
                'description' => $_POST['description'] ?? '',
                'category' => $_POST['category'] ?? '',
                'cover_image' => $_POST['cover_image'] ?? '',
                'quantity' => $_POST['quantity'] ?? 1,
                'available_quantity' => $_POST['quantity'] ?? 1
            ];
            
            if ($book->create($data)) {
                $this->json(['success' => true, 'message' => 'Livre ajouté avec succès', 'redirect' => '/books']);
            } else {
                $this->json(['success' => false, 'message' => 'Erreur lors de l\'ajout']);
            }
            return;
        }
        
        $this->view('books/create');
    }
    
    public function edit($id) {
        if (!$this->isAdmin()) {
            $this->redirect('/dashboard');
            return;
        }
        
        $book = new Book();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = json_decode(file_get_contents('php://input'), true);
            $data['available_quantity'] = $data['quantity'];
            
            if ($book->update($id, $data)) {
                $this->json(['success' => true, 'message' => 'Livre modifié avec succès', 'redirect' => '/books']);
            } else {
                $this->json(['success' => false, 'message' => 'Erreur lors de la modification']);
            }
            return;
        }
        
        $bookData = $book->findById($id);
        $this->view('books/edit', ['book' => $bookData]);
    }
    
    public function delete($id) {
        if (!$this->isAdmin()) {
            $this->json(['success' => false, 'message' => 'Accès non autorisé']);
            return;
        }
        
        $book = new Book();
        if ($book->delete($id)) {
            $this->json(['success' => true, 'message' => 'Livre supprimé avec succès']);
        } else {
            $this->json(['success' => false, 'message' => 'Erreur lors de la suppression']);
        }
    }
}
?>