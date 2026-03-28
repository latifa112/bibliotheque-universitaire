<!DOCTYPE html>
<html lang="<?php echo $preferences['language'] ?? 'fr'; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo __('register_title'); ?> - BiblioGest</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;500;600;700&family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --primary: #6366f1;
            --primary-dark: #4f46e5;
            --secondary: #8b5cf6;
            --accent: #ec4899;
            --dark: #0f172a;
            --light: #f8fafc;
            --success: #10b981;
            --danger: #ef4444;
            --gradient-1: linear-gradient(135deg, #6366f1 0%, #8b5cf6 50%, #ec4899 100%);
        }

        body {
            font-family: 'Space Grotesk', sans-serif;
            background: var(--dark);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow-x: hidden;
        }

        .bg-animation {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 0;
            background: radial-gradient(circle at 20% 50%, rgba(99, 102, 241, 0.1) 0%, transparent 50%),
                        radial-gradient(circle at 80% 80%, rgba(139, 92, 246, 0.1) 0%, transparent 50%);
            animation: pulseBg 8s ease-in-out infinite;
        }

        @keyframes pulseBg {
            0%, 100% { opacity: 0.5; }
            50% { opacity: 1; }
        }

        .floating-books {
            position: absolute;
            width: 100%;
            height: 100%;
            pointer-events: none;
        }

        .floating-book {
            position: absolute;
            font-size: 2rem;
            opacity: 0.1;
            animation: float 20s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0) rotate(0deg); }
            50% { transform: translateY(-30px) rotate(5deg); }
        }

        .auth-container {
            position: relative;
            z-index: 10;
            width: 100%;
            max-width: 1200px;
            padding: 2rem;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }

        .auth-card {
            background: rgba(15, 23, 42, 0.8);
            backdrop-filter: blur(20px);
            border-radius: 48px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            overflow: hidden;
            display: flex;
            max-width: 1100px;
            width: 100%;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
            animation: slideUp 0.6s ease;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .auth-brand {
            flex: 1;
            background: linear-gradient(135deg, rgba(99, 102, 241, 0.2), rgba(139, 92, 246, 0.1));
            padding: 3rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        .brand-logo {
            position: relative;
            z-index: 1;
            margin-bottom: 2rem;
        }

        .brand-logo i {
            font-size: 4rem;
            background: var(--gradient-1);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .brand-title {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
            background: var(--gradient-1);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .brand-description {
            color: rgba(255, 255, 255, 0.7);
            line-height: 1.6;
            margin-bottom: 2rem;
        }

        .stats {
            display: flex;
            gap: 1.5rem;
            margin-top: 2rem;
        }

        .stat {
            text-align: center;
        }

        .stat-number {
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--primary);
        }

        .stat-label {
            font-size: 0.8rem;
            color: rgba(255, 255, 255, 0.6);
        }

        .auth-form {
            flex: 1.2;
            padding: 2.5rem;
        }

        .form-header {
            text-align: center;
            margin-bottom: 1.5rem;
        }

        .form-header h2 {
            font-size: 2rem;
            margin-bottom: 0.5rem;
            background: linear-gradient(135deg, #fff, #a5b4fc);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .form-header p {
            color: rgba(255, 255, 255, 0.7);
        }

        .input-group {
            position: relative;
            margin-bottom: 1.25rem;
        }

        .input-group input,
        .input-group select {
            width: 100%;
            padding: 0.9rem 1rem 0.9rem 3rem;
            background: rgba(255, 255, 255, 0.05);
            border: 2px solid rgba(255, 255, 255, 0.1);
            border-radius: 14px;
            color: var(--light);
            font-size: 0.95rem;
            transition: all 0.3s ease;
            font-family: inherit;
        }

        .input-group select {
            appearance: none;
            cursor: pointer;
        }

        .input-group select option {
            background: #1e293b;
            color: white;
        }

        .input-group input:focus,
        .input-group select:focus {
            outline: none;
            border-color: var(--primary);
            background: rgba(255, 255, 255, 0.1);
            box-shadow: 0 0 15px rgba(99, 102, 241, 0.2);
        }

        .input-group i:first-of-type {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--primary);
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }

        .password-strength {
            margin-top: 0.5rem;
            height: 4px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 2px;
            overflow: hidden;
        }

        .strength-bar {
            height: 100%;
            width: 0;
            transition: width 0.3s ease, background 0.3s ease;
        }

        .strength-text {
            font-size: 0.7rem;
            margin-top: 0.25rem;
            color: rgba(255, 255, 255, 0.5);
        }

        .btn-register {
            width: 100%;
            padding: 1rem;
            background: var(--gradient-1);
            border: none;
            border-radius: 14px;
            color: white;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
            transition: all 0.3s ease;
            margin-top: 0.5rem;
        }

        .btn-register:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(99, 102, 241, 0.4);
        }

        .terms {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin: 1rem 0;
            color: rgba(255, 255, 255, 0.6);
            font-size: 0.85rem;
        }

        .terms input {
            width: 18px;
            height: 18px;
            cursor: pointer;
            accent-color: var(--primary);
        }

        .terms a {
            color: var(--primary);
            text-decoration: none;
        }

        .login-link {
            text-align: center;
            margin-top: 1.5rem;
            color: rgba(255, 255, 255, 0.6);
        }

        .login-link a {
            color: var(--primary);
            text-decoration: none;
            font-weight: 600;
        }

        .error-message, .success-message {
            border-radius: 12px;
            padding: 0.75rem;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.9rem;
            animation: slideDown 0.3s ease;
        }

        .error-message {
            background: rgba(239, 68, 68, 0.1);
            border: 1px solid rgba(239, 68, 68, 0.3);
            color: var(--danger);
        }

        .success-message {
            background: rgba(16, 185, 129, 0.1);
            border: 1px solid rgba(16, 185, 129, 0.3);
            color: var(--success);
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @media (max-width: 768px) {
            .auth-card {
                flex-direction: column;
                border-radius: 32px;
            }
            
            .auth-brand {
                padding: 2rem;
                text-align: center;
            }
            
            .stats {
                justify-content: center;
            }
            
            .auth-form {
                padding: 1.5rem;
            }
            
            .form-row {
                grid-template-columns: 1fr;
                gap: 0;
            }
            
            .form-header h2 {
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="bg-animation"></div>
    <div class="floating-books">
        <div class="floating-book" style="top: 15%; left: 8%; animation-delay: 0s;"><i class="fas fa-book"></i></div>
        <div class="floating-book" style="top: 65%; left: 88%; animation-delay: 2s;"><i class="fas fa-book-reader"></i></div>
        <div class="floating-book" style="top: 85%; left: 12%; animation-delay: 4s;"><i class="fas fa-graduation-cap"></i></div>
        <div class="floating-book" style="top: 25%; left: 92%; animation-delay: 1s;"><i class="fas fa-library"></i></div>
    </div>

    <div class="auth-container">
        <div class="auth-card">
            <div class="auth-brand">
                <div class="brand-logo">
                    <i class="fas fa-user-plus"></i>
                </div>
                <h1 class="brand-title"><?php echo __('register_title'); ?></h1>
                <p class="brand-description">
                    <?php echo __('register_subtitle'); ?>
                </p>
                <div class="stats">
                    <div class="stat">
                        <div class="stat-number">50k+</div>
                        <div class="stat-label"><?php echo __('books'); ?></div>
                    </div>
                    <div class="stat">
                        <div class="stat-number">5k+</div>
                        <div class="stat-label"><?php echo __('users'); ?></div>
                    </div>
                    <div class="stat">
                        <div class="stat-number">24/7</div>
                        <div class="stat-label"><?php echo __('support'); ?></div>
                    </div>
                </div>
            </div>
            <div class="auth-form">
                <div class="form-header">
                    <h2><?php echo __('register_title'); ?></h2>
                    <p><?php echo __('register_subtitle'); ?></p>
                </div>

                <div id="message-container"></div>

                <form id="registerForm">
                    <div class="form-row">
                        <div class="input-group">
                            <input type="text" id="first_name" name="first_name" placeholder=" " required>
                            <label for="first_name" style="position: absolute; left: 3rem; top: 0.9rem; color: rgba(255,255,255,0.5); pointer-events: none; transition: all 0.3s; font-size: 0.95rem;"><?php echo __('first_name'); ?></label>
                            <i class="fas fa-user"></i>
                        </div>
                        <div class="input-group">
                            <input type="text" id="last_name" name="last_name" placeholder=" " required>
                            <label for="last_name" style="position: absolute; left: 3rem; top: 0.9rem; color: rgba(255,255,255,0.5); pointer-events: none; transition: all 0.3s; font-size: 0.95rem;"><?php echo __('last_name'); ?></label>
                            <i class="fas fa-user"></i>
                        </div>
                    </div>

                    <div class="input-group">
                        <input type="email" id="email" name="email" placeholder=" " required>
                        <label for="email" style="position: absolute; left: 3rem; top: 0.9rem; color: rgba(255,255,255,0.5); pointer-events: none; transition: all 0.3s; font-size: 0.95rem;"><?php echo __('email'); ?></label>
                        <i class="fas fa-envelope"></i>
                    </div>

                    <div class="input-group">
                        <i class="fas fa-user-tag"></i>
                        <select id="role" name="role" required>
                            <option value="" disabled selected style="display: none;"></option>
                            <option value="etudiant"><?php echo __('student'); ?></option>
                            <option value="professeur"><?php echo __('teacher'); ?></option>
                        </select>
                        <label for="role" style="position: absolute; left: 3rem; top: 0.9rem; color: rgba(255,255,255,0.5); pointer-events: none; transition: all 0.3s; font-size: 0.95rem;"><?php echo __('role'); ?></label>
                    </div>

                    <div class="input-group">
    <i class="fas fa-graduation-cap"></i>
    <select id="field_of_study" name="field_of_study">
        <option value="" disabled selected style="display: none;"></option>
        <option value="informatique">💻 Informatique / Programmation</option>
        <option value="physique">⚛️ Physique</option>
        <option value="chimie">🧪 Chimie</option>
        <option value="biologie">🧬 Biologie</option>
        <option value="mathématiques">📐 Mathématiques</option>
        <option value="histoire">📜 Histoire</option>
        <option value="littérature">📖 Littérature</option>
        <option value="philosophie">🧠 Philosophie</option>
        <option value="art">🎨 Art</option>
        <option value="économie">📊 Économie / Gestion</option>
    </select>
    <label for="field_of_study" style="position: absolute; left: 3rem; top: 0.9rem; color: rgba(255,255,255,0.5); pointer-events: none; transition: all 0.3s; font-size: 0.95rem;">Filière d'étude (optionnel)</label>
</div>

                    <div class="input-group">
                        <input type="password" id="password" name="password" placeholder=" " required>
                        <label for="password" style="position: absolute; left: 3rem; top: 0.9rem; color: rgba(255,255,255,0.5); pointer-events: none; transition: all 0.3s; font-size: 0.95rem;"><?php echo __('password'); ?></label>
                        <i class="fas fa-lock"></i>
                        <i class="fas fa-eye toggle-password" id="togglePassword" style="position: absolute; right: 1rem; top: 50%; transform: translateY(-50%); cursor: pointer; color: #6366f1; z-index: 10; font-size: 1rem;"></i>
                    </div>
                    <div class="password-strength">
                        <div class="strength-bar" id="strengthBar"></div>
                    </div>
                    <div class="strength-text" id="strengthText"></div>

                    <div class="input-group">
                        <input type="password" id="confirm_password" name="confirm_password" placeholder=" " required>
                        <label for="confirm_password" style="position: absolute; left: 3rem; top: 0.9rem; color: rgba(255,255,255,0.5); pointer-events: none; transition: all 0.3s; font-size: 0.95rem;"><?php echo __('confirm_password'); ?></label>
                        <i class="fas fa-check-circle"></i>
                        <i class="fas fa-eye toggle-password" id="toggleConfirmPassword" style="position: absolute; right: 1rem; top: 50%; transform: translateY(-50%); cursor: pointer; color: #6366f1; z-index: 10; font-size: 1rem;"></i>
                    </div>

                    <label class="terms">
                        <input type="checkbox" id="terms" required>
                        <span><?php echo __('terms_accept'); ?> <a href="#"><?php echo __('terms_conditions'); ?></a> <?php echo __('and'); ?> <a href="#"><?php echo __('privacy_policy'); ?></a></span>
                    </label>

                    <button type="submit" class="btn-register">
                        <span><?php echo __('register_button'); ?></span>
                        <i class="fas fa-arrow-right"></i>
                    </button>
                </form>

                <div class="login-link">
                    <?php echo __('already_account'); ?> <a href="/login"><?php echo __('login_now'); ?></a>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Animation des labels flottants
        document.querySelectorAll('.input-group input, .input-group select').forEach(input => {
            input.addEventListener('focus', function() {
                const label = this.nextElementSibling;
                if (label && label.tagName === 'LABEL') {
                    label.style.top = '-0.6rem';
                    label.style.left = '3rem';
                    label.style.fontSize = '0.65rem';
                    label.style.color = 'var(--primary)';
                    label.style.background = 'rgba(15, 23, 42, 0.9)';
                    label.style.padding = '0 0.25rem';
                }
            });
            
            input.addEventListener('blur', function() {
                const label = this.nextElementSibling;
                if (label && label.tagName === 'LABEL') {
                    if (!this.value) {
                        label.style.top = '0.9rem';
                        label.style.left = '3rem';
                        label.style.fontSize = '0.95rem';
                        label.style.color = 'rgba(255,255,255,0.5)';
                        label.style.background = 'transparent';
                        label.style.padding = '0';
                    }
                }
            });
            
            if (input.value) {
                const label = input.nextElementSibling;
                if (label && label.tagName === 'LABEL') {
                    label.style.top = '-0.6rem';
                    label.style.left = '3rem';
                    label.style.fontSize = '0.65rem';
                    label.style.color = 'var(--primary)';
                    label.style.background = 'rgba(15, 23, 42, 0.9)';
                    label.style.padding = '0 0.25rem';
                }
            }
        });

        // Afficher/Masquer le mot de passe (champ mot de passe)
        const togglePassword = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('password');

        if (togglePassword && passwordInput) {
            togglePassword.addEventListener('click', function() {
                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput.setAttribute('type', type);
                this.classList.toggle('fa-eye');
                this.classList.toggle('fa-eye-slash');
            });
        }

        // Afficher/Masquer le mot de passe (champ confirmation)
        const toggleConfirmPassword = document.getElementById('toggleConfirmPassword');
        const confirmPasswordInput = document.getElementById('confirm_password');

        if (toggleConfirmPassword && confirmPasswordInput) {
            toggleConfirmPassword.addEventListener('click', function() {
                const type = confirmPasswordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                confirmPasswordInput.setAttribute('type', type);
                this.classList.toggle('fa-eye');
                this.classList.toggle('fa-eye-slash');
            });
        }

        // Force du mot de passe
        const password = document.getElementById('password');
        const strengthBar = document.getElementById('strengthBar');
        const strengthText = document.getElementById('strengthText');

        password.addEventListener('input', function() {
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
                strengthText.textContent = '<?php echo __('weak'); ?>';
                strengthText.style.color = '#ef4444';
            } else if (strength < 60) {
                strengthBar.style.background = '#f59e0b';
                strengthText.textContent = '<?php echo __('medium'); ?>';
                strengthText.style.color = '#f59e0b';
            } else if (strength < 80) {
                strengthBar.style.background = '#3b82f6';
                strengthText.textContent = '<?php echo __('good'); ?>';
                strengthText.style.color = '#3b82f6';
            } else {
                strengthBar.style.background = '#10b981';
                strengthText.textContent = '<?php echo __('excellent'); ?>';
                strengthText.style.color = '#10b981';
            }
        });

        // Soumission du formulaire
        document.getElementById('registerForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirm_password').value;
            
            if (password !== confirmPassword) {
                showMessage('error', '<?php echo __('password_mismatch_error'); ?>');
                return;
            }
            
            const formData = new FormData(e.target);
            const data = Object.fromEntries(formData);
            
            try {
                const response = await fetch('/register', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(data)
                });
                
                const result = await response.json();
                
                if (result.success) {
                    showMessage('success', '<?php echo __('register_success'); ?>');
                    setTimeout(() => {
                        window.location.href = result.redirect;
                    }, 2000);
                } else {
                    showMessage('error', result.message);
                }
            } catch (error) {
                console.error('Erreur:', error);
                showMessage('error', '<?php echo __('connection_error'); ?>');
            }
        });
        
        function showMessage(type, message) {
            const container = document.getElementById('message-container');
            const className = type === 'error' ? 'error-message' : 'success-message';
            const icon = type === 'error' ? 'fa-exclamation-circle' : 'fa-check-circle';
            
            container.innerHTML = `
                <div class="${className}">
                    <i class="fas ${icon}"></i>
                    <span>${message}</span>
                </div>
            `;
            
            setTimeout(() => {
                container.innerHTML = '';
            }, 3000);
        }
    </script>
</body>
</html>