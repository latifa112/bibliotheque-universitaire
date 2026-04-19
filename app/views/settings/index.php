<?php
$activePage = 'settings';

// Initialisation sécurisée de toutes les variables
$user = $user ?? [];
$user['first_name'] = $user['first_name'] ?? '';
$user['last_name'] = $user['last_name'] ?? '';
$user['email'] = $user['email'] ?? $_SESSION['user_email'] ?? '';
$user['username'] = $user['username'] ?? $_SESSION['username'] ?? '';
$user['created_at'] = $user['created_at'] ?? date('Y-m-d H:i:s');

$totalBooks = $totalBooks ?? 0;
$activeLoans = $activeLoans ?? 0;
$totalUsers = $totalUsers ?? 0;
$totalReservations = $totalReservations ?? 0;
$total_loans = $total_loans ?? 0;
$active_loans = $active_loans ?? 0;
$preferences = $preferences ?? ['notifications' => true, 'language' => 'fr', 'theme' => 'dark'];
?>

<div class="settings-container">
    <!-- Hero Section -->
    <div class="settings-hero">
        <div class="hero-content">
            <div class="hero-icon">
                <i class="fas fa-sliders-h"></i>
            </div>
            <div class="hero-text">
                <h1>Paramètres</h1>
                <p>Personnalisez votre expérience</p>
            </div>
        </div>
        <div class="hero-stats">
            <div class="hero-stat">
                <div class="stat-number" id="memberDays">-</div>
                <div class="stat-label">Jours d'activité</div>
            </div>
            <div class="hero-stat">
                <div class="stat-number"><?php echo $total_loans; ?></div>
                <div class="stat-label">Livres lus</div>
            </div>
            <div class="hero-stat">
                <div class="stat-number"><?php echo $active_loans; ?></div>
                <div class="stat-label">En cours</div>
            </div>
        </div>
    </div>

    <div class="settings-grid">
        <!-- Carte Profil -->
        <div class="settings-card profile-card">
            <div class="card-decoration"></div>
            <div class="card-header">
                <div class="header-icon-wrapper">
                    <i class="fas fa-user-astronaut"></i>
                </div>
                <div class="header-info">
                    <h2>Informations personnelles</h2>
                    <p>Modifiez vos informations de profil</p>
                </div>
            </div>
            <div class="card-body">
                <form id="profileForm" class="modern-form">
                    <div class="form-row">
                        <div class="input-group">
                            <i class="fas fa-user"></i>
                            <input type="text" id="first_name" name="first_name" value="<?php echo htmlspecialchars($user['first_name']); ?>" required>
                            <label>Prénom</label>
                        </div>
                        <div class="input-group">
                            <i class="fas fa-user"></i>
                            <input type="text" id="last_name" name="last_name" value="<?php echo htmlspecialchars($user['last_name']); ?>" required>
                            <label>Nom</label>
                        </div>
                    </div>
                    <div class="input-group">
                        <i class="fas fa-envelope"></i>
                        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                        <label>Email</label>
                    </div>
                    <button type="submit" class="btn-submit">
                        <i class="fas fa-save"></i>
                        <span>Enregistrer</span>
                        <i class="fas fa-arrow-right"></i>
                    </button>
                </form>
            </div>
        </div>

        <!-- Carte Sécurité -->
        <div class="settings-card security-card">
            <div class="card-decoration"></div>
            <div class="card-header">
                <div class="header-icon-wrapper">
                    <i class="fas fa-shield-alt"></i>
                </div>
                <div class="header-info">
                    <h2>Sécurité</h2>
                    <p>Protégez votre compte</p>
                </div>
            </div>
            <div class="card-body">
                <form id="passwordForm" class="modern-form">
                    <div class="input-group">
                        <i class="fas fa-lock"></i>
                        <input type="password" id="current_password" name="current_password" required>
                        <label>Mot de passe actuel</label>
                    </div>
                    <div class="input-group">
                        <i class="fas fa-key"></i>
                        <input type="password" id="new_password" name="new_password" required>
                        <label>Nouveau mot de passe</label>
                    </div>
                    <div class="password-strength-container">
                        <div class="password-strength">
                            <div class="strength-bar" id="strengthBar"></div>
                        </div>
                        <div class="strength-text" id="strengthText"></div>
                    </div>
                    <div class="input-group">
                        <i class="fas fa-check-circle"></i>
                        <input type="password" id="confirm_password" name="confirm_password" required>
                        <label>Confirmer le mot de passe</label>
                    </div>
                    <button type="submit" class="btn-submit">
                        <i class="fas fa-key"></i>
                        <span>Changer le mot de passe</span>
                        <i class="fas fa-arrow-right"></i>
                    </button>
                </form>
            </div>
        </div>

        <!-- Carte Préférences -->
        <div class="settings-card preferences-card">
            <div class="card-decoration"></div>
            <div class="card-header">
                <div class="header-icon-wrapper">
                    <i class="fas fa-palette"></i>
                </div>
                <div class="header-info">
                    <h2>Apparence</h2>
                    <p>Personnalisez l'interface</p>
                </div>
            </div>
            <div class="card-body">
                <form id="preferencesForm" class="modern-form">
                    <div class="toggle-group">
                        <div class="toggle-item">
                            <div class="toggle-info">
                                <i class="fas fa-bell"></i>
                                <div>
                                    <span class="toggle-label">Notifications</span>
                                    <span class="toggle-desc">Recevoir des alertes</span>
                                </div>
                            </div>
                            <label class="switch">
                                <input type="checkbox" name="notifications" <?php echo (isset($preferences['notifications']) && $preferences['notifications']) ? 'checked' : ''; ?>>
                                <span class="slider round"></span>
                            </label>
                        </div>
                    </div>
                    <div class="select-group">
                        <div class="select-item">
                            <i class="fas fa-globe"></i>
                            <div class="select-info">
                                <span class="select-label">Langue</span>
                                <span class="select-desc">Choisissez votre langue</span>
                            </div>
                            <select id="language" name="language">
                                <option value="fr" <?php echo $preferences['language'] == 'fr' ? 'selected' : ''; ?>>🇫🇷 Français</option>
                                <option value="en" <?php echo $preferences['language'] == 'en' ? 'selected' : ''; ?>>🇬🇧 English</option>
                                <option value="ar" <?php echo $preferences['language'] == 'ar' ? 'selected' : ''; ?>>🇸🇦 العربية</option>
                            </select>
                        </div>
                    </div>
                    <div class="theme-options">
                        <div class="theme-option dark <?php echo $preferences['theme'] == 'dark' ? 'active' : ''; ?>" data-theme="dark">
                            <i class="fas fa-moon"></i>
                            <span>Sombre</span>
                        </div>
                        <div class="theme-option light <?php echo $preferences['theme'] == 'light' ? 'active' : ''; ?>" data-theme="light">
                            <i class="fas fa-sun"></i>
                            <span>Clair</span>
                        </div>
                    </div>
                    <button type="submit" class="btn-submit">
                        <i class="fas fa-save"></i>
                        <span>Enregistrer</span>
                        <i class="fas fa-arrow-right"></i>
                    </button>
                </form>
            </div>
        </div>

        <!-- Section Statistiques -->
        <div class="settings-card stats-card">
            <div class="card-decoration"></div>
            <div class="card-header">
                <div class="header-icon-wrapper">
                    <i class="fas fa-chart-line"></i>
                </div>
                <div class="header-info">
                    <h2>Statistiques</h2>
                    <p>Votre parcours de lecture</p>
                </div>
            </div>
            <div class="card-body">
                <div class="stats-grid-custom">
                    <div class="stat-circle">
                        <svg viewBox="0 0 100 100">
                            <circle cx="50" cy="50" r="45" fill="none" stroke="rgba(255,255,255,0.1)" stroke-width="8"/>
                            <circle class="progress-ring" cx="50" cy="50" r="45" fill="none" stroke="url(#gradient)" stroke-width="8" stroke-linecap="round" stroke-dasharray="283" stroke-dashoffset="283"/>
                            <defs>
                                <linearGradient id="gradient" x1="0%" y1="0%" x2="100%" y2="0%">
                                    <stop offset="0%" stop-color="#6366f1"/>
                                    <stop offset="100%" stop-color="#8b5cf6"/>
                                </linearGradient>
                            </defs>
                        </svg>
                        <div class="stat-circle-content">
                            <span class="circle-value" id="completionRate">0</span>
                            <span class="circle-label">%</span>
                        </div>
                    </div>
                    <div class="stats-list-modern">
                        <div class="stat-item-modern">
                            <div class="stat-icon">
                                <i class="fas fa-calendar-alt"></i>
                            </div>
                            <div class="stat-details">
                                <span class="stat-label">Membre depuis</span>
                                <span class="stat-value"><?php echo date('d/m/Y', strtotime($user['created_at'])); ?></span>
                            </div>
                        </div>
                        <div class="stat-item-modern">
                            <div class="stat-icon">
                                <i class="fas fa-book-reader"></i>
                            </div>
                            <div class="stat-details">
                                <span class="stat-label">Livres empruntés</span>
                                <span class="stat-value"><?php echo $total_loans; ?></span>
                            </div>
                        </div>
                        <div class="stat-item-modern">
                            <div class="stat-icon">
                                <i class="fas fa-hourglass-half"></i>
                            </div>
                            <div class="stat-details">
                                <span class="stat-label">Emprunts actifs</span>
                                <span class="stat-value"><?php echo $active_loans; ?></span>
                            </div>
                        </div>
                        <div class="stat-item-modern">
                            <div class="stat-icon">
                                <i class="fas fa-check-circle"></i>
                            </div>
                            <div class="stat-details">
                                <span class="stat-label">Retours effectués</span>
                                <span class="stat-value"><?php echo $total_loans - $active_loans; ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Zone dangereuse -->
        <div class="settings-card danger-card">
            <div class="card-decoration"></div>
            <div class="card-header">
                <div class="header-icon-wrapper">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <div class="header-info">
                    <h2>Zone dangereuse</h2>
                    <p>Actions irréversibles</p>
                </div>
            </div>
            <div class="card-body">
                <div class="danger-content">
                    <i class="fas fa-skull"></i>
                    <p>La suppression de votre compte est définitive et irréversible.</p>
                    <button class="btn-danger" onclick="deleteAccount()">
                        <i class="fas fa-trash-alt"></i>
                        Supprimer mon compte
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.settings-container {
    max-width: 1400px;
    margin: 0 auto;
}

.settings-hero {
    background: linear-gradient(135deg, rgba(99, 102, 241, 0.15), rgba(139, 92, 246, 0.05));
    border-radius: 32px;
    padding: 2rem;
    margin-bottom: 2rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 1.5rem;
    border: 1px solid var(--border-color);
}

.hero-content {
    display: flex;
    align-items: center;
    gap: 1.5rem;
}

.hero-icon {
    width: 70px;
    height: 70px;
    background: linear-gradient(135deg, #6366f1, #8b5cf6);
    border-radius: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2rem;
    box-shadow: 0 10px 30px rgba(99, 102, 241, 0.3);
}

.hero-text h1 {
    font-size: 2rem;
    margin-bottom: 0.25rem;
    background: linear-gradient(135deg, #fff, #a5b4fc);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

.hero-text p {
    color: var(--text-secondary);
    font-size: 0.9rem;
}

.hero-stats {
    display: flex;
    gap: 2rem;
}

.hero-stat {
    text-align: center;
    padding: 0.5rem 1rem;
    background: rgba(255, 255, 255, 0.05);
    border-radius: 16px;
    min-width: 100px;
}

.hero-stat .stat-number {
    font-size: 1.5rem;
    font-weight: 700;
    color: #6366f1;
}

.hero-stat .stat-label {
    font-size: 0.7rem;
    color: rgba(255, 255, 255, 0.5);
}

.settings-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1.5rem;
}

.settings-card {
    position: relative;
    background: var(--card-bg);
    border: 1px solid var(--border-color);
    border-radius: 28px;
    overflow: hidden;
    transition: all 0.3s ease;
}

.settings-card:hover {
    transform: translateY(-5px);
    border-color: #6366f1;
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
}

.card-decoration {
    position: absolute;
    top: 0;
    right: 0;
    width: 150px;
    height: 150px;
    background: radial-gradient(circle, rgba(99, 102, 241, 0.1) 0%, transparent 70%);
    pointer-events: none;
}

.card-header {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1.5rem;
    border-bottom: 1px solid var(--border-color);
    background: rgba(99, 102, 241, 0.05);
}

.header-icon-wrapper {
    width: 50px;
    height: 50px;
    background: linear-gradient(135deg, #6366f1, #8b5cf6);
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
}

.header-info h2 {
    font-size: 1.2rem;
    margin-bottom: 0.25rem;
}

.header-info p {
    font-size: 0.8rem;
    color: var(--text-secondary);
}

.card-body {
    padding: 1.5rem;
}

.modern-form .input-group {
    position: relative;
    margin-bottom: 1.25rem;
}

.modern-form .input-group i {
    position: absolute;
    left: 1rem;
    top: 50%;
    transform: translateY(-50%);
    color: #6366f1;
    font-size: 1rem;
    z-index: 2;
}

.modern-form .input-group input {
    width: 100%;
    padding: 1rem 1rem 1rem 2.8rem;
    background: var(--input-bg);
    border: 1px solid var(--input-border);
    border-radius: 14px;
    color: var(--text-primary);
    font-size: 0.95rem;
    transition: all 0.3s;
}

.modern-form .input-group input:focus {
    outline: none;
    border-color: #6366f1;
    background: var(--bg-secondary);
}

.modern-form .input-group label {
    position: absolute;
    left: 2.8rem;
    top: 1rem;
    color: var(--label-color);
    pointer-events: none;
    transition: all 0.3s;
}

.modern-form .input-group input:focus ~ label,
.modern-form .input-group input:not(:placeholder-shown) ~ label {
    top: -0.5rem;
    left: 2rem;
    font-size: 0.7rem;
    background: var(--bg-secondary);
    padding: 0 0.25rem;
    color: #6366f1;
}

.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
    margin-bottom: 1.25rem;
}

.btn-submit {
    width: 100%;
    padding: 0.875rem;
    background: linear-gradient(135deg, #10b981, #059669);
    border: none;
    border-radius: 14px;
    color: white;
    font-weight: 600;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.75rem;
    transition: all 0.3s;
    margin-top: 0.5rem;
}

.btn-submit:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 25px rgba(16, 185, 129, 0.4);
}

.password-strength-container {
    margin: -0.5rem 0 1rem 0;
}

.password-strength {
    height: 4px;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 2px;
    overflow: hidden;
}

.strength-bar {
    height: 100%;
    width: 0;
    transition: width 0.3s;
}

.strength-text {
    font-size: 0.7rem;
    margin-top: 0.25rem;
    color: rgba(255, 255, 255, 0.5);
}

.toggle-group {
    margin-bottom: 1.5rem;
}

.toggle-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1rem;
    background: rgba(255, 255, 255, 0.03);
    border-radius: 16px;
}

.toggle-info {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.toggle-info i {
    font-size: 1.2rem;
    color: #6366f1;
}

.toggle-label {
    display: block;
    font-weight: 500;
    margin-bottom: 0.25rem;
}

.toggle-desc {
    font-size: 0.7rem;
    color: rgba(255, 255, 255, 0.5);
}

.switch {
    position: relative;
    display: inline-block;
    width: 50px;
    height: 24px;
}

.switch input {
    opacity: 0;
    width: 0;
    height: 0;
}

.slider {
    position: absolute;
    cursor: pointer;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: rgba(255, 255, 255, 0.2);
    transition: 0.3s;
    border-radius: 34px;
}

.slider:before {
    position: absolute;
    content: "";
    height: 18px;
    width: 18px;
    left: 3px;
    bottom: 3px;
    background-color: white;
    transition: 0.3s;
    border-radius: 50%;
}

input:checked + .slider {
    background: linear-gradient(135deg, #10b981, #059669);
}

input:checked + .slider:before {
    transform: translateX(26px);
}

.select-group {
    margin-bottom: 1.5rem;
}

.select-item {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1rem;
    background: rgba(255, 255, 255, 0.03);
    border-radius: 16px;
}

.select-item i {
    font-size: 1.2rem;
    color: #6366f1;
}

.select-info {
    flex: 1;
}

.select-label {
    display: block;
    font-weight: 500;
    margin-bottom: 0.25rem;
}

.select-desc {
    font-size: 0.7rem;
    color: rgba(255, 255, 255, 0.5);
}

.select-item select {
    background: rgba(255, 255, 255, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.2);
    border-radius: 10px;
    padding: 0.5rem 1rem;
    color: white;
    cursor: pointer;
}

.theme-options {
    display: flex;
    gap: 1rem;
    margin-bottom: 1.5rem;
}

.theme-option {
    flex: 1;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0.5rem;
    padding: 1rem;
    background: rgba(255, 255, 255, 0.03);
    border: 2px solid rgba(255, 255, 255, 0.1);
    border-radius: 16px;
    cursor: pointer;
    transition: all 0.3s;
}

.theme-option i {
    font-size: 1.5rem;
}

.theme-option.active {
    border-color: #6366f1;
    background: rgba(99, 102, 241, 0.1);
}

.stats-grid-custom {
    display: flex;
    gap: 1.5rem;
    align-items: center;
}

.stat-circle {
    position: relative;
    width: 120px;
    height: 120px;
}

.stat-circle svg {
    width: 100%;
    height: 100%;
    transform: rotate(-90deg);
}

.progress-ring {
    stroke-dashoffset: calc(283 - (283 * 0) / 100);
    transition: stroke-dashoffset 0.5s;
}

.stat-circle-content {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    text-align: center;
}

.circle-value {
    font-size: 1.5rem;
    font-weight: 700;
    color: #6366f1;
}

.circle-label {
    font-size: 0.8rem;
    color: rgba(255, 255, 255, 0.5);
}

.stats-list-modern {
    flex: 1;
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
}

.stat-item-modern {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 0.75rem;
    background: rgba(255, 255, 255, 0.03);
    border-radius: 12px;
}

.stat-icon {
    width: 35px;
    height: 35px;
    background: rgba(99, 102, 241, 0.2);
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #6366f1;
}

.stat-details {
    flex: 1;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.stat-label {
    font-size: 0.8rem;
    color: rgba(255, 255, 255, 0.6);
}

.stat-value {
    font-weight: 600;
    color: #6366f1;
}

.danger-card {
    border-color: rgba(239, 68, 68, 0.3);
}

.danger-card .card-header .header-icon-wrapper {
    background: linear-gradient(135deg, #ef4444, #dc2626);
}

.danger-content {
    text-align: center;
    padding: 1rem 0;
}

.danger-content i {
    font-size: 3rem;
    color: #ef4444;
    margin-bottom: 1rem;
}

.danger-content p {
    color: rgba(255, 255, 255, 0.6);
    margin-bottom: 1.5rem;
}

.btn-danger {
    padding: 0.875rem 1.5rem;
    background: rgba(239, 68, 68, 0.2);
    border: 1px solid rgba(239, 68, 68, 0.3);
    border-radius: 14px;
    color: #ef4444;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
}

.btn-danger:hover {
    background: #ef4444;
    color: white;
    transform: translateY(-2px);
}

@media (max-width: 1024px) {
    .settings-grid {
        grid-template-columns: 1fr;
    }
    .form-row {
        grid-template-columns: 1fr;
    }
    .stats-grid-custom {
        flex-direction: column;
    }
    .hero-stats {
        width: 100%;
        justify-content: space-around;
    }
}
</style>

<script>
// Force du mot de passe
const passwordInput = document.getElementById('new_password');
const strengthBar = document.getElementById('strengthBar');
const strengthText = document.getElementById('strengthText');

if (passwordInput) {
    passwordInput.addEventListener('input', function() {
        const val = this.value;
        let strength = 0;
        
        if (val.length >= 6) strength += 20;
        if (val.match(/[a-z]+/)) strength += 20;
        if (val.match(/[A-Z]+/)) strength += 20;
        if (val.match(/[0-9]+/)) strength += 20;
        if (val.match(/[$@#&!]+/)) strength += 20;
        
        strengthBar.style.width = strength + '%';
        
        if (strength < 30) {
            strengthBar.style.background = '#ef4444';
            strengthText.textContent = 'Faible';
            strengthText.style.color = '#ef4444';
        } else if (strength < 60) {
            strengthBar.style.background = '#f59e0b';
            strengthText.textContent = 'Moyen';
            strengthText.style.color = '#f59e0b';
        } else if (strength < 80) {
            strengthBar.style.background = '#3b82f6';
            strengthText.textContent = 'Bon';
            strengthText.style.color = '#3b82f6';
        } else {
            strengthBar.style.background = '#10b981';
            strengthText.textContent = 'Excellent';
            strengthText.style.color = '#10b981';
        }
    });
}

// Thème options
document.querySelectorAll('.theme-option').forEach(option => {
    option.addEventListener('click', function() {
        document.querySelectorAll('.theme-option').forEach(o => o.classList.remove('active'));
        this.classList.add('active');
        
        const theme = this.dataset.theme;
        if (theme === 'light') {
            document.body.classList.remove('dark-theme');
            document.body.classList.add('light-theme');
        } else {
            document.body.classList.remove('light-theme');
            document.body.classList.add('dark-theme');
        }
        localStorage.setItem('theme', theme);
    });
});

// Charger le thème
const savedTheme = localStorage.getItem('theme');
if (savedTheme) {
    if (savedTheme === 'light') {
        document.body.classList.remove('dark-theme');
        document.body.classList.add('light-theme');
    } else {
        document.body.classList.remove('light-theme');
        document.body.classList.add('dark-theme');
    }
    document.querySelectorAll('.theme-option').forEach(opt => {
        if (opt.dataset.theme === savedTheme) opt.classList.add('active');
        else opt.classList.remove('active');
    });
}

// Formulaire profil
document.getElementById('profileForm')?.addEventListener('submit', async (e) => {
    e.preventDefault();
    const data = {
        first_name: document.getElementById('first_name').value,
        last_name: document.getElementById('last_name').value,
        email: document.getElementById('email').value
    };
    
    const response = await fetch('/settings/update-profile', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(data)
    });
    const result = await response.json();
    alert(result.message);
    if (result.success) location.reload();
});

// Formulaire mot de passe
document.getElementById('passwordForm')?.addEventListener('submit', async (e) => {
    e.preventDefault();
    const newPass = document.getElementById('new_password').value;
    const confirmPass = document.getElementById('confirm_password').value;
    
    if (newPass !== confirmPass) {
        alert('Les mots de passe ne correspondent pas');
        return;
    }
    
    const data = {
        current_password: document.getElementById('current_password').value,
        new_password: newPass
    };
    
    const response = await fetch('/settings/update-password', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(data)
    });
    const result = await response.json();
    alert(result.message);
    if (result.success) document.getElementById('passwordForm').reset();
});

// Formulaire préférences
document.getElementById('preferencesForm')?.addEventListener('submit', async (e) => {
    e.preventDefault();
    const selectedTheme = document.querySelector('.theme-option.active')?.dataset.theme || 'dark';
    const data = {
        notifications: document.querySelector('input[name="notifications"]')?.checked || false,
        language: document.getElementById('language')?.value || 'fr',
        theme: selectedTheme
    };
    
    const response = await fetch('/settings/update-preferences', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(data)
    });
    const result = await response.json();
    alert(result.message);
    if (result.success && result.language !== '<?php echo $preferences['language']; ?>') {
        location.reload();
    }
});

// Cercle de progression
document.addEventListener('DOMContentLoaded', function() {
    const totalLoans = <?php echo $total_loans; ?>;
    const activeLoans = <?php echo $active_loans; ?>;
    
    if (totalLoans > 0) {
        const completionRate = Math.round(((totalLoans - activeLoans) / totalLoans) * 100);
        const completionElement = document.getElementById('completionRate');
        if (completionElement) completionElement.textContent = completionRate;
        
        const circle = document.querySelector('.progress-ring');
        if (circle) {
            const circumference = 2 * Math.PI * 45;
            const offset = circumference - (completionRate / 100) * circumference;
            circle.style.strokeDashoffset = offset;
        }
    }
});

// Jours d'activité
const createdDate = '<?php echo $user['created_at']; ?>';
if (createdDate) {
    const days = Math.floor((new Date() - new Date(createdDate)) / (1000 * 60 * 60 * 24));
    document.getElementById('memberDays').textContent = days;
}

function deleteAccount() {
    if (!confirm('⚠️ Êtes-vous sûr de vouloir supprimer votre compte ? Cette action est irréversible !')) return;
    if (!confirm('⚠️ DERNIÈRE CHANCE : Voulez-vous vraiment supprimer votre compte définitivement ?')) return;
    alert('Cette fonctionnalité sera bientôt disponible');
}
</script>