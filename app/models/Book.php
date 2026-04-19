<?php
class Book extends Model {
    protected $table = 'books';
    
    public function decrementQuantity($id) {
        $stmt = $this->db->prepare("UPDATE books SET available_quantity = available_quantity - 1 WHERE id = :id AND available_quantity > 0");
        return $stmt->execute([':id' => $id]);
    }
    
    public function incrementQuantity($id) {
        $stmt = $this->db->prepare("UPDATE books SET available_quantity = available_quantity + 1 WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }
    
    public function getAvailableBooks() {
        $stmt = $this->db->prepare("SELECT * FROM books WHERE available_quantity > 0");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function search($keyword) {
    $stmt = $this->db->prepare("
        SELECT * FROM books 
        WHERE title LIKE :keyword 
        OR author LIKE :keyword 
        OR isbn LIKE :keyword
        ORDER BY created_at DESC
    ");
    $keyword = "%$keyword%";
    $stmt->execute([':keyword' => $keyword]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

public function getCategoryStats() {
    $stmt = $this->db->query("
        SELECT 
            CASE 
                WHEN category IS NULL OR category = '' THEN 'Non classé'
                ELSE category 
            END as category,
            COUNT(*) as count 
        FROM books 
        GROUP BY category 
        ORDER BY count DESC
    ");
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Si aucun résultat, ajouter des données par défaut
    if (empty($result)) {
        return [
            ['category' => 'Informatique', 'count' => 1],
            ['category' => 'Sciences', 'count' => 1],
            ['category' => 'Histoire', 'count' => 1],
            ['category' => 'Littérature', 'count' => 1]
        ];
    }
    return $result;
}

/**
 * Obtenir des recommandations personnalisées pour un utilisateur
 */
public function getPersonalizedRecommendations($userId, $limit = 6) {
    $recommendations = [];
    
    // 1. Recommandations basées sur la filière d'étude
    $user = new User();
    $userData = $user->findById($userId);
    $fieldOfStudy = $userData['field_of_study'] ?? null;
    
    if ($fieldOfStudy) {
        $category = $this->getCategoryFromField($fieldOfStudy);
        $stmt = $this->db->prepare("
            SELECT *, 'basé sur votre filière' as reason
            FROM books 
            WHERE category = :category 
            AND available_quantity > 0
            ORDER BY RAND()
            LIMIT 2
        ");
        $stmt->execute([':category' => $category]);
        $fieldRecommendations = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $recommendations = array_merge($recommendations, $fieldRecommendations);
    }
    
    // 2. Recommandations basées sur l'historique d'emprunts
    $stmt = $this->db->prepare("
        SELECT b.category, COUNT(*) as count
        FROM loans l
        JOIN books b ON l.book_id = b.id
        WHERE l.user_id = :user_id AND b.category IS NOT NULL
        GROUP BY b.category
        ORDER BY count DESC
        LIMIT 2
    ");
    $stmt->execute([':user_id' => $userId]);
    $favoriteCategories = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (!empty($favoriteCategories)) {
        $category = $favoriteCategories[0]['category'];
        $stmt = $this->db->prepare("
            SELECT *, 'parce que vous aimez les livres de " . $category . "' as reason
            FROM books 
            WHERE category = :category 
            AND available_quantity > 0
            AND id NOT IN (SELECT book_id FROM loans WHERE user_id = :user_id)
            ORDER BY RAND()
            LIMIT 2
        ");
        $stmt->execute([':category' => $category, ':user_id' => $userId]);
        $historyRecommendations = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $recommendations = array_merge($recommendations, $historyRecommendations);
    }
    
    // 3. Recommandations basées sur les livres populaires
    $stmt = $this->db->prepare("
        SELECT b.*, COUNT(l.id) as loan_count, 
               'livre populaire parmi nos lecteurs' as reason
        FROM books b
        LEFT JOIN loans l ON b.id = l.book_id
        WHERE b.available_quantity > 0
        GROUP BY b.id
        ORDER BY loan_count DESC
        LIMIT ?
    ");
    $limitNeeded = $limit - count($recommendations);
    if ($limitNeeded > 0) {
        $stmt->bindParam(1, $limitNeeded, PDO::PARAM_INT);
        $stmt->execute();
        $popularRecommendations = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $recommendations = array_merge($recommendations, $popularRecommendations);
    }
    
    // 4. Si encore trop peu, ajouter des nouveautés
    if (count($recommendations) < $limit) {
        $stmt = $this->db->prepare("
            SELECT *, 'nouvelle arrivée' as reason
            FROM books 
            WHERE available_quantity > 0
            ORDER BY created_at DESC
            LIMIT ?
        ");
        $remaining = $limit - count($recommendations);
        $stmt->bindParam(1, $remaining, PDO::PARAM_INT);
        $stmt->execute();
        $newRecommendations = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $recommendations = array_merge($recommendations, $newRecommendations);
    }
    
    return array_slice($recommendations, 0, $limit);
}

/**
 * Convertir une filière en catégorie de livre
 */
private function getCategoryFromField($field) {
    $fieldCategories = [
        'informatique' => 'Informatique',
        'génie_logiciel' => 'Informatique',
        'data science' => 'Informatique',
        'physique' => 'Sciences',
        'chimie' => 'Sciences',
        'biologie' => 'Sciences',
        'mathématiques' => 'Sciences',
        'histoire' => 'Histoire',
        'littérature' => 'Littérature',
        'philosophie' => 'Philosophie',
        'art' => 'Art',
        'économie' => 'Économie',
        'gestion' => 'Économie',
    ];
    
    $fieldLower = strtolower($field);
    foreach ($fieldCategories as $key => $category) {
        if (strpos($fieldLower, $key) !== false) {
            return $category;
        }
    }
    return null;
}

/**
 * Obtenir les livres les plus populaires
 */
public function getPopularBooks($limit = 10) {
    $stmt = $this->db->prepare("
        SELECT b.*, COUNT(l.id) as loan_count
        FROM books b
        LEFT JOIN loans l ON b.id = l.book_id
        GROUP BY b.id
        ORDER BY loan_count DESC
        LIMIT :limit
    ");
    $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

}
?>
