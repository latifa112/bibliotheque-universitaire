<?php
class Notification extends Model {
    protected $table = 'notifications';
    
    public function getUserNotifications($userId, $limit = 10) {
        $stmt = $this->db->prepare("
            SELECT * FROM notifications 
            WHERE user_id = :user_id 
            ORDER BY created_at DESC 
            LIMIT :limit
        ");
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getUnreadCount($userId) {
        $stmt = $this->db->prepare("
            SELECT COUNT(*) as count FROM notifications 
            WHERE user_id = :user_id AND is_read = FALSE
        ");
        $stmt->execute([':user_id' => $userId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['count'];
    }
    
    public function markAsRead($id, $userId) {
        $stmt = $this->db->prepare("
            UPDATE notifications SET is_read = TRUE 
            WHERE id = :id AND user_id = :user_id
        ");
        return $stmt->execute([':id' => $id, ':user_id' => $userId]);
    }
    
    public function markAllAsRead($userId) {
        $stmt = $this->db->prepare("
            UPDATE notifications SET is_read = TRUE 
            WHERE user_id = :user_id
        ");
        return $stmt->execute([':user_id' => $userId]);
    }
    
    public function createNotification($userId, $type, $title, $message, $link = null) {
        $stmt = $this->db->prepare("
            INSERT INTO notifications (user_id, type, title, message, link) 
            VALUES (:user_id, :type, :title, :message, :link)
        ");
        return $stmt->execute([
            ':user_id' => $userId,
            ':type' => $type,
            ':title' => $title,
            ':message' => $message,
            ':link' => $link
        ]);
    }
 
public function addOverdueNotification($userId, $type, $message, $loanId = null) {
    $title = '';
    if ($type == 'overdue') {
        $title = '⚠️ Livre en retard';
    } elseif ($type == 'reminder') {
        $title = '⏰ Retour imminent';
    } else {
        $title = 'Notification';
    }
    
    $stmt = $this->db->prepare("
        INSERT INTO notifications (user_id, type, title, message, loan_id, is_read, created_at) 
        VALUES (:user_id, :type, :title, :message, :loan_id, 0, NOW())
    ");
    return $stmt->execute([
        ':user_id' => $userId,
        ':type' => $type,
        ':title' => $title,
        ':message' => $message,
        ':loan_id' => $loanId
    ]);
}
}
?>
