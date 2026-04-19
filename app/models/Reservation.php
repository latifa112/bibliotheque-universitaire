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
    
    // MÉTHODE CREATE RESERVATION
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
}
?>