<?php
class Loan extends Model {
    protected $table = 'loans';
    
    public function createLoan($userId, $bookId) {
        $loanDate = date('Y-m-d');
        $dueDate = date('Y-m-d', strtotime('+14 days'));
        
        $this->db->beginTransaction();
        
        try {
            $stmt = $this->db->prepare("INSERT INTO loans (user_id, book_id, loan_date, due_date, status) VALUES (:user_id, :book_id, :loan_date, :due_date, 'en_cours')");
            $stmt->execute([
                ':user_id' => $userId,
                ':book_id' => $bookId,
                ':loan_date' => $loanDate,
                ':due_date' => $dueDate
            ]);
            
            $book = new Book();
            $book->decrementQuantity($bookId);
            
            $this->db->commit();
            return true;
        } catch(Exception $e) {
            $this->db->rollBack();
            return false;
        }
    }
    
    public function returnBook($loanId, $userId) {
        $this->db->beginTransaction();
        
        try {
            $stmt = $this->db->prepare("UPDATE loans SET status = 'retourne', return_date = :return_date WHERE id = :id AND user_id = :user_id AND status = 'en_cours'");
            $stmt->execute([
                ':id' => $loanId,
                ':user_id' => $userId,
                ':return_date' => date('Y-m-d')
            ]);
            
            if ($stmt->rowCount() > 0) {
                $loan = $this->findById($loanId);
                if ($loan) {
                    $book = new Book();
                    $book->incrementQuantity($loan['book_id']);
                }
            }
            
            $this->db->commit();
            return true;
        } catch(Exception $e) {
            $this->db->rollBack();
            return false;
        }
    }
    
    // ========== NOUVELLE MÉTHODE : VÉRIFIER SI L'UTILISATEUR A DÉJÀ EMPRUNTÉ CE LIVRE ==========
    public function hasUserBorrowedBook($userId, $bookId) {
        $stmt = $this->db->prepare("
            SELECT id FROM loans 
            WHERE user_id = :user_id 
            AND book_id = :book_id 
            AND status = 'en_cours'
        ");
        $stmt->execute([
            ':user_id' => $userId,
            ':book_id' => $bookId
        ]);
        return $stmt->fetch() !== false;
    }
    
    public function getUserLoans($userId) {
        $stmt = $this->db->prepare("
            SELECT l.*, b.title, b.author, b.isbn, b.cover_image 
            FROM loans l 
            JOIN books b ON l.book_id = b.id 
            WHERE l.user_id = :user_id 
            ORDER BY l.loan_date DESC
        ");
        $stmt->execute([':user_id' => $userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getActiveLoans() {
        $stmt = $this->db->prepare("SELECT l.*, b.title, b.author, u.first_name, u.last_name FROM loans l JOIN books b ON l.book_id = b.id JOIN users u ON l.user_id = u.id WHERE l.status = 'en_cours'");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getMostBorrowedBooks($limit = 5) {
        $stmt = $this->db->prepare("
            SELECT b.id, b.title, b.author, b.cover_image, COUNT(l.id) as loan_count
            FROM loans l
            JOIN books b ON l.book_id = b.id
            GROUP BY b.id
            ORDER BY loan_count DESC
            LIMIT :limit
        ");
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getMonthlyStats() {
        $stmt = $this->db->prepare("
            SELECT 
                MONTH(loan_date) as month,
                COUNT(*) as count
            FROM loans
            WHERE YEAR(loan_date) = YEAR(CURDATE())
            GROUP BY MONTH(loan_date)
            ORDER BY month ASC
        ");
        $stmt->execute();
        
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $months = array_fill(1, 12, 0);
        foreach ($results as $result) {
            $months[$result['month']] = $result['count'];
        }
        
        // Si aucun emprunt, générer des données de test
        $hasData = false;
        foreach ($months as $count) {
            if ($count > 0) {
                $hasData = true;
                break;
            }
        }
        
        if (!$hasData) {
            // Données de test
            $months = [
                1 => 5, 2 => 8, 3 => 12, 4 => 15, 5 => 18, 6 => 22,
                7 => 25, 8 => 20, 9 => 18, 10 => 14, 11 => 10, 12 => 7
            ];
        }
        
        return $months;
    }

    public function getAllLoans() {
        $stmt = $this->db->prepare("
            SELECT 
                l.*,
                b.title as title,
                b.author as author,
                b.isbn as isbn,
                b.cover_image as cover_image,
                u.id as user_id,
                u.first_name, 
                u.last_name, 
                u.email as user_email,
                u.role as user_role
            FROM loans l
            LEFT JOIN books b ON l.book_id = b.id
            LEFT JOIN users u ON l.user_id = u.id
            ORDER BY l.loan_date DESC
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>