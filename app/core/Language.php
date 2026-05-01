<?php
class Language {
    private static $instance = null;
    private $translations = [];
    private $currentLang = 'fr';
    private $availableLangs = ['fr', 'en', 'ar'];
    private $cookieName = 'bibliogest_lang';
    
    private function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_name('BIBLIOGEST_SESSION');
            session_start();
        }
        
        if (isset($_SESSION['lang'])) {
            $this->currentLang = $_SESSION['lang'];
        } elseif (isset($_COOKIE[$this->cookieName])) {
            $this->currentLang = $_COOKIE[$this->cookieName];
            $_SESSION['lang'] = $this->currentLang;
        } else {
            $this->currentLang = 'fr';
            $_SESSION['lang'] = 'fr';
        }
        
        if (!isset($_SESSION['preferences']['language'])) {
            $_SESSION['preferences']['language'] = $this->currentLang;
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
        if (isset($this->translations[$key])) {
            return $this->translations[$key];
        }
        return $default ?? $key;
    }
    
    public function setLanguage($lang) {
        if (in_array($lang, $this->availableLangs)) {
            $this->currentLang = $lang;
            $_SESSION['lang'] = $lang;
            $_SESSION['preferences']['language'] = $lang;
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
if (!function_exists('__')) {
    function __($key, $default = null) {
        return Language::getInstance()->get($key, $default);
    }
}
?>