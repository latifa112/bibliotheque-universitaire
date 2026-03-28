<?php
$preferences = $_SESSION['preferences'] ?? ['theme' => 'dark', 'language' => 'fr'];
$themeClass = $preferences['theme'] === 'light' ? 'light-theme' : 'dark-theme';
?>
<!DOCTYPE html>
<html lang="<?php echo $preferences['language']; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BiblioGest - Bibliothèque Universitaire 4.0</title>
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
            --secondary: #8b5cf6;
            --accent: #ec4899;
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
            --info: #3b82f6;
            --gradient-1: linear-gradient(135deg, #6366f1 0%, #8b5cf6 50%, #ec4899 100%);
            --gradient-3: linear-gradient(135deg, #10b981 0%, #3b82f6 100%);
            --shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
            --shadow-neon: 0 0 20px rgba(99, 102, 241, 0.3);
        }

        body {
            --bg-primary: #0f172a;
            --bg-secondary: #1e293b;
            --text-primary: #f8fafc;
            --text-secondary: #94a3b8;
            --card-bg: rgba(255, 255, 255, 0.05);
            --border-color: rgba(255, 255, 255, 0.1);
            --input-bg: rgba(255, 255, 255, 0.05);
            --input-border: rgba(255, 255, 255, 0.1);
            --input-focus: #6366f1;
            --label-color: rgba(255, 255, 255, 0.6);
            --glass: rgba(255, 255, 255, 0.05);
            --glass-border: rgba(255, 255, 255, 0.1);
            font-family: 'Space Grotesk', sans-serif;
            background: var(--bg-primary);
            color: var(--text-primary);
            line-height: 1.6;
            overflow-x: hidden;
        }

        body.light-theme {
            --bg-primary: #f5f7fb;
            --bg-secondary: #ffffff;
            --text-primary: #1e293b;
            --text-secondary: #64748b;
            --card-bg: #ffffff;
            --border-color: #e9eef3;
            --input-bg: #ffffff;
            --input-border: #e2e8f0;
            --input-focus: #6366f1;
            --label-color: #94a3b8;
            --glass: rgba(255, 255, 255, 0.8);
            --glass-border: #e9eef3;
            --shadow-sm: 0 1px 2px rgba(0, 0, 0, 0.05);
            --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }

        h1, h2, h3, h4 {
            font-family: 'Outfit', sans-serif;
            font-weight: 700;
        }

        .cursor {
            width: 20px;
            height: 20px;
            border: 2px solid var(--primary);
            border-radius: 50%;
            position: fixed;
            pointer-events: none;
            z-index: 9999;
            transition: all 0.1s ease;
            transform: translate(-50%, -50%);
            mix-blend-mode: difference;
        }

        .cursor-follower {
            width: 40px;
            height: 40px;
            background: rgba(99, 102, 241, 0.1);
            border-radius: 50%;
            position: fixed;
            pointer-events: none;
            z-index: 9998;
            transition: all 0.3s ease;
            transform: translate(-50%, -50%);
            backdrop-filter: blur(2px);
        }

        #particles {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            pointer-events: none;
        }

        .app {
            display: flex;
            min-height: 100vh;
            position: relative;
        }

        .sidebar {
            width: 300px;
            background: var(--bg-secondary);
            backdrop-filter: blur(20px);
            border-right: 2px solid var(--border-color);
            position: fixed;
            height: 100vh;
            overflow-y: auto;
            z-index: 1000;
            box-shadow: 10px 0 30px rgba(0, 0, 0, 0.5);
        }

        .sidebar-header {
            padding: 2.5rem 1.5rem;
            border-bottom: 1px solid var(--border-color);
            text-align: center;
        }

        .sidebar-logo {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 1rem;
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .sidebar-logo i {
            font-size: 2.5rem;
            color: var(--primary);
            filter: drop-shadow(0 0 10px var(--primary));
        }

        .sidebar-subtitle {
            font-size: 1rem;
            opacity: 0.8;
            color: var(--text-secondary);
        }

        .sidebar-nav {
            padding: 2rem 0.5rem;
        }

        .nav-item {
            display: flex;
            align-items: center;
            padding: 1rem 1.5rem;
            color: var(--text-secondary);
            text-decoration: none;
            transition: all 0.3s ease;
            margin: 0.5rem 0;
            border-left: 4px solid transparent;
            border-radius: 0 10px 10px 0;
            font-size: 1.1rem;
        }

        .nav-item i {
            width: 30px;
            margin-right: 1rem;
            font-size: 1.3rem;
            color: var(--primary);
        }

        .nav-item:hover {
            background: rgba(99, 102, 241, 0.15);
            color: var(--text-primary);
            border-left-color: var(--primary);
            transform: translateX(5px);
        }

        .nav-item.active {
            background: linear-gradient(90deg, rgba(99, 102, 241, 0.2) 0%, transparent 100%);
            color: var(--text-primary);
            border-left-color: var(--primary);
            box-shadow: 0 0 20px rgba(99, 102, 241, 0.3);
        }

        .nav-badge {
            margin-left: auto;
            background: rgba(255,255,255,0.1);
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.8rem;
        }

        .main-content {
            flex: 1;
            margin-left: 300px;
            padding: 2rem;
            min-height: 100vh;
            width: calc(100% - 300px);
        }

        .top-bar {
            background: var(--glass);
            backdrop-filter: blur(20px);
            border: 1px solid var(--glass-border);
            border-radius: 12px;
            padding: 1rem 2rem;
            margin-bottom: 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .page-title {
            font-size: 1.5rem;
            font-weight: 600;
            background: var(--gradient-1);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .user-menu {
            display: flex;
            align-items: center;
            gap: 1.5rem;
        }

        .notifications {
            position: relative;
            cursor: pointer;
            color: var(--text-primary);
            font-size: 1.25rem;
        }

        .notifications-badge {
            position: absolute;
            top: -8px;
            right: -8px;
            background: var(--danger);
            color: white;
            font-size: 0.7rem;
            padding: 0.2rem 0.5rem;
            border-radius: 10px;
            font-weight: 600;
            display: none;
        }

        .user-avatar {
            width: 42px;
            height: 42px;
            background: var(--gradient-1);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            color: white;
        }

        .user-avatar:hover {
            transform: scale(1.1);
            box-shadow: var(--shadow-neon);
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: var(--card-bg);
            backdrop-filter: blur(10px);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            padding: 1.5rem;
            display: flex;
            align-items: center;
            gap: 1.5rem;
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-neon);
            border-color: var(--primary);
        }

        .stat-icon {
            width: 60px;
            height: 60px;
            background: var(--gradient-1);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            transition: transform 0.3s ease;
        }

        .stat-card:hover .stat-icon {
            transform: scale(1.1);
        }

        .stat-info h3 {
            font-size: 1.75rem;
            font-weight: 700;
            margin-bottom: 0.25rem;
            color: var(--text-primary);
        }

        .stat-info p {
            color: var(--text-secondary);
            font-size: 0.875rem;
        }

        .section {
            background: var(--card-bg);
            backdrop-filter: blur(10px);
            border: 1px solid var(--border-color);
            border-radius: 20px;
            padding: 2rem;
            margin-bottom: 2rem;
        }

        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }

        .section-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--text-primary);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .section-title i {
            color: var(--primary);
        }

        .btn {
            padding: 0.625rem 1.25rem;
            border: none;
            border-radius: 8px;
            font-weight: 500;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
            font-size: 0.875rem;
            text-decoration: none;
        }

        .btn-primary {
            background: var(--gradient-1);
            color: white;
        }

        .btn-primary:hover {
            opacity: 0.9;
            transform: translateY(-2px);
            box-shadow: var(--shadow-neon);
        }

        .btn-secondary {
            background: rgba(255,255,255,0.1);
            border: 1px solid var(--border-color);
            color: var(--text-primary);
        }

        .btn-secondary:hover {
            background: rgba(99, 102, 241, 0.2);
            transform: translateY(-2px);
        }

        .books-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 2rem;
        }

        .book-card {
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 16px;
            overflow: hidden;
            transition: all 0.4s ease;
        }

        .book-card:hover {
            transform: translateY(-10px) scale(1.02);
            border-color: var(--primary);
            box-shadow: var(--shadow-neon);
        }

        .book-cover {
            height: 280px;
            background-size: cover;
            background-position: center;
            position: relative;
        }

        .book-info {
            padding: 1.5rem;
            background: var(--card-bg);
        }

        .book-title {
            font-size: 1.2rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .book-author {
            color: var(--text-secondary);
            font-size: 0.9rem;
            margin-bottom: 0.75rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .book-author i {
            color: var(--primary);
        }

        .book-meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: 1rem 0;
            padding: 0.75rem 0;
            border-top: 1px solid var(--border-color);
            border-bottom: 1px solid var(--border-color);
        }

        .badge {
            padding: 0.35rem 1rem;
            border-radius: 50px;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .badge-success {
            background: rgba(16, 185, 129, 0.2);
            color: var(--success);
            border: 1px solid rgba(16, 185, 129, 0.3);
        }

        .badge-warning {
            background: rgba(245, 158, 11, 0.2);
            color: var(--warning);
            border: 1px solid rgba(245, 158, 11, 0.3);
        }

        .book-actions {
            display: flex;
            gap: 0.75rem;
            margin-top: 1rem;
        }

        .book-action-btn {
            flex: 1;
            padding: 0.75rem;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .book-action-btn.borrow {
            background: var(--gradient-3);
            color: white;
        }

        .table-wrapper {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 1rem;
            text-align: left;
            border-bottom: 1px solid var(--border-color);
        }

        th {
            color: var(--text-secondary);
            font-weight: 600;
        }

        .chatbot-3d {
            position: fixed;
            bottom: 2rem;
            right: 2rem;
            z-index: 2000;
        }

        .chatbot-sphere {
            width: 70px;
            height: 70px;
            background: var(--gradient-1);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            cursor: pointer;
            box-shadow: var(--shadow-neon);
        }

        .chatbot-sphere:hover {
            transform: scale(1.1);
        }

        .chatbot-window-3d {
            position: absolute;
            bottom: 90px;
            right: 0;
            width: 350px;
            height: 450px;
            background: var(--bg-secondary);
            backdrop-filter: blur(20px);
            border: 1px solid var(--border-color);
            border-radius: 28px;
            display: none;
            box-shadow: var(--shadow);
        }

        .chatbot-3d.active .chatbot-window-3d {
            display: block;
        }

        .chatbot-header {
            background: var(--gradient-1);
            padding: 1.5rem;
            border-radius: 28px 28px 0 0;
            color: white;
        }

        .chatbot-messages {
            height: 300px;
            overflow-y: auto;
            padding: 1rem;
        }

        .chatbot-input {
            display: flex;
            padding: 1rem;
            gap: 0.5rem;
            border-top: 1px solid var(--border-color);
        }

        .chatbot-input input {
            flex: 1;
            padding: 0.75rem;
            background: rgba(255,255,255,0.1);
            border: 1px solid var(--border-color);
            border-radius: 10px;
            color: var(--text-primary);
        }

        .chatbot-input button {
            padding: 0.75rem 1.5rem;
            background: var(--gradient-1);
            border: none;
            border-radius: 10px;
            color: white;
            cursor: pointer;
        }

        .loader {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: var(--bg-primary);
            z-index: 9999;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: opacity 0.5s ease;
        }

        .loader.hidden {
            opacity: 0;
            pointer-events: none;
        }

        /* ========== THÈME CLAIR COMPLET ========== */
        body.light-theme {
            background: var(--bg-primary);
        }

        body.light-theme .sidebar {
            background: var(--bg-secondary);
            border-right-color: var(--border-color);
            box-shadow: var(--shadow-md);
        }

        body.light-theme .sidebar-header {
            border-bottom-color: var(--border-color);
        }

        body.light-theme .sidebar-logo span {
            color: var(--text-primary);
        }

        body.light-theme .nav-item {
            color: var(--text-secondary);
        }

        body.light-theme .nav-item:hover {
            background: #f1f5f9;
            color: var(--primary);
        }

        body.light-theme .nav-item.active {
            background: linear-gradient(90deg, rgba(99, 102, 241, 0.08) 0%, transparent 100%);
            color: var(--primary);
        }

        body.light-theme .stat-card,
        body.light-theme .section,
        body.light-theme .settings-card,
        body.light-theme .book-card {
            background: var(--card-bg);
            border-color: var(--border-color);
            box-shadow: var(--shadow-sm);
        }

        body.light-theme .stat-card:hover,
        body.light-theme .section:hover,
        body.light-theme .settings-card:hover,
        body.light-theme .book-card:hover {
            box-shadow: var(--shadow-lg);
            border-color: var(--primary);
        }

        body.light-theme .top-bar {
            background: var(--card-bg);
            border-color: var(--border-color);
            box-shadow: var(--shadow-sm);
        }

        body.light-theme .page-title {
            background: var(--gradient-1);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        body.light-theme .input-group input,
        body.light-theme .input-group select,
        body.light-theme .input-group textarea {
            background: var(--input-bg);
            border-color: var(--input-border);
            color: var(--text-primary);
        }

        body.light-theme .input-group input:focus {
            border-color: var(--input-focus);
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
            background: white;
        }

        body.light-theme .input-group label {
            color: var(--label-color);
        }

        body.light-theme .input-group input:focus ~ label,
        body.light-theme .input-group input:not(:placeholder-shown) ~ label {
            background: white;
            color: var(--primary);
        }

        body.light-theme .btn-primary {
            background: var(--gradient-1);
            color: white;
        }

        body.light-theme .btn-secondary {
            background: #f1f5f9;
            border-color: var(--border-color);
            color: var(--text-secondary);
        }

        body.light-theme .btn-secondary:hover {
            background: #e2e8f0;
            color: var(--text-primary);
        }

        body.light-theme .badge-success {
            background: rgba(16, 185, 129, 0.1);
            color: #059669;
        }

        body.light-theme .badge-warning {
            background: rgba(245, 158, 11, 0.1);
            color: #d97706;
        }

        body.light-theme .badge-danger {
            background: rgba(239, 68, 68, 0.1);
            color: #dc2626;
        }

        body.light-theme .badge-info {
            background: rgba(59, 130, 246, 0.1);
            color: #2563eb;
        }

        body.light-theme .notifications-dropdown {
            background: var(--bg-secondary);
            border-color: var(--border-color);
            box-shadow: var(--shadow-lg);
        }

        body.light-theme .notification-item {
            border-bottom-color: var(--border-color);
        }

        body.light-theme .notification-item:hover {
            background: #f8fafc;
        }

        body.light-theme .chatbot-window-3d {
            background: var(--bg-secondary);
            border-color: var(--border-color);
            box-shadow: var(--shadow-lg);
        }

        body.light-theme .chatbot-input input {
            background: var(--input-bg);
            border-color: var(--input-border);
            color: var(--text-primary);
        }

        body.light-theme .chatbot-messages {
            background: #fafcff;
        }

        body.light-theme .chatbot-messages > div > div {
            background: #f1f5f9;
            color: var(--text-primary);
        }

        body.light-theme th {
            background: #f8fafc;
            color: var(--text-secondary);
        }

        body.light-theme td {
            border-bottom-color: var(--border-color);
        }

        body.light-theme tr:hover td {
            background: #f8fafc;
        }

        body.light-theme .settings-hero {
            background: linear-gradient(135deg, rgba(99, 102, 241, 0.08), rgba(139, 92, 246, 0.03));
            border-color: var(--border-color);
        }

        body.light-theme .hero-stat {
            background: var(--card-bg);
            border-color: var(--border-color);
        }

        body.light-theme .hero-stat .stat-number {
            color: var(--primary);
        }

        body.light-theme .card-header {
            background: #fafcff;
            border-bottom-color: var(--border-color);
        }

        body.light-theme .stat-item-modern,
        body.light-theme .select-item,
        body.light-theme .toggle-item {
            background: #fafcff;
            border-color: var(--border-color);
        }

        body.light-theme .stat-icon {
            background: rgba(99, 102, 241, 0.1);
            color: var(--primary);
        }

        body.light-theme .info-icon-bg {
            background: rgba(99, 102, 241, 0.1);
        }

        /* ========== SECTION STATISTIQUES MODE CLAIR ========== */
        body.light-theme .stats-card {
            background: var(--card-bg);
            border-color: var(--border-color);
        }

        body.light-theme .stats-card .card-header {
            background: #f8fafc;
            border-bottom-color: var(--border-color);
        }

        body.light-theme .stats-card .card-header .header-icon-wrapper {
            background: rgba(99, 102, 241, 0.1);
        }

        body.light-theme .stats-card .card-header .header-icon-wrapper i {
            color: var(--primary);
        }

        body.light-theme .stats-card .card-header h2 {
            color: var(--text-primary);
        }

        body.light-theme .stats-card .card-header p {
            color: var(--text-secondary);
        }

        body.light-theme .stat-circle .circle-value {
            color: var(--primary);
            font-weight: 700;
        }

        body.light-theme .stat-circle .circle-label {
            color: var(--text-secondary);
        }

        body.light-theme .stat-circle svg circle:first-child {
            stroke: #e2e8f0;
        }

        body.light-theme .stats-list-modern {
            background: transparent;
        }

        body.light-theme .stat-item-modern {
            background: #f8fafc;
            border: 1px solid var(--border-color);
            border-radius: 12px;
        }

        body.light-theme .stat-item-modern .stat-icon {
            background: rgba(99, 102, 241, 0.1);
            color: var(--primary);
        }

        body.light-theme .stat-item-modern .stat-label {
            color: var(--text-secondary);
            font-weight: 500;
        }

        body.light-theme .stat-item-modern .stat-value {
            color: var(--primary);
            font-weight: 700;
            font-size: 1.1rem;
        }

        body.light-theme .stat-card:hover,
        body.light-theme .book-card:hover,
        body.light-theme .settings-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-lg);
        }

        body.light-theme .avatar-section {
            background: var(--card-bg);
            border-color: var(--border-color);
        }

        body.light-theme .info-section {
            background: var(--card-bg);
            border-color: var(--border-color);
        }

        body.light-theme .info-card {
            background: #fafcff;
        }

        body.light-theme .password-strength {
            background: var(--input-border);
        }

        body.light-theme .theme-option {
            background: #fafcff;
            border-color: var(--border-color);
        }

        body.light-theme .theme-option.active {
            border-color: var(--primary);
            background: rgba(99, 102, 241, 0.05);
        }

        body.light-theme .slider {
            background-color: #cbd5e1;
        }

        body.light-theme input:checked + .slider {
            background: linear-gradient(135deg, #10b981, #059669);
        }

        .menu-toggle {
            display: none;
            position: fixed;
            top: 1rem;
            left: 1rem;
            z-index: 1001;
            background: var(--gradient-1);
            color: white;
            border: none;
            border-radius: 8px;
            padding: 0.75rem;
            cursor: pointer;
        }

        .notifications-dropdown {
            position: absolute;
            top: 80px;
            right: 80px;
            width: 380px;
            background: var(--bg-secondary);
            backdrop-filter: blur(20px);
            border: 1px solid var(--border-color);
            border-radius: 16px;
            box-shadow: var(--shadow);
            z-index: 1100;
            overflow: hidden;
        }

        .notifications-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 1.5rem;
            border-bottom: 1px solid var(--border-color);
            background: rgba(99, 102, 241, 0.1);
        }

        .mark-all-read {
            background: none;
            border: none;
            color: var(--primary);
            cursor: pointer;
        }

        .notification-item {
            display: flex;
            padding: 1rem 1.5rem;
            border-bottom: 1px solid var(--border-color);
            cursor: pointer;
        }

        .notification-item:hover {
            background: rgba(99, 102, 241, 0.05);
        }

        .notification-item.unread {
            background: rgba(99, 102, 241, 0.1);
        }

        .notification-icon {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 1rem;
        }

        .input-group {
            position: relative;
            margin-bottom: 1.5rem;
        }

        .input-group input,
        .input-group select,
        .input-group textarea {
            width: 100%;
            padding: 1rem 1rem 1rem 3rem;
            background: var(--input-bg);
            border: 1px solid var(--input-border);
            border-radius: 12px;
            color: var(--text-primary);
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .input-group input:focus,
        .input-group select:focus {
            outline: none;
            border-color: var(--input-focus);
            background: var(--bg-secondary);
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
        }

        .input-group i:first-child {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--primary);
            font-size: 1.1rem;
        }

        .input-group label {
            position: absolute;
            left: 3rem;
            top: 1rem;
            color: var(--label-color);
            pointer-events: none;
            transition: all 0.3s ease;
            font-size: 1rem;
            background: transparent;
        }

        .input-group input:focus ~ label,
        .input-group input:not(:placeholder-shown) ~ label,
        .input-group select:focus ~ label,
        .input-group select:not([value=""]) ~ label {
            top: -0.5rem;
            left: 2rem;
            font-size: 0.7rem;
            background: var(--bg-secondary);
            padding: 0 0.25rem;
            color: var(--primary);
        }

        @media (max-width: 1024px) {
            .sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s ease;
                position: fixed;
            }
            .sidebar.active {
                transform: translateX(0);
            }
            .main-content {
                margin-left: 0;
                width: 100%;
            }
            .menu-toggle {
                display: block;
            }
            .cursor, .cursor-follower {
                display: none;
            }
        }
    </style>
</head>
<body class="<?php echo $themeClass; ?>">
    <button class="menu-toggle" onclick="toggleSidebar()">
        <i class="fas fa-bars"></i>
    </button>

    <div class="loader" id="loader">
        <div class="book-loader"></div>
    </div>

    <div class="cursor" id="cursor"></div>
    <div class="cursor-follower" id="cursorFollower"></div>

    <canvas id="particles"></canvas>

    <div class="app">
        <aside class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <div class="sidebar-logo">
                    <i class="fas fa-university"></i>
                    <span>BiblioGest</span>
                </div>
                <div class="sidebar-subtitle">Gestion de bibliothèque</div>
            </div>
            <nav class="sidebar-nav">
    <a href="/dashboard" class="nav-item <?php echo $activePage == 'dashboard' ? 'active' : ''; ?>">
        <i class="fas fa-chart-pie"></i>
        <span><?php echo __('dashboard'); ?></span>
    </a>
    <a href="/books" class="nav-item <?php echo $activePage == 'books' ? 'active' : ''; ?>">
        <i class="fas fa-book"></i>
        <span><?php echo __('catalogue'); ?></span>
        <span class="nav-badge"><?php echo $totalBooks ?? 0; ?></span>
    </a>
    <a href="/loans" class="nav-item <?php echo $activePage == 'loans' ? 'active' : ''; ?>">
        <i class="fas fa-exchange-alt"></i>
        <span><?php echo __('loans'); ?></span>
        <span class="nav-badge"><?php echo $activeLoans ?? 0; ?></span>
    </a>
    <a href="/reservations" class="nav-item <?php echo $activePage == 'reservations' ? 'active' : ''; ?>">
        <i class="fas fa-clock"></i>
        <span><?php echo __('reservations'); ?></span>
        <span class="nav-badge"><?php echo $totalReservations ?? 0; ?></span>
    </a>
    <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin'): ?>
    <a href="/users" class="nav-item <?php echo $activePage == 'users' ? 'active' : ''; ?>">
        <i class="fas fa-users"></i>
        <span><?php echo __('users'); ?></span>
        <span class="nav-badge"><?php echo $totalUsers ?? 0; ?></span>
    </a>
    <?php endif; ?>
    <a href="/statistics" class="nav-item <?php echo $activePage == 'statistics' ? 'active' : ''; ?>">
        <i class="fas fa-chart-line"></i>
        <span><?php echo __('statistics'); ?></span>
    </a>
    <a href="/settings" class="nav-item <?php echo $activePage == 'settings' ? 'active' : ''; ?>">
        <i class="fas fa-cog"></i>
        <span><?php echo __('settings'); ?></span>
    </a>
<?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin'): ?>
<a href="/admin/backups" class="nav-item <?php echo $activePage == 'backups' ? 'active' : ''; ?>">
    <i class="fas fa-database"></i>
    <span><?php echo __('backups'); ?></span>
</a>
<?php endif; ?>
    <a href="/logout" class="nav-item">
        <i class="fas fa-sign-out-alt"></i>
        <span><?php echo __('logout'); ?></span>
    </a>
</nav>
        </aside>

        <main class="main-content">
            <div class="top-bar">
                <h1 class="page-title">
                    <i class="fas fa-chart-pie" style="margin-right: 0.5rem;"></i>
                    <?php echo __('dashboard'); ?>
                </h1>
                <div class="user-menu">
                    <div class="notifications" id="notificationsBtn" onclick="toggleNotifications()">
                        <i class="fas fa-bell"></i>
                        <span class="notifications-badge" id="notificationsBadge">0</span>
                    </div>
                    <a href="/profile" class="user-avatar" title="<?php echo $_SESSION['user_name']; ?>">
                        <?php echo strtoupper(substr($_SESSION['user_name'], 0, 1)); ?>
                    </a>
                </div>
            </div>

            <div id="notificationsDropdown" class="notifications-dropdown" style="display: none;">
                <div class="notifications-header">
                    <h3><?php echo __('notifications'); ?></h3>
                    <button onclick="markAllNotificationsRead()" class="mark-all-read"><?php echo __('mark_all_read'); ?></button>
                </div>
                <div id="notificationsList" class="notifications-list">
                    <div class="notification-loading"><?php echo __('loading'); ?></div>
                </div>
            </div>