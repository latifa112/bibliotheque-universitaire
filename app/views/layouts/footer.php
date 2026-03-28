        </main>
    </div>

    <div class="chatbot-3d" id="chatbot3d">
        <div class="chatbot-sphere" onclick="toggleChatbot()">
            <i class="fas fa-robot"></i>
        </div>
        <div class="chatbot-window-3d">
            <div class="chatbot-header">
                <h4><i class="fas fa-robot"></i> Assistant Athena</h4>
            </div>
            <div class="chatbot-messages" id="chatMessages">
                <div style="margin-bottom: 1rem;">
                    <div style="background: rgba(255,255,255,0.1); padding: 0.75rem; border-radius: 15px; max-width: 80%;">
                        Bonjour ! Je suis votre assistant virtuel. Comment puis-je vous aider ?
                    </div>
                </div>
            </div>
            <div class="chatbot-input">
                <input type="text" id="chatInput" placeholder="Votre message...">
                <button onclick="sendChatMessage()"><i class="fas fa-paper-plane"></i></button>
            </div>
        </div>
    </div>

    <script>
        window.addEventListener('load', function() {
            setTimeout(function() {
                const loader = document.getElementById('loader');
                if (loader) loader.classList.add('hidden');
            }, 500);
        });

        const cursor = document.getElementById('cursor');
        const follower = document.getElementById('cursorFollower');

        if (window.innerWidth > 1024) {
            document.addEventListener('mousemove', function(e) {
                if (cursor) {
                    cursor.style.left = e.clientX + 'px';
                    cursor.style.top = e.clientY + 'px';
                }
                setTimeout(function() {
                    if (follower) {
                        follower.style.left = e.clientX + 'px';
                        follower.style.top = e.clientY + 'px';
                    }
                }, 50);
            });
        }

        const canvas = document.getElementById('particles');
        if (canvas) {
            const ctx = canvas.getContext('2d');
            canvas.width = window.innerWidth;
            canvas.height = window.innerHeight;

            class Particle {
                constructor() {
                    this.x = Math.random() * canvas.width;
                    this.y = Math.random() * canvas.height;
                    this.size = Math.random() * 2;
                    this.speedX = (Math.random() - 0.5) * 0.3;
                    this.speedY = (Math.random() - 0.5) * 0.3;
                    this.color = `rgba(99, 102, 241, ${Math.random() * 0.5})`;
                }
                update() {
                    this.x += this.speedX;
                    this.y += this.speedY;
                    if (this.x > canvas.width) this.x = 0;
                    if (this.x < 0) this.x = canvas.width;
                    if (this.y > canvas.height) this.y = 0;
                    if (this.y < 0) this.y = canvas.height;
                }
                draw() {
                    ctx.fillStyle = this.color;
                    ctx.beginPath();
                    ctx.arc(this.x, this.y, this.size, 0, Math.PI * 2);
                    ctx.fill();
                }
            }

            const particles = [];
            for (let i = 0; i < 100; i++) particles.push(new Particle());

            function animate() {
                ctx.clearRect(0, 0, canvas.width, canvas.height);
                particles.forEach(p => { p.update(); p.draw(); });
                requestAnimationFrame(animate);
            }
            animate();

            window.addEventListener('resize', () => {
                canvas.width = window.innerWidth;
                canvas.height = window.innerHeight;
            });
        }

        function toggleChatbot() {
            const chatbot = document.getElementById('chatbot3d');
            if (chatbot) chatbot.classList.toggle('active');
        }

function sendChatMessage() {
    const input = document.getElementById('chatInput');
    const message = input.value.trim();
    if (!message) return;
    
    const messagesDiv = document.getElementById('chatMessages');
    
    // Ajouter le message de l'utilisateur
    messagesDiv.innerHTML += `
        <div style="margin-bottom: 1rem; text-align: right;">
            <div style="background: var(--primary); padding: 0.75rem; border-radius: 15px; display: inline-block; max-width: 80%;">
                ${escapeHtml(message)}
            </div>
        </div>
    `;
    input.value = '';
    
    // Analyser le message et générer une réponse
    const response = getChatbotResponse(message);
    
    // Ajouter la réponse avec un délai
    setTimeout(() => {
        messagesDiv.innerHTML += `
            <div style="margin-bottom: 1rem;">
                <div style="background: rgba(255,255,255,0.1); padding: 0.75rem; border-radius: 15px; max-width: 80%;">
                    ${response}
                </div>
            </div>
        `;
        messagesDiv.scrollTop = messagesDiv.scrollHeight;
    }, 500);
}

function getChatbotResponse(message) {
    const msg = message.toLowerCase();
    
    // Livres recommandés
    const livresPhysique = [
        "📚 Voici quelques livres de physique recommandés :<br>• 'Physique quantique' par Prof. Marie Laurent<br>• 'Introduction à la mécanique quantique' par David J. Griffiths<br>• 'L\'univers élégant' par Brian Greene",
        "🔬 Je vous recommande 'Physique quantique : Voyage au cœur de la matière' disponible dans notre catalogue.",
        "📖 Pour la physique, je vous suggère de consulter notre section Sciences, vous y trouverez des ouvrages passionnants !"
    ];
    
    const livresInformatique = [
        "💻 Voici des livres d'informatique :<br>• 'L'intelligence artificielle pour les nuls' par Dr. Sarah Chen<br>• 'Architecture logicielle' par Elena Rodriguez<br>• 'Clean Code' par Robert C. Martin",
        "🖥️ Je vous recommande 'Architecture logicielle : Patterns et bonnes pratiques' disponible dans notre catalogue."
    ];
    
    const livresHistoire = [
        "📜 Pour l'histoire :<br>• 'Histoire des civilisations' par Thomas Anderson<br>• 'La Révolution Française' par Prof. Sophie Martin<br>• 'Sapiens' par Yuval Noah Harari",
        "🏛️ 'Histoire des civilisations' est très populaire auprès de nos lecteurs !"
    ];
    
    const livresLitterature = [
        "📖 En littérature :<br>• 'Les Misérables' par Victor Hugo<br>• 'Le Petit Prince' par Antoine de Saint-Exupéry<br>• 'L'Étranger' par Albert Camus",
        "📚 Je vous recommande 'Les Misérables', un classique incontournable !"
    ];
    
    // Réponses pour les emprunts
    if (msg.includes('emprunter') || msg.includes('emprunt')) {
        return "📖 Pour emprunter un livre, connectez-vous, allez dans le catalogue, cliquez sur 'Emprunter' sur le livre de votre choix. Vous avez 14 jours pour le retourner !";
    }
    
    // Réponses pour les retours
    if (msg.includes('retour') || msg.includes('rendre')) {
        return "🔄 Pour retourner un livre, allez dans 'Mes emprunts' et cliquez sur 'Retourner' à côté du livre concerné.";
    }
    
    // Réponses pour les réservations
    if (msg.includes('reserv') || msg.includes('réserver')) {
        return "📅 Pour réserver un livre indisponible, cliquez sur 'Réserver' dans le catalogue. Vous serez notifié quand le livre sera disponible.";
    }
    
    // Réponses pour les livres physiques
    if (msg.includes('physique') || (msg.includes('science') && !msg.includes('informatique'))) {
        return livresPhysique[Math.floor(Math.random() * livresPhysique.length)];
    }
    
    // Réponses pour l'informatique
    if (msg.includes('informatique') || msg.includes('programmation') || msg.includes('ia') || msg.includes('intelligence')) {
        return livresInformatique[Math.floor(Math.random() * livresInformatique.length)];
    }
    
    // Réponses pour l'histoire
    if (msg.includes('histoire') || msg.includes('civilisation')) {
        return livresHistoire[Math.floor(Math.random() * livresHistoire.length)];
    }
    
    // Réponses pour la littérature
    if (msg.includes('littérature') || msg.includes('roman') || msg.includes('livre')) {
        return livresLitterature[Math.floor(Math.random() * livresLitterature.length)];
    }
    
    // Réponses pour les catégories
    if (msg.includes('catalogue') || msg.includes('livres')) {
        return "📚 Notre catalogue propose plus de 50 000 ouvrages répartis en plusieurs catégories : Informatique, Sciences, Histoire, Littérature, Art, Philosophie... Consultez-le en ligne !";
    }
    
    // Réponses pour les notifications
    if (msg.includes('notif') || msg.includes('alerte')) {
        return "🔔 Vous recevrez des notifications pour vos emprunts et réservations. Cliquez sur la cloche en haut à droite pour les voir !";
    }
    
    // Réponses pour le profil
    if (msg.includes('profil') || msg.includes('compte')) {
        return "👤 Pour modifier votre profil, allez dans Paramètres. Vous pouvez y changer vos informations personnelles et votre mot de passe.";
    }
    
    // Réponses pour la connexion
    if (msg.includes('connexion') || msg.includes('login')) {
        return "🔐 Pour vous connecter, utilisez votre email et mot de passe. Si vous n'avez pas de compte, inscrivez-vous gratuitement !";
    }
    
    // Réponses pour l'aide
    if (msg.includes('aide') || msg.includes('help') || msg.includes('comment')) {
        return "🤖 Je suis votre assistant virtuel ! Je peux vous aider à :<br>• Trouver des livres<br>• Comprendre comment emprunter<br>• Réserver des livres indisponibles<br>• Gérer votre profil<br>Que souhaitez-vous ?";
    }
    
    // Réponse par défaut pour les salutations
    if (msg.includes('bonjour') || msg.includes('salut') || msg.includes('hello') || msg.includes('coucou')) {
        return "Bonjour ! 👋 Comment puis-je vous aider aujourd'hui ? Je peux vous recommander des livres ou vous aider à naviguer sur BiblioGest.";
    }
    
    // Réponse par défaut
    return "🤔 Je n'ai pas bien compris. Pouvez-vous préciser votre demande ?<br><br>Voici ce que je peux faire :<br>• 📚 Vous recommander des livres par catégorie (physique, informatique, histoire...)<br>• 📖 Vous expliquer comment emprunter ou réserver<br>• 👤 Vous aider avec votre profil<br>• ❓ Répondre à vos questions sur BiblioGest";
}

// Ajouter des suggestions de questions
function addSuggestions() {
    const suggestions = [
        "Je cherche un livre de physique",
        "Comment emprunter un livre ?",
        "Quels sont les livres en informatique ?",
        "Comment retourner un livre ?"
    ];
    
    const suggestionsDiv = document.createElement('div');
    suggestionsDiv.style.display = 'flex';
    suggestionsDiv.style.gap = '0.5rem';
    suggestionsDiv.style.flexWrap = 'wrap';
    suggestionsDiv.style.marginTop = '1rem';
    
    suggestions.forEach(suggestion => {
        const btn = document.createElement('button');
        btn.textContent = suggestion;
        btn.style.background = 'rgba(99, 102, 241, 0.2)';
        btn.style.border = '1px solid var(--glass-border)';
        btn.style.borderRadius = '20px';
        btn.style.padding = '0.4rem 0.8rem';
        btn.style.fontSize = '0.7rem';
        btn.style.cursor = 'pointer';
        btn.onclick = () => {
            document.getElementById('chatInput').value = suggestion;
            sendChatMessage();
        };
        suggestionsDiv.appendChild(btn);
    });
    
    document.getElementById('chatMessages').appendChild(suggestionsDiv);
}

function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }

        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            if (sidebar) sidebar.classList.toggle('active');
        }

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = 1;
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        });

        document.querySelectorAll('.stat-card, .book-card').forEach(el => {
            el.style.opacity = 0;
            el.style.transform = 'translateY(20px)';
            el.style.transition = 'all 0.6s ease';
            observer.observe(el);
        });

        async function loadNotifications() {
            try {
                const response = await fetch('/api/notifications');
                const data = await response.json();
                
                if (data.success) {
                    const badge = document.getElementById('notificationsBadge');
                    if (badge) {
                        const count = data.unread_count;
                        badge.textContent = count;
                        badge.style.display = count > 0 ? 'flex' : 'none';
                    }
                    displayNotifications(data.notifications);
                }
            } catch (error) {
                console.error('Erreur chargement notifications:', error);
            }
        }

        function displayNotifications(notifications) {
            const container = document.getElementById('notificationsList');
            if (!container) return;
            
            if (notifications.length === 0) {
                container.innerHTML = '<div class="notification-empty">Aucune notification</div>';
                return;
            }
            
            container.innerHTML = notifications.map(notif => `
                <div class="notification-item ${notif.is_read ? '' : 'unread'}" onclick="handleNotificationClick(${notif.id}, '${notif.link}')">
                    <div class="notification-icon ${notif.type}">
                        <i class="fas ${getIconForType(notif.type)}"></i>
                    </div>
                    <div class="notification-content">
                        <div class="notification-title">${escapeHtml(notif.title)}</div>
                        <div class="notification-message">${escapeHtml(notif.message)}</div>
                        <div class="notification-time">${formatDate(notif.created_at)}</div>
                    </div>
                </div>
            `).join('');
        }

        function getIconForType(type) {
            const icons = {
                'info': 'fa-info-circle',
                'success': 'fa-check-circle',
                'warning': 'fa-exclamation-triangle',
                'danger': 'fa-times-circle'
            };
            return icons[type] || 'fa-bell';
        }

        function formatDate(dateString) {
            const date = new Date(dateString);
            const now = new Date();
            const diff = Math.floor((now - date) / 1000);
            
            if (diff < 60) return 'à l\'instant';
            if (diff < 3600) return `il y a ${Math.floor(diff / 60)} min`;
            if (diff < 86400) return `il y a ${Math.floor(diff / 3600)} h`;
            return `il y a ${Math.floor(diff / 86400)} j`;
        }

        async function handleNotificationClick(notificationId, link) {
            try {
                await fetch('/api/notifications/read', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/json'},
                    body: JSON.stringify({id: notificationId})
                });
                
                if (link && link !== '#') {
                    window.location.href = link;
                } else {
                    loadNotifications();
                    toggleNotifications();
                }
            } catch (error) {
                console.error('Erreur:', error);
            }
        }

        async function markAllNotificationsRead() {
            try {
                const response = await fetch('/api/notifications/read-all', {method: 'POST'});
                const data = await response.json();
                if (data.success) {
                    loadNotifications();
                }
            } catch (error) {
                console.error('Erreur:', error);
            }
        }

        function toggleNotifications() {
            const dropdown = document.getElementById('notificationsDropdown');
            if (dropdown) {
                const isVisible = dropdown.style.display === 'block';
                dropdown.style.display = isVisible ? 'none' : 'block';
                if (!isVisible) {
                    loadNotifications();
                }
            }
        }

        document.addEventListener('click', function(event) {
            const dropdown = document.getElementById('notificationsDropdown');
            const btn = document.getElementById('notificationsBtn');
            if (dropdown && btn && !dropdown.contains(event.target) && !btn.contains(event.target)) {
                dropdown.style.display = 'none';
            }
        });

        document.addEventListener('DOMContentLoaded', function() {
            loadNotifications();
            setInterval(loadNotifications, 30000);
        });
    </script>
</body>
</html>