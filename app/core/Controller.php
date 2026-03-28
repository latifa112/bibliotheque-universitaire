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
        return isset($_SESSION['user_id']);
    }
    
    protected function isAdmin() {
        return isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin';
    }
}
?>
