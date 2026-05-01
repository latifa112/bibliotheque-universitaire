<?php
class Reservation extends Model {
    protected $table = 'reservations';
    
    public function getUserReservations($userId) {
        $stmt = $this->db->prepare("
            SELECT r.*, b.title, b.author, b.cover_image, b.isbn 
            FROM reservations r 
            JOIN books b ON r.book_id = b.id 
            WHERE r.user_id = :user_id 
            ORDER BY r.reservation_date DESC
        ");
        $stmt->execute([':user_id' => $userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function createReservation($userId, $bookId) {
        // Vérifier si une réservation active existe déjà
        $check = $this->db->prepare("
            SELECT id FROM reservations 
            WHERE user_id = :user_id AND book_id = :book_id AND status = 'active'
        ");
        $check->execute([':user_id' => $userId, ':book_id' => $bookId]);
        
        if ($check->fetch()) {
            return false;
        }
        
        $reservationDate = date('Y-m-d');
        $expiryDate = date('Y-m-d', strtotime('+7 days'));
        
        $stmt = $this->db->prepare("
            INSERT INTO reservations (user_id, book_id, reservation_date, expiry_date, status) 
            VALUES (:user_id, :book_id, :reservation_date, :expiry_date, 'active')
        ");
        
        return $stmt->execute([
            ':user_id' => $userId,
            ':book_id' => $bookId,
            ':reservation_date' => $reservationDate,
            ':expiry_date' => $expiryDate
        ]);
    }
    
    public function cancelReservation($reservationId, $userId) {
        $stmt = $this->db->prepare("
            UPDATE reservations 
            SET status = 'annulee' 
            WHERE id = :id AND user_id = :user_id AND status = 'active'
        ");
        return $stmt->execute([':id' => $reservationId, ':user_id' => $userId]);
    }
    
    // ========== MÉTHODE CORRIGÉE : RÉCUPÉRER LE PROCHAIN RÉSERVATAIRE ==========
    public function getNextReservation($bookId) {
        $stmt = $this->db->prepare("
            SELECT r.*, u.first_name, u.last_name, u.email
            FROM reservations r
            JOIN users u ON r.user_id = u.id
            WHERE r.book_id = :book_id AND r.status = 'active'
            ORDER BY r.reservation_date ASC
            LIMIT 1
        ");
        $stmt->execute([':book_id' => $bookId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    // ========== MÉTHODE : MARQUER UNE RÉSERVATION COMME COMPLÉTÉE ==========
    public function markAsCompleted($bookId, $userId) {
        $stmt = $this->db->prepare("
            UPDATE reservations 
            SET status = 'completed' 
            WHERE book_id = :book_id AND user_id = :user_id AND status = 'active'
        ");
        return $stmt->execute([':book_id' => $bookId, ':user_id' => $userId]);
    }
    
    // ========== MÉTHODE : COMPTER LE NOMBRE DE RÉSERVATIONS ACTIVES ==========
    public function getWaitingCount($bookId) {
        $stmt = $this->db->prepare("
            SELECT COUNT(*) as count FROM reservations 
            WHERE book_id = :book_id AND status = 'active'
        ");
        $stmt->execute([':book_id' => $bookId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['count'] ?? 0;
    }
    
    public function getActiveReservations() {
        $stmt = $this->db->prepare("
            SELECT r.*, b.title, b.author, u.first_name, u.last_name 
            FROM reservations r 
            JOIN books b ON r.book_id = b.id 
            JOIN users u ON r.user_id = u.id 
            WHERE r.status = 'active' AND r.expiry_date >= CURDATE()
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getAllReservations() {
        $stmt = $this->db->prepare("
            SELECT 
                r.*,
                b.title,
                b.author,
                b.isbn,
                b.cover_image,
                u.id as user_id,
                u.first_name, 
                u.last_name, 
                u.email as user_email,
                u.role as user_role
            FROM reservations r
            LEFT JOIN books b ON r.book_id = b.id
            LEFT JOIN users u ON r.user_id = u.id
            ORDER BY r.reservation_date DESC
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function cancelReservationAdmin($reservationId) {
        $stmt = $this->db->prepare("
            UPDATE reservations 
            SET status = 'annulee' 
            WHERE id = :id
        ");
        return $stmt->execute([':id' => $reservationId]);
    }
}
?>