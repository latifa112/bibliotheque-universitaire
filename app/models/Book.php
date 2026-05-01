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
    try {
        // 1. Récupérer l'historique des emprunts de l'utilisateur avec catégories
        $stmt = $this->db->prepare("
            SELECT DISTINCT b.category, COUNT(*) as count, GROUP_CONCAT(DISTINCT b.author) as authors
            FROM loans l
            JOIN books b ON l.book_id = b.id
            WHERE l.user_id = :user_id AND b.category IS NOT NULL AND b.category != ''
            GROUP BY b.category
            ORDER BY count DESC
            LIMIT 3
        ");
        $stmt->execute([':user_id' => $userId]);
        $userCategories = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $recommendations = [];
        
        // 2. Recommandations basées sur les catégories préférées
        if (!empty($userCategories)) {
            foreach ($userCategories as $cat) {
                $category = $cat['category'];
                $stmt = $this->db->prepare("
                    SELECT b.*, 
                           'similar_category' as type,
                           :category as matched_category,
                           '📚 Parce que vous aimez la catégorie ' || :category || '' as reason
                    FROM books b
                    WHERE b.category = :category 
                    AND b.available_quantity > 0
                    AND b.id NOT IN (
                        SELECT book_id FROM loans WHERE user_id = :user_id
                    )
                    ORDER BY RANDOM()
                    LIMIT 2
                ");
                $stmt->execute([
                    ':category' => $category,
                    ':user_id' => $userId
                ]);
                $catBooks = $stmt->fetchAll(PDO::FETCH_ASSOC);
                foreach ($catBooks as $book) {
                    $book['reason'] = "📚 Parce que vous aimez la catégorie « " . $this->getCategoryName($category) . " »";
                    $book['type'] = 'category';
                    $recommendations[] = $book;
                }
            }
        }
        
        // 3. Recommandations basées sur les auteurs préférés
        $stmt = $this->db->prepare("
            SELECT b.author, COUNT(*) as count
            FROM loans l
            JOIN books b ON l.book_id = b.id
            WHERE l.user_id = :user_id AND b.author IS NOT NULL
            GROUP BY b.author
            ORDER BY count DESC
            LIMIT 2
        ");
        $stmt->execute([':user_id' => $userId]);
        $favoriteAuthors = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        if (!empty($favoriteAuthors)) {
            foreach ($favoriteAuthors as $authorData) {
                $author = $authorData['author'];
                $stmt = $this->db->prepare("
                    SELECT b.*, 
                           'same_author' as type,
                           :author as matched_author,
                           '✍️ Vous avez aimé les livres de ' || :author || ' - Découvrez ses autres œuvres' as reason
                    FROM books b
                    WHERE b.author = :author 
                    AND b.available_quantity > 0
                    AND b.id NOT IN (
                        SELECT book_id FROM loans WHERE user_id = :user_id
                    )
                    ORDER BY RANDOM()
                    LIMIT 2
                ");
                $stmt->execute([
                    ':author' => $author,
                    ':user_id' => $userId
                ]);
                $authorBooks = $stmt->fetchAll(PDO::FETCH_ASSOC);
                foreach ($authorBooks as $book) {
                    $book['reason'] = "✍️ Vous avez aimé « " . $author . " » - Découvrez ses autres œuvres";
                    $book['type'] = 'author';
                    $recommendations[] = $book;
                }
            }
        }
        
        // 4. Livres populaires (les plus empruntés)
        if (count($recommendations) < $limit) {
            $stmt = $this->db->prepare("
                SELECT b.*, 
                       COUNT(l.id) as loan_count,
                       'popular' as type,
                       '🔥 Très populaire - ' || COUNT(l.id) || ' emprunts ce mois-ci' as reason
                FROM books b
                LEFT JOIN loans l ON b.id = l.book_id AND l.loan_date >= date('now', '-30 days')
                WHERE b.available_quantity > 0
                AND b.id NOT IN (
                    SELECT book_id FROM loans WHERE user_id = :user_id
                )
                GROUP BY b.id
                ORDER BY loan_count DESC
                LIMIT :limit
            ");
            $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);
            $stmt->bindValue(':limit', $limit - count($recommendations), PDO::PARAM_INT);
            $stmt->execute();
            $popularBooks = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ($popularBooks as $book) {
                $book['reason'] = "🔥 Très populaire - " . ($book['loan_count'] ?? 0) . " emprunts ce mois-ci";
                $recommendations[] = $book;
            }
        }
        
        // 5. Nouveautés
        if (count($recommendations) < $limit) {
            $stmt = $this->db->prepare("
                SELECT b.*, 
                       'new' as type,
                       '✨ Nouvelle arrivée - Ajouté récemment à notre bibliothèque' as reason
                FROM books b
                WHERE b.available_quantity > 0
                AND b.id NOT IN (
                    SELECT book_id FROM loans WHERE user_id = :user_id
                )
                ORDER BY b.created_at DESC
                LIMIT :limit
            ");
            $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);
            $stmt->bindValue(':limit', $limit - count($recommendations), PDO::PARAM_INT);
            $stmt->execute();
            $newBooks = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ($newBooks as $book) {
                $book['reason'] = "✨ Nouvelle arrivée - Ajouté récemment à notre bibliothèque";
                $recommendations[] = $book;
            }
        }
        
        // Supprimer les doublons
        $uniqueRecommendations = [];
        $seenIds = [];
        foreach ($recommendations as $rec) {
            if (!in_array($rec['id'], $seenIds)) {
                $seenIds[] = $rec['id'];
                $uniqueRecommendations[] = $rec;
            }
        }
        
        // Limiter et retourner
        return array_slice($uniqueRecommendations, 0, $limit);
        
    } catch (Exception $e) {
        error_log("Erreur recommendations: " . $e->getMessage());
        // Fallback : livres récents
        $stmt = $this->db->prepare("
            SELECT b.*, 
                   'default' as type,
                   '📖 Découvrez notre sélection de livres récents' as reason
            FROM books b
            WHERE b.available_quantity > 0
            ORDER BY b.created_at DESC
            LIMIT :limit
        ");
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

private function getCategoryName($category) {
    $categories = [
        'computer_science' => 'Informatique',
        'sciences' => 'Sciences',
        'history' => 'Histoire',
        'literature' => 'Littérature',
        'art' => 'Art',
        'philosophy' => 'Philosophie',
        'psychology' => 'Psychologie',
        'economy' => 'Économie',
        'fiction' => 'Fiction',
        'science_fiction' => 'Science-Fiction',
        'biography' => 'Biographie',
        'self_help' => 'Développement personnel'
    ];
    return $categories[$category] ?? $category;
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
