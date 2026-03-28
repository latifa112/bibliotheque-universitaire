<!DOCTYPE html>
<html lang="<?php echo $preferences['language'] ?? 'fr'; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo __('reset_title'); ?> - BiblioGest</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        /* Garde tous tes styles CSS inchangés */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --primary: #6366f1;
            --success: #10b981;
            --danger: #ef4444;
            --dark: #0f172a;
            --gradient-1: linear-gradient(135deg, #6366f1 0%, #8b5cf6 50%, #ec4899 100%);
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: var(--dark);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }

        .reset-container {
            max-width: 500px;
            width: 100%;
        }

        .reset-card {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 24px;
            padding: 2rem;
        }

        .logo {
            text-align: center;
            margin-bottom: 1.5rem;
        }

        .logo i {
            font-size: 3rem;
            background: var(--gradient-1);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        h1 {
            text-align: center;
            color: white;
            margin-bottom: 1rem;
            font-size: 1.5rem;
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
            border-radius: 12px;
            color: white;
            font-size: 1rem;
        }

        .input-group input:focus {
            outline: none;
            border-color: var(--primary);
        }

        .input-group i {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--primary);
        }

        .btn-reset {
            width: 100%;
            padding: 1rem;
            background: var(--gradient-1);
            border: none;
            border-radius: 12px;
            color: white;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
        }

        .btn-reset:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(99, 102, 241, 0.4);
        }

        .message {
            padding: 0.75rem;
            border-radius: 8px;
            margin-bottom: 1rem;
            text-align: center;
        }

        .message-success {
            background: rgba(16, 185, 129, 0.2);
            border: 1px solid rgba(16, 185, 129, 0.3);
            color: #10b981;
        }

        .message-error {
            background: rgba(239, 68, 68, 0.2);
            border: 1px solid rgba(239, 68, 68, 0.3);
            color: #ef4444;
        }

        .back-link {
            text-align: center;
            margin-top: 1rem;
        }

        .back-link a {
            color: var(--primary);
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="reset-container">
        <div class="reset-card">
            <div class="logo">
                <i class="fas fa-key"></i>
            </div>
            <h1><?php echo __('reset_title'); ?></h1>
            
            <div id="message"></div>

            <form id="resetForm">
                <div class="input-group">
                    <i class="fas fa-lock"></i>
                    <input type="password" id="password" name="password" placeholder="<?php echo __('new_password_placeholder'); ?>" required>
                </div>
                <div class="input-group">
                    <i class="fas fa-check-circle"></i>
                    <input type="password" id="confirm_password" name="confirm_password" placeholder="<?php echo __('confirm_new_password_placeholder'); ?>" required>
                </div>
                <button type="submit" class="btn-reset">
                    <i class="fas fa-save"></i> <?php echo __('reset_button'); ?>
                </button>
            </form>

            <div class="back-link">
                <a href="/login"><i class="fas fa-arrow-left"></i> <?php echo __('back_to_login'); ?></a>
            </div>
        </div>
    </div>

    <script>
        const urlParams = new URLSearchParams(window.location.search);
        const token = urlParams.get('token');

        document.getElementById('resetForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            
            const password = document.getElementById('password').value;
            const confirm = document.getElementById('confirm_password').value;
            
            if (password !== confirm) {
                showMessage('<?php echo __('password_mismatch_error'); ?>', 'error');
                return;
            }
            
            if (password.length < 6) {
                showMessage('<?php echo __('password_length'); ?>', 'error');
                return;
            }
            
            try {
                const response = await fetch('/reset-password', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ token, password })
                });
                
                const result = await response.json();
                
                if (result.success) {
                    showMessage(result.message, 'success');
                    setTimeout(() => {
                        window.location.href = '/login';
                    }, 2000);
                } else {
                    showMessage(result.message, 'error');
                }
            } catch (error) {
                showMessage('<?php echo __('connection_error'); ?>', 'error');
            }
        });
        
        function showMessage(msg, type) {
            const div = document.getElementById('message');
            div.className = `message message-${type}`;
            div.innerHTML = `<i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'}"></i> ${msg}`;
            setTimeout(() => {
                div.innerHTML = '';
            }, 5000);
        }
    </script>
</body>
</html>