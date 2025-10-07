<?php $this->view("head"); ?>

<main>
    <div class="p-4 mx-auto max-w-(--breakpoint-2xl) md:p-6">
        <div class="mb-6">
            <div class="mb-6 flex flex-wrap items-center justify-between gap-3">
                <h2 class="text-xl font-semibold text-gray-800 dark:text-white/90">Dossiers Archivés</h2>
                <a href="<?= ROOT ?>/document" class="inline-flex items-center gap-2 px-4 py-3 text-sm font-medium text-gray-700 transition rounded-lg bg-gray-100 hover:bg-gray-200">
                    Retour aux documents
                </a>
            </div>
        </div>

        <?php if (!empty($dossiers_archives)): ?>
            <div class="dossier-grid">
                <?php foreach ($dossiers_archives as $dossier): ?>
                    <div class="dossier-card opacity-70">
                        <div class="dossier-icon text-gray-400">
                            <svg viewBox="0 0 24 24" fill="currentColor">
                                <path d="M20 6h-8l-2-2H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V8c0-1.1-.9-2-2-2zm0 12H4V6h5.17l2 2H20v10z"/>
                            </svg>
                        </div>
                        <div class="dossier-name text-gray-500"><?= esc($dossier->nom) ?></div>
                        <div class="dossier-info">
                            <div class="document-count bg-gray-400">
                                Archivé
                            </div>
                            <div class="dossier-date">
                                <?= date('d/m/Y', strtotime($dossier->date_archivage)) ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="p-6 text-center text-gray-500 dark:text-gray-400">
                Aucun dossier archivé.
            </div>
        <?php endif; ?>
    </div>
</main>

<?php $this->view("footer"); ?>