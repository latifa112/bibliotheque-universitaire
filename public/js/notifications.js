// notifications.js
let notificationsDropdownVisible = false;

// Charger les notifications au chargement de la page
document.addEventListener('DOMContentLoaded', function() {
    loadNotifications();
    loadUnreadCount();
    
    // Rafraîchir toutes les 30 secondes
    setInterval(function() {
        loadUnreadCount();
        if (notificationsDropdownVisible) {
            loadNotifications();
        }
    }, 30000);
});

function toggleNotifications() {
    notificationsDropdownVisible = !notificationsDropdownVisible;
    const dropdown = document.getElementById('notificationsDropdown');
    if (dropdown) {
        dropdown.style.display = notificationsDropdownVisible ? 'block' : 'none';
        if (notificationsDropdownVisible) {
            loadNotifications();
        }
    }
}

function loadNotifications() {
    fetch('/notifications/list')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                renderNotifications(data.notifications);
            }
        })
        .catch(error => console.error('Erreur:', error));
}

function loadUnreadCount() {
    fetch('/notifications/unread-count')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const badge = document.getElementById('notificationsBadge');
                if (badge) {
                    const count = data.count;
                    if (count > 0) {
                        badge.textContent = count > 99 ? '99+' : count;
                        badge.style.display = 'inline-block';
                    } else {
                        badge.style.display = 'none';
                    }
                }
            }
        })
        .catch(error => console.error('Erreur:', error));
}

function renderNotifications(notifications) {
    const container = document.getElementById('notificationsList');
    if (!container) return;
    
    if (!notifications || notifications.length === 0) {
        container.innerHTML = '<div class="notification-empty">Aucune notification</div>';
        return;
    }
    
    let html = '';
    for (const notif of notifications) {
        const unreadClass = notif.is_read == 0 ? 'unread' : '';
        const icon = getNotificationIcon(notif.type);
        
        html += `
            <div class="notification-item ${unreadClass}" onclick="markAsRead(${notif.id})">
                <div class="notification-icon" style="background: rgba(99,102,241,0.1);">
                    <i class="fas ${icon}" style="color: #6366f1;"></i>
                </div>
                <div class="notification-content">
                    <div class="notification-title">${escapeHtml(notif.title)}</div>
                    <div class="notification-message">${escapeHtml(notif.message)}</div>
                    <div class="notification-time">${formatDate(notif.created_at)}</div>
                </div>
            </div>
        `;
    }
    container.innerHTML = html;
}

function getNotificationIcon(type) {
    const icons = {
        'borrow_success': 'fa-book',
        'return_success': 'fa-check-circle',
        'reserve_success': 'fa-clock',
        'book_available': 'fa-bell',
        'overdue': 'fa-exclamation-triangle'
    };
    return icons[type] || 'fa-bell';
}

function markAsRead(notificationId) {
    fetch('/notifications/mark-read', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ id: notificationId })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Recharger les notifications et le compteur
            loadNotifications();
            loadUnreadCount();
        }
    })
    .catch(error => console.error('Erreur:', error));
}

function markAllNotificationsRead() {
    fetch('/notifications/mark-all-read', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            loadNotifications();
            loadUnreadCount();
        }
    })
    .catch(error => console.error('Erreur:', error));
}

function escapeHtml(text) {
    if (!text) return '';
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

function formatDate(dateString) {
    const date = new Date(dateString);
    const now = new Date();
    const diff = now - date;
    
    const minutes = Math.floor(diff / 60000);
    const hours = Math.floor(diff / 3600000);
    const days = Math.floor(diff / 86400000);
    
    if (minutes < 1) return 'À l\'instant';
    if (minutes < 60) return `Il y a ${minutes} min`;
    if (hours < 24) return `Il y a ${hours} h`;
    if (days < 7) return `Il y a ${days} j`;
    
    return date.toLocaleDateString('fr-FR');
}
