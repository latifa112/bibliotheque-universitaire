<div style="text-align: center; padding: 4rem;">
    <i class="fas fa-search" style="font-size: 4rem; opacity: 0.5;"></i>
    <h1 style="margin: 2rem 0;">404 - Page non trouvée</h1>
    <p>La page que vous recherchez n'existe pas.</p>
    <a href="/dashboard" class="btn" style="margin-top: 2rem; display: inline-block;">
        <i class="fas fa-home"></i> Retour à l'accueil
    </a>
    <?php if (!isset($_SESSION['user_id'])): ?>
    <div style="margin-top: 1rem;">
        <a href="/login" style="color: #6366f1;">Se connecter</a> | 
        <a href="/register" style="color: #6366f1;">S'inscrire</a>
    </div>
    <?php endif; ?>
</div>