<?php
class Router {
    private $controller = 'HomeController';
    private $action = 'index';
    private $params = [];

    public function dispatch($url) {
        // Nettoyer l'URL
        $url = trim($url, '/');
        $urlParts = !empty($url) ? explode('/', $url) : [];
        
        // Définir les routes
        $routes = [
            '' => ['HomeController', 'index'],
            'login' => ['UserController', 'login'],
            'register' => ['UserController', 'register'],
            'logout' => ['UserController', 'logout'],
            'dashboard' => ['HomeController', 'dashboard'],
            'books' => ['BookController', 'index'],
            'loans' => ['LoanController', 'index'],
            'profile' => ['UserController', 'profile'],
            'loans/borrow' => ['LoanController', 'borrow'],
            'loans/return' => ['LoanController', 'returnBook'],
            'debug' => ['HomeController', 'debug'],
            'api/notifications' => ['NotificationController', 'getNotifications'],
            'api/notifications/read' => ['NotificationController', 'markAsRead'],
            'api/notifications/read-all' => ['NotificationController', 'markAllAsRead'],
            'api/notifications/test' => ['NotificationController', 'createTestNotification'],
            'books/search' => ['BookController', 'search'],
            'books/create' => ['BookController', 'create'],
            'profile/update' => ['UserController', 'updateProfile'],
            'profile/password' => ['UserController', 'updatePassword'],
            'forgot-password' => ['UserController', 'forgotPassword'],
            'reset-password' => ['UserController', 'resetPassword'],
            'settings' => ['SettingsController', 'index'],
            'settings/update-profile' => ['SettingsController', 'updateProfile'],
            'settings/update-password' => ['SettingsController', 'updatePassword'],
            'settings/update-preferences' => ['SettingsController', 'updatePreferences'],
            'reservations' => ['ReservationController', 'index'],
            'reservations/cancel' => ['ReservationController', 'cancel'],
            'reservations/reserve' => ['ReservationController', 'reserve'],
            'users' => ['UserManagementController', 'index'],
            'users/toggle-status' => ['UserManagementController', 'toggleStatus'],
            'users/delete' => ['UserManagementController', 'deleteUser'],
            'statistics' => ['StatisticsController', 'index'],
            'admin/backups' => ['BackupController', 'index'],
            'admin/backups/create' => ['BackupController', 'create'],
            'admin/backups/download' => ['BackupController', 'download'],
            'admin/backups/delete' => ['BackupController', 'delete'],
            'profile/export' => ['UserController', 'exportData'],
            'api/recommendations' => ['ApiController', 'getRecommendations'],
        ];
        
        // Vérifier si l'URL existe dans les routes
        if (array_key_exists($url, $routes)) {
            $this->controller = $routes[$url][0];
            $this->action = $routes[$url][1];
        } else {
            // Vérifier si c'est une URL avec paramètre (ex: books/edit/1)
            $parts = explode('/', $url);
            if (count($parts) == 3 && $parts[0] == 'books' && $parts[1] == 'edit') {
                $this->controller = 'BookController';
                $this->action = 'edit';
                $this->params = [$parts[2]];
            } 
            // Vérifier si c'est une URL avec paramètre pour les livres (ex: books/1)
            elseif (count($parts) == 2 && $parts[0] == 'books' && is_numeric($parts[1])) {
                $this->controller = 'BookController';
                $this->action = 'show';
                $this->params = [$parts[1]];
            } 
            // Vérifier si c'est une recherche avec paramètre (ex: books/search?q=livre)
            elseif (count($parts) == 2 && $parts[0] == 'books' && $parts[1] == 'search') {
                $this->controller = 'BookController';
                $this->action = 'search';
            }
            else {
                // 404
                $this->show404();
                return;
            }
        }
        
        // Charger le contrôleur
        $controllerFile = APP_ROOT . '/controllers/' . $this->controller . '.php';
        if (file_exists($controllerFile)) {
            require_once $controllerFile;
            $controller = new $this->controller();
        } else {
            $this->show404();
            return;
        }
        
        // Vérifier si l'action existe
        if (method_exists($controller, $this->action)) {
            call_user_func_array([$controller, $this->action], $this->params);
        } else {
            $this->show404();
        }
    }
    
    private function show404() {
        header("HTTP/1.0 404 Not Found");
        require_once ROOT . '/app/views/layouts/header.php';
        require_once ROOT . '/app/views/errors/404.php';
        require_once ROOT . '/app/views/layouts/footer.php';
        exit;
    }
}
?>