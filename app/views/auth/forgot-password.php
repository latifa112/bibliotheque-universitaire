<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mot de passe oublié - BiblioGest</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
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
            margin-bottom: 0.5rem;
            font-size: 1.5rem;
        }

        .subtitle {
            text-align: center;
            color: rgba(255, 255, 255, 0.6);
            margin-bottom: 1.5rem;
            font-size: 0.9rem;
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

        .btn-reset:disabled {
            opacity: 0.7;
            cursor: not-allowed;
        }

        .message {
            padding: 0.75rem;
            border-radius: 8px;
            margin-bottom: 1rem;
            word-break: break-all;
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

        .copy-btn {
            background: var(--primary);
            border: none;
            padding: 5px 10px;
            border-radius: 5px;
            color: white;
            cursor: pointer;
            margin-top: 5px;
            font-size: 0.8rem;
        }

        .copy-btn:hover {
            background: #4f46e5;
        }
    </style>
</head>
<body>
    <div class="reset-container">
        <div class="reset-card">
            <div class="logo">
                <i class="fas fa-key"></i>
            </div>
            <h1>Mot de passe oublié ?</h1>
            <p class="subtitle">Entrez votre email pour réinitialiser votre mot de passe</p>
            
            <div id="message"></div>

            <form id="forgotForm">
                <div class="input-group">
                    <i class="fas fa-envelope"></i>
                    <input type="email" id="email" name="email" placeholder="Adresse email" required>
                </div>
                <button type="submit" class="btn-reset">
                    <i class="fas fa-paper-plane"></i> Envoyer le lien de réinitialisation
                </button>
            </form>

            <div class="back-link">
                <a href="/login"><i class="fas fa-arrow-left"></i> Retour à la connexion</a>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('forgotForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            const email = document.getElementById('email').value;
            const btn = document.querySelector('.btn-reset');
            const originalText = btn.innerHTML;
            
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Envoi en cours...';
            btn.disabled = true;
            
            try {
                const response = await fetch('/forgot-password', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ email })
                });
                
                const result = await response.json();
                const msgDiv = document.getElementById('message');
                
                if (result.success) {
                    let html = `<div class="message message-success">✅ ${result.message}</div>`;
                    
                    // Afficher le lien de réinitialisation
                    if (result.reset_link) {
                        html += `
                            <div class="message message-success" style="margin-top: 10px;">
                                <strong>🔗 Lien de réinitialisation :</strong><br>
                                <a href="${result.reset_link}" target="_blank" style="word-break: break-all; color: #10b981;">${result.reset_link}</a>
                                <br>
                                <button onclick="navigator.clipboard.writeText('${result.reset_link}')" class="copy-btn">
                                    📋 Copier le lien
                                </button>
                            </div>
                        `;
                    }
                    
                    msgDiv.innerHTML = html;
                } else {
                    msgDiv.innerHTML = `<div class="message message-error">❌ ${result.message}</div>`;
                }
            } catch (error) {
                document.getElementById('message').innerHTML = `<div class="message message-error">❌ Erreur de connexion</div>`;
            } finally {
                btn.innerHTML = originalText;
                btn.disabled = false;
            }
        });
    </script>
</body>
</html>