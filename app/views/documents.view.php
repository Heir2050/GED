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
        
        <?php if (isset($dossier_courant)): ?>
            <!-- Affichage des documents d'un dossier spécifique -->
            <a href="<?= ROOT ?>/document" class="back-button">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16" class="mr-2">
                    <path fill-rule="evenodd" d="M12 8a.5.5 0 0 1-.5.5H5.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L5.707 7.5H11.5a.5.5 0 0 1 .5.5z"/>
                </svg>
                Retour aux dossiers
            </a>
            
            <h3 class="text-lg font-semibold mb-4">Documents du dossier: <?= esc($dossier_courant->nom) ?></h3>
            
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
                                <tr>
                                    <!-- Fichier -->
                                    <td class="py-3 px-5 whitespace-nowrap sm:px-5">
                                        <div class="flex items-center gap-3">
                                            <div class="h-8 w-8">
                                                <?= getFileIcon($item->nom_stockage) ?>
                                            </div>
                                            <div>
                                                <?php if (!empty($item->nom_stockage)): ?>
                                                    <a href="<?= ROOT . '/' . $dossier_courant->chemin . $item->nom_stockage ?>" target="_blank" class="text-brand-600 hover:underline">
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
                                            <?= !empty($item->type) ? esc($item->type) : 'Inconnu' ?>
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
                                                <a href="<?= ROOT . '/' . $dossier_courant->chemin . $item->nom_stockage ?>" target="_blank" class="text-theme-xs flex w-full rounded-lg px-3 py-2 text-left font-medium text-gray-500 hover:bg-gray-100">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="mr-2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"></path>
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    </svg>
                                                    Voir
                                                </a>
                                                <a href="<?= ROOT . '/' . $dossier_courant->chemin . $item->nom_stockage ?>" download class="text-theme-xs flex w-full rounded-lg px-3 py-2 text-left font-medium text-gray-500 hover:bg-gray-100">
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
            <!-- Affichage de la liste des dossiers -->
            <div class="space-y-5 sm:space-y-6">
                <?php if (!empty($dossiers)): ?>
                    <div class="dossier-grid">
                        <?php foreach ($dossiers as $dossier_item): ?>
                            <div class="dossier-card" onclick="window.location='<?= ROOT ?>/document?dossier_id=<?= $dossier_item->id ?>'">
                                <div class="dossier-icon">
                                    <svg viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M20 6h-8l-2-2H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V8c0-1.1-.9-2-2-2zm0 12H4V6h5.17l2 2H20v10z"/>
                                    </svg>
                                </div>
                                <div class="dossier-name"><?= esc($dossier_item->nom) ?></div>
                                <div class="dossier-info">
                                    <div class="document-count">
                                        <?= $dossier_item->nb_documents ?> fichier(s)
                                    </div>
                                    <div class="dossier-date">
                                        <?= date('d/m/Y', strtotime($dossier_item->date_creation)) ?>
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

<!-- Modal for d'ajout d'un document -->
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
                Ajouter un nouveau document
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
                                <input type="file" id="fileInput" name="files[]" multiple accept=".jpg,.jpeg,.png,.pdf,.doc,.docx,.xls,.xlsx,.zip,.rar">
                                <label for="fileInput" class="file-upload-label">
                                    <div class="file-icon-svg" id="defaultFileIcon">
                                        <svg viewBox="0 0 24 24"><path d="M14,2H6A2,2 0 0,0 4,4V20A2,2 0 0,0 6,22H18A2,2 0 0,0 20,20V8L14,2M18,20H6V4H13V9H18V20Z"/></svg>
                                    </div>
                                    <span id="fileLabelText">Glissez-déposez vos fichiers ou cliquez pour sélectionner</span>
                                    <span class="file-requirements">(Formats acceptés: JPG, PNG, PDF, DOC, XLS, ZIP - Max 10MB par fichier)</span>
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

<?php $this->view("footer"); ?>