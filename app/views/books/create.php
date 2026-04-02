<?php
$activePage = 'books';
?>

<div class="create-book-container">
    <div class="create-header">
        <div class="header-left">
            <a href="/books" class="back-link">
                <i class="fas fa-arrow-left"></i> <?php echo __('back_to_catalogue'); ?>
            </a>
            <h1>
                <span class="title-icon">
                    <i class="fas fa-plus-circle"></i>
                </span>
                <?php echo __('add_new_book'); ?>
            </h1>
            <p class="subtitle"><?php echo __('add_book_subtitle'); ?></p>
        </div>
        <div class="book-status">
            <div class="status-card">
                <i class="fas fa-gem"></i>
                <div class="status-info">
                    <span><?php echo __('new_book'); ?></span>
                    <strong><?php echo __('ready_to_add'); ?></strong>
                </div>
            </div>
            <div class="status-card">
                <i class="fas fa-chart-line"></i>
                <div class="status-info">
                    <span><?php echo __('status'); ?></span>
                    <strong><?php echo __('available'); ?></strong>
                </div>
            </div>
        </div>
    </div>

    <div class="create-form-wrapper">
        <form id="createBookForm" class="modern-form">
            <div class="form-grid">
                <!-- Colonne gauche -->
                <div class="form-left">
                    <div class="form-section">
                        <div class="section-title">
                            <i class="fas fa-info-circle"></i>
                            <h3><?php echo __('main_info'); ?></h3>
                        </div>
                        
                        <div class="input-group floating-label">
                            <input type="text" id="title" name="title" placeholder=" " required>
                            <label for="title"><?php echo __('book_title'); ?> *</label>
                            <i class="fas fa-book input-icon"></i>
                        </div>

                        <div class="input-group floating-label">
                            <input type="text" id="author" name="author" placeholder=" " required>
                            <label for="author"><?php echo __('author'); ?> *</label>
                            <i class="fas fa-user-pen input-icon"></i>
                        </div>

                        <div class="input-group floating-label">
                            <input type="text" id="isbn" name="isbn" placeholder=" ">
                            <label for="isbn"><?php echo __('isbn'); ?></label>
                            <i class="fas fa-barcode input-icon"></i>
                        </div>
                    </div>

                    <div class="form-section">
                        <div class="section-title">
                            <i class="fas fa-chart-line"></i>
                            <h3><?php echo __('stock_availability'); ?></h3>
                        </div>
                        
                        <div class="stock-stats-preview">
                            <div class="preview-card">
                                <i class="fas fa-boxes"></i>
                                <span><?php echo __('quantity_to_add'); ?></span>
                                <strong id="quantityPreview">1</strong>
                            </div>
                            <div class="preview-card success">
                                <i class="fas fa-check-circle"></i>
                                <span><?php echo __('availability'); ?></span>
                                <strong><?php echo __('immediate'); ?></strong>
                            </div>
                        </div>

                        <div class="input-group floating-label">
                            <input type="number" id="quantity" name="quantity" value="1" min="1" step="1">
                            <label for="quantity"><?php echo __('total_quantity'); ?></label>
                            <i class="fas fa-layer-group input-icon"></i>
                        </div>

                        <div class="input-group floating-label">
                            <select id="category" name="category">
                                <option value=""><?php echo __('select_category'); ?></option>
                                <option value="Informatique">💻 <?php echo __('computer_science'); ?></option>
                                <option value="Sciences">🔬 <?php echo __('sciences'); ?></option>
                                <option value="Histoire">📜 <?php echo __('history'); ?></option>
                                <option value="Littérature">📖 <?php echo __('literature'); ?></option>
                                <option value="Art">🎨 <?php echo __('art'); ?></option>
                                <option value="Philosophie">🧠 <?php echo __('philosophy'); ?></option>
                                <option value="Psychologie">🧘 <?php echo __('psychology'); ?></option>
                                <option value="Économie">📊 <?php echo __('economy'); ?></option>
                            </select>
                            <label for="category"><?php echo __('category'); ?></label>
                            <i class="fas fa-tag input-icon"></i>
                        </div>
                    </div>
                </div>

                <!-- Colonne droite -->
                <div class="form-right">
                    <div class="form-section">
                        <div class="section-title">
                            <i class="fas fa-image"></i>
                            <h3><?php echo __('book_cover'); ?></h3>
                        </div>
                        
                        <div class="cover-upload">
                            <div class="cover-preview" id="coverPreview">
                                <div class="placeholder">
                                    <i class="fas fa-cloud-upload-alt"></i>
                                    <span><?php echo __('add_image'); ?></span>
                                </div>
                            </div>
                            <div class="input-group floating-label">
                                <input type="url" id="cover_image" name="cover_image" placeholder=" ">
                                <label for="cover_image"><?php echo __('image_url'); ?></label>
                                <i class="fas fa-link input-icon"></i>
                            </div>
                            <p class="help-text">
                                <i class="fas fa-info-circle"></i> 
                                <?php echo __('image_help'); ?>
                            </p>
                        </div>
                    </div>

                    <div class="form-section">
                        <div class="section-title">
                            <i class="fas fa-align-left"></i>
                            <h3><?php echo __('description'); ?></h3>
                        </div>
                        
                        <div class="textarea-group">
                            <textarea id="description" name="description" rows="6" placeholder="<?php echo __('description_placeholder'); ?>"></textarea>
                            <div class="char-counter">
                                <span id="charCount">0</span> <?php echo __('characters'); ?>
                            </div>
                        </div>
                        <div class="description-tips">
                            <i class="fas fa-lightbulb"></i>
                            <span><?php echo __('description_tip'); ?></span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-actions">
                <a href="/books" class="btn-cancel">
                    <i class="fas fa-times"></i> <?php echo __('cancel'); ?>
                </a>
                <button type="submit" class="btn-save">
                    <i class="fas fa-save"></i> <?php echo __('add_book'); ?>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Garde tous tes styles CSS inchangés -->
<style>
/* Tous tes styles CSS existants restent inchangés */
.create-book-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 1rem;
    animation: fadeInUp 0.5s ease;
}

.create-header {
    margin-bottom: 2rem;
}

.header-left {
    margin-bottom: 1.5rem;
}

.back-link {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    color: var(--primary);
    text-decoration: none;
    font-size: 0.9rem;
    margin-bottom: 1rem;
    transition: all 0.3s ease;
}

.back-link:hover {
    transform: translateX(-5px);
}

.create-header h1 {
    font-size: 2rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.title-icon {
    width: 48px;
    height: 48px;
    background: linear-gradient(135deg, #10b981, #059669);
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.4rem;
    box-shadow: 0 4px 15px rgba(16, 185, 129, 0.3);
}

.subtitle {
    color: rgba(255, 255, 255, 0.6);
    font-size: 0.9rem;
}

.book-status {
    display: flex;
    gap: 1rem;
    flex-wrap: wrap;
}

.status-card {
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid var(--glass-border);
    border-radius: 12px;
    padding: 0.75rem 1.25rem;
    display: flex;
    align-items: center;
    gap: 0.75rem;
    transition: all 0.3s ease;
}

.status-card:hover {
    border-color: var(--primary);
    transform: translateY(-2px);
}

.status-card i {
    font-size: 1.25rem;
    color: var(--primary);
}

.status-info {
    display: flex;
    flex-direction: column;
}

.status-info span {
    font-size: 0.7rem;
    opacity: 0.6;
}

.status-info strong {
    font-size: 0.9rem;
}

.create-form-wrapper {
    background: rgba(255, 255, 255, 0.02);
    border: 1px solid var(--glass-border);
    border-radius: 32px;
    padding: 2rem;
    backdrop-filter: blur(10px);
}

.form-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 2rem;
}

.form-section {
    background: rgba(255, 255, 255, 0.03);
    border-radius: 24px;
    padding: 1.5rem;
    margin-bottom: 1.5rem;
    border: 1px solid rgba(255, 255, 255, 0.05);
    transition: all 0.3s ease;
}

.form-section:hover {
    border-color: rgba(99, 102, 241, 0.3);
}

.section-title {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    margin-bottom: 1.5rem;
    padding-bottom: 0.75rem;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
}

.section-title i {
    font-size: 1.25rem;
    color: var(--primary);
}

.section-title h3 {
    font-size: 1.1rem;
    font-weight: 600;
}

.input-group {
    position: relative;
    margin-bottom: 1.5rem;
}

.input-group.floating-label input,
.input-group.floating-label select {
    width: 100%;
    padding: 1rem 1rem 1rem 2.8rem;
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid var(--glass-border);
    border-radius: 12px;
    color: var(--light);
    font-size: 0.95rem;
    transition: all 0.3s ease;
}

.input-group.floating-label select {
    appearance: none;
    cursor: pointer;
}

.input-group.floating-label input:focus,
.input-group.floating-label select:focus {
    outline: none;
    border-color: var(--primary);
    background: rgba(255, 255, 255, 0.1);
}

.input-group.floating-label label {
    position: absolute;
    left: 2.8rem;
    top: 50%;
    transform: translateY(-50%);
    background: transparent;
    padding: 0 0.25rem;
    color: rgba(255, 255, 255, 0.5);
    transition: all 0.3s ease;
    pointer-events: none;
    font-size: 0.95rem;
}

.input-group.floating-label input:focus ~ label,
.input-group.floating-label input:not(:placeholder-shown) ~ label,
.input-group.floating-label select:focus ~ label,
.input-group.floating-label select:not([value=""]) ~ label {
    top: 0;
    left: 2rem;
    font-size: 0.7rem;
    background: rgba(15, 23, 42, 0.9);
    padding: 0 0.5rem;
    color: var(--primary);
}

.input-icon {
    position: absolute;
    left: 1rem;
    top: 50%;
    transform: translateY(-50%);
    color: var(--primary);
    font-size: 1rem;
    pointer-events: none;
}

.stock-stats-preview {
    display: flex;
    gap: 1rem;
    margin-bottom: 1.5rem;
    flex-wrap: wrap;
}

.preview-card {
    flex: 1;
    background: rgba(255, 255, 255, 0.05);
    border-radius: 12px;
    padding: 1rem;
    text-align: center;
    transition: all 0.3s ease;
}

.preview-card i {
    font-size: 1.5rem;
    color: var(--primary);
    margin-bottom: 0.5rem;
    display: block;
}

.preview-card span {
    font-size: 0.7rem;
    opacity: 0.6;
    display: block;
    margin-bottom: 0.25rem;
}

.preview-card strong {
    font-size: 1.25rem;
    font-weight: 700;
    display: block;
}

.preview-card.success i {
    color: #10b981;
}

.cover-upload {
    text-align: center;
}

.cover-preview {
    width: 200px;
    height: 280px;
    margin: 0 auto 1rem;
    border-radius: 16px;
    overflow: hidden;
    background: rgba(255, 255, 255, 0.05);
    border: 2px dashed var(--glass-border);
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
}

.cover-preview:hover {
    border-color: var(--primary);
}

.cover-preview .placeholder {
    text-align: center;
    color: rgba(255, 255, 255, 0.5);
    padding: 1rem;
}

.cover-preview .placeholder i {
    font-size: 3rem;
    margin-bottom: 0.5rem;
    display: block;
}

.cover-preview img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.help-text {
    font-size: 0.7rem;
    color: rgba(255, 255, 255, 0.4);
    margin-top: 0.5rem;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.25rem;
}

.textarea-group {
    position: relative;
}

.textarea-group textarea {
    width: 100%;
    padding: 1rem;
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid var(--glass-border);
    border-radius: 12px;
    color: var(--light);
    font-size: 0.95rem;
    resize: vertical;
    transition: all 0.3s ease;
    font-family: inherit;
}

.textarea-group textarea:focus {
    outline: none;
    border-color: var(--primary);
    background: rgba(255, 255, 255, 0.1);
}

.char-counter {
    text-align: right;
    font-size: 0.7rem;
    color: rgba(255, 255, 255, 0.4);
    margin-top: 0.5rem;
}

.description-tips {
    margin-top: 0.75rem;
    padding: 0.5rem;
    background: rgba(99, 102, 241, 0.1);
    border-radius: 8px;
    font-size: 0.7rem;
    color: rgba(255, 255, 255, 0.5);
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.description-tips i {
    color: #f59e0b;
}

.form-actions {
    display: flex;
    gap: 1rem;
    margin-top: 2rem;
    padding-top: 1.5rem;
    border-top: 1px solid var(--glass-border);
}

.btn-cancel, .btn-save {
    flex: 1;
    padding: 1rem;
    border-radius: 14px;
    font-weight: 600;
    font-size: 1rem;
    cursor: pointer;
    transition: all 0.3s ease;
    text-align: center;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 0.75rem;
}

.btn-cancel {
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid var(--glass-border);
    color: var(--light);
}

.btn-cancel:hover {
    background: rgba(239, 68, 68, 0.2);
    border-color: #ef4444;
    transform: translateY(-2px);
}

.btn-save {
    background: linear-gradient(135deg, #10b981, #059669);
    border: none;
    color: white;
    box-shadow: 0 4px 15px rgba(16, 185, 129, 0.3);
}

.btn-save:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(16, 185, 129, 0.4);
}

@media (max-width: 768px) {
    .form-grid {
        grid-template-columns: 1fr;
        gap: 1rem;
    }
    
    .create-form-wrapper {
        padding: 1rem;
    }
    
    .form-section {
        padding: 1rem;
    }
    
    .book-status {
        flex-direction: column;
    }
    
    .stock-stats-preview {
        flex-direction: column;
    }
    
    .form-actions {
        flex-direction: column;
    }
    
    .cover-preview {
        width: 150px;
        height: 210px;
    }
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
</style>

<script>
// Mise à jour de l'aperçu de la quantité
const quantityInput = document.getElementById('quantity');
const quantityPreview = document.getElementById('quantityPreview');

quantityInput.addEventListener('input', function() {
    quantityPreview.textContent = this.value;
});

// Aperçu de l'image en temps réel
const coverInput = document.getElementById('cover_image');
const coverPreview = document.getElementById('coverPreview');

coverInput.addEventListener('input', function() {
    const url = this.value;
    if (url && url.match(/^https?:\/\/.+\..+/)) {
        coverPreview.innerHTML = `<img src="${url}" alt="Aperçu de la couverture" onerror="this.parentElement.innerHTML='<div class=\'placeholder\'><i class=\'fas fa-image\'></i><span><?php echo __('invalid_image'); ?></span></div>'">`;
    } else if (url) {
        coverPreview.innerHTML = `<div class="placeholder"><i class="fas fa-exclamation-triangle"></i><span><?php echo __('invalid_url'); ?></span></div>`;
    } else {
        coverPreview.innerHTML = `<div class="placeholder"><i class="fas fa-cloud-upload-alt"></i><span><?php echo __('add_image'); ?></span></div>`;
    }
});

// Compteur de caractères
const description = document.getElementById('description');
const charCount = document.getElementById('charCount');

description.addEventListener('input', function() {
    charCount.textContent = this.value.length;
});

// Animation au focus des inputs
document.querySelectorAll('.input-group input, .input-group select').forEach(input => {
    input.addEventListener('focus', () => {
        input.parentElement.classList.add('focused');
    });
    input.addEventListener('blur', () => {
        if (!input.value) {
            input.parentElement.classList.remove('focused');
        }
    });
});

// Soumission du formulaire
document.getElementById('createBookForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const data = Object.fromEntries(formData);
    
    if (!data.title || !data.author) {
        alert('<?php echo __('required_fields'); ?>');
        return;
    }
    
    data.available_quantity = data.quantity;
    
    const submitBtn = this.querySelector('.btn-save');
    const originalText = submitBtn.innerHTML;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> <?php echo __('adding'); ?>...';
    submitBtn.disabled = true;
    
    try {
        const response = await fetch('/books/create', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify(data)
        });
        const result = await response.json();
        
        if (result.success) {
            submitBtn.style.background = '#10b981';
            submitBtn.innerHTML = '<i class="fas fa-check"></i> <?php echo __('book_added'); ?>!';
            setTimeout(() => {
                window.location.href = '/books';
            }, 1000);
        } else {
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
            alert('❌ ' + result.message);
        }
    } catch (error) {
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
        alert('<?php echo __('connection_error'); ?>');
    }
});
</script>
