<?php
$activePage = 'statistics';
$totalBooks = $totalBooks ?? 0;
$activeLoans = $activeLoans ?? 0;
$totalUsers = $totalUsers ?? 0;
$totalReservations = $totalReservations ?? 0;
?>

<div class="statistics-container">
    <!-- Hero Section -->
    <div class="statistics-hero">
        <div class="hero-content">
            <div class="hero-icon pulse">
                <i class="fas fa-chart-line"></i>
            </div>
            <div class="hero-text">
                <h1><?php echo __('analytics_dashboard'); ?></h1>
                <p><?php echo __('real_time_performance'); ?></p>
            </div>
        </div>
        <div class="date-range">
            <div class="date-badge">
                <i class="fas fa-calendar-alt"></i>
                <span><?php echo __('year'); ?> <?php echo date('Y'); ?></span>
            </div>
        </div>
    </div>

    <!-- Cartes KPI -->
    <div class="kpi-grid">
        <div class="kpi-card">
            <div class="kpi-icon books">
                <i class="fas fa-book-open"></i>
            </div>
            <div class="kpi-info">
                <span class="kpi-label"><?php echo __('total_collection'); ?></span>
                <strong class="kpi-value"><?php echo $totalBooks ?? 0; ?></strong>
                <span class="kpi-trend up">
                    <i class="fas fa-arrow-up"></i> +12%
                </span>
            </div>
            <div class="kpi-bg">📚</div>
        </div>
        
        <div class="kpi-card">
            <div class="kpi-icon users">
                <i class="fas fa-user-group"></i>
            </div>
            <div class="kpi-info">
                <span class="kpi-label"><?php echo __('active_readers'); ?></span>
                <strong class="kpi-value"><?php echo $activeUsers ?? 0; ?></strong>
                <span class="kpi-trend up">
                    <i class="fas fa-arrow-up"></i> +8%
                </span>
            </div>
            <div class="kpi-bg">👥</div>
        </div>
        
        <div class="kpi-card">
            <div class="kpi-icon loans">
                <i class="fas fa-book-reader"></i>
            </div>
            <div class="kpi-info">
                <span class="kpi-label"><?php echo __('current_loans'); ?></span>
                <strong class="kpi-value"><?php echo $activeLoans ?? 0; ?></strong>
                <span class="kpi-trend">
                    <i class="fas fa-minus"></i> <?php echo __('stable'); ?>
                </span>
            </div>
            <div class="kpi-bg">📖</div>
        </div>
        
        <div class="kpi-card">
            <div class="kpi-icon returns">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="kpi-info">
                <span class="kpi-label"><?php echo __('returns_made'); ?></span>
                <strong class="kpi-value"><?php echo $returnedLoans ?? 0; ?></strong>
                <span class="kpi-trend up">
                    <i class="fas fa-arrow-up"></i> +23%
                </span>
            </div>
            <div class="kpi-bg">✅</div>
        </div>
    </div>

    <!-- Graphiques -->
    <div class="charts-section">
        <div class="chart-container">
            <div class="chart-header-modern">
                <div class="chart-title">
                    <i class="fas fa-chart-line"></i>
                    <h3><?php echo __('loan_trends'); ?></h3>
                </div>
                <div class="chart-subtitle"><?php echo __('monthly_evolution'); ?></div>
            </div>
            <div class="chart-wrapper">
                <canvas id="monthlyChart"></canvas>
            </div>
        </div>

        <div class="chart-container">
            <div class="chart-header-modern">
                <div class="chart-title">
                    <i class="fas fa-chart-pie"></i>
                    <h3><?php echo __('category_distribution'); ?></h3>
                </div>
                <div class="chart-subtitle"><?php echo __('books_classification'); ?></div>
            </div>
            <div class="chart-wrapper">
                <canvas id="categoryChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Top Lists -->
    <div class="top-lists-section">
        <div class="top-list-card">
            <div class="top-list-header">
                <div class="header-icon">
                    <i class="fas fa-trophy"></i>
                </div>
                <div>
                    <h3><?php echo __('top_5_books'); ?></h3>
                    <p><?php echo __('most_popular_books'); ?></p>
                </div>
            </div>
            <div class="top-list-items">
                <?php if (empty($topBooks)): ?>
                    <div class="empty-list"><?php echo __('no_data_available'); ?></div>
                <?php else: ?>
                    <?php foreach ($topBooks as $index => $book): ?>
                    <div class="list-item">
                        <div class="item-rank rank-<?php echo $index + 1; ?>">
                            <?php echo $index + 1; ?>
                        </div>
                        <div class="item-cover" style="background-image: url('<?php echo $book['cover_image']; ?>')"></div>
                        <div class="item-info">
                            <strong><?php echo htmlspecialchars($book['title']); ?></strong>
                            <span><?php echo htmlspecialchars($book['author']); ?></span>
                        </div>
                        <div class="item-stats">
                            <i class="fas fa-book-reader"></i>
                            <span><?php echo $book['loan_count']; ?> <?php echo __('loans'); ?></span>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>

        <div class="top-list-card">
            <div class="top-list-header">
                <div class="header-icon">
                    <i class="fas fa-star"></i>
                </div>
                <div>
                    <h3><?php echo __('top_5_readers'); ?></h3>
                    <p><?php echo __('most_active_members'); ?></p>
                </div>
            </div>
            <div class="top-list-items">
                <?php if (empty($topUsers)): ?>
                    <div class="empty-list"><?php echo __('no_data_available'); ?></div>
                <?php else: ?>
                    <?php foreach ($topUsers as $index => $user): ?>
                    <div class="list-item">
                        <div class="item-rank rank-<?php echo $index + 1; ?>">
                            <?php echo $index + 1; ?>
                        </div>
                        <div class="item-avatar">
                            <?php echo strtoupper(substr($user['first_name'], 0, 1) . substr($user['last_name'], 0, 1)); ?>
                        </div>
                        <div class="item-info">
                            <strong><?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?></strong>
                            <span><?php echo htmlspecialchars($user['email']); ?></span>
                        </div>
                        <div class="item-stats">
                            <i class="fas fa-book"></i>
                            <span><?php echo $user['loan_count']; ?> <?php echo __('books'); ?></span>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- État des emprunts -->
    <div class="loans-status-section">
        <div class="status-card">
            <div class="status-header">
                <i class="fas fa-chart-simple"></i>
                <h3><?php echo __('loans_status'); ?></h3>
            </div>
            <div class="status-bars">
                <div class="status-item">
                    <div class="status-label">
                        <span><i class="fas fa-hourglass-half"></i> <?php echo __('in_progress'); ?></span>
                        <strong><?php echo $activeLoans ?? 0; ?></strong>
                    </div>
                    <div class="progress-bar">
                        <div class="progress-fill active" style="width: <?php echo $totalLoans > 0 ? ($activeLoans / $totalLoans) * 100 : 0; ?>%"></div>
                    </div>
                </div>
                <div class="status-item">
                    <div class="status-label">
                        <span><i class="fas fa-check-circle"></i> <?php echo __('returned'); ?></span>
                        <strong><?php echo $returnedLoans ?? 0; ?></strong>
                    </div>
                    <div class="progress-bar">
                        <div class="progress-fill returned" style="width: <?php echo $totalLoans > 0 ? ($returnedLoans / $totalLoans) * 100 : 0; ?>%"></div>
                    </div>
                </div>
                <div class="status-item">
                    <div class="status-label">
                        <span><i class="fas fa-exclamation-triangle"></i> <?php echo __('late'); ?></span>
                        <strong><?php echo $overdueLoans ?? 0; ?></strong>
                    </div>
                    <div class="progress-bar">
                        <div class="progress-fill overdue" style="width: <?php echo $totalLoans > 0 ? ($overdueLoans / $totalLoans) * 100 : 0; ?>%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.statistics-container {
    max-width: 1400px;
    margin: 0 auto;
    padding: 0 1rem;
}

/* Hero Section */
.statistics-hero {
    background: linear-gradient(135deg, rgba(99, 102, 241, 0.2), rgba(139, 92, 246, 0.1));
    border-radius: 32px;
    padding: 2rem;
    margin-bottom: 2rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 1rem;
    border: 1px solid var(--border-color);
    backdrop-filter: blur(10px);
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

.pulse {
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.05); }
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

.date-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 1.25rem;
    background: var(--card-bg);
    border: 1px solid var(--border-color);
    border-radius: 30px;
    font-size: 0.85rem;
    font-weight: 500;
}

/* KPI Cards */
.kpi-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.kpi-card {
    position: relative;
    background: var(--card-bg);
    border: 1px solid var(--border-color);
    border-radius: 28px;
    padding: 1.5rem;
    display: flex;
    align-items: center;
    gap: 1rem;
    transition: all 0.3s ease;
    overflow: hidden;
}

.kpi-card:hover {
    transform: translateY(-5px);
    border-color: var(--primary);
    box-shadow: 0 20px 35px -10px rgba(0, 0, 0, 0.3);
}

.kpi-icon {
    width: 60px;
    height: 60px;
    border-radius: 18px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.6rem;
    z-index: 1;
}

.kpi-icon.books { background: linear-gradient(135deg, #3b82f6, #1d4ed8); }
.kpi-icon.users { background: linear-gradient(135deg, #10b981, #059669); }
.kpi-icon.loans { background: linear-gradient(135deg, #f59e0b, #d97706); }
.kpi-icon.returns { background: linear-gradient(135deg, #8b5cf6, #7c3aed); }

.kpi-info {
    flex: 1;
    z-index: 1;
}

.kpi-label {
    font-size: 0.75rem;
    color: var(--text-secondary);
    text-transform: uppercase;
    letter-spacing: 1px;
}

.kpi-value {
    display: block;
    font-size: 2.2rem;
    font-weight: 800;
    line-height: 1.2;
    margin: 0.25rem 0;
}

.kpi-trend {
    font-size: 0.7rem;
    display: inline-flex;
    align-items: center;
    gap: 0.25rem;
    padding: 0.2rem 0.5rem;
    border-radius: 20px;
}

.kpi-trend.up { color: #10b981; background: rgba(16, 185, 129, 0.1); }
.kpi-trend.down { color: #ef4444; background: rgba(239, 68, 68, 0.1); }

.kpi-bg {
    position: absolute;
    right: -20px;
    bottom: -20px;
    font-size: 8rem;
    opacity: 0.05;
    pointer-events: none;
}

/* Charts */
.charts-section {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(500px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.chart-container {
    background: var(--card-bg);
    border: 1px solid var(--border-color);
    border-radius: 28px;
    overflow: hidden;
}

.chart-container:hover {
    border-color: var(--primary);
}

.chart-header-modern {
    padding: 1.5rem;
    border-bottom: 1px solid var(--border-color);
    background: rgba(99, 102, 241, 0.05);
}

.chart-title {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin-bottom: 0.25rem;
}

.chart-title i {
    font-size: 1.2rem;
    color: var(--primary);
}

.chart-title h3 {
    font-size: 1rem;
    font-weight: 600;
}

.chart-subtitle {
    font-size: 0.7rem;
    color: var(--text-secondary);
    margin-left: 1.7rem;
}

.chart-wrapper {
    padding: 1.5rem;
    height: 320px;
    position: relative;
}

/* Top Lists */
.top-lists-section {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(450px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.top-list-card {
    background: var(--card-bg);
    border: 1px solid var(--border-color);
    border-radius: 28px;
    overflow: hidden;
}

.top-list-card:hover {
    border-color: var(--primary);
}

.top-list-header {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1.5rem;
    border-bottom: 1px solid var(--border-color);
    background: rgba(99, 102, 241, 0.05);
}

.header-icon {
    width: 45px;
    height: 45px;
    background: linear-gradient(135deg, #fbbf24, #f59e0b);
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.3rem;
}

.top-list-header h3 {
    font-size: 1rem;
    font-weight: 600;
    margin-bottom: 0.25rem;
}

.top-list-header p {
    font-size: 0.7rem;
    color: var(--text-secondary);
}

.top-list-items {
    padding: 0.5rem;
}

.list-item {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 0.75rem;
    border-bottom: 1px solid var(--border-color);
    transition: all 0.3s ease;
}

.list-item:hover {
    background: rgba(99, 102, 241, 0.05);
    transform: translateX(5px);
}

.item-rank {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 0.9rem;
}

.rank-1 { background: linear-gradient(135deg, #fbbf24, #f59e0b); color: white; }
.rank-2 { background: linear-gradient(135deg, #94a3b8, #64748b); color: white; }
.rank-3 { background: linear-gradient(135deg, #cd7b4b, #b45309); color: white; }
.rank-4, .rank-5 { background: rgba(99, 102, 241, 0.2); color: var(--primary); }

.item-cover {
    width: 40px;
    height: 55px;
    background-size: cover;
    background-position: center;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.2);
}

.item-avatar {
    width: 40px;
    height: 40px;
    background: var(--gradient-1);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    color: white;
}

.item-info {
    flex: 1;
}

.item-info strong {
    display: block;
    font-size: 0.85rem;
    margin-bottom: 0.25rem;
}

.item-info span {
    font-size: 0.7rem;
    color: var(--text-secondary);
}

.item-stats {
    display: flex;
    align-items: center;
    gap: 0.35rem;
    font-size: 0.8rem;
    font-weight: 600;
    color: var(--primary);
    background: rgba(99, 102, 241, 0.1);
    padding: 0.25rem 0.75rem;
    border-radius: 30px;
}

/* Loans Status */
.loans-status-section {
    margin-bottom: 2rem;
}

.status-card {
    background: var(--card-bg);
    border: 1px solid var(--border-color);
    border-radius: 28px;
    padding: 1.5rem;
}

.status-header {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin-bottom: 1.5rem;
}

.status-header i {
    font-size: 1.2rem;
    color: var(--primary);
}

.status-header h3 {
    font-size: 1rem;
    font-weight: 600;
}

.status-bars {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.status-item {
    width: 100%;
}

.status-label {
    display: flex;
    justify-content: space-between;
    margin-bottom: 0.5rem;
    font-size: 0.85rem;
}

.status-label span i {
    margin-right: 0.5rem;
    font-size: 0.8rem;
}

.progress-bar {
    height: 8px;
    background: var(--border-color);
    border-radius: 4px;
    overflow: hidden;
}

.progress-fill {
    height: 100%;
    border-radius: 4px;
    transition: width 1s ease;
}

.progress-fill.active { background: linear-gradient(90deg, #3b82f6, #6366f1); }
.progress-fill.returned { background: linear-gradient(90deg, #10b981, #059669); }
.progress-fill.overdue { background: linear-gradient(90deg, #ef4444, #dc2626); }

.empty-list {
    text-align: center;
    padding: 2rem;
    color: var(--text-secondary);
}

/* Responsive */
@media (max-width: 1024px) {
    .charts-section {
        grid-template-columns: 1fr;
    }
    
    .top-lists-section {
        grid-template-columns: 1fr;
    }
    
    .chart-wrapper {
        height: 280px;
    }
}

@media (max-width: 768px) {
    .kpi-grid {
        grid-template-columns: 1fr;
    }
    
    .hero-content {
        flex-direction: column;
        text-align: center;
    }
    
    .hero-text h1 {
        font-size: 1.5rem;
    }
    
    .list-item {
        flex-wrap: wrap;
    }
    
    .item-stats {
        margin-left: 48px;
    }
}
</style>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Attendre que le DOM soit complètement chargé
document.addEventListener('DOMContentLoaded', function() {
    // Graphique mensuel
    const monthlyCtx = document.getElementById('monthlyChart');
    if (monthlyCtx) {
        const monthlyData = <?php echo json_encode(array_values($monthlyStats)); ?>;
        const monthsLabels = ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin', 'Juil', 'Aoû', 'Sep', 'Oct', 'Nov', 'Déc'];
        
        new Chart(monthlyCtx.getContext('2d'), {
            type: 'line',
            data: {
                labels: monthsLabels,
                datasets: [{
                    label: 'Nombre d\'emprunts',
                    data: monthlyData,
                    borderColor: '#6366f1',
                    backgroundColor: 'rgba(99, 102, 241, 0.1)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#6366f1',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0,0,0,0.8)',
                        titleColor: '#fff',
                        bodyColor: '#94a3b8'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: 'rgba(255,255,255,0.05)' },
                        ticks: { color: '#94a3b8' }
                    },
                    x: {
                        grid: { display: false },
                        ticks: { color: '#94a3b8' }
                    }
                }
            }
        });
    }

    // Graphique par catégorie
    const categoryCtx = document.getElementById('categoryChart');
    if (categoryCtx) {
        const categoryLabels = <?php echo json_encode(array_column($categoryStats, 'category')); ?>;
        const categoryData = <?php echo json_encode(array_column($categoryStats, 'count')); ?>;
        
        new Chart(categoryCtx.getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: categoryLabels,
                datasets: [{
                    data: categoryData,
                    backgroundColor: ['#6366f1', '#8b5cf6', '#ec4899', '#10b981', '#f59e0b', '#ef4444', '#3b82f6'],
                    borderWidth: 0,
                    hoverOffset: 10
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'right',
                        labels: { 
                            color: '#94a3b8',
                            font: { size: 11 },
                            boxWidth: 10,
                            padding: 10
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0,0,0,0.8)',
                        titleColor: '#fff',
                        bodyColor: '#94a3b8'
                    }
                },
                cutout: '60%'
            }
        });
    }
});
</script>