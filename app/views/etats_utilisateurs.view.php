<?php $this->view("head"); ?>

<style>
    .etat-badge {
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 14px;
        font-weight: 500;
    }
    
    .etat-non-ouvert {
        background: #fef3cd;
        color: #856404;
    }
    
    .etat-traitement {
        background: #cce7ff;
        color: #004085;
    }
    
    .etat-cloture {
        background: #d4edda;
        color: #155724;
    }
    /* Classe utilitaire équivalente */
    .btn-green {
        padding: 0.5rem 1.5rem;        /* py-2 px-6 */
        background-color: #10B981;    /* bg-green-500 */
        color: #ffffff;               /* text-white */
        border-radius: 0.5rem;        /* rounded-lg */
        transition: background-color 150ms ease-in-out; /* transition-colors (durée par défaut) */
        display: inline-block;
        text-decoration: none;
        cursor: pointer;
        border: none;                 /* si c'est un <button> */
    }

    /* état hover */
    .btn-green:hover {
        background-color: #059669;    /* hover:bg-green-600 */
    }

    /* état focus (accessibilité) */
    .btn-green:focus {
        outline: 2px solid rgba(5,150,105,0.25);
        outline-offset: 2px;
    }

</style>

<main>
    <?php if (!empty(message())) : ?>
        <!-- ... message de succès existant ... -->
    <?php endif; ?>
    
    <div class="p-4 mx-auto max-w-(--breakpoint-2xl) md:p-6">
        <!-- Breadcrumb Start -->
        <div x-data="{ pageName: `États des Utilisateurs` }" class="mb-6">
            <div class="mb-6 flex flex-wrap items-center justify-between gap-3">
                <h2 class="text-xl font-semibold text-gray-800 dark:text-white/90" x-text="pageName">États des Utilisateurs</h2>
                <nav>
                    <a href="<?= ROOT ?>/document?dossier_id=<?= $dossier_courant->id ?? '' ?>" 
                       class="inline-flex items-center gap-2 px-4 py-3 text-sm font-medium text-gray-700 transition rounded-lg bg-gray-100 hover:bg-gray-200">
                        Retour aux documents
                    </a>
                </nav>
            </div>
        </div>

        <?php if (isset($dossier_courant)): ?>
            <!-- Informations du dossier -->
            <div class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                <h3 class="text-lg font-semibold text-blue-800">Dossier: <?= esc($dossier_courant->nom) ?></h3>
                <p class="text-blue-600">Suivi des états des utilisateurs</p>
            </div>

            <!-- Liste des états des utilisateurs -->
            <div class="rounded-2xl bg-white p-6">
                <h4 class="text-lg font-semibold mb-4">États des utilisateurs</h4>
                
                <?php if (!empty($etats_utilisateurs)): ?>
                    <div class="space-y-4">
                        <?php foreach ($etats_utilisateurs as $etat): ?>
                            <div class="flex items-center mb-3 justify-between p-4 border border-gray-200 rounded-lg hover:bg-gray-50">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-gray-200 rounded-full flex items-center justify-center mr-3">
                                        <span class="text-gray-600 font-semibold">
                                            <?= strtoupper(substr($etat->prenom, 0, 1) . substr($etat->nom, 0, 1)) ?>
                                        </span>
                                    </div>
                                    <div>
                                        <span class="font-medium text-gray-800"><?= esc($etat->prenom . ' ' . $etat->nom) ?></span>
                                        <p class="text-sm text-gray-500"><?= esc($etat->email) ?></p>
                                    </div>
                                </div>
                                
                                <div class="flex items-center gap-4">
                                    <span class="etat-badge 
                                        <?= $etat->etat == 'NON_OUVERT' ? 'etat-non-ouvert' : '' ?>
                                        <?= $etat->etat == 'TRAITEMENT' ? 'etat-traitement' : '' ?>
                                        <?= $etat->etat == 'CLOTURE' ? 'etat-cloture' : '' ?>">
                                        <?= $this->getEtatLabel($etat->etat) ?>
                                    </span>
                                    
                                    <?php 
                                    // Récupérer l'ID de l'employé connecté
                                    $employe_id_connecte = $this->getEmployeIdFromSession();
                                    if ($etat->employe_id == $employe_id_connecte && $etat->etat != 'CLOTURE'): 
                                    ?>
                                        <button onclick="cloturerDossier(<?= $dossier_courant->id ?>)" 
                                                class="px-4 py-2 bg-green-500 text-black rounded-lg hover:bg-green-600 text-sm transition-colors">
                                            Clôturer
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    
                    <!-- Bouton d'archivage -->
                    <?php if (!empty($tous_ont_cloture) && $tous_ont_cloture): ?>
                        <div class="mt-6 p-4 bg-green-50 border border-green-200 rounded-lg">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-green-800 font-medium">✅ Tous les utilisateurs ont clôturé ce dossier</p>
                                    <p class="text-green-600 text-sm">Vous pouvez maintenant archiver le dossier</p>
                                </div>
                                <button onclick="archiverDossier(<?= $dossier_courant->id ?>)" class="btn-green">
                                    Archiver le dossier
                                </button>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="mt-4 p-3 bg-yellow-50 border border-yellow-200 rounded-lg" style="margin-top: 10px;">
                            <p class="text-yellow-800 text-sm">
                                📊 <strong>Statut :</strong> 
                                <?php 
                                    $clotures = array_filter($etats_utilisateurs, fn($e) => $e->etat == 'CLOTURE');
                                    $total = count($etats_utilisateurs);
                                    echo count($clotures) . " sur " . $total . " utilisateurs ont clôturé";
                                ?>
                            </p>
                        </div>
                    <?php endif; ?>
                    
                <?php else: ?>
                    <div class="text-center py-8 text-gray-500">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
                        </svg>
                        <p class="mt-2">Aucun utilisateur à suivre pour ce dossier.</p>
                    </div>
                <?php endif; ?>
            </div>
            
        <?php else: ?>
            <div class="text-center py-8 text-gray-500">
                <p>Aucun dossier sélectionné.</p>
                <a href="<?= ROOT ?>/document" class="text-blue-500 hover:underline">Retour à la liste des dossiers</a>
            </div>
        <?php endif; ?>
    </div>
</main>

<script>
function cloturerDossier(dossierId) {
    if (confirm('Êtes-vous sûr de vouloir clôturer ce dossier ? Cette action est définitive.')) {
        window.location.href = '<?= ROOT ?>/document/cloturer_dossier/' + dossierId;
    }
}

function archiverDossier(dossierId) {
    if (confirm('Êtes-vous sûr de vouloir archiver ce dossier ? Il ne sera plus visible dans la liste principale.')) {
        window.location.href = '<?= ROOT ?>/document/archiver_dossier/' + dossierId;
    }
}
</script>

<?php $this->view("footer"); ?>