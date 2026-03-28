<?php
class NotificationController extends Controller {
    
    public function getNotifications() {
        if (!$this->isLoggedIn()) {
            $this->json(['success' => false, 'message' => 'Non connecté']);
            return;
        }
        
        $notification = new Notification();
        $notifications = $notification->getUserNotifications($_SESSION['user_id'], 10);
        $unreadCount = $notification->getUnreadCount($_SESSION['user_id']);
        
        $this->json([
            'success' => true,
            'notifications' => $notifications,
            'unread_count' => $unreadCount
        ]);
    }
    
    public function markAsRead() {
        if (!$this->isLoggedIn()) {
            $this->json(['success' => false, 'message' => 'Non connecté']);
            return;
        }
        
        $data = json_decode(file_get_contents('php://input'), true);
        $notificationId = $data['id'] ?? null;
        
        if (!$notificationId) {
            $this->json(['success' => false, 'message' => 'ID manquant']);
            return;
        }
        
        $notification = new Notification();
        if ($notification->markAsRead($notificationId, $_SESSION['user_id'])) {
            $this->json(['success' => true]);
        } else {
            $this->json(['success' => false, 'message' => 'Erreur']);
        }
    }
    
    public function markAllAsRead() {
        if (!$this->isLoggedIn()) {
            $this->json(['success' => false, 'message' => 'Non connecté']);
            return;
        }
        
        $notification = new Notification();
        if ($notification->markAllAsRead($_SESSION['user_id'])) {
            $this->json(['success' => true]);
        } else {
            $this->json(['success' => false, 'message' => 'Erreur']);
        }
    }
}
?>
