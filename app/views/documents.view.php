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
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        font-size: 0.875rem;
        font-weight: 500;
        color: #ffffff;
        background-color: #10B981;
        border-radius: 0.5rem;
        text-decoration: none;
        cursor: pointer;
        transition: background-color 150ms ease-in-out;
    }

    .send:hover, .etat-cloture:hover {
        background-color: #059669;
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

    .btn-green {
        padding: 0.5rem 1.5rem;
        background-color: #10B981;
        color: #ffffff;
        border-radius: 0.5rem;
        transition: background-color 150ms ease-in-out;
        display: inline-block;
        text-decoration: none;
        cursor: pointer;
        border: none;
    }

    .btn-green:hover {
        background-color: #059669;
    }

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

    /* Styles pour la pagination */
    .pagination-container {
        display: flex;
        justify-content: center;
        align-items: center;
        margin-top: 2rem;
        gap: 1rem;
    }

    .pagination-info {
        color: #6b7280;
        font-size: 0.875rem;
    }

    .pagination-btn {
        padding: 0.5rem 1rem;
        border: 1px solid #d1d5db;
        border-radius: 0.375rem;
        background: white;
        color: #374151;
        cursor: pointer;
        transition: all 0.2s;
    }

    .pagination-btn:hover:not(.disabled) {
        background: #f9fafb;
        border-color: #9ca3af;
    }

    .pagination-btn.disabled {
        color: #9ca3af;
        cursor: not-allowed;
        background: #f3f4f6;
    }

    .pagination-current {
        background: #3b82f6;
        color: white;
        border-color: #3b82f6;
    }
    .content {
        background: white;
        padding: 30px;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        line-height: 1.6;
        min-height: 400px;
        text-align: left; /* 👈 force l’alignement de base à gauche */
        word-wrap: break-word;
    }

    /* Dans votre fichier CSS principal */
    .badge-interne { background-color: #dbeafe; color: #1e40af; }
    .badge-service { background-color: #d1fae5; color: #065f46; }
    .badge-role { background-color: #ffedd5; color: #9a3412; }  
    

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
                        <button  @click="DemandeFichier = true"  class="inline-flex items-center gap-2 px-4 py-3 text-sm font-medium text-brand-500 border border-brand-500 transition rounded-lg shadow-theme-xs hover:text-brand-600">
                            Ajouter un fichier
                            <svg class="stroke-current" width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M8 3.3335V12.6668M3.3335 8H12.6668" stroke="" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"></path>
                            </svg>
                        </button>
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
                            Voir les états
                        </a>
                    </div>
                </div>
            </div>

            <div class="mb-4">
                <!-- Afficher les envois actifs UNIQUEMENT pour le propriétaire -->
                <?php 
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
                <div class="rounded-2xl bg-white">
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
                                        <p class="text-theme-sm text-gray-500 dark:text-gray-400">Date&nbsp;de&nbsp;création</p>
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
                                                    <?php if ($view_method === 'word'): ?>
                                                        <button onclick="openWordDocument('<?= $item->id ?>', '<?= esc($item->nom) ?>')" 
                                                                class="text-brand-600 hover:underline text-left">
                                                            <?= esc($item->nom) ?>
                                                        </button>
                                                    <?php elseif ($view_method === 'excel'): ?>
                                                        <button onclick="openExcelDocument('<?= $item->id ?>', '<?= esc($item->nom) ?>')" 
                                                                class="text-brand-600 hover:underline text-left">
                                                            <?= esc($item->nom) ?>
                                                        </button>
                                                    <?php else: ?>
                                                        <a href="<?= $view_url ?>" 
                                                           target="_blank" 
                                                           class="text-brand-600 hover:underline"
                                                           title="<?= $link_title ?>">
                                                            <?= esc($item->nom) ?>
                                                        </a>
                                                    <?php endif; ?>
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
                                            <?php if ($is_word_file): ?>
                                                <span class="text-xs text-blue-500"></span>
                                            <?php elseif ($is_excel_file): ?>
                                                <span class="text-xs text-green-500"></span>
                                            <?php elseif ($is_powerpoint_file): ?>
                                                <span class="text-xs text-orange-500"></span>
                                            <?php endif; ?>
                                        </span>
                                    </td>

                                    <!-- Date d'upload -->
                                    <td class="px-5 py-3 whitespace-nowrap sm:px-6">
                                        <span class="block text-sm">
                                            <?php
                                                $formatter = new IntlDateFormatter(
                                                    'fr_FR',                         // locale
                                                    IntlDateFormatter::LONG,        // date format
                                                    IntlDateFormatter::SHORT,       // time format
                                                    'Europe/Paris',                 // timezone
                                                    IntlDateFormatter::GREGORIAN,   // calendar
                                                    "d MMMM yyyy 'à' HH:mm"         // pattern personnalisé
                                                );

                                                $date = new DateTime($item->date_upload);
                                                echo $formatter->format($date);
                                            ?>
                                        </span>
                                    </td>
                                    
                                    <!-- Colonne Actions -->
                                    <td class="px-5 py-3 whitespace-nowrap sm:px-6">
                                        <div x-data="{ open: false }" class="relative">
                                            <button @click="open = !open" class="text-gray-500 dark:text-gray-400">
                                                <svg class="fill-current" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M5.99902 10.245C6.96552 10.245 7.74902 11.0285 7.74902 11.995V12.005C7.74902 12.9715 6.96552 13.755 5.99902 13.755C5.03253 13.755 4.24902 12.9715 4.24902 12.005V11.995C4.24902 11.0285 5.03253 10.245 5.99902 10.245ZM17.999 10.245C18.9655 10.245 19.749 11.0285 19.749 11.995V12.005C19.749 12.9715 18.9655 13.755 17.999 13.755C17.0325 13.755 16.249 12.9715 16.249 12.005V11.995C16.249 11.0285 17.0325 10.245 17.999 10.245ZM13.749 11.995C13.749 11.0285 12.9655 10.245 11.999 10.245C11.0325 10.245 10.249 11.0285 10.249 11.995V12.005C10.249 12.9715 11.0325 13.755 11.999 13.755C12.9655 13.755 13.749 12.9715 13.749 12.005V11.995Z" fill=""></path>
                                                </svg>
                                            </button>
                                            
                                            <!-- Menu déroulant -->
                                            <div x-show="open" @click.outside="open = false" class="shadow-theme-lg dark:bg-gray-dark fixed w-40 space-y-1 rounded-2xl border border-gray-200 bg-white p-2 dark:border-gray-800" style="position: absolute; top: 20px; right: 0; z-index: 999;">
                                                <?php if ($view_method === 'word'): ?>
                                                    <button onclick="openWordDocument('<?= $item->id ?>', '<?= esc($item->nom) ?>')" 
                                                        class="text-theme-xs flex w-full rounded-lg px-3 py-2 text-left font-medium text-gray-500 hover:bg-gray-100">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="mr-2">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"></path>
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                        </svg>
                                                        Voir le document
                                                    </button>
                                                <?php elseif ($view_method === 'excel'): ?>
                                                    <button onclick="openExcelDocument('<?= $item->id ?>', '<?= esc($item->nom) ?>')" 
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
                <!-- Recherche spécifique aux documents -->
                <!-- <div class="mb-6">
                    <form method="GET" action="<?= ROOT ?>/document" class="flex gap-3">
                        <input type="hidden" name="dossier_id" value="<?= $dossier_courant->id ?? '' ?>">
                        <input 
                            type="text" 
                            name="search" 
                            value="<?= htmlspecialchars($_GET['search'] ?? '') ?>" 
                            placeholder="Rechercher dans les documents..." 
                            class="flex-1 h-11 rounded-lg border border-gray-300 px-4 text-sm focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10"
                        />
                        <button type="submit" class="h-11 px-6 bg-brand-500 text-white rounded-lg hover:bg-brand-600">
                            Rechercher
                        </button>
                        <?php if (!empty($_GET['search'])): ?>
                            <a href="<?= ROOT ?>/document<?= isset($dossier_courant) ? '?dossier_id=' . $dossier_courant->id : '' ?>" class="h-11 px-6 bg-gray-500 text-white rounded-lg hover:bg-gray-600 flex items-center">
                                ✕
                            </a>
                        <?php endif; ?>
                    </form>
                </div> -->
            </div>
            <!-- Affichage de la liste des dossiers -->
            <!-- Affichage de la liste des dossiers -->
            <div class="space-y-5 sm:space-y-6">
                <?php if (!empty($dossiers)): ?>
                    <div class="dossier-grid">
                        <?php foreach ($dossiers as $dossier_item): ?>
                            <div class="dossier-card" onclick="window.location='<?= ROOT ?>/document?dossier_id=<?= $dossier_item->id ?>'">
                                <div class="flex items-center justify-between">
                                    <div class="dossier-icon">
                                        <svg width="36" height="36" viewBox="0 0 36 36" fill="none" xmlns="http://www.w3.org/2000/svg" style="width: 100%;height:100%;">
                                            <path fill-rule="evenodd" clip-rule="evenodd" d="M13.3986 4.40674C12.9265 3.77722 12.1855 3.40674 11.3986 3.40674H2.5C1.11929 3.40674 0 4.52602 0 5.90674V30.0959C0 31.4766 1.11929 32.5959 2.5 32.5959H33.5C34.8807 32.5959 36 31.4766 36 30.0959V11.7446C36 10.3639 34.8807 9.24458 33.5 9.24458H18.277C17.4901 9.24458 16.7492 8.87409 16.277 8.24458L13.3986 4.40674Z" fill="url(#paint0_linear_2816_28044)"></path>
                                            <defs>
                                                <linearGradient id="paint0_linear_2816_28044" x1="18" y1="3.40674" x2="18" y2="32.5959" gradientUnits="userSpaceOnUse">
                                                    <stop stop-color="#FFDC78"></stop>
                                                    <stop offset="1" stop-color="#FBBC1A"></stop>
                                                </linearGradient>
                                            </defs>
                                        </svg>
                                    </div>

                                    <!-- État du dossier -->
                                    <?php
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
                                        <?= $dossier_item->nb_documents ?>&nbsp;fichier(s)
                                    </div>
                                    <div class="dossier-date">
                                        
                                        <?php if ($dossier_item->type_acces == 'interne'): ?>
                                            <span class="inline-flex items-center rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                📁 Interne
                                            </span>
                                        <?php elseif ($dossier_item->type_acces == 'envoye_service'): ?>
                                            <span class="inline-flex items-center rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                🏢 Reçu (Service)
                                            </span>
                                        <?php elseif ($dossier_item->type_acces == 'envoye_role'): ?>
                                            <span class="inline-flex items-center rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                                <!-- 👤 Reçu (fonction: <?= esc($dossier_item->role_service) ?>) -->
                                                Reçu (fonction)
                                            </span>
                                        <?php else: ?>
                                            <span class="inline-flex items-center rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                📨 Reçu
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <!-- PAGINATION - Afficher même s'il n'y a qu'une page -->
                    <?php if (isset($pagination) && $pagination['totalDossiers'] > 0): ?>
                        <div class="mt-8 flex items-center justify-between border-t border-gray-200 px-4 py-3 sm:px-6">
                            <!-- Informations sur la pagination -->
                            <div class="flex-1 items-center justify-between w-full">
                                <!-- <div>
                                    <p class="text-sm text-gray-700">
                                        Affichage des dossiers 
                                        <span class="font-medium"><?= $pagination['startIndex'] ?></span>
                                        à 
                                        <span class="font-medium"><?= $pagination['endIndex'] ?></span>
                                        sur 
                                        <span class="font-medium"><?= $pagination['totalDossiers'] ?></span>
                                        résultats
                                    </p>
                                </div> -->
                                
                                <!-- Navigation - Afficher seulement si plus d'une page -->
                                <?php if ($pagination['totalPages'] >= 1): ?>
                                    <div class="flex justify-center">
                                        <nav class="isolate inline-flex -space-x-px rounded-md shadow-sm" aria-label="Pagination">
                                            <!-- Bouton Précédent -->
                                            <?php if ($pagination['page'] > 1): ?>
                                                <a href="<?= ROOT ?>/document?page=<?= $pagination['page'] - 1 ?>" 
                                                class="relative inline-flex items-center rounded-l-md px-2 py-2 text-gray-400 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0">
                                                    <span class="sr-only">Précédent</span>
                                                    <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                        <path fill-rule="evenodd" d="M12.79 5.23a.75.75 0 01-.02 1.06L8.832 10l3.938 3.71a.75.75 0 11-1.04 1.08l-4.5-4.25a.75.75 0 010-1.08l4.5-4.25a.75.75 0 011.06.02z" clip-rule="evenodd" />
                                                    </svg>
                                                </a>
                                            <?php else: ?>
                                                <span class="relative inline-flex items-center rounded-l-md px-2 py-2 text-gray-300 ring-1 ring-inset ring-gray-300 cursor-not-allowed">
                                                    <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                        <path fill-rule="evenodd" d="M12.79 5.23a.75.75 0 01-.02 1.06L8.832 10l3.938 3.71a.75.75 0 11-1.04 1.08l-4.5-4.25a.75.75 0 010-1.08l4.5-4.25a.75.75 0 011.06.02z" clip-rule="evenodd" />
                                                    </svg>
                                                </span>
                                            <?php endif; ?>

                                            <!-- Numéros de page -->
                                            <?php for ($i = 1; $i <= $pagination['totalPages']; $i++): ?>
                                                <?php if ($i == $pagination['page']): ?>
                                                    <!-- Page actuelle -->
                                                    <a href="#" aria-current="page" 
                                                    class="relative z-10 inline-flex items-center bg-brand-500 px-4 py-2 text-sm font-semibold text-white focus:z-20 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-brand-600">
                                                        <?= $i ?>
                                                    </a>
                                                <?php elseif ($i == 1 || $i == $pagination['totalPages'] || ($i >= $pagination['page'] - 1 && $i <= $pagination['page'] + 1)): ?>
                                                    <!-- Pages proches -->
                                                    <a href="<?= ROOT ?>/document?page=<?= $i ?>" 
                                                    class="relative inline-flex items-center px-4 py-2 text-sm font-semibold text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0">
                                                        <?= $i ?>
                                                    </a>
                                                <?php elseif ($i == $pagination['page'] - 2 || $i == $pagination['page'] + 2): ?>
                                                    <!-- Points de suspension -->
                                                    <span class="relative inline-flex items-center px-4 py-2 text-sm font-semibold text-gray-700 ring-1 ring-inset ring-gray-300">
                                                        ...
                                                    </span>
                                                <?php endif; ?>
                                            <?php endfor; ?>

                                            <!-- Bouton Suivant -->
                                            <?php if ($pagination['page'] < $pagination['totalPages']): ?>
                                                <a href="<?= ROOT ?>/document?page=<?= $pagination['page'] + 1 ?>" 
                                                class="relative inline-flex items-center rounded-r-md px-2 py-2 text-gray-400 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0">
                                                    <span class="sr-only">Suivant</span>
                                                    <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                        <path fill-rule="evenodd" d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z" clip-rule="evenodd" />
                                                    </svg>
                                                </a>
                                            <?php else: ?>
                                                <span class="relative inline-flex items-center rounded-r-md px-2 py-2 text-gray-300 ring-1 ring-inset ring-gray-300 cursor-not-allowed">
                                                    <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                        <path fill-rule="evenodd" d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z" clip-rule="evenodd" />
                                                    </svg>
                                                </span>
                                            <?php endif; ?>
                                        </nav>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endif; ?>

                <?php else: ?>
                    <div class="p-6 text-center text-gray-500 dark:text-gray-400">
                        Aucun dossier disponible. Créez votre premier dossier en uploadant un document.
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</main>

<!-- Modal for d'ajout d'un Dossier -->
<div x-show="DemandeConges" class="fixed inset-0 flex items-center justify-center p-5 overflow-y-auto z-99999">
    <div class="modal-close-btn fixed inset-0 h-full w-full bg-gray-400/50 backdrop-blur-[32px]"></div>
    <div class="no-scrollbar relative w-full max-w-[700px] overflow-y-auto rounded-3xl bg-white p-4 dark:bg-gray-900 lg:p-11">
        <!-- close btn -->
        <button @click="DemandeConges = false" class="transition-color absolute right-5 top-5 z-999 flex h-11 w-11 items-center justify-center rounded-full bg-gray-100 text-gray-400 hover:bg-gray-200 hover:text-gray-600 dark:bg-gray-700 dark:bg-white/[0.05] dark:text-gray-400 dark:hover:bg-white/[0.07] dark:hover:text-gray-300">
            <svg class="fill-current" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" clip-rule="evenodd" d="M6.04289 16.5418C5.65237 16.9323 5.65237 17.5655 6.04289 17.956C6.43342 18.3465 7.06658 18.3465 7.45711 17.956L11.9987 13.4144L16.5408 17.9565C16.9313 18.347 17.5645 18.347 17.955 17.9565C18.3455 17.566 18.3455 16.9328 17.955 16.5423L13.4129 12.0002L17.955 7.45808C18.3455 7.06756 18.3455 6.43439 17.955 6.04387C17.5645 5.65335 16.9313 5.65335 16.5408 6.04387L11.9987 10.586L7.45711 6.04439C7.06658 5.65386 6.43342 5.65386 6.04289 6.04439C5.65237 6.43491 5.65237 7.06808 6.04289 7.4586L10.5845 12.0002L6.04289 16.5418Z" fill="" />
            </svg>
        </button>
        <div class="pr-14">
            <h4 class="mb-2 text-2xl font-semibold text-gray-800 dark:text-white/90">
                Ajouter un nouveau ossier
            </h4>
            <p class="mb-6 text-sm text-gray-500 dark:text-gray-400 lg:mb-7">
                Téléversez un document et spécifiez son dossier
            </p>
        </div>
        <form method="post" action="<?= ROOT ?>/document" enctype="multipart/form-data">
            <div class="grid grid-cols-1 gap-x-6 gap-y-5 lg:grid-cols-2 mb-6">
                <div class="col-span-2">
                    <div class="submission-container">
                        <div class="form-group file-upload-group">
                            <div class="file-upload-area" id="fileUploadArea">
                                <input type="file" id="fileInput" name="files[]" multiple accept=".jpg,.jpeg,.png,.pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.zip,.rar,.txt,.csv,.html,.htm">
                                <label for="fileInput" class="file-upload-label">
                                    <div class="file-icon-svg" id="defaultFileIcon">
                                        <svg viewBox="0 0 24 24"><path d="M14,2H6A2,2 0 0,0 4,4V20A2,2 0 0,0 6,22H18A2,2 0 0,0 20,20V8L14,2M18,20H6V4H13V9H18V20Z"/></svg>
                                    </div>
                                    <span id="fileLabelText">Glissez-déposez vos fichiers ou cliquez pour sélectionner</span>
                                    <span class="file-requirements">(Formats acceptés: JPG, PNG, PDF, DOC, XLS, PPT, TXT, CSV, HTML, ZIP - Max 10MB par fichier)</span>
                                </label>
                                <div class="file-preview" id="filePreview"></div>
                                <div class="files-count" id="filesCount">Aucun fichier sélectionné</div>
                                <div class="upload-status" id="uploadStatus"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-span-2">
                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                        Nom du dossier
                    </label>
                    <input type="text" name="dossier_name" placeholder="Nom du dossier (ex: Factures, Contrats, etc.)" class="dark:bg-dark-900 h-11 w-full appearance-none rounded-lg border border-gray-300 bg-transparent bg-none px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800">
                    <p class="text-theme-xs text-error-500 mt-1.5"></p>
                </div>
            </div>

            <div class="flex justify-end gap-3">
                <button type="button" @click="DemandeConges = false" class="flex w-full justify-center rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.03] sm:w-auto">Annuler</button>
                <button type="submit" class="flex w-full justify-center rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-brand-600 sm:w-auto">Téléverser le document</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal for d'ajout d'un document (dans le dossier existant) -->
<div x-show="DemandeFichier" class="fixed inset-0 flex items-center justify-center p-5 overflow-y-auto z-99999">
    <!-- ... contenu existant ... -->
    <form method="post" action="<?= ROOT ?>/document" enctype="multipart/form-data">
        <!-- Champ hidden pour le dossier_id -->
        <input type="hidden" name="dossier_id" value="<?= $dossier_courant->id ?? '' ?>">
        
        <div class="grid grid-cols-1 gap-x-6 gap-y-5 lg:grid-cols-2 mb-6">
            <div class="col-span-2">
                <div class="submission-container">
                    <div class="form-group file-upload-group">
                        <div class="file-upload-area" id="fileUploadAreaExisting">
                            <!-- AJOUT DE multiple DANS L'INPUT -->
                            <input type="file" id="fileInputExisting" name="files[]" multiple accept=".jpg,.jpeg,.png,.pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.zip,.rar,.txt,.csv,.html,.htm">
                            <label for="fileInputExisting" class="file-upload-label">
                                <div class="file-icon-svg" id="defaultFileIconExisting">
                                    <svg viewBox="0 0 24 24"><path d="M14,2H6A2,2 0 0,0 4,4V20A2,2 0 0,0 6,22H18A2,2 0 0,0 20,20V8L14,2M18,20H6V4H13V9H18V20Z"/></svg>
                                </div>
                                <span id="fileLabelTextExisting">Glissez-déposez vos fichiers ou cliquez pour sélectionner</span>
                                <span class="file-requirements">(Formats acceptés: JPG, PDF, DOC, DOCX, XLS, ZIP - Max 10MB par fichier)</span>
                            </label>
                            <div class="file-preview" id="filePreviewExisting"></div>
                            <div class="files-count" id="filesCountExisting">Aucun fichier sélectionné</div>
                            <div class="upload-status" id="uploadStatusExisting"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex justify-end gap-3">
            <button type="button" @click="DemandeFichier = false" class="flex w-full justify-center rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.03] sm:w-auto">Annuler</button>
            <button type="submit" class="flex w-full justify-center rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-brand-600 sm:w-auto">Téléverser les documents</button>
        </div>
    </form>
</div>

<script>
    // ========== FONCTIONS POUR WORD ==========
    // ========== FONCTIONS POUR WORD ==========
    function openWordDocument(documentId, filename) {
        const fileUrl = '<?= ROOT ?>/document/servir_fichier/' + documentId;
        const features = 'width=1200,height=800,scrollbars=yes,resizable=yes,left=100,top=100';
        const newWindow = window.open('', `word_${Date.now()}`, features);

        newWindow.document.write(`
            <!DOCTYPE html>
            <html>
            <head>
                <title>${filename} - Word Viewer</title>
                <script src="https://cdnjs.cloudflare.com/ajax/libs/mammoth/1.6.0/mammoth.browser.min.js"><\/script>
                <style>
                    body { font-family: Arial, sans-serif; margin: 0; padding: 20px; background: #f8fafc; }
                    .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; padding: 15px; background: white; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
                    .content { background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); line-height: 1.6; min-height: 400px; }
                    table { border-collapse: collapse; width: 100%; margin: 1em 0; }
                    table, th, td { border: 1px solid #ddd; padding: 8px; }
                    .loading { text-align: center; padding: 50px; color: #6b7280; }
                    .error { text-align: center; padding: 50px; color: #ef4444; }
                    .close-btn { padding: 10px 20px; background: #ef4444; color: white; border: none; border-radius: 6px; cursor: pointer; font-weight: 500; }
                    .download-btn { padding: 10px 20px; background: #3b82f6; color: white; border: none; border-radius: 6px; cursor: pointer; font-weight: 500; margin-right: 10px; }
                    .spinner { border: 4px solid #f3f4f6; border-top: 4px solid #3b82f6; border-radius: 50%; width: 40px; height: 40px; animation: spin 1s linear infinite; margin: 0 auto 20px; }
                    @keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }
                    .content {
                        background: white;
                        padding: 30px;
                        border-radius: 8px;
                        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
                        line-height: 1.6;
                        min-height: 400px;
                        text-align: left;
                        word-wrap: break-word;
                    }

                    p.align-center { text-align: center; }
                    p.align-right { text-align: right; }
                    p.align-justify { text-align: justify; }

                </style>
            </head>
            <body>
                <div class="header">
                    <h1 style="margin: 0; color: #1f2937;">📄 ${filename}</h1>
                    <div>
                        <button class="download-btn" onclick="window.open('${fileUrl}')">📥 Télécharger</button>
                        <button class="close-btn" onclick="window.close()">✕ Fermer</button>
                    </div>
                </div>
                <div id="content" class="loading">
                    <div class="spinner"></div>
                    <p>Chargement du document Word...</p>
                </div>
                <script>
                    console.log('Début du chargement Word...');
                    loadWordDocument('${fileUrl}');

                    async function loadWordDocument(fileUrl) {
                        try {
                            console.log('Tentative de chargement:', fileUrl);
                            const response = await fetch(fileUrl);
                            if (!response.ok) throw new Error('Erreur HTTP: ' + response.status);

                            const arrayBuffer = await response.arrayBuffer();
                            console.log('Fichier chargé, conversion en cours...');

                            const result = await mammoth.convertToHtml({ arrayBuffer });
                            console.log('Conversion réussie');

                            document.getElementById('content').innerHTML = 
                                '<div class="content">' + result.value + '</div>';
                        } catch (error) {
                            console.error('Erreur:', error);
                            document.getElementById('content').innerHTML = \`
                                <div class="error">
                                    <h3>❌ Erreur de chargement</h3>
                                    <p>Impossible d'afficher le document Word.</p>
                                    <small>\${error.message}</small><br><br>
                                    <button class="download-btn" onclick="window.open('\${fileUrl}')">📥 Télécharger le document</button>
                                </div>
                            \`;
                        }
                    }
                <\/script>
            </body>
            </html>
        `);
    }


    // ========== FONCTIONS POUR EXCEL ==========
    // ========== FONCTIONS POUR EXCEL ==========
    function openExcelDocument(documentId, filename) {
        const fileUrl = '<?= ROOT ?>/document/servir_fichier/' + documentId;
        const features = 'width=1400,height=800,scrollbars=yes,resizable=yes,left=100,top=100';
        const newWindow = window.open('', `excel_${Date.now()}`, features);

        newWindow.document.write(`
            <!DOCTYPE html>
            <html>
            <head>
                <title>${filename} - Excel Viewer</title>
                <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"><\/script>
                <style>
                    body { font-family: Arial, sans-serif; margin: 0; padding: 20px; background: #f8fafc; }
                    .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; padding: 15px; background: white; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
                    .sheet { margin-bottom: 30px; background: white; padding: 0; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); overflow: hidden; }
                    .sheet-title { background: #10b981; color: white; padding: 15px; margin: 0; font-weight: bold; font-size: 16px; }
                    table { border-collapse: collapse; width: 100%; font-size: 14px; }
                    th { background: #f8fafc; border: 1px solid #e2e8f0; padding: 10px; font-weight: 600; text-align: left; }
                    td { border: 1px solid #e2e8f0; padding: 10px; min-width: 80px; }
                    tr:nth-child(even) { background: #f8fafc; }
                    .loading { text-align: center; padding: 50px; color: #6b7280; }
                    .error { text-align: center; padding: 50px; color: #ef4444; }
                    .close-btn { padding: 10px 20px; background: #ef4444; color: white; border: none; border-radius: 6px; cursor: pointer; font-weight: 500; }
                    .download-btn { padding: 10px 20px; background: #10b981; color: white; border: none; border-radius: 6px; cursor: pointer; font-weight: 500; margin-right: 10px; }
                    .spinner { border: 4px solid #f3f4f6; border-top: 4px solid #10b981; border-radius: 50%; width: 40px; height: 40px; animation: spin 1s linear infinite; margin: 0 auto 20px; }
                    @keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }
                </style>
            </head>
            <body>
                <div class="header">
                    <h1 style="margin: 0; color: #1f2937;">📊 ${filename}</h1>
                    <div>
                        <button class="download-btn" onclick="window.open('${fileUrl}')">📥 Télécharger</button>
                        <button class="close-btn" onclick="window.close()">✕ Fermer</button>
                    </div>
                </div>
                <div id="content" class="loading">
                    <div class="spinner"></div>
                    <p>Chargement du fichier Excel...</p>
                </div>

                <script>
                    console.log('Début du chargement Excel...');
                    loadExcelDocument('${fileUrl}');

                    async function loadExcelDocument(fileUrl) {
                        try {
                            console.log('Tentative de chargement:', fileUrl);
                            const response = await fetch(fileUrl);
                            if (!response.ok) throw new Error('Erreur HTTP: ' + response.status);

                            const arrayBuffer = await response.arrayBuffer();
                            console.log('Fichier chargé, traitement en cours...');

                            const data = new Uint8Array(arrayBuffer);
                            const workbook = XLSX.read(data, { type: 'array' });
                            console.log('Fichier Excel chargé avec', workbook.SheetNames.length, 'feuilles.');

                            let html = '';
                            workbook.SheetNames.forEach(sheetName => {
                                const worksheet = workbook.Sheets[sheetName];
                                const sheetHtml = XLSX.utils.sheet_to_html(worksheet, {
                                    editable: false,
                                    header: '',
                                    raw: true
                                });
                                html += \`
                                    <div class="sheet">
                                        <div class="sheet-title">📊 \${sheetName}</div>
                                        <div style="overflow-x:auto; padding:20px;">\${sheetHtml}</div>
                                    </div>\`;
                            });

                            document.getElementById('content').innerHTML = html;
                            console.log('Affichage terminé avec succès.');

                        } catch (error) {
                            console.error('Erreur:', error);
                            document.getElementById('content').innerHTML = \`
                                <div class="error">
                                    <h3>❌ Erreur de chargement</h3>
                                    <p>Impossible d'afficher le fichier Excel.</p>
                                    <small>\${error.message}</small><br><br>
                                    <button class="download-btn" onclick="window.open('\${fileUrl}')">📥 Télécharger le fichier</button>
                                </div>
                            \`;
                        }
                    }
                <\/script>
            </body>
            </html>
        `);
    }

    // ========== FONCTIONS UTILITAIRES ==========
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

    function formatFileSize(bytes) {
        if (bytes === 0 || bytes === undefined) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    }
</script>

<!-- LA GESTION POUR AJOUTER UN FICHIER DANS UN DOSSIER EXISTANTES -->
<script>
    // ========== INITIALISATION DES DEUX MODALS ==========
    document.addEventListener('DOMContentLoaded', function() {
        // Initialisation pour le modal de nouveau dossier
        initializeFileUpload('fileUploadAreaNew', 'fileInputNew', 'filePreviewNew', 'filesCountNew', 'uploadStatusNew', 'fileLabelTextNew', 'defaultFileIconNew');
        
        // Initialisation pour le modal de dossier existant
        initializeFileUpload('fileUploadAreaExisting', 'fileInputExisting', 'filePreviewExisting', 'filesCountExisting', 'uploadStatusExisting', 'fileLabelTextExisting', 'defaultFileIconExisting');
    });

    function initializeFileUpload(uploadAreaId, fileInputId, previewId, countId, statusId, labelTextId, fileIconId) {
        const fileUploadArea = document.getElementById(uploadAreaId);
        const fileInput = document.getElementById(fileInputId);
        const filePreview = document.getElementById(previewId);
        const filesCount = document.getElementById(countId);
        const uploadStatus = document.getElementById(statusId);
        const fileLabelText = document.getElementById(labelTextId);
        const defaultFileIcon = document.getElementById(fileIconId);
        
        let selectedFiles = [];

        if (!fileUploadArea || !fileInput) return;

        // Événements de drag & drop
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            fileUploadArea.addEventListener(eventName, preventDefaults, false);
        });

        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }

        ['dragenter', 'dragover'].forEach(eventName => {
            fileUploadArea.addEventListener(eventName, () => {
                fileUploadArea.classList.add('drag-over');
            }, false);
        });

        ['dragleave', 'drop'].forEach(eventName => {
            fileUploadArea.addEventListener(eventName, () => {
                fileUploadArea.classList.remove('drag-over');
            }, false);
        });

        fileUploadArea.addEventListener('drop', handleDrop, false);
        fileInput.addEventListener('change', handleFileSelect, false);

        function handleDrop(e) {
            const dt = e.dataTransfer;
            const files = dt.files;
            handleFiles(files);
        }

        function handleFileSelect(e) {
            const files = e.target.files;
            handleFiles(files);
        }

        function handleFiles(files) {
            selectedFiles = Array.from(files);
            updateFilePreview();
            updateFilesCount();
            
            if (selectedFiles.length > 0) {
                fileUploadArea.classList.add('has-files');
                fileLabelText.textContent = `${selectedFiles.length} fichier(s) sélectionné(s)`;
                defaultFileIcon.style.color = '#10b981';
            } else {
                fileUploadArea.classList.remove('has-files');
                fileLabelText.textContent = 'Glissez-déposez vos fichiers ou cliquez pour sélectionner';
                defaultFileIcon.style.color = '#9ca3af';
            }
        }

        function updateFilePreview() {
            filePreview.innerHTML = '';
            
            selectedFiles.forEach((file, index) => {
                const fileItem = document.createElement('div');
                fileItem.className = 'file-preview-item';
                
                const fileExtension = file.name.split('.').pop().toLowerCase();
                const fileIcon = getFileIconSVG(fileExtension);
                
                fileItem.innerHTML = `
                    ${fileIcon}
                    <div class="file-preview-name">${file.name}</div>
                    <div class="file-preview-size">${formatFileSize(file.size)}</div>
                    <div class="remove-file" onclick="removeFileFromPreview(${index}, '${uploadAreaId}', '${fileInputId}', '${previewId}', '${countId}', '${labelTextId}', '${fileIconId}')">×</div>
                `;
                
                filePreview.appendChild(fileItem);
            });
        }

        function updateFilesCount() {
            if (selectedFiles.length === 0) {
                filesCount.textContent = 'Aucun fichier sélectionné';
            } else {
                filesCount.textContent = `${selectedFiles.length} fichier(s) sélectionné(s) - ${formatTotalSize(selectedFiles)}`;
            }
        }

        function formatTotalSize(files) {
            const totalSize = files.reduce((total, file) => total + file.size, 0);
            return formatFileSize(totalSize);
        }
    }

    // Fonction pour supprimer un fichier de la prévisualisation
    function removeFileFromPreview(index, uploadAreaId, fileInputId, previewId, countId, labelTextId, fileIconId) {
        const fileUploadArea = document.getElementById(uploadAreaId);
        const fileInput = document.getElementById(fileInputId);
        const fileLabelText = document.getElementById(labelTextId);
        const defaultFileIcon = document.getElementById(fileIconId);
        
        // Créer un nouveau FileList sans le fichier supprimé
        const dt = new DataTransfer();
        const files = Array.from(fileInput.files);
        
        files.forEach((file, i) => {
            if (i !== index) {
                dt.items.add(file);
            }
        });
        
        fileInput.files = dt.files;
        
        // Réinitialiser l'interface
        initializeFileUpload(uploadAreaId, fileInputId, previewId, countId, 'uploadStatusExisting', labelTextId, fileIconId);
        
        if (dt.files.length === 0) {
            fileUploadArea.classList.remove('has-files');
            fileLabelText.textContent = 'Glissez-déposez vos fichiers ou cliquez pour sélectionner';
            defaultFileIcon.style.color = '#9ca3af';
        }
    }

    // Fonction utilitaire pour les icônes de fichiers
    function getFileIconSVG(extension) {
        const icons = {
            pdf: '<svg class="file-preview-icon" viewBox="0 0 24 24"><path fill="currentColor" d="M20 2H8c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm-8.5 7.5c0 .83-.67 1.5-1.5 1.5H9v2H7.5V7H10c.83 0 1.5.67 1.5 1.5v1zm5 2c0 .83-.67 1.5-1.5 1.5h-2.5V7H15c.83 0 1.5.67 1.5 1.5v3zm4-3H19v1h1.5V11H19v2h-1.5V7h3v1.5zM9 9.5h1v-1H9v1zM4 6H2v14c0 1.1.9 2 2 2h14v-2H4V6zm10 5.5h1v-3h-1v3z"/></svg>',
            doc: '<svg class="file-preview-icon" viewBox="0 0 24 24"><path fill="currentColor" d="M14,2H6A2,2 0 0,0 4,4V20A2,2 0 0,0 6,22H18A2,2 0 0,0 20,20V8L14,2M18,20H6V4H13V9H18V20Z"/></svg>',
            docx: '<svg class="file-preview-icon" viewBox="0 0 24 24"><path fill="currentColor" d="M14,2H6A2,2 0 0,0 4,4V20A2,2 0 0,0 6,22H18A2,2 0 0,0 20,20V8L14,2M18,20H6V4H13V9H18V20Z"/></svg>',
            xls: '<svg class="file-preview-icon" viewBox="0 0 24 24"><path fill="currentColor" d="M14,2H6A2,2 0 0,0 4,4V20A2,2 0 0,0 6,22H18A2,2 0 0,0 20,20V8L14,2M18,20H6V4H13V9H18V20Z"/></svg>',
            xlsx: '<svg class="file-preview-icon" viewBox="0 0 24 24"><path fill="currentColor" d="M14,2H6A2,2 0 0,0 4,4V20A2,2 0 0,0 6,22H18A2,2 0 0,0 20,20V8L14,2M18,20H6V4H13V9H18V20Z"/></svg>',
            jpg: '<svg class="file-preview-icon" viewBox="0 0 24 24"><path fill="currentColor" d="M14,2H6A2,2 0 0,0 4,4V20A2,2 0 0,0 6,22H18A2,2 0 0,0 20,20V8L14,2M18,20H6V4H13V9H18V20Z"/></svg>',
            jpeg: '<svg class="file-preview-icon" viewBox="0 0 24 24"><path fill="currentColor" d="M14,2H6A2,2 0 0,0 4,4V20A2,2 0 0,0 6,22H18A2,2 0 0,0 20,20V8L14,2M18,20H6V4H13V9H18V20Z"/></svg>',
            png: '<svg class="file-preview-icon" viewBox="0 0 24 24"><path fill="currentColor" d="M14,2H6A2,2 0 0,0 4,4V20A2,2 0 0,0 6,22H18A2,2 0 0,0 20,20V8L14,2M18,20H6V4H13V9H18V20Z"/></svg>'
        };
        
        return icons[extension] || '<svg class="file-preview-icon" viewBox="0 0 24 24"><path fill="currentColor" d="M14,2H6A2,2 0 0,0 4,4V20A2,2 0 0,0 6,22H18A2,2 0 0,0 20,20V8L14,2M18,20H6V4H13V9H18V20Z"/></svg>';
    }

    function formatFileSize(bytes) {
        if (bytes === 0 || bytes === undefined) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    }
</script>


<?php $this->view("footer"); ?>