<?php $this->view("head"); ?>

<style>
    .actions-container {
        background: white;
        border-radius: 12px;
        padding: 20px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        margin-bottom: 20px;
    }
    
    .user-header {
        display: flex;
        align-items: center;
        gap: 16px;
        margin-bottom: 24px;
        padding-bottom: 16px;
        border-bottom: 1px solid #e2e8f0;
    }
    
    .user-avatar {
        width: 64px;
        height: 64px;
        border-radius: 50%;
        background: #3b82f6;
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        font-size: 20px;
        overflow: hidden;
    }
    
    .user-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .user-info h2 {
        font-size: 1.5rem;
        font-weight: 600;
        color: #374151;
        margin-bottom: 4px;
    }
    
    .user-details {
        color: #6b7280;
        font-size: 14px;
    }
    
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 16px;
        margin-bottom: 24px;
    }
    
    .stat-card {
        background: #f8fafc;
        border-radius: 8px;
        padding: 16px;
        text-align: center;
    }
    
    .stat-number {
        font-size: 1.5rem;
        font-weight: bold;
        color: #3b82f6;
        margin-bottom: 4px;
    }
    
    .stat-label {
        color: #64748b;
        font-size: 0.875rem;
    }
    
    .actions-list {
        margin-top: 24px;
    }
    
    .action-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 12px;
        border-bottom: 1px solid #e2e8f0;
    }
    
    .action-details {
        flex: 1;
    }
    
    .action-document {
        font-weight: 600;
        color: #374151;
        margin-bottom: 4px;
    }
    
    .action-type {
        font-size: 14px;
        color: #6b7280;
        display: flex;
        align-items: center;
        gap: 6px;
    }
    
    .action-type-badge {
        padding: 2px 8px;
        border-radius: 12px;
        font-size: 12px;
        font-weight: 500;
    }
    
    .badge-open {
        background: #10b981;
        color: white;
    }
    
    .badge-download {
        background: #3b82f6;
        color: white;
    }
    
    .badge-modify {
        background: #f59e0b;
        color: white;
    }
    
    .badge-delete {
        background: #ef4444;
        color: white;
    }
    
    .action-time {
        color: #9ca3af;
        font-size: 12px;
        text-align: right;
        min-width: 120px;
    }
    
    .empty-state {
        text-align: center;
        padding: 40px 20px;
        color: #6b7280;
    }
</style>

<main>
    <div class="p-4 mx-auto max-w-4xl md:p-6">
        <!-- En-tête -->
        <div class="mb-6">
            <a href="<?= ROOT ?>/document/dossier_stats/<?= $dossier->id ?>" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-gray-700 transition rounded-lg bg-gray-100 hover:bg-gray-200 mb-4">
                ← Retour aux statistiques
            </a>
            
            <div class="user-header">
                <div class="user-avatar">
                    <?php if (!empty($employe->photo)): ?>
                        <img src="<?= ROOT ?>/uploads/profiles/<?= $employe->photo ?>" alt="<?= esc($employe->prenom) ?>">
                    <?php else: ?>
                        <?= strtoupper(substr($employe->prenom, 0, 1) . substr($employe->nom, 0, 1)) ?>
                    <?php endif; ?>
                </div>
                
                <div class="user-info">
                    <h2><?= esc($employe->prenom . ' ' . $employe->nom) ?></h2>
                    <div class="user-details">
                        <?= esc($employe->email) ?> • Dossier: <?= esc($dossier->nom) ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistiques -->
        <?php if ($stats): ?>
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-number"><?= $stats->total_actions ?></div>
                    <div class="stat-label">Actions totales</div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-number"><?= $stats->documents_différents ?></div>
                    <div class="stat-label">Documents différents</div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-number"><?= $stats->ouvertures ?></div>
                    <div class="stat-label">Ouvertures</div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-number"><?= $stats->telechargements ?></div>
                    <div class="stat-label">Téléchargements</div>
                </div>
            </div>
            
            <div class="text-sm text-gray-600 mb-6">
                <?php if ($stats->premiere_action): ?>
                    <span class="mr-4">📅 Première action: <?= date('d/m/Y H:i', strtotime($stats->premiere_action)) ?></span>
                <?php endif; ?>
                
                <?php if ($stats->derniere_action): ?>
                    <span>🕒 Dernière action: <?= date('d/m/Y H:i', strtotime($stats->derniere_action)) ?></span>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <!-- Liste des actions -->
        <div class="actions-container">
            <h3 class="text-lg font-semibold mb-4">📋 Historique des actions</h3>
            
            <?php if (!empty($actions)): ?>
                <div class="actions-list">
                    <?php foreach ($actions as $action): ?>
                        <div class="action-item">
                            <div class="action-details">
                                <div class="action-document">
                                    <?= esc($action->document_nom) ?>
                                </div>
                                <div class="action-type">
                                    <?php
                                    $badge_class = '';
                                    $action_text = '';
                                    
                                    switch ($action->action) {
                                        case 'OUVERTURE':
                                            $badge_class = 'badge-open';
                                            $action_text = 'Ouverture';
                                            break;
                                        case 'TELECHARGEMENT':
                                            $badge_class = 'badge-download';
                                            $action_text = 'Téléchargement';
                                            break;
                                        case 'MODIFICATION':
                                            $badge_class = 'badge-modify';
                                            $action_text = 'Modification';
                                            break;
                                        case 'SUPPRESSION':
                                            $badge_class = 'badge-delete';
                                            $action_text = 'Suppression';
                                            break;
                                        default:
                                            $badge_class = 'badge-open';
                                            $action_text = $action->action;
                                    }
                                    ?>
                                    
                                    <span class="action-type-badge <?= $badge_class ?>">
                                        <?= $action_text ?>
                                    </span>
                                    
                                    <?php if ($action->document_type): ?>
                                        <span>• Type: <?= $action->document_type ?></span>
                                    <?php endif; ?>
                                    
                                    <?php if ($action->document_taille): ?>
                                        <span>• Taille: <?= formatFileSize($action->document_taille) ?></span>
                                    <?php endif; ?>
                                </div>
                            </div>
                            
                            <div class="action-time">
                                <?= date('d/m/Y H:i', strtotime($action->date_action)) ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="empty-state">
                    <p>Aucune action enregistrée pour cet utilisateur sur ce dossier.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</main>

<?php $this->view("footer"); ?>