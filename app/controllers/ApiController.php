<?php
class ApiController extends Controller {
    
    public function getRecommendations() {
        if (!$this->isLoggedIn()) {
            $this->json(['success' => false, 'message' => 'Non connecté']);
            return;
        }
        
        $book = new Book();
        $recommendations = $book->getPersonalizedRecommendations($_SESSION['user_id'], 6);
        
        // Ajouter des URLs d'images par défaut si nécessaire
        foreach ($recommendations as &$rec) {
            if (empty($rec['cover_image'])) {
                $rec['cover_image'] = 'https://images.unsplash.com/photo-1544716278-ca5e3f4abd8c?w=200';
            }
            if (empty($rec['reason'])) {
                $rec['reason'] = 'Recommandé pour vous';
            }
        }
        
        $this->json(['success' => true, 'recommendations' => $recommendations]);
    }
}
?>