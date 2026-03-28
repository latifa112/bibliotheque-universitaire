<?php
$activePage = 'settings';
$totalBooks = $totalBooks ?? 0;
$activeLoans = $activeLoans ?? 0;
$totalUsers = $totalUsers ?? 0;
$totalReservations = $totalReservations ?? 0;
$preferences = $_SESSION['preferences'] ?? ['notifications' => true, 'language' => 'fr', 'theme' => 'dark'];
$user = $_SESSION;
$total_loans = $total_loans ?? 0;
$active_loans = $active_loans ?? 0;
?>

<div class="settings-container">
    <!-- Hero Section -->
    <div class="settings-hero">
        <div class="hero-content">
            <div class="hero-icon">
                <i class="fas fa-sliders-h"></i>
            </div>
            <div class="hero-text">
                <h1><?php echo __('settings'); ?></h1>
                <p><?php echo __('customize_experience'); ?></p>
            </div>
        </div>
        <div class="hero-stats">
            <div class="hero-stat">
                <div class="stat-number" id="memberDays">-</div>
                <div class="stat-label"><?php echo __('activity_days'); ?></div>
            </div>
            <div class="hero-stat">
                <div class="stat-number" id="totalLoans"><?php echo $total_loans; ?></div>
                <div class="stat-label"><?php echo __('books_read'); ?></div>
            </div>
            <div class="hero-stat">
                <div class="stat-number" id="activeLoans"><?php echo $active_loans; ?></div>
                <div class="stat-label"><?php echo __('in_progress'); ?></div>
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
                    <h2><?php echo __('personal_info'); ?></h2>
                    <p><?php echo __('edit_profile_info'); ?></p>
                </div>
            </div>
            <div class="card-body">
                <form id="profileForm" class="modern-form">
                    <div class="form-row">
                        <div class="input-group">
                            <i class="fas fa-user"></i>
                            <input type="text" id="first_name" name="first_name" value="<?php echo explode(' ', $user['user_name'])[0] ?? ''; ?>" required>
                            <label><?php echo __('first_name'); ?></label>
                        </div>
                        <div class="input-group">
                            <i class="fas fa-user"></i>
                            <input type="text" id="last_name" name="last_name" value="<?php echo explode(' ', $user['user_name'])[1] ?? ''; ?>" required>
                            <label><?php echo __('last_name'); ?></label>
                        </div>
                    </div>
                    <div class="input-group">
                        <i class="fas fa-envelope"></i>
                        <input type="email" id="email" name="email" value="<?php echo $user['user_email']; ?>" required>
                        <label><?php echo __('email'); ?></label>
                    </div>
                    <button type="submit" class="btn-submit">
                        <i class="fas fa-save"></i>
                        <span><?php echo __('save_changes'); ?></span>
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
                    <h2><?php echo __('security'); ?></h2>
                    <p><?php echo __('protect_account'); ?></p>
                </div>
            </div>
            <div class="card-body">
                <form id="passwordForm" class="modern-form">
                    <div class="input-group">
                        <i class="fas fa-lock"></i>
                        <input type="password" id="current_password" name="current_password" required>
                        <label><?php echo __('current_password'); ?></label>
                    </div>
                    <div class="input-group">
                        <i class="fas fa-key"></i>
                        <input type="password" id="new_password" name="new_password" required>
                        <label><?php echo __('new_password'); ?></label>
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
                        <label><?php echo __('confirm_password'); ?></label>
                    </div>
                    <button type="submit" class="btn-submit">
                        <i class="fas fa-key"></i>
                        <span><?php echo __('change_password'); ?></span>
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
                    <h2><?php echo __('appearance_preferences'); ?></h2>
                    <p><?php echo __('customize_interface'); ?></p>
                </div>
            </div>
            <div class="card-body">
                <form id="preferencesForm" class="modern-form">
                    <div class="toggle-group">
                        <div class="toggle-item">
                            <div class="toggle-info">
                                <i class="fas fa-bell"></i>
                                <div>
                                    <span class="toggle-label"><?php echo __('notifications'); ?></span>
                                    <span class="toggle-desc"><?php echo __('receive_alerts'); ?></span>
                                </div>
                            </div>
                            <label class="switch">
                                <input type="checkbox" name="notifications" <?php echo $preferences['notifications'] ? 'checked' : ''; ?>>
                                <span class="slider round"></span>
                            </label>
                        </div>
                    </div>
                    <div class="select-group">
                        <div class="select-item">
                            <i class="fas fa-globe"></i>
                            <div class="select-info">
                                <span class="select-label"><?php echo __('language'); ?></span>
                                <span class="select-desc"><?php echo __('choose_language'); ?></span>
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
                            <span><?php echo __('dark_theme'); ?></span>
                        </div>
                        <div class="theme-option light <?php echo $preferences['theme'] == 'light' ? 'active' : ''; ?>" data-theme="light">
                            <i class="fas fa-sun"></i>
                            <span><?php echo __('light_theme'); ?></span>
                        </div>
                    </div>
                    <button type="submit" class="btn-submit">
                        <i class="fas fa-save"></i>
                        <span><?php echo __('save_preferences'); ?></span>
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
                    <h2><?php echo __('statistics'); ?></h2>
                    <p><?php echo __('your_reading_journey'); ?></p>
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
                                <span class="stat-label"><?php echo __('member_since'); ?></span>
                                <span class="stat-value">
                                    <?php 
                                    if (isset($user['created_at']) && !empty($user['created_at'])) {
                                        echo date('d/m/Y', strtotime($user['created_at']));
                                    } else {
                                        echo date('d/m/Y');
                                    }
                                    ?>
                                </span>
                            </div>
                        </div>
                        <div class="stat-item-modern">
                            <div class="stat-icon">
                                <i class="fas fa-book-reader"></i>
                            </div>
                            <div class="stat-details">
                                <span class="stat-label"><?php echo __('books_borrowed'); ?></span>
                                <span class="stat-value"><?php echo $total_loans; ?></span>
                            </div>
                        </div>
                        <div class="stat-item-modern">
                            <div class="stat-icon">
                                <i class="fas fa-hourglass-half"></i>
                            </div>
                            <div class="stat-details">
                                <span class="stat-label"><?php echo __('active_loans'); ?></span>
                                <span class="stat-value"><?php echo $active_loans; ?></span>
                            </div>
                        </div>
                        <div class="stat-item-modern">
                            <div class="stat-icon">
                                <i class="fas fa-check-circle"></i>
                            </div>
                            <div class="stat-details">
                                <span class="stat-label"><?php echo __('returns_made'); ?></span>
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
                    <h2><?php echo __('danger_zone'); ?></h2>
                    <p><?php echo __('irreversible_actions'); ?></p>
                </div>
            </div>
            <div class="card-body">
                <div class="danger-content">
                    <i class="fas fa-skull"></i>
                    <p><?php echo __('delete_account_warning'); ?></p>
                    <button class="btn-danger" onclick="deleteAccount()">
                        <i class="fas fa-trash-alt"></i>
                        <?php echo __('delete_account'); ?>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Tous tes styles CSS restent ici inchangés */
.settings-container {
    max-width: 1400px;
    margin: 0 auto;
}

/* Hero Section */
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
    color: var(--primary);
}

.hero-stat .stat-label {
    font-size: 0.7rem;
    color: rgba(255, 255, 255, 0.5);
}

/* Grid */
.settings-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1.5rem;
}

/* Cartes */
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
    border-color: var(--primary);
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

/* Formulaires modernes */
.modern-form .input-group {
    position: relative;
    margin-bottom: 1.25rem;
}

.modern-form .input-group i {
    position: absolute;
    left: 1rem;
    top: 50%;
    transform: translateY(-50%);
    color: var(--primary);
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
    border-color: var(--primary);
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
    color: var(--primary);
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

/* Password strength */
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

/* Toggle switch */
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
    color: var(--primary);
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

/* Select group */
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
    color: var(--primary);
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

/* Theme options */
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
    border-color: var(--primary);
    background: rgba(99, 102, 241, 0.1);
}

/* Stats section */
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
    color: var(--primary);
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
    color: var(--primary);
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
    color: var(--primary);
}

/* Danger zone */
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

/* Responsive */
@media (max-width: 1024px) {
    .settings-grid {
        grid-template-columns: 1fr;
        gap: 1rem;
    }
    
    .form-row {
        grid-template-columns: 1fr;
        gap: 1rem;
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

// Thème options - changement immédiat
document.querySelectorAll('.theme-option').forEach(option => {
    option.addEventListener('click', function() {
        document.querySelectorAll('.theme-option').forEach(o => o.classList.remove('active'));
        this.classList.add('active');
        
        const theme = this.dataset.theme;
        if (theme === 'light') {
            document.body.classList.remove('dark-theme');
            document.body.classList.add('light-theme');
            document.documentElement.classList.remove('dark-theme');
            document.documentElement.classList.add('light-theme');
        } else {
            document.body.classList.remove('light-theme');
            document.body.classList.add('dark-theme');
            document.documentElement.classList.remove('light-theme');
            document.documentElement.classList.add('dark-theme');
        }
        
        localStorage.setItem('theme', theme);
    });
});

// Charger le thème sauvegardé
const savedTheme = localStorage.getItem('theme');
if (savedTheme) {
    if (savedTheme === 'light') {
        document.body.classList.remove('dark-theme');
        document.body.classList.add('light-theme');
        document.documentElement.classList.remove('dark-theme');
        document.documentElement.classList.add('light-theme');
    } else {
        document.body.classList.remove('light-theme');
        document.body.classList.add('dark-theme');
        document.documentElement.classList.remove('light-theme');
        document.documentElement.classList.add('dark-theme');
    }
    document.querySelectorAll('.theme-option').forEach(opt => {
        if (opt.dataset.theme === savedTheme) {
            opt.classList.add('active');
        } else {
            opt.classList.remove('active');
        }
    });
}

// Formulaire profil
document.getElementById('profileForm')?.addEventListener('submit', async (e) => {
    e.preventDefault();
    
    const formData = new FormData(e.target);
    const data = {
        first_name: formData.get('first_name'),
        last_name: formData.get('last_name'),
        email: formData.get('email')
    };
    
    try {
        const response = await fetch('/settings/update-profile', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(data)
        });
        const result = await response.json();
        
        if (result.success) {
            alert('✅ ' + result.message);
            setTimeout(() => location.reload(), 1000);
        } else {
            alert('❌ ' + result.message);
        }
    } catch (error) {
        alert('Erreur de connexion');
    }
});

// Formulaire mot de passe
document.getElementById('passwordForm')?.addEventListener('submit', async (e) => {
    e.preventDefault();
    
    const newPassword = document.getElementById('new_password').value;
    const confirmPassword = document.getElementById('confirm_password').value;
    
    if (newPassword !== confirmPassword) {
        alert('❌ Les mots de passe ne correspondent pas');
        return;
    }
    
    const formData = new FormData(e.target);
    const data = {
        current_password: formData.get('current_password'),
        new_password: formData.get('new_password')
    };
    
    try {
        const response = await fetch('/settings/update-password', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(data)
        });
        const result = await response.json();
        
        if (result.success) {
            alert('✅ ' + result.message);
            e.target.reset();
        } else {
            alert('❌ ' + result.message);
        }
    } catch (error) {
        alert('Erreur de connexion');
    }
});

// Formulaire préférences
document.getElementById('preferencesForm')?.addEventListener('submit', async (e) => {
    e.preventDefault();
    
    const formData = new FormData(e.target);
    const selectedTheme = document.querySelector('.theme-option.active')?.dataset.theme || 'dark';
    const selectedLanguage = formData.get('language');
    
    const data = {
        notifications: formData.get('notifications') === 'on',
        language: selectedLanguage,
        theme: selectedTheme
    };
    
    try {
        const response = await fetch('/settings/update-preferences', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(data)
        });
        const result = await response.json();
        
        if (result.success) {
            alert('✅ ' + result.message);
            
            if (selectedTheme === 'light') {
                document.body.classList.remove('dark-theme');
                document.body.classList.add('light-theme');
                document.documentElement.classList.remove('dark-theme');
                document.documentElement.classList.add('light-theme');
            } else {
                document.body.classList.remove('light-theme');
                document.body.classList.add('dark-theme');
                document.documentElement.classList.remove('light-theme');
                document.documentElement.classList.add('dark-theme');
            }
            
            if (selectedLanguage !== '<?php echo $preferences['language']; ?>') {
                setTimeout(() => {
                    location.reload();
                }, 500);
            }
        } else {
            alert('❌ ' + result.message);
        }
    } catch (error) {
        alert('Erreur de connexion');
    }
});

// Mettre à jour le cercle de progression
document.addEventListener('DOMContentLoaded', function() {
    const totalLoans = <?php echo $total_loans; ?>;
    const activeLoans = <?php echo $active_loans; ?>;
    
    if (totalLoans > 0) {
        const completionRate = Math.round((activeLoans / totalLoans) * 100);
        const completionElement = document.getElementById('completionRate');
        if (completionElement) {
            completionElement.textContent = completionRate;
        }
        
        const circle = document.querySelector('.progress-ring');
        if (circle) {
            const radius = 45;
            const circumference = 2 * Math.PI * radius;
            const offset = circumference - (completionRate / 100) * circumference;
            circle.style.strokeDashoffset = offset;
        }
    }
});

// Supprimer le compte
async function deleteAccount() {
    if (!confirm('⚠️ Êtes-vous sûr de vouloir supprimer votre compte ? Cette action est irréversible !')) return;
    if (!confirm('⚠️ DERNIÈRE CHANCE : Voulez-vous vraiment supprimer votre compte définitivement ?')) return;
    
    alert('Cette fonctionnalité sera bientôt disponible');
}
</script>