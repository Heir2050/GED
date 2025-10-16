<?php $this->view("head"); ?>



<style>
    /* Styles existants conservés */
    .submission-container {
        width: 100%;
        max-width: 600px;
        background: white;
        border-radius: 16px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        padding: 30px;
    }
    
    .form-group {
        margin-bottom: 20px;
    }
    
    .file-upload-group {
        position: relative;
    }
    
    .file-upload-area {
        border: 2px dashed #d1d5db;
        border-radius: 12px;
        padding: 40px 20px;
        text-align: center;
        transition: all 0.3s ease;
        background: #f9fafb;
        position: relative;
        overflow: hidden;
    }
    
    .file-upload-area.drag-over {
        border-color: #3b82f6;
        background-color: rgba(59, 130, 246, 0.05);
    }
    
    .file-upload-area.has-files {
        border-color: #10b981;
        background-color: rgba(16, 185, 129, 0.05);
    }
    
    #fileInput {
        position: absolute;
        width: 0.1px;
        height: 0.1px;
        opacity: 0;
        overflow: hidden;
        z-index: -1;
    }
    
    .file-upload-label {
        display: flex;
        flex-direction: column;
        align-items: center;
        cursor: pointer;
        color: #4b5563;
    }
    
    .file-icon-svg {
        width: 60px;
        height: 60px;
        margin-bottom: 16px;
        color: #9ca3af;
        transition: color 0.3s ease;
    }
    
    .file-upload-area:hover .file-icon-svg {
        color: #3b82f6;
    }
    
    .file-upload-label span {
        display: block;
    }
    
    #fileLabelText {
        font-size: 18px;
        font-weight: 500;
        margin-bottom: 8px;
    }
    
    .file-requirements {
        font-size: 14px;
        color: #6b7280;
    }
    
    .file-preview {
        margin-top: 20px;
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
        gap: 12px;
    }
    
    .file-preview-item {
        background: white;
        border-radius: 8px;
        padding: 12px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        position: relative;
        margin-bottom: 1rem;
    }
    
    .file-preview-icon {
        width: 40px;
        height: 40px;
        color: #3b82f6;
        margin-bottom: 8px;
    }
    
    .file-preview-name {
        font-size: 12px;
        font-weight: 500;
        color: #374151;
        word-break: break-word;
        max-width: 100%;
    }
    
    .file-preview-size {
        font-size: 10px;
        color: #6b7280;
        margin-top: 4px;
    }
    
    .remove-file {
        position: absolute;
        top: -8px;
        right: -8px;
        width: 20px;
        height: 20px;
        border-radius: 50%;
        background: #ef4444;
        color: white;
        display: flex;
        justify-content: center;
        align-items: center;
        font-size: 12px;
        cursor: pointer;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }
    
    .upload-status {
        margin-top: 16px;
        text-align: center;
        font-size: 14px;
        font-weight: 500;
    }
    
    .upload-status.success {
        color: #10b981;
    }
    
    .upload-status.error {
        color: #ef4444;
    }
    
    .submit-button {
        display: block;
        width: 100%;
        padding: 12px 20px;
        background: #3b82f6;
        color: white;
        border: none;
        border-radius: 8px;
        font-size: 16px;
        font-weight: 500;
        cursor: pointer;
        transition: background 0.3s ease;
        margin-top: 20px;
    }
    
    .submit-button:hover {
        background: #2563eb;
    }
    
    .submit-button:disabled {
        background: #9ca3af;
        cursor: not-allowed;
    }
    
    .files-count {
        margin-top: 12px;
        font-size: 14px;
        color: #6b7280;
        text-align: center;
    }
    
    .text-error-500 {
        color: #ef4444;
        font-size: 0.875rem;
        margin-top: 0.5rem;
    }
    /* ... (votre CSS existant) ... */
    
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
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        cursor: pointer;
        border: 1px solid #e5e7eb;
    }
    
    .dossier-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);
    }
    
    .dossier-icon {
        width: 50px;
        height: 50px;
        color: #3b82f6;
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
    
    .document-count {
        background: #3b82f6;
        color: white;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 14px;
        font-weight: 500;
    }
    
    .dossier-date {
        font-size: 12px;
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

    .send, .etat-cloture {
        display: inline-flex;              /* inline-flex */
        align-items: center;               /* items-center */
        gap: 0.5rem;                       /* gap-2 */
        padding: 0.5rem 1rem;              /* py-2 px-4 */
        font-size: 0.875rem;               /* text-sm */
        font-weight: 500;                  /* font-medium */
        color: #ffffff;                    /* text-white */
        background-color: #10B981;         /* bg-green-500 */
        border-radius: 0.5rem;             /* rounded-lg */
        text-decoration: none;             /* pour les <a> */
        cursor: pointer;
        transition: background-color 150ms ease-in-out; /* hover transition */
    }

    .send:hover, .etat-cloture:hover {
        background-color: #059669;         /* hover:bg-green-600 */
    }

    .retirer {
        color: oklch(63.7% 0.237 25.331);
        margin-left: 4px;
        font-size: 1rem;
    }

    .retirer:hover {
        color: oklch(50.5% 0.213 27.518);
    }

    .bg-retirer {
        background-color: oklch(97.1% 0.013 17.38);
        color: oklch(63.7% 0.237 25.331);
        border: 1px solid oklch(63.7% 0.237 25.331);
        padding: 0.25rem 0.5rem;
        border-radius: 0.25rem;
        display: flex;
        align-items: center;
        font-weight: 500;
        font-size: 0.75rem;
    }

    .bg-retirer:hover, .bg-retirer:hover a {
        color: oklch(57.7% 0.245 27.325);
        cursor: pointer;
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

    .btn-green.cardss {
        padding: 4px 12px;
    }
    
    /* .etat-cloture {
        background: #d4edda;
        color: #155724;
        padding: .5rem 1rem;
    } */

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
        <!-- Breadcrumb Start -->
        <!-- <div x-data="{ pageName: `Documents` }" class="mb-6">
            <div class="mb-6 flex flex-wrap items-center justify-between gap-3">
                <h2 class="text-xl font-semibold text-gray-800 dark:text-white/90" x-text="pageName">Documents</h2>
                <nav>
                    <button  @click="DemandeConges = true"  class="inline-flex items-center gap-2 px-4 py-3 text-sm font-medium text-white transition rounded-lg bg-brand-500 shadow-theme-xs hover:bg-brand-600">
                        Ajouter un Document
                        <svg class="stroke-current" width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M8 3.3335V12.6668M3.3335 8H12.6668" stroke="" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"></path>
                        </svg>
                    </button>
                </nav>
            </div>
        </div> -->
        
        <?php if (isset($dossier_courant) && $dossier_courant->origine_envoi == 'INTERNE'): ?>
                <!-- Affichage des documents d'un dossier spécifique -->
                <div class="flex justify-between items-center mb-4">
                    <div class="flex items-center gap-2">
                        <a href="<?= ROOT ?>/document" class="inline-flex items-center gap-2 px-4 py-3 text-sm font-medium text-gray-700 transition rounded-lg bg-gray-100 hover:bg-gray-200">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16" class="mr-2">
                                <path fill-rule="evenodd" d="M12 8a.5.5 0 0 1-.5.5H5.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L5.707 7.5H11.5a.5.5 0 0 1 .5.5z"/>
                            </svg>
                            Retour aux dossiers
                        </a>
                        <h3 class="text-lg font-semibold"><?= esc($dossier_courant->nom) ?></h3>
                    </div>

                    <div class="flex gap-2">
                        <div class="flex gap-2 ">
                            <!-- Afficher le bouton d'envoi UNIQUEMENT dans le service d'origine -->
                            <?php if (isset($dossier_courant) && isset($employe) && $dossier_courant->service_id == $employe->service_id): ?>
                                <a href="<?= ROOT ?>/document/envoyer/<?= $dossier_courant->id ?>" class="send">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                        <path d="M15.854.146a.5.5 0 0 1 .11.54l-5.819 14.547a.75.75 0 0 1-1.329.124l-3.178-4.995L.643 7.184a.75.75 0 0 1 .124-1.33L15.314.037a.5.5 0 0 1 .54.11ZM6.636 10.07l2.761 4.338L14.13 2.576 6.636 10.07Zm6.787-8.201L1.591 6.602l4.339 2.76 7.494-7.493Z"/>
                                    </svg>
                                    Envoyer le dossier
                                </a>
                            <?php endif; ?>
                            <!-- Ajouter un lien vers les états utilisateurs -->
                            <a href="<?= ROOT ?>/document/etats_utilisateurs/<?= $dossier_courant->id ?>" class="inline-flex items-center  px-4 py-3 text-sm font-medium text-white transition rounded-lg bg-brand-500 shadow-theme-xs hover:bg-brand-600">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16" class="mr-2">
                                    <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2z"/>
                                </svg>
                                <!-- Voir les états des utilisateurs -->
                                Voir les états
                            </a>
                        </div>
                    </div>
                </div>

                <div class="mb-4">
                    <!-- Afficher les envois actifs -->
                    <!-- Afficher les envois actifs UNIQUEMENT pour le propriétaire -->
                    <?php 
                        // Vérifier si l'utilisateur courant est le créateur du dossier
                        if (isset($dossier_courant) && isset($employe) && $dossier_courant->createur_id == $employe->id): 
                            $envoiModel = new \Model\EnvoiDossiers();
                            $serviceModel = new \Model\Services();
                            $envois_actifs = $envoiModel->getEnvoisActifs($dossier_courant->id);
                    ?>
                        <?php if ($envois_actifs): ?>
                            <div class="flex items-center gap-2 text-sm text-gray-600">
                                <span>Envoyé à :</span>
                                <?php foreach ($envois_actifs as $envoi): ?>
                                    <?php 
                                        // Récupérer le nom du service
                                        $service = $serviceModel->first(['id' => $envoi->service_id]);
                                        $nom_service = $service ? $service->nom : 'Service inconnu';
                                    ?>
                                    <span class="bg-retirer">
                                        <?= $envoi->type_envoi == 'SERVICE' ? "Service: $nom_service" : "Rôle {$envoi->role_service} ($nom_service)" ?>
                                        <a href="<?= ROOT ?>/document/retirer_envoi/<?= $envoi->id ?>" class="retirer" onclick="return confirm('Retirer cet envoi ?')">
                                            ×
                                        </a>
                                    </span>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>

                <div class="mb-4">
                    <!-- Bouton d'archivage -->
                        <div class="mt-4 p-3 bg-yellow-50 border border-yellow-200 rounded-lg" style="margin-top: 10px;">
                        <!-- Après l'affichage des documents, ajoutez la section de clôture -->
                        <?php if (isset($dossier_courant) && isset($employe)): ?>
                            <div class=" p-2 ">
                                <?php 
                                    // Vérifier l'état de l'utilisateur courant pour ce dossier
                                    $dossierModel = new \Model\Dossiers();
                                    $etat_utilisateur = $dossierModel->query(
                                        "SELECT etat FROM etatdossierutilisateur WHERE dossier_id = :dossier_id AND employe_id = :employe_id",
                                        ['dossier_id' => $dossier_courant->id, 'employe_id' => $employe->id]
                                    );
                                    
                                    $etat_courant = $etat_utilisateur[0]->etat ?? 'NON_OUVERT';
                                ?>
                                
                                <?php if ($etat_courant != 'CLOTURE'): ?>
                                    <div class="flex items-center justify-between">
                                        <p class="text-sm text-gray-600">
                                            Statut actuel : <strong class="etat-badge etat-traitement" ><?= $this->getEtatLabel($etat_courant) ?></strong>
                                        </p>
                                        <div class="flex gap-3">
                                            <button onclick="cloturerDossier(<?= $dossier_courant->id ?>)" 
                                                    class="etat-cloture">
                                                📋 Clôturer ce dossier
                                            </button>
                                        
                                        </div>
                                    </div>
                                <?php else: ?>
                                    <?php if (isset($tous_ont_cloture) && $tous_ont_cloture): ?>
                                        <div class="flex items-center justify-between">
                                            <div>
                                                <p class="text-green-800 font-medium">✅ Tous les utilisateurs ont clôturé ce dossier</p>
                                                <p class="text-green-600 text-sm">Vous pouvez maintenant archiver le dossier</p>
                                            </div>
                                            <button onclick="archiverDossier(<?= $dossier_courant->id ?>)" class="btn-green">
                                                Archiver le dossier
                                            </button>
                                        </div>
                                    <?php else: ?>
                                        <div class="bg-yellow-50 rounded-lg">
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
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            
            
            <?php if (!empty($documents)): ?>
                <div class="rounded-2xl bg-white p-6">
                    <table class="min-w-full">
                        <thead class="border-y border-gray-100 py-3 dark:border-gray-800">
                            <tr>
                                <th class="py-3 px-5 font-normal whitespace-nowrap sm:pr-6">
                                    <div class="flex items-center">
                                        <p class="text-theme-sm text-gray-500 dark:text-gray-400">Nom du Fichier</p>
                                    </div>
                                </th>
                                <th class="px-5 py-3 font-normal whitespace-nowrap sm:px-6">
                                    <div class="flex items-center">
                                        <p class="text-theme-sm text-gray-500 dark:text-gray-400">Type</p>
                                    </div>
                                </th>
                                <th class="px-5 py-3 font-normal whitespace-nowrap sm:px-6">
                                    <div class="flex items-center">
                                        <p class="text-theme-sm text-gray-500 dark:text-gray-400">Taille</p>
                                    </div>
                                </th>
                                <th class="px-5 py-3 font-normal whitespace-nowrap sm:px-6">
                                    <div class="flex items-center">
                                        <p class="text-theme-sm text-gray-500 dark:text-gray-400">Date d'upload</p>
                                    </div>
                                </th>
                                <th class="px-5 py-3 font-normal whitespace-nowrap sm:px-6">
                                    <div class="flex items-center">
                                        <p class="text-theme-sm text-gray-500 dark:text-gray-400">Actions</p>
                                    </div>
                                </th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                            <?php foreach ($documents as $item): ?>
                                <?php
                                    $extension = strtolower(pathinfo($item->nom, PATHINFO_EXTENSION));
                                    $can_display_nativement = in_array($extension, ['pdf', 'jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp', 'txt', 'csv', 'html', 'htm']);
                                    $is_word_file = in_array($extension, ['doc', 'docx']);
                                    $is_excel_file = in_array($extension, ['xls', 'xlsx']);
                                    $is_powerpoint_file = in_array($extension, ['ppt', 'pptx']);
                                    
                                    if ($can_display_nativement) {
                                        $view_url = ROOT . '/document/visualiser/' . $item->id;
                                        $view_text = 'Voir dans le navigateur';
                                        $link_title = 'Ouvrir dans le navigateur';
                                        $view_method = 'native';
                                    } elseif ($is_word_file) {
                                        $file_url = ROOT . '/' . $dossier_courant->chemin . $item->nom_stockage;
                                        $view_text = 'Voir le document Word';
                                        $link_title = 'Ouvrir le document Word';
                                        $view_method = 'word';
                                    } elseif ($is_excel_file) {
                                        $file_url = ROOT . '/' . $dossier_courant->chemin . $item->nom_stockage;
                                        $view_text = 'Voir le fichier Excel';
                                        $link_title = 'Ouvrir le fichier Excel';
                                        $view_method = 'excel';
                                    } elseif ($is_powerpoint_file) {
                                        $view_url = ROOT . '/document/telecharger/' . $item->id;
                                        $view_text = 'Télécharger';
                                        $link_title = 'Télécharger le fichier';
                                        $view_method = 'download';
                                    } else {
                                        $view_url = ROOT . '/document/telecharger/' . $item->id;
                                        $view_text = 'Télécharger';
                                        $link_title = 'Télécharger le fichier';
                                        $view_method = 'download';
                                    }
                                ?>
                                <tr>
                                    <!-- Fichier -->
                                    <td class="py-3 px-5 whitespace-nowrap sm:px-5">
                                        <div class="flex items-center gap-3">
                                            <div class="h-8 w-8">
                                                <?= getFileIcon($item->nom_stockage) ?>
                                            </div>
                                            <div>
                                                <?php if (!empty($item->nom_stockage)): ?>
                                                    <a href="<?= $view_url ?>" 
                                                       target="_blank" 
                                                       class="text-brand-600 hover:underline"
                                                       title="<?= $can_display_natively ? 'Voir dans le navigateur' : ($is_office_file ? 'Voir avec Google Docs' : 'Télécharger') ?>">
                                                        <?= esc($item->nom) ?>
                                                    </a>
                                                <?php else: ?>
                                                    <span class="text-gray-400">Aucun fichier</span>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </td>

                                    <!-- Type -->
                                    <td class="px-5 py-3 whitespace-nowrap sm:px-6">
                                        <span class="block text-sm">
                                            <?= !empty($item->type) ? esc($item->type) : strtoupper($extension) ?>
                                        </span>
                                    </td>

                                    <!-- Taille -->
                                    <td class="px-5 py-3 whitespace-nowrap sm:px-6">
                                        <span class="block text-sm">
                                            <?= !empty($item->taille) ? formatFileSize($item->taille) : 'N/A' ?>
                                        </span>
                                    </td>

                                    <!-- Date d'upload -->
                                    <td class="px-5 py-3 whitespace-nowrap sm:px-6">
                                        <span class="block text-sm"><?= date('d/m/Y H:i', strtotime($item->date_upload)) ?></span>
                                    </td>

                                    <!-- <button @click="open = !open" class="text-gray-500 dark:text-gray-400">
                                        <svg class="fill-current" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd" clip-rule="evenodd" d="M5.99902 10.245C6.96552 10.245 7.74902 11.0285 7.74902 11.995V12.005C7.74902 12.9715 6.96552 13.755 5.99902 13.755C5.03253 13.755 4.24902 12.9715 4.24902 12.005V11.995C4.24902 11.0285 5.03253 10.245 5.99902 10.245ZM17.999 10.245C18.9655 10.245 19.749 11.0285 19.749 11.995V12.005C19.749 12.9715 18.9655 13.755 17.999 13.755C17.0325 13.755 16.249 12.9715 16.249 12.005V11.995C16.249 11.0285 17.0325 10.245 17.999 10.245ZM13.749 11.995C13.749 11.0285 12.9655 10.245 11.999 10.245C11.0325 10.245 10.249 11.0285 10.249 11.995V12.005C10.249 12.9715 11.0325 13.755 11.999 13.755C12.9655 13.755 13.749 12.9715 13.749 12.005V11.995Z" fill=""></path>
                                        </svg>
                                    </button> -->
                                    
                                    <!-- Colonne Actions -->
                                    <td class="px-5 py-3 whitespace-nowrap sm:px-6">
                                        <div x-data="{ open: false }" class="relative">
                                            <button @click="open = !open" class="text-gray-500 dark:text-gray-400">
                                                <!-- Icône menu -->
                                                <svg class="fill-current" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M5.99902 10.245C6.96552 10.245 7.74902 11.0285 7.74902 11.995V12.005C7.74902 12.9715 6.96552 13.755 5.99902 13.755C5.03253 13.755 4.24902 12.9715 4.24902 12.005V11.995C4.24902 11.0285 5.03253 10.245 5.99902 10.245ZM17.999 10.245C18.9655 10.245 19.749 11.0285 19.749 11.995V12.005C19.749 12.9715 18.9655 13.755 17.999 13.755C17.0325 13.755 16.249 12.9715 16.249 12.005V11.995C16.249 11.0285 17.0325 10.245 17.999 10.245ZM13.749 11.995C13.749 11.0285 12.9655 10.245 11.999 10.245C11.0325 10.245 10.249 11.0285 10.249 11.995V12.005C10.249 12.9715 11.0325 13.755 11.999 13.755C12.9655 13.755 13.749 12.9715 13.749 12.005V11.995Z" fill=""></path>
                                                </svg>
                                            </button>
                                            
                                            <div x-show="open" @click.outside="open = false" class="shadow-theme-lg dark:bg-gray-dark fixed w-40 space-y-1 rounded-2xl border border-gray-200 bg-white p-2 dark:border-gray-800" style="position: absolute; top: 20px; right: 0; z-index: 999;">
                                                <?php if ($view_method === 'word'): ?>
                                                    <button onclick="openWordDocument('<?= ROOT . '/' . $dossier_courant->chemin . $item->nom_stockage ?>', '<?= esc($item->nom) ?>')" 
                                                            class="text-theme-xs flex w-full rounded-lg px-3 py-2 text-left font-medium text-gray-500 hover:bg-gray-100">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="mr-2">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"></path>
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                        </svg>
                                                        Voir le document
                                                    </button>
                                                <?php elseif ($view_method === 'excel'): ?>
                                                    <button onclick="openExcelDocument('<?= ROOT . '/' . $dossier_courant->chemin . $item->nom_stockage ?>', '<?= esc($item->nom) ?>')" 
                                                            class="text-theme-xs flex w-full rounded-lg px-3 py-2 text-left font-medium text-gray-500 hover:bg-gray-100">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="mr-2">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"></path>
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                        </svg>
                                                        Voir le tableau
                                                    </button>
                                                <?php elseif ($view_method === 'native'): ?>
                                                    <a href="<?= $view_url ?>" 
                                                    target="_blank" 
                                                    class="text-theme-xs flex w-full rounded-lg px-3 py-2 text-left font-medium text-gray-500 hover:bg-gray-100">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="mr-2">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"></path>
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                        </svg>
                                                        <?= $view_text ?>
                                                    </a>
                                                <?php endif; ?>
                                                
                                                <a href="<?= ROOT ?>/document/telecharger/<?= $item->id ?>" 
                                                class="text-theme-xs flex w-full rounded-lg px-3 py-2 text-left font-medium text-gray-500 hover:bg-gray-100">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="mr-2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3"></path>
                                                    </svg>
                                                    Télécharger
                                                </a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else : ?>
                <div class="p-6 text-center text-gray-500 dark:text-gray-400">
                    Aucun document dans ce dossier.
                </div>
            <?php endif; ?>
            
        <?php else: ?>
            <!-- Breadcrumb Start -->
            <div x-data="{ pageName: `Documents` }" class="mb-6">
                <div class="mb-6 flex flex-wrap items-center justify-between gap-3">
                    <h2 class="text-xl font-semibold text-gray-800 dark:text-white/90" x-text="pageName">Documents</h2>
                    <nav>
                        <button  @click="DemandeConges = true"  class="inline-flex items-center gap-2 px-4 py-3 text-sm font-medium text-white transition rounded-lg bg-brand-500 shadow-theme-xs hover:bg-brand-600">
                            Ajouter un Document
                            <svg class="stroke-current" width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M8 3.3335V12.6668M3.3335 8H12.6668" stroke="" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"></path>
                            </svg>
                        </button>
                    </nav>
                </div>
            </div>
            <!-- Affichage de la liste des dossiers -->
            <div class="space-y-5 sm:space-y-6">
                <?php if (!empty($dossiers)): ?>
                    <div class="dossier-grid">
                        <?php foreach ($dossiers as $dossier_item): ?>
                            <div class="dossier-card" onclick="window.location='<?= ROOT ?>/document?dossier_id=<?= $dossier_item->id ?>'">
                                <div class="flex items-center justify-between">
                                    <div class="dossier-icon">
                                        <svg viewBox="0 0 24 24" fill="currentColor">
                                            <path d="M20 6h-8l-2-2H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V8c0-1.1-.9-2-2-2zm0 12H4V6h5.17l2 2H20v10z"/>
                                        </svg>
                                    </div>

                                    <!-- État du dossier -->
                                    <?php
                                        // Récupérer l'état du dossier pour l'utilisateur courant
                                        $dossierModel = new \Model\Dossiers();
                                        $etat_utilisateur = $dossierModel->query(
                                            "SELECT etat FROM etatdossierutilisateur WHERE dossier_id = :dossier_id AND employe_id = :employe_id",
                                            ['dossier_id' => $dossier_item->id, 'employe_id' => $employe->id]
                                        );
                                        
                                        $etat_courant = $etat_utilisateur[0]->etat ?? 'NON_OUVERT';
                                        $etat_labels = [
                                            'NON_OUVERT' => ['label' => 'Non ouvert', 'class' => 'etat-non-ouvert rounded'],
                                            'TRAITEMENT' => ['label' => 'En traitement', 'class' => 'etat-traitement rounded'],
                                            'CLOTURE' => ['label' => 'Clôturé', 'class' => 'btn-green cardss']
                                        ];
                                        $etat_info = $etat_labels[$etat_courant] ?? $etat_labels['NON_OUVERT'];
                                    ?>
                                
                                    <div class="etat-dossier mb-2">
                                        <span class="inline-block px-2 py-1 rounded text-xs font-medium <?= $etat_info['class'] ?>">
                                            <?= $etat_info['label'] ?>
                                        </span>
                                    </div>
                                </div>

                                <div class="dossier-name"><?= esc($dossier_item->nom) ?></div>
                                
                                
                                
                                <div class="dossier-info">
                                    <div class="document-count">
                                        <?= $dossier_item->nb_documents ?> fichier(s)
                                    </div>
                                    <div class="dossier-date">
                                        <?= date('d/m/Y', strtotime($dossier_item->date_creation)) ?>
                                        <?php if (isset($dossier_item->origine) && $dossier_item->origine == 'EXTERNE'): ?>
                                            <span class="bg-green-100 text-green-800 px-2 py-1 rounded text-xs ml-2">
                                                Reçu
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="p-6 text-center text-gray-500 dark:text-gray-400">
                        Aucun dossier disponible. Créez votre premier dossier en uploadant un document.
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>

       
    </div>
</main>

<!-- Modal pour Word -->
<div id="wordModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-lg w-11/12 h-5/6 max-w-6xl flex flex-col">
        <div class="flex justify-between items-center p-4 border-b">
            <h3 id="wordModalTitle" class="text-lg font-semibold">Document Word</h3>
            <div class="flex gap-2">
                <button id="wordDownloadBtn" class="text-sm bg-blue-600 text-white px-3 py-1 rounded hover:bg-blue-700">
                    Télécharger
                </button>
                <button onclick="closeWordViewer()" class="text-gray-500 hover:text-gray-700">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        </div>
        <div class="p-4 flex-1 overflow-auto">
            <div id="wordContent" class="prose max-w-none">
                <div class="text-center py-8">
                    <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600 mx-auto"></div>
                    <p class="mt-4 text-gray-600">Chargement du document...</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal pour Excel -->
<div id="excelModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-lg w-11/12 h-5/6 max-w-6xl flex flex-col">
        <div class="flex justify-between items-center p-4 border-b">
            <h3 id="excelModalTitle" class="text-lg font-semibold">Fichier Excel</h3>
            <div class="flex gap-2">
                <button id="excelDownloadBtn" class="text-sm bg-green-600 text-white px-3 py-1 rounded hover:bg-green-700">
                    Télécharger
                </button>
                <button onclick="closeExcelViewer()" class="text-gray-500 hover:text-gray-700">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        </div>
        <div class="p-4 flex-1 overflow-auto">
            <div id="excelContent">
                <div class="text-center py-8">
                    <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-green-600 mx-auto"></div>
                    <p class="mt-4 text-gray-600">Chargement du fichier Excel...</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/mammoth/1.6.0/mammoth.browser.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
<script>
// Variables globales
let currentDocumentUrl = '';

// ========== FONCTIONS POUR WORD ==========
function openWordDocument(fileUrl, filename) {
    currentDocumentUrl = fileUrl;
    document.getElementById('wordModalTitle').textContent = filename;
    document.getElementById('wordModal').classList.remove('hidden');
    document.getElementById('wordDownloadBtn').onclick = function() {
        window.open(fileUrl, '_blank');
    };
    
    loadWordDocument(fileUrl);
}

function closeWordViewer() {
    document.getElementById('wordModal').classList.add('hidden');
    document.getElementById('wordContent').innerHTML = `
        <div class="text-center py-8">
            <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600 mx-auto"></div>
            <p class="mt-4 text-gray-600">Chargement du document...</p>
        </div>
    `;
}

async function loadWordDocument(fileUrl) {
    try {
        const arrayBuffer = await fetch(fileUrl).then(response => {
            if (!response.ok) throw new Error('Erreur de chargement');
            return response.arrayBuffer();
        });
        
        const result = await mammoth.convertToHtml({arrayBuffer: arrayBuffer});
        
        document.getElementById('wordContent').innerHTML = `
            <div class="bg-white p-6 rounded-lg border">
                ${result.value}
            </div>
            ${result.messages.length > 0 ? `
                <div class="mt-4 p-4 bg-yellow-50 border border-yellow-200 rounded">
                    <p class="text-sm text-yellow-800">
                        Note : Certains éléments du document peuvent ne pas s'afficher correctement.
                    </p>
                </div>
            ` : ''}
        `;
        
    } catch (error) {
        showError('wordContent', fileUrl, error);
    }
}

// ========== FONCTIONS POUR EXCEL ==========
function openExcelDocument(fileUrl, filename) {
    currentDocumentUrl = fileUrl;
    document.getElementById('excelModalTitle').textContent = filename;
    document.getElementById('excelModal').classList.remove('hidden');
    document.getElementById('excelDownloadBtn').onclick = function() {
        window.open(fileUrl, '_blank');
    };
    
    loadExcelDocument(fileUrl);
}

function closeExcelViewer() {
    document.getElementById('excelModal').classList.add('hidden');
    document.getElementById('excelContent').innerHTML = `
        <div class="text-center py-8">
            <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-green-600 mx-auto"></div>
            <p class="mt-4 text-gray-600">Chargement du fichier Excel...</p>
        </div>
    `;
}

async function loadExcelDocument(fileUrl) {
    try {
        const arrayBuffer = await fetch(fileUrl).then(response => {
            if (!response.ok) throw new Error('Erreur de chargement');
            return response.arrayBuffer();
        });
        
        // Lire le fichier Excel
        const data = new Uint8Array(arrayBuffer);
        const workbook = XLSX.read(data, {type: 'array'});
        
        let htmlContent = '';
        
        // Parcourir toutes les feuilles
        workbook.SheetNames.forEach((sheetName, index) => {
            const worksheet = workbook.Sheets[sheetName];
            const html = XLSX.utils.sheet_to_html(worksheet, {
                id: `sheet-${index}`,
                editable: false,
                header: ''
            });
            
            htmlContent += `
                <div class="mb-8">
                    <h4 class="text-lg font-semibold mb-4 text-gray-800 border-b pb-2">
                        📊 Feuille : ${sheetName}
                    </h4>
                    <div class="overflow-x-auto">
                        ${html}
                    </div>
                </div>
            `;
        });
        
        document.getElementById('excelContent').innerHTML = `
            <div class="bg-white">
                ${htmlContent}
            </div>
            <div class="mt-4 p-4 bg-blue-50 border border-blue-200 rounded">
                <p class="text-sm text-blue-800">
                    💡 <strong>Information :</strong> Le fichier Excel contient ${workbook.SheetNames.length} feuille(s).
                    Les formules et graphiques ne sont pas affichés.
                </p>
            </div>
        `;
        
    } catch (error) {
        showError('excelContent', fileUrl, error);
    }
}

// ========== FONCTION D'ERREUR COMMUNE ==========
function showError(containerId, fileUrl, error) {
    document.getElementById(containerId).innerHTML = `
        <div class="text-center py-8 text-red-600">
            <svg class="w-16 h-16 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/>
            </svg>
            <h3 class="text-lg font-semibold mb-2">Erreur de chargement</h3>
            <p>Impossible d'afficher le document. Veuillez le télécharger pour le visualiser.</p>
            <button onclick="window.open('${fileUrl}', '_blank')" 
                    class="mt-4 bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                Télécharger le document
            </button>
        </div>
    `;
    console.error('Erreur:', error);
}

// ========== GESTION DES ÉVÉNEMENTS ==========
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeWordViewer();
        closeExcelViewer();
    }
});

// Fermer en cliquant en dehors
document.getElementById('wordModal').addEventListener('click', function(e) {
    if (e.target === this) closeWordViewer();
});

document.getElementById('excelModal').addEventListener('click', function(e) {
    if (e.target === this) closeExcelViewer();
});
</script>

<style>
/* Styles pour Word */
.prose {
    max-width: none;
    line-height: 1.6;
}

.prose p {
    margin-bottom: 1em;
}

.prose h1, .prose h2, .prose h3, .prose h4, .prose h5, .prose h6 {
    margin-top: 1.5em;
    margin-bottom: 0.5em;
    font-weight: bold;
}

.prose table {
    border-collapse: collapse;
    width: 100%;
    margin: 1em 0;
}

.prose table, .prose th, .prose td {
    border: 1px solid #e5e7eb;
    padding: 0.5em;
}

/* Styles pour Excel */
#excelContent table {
    border-collapse: collapse;
    width: 100%;
    margin: 0.5em 0;
    font-size: 0.875rem;
}

#excelContent th {
    background-color: #f8fafc;
    border: 1px solid #e2e8f0;
    padding: 0.5em;
    font-weight: 600;
    text-align: left;
}

#excelContent td {
    border: 1px solid #e2e8f0;
    padding: 0.5em;
    min-width: 80px;
}

#excelContent tr:nth-child(even) {
    background-color: #f8fafc;
}

#excelContent tr:hover {
    background-color: #f1f5f9;
}
</style>



<!-- Cloturer un dossier -->
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

<!-- Fonctions utilitaires -->
<script>
    function formatFileSize(bytes) {
        if (bytes === 0 || bytes === undefined) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    }
</script>

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

    // Fonction helper pour les labels d'état
    function getEtatLabel(etat) {
        const labels = {
            'NON_OUVERT': 'Non ouvert',
            'TRAITEMENT': 'En traitement', 
            'CLOTURE': 'Clôturé'
        };
        return labels[etat] || etat;
    }
</script>

<?php $this->view("footer"); ?>