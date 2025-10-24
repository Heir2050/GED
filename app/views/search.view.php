<?php $this->view("head"); ?>

<style>
    .search-results {
        max-width: 1200px;
        margin: 0 auto;
        padding: 20px;
    }
    
    .search-header {
        margin-bottom: 30px;
    }
    
    .search-stats {
        color: #6b7280;
        font-size: 0.875rem;
        margin-bottom: 20px;
    }
    
    .results-section {
        margin-bottom: 40px;
    }
    
    .section-title {
        font-size: 1.25rem;
        font-weight: 600;
        margin-bottom: 15px;
        color: #374151;
        border-bottom: 2px solid #e5e7eb;
        padding-bottom: 8px;
    }
    
    .result-item {
        background: white;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        padding: 16px;
        margin-bottom: 12px;
        transition: all 0.2s ease;
        cursor: pointer;
    }
    
    .result-item:hover {
        border-color: #3b82f6;
        box-shadow: 0 2px 8px rgba(59, 130, 246, 0.1);
    }
    
    .result-title {
        font-weight: 600;
        color: #374151;
        margin-bottom: 8px;
    }
    
    .result-meta {
        font-size: 0.875rem;
        color: #6b7280;
        display: flex;
        gap: 15px;
        flex-wrap: wrap;
    }
    
    .result-type {
        display: inline-block;
        padding: 2px 8px;
        border-radius: 12px;
        font-size: 0.75rem;
        font-weight: 500;
        margin-right: 8px;
    }
    
    .type-documents { background: #dbeafe; color: #1e40af; }
    .type-dossiers { background: #dcfce7; color: #166534; }
    .type-archives { background: #fef3c7; color: #92400e; }
    .type-historique { background: #f3e8ff; color: #7c3aed; }
    
    .no-results {
        text-align: center;
        padding: 40px;
        color: #6b7280;
    }
    
    .search-filters {
        display: flex;
        gap: 10px;
        margin-bottom: 20px;
        flex-wrap: wrap;
    }
    
    .filter-btn {
        padding: 8px 16px;
        border: 1px solid #d1d5db;
        border-radius: 6px;
        background: white;
        color: #374151;
        cursor: pointer;
        transition: all 0.2s;
        text-decoration: none;
        display: inline-block;
    }
    
    .filter-btn.active {
        background: #3b82f6;
        color: white;
        border-color: #3b82f6;
    }
    
    .filter-btn:hover:not(.active) {
        background: #f9fafb;
    }
    
    .highlight {
        background: #fef3cd;
        padding: 0 2px;
        border-radius: 2px;
    }
    
    .search-form {
        margin-bottom: 20px;
    }
</style>

<main>
    <div class="p-4 mx-auto max-w-(--breakpoint-2xl) md:p-6">
        <div class="search-results">
            <div class="search-header">
                <h1 class="text-2xl font-bold text-gray-800 mb-4">Recherche</h1>
                
                <!-- Formulaire de recherche -->
                <!-- <form method="GET" action="<?= ROOT ?>/search" class="search-form">
                    <div class="flex gap-3">
                        <input 
                            type="text" 
                            name="q" 
                            value="<?= htmlspecialchars($query ?? '') ?>" 
                            placeholder="Rechercher documents, dossiers, archives..." 
                            class="flex-1 h-11 rounded-lg border border-gray-300 px-4 text-sm focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10"
                        />
                        <button type="submit" class="h-11 px-6 bg-brand-500 text-white rounded-lg hover:bg-brand-600">
                            Rechercher
                        </button>
                    </div>
                </form> -->
                
                <!-- Filtres -->
                <?php if (!empty($query)): ?>
                    <div class="search-filters" >
                        <?php
                        // Utiliser les comptes stockés dans le contrôleur
                        $counts = $counts ?? [
                            'all' => 0,
                            'documents' => 0,
                            'dossiers' => 0,
                            'archives' => 0,
                            'historique' => 0
                        ];
                        ?>
                        
                        <a href="<?= ROOT ?>/search?q=<?= urlencode($query) ?>&type=all" 
                        class="filter-btn <?= $type === 'all' ? 'active' : '' ?>">
                            Tous (<?= $counts['all'] ?>)
                        </a>
                        <a href="<?= ROOT ?>/search?q=<?= urlencode($query) ?>&type=documents" 
                        class="filter-btn <?= $type === 'documents' ? 'active' : '' ?>">
                            Documents (<?= $counts['documents'] ?>)
                        </a>
                        <a href="<?= ROOT ?>/search?q=<?= urlencode($query) ?>&type=dossiers" 
                        class="filter-btn <?= $type === 'dossiers' ? 'active' : '' ?>">
                            Dossiers (<?= $counts['dossiers'] ?>)
                        </a>
                        <a href="<?= ROOT ?>/search?q=<?= urlencode($query) ?>&type=archives" 
                        class="filter-btn <?= $type === 'archives' ? 'active' : '' ?>">
                            Archives (<?= $counts['archives'] ?>)
                        </a>
                        <a href="<?= ROOT ?>/search?q=<?= urlencode($query) ?>&type=historique" 
                        class="filter-btn <?= $type === 'historique' ? 'active' : '' ?>">
                            Historique (<?= $counts['historique'] ?>)
                        </a>
                    </div>
                <?php endif; ?>
            </div>

            <?php if (!empty($query)): ?>
                <div class="search-stats">
                    <?php if ($type === 'all'): ?>
                        Résultats pour "<?= htmlspecialchars($query) ?>" dans tous les éléments
                    <?php else: ?>
                        Résultats pour "<?= htmlspecialchars($query) ?>" dans <?= $type ?>
                    <?php endif; ?>
                </div>

                <?php 
                // Vérifier s'il y a des résultats
                $hasResults = false;
                if ($type === 'all') {
                    foreach ($results as $section) {
                        if (!empty($section['items']) && is_array($section['items']) && count($section['items']) > 0) {
                            $hasResults = true;
                            break;
                        }
                    }
                } else {
                    $currentSection = $results[$type] ?? null;
                    $hasResults = !empty($currentSection['items']) && is_array($currentSection['items']) && count($currentSection['items']) > 0;
                }
                ?>

                <?php if (!$hasResults): ?>
                    <div class="no-results">
                        <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <p class="text-lg text-gray-500">Aucun résultat trouvé pour "<?= htmlspecialchars($query) ?>"</p>
                        <?php if ($type !== 'all'): ?>
                            <p class="text-sm text-gray-400 mt-2">
                                <a href="<?= ROOT ?>/search?q=<?= urlencode($query) ?>&type=all" class="text-brand-500 hover:text-brand-600">
                                    Voir tous les résultats
                                </a>
                            </p>
                        <?php endif; ?>
                    </div>
                <?php else: ?>
                    <!-- Affichage par type -->
                    <?php if ($type === 'all'): ?>
                        <?php foreach ($results as $sectionType => $section): ?>
                            <?php if (!empty($section['items']) && is_array($section['items']) && count($section['items']) > 0): ?>
                                <div class="results-section">
                                    <h2 class="section-title">
                                        <?= $section['title'] ?> (<?= $section['count'] ?>)
                                    </h2>
                                    <?php foreach ($section['items'] as $item): ?>
                                        <div class="result-item" onclick="openResult('<?= $sectionType ?>', <?= $item->id ?? 0 ?>)">
                                            <div class="result-title">
                                                <span class="result-type type-<?= $sectionType ?>">
                                                    <?= strtoupper(substr($sectionType, 0, 1)) ?>
                                                </span>
                                                <?= $this->highlightSearchText($item->nom ?? $item->details ?? 'Sans titre', $query) ?>
                                            </div>
                                            <div class="result-meta">
                                                <?php if ($sectionType === 'documents'): ?>
                                                    <span>Dossier: <?= $item->dossier_nom ?? 'Inconnu' ?></span>
                                                    <span>Ajouté: <?= date('d/m/Y H:i', strtotime($item->date_upload ?? 'now')) ?></span>
                                                <?php elseif ($sectionType === 'dossiers'): ?>
                                                    <span>Service: <?= $item->service_nom ?? 'Inconnu' ?></span>
                                                    <span>Créé: <?= date('d/m/Y H:i', strtotime($item->date_creation ?? 'now')) ?></span>
                                                    <?php if (!empty($item->createur_prenom) || !empty($item->createur_nom)): ?>
                                                        <span>Par: <?= $item->createur_prenom ?? '' ?> <?= $item->createur_nom ?? '' ?></span>
                                                    <?php endif; ?>
                                                <?php elseif ($sectionType === 'archives'): ?>
                                                    <span>Archivé: <?= date('d/m/Y H:i', strtotime($item->date_archivage ?? 'now')) ?></span>
                                                    <span>Service: <?= $item->service_nom ?? 'Inconnu' ?></span>
                                                <?php elseif ($sectionType === 'historique'): ?>
                                                    <span>Action: <?= $item->type_action ?? 'Inconnue' ?></span>
                                                    <span>Date: <?= date('d/m/Y H:i', strtotime($item->date_action ?? 'now')) ?></span>
                                                    <?php if (!empty($item->document_nom)): ?>
                                                        <span>Document: <?= $item->document_nom ?></span>
                                                    <?php endif; ?>
                                                    <?php if (!empty($item->dossier_nom)): ?>
                                                        <span>Dossier: <?= $item->dossier_nom ?></span>
                                                    <?php endif; ?>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <!-- Affichage pour un type spécifique -->
                        <div class="results-section">
                            <?php 
                            $currentSection = $results[$type] ?? ['items' => []];
                            foreach ($currentSection['items'] as $item): 
                            ?>
                                <div class="result-item" onclick="openResult('<?= $type ?>', <?= $item->id ?? 0 ?>)">
                                    <div class="result-title">
                                        <span class="result-type type-<?= $type ?>">
                                            <?= strtoupper(substr($type, 0, 1)) ?>
                                        </span>
                                        <?= $this->highlightSearchText($item->nom ?? $item->details ?? 'Sans titre', $query) ?>
                                    </div>
                                    <div class="result-meta">
                                        <?php if ($type === 'documents'): ?>
                                            <span>Dossier: <?= $item->dossier_nom ?? 'Inconnu' ?></span>
                                            <span>Ajouté: <?= date('d/m/Y H:i', strtotime($item->date_upload ?? 'now')) ?></span>
                                        <?php elseif ($type === 'dossiers'): ?>
                                            <span>Service: <?= $item->service_nom ?? 'Inconnu' ?></span>
                                            <span>Créé: <?= date('d/m/Y H:i', strtotime($item->date_creation ?? 'now')) ?></span>
                                            <?php if (!empty($item->createur_prenom) || !empty($item->createur_nom)): ?>
                                                <span>Par: <?= $item->createur_prenom ?? '' ?> <?= $item->createur_nom ?? '' ?></span>
                                            <?php endif; ?>
                                        <?php elseif ($type === 'archives'): ?>
                                            <span>Archivé: <?= date('d/m/Y H:i', strtotime($item->date_archivage ?? 'now')) ?></span>
                                            <span>Service: <?= $item->service_nom ?? 'Inconnu' ?></span>
                                        <?php elseif ($type === 'historique'): ?>
                                            <span>Action: <?= $item->type_action ?? 'Inconnue' ?></span>
                                            <span>Date: <?= date('d/m/Y H:i', strtotime($item->date_action ?? 'now')) ?></span>
                                            <?php if (!empty($item->document_nom)): ?>
                                                <span>Document: <?= $item->document_nom ?></span>
                                            <?php endif; ?>
                                            <?php if (!empty($item->dossier_nom)): ?>
                                                <span>Dossier: <?= $item->dossier_nom ?></span>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
            <?php else: ?>
                <div class="no-results">
                    <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    <p class="text-lg text-gray-500">Entrez un terme de recherche pour commencer</p>
                    <p class="text-sm text-gray-400 mt-2">
                        Recherchez parmi vos documents, dossiers, archives et historique d'actions
                    </p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</main>

<?php $this->view("footer"); ?>

<script>
function openResult(type, id) {
    const baseUrl = '<?= ROOT ?>';
    
    switch(type) {
        case 'documents':
            // Pour un document, on redirige vers son dossier
            window.location.href = baseUrl + '/document?dossier_id=' + (id || '');
            break;
        case 'dossiers':
            // Pour un dossier, on redirige vers le dossier
            window.location.href = baseUrl + '/document?dossier_id=' + id;
            break;
        case 'archives':
            // Pour les archives, on redirige vers la page des archives
            window.location.href = baseUrl + '/document/archives';
            break;
        case 'historique':
            // Pour l'historique, on peut rester sur la page ou rediriger vers l'historique détaillé
            // window.location.href = baseUrl + '/historique?action_id=' + id;
            break;
        default:
            console.log('Type non géré:', type);
    }
}

// Raccourci clavier pour la recherche
document.addEventListener('keydown', function(e) {
    if ((e.metaKey || e.ctrlKey) && e.key === 'k') {
        e.preventDefault();
        const searchInput = document.querySelector('input[name="q"]');
        if (searchInput) {
            searchInput.focus();
        }
    }
    
    // Échap pour vider la recherche
    if (e.key === 'Escape') {
        const searchInput = document.querySelector('input[name="q"]');
        if (searchInput && searchInput.value) {
            searchInput.value = '';
            searchInput.focus();
        }
    }
});

// Focus automatique sur le champ de recherche si query vide
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.querySelector('input[name="q"]');
    const urlParams = new URLSearchParams(window.location.search);
    const query = urlParams.get('q');
    
    if (!query && searchInput) {
        searchInput.focus();
    }
});
</script>