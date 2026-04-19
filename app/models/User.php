<?php
class User extends Model {
    protected $table = 'users';
    
    public function findByEmail($email) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute([':email' => $email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function findByUsername($username) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE username = :username");
        $stmt->execute([':username' => $username]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function createUser($data) {
        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        return $this->create($data);
    }
    
public function authenticate($email, $password) {
    $stmt = $this->db->prepare("SELECT * FROM users WHERE email = :email AND status = 'actif'");
    $stmt->execute([':email' => $email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($user && password_verify($password, $user['password'])) {
        return $user;
    }
    return false;
}

public function findAll() {
    $stmt = $this->db->query("SELECT * FROM users");
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    error_log("findAll users returned " . count($result) . " records");
    return $result;
}

public function updateProfile($userId, $data) {
    try {
        // Vérifier si l'email existe déjà pour un autre utilisateur
        $checkStmt = $this->db->prepare("
            SELECT id FROM users WHERE email = :email AND id != :id
        ");
        $checkStmt->execute([
            ':email' => $data['email'],
            ':id' => $userId
        ]);
        
        if ($checkStmt->fetch()) {
            error_log("Email déjà utilisé par un autre utilisateur");
            return false;
        }
        
        $stmt = $this->db->prepare("
            UPDATE users 
            SET first_name = :first_name, 
                last_name = :last_name, 
                email = :email
            WHERE id = :id
        ");
        
        $result = $stmt->execute([
            ':id' => $userId,
            ':first_name' => $data['first_name'],
            ':last_name' => $data['last_name'],
            ':email' => $data['email']
        ]);
        
        error_log("updateProfile - Result: " . ($result ? 'true' : 'false'));
        error_log("Rows affected: " . $stmt->rowCount());
        
        return $result;
        
    } catch (PDOException $e) {
        error_log("PDO Exception in updateProfile: " . $e->getMessage());
        return false;
    }
}

public function changePassword($userId, $newPassword) {
    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
    $stmt = $this->db->prepare("UPDATE users SET password = :password WHERE id = :id");
    return $stmt->execute([':id' => $userId, ':password' => $hashedPassword]);
}

public function updateUser($id, $data) {
    return $this->update($id, $data);
}
public function setResetToken($userId, $token, $expires) {
    $stmt = $this->db->prepare("UPDATE users SET reset_token = :token, reset_expires = :expires WHERE id = :id");
    return $stmt->execute([
        ':id' => $userId,
        ':token' => $token,
        ':expires' => $expires
    ]);
}

public function findByResetToken($token) {
    $stmt = $this->db->prepare("SELECT * FROM users WHERE reset_token = :token AND reset_expires > NOW()");
    $stmt->execute([':token' => $token]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

public function clearResetToken($userId) {
    $stmt = $this->db->prepare("UPDATE users SET reset_token = NULL, reset_expires = NULL WHERE id = :id");
    return $stmt->execute([':id' => $userId]);
}

public function getAllUsers() {
    $stmt = $this->db->prepare("
        SELECT u.*, 
               (SELECT COUNT(*) FROM loans WHERE user_id = u.id) as total_loans,
               (SELECT COUNT(*) FROM loans WHERE user_id = u.id AND status = 'en_cours') as active_loans
        FROM users u
        ORDER BY u.created_at DESC
    ");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

public function updateStatus($userId, $status) {
    $stmt = $this->db->prepare("UPDATE users SET status = :status WHERE id = :id");
    return $stmt->execute([':id' => $userId, ':status' => $status]);
}


public function getTopBorrowers($limit = 5) {
    $stmt = $this->db->prepare("
        SELECT 
            u.id, 
            u.first_name, 
            u.last_name, 
            u.email, 
            COUNT(l.id) as loan_count
        FROM users u
        LEFT JOIN loans l ON u.id = l.user_id
        WHERE u.status = 'actif'
        GROUP BY u.id
        ORDER BY loan_count DESC
        LIMIT :limit
    ");
    $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Retourner les données réelles (peut être vide)
    return $result;
}

public function updateFieldOfStudy($userId, $field) {
    $stmt = $this->db->prepare("UPDATE users SET field_of_study = :field WHERE id = :id");
    return $stmt->execute([':id' => $userId, ':field' => $field]);
}

/**
 * Récupérer les statistiques complètes d'un utilisateur (emprunts + réservations)
 */
public function getUserFullStats($userId) {
    $stmt = $this->db->prepare("
        SELECT 
            u.*,
            (SELECT COUNT(*) FROM loans WHERE user_id = u.id) as total_loans,
            (SELECT COUNT(*) FROM loans WHERE user_id = u.id AND status = 'en_cours') as active_loans,
            (SELECT COUNT(*) FROM reservations WHERE user_id = u.id) as total_reservations,
            (SELECT COUNT(*) FROM reservations WHERE user_id = u.id AND status = 'active') as active_reservations
        FROM users u
        WHERE u.id = :user_id
    ");
    $stmt->execute([':user_id' => $userId]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

/**
 * Récupérer tous les utilisateurs avec leurs statistiques complètes
 */
public function getAllUsersWithStats() {
    $stmt = $this->db->prepare("
        SELECT 
            u.*,
            (SELECT COUNT(*) FROM loans WHERE user_id = u.id) as total_loans,
            (SELECT COUNT(*) FROM loans WHERE user_id = u.id AND status = 'en_cours') as active_loans,
            (SELECT COUNT(*) FROM reservations WHERE user_id = u.id) as total_reservations,
            (SELECT COUNT(*) FROM reservations WHERE user_id = u.id AND status = 'active') as active_reservations
        FROM users u
        ORDER BY u.created_at DESC
    ");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

}
?>
