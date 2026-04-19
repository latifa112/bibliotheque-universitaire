<?php
class ApiController extends Controller {
    
    public function getRecommendations() {
        if (!$this->isLoggedIn()) {
            $this->json(['success' => false, 'message' => 'Non connecté']);
            return;
        }
        
        $book = new Book();
        $recommendations = $book->getPersonalizedRecommendations($_SESSION['user_id'], 6);
        
        $this->json(['success' => true, 'recommendations' => $recommendations]);
    }
}
?>
