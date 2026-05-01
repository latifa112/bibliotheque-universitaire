<?php
class Controller {
    protected function model($model) {
        $modelClass = $model;
        if (class_exists($modelClass)) {
            return new $modelClass();
        }
        return null;
    }
    
    protected function view($view, $data = []) {
        extract($data);
        $viewFile = ROOT . '/app/views/' . $view . '.php';
        if (file_exists($viewFile)) {
            require_once ROOT . '/app/views/layouts/header.php';
            require_once $viewFile;
            require_once ROOT . '/app/views/layouts/footer.php';
        }
    }
    
    protected function json($data) {
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
    
    protected function redirect($url) {
        header('Location: ' . $url);
        exit;
    }
    
    protected function isLoggedIn() {
        return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
    }
    
    protected function isAdmin() {
        return isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin';
    }

    /**
 * Afficher une vue avec les données de la sidebar
 */
protected function viewWithSidebar($view, $data = []) {
    // Récupérer les données de la sidebar (statistiques globales)
    $sidebarData = $this->getSidebarData();
    // Fusionner avec les données passées
    $allData = array_merge($sidebarData, $data);
    // S'assurer que activePage est défini
    if (!isset($allData['activePage'])) {
        $allData['activePage'] = $view;
    }
    $this->view($view, $allData);
}

/**
 * Récupérer les données pour la sidebar
 */
protected function getSidebarData() {
    $data = [
        'totalBooks' => 0,
        'activeLoans' => 0,
        'totalUsers' => 0,
        'totalReservations' => 0
    ];
    
    if (!$this->isLoggedIn()) {
        return $data;
    }
    
    try {
        // Total des livres
        $book = new Book();
        $allBooks = $book->findAll();
        $data['totalBooks'] = count($allBooks);
        
        // Emprunts actifs de l'utilisateur
        $loan = new Loan();
        $userLoans = $loan->getUserLoans($_SESSION['user_id']);
        $activeLoans = 0;
        foreach ($userLoans as $l) {
            if ($l['status'] == 'en_cours') $activeLoans++;
        }
        $data['activeLoans'] = $activeLoans;
        
        // Total des utilisateurs (admin seulement)
        if ($this->isAdmin()) {
            $user = new User();
            $allUsers = $user->findAll();
            $data['totalUsers'] = count($allUsers);
        } else {
            $data['totalUsers'] = 0;
        }
        
        // Réservations actives de l'utilisateur
        $reservation = new Reservation();
        $userReservations = $reservation->getUserReservations($_SESSION['user_id']);
        $totalReservations = 0;
        foreach ($userReservations as $r) {
            if ($r['status'] == 'active') $totalReservations++;
        }
        $data['totalReservations'] = $totalReservations;
        
    } catch (Exception $e) {
        error_log("Erreur getSidebarData: " . $e->getMessage());
    }
    
    return $data;
}
}
?>
