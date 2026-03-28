<!DOCTYPE html>
<html lang="<?php echo $preferences['language'] ?? 'fr'; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo __('login_title'); ?> - BiblioGest</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;500;600;700&family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        /* Garde tous tes styles CSS inchangés */
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
            --gradient-2: linear-gradient(135deg, #0f172a 0%, #1e1b4b 100%);
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
            overflow: hidden;
        }

        .bg-animation::before {
            content: '';
            position: absolute;
            width: 200%;
            height: 200%;
            background: linear-gradient(45deg, 
                rgba(99, 102, 241, 0.1) 0%, 
                rgba(139, 92, 246, 0.1) 25%,
                rgba(236, 72, 153, 0.1) 50%,
                rgba(139, 92, 246, 0.1) 75%,
                rgba(99, 102, 241, 0.1) 100%);
            animation: rotate 20s linear infinite;
        }

        @keyframes rotate {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
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
            animation: float 15s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0) rotate(0deg); }
            50% { transform: translateY(-50px) rotate(10deg); }
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
            max-width: 1000px;
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

        .auth-brand::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(99, 102, 241, 0.1) 0%, transparent 70%);
            animation: pulse 4s ease-in-out infinite;
        }

        @keyframes pulse {
            0%, 100% { opacity: 0.5; transform: scale(1); }
            50% { opacity: 1; transform: scale(1.1); }
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

        .brand-features {
            list-style: none;
        }

        .brand-features li {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 1rem;
            color: rgba(255, 255, 255, 0.8);
        }

        .brand-features i {
            width: 24px;
            color: var(--primary);
        }

        .auth-form {
            flex: 1;
            padding: 3rem;
        }

        .form-header {
            text-align: center;
            margin-bottom: 2rem;
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
            margin-bottom: 1.5rem;
        }

        .input-group input {
            width: 100%;
            padding: 1rem 1rem 1rem 3rem;
            background: rgba(255, 255, 255, 0.05);
            border: 2px solid rgba(255, 255, 255, 0.1);
            border-radius: 16px;
            color: var(--light);
            font-size: 1rem;
            transition: all 0.3s ease;
            font-family: inherit;
        }

        .input-group input:focus {
            outline: none;
            border-color: var(--primary);
            background: rgba(255, 255, 255, 0.1);
            box-shadow: 0 0 20px rgba(99, 102, 241, 0.2);
        }

        .input-group i:first-of-type {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--primary);
            font-size: 1.1rem;
            transition: all 0.3s ease;
        }

        .form-options {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }

        .checkbox {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            cursor: pointer;
            color: rgba(255, 255, 255, 0.7);
        }

        .checkbox input {
            width: 18px;
            height: 18px;
            cursor: pointer;
            accent-color: var(--primary);
        }

        .forgot-link {
            color: var(--primary);
            text-decoration: none;
            font-size: 0.9rem;
            transition: color 0.3s;
        }

        .forgot-link:hover {
            color: var(--accent);
        }

        .btn-login {
            width: 100%;
            padding: 1rem;
            background: var(--gradient-1);
            border: none;
            border-radius: 16px;
            color: white;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .btn-login::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.3);
            transform: translate(-50%, -50%);
            transition: width 0.6s, height 0.6s;
        }

        .btn-login:hover::before {
            width: 300px;
            height: 300px;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(99, 102, 241, 0.4);
        }

        .register-link {
            text-align: center;
            margin-top: 2rem;
            color: rgba(255, 255, 255, 0.6);
        }

        .register-link a {
            color: var(--primary);
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s;
        }

        .register-link a:hover {
            color: var(--accent);
        }

        .error-message {
            background: rgba(239, 68, 68, 0.1);
            border: 1px solid rgba(239, 68, 68, 0.3);
            border-radius: 12px;
            padding: 0.75rem;
            margin-bottom: 1rem;
            color: var(--danger);
            display: flex;
            align-items: center;
            gap: 0.5rem;
            animation: shake 0.5s ease;
        }

        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-5px); }
            75% { transform: translateX(5px); }
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
            
            .brand-features {
                display: none;
            }
            
            .auth-form {
                padding: 2rem;
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
        <div class="floating-book" style="top: 10%; left: 5%; animation-delay: 0s;"><i class="fas fa-book-open"></i></div>
        <div class="floating-book" style="top: 70%; left: 85%; animation-delay: 2s;"><i class="fas fa-book-reader"></i></div>
        <div class="floating-book" style="top: 80%; left: 15%; animation-delay: 4s;"><i class="fas fa-graduation-cap"></i></div>
        <div class="floating-book" style="top: 20%; left: 90%; animation-delay: 1s;"><i class="fas fa-library"></i></div>
    </div>

    <div class="auth-container">
        <div class="auth-card">
            <div class="auth-brand">
                <div class="brand-logo">
                    <i class="fas fa-book-open"></i>
                </div>
                <h1 class="brand-title">BiblioGest</h1>
                <p class="brand-description">
                    <?php echo __('brand_description'); ?>
                </p>
                <ul class="brand-features">
                    <li><i class="fas fa-check-circle"></i> <?php echo __('unlimited_catalogue'); ?></li>
                    <li><i class="fas fa-book-open"></i> <?php echo __('simplified_loans'); ?></li>
                    <li><i class="fas fa-bell"></i> <?php echo __('real_time_notifications'); ?></li>
                    <li><i class="fas fa-robot"></i> <?php echo __('virtual_assistant'); ?></li>
                </ul>
            </div>
            <div class="auth-form">
                <div class="form-header">
                    <h2><?php echo __('login_title'); ?></h2>
                    <p><?php echo __('login_subtitle'); ?></p>
                </div>

                <div id="error-message" class="error-message" style="display: none;">
                    <i class="fas fa-exclamation-circle"></i>
                    <span><?php echo __('login_error'); ?></span>
                </div>

                <form id="loginForm">
                    <div class="input-group">
                        <input type="email" id="email" name="email" placeholder=" " required>
                        <label for="email" style="position: absolute; left: 3rem; top: 1rem; color: rgba(255,255,255,0.5); pointer-events: none; transition: all 0.3s; font-size: 1rem;"><?php echo __('email_placeholder'); ?></label>
                        <i class="fas fa-envelope"></i>
                    </div>

                    <div class="input-group">
                        <input type="password" id="password" name="password" placeholder=" " required>
                        <label for="password" style="position: absolute; left: 3rem; top: 1rem; color: rgba(255,255,255,0.5); pointer-events: none; transition: all 0.3s; font-size: 1rem;"><?php echo __('password_placeholder'); ?></label>
                        <i class="fas fa-lock"></i>
                        <i class="fas fa-eye toggle-password" id="togglePassword" style="position: absolute; right: 1rem; top: 50%; transform: translateY(-50%); cursor: pointer; color: #6366f1; z-index: 10; font-size: 1.1rem;"></i>
                    </div>

                    <div class="form-options">
                        <label class="checkbox">
                            <input type="checkbox" name="remember">
                            <span><?php echo __('remember_me'); ?></span>
                        </label>
                        <a href="/forgot-password" class="forgot-link"><?php echo __('forgot_password'); ?></a>
                    </div>

                    <button type="submit" class="btn-login">
                        <span><?php echo __('login_button'); ?></span>
                        <i class="fas fa-arrow-right"></i>
                    </button>
                </form>

                <div class="register-link">
                    <?php echo __('no_account'); ?> <a href="/register"><?php echo __('register_now'); ?></a>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Animation des labels flottants
        document.querySelectorAll('.input-group input').forEach(input => {
            input.addEventListener('focus', function() {
                const label = this.nextElementSibling;
                if (label && label.tagName === 'LABEL') {
                    label.style.top = '-0.5rem';
                    label.style.left = '3rem';
                    label.style.fontSize = '0.7rem';
                    label.style.color = 'var(--primary)';
                    label.style.background = 'rgba(15, 23, 42, 0.9)';
                    label.style.padding = '0 0.25rem';
                }
            });
            
            input.addEventListener('blur', function() {
                const label = this.nextElementSibling;
                if (label && label.tagName === 'LABEL') {
                    if (!this.value) {
                        label.style.top = '1rem';
                        label.style.left = '3rem';
                        label.style.fontSize = '1rem';
                        label.style.color = 'rgba(255,255,255,0.5)';
                        label.style.background = 'transparent';
                        label.style.padding = '0';
                    }
                }
            });
            
            if (input.value) {
                const label = input.nextElementSibling;
                if (label && label.tagName === 'LABEL') {
                    label.style.top = '-0.5rem';
                    label.style.left = '3rem';
                    label.style.fontSize = '0.7rem';
                    label.style.color = 'var(--primary)';
                    label.style.background = 'rgba(15, 23, 42, 0.9)';
                    label.style.padding = '0 0.25rem';
                }
            }
        });

        // Afficher/Masquer le mot de passe
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

        // Soumission du formulaire
        document.getElementById('loginForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;
            
            try {
                const response = await fetch('/login', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ email, password })
                });
                
                const result = await response.json();
                
                if (result.success) {
                    window.location.href = result.redirect;
                } else {
                    const errorDiv = document.getElementById('error-message');
                    errorDiv.querySelector('span').textContent = result.message;
                    errorDiv.style.display = 'flex';
                    setTimeout(() => {
                        errorDiv.style.display = 'none';
                    }, 3000);
                }
            } catch (error) {
                console.error('Erreur:', error);
                const errorDiv = document.getElementById('error-message');
                errorDiv.querySelector('span').textContent = '<?php echo __('connection_error'); ?>';
                errorDiv.style.display = 'flex';
            }
        });
    </script>
</body>
</html>