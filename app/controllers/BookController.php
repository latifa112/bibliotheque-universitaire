<?php
class BookController extends Controller {
    
    public function index() {
        if (!$this->isLoggedIn()) {
            $this->redirect('/login');
        }
        
        $activePage = 'books'; 
        $book = new Book();
        $books = $book->findAll();
        
        $this->viewWithSidebar('books/index', [
            'books' => $books,
            'search' => '',
            'activePage' => $activePage
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
        
        $this->viewWithSidebar('books/index', [
            'books' => $books,
            'search' => $keyword,
            'activePage' => 'books'
        ]);
    }
    
    public function show($id) {
        if (!$this->isLoggedIn()) {
            $this->redirect('/login');
        }
        
        $bookModel = new Book();
        $book = $bookModel->findById($id);
        
        if (!$book) {
            $this->redirect('/books');
        }
        
        // Vérifier si l'utilisateur a déjà emprunté ce livre
        $loanModel = new Loan();
        $userLoan = null;
        $userLoansList = $loanModel->getUserLoans($_SESSION['user_id']);
        foreach ($userLoansList as $l) {
            if ($l['book_id'] == $id && $l['status'] == 'en_cours') {
                $userLoan = $l;
                break;
            }
        }
        
        // Vérifier si l'utilisateur a déjà réservé ce livre
        $reservationModel = new Reservation();
        $userReservations = $reservationModel->getUserReservations($_SESSION['user_id']);
        $userReservation = null;
        foreach ($userReservations as $r) {
            if ($r['book_id'] == $id && $r['status'] == 'active') {
                $userReservation = $r;
                break;
            }
        }
        
        $this->viewWithSidebar('books/show', [
            'book' => $book,
            'userLoan' => $userLoan,
            'userReservation' => $userReservation,
            'activePage' => 'books'
        ]);
    }
    
    public function create() {
        if (!$this->isAdmin()) {
            $this->redirect('/dashboard');
            return;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Récupérer les données JSON
            $input = json_decode(file_get_contents('php://input'), true);
            
            if (!$input) {
                $input = $_POST;
            }
            
            $book = new Book();
            $data = [
                'title' => $input['title'] ?? '',
                'author' => $input['author'] ?? '',
                'isbn' => $input['isbn'] ?? '',
                'description' => $input['description'] ?? '',
                'category' => $input['category'] ?? '',
                'cover_image' => $input['cover_image'] ?? '',
                'quantity' => $input['quantity'] ?? 1,
                'available_quantity' => $input['quantity'] ?? 1
            ];
            
            // Validation
            if (empty($data['title']) || empty($data['author'])) {
                $this->json(['success' => false, 'message' => 'Le titre et l\'auteur sont obligatoires']);
                return;
            }
            
            // Validation du format ISBN
            if (!empty($data['isbn']) && !preg_match('/^[0-9]{10,13}$|^[0-9]{3}-[0-9]{10}$/', $data['isbn'])) {
                $this->json(['success' => false, 'message' => 'Format ISBN invalide. Utilisez 10 ou 13 chiffres (ex: 978-2-1234-5678-9)']);
                return;
            }
            
            if ($book->create($data)) {
                $this->json(['success' => true, 'message' => 'Livre ajouté avec succès', 'redirect' => '/books']);
            } else {
                $this->json(['success' => false, 'message' => 'Erreur lors de l\'ajout']);
            }
            return;
        }
        
        $this->viewWithSidebar('books/create', ['activePage' => 'books']);
    }
    
    public function edit($id) {
        if (!$this->isAdmin()) {
            $this->redirect('/dashboard');
            return;
        }
        
        $bookModel = new Book();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $input = json_decode(file_get_contents('php://input'), true);
            
            if (!$input) {
                $input = $_POST;
            }
            
            $data = [
                'title' => $input['title'] ?? '',
                'author' => $input['author'] ?? '',
                'isbn' => $input['isbn'] ?? '',
                'description' => $input['description'] ?? '',
                'category' => $input['category'] ?? '',
                'cover_image' => $input['cover_image'] ?? '',
                'quantity' => $input['quantity'] ?? 1,
                'available_quantity' => $input['quantity'] ?? 1
            ];
            
            // Validation du format ISBN
            if (!empty($data['isbn']) && !preg_match('/^[0-9]{10,13}$|^[0-9]{3}-[0-9]{10}$/', $data['isbn'])) {
                $this->json(['success' => false, 'message' => 'Format ISBN invalide. Utilisez 10 ou 13 chiffres (ex: 978-2-1234-5678-9)']);
                return;
            }
            
            if ($bookModel->update($id, $data)) {
                $this->json(['success' => true, 'message' => 'Livre modifié avec succès', 'redirect' => '/books']);
            } else {
                $this->json(['success' => false, 'message' => 'Erreur lors de la modification']);
            }
            return;
        }
        
        $bookData = $bookModel->findById($id);
        if (!$bookData) {
            $this->redirect('/books');
        }
        
        $this->viewWithSidebar('books/edit', [
            'book' => $bookData,
            'activePage' => 'books'
        ]);
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
