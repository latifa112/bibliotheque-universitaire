<?php
class Language {
    private static $instance = null;
    private $translations = [];
    private $currentLang = 'fr';
    private $availableLangs = ['fr', 'en', 'ar'];
    
    private function __construct() {
        $this->currentLang = $_SESSION['lang'] ?? 'fr';
        $this->loadLanguage();
    }
    
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new Language();
        }
        return self::$instance;
    }
    
    private function loadLanguage() {
        $langFile = ROOT . "/lang/{$this->currentLang}.php";
        if (file_exists($langFile)) {
            $this->translations = include $langFile;
        } else {
            // Fallback au français si le fichier n'existe pas
            $fallbackFile = ROOT . "/lang/fr.php";
            if (file_exists($fallbackFile)) {
                $this->translations = include $fallbackFile;
            } else {
                $this->translations = [];
            }
        }
    }
    
    public function get($key, $default = null) {
        return $this->translations[$key] ?? $default ?? $key;
    }
    
    public function setLanguage($lang) {
        if (in_array($lang, $this->availableLangs)) {
            $this->currentLang = $lang;
            $_SESSION['lang'] = $lang;
            $this->loadLanguage();
            return true;
        }
        return false;
    }
    
    public function getCurrentLang() {
        return $this->currentLang;
    }
    
    public function getAvailableLangs() {
        return $this->availableLangs;
    }
}

// Fonction helper pour utiliser dans les vues
function __($key, $default = null) {
    return Language::getInstance()->get($key, $default);
}
?>