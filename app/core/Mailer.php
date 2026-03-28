<?php
class Mailer {
    
    public function sendResetPassword($to, $name, $resetLink) {
        // Version simplifiée pour le développement
        // Affiche le lien dans les logs
        error_log("=========================================");
        error_log("DEMANDE DE RÉINITIALISATION DE MOT DE PASSE");
        error_log("Email: $to");
        error_log("Nom: $name");
        error_log("Lien de réinitialisation: $resetLink");
        error_log("=========================================");
        
        // Pour le développement, on retourne true
        return true;
    }
    
    public function sendWelcomeEmail($to, $name) {
        error_log("EMAIL DE BIENVENUE - Email: $to, Nom: $name");
        return true;
    }
}
?>