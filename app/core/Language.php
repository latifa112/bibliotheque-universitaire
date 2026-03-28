<?php
class Language {
    private static $instance = null;
    private $translations = [];
    private $currentLang = 'fr';
    private $availableLangs = ['fr', 'en', 'ar'];
    private $cookieName = 'bibliogest_lang';
    
    private function __construct() {
        // Priorité : Session > Cookie > Fr
        if (isset($_SESSION['lang'])) {
            $this->currentLang = $_SESSION['lang'];
        } elseif (isset($_COOKIE[$this->cookieName])) {
            $this->currentLang = $_COOKIE[$this->cookieName];
            $_SESSION['lang'] = $this->currentLang;
        } else {
            $this->currentLang = 'fr';
        }
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
            // Stocker dans un cookie qui expire dans 1 an
            setcookie($this->cookieName, $lang, time() + 365 * 24 * 3600, '/');
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