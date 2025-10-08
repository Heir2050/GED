<?php $this->view("head"); ?>

<style>
    .dossier-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
        gap: 20px;
        margin-top: 20px;
    }
    
    .dossier-card {
        background: white;
        border-radius: 12px;
        padding: 20px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        border: 1px solid #e5e7eb;
        opacity: 0.8;
        transition: opacity 0.3s ease;
    }
    
    .dossier-card:hover {
        opacity: 1;
    }
    
    .dossier-icon {
        width: 50px;
        height: 50px;
        color: #6b7280; /* Gris pour les archives */
        margin-bottom: 15px;
    }
    
    .dossier-name {
        font-size: 18px;
        font-weight: 600;
        color: #374151;
        margin-bottom: 8px;
    }
    
    .dossier-info {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 15px;
    }
    
    .archive-badge {
        background: #6b7280;
        color: white;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 500;
    }
    
    .dossier-date {
        font-size: 12px;
        color: #6b7280;
    }
    
    .empty-state {
        text-align: center;
        padding: 40px 20px;
        color: #6b7280;
    }
    
    .back-button {
        display: inline-flex;
        align-items: center;
        margin-bottom: 20px;
        color: #3b82f6;
        text-decoration: none;
        font-weight: 500;
    }
    
    .back-button:hover {
        text-decoration: underline;
    }
</style>

<main>
    <?php if (!empty(message())) : ?>
        <div class="rounded-xl border border-success-500 mt-6 bg-success-50 p-4 dark:border-success-500/30 dark:bg-success-500/15" style="margin-top: 20px; position:absolute; left:50%; transform: translate(-50%, 0);">
            <div class="flex items-start gap-3">
                <div class="-mt-0.5 text-success-500">
                    <svg class="fill-current" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M3.70186 12.0001C3.70186 7.41711 7.41711 3.70186 12.0001 3.70186C16.5831 3.70186 20.2984 7.41711 20.2984 12.0001C20.2984 16.5831 16.5831 20.2984 12.0001 20.2984C7.41711 20.2984 3.70186 16.5831 3.70186 12.0001ZM12.0001 1.90186C6.423 1.90186 1.90186 6.423 1.90186 12.0001C1.90186 17.5772 6.423 22.0984 12.0001 22.0984C17.5772 22.0984 22.0984 17.5772 22.0984 12.0001C22.0984 6.423 17.5772 1.90186 12.0001 1.90186ZM15.6197 10.7395C15.9712 10.388 15.9712 9.81819 15.6197 9.46672C15.2683 9.11525 14.6984 9.11525 14.347 9.46672L11.1894 12.6243L9.6533 11.0883C9.30183 10.7368 8.73198 10.7368 8.38051 11.0883C8.02904 11.4397 8.02904 12.0096 8.38051 12.3611L10.553 14.5335C10.7217 14.7023 10.9507 14.7971 11.1894 14.7971C11.428 14.7971 11.657 14.7023 11.8257 14.5335L15.6197 10.7395Z" fill=""></path>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        <?= message('', true) ?>
                    </p>
                </div>
            </div>
        </div>
    <?php endif; ?>
    
    <div class="p-4 mx-auto max-w-(--breakpoint-2xl) md:p-6">
        <!-- Breadcrumb -->
        <div x-data="{ pageName: `Archives` }" class="mb-6">
            <div class="mb-6 flex flex-wrap items-center justify-between gap-3">
                <h2 class="text-xl font-semibold text-gray-800 dark:text-white/90" x-text="pageName">Archives</h2>
                <nav>
                    <a href="<?= ROOT ?>/document" class="inline-flex items-center gap-2 px-4 py-3 text-sm font-medium text-gray-700 transition rounded-lg bg-gray-100 hover:bg-gray-200">
                        Retour aux documents
                    </a>
                </nav>
            </div>
        </div>

        <?php if (!empty($dossiers_archives)): ?>
            <div class="dossier-grid">
                <?php foreach ($dossiers_archives as $dossier): ?>
                    <div class="dossier-card">
                        <div class="dossier-icon">
                            <svg viewBox="0 0 24 24" fill="currentColor">
                                <path d="M20 6h-8l-2-2H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V8c0-1.1-.9-2-2-2zm0 12H4V6h5.17l2 2H20v10z"/>
                            </svg>
                        </div>
                        <div class="dossier-name"><?= esc($dossier->nom) ?></div>
                        <div class="dossier-info">
                            <div class="archive-badge">
                                Archivé
                            </div>
                            <div class="dossier-date">
                                Archivé le: <?= date('d/m/Y', strtotime($dossier->date_archivage)) ?>
                            </div>
                        </div>
                        <div class="mt-3 text-xs text-gray-500">
                            Créé le: <?= date('d/m/Y', strtotime($dossier->date_creation)) ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="empty-state">
                <svg class="mx-auto h-16 w-16 text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                </svg>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Aucun dossier archivé</h3>
                <p class="text-gray-500">Les dossiers archivés apparaîtront ici.</p>
            </div>
        <?php endif; ?>
    </div>
</main>

<?php $this->view("footer"); ?>