<?php $this->view('head'); 

$url1 = URL(0);
$url2 = URL(1);
$url3 = URL(2);

?>

<style>
        .file-upload-group {
            margin-bottom: 30px;
        }

        .file-upload-area {
            border: 2px dashed var(--border-color);
            border-radius: 6px;
            padding: 25px;
            text-align: center;
            transition: all 0.3s;
            position: relative;
        }

        .file-upload-area.active {
            border-color: var(--primary-color);
            background-color: rgba(74, 107, 255, 0.05);
        }

        #fileInput {
            display: none;
        }

        .file-upload-label {
            display: flex;
            flex-direction: column;
            align-items: center;
            cursor: pointer;
        }

        .file-upload-label i {
            font-size: 40px;
            color: var(--primary-color);
            margin-bottom: 10px;
        }

        .file-upload-label span {
            display: block;
            margin-bottom: 5px;
            color: var(--dark-gray);
        }

        .file-requirements {
            font-size: 13px;
            color: var(--secondary-color);
        }

        .file-preview {
            margin-top: 15px;
        }

        .file-preview-content {
            display: flex;
            align-items: center;
            background-color: var(--light-gray);
            padding: 10px;
            border-radius: 4px;
        }

        .file-preview-content i {
            font-size: 24px;
            margin-right: 10px;
            color: var(--primary-color);
        }

        .file-info {
            flex-grow: 1;
        }

        .file-name {
            font-weight: 600;
            margin-bottom: 3px;
        }

        .file-size {
            font-size: 13px;
            color: var(--secondary-color);
        }

        .remove-file {
            background: none;
            border: none;
            color: var(--error-color);
            cursor: pointer;
            font-size: 18px;
        }

        .form-actions {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-top: 30px;
        }

        .primary-btn {
            background-color: var(--primary-color);
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .primary-btn:hover {
            background-color: #3a5bef;
        }

        .secondary-btn {
            background-color: var(--secondary-color);
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .secondary-btn:hover {
            background-color: #5a6268;
        }

        .status-message {
            padding: 15px;
            border-radius: 4px;
            margin-bottom: 20px;
        }

        .status-message.success {
            background-color: rgba(40, 167, 69, 0.1);
            color: var(--success-color);
        }

        .status-message.error {
            background-color: rgba(220, 53, 69, 0.1);
            color: var(--error-color);
        }




        
        /* Autres styles */
        .file-upload-area {
            border: 2px dashed #ced4da;
            border-radius: 6px;
            padding: 25px;
            text-align: center;
            transition: all 0.3s;
            position: relative;
        }

        .file-upload-area.active {
            border-color: #4a6bff;
            background-color: rgba(74, 107, 255, 0.05);
        }

        #fileInput {
            display: none;
        }

        .file-upload-label {
            display: flex;
            flex-direction: column;
            align-items: center;
            cursor: pointer;
        }

        .file-upload-label i {
            font-size: 40px;
            color: #4a6bff;
            margin-bottom: 10px;
        }

        .file-upload-label span {
            display: block;
            margin-bottom: 5px;
            color: #343a40;
        }

        .file-requirements {
            font-size: 13px;
            color: #6c757d;
        }

        .file-preview {
            margin-top: 15px;
            display: none;
        }

        .file-preview-content {
            display: flex;
            align-items: center;
            background-color: #f8f9fa;
            padding: 10px;
            border-radius: 4px;
        }

        .file-preview-content i {
            font-size: 24px;
            margin-right: 10px;
            color: #4a6bff;
        }

        .file-info {
            flex-grow: 1;
        }

        .file-name {
            font-weight: 600;
            margin-bottom: 3px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 300px;
        }

        .file-size {
            font-size: 13px;
            color: #6c757d;
        }

        .remove-file {
            background: none;
            border: none;
            color: #dc3545;
            cursor: pointer;
            font-size: 18px;
            margin-left: 10px;
        }


        /* FILES */
        .file-upload-area {
            border: 2px dashed #ced4da;
            border-radius: 6px;
            padding: 25px;
            text-align: center;
            transition: all 0.3s;
            position: relative;
        }

        .file-upload-area.active {
            border-color: #4a6bff;
            background-color: rgba(74, 107, 255, 0.05);
        }

        #fileInput {
            display: none;
        }

        .file-upload-label {
            display: flex;
            flex-direction: column;
            align-items: center;
            cursor: pointer;
        }

        .file-icon-svg {
            width: 40px;
            height: 40px;
            margin-bottom: 10px;
        }

        .file-icon-svg svg {
            width: 100%;
            height: 100%;
        }

        .file-upload-label span {
            display: block;
            margin-bottom: 5px;
            color: #343a40;
        }

        .file-requirements {
            font-size: 13px;
            color: #6c757d;
        }

        .file-preview {
            margin-top: 15px;
            display: none;
        }

        .file-preview-content {
            display: flex;
            align-items: center;
            background-color: #f8f9fa;
            padding: 10px;
            border-radius: 4px;
        }

        .file-preview-content .file-icon-svg {
            width: 24px;
            height: 24px;
            margin-right: 10px;
        }

        .file-info {
            flex-grow: 1;
        }

        .file-name {
            font-weight: 600;
            margin-bottom: 3px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 300px;
        }

        .file-size {
            font-size: 13px;
            color: #6c757d;
        }

        .remove-file {
            background: none;
            border: none;
            color: #dc3545;
            cursor: pointer;
            font-size: 18px;
            margin-left: 10px;
        }

</style>
<style>
    /* Animation pour l'apparition des champs */
    .hiddens {
        display: none;
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    [class*="-fields"]:not(.hidden) {
        display: grid;
        animation: fadeIn 0.3s ease forwards;
    }

    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    /* Style pour les champs obligatoires */
    .required-field::after {
        content: " *";
        color: red;
    }
</style>
<?php if ($action == "add") : ?>


    
    <main>
        <div class="p-4 mx-auto max-w-(--breakpoint-2xl) md:p-6">
            <!-- Breadcrumb Start -->
            <div x-data="{ pageName: `Upload Files` }">
                <div class="flex flex-wrap items-center justify-between gap-3 mb-6">
                    <h2 class="text-xl font-semibold text-gray-800 dark:text-white/90" x-text="pageName">Upload Files</h2>
                    <nav>
                        <a href="<?= $_SERVER['HTTP_REFERER'] ?? ROOT ?>" class="flex w-full rounded-lg px-3 py-2 text-left font-medium " style="color:#fff;background:#1d2939;">
                            Annuler
                        </a>
                    </nav>
                </div>
            </div>

            <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
                <div class="p-5 space-y-6 dark:border-gray-800 sm:p-6">
                    <form id="submissionForm" method="post" enctype="multipart/form-data">
                        <div class="submission-container">
                            <div class="form-group file-upload-group">
                                <div class="file-upload-area" id="fileUploadArea">
                                    <input type="file" id="fileInput" name="file_path" accept=".jpg,.jpeg,.png,.pdf,.doc,.docx,.xls,.xlsx,.zip,.rar">
                                    <label for="fileInput" class="file-upload-label">
                                        <div class="file-icon-svg" id="defaultFileIcon">
                                            <svg viewBox="0 0 24 24"><path d="M14,2H6A2,2 0 0,0 4,4V20A2,2 0 0,0 6,22H18A2,2 0 0,0 20,20V8L14,2M18,20H6V4H13V9H18V20Z"/></svg>
                                        </div>
                                        <span id="fileLabelText">Glissez-déposez votre fichier ou cliquez pour sélectionner</span>
                                        <span class="file-requirements">(Formats acceptés: JPG, PNG, PDF, DOC, XLS, ZIP - Max 10MB)</span>
                                    </label>
                                    <div class="file-preview" id="filePreview"></div>
                                </div>
                            </div>
                        </div>
                        <!-- Champ Fichier -->
                        <div class="-mx-2.5 flex flex-wrap gap-y-5">
                            <div class="w-full px-2.5 xl:w-1/2">
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                Type de fichier *
                                </label>
                                <div class="relative z-20 bg-transparent">
                                    <select name="type" class="dark:bg-dark-900 z-20 h-11 w-full appearance-none rounded-lg border border-gray-300 bg-transparent bg-none px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800" :class="isOptionSelected &amp;&amp; 'text-gray-500 dark:text-gray-400'" @change="isOptionSelected = true">
                                        <option value="" <?= old_value('type') === '' ? 'selected' : '' ?> class="text-gray-500 dark:bg-gray-900 dark:text-gray-400">
                                            Sélectionnez le type
                                        </option>
                                        <option value="recu" <?= old_value('type') === 'recu' ? 'selected' : '' ?> class="text-gray-500 dark:bg-gray-900 dark:text-gray-400">
                                            Reçu
                                        </option>
                                        <option value="envoye" <?= old_value('type') === 'envoye' ? 'selected' : '' ?> class="text-gray-500 dark:bg-gray-900 dark:text-gray-400">
                                            Envoye
                                        </option>
                                        <option value="interne" <?= old_value('type') === 'interne' ? 'selected' : '' ?> class="text-gray-500 dark:bg-gray-900 dark:text-gray-400">
                                            Interne
                                        </option>
                                    </select>
                                    <span class="absolute z-30 text-gray-500 -translate-y-1/2 right-4 top-1/2 dark:text-gray-400">
                                        <svg class="stroke-current" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M4.79175 7.396L10.0001 12.6043L15.2084 7.396" stroke="" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                        </svg>
                                    </span>
                                </div>
                                <?php if (!empty($errors['type'])) : ?>
                                    <p class="text-theme-xs text-error-500 mt-1.5"><?= $errors['type'] ?></p>
                                <?php endif; ?>
                            </div>

                            <div class="w-full px-2.5 xl:w-1/2">
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                Titre *
                                </label>
                                <input type="text" name="title" value="<?= old_value('title') ?>" placeholder="Enter a title for the file" class="dark:bg-dark-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800">
                                <?php if (!empty($errors['title'])) : ?>
                                    <p class="text-theme-xs text-error-500 mt-1.5"><?= $errors['title'] ?></p>
                                <?php endif; ?>
                            </div>

                            <div class="w-full px-2.5 xl:w-1/2">
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                Objet *
                                </label>
                                <input type="text" name="objet" value="<?= old_value('objet') ?>" placeholder="Enter last name" class="dark:bg-dark-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800">
                                <?php if (!empty($errors['objet'])) : ?>
                                    <p class="text-theme-xs text-error-500 mt-1.5"><?= $errors['objet'] ?></p>
                                <?php endif; ?>
                            </div>

                            <div class="w-full px-2.5 xl:w-1/2">
                                <input type="hidden" name="uploaded_by">
                            </div>

                            <hr width="100%" height="2px">
                            <!-- Additionnel -->

                            <div id="recu-fields" class="hiddens w-full px-2.5 grid grid-cols-1 md:grid-cols-2 gap-5">
                                <div class="w-full xl:w-1/2">
                                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                        Numéro interne *
                                    </label>
                                    <input type="text" value="<?= old_value('numero_interne') ?>" name="numero_interne" class="dark:bg-dark-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800">
                                </div>
                                
                                <div class="w-full xl:w-1/2">
                                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                        Numéro de réception *
                                    </label>
                                    <input type="text" value="<?= old_value('numero_reception') ?>" name="numero_reception" class="dark:bg-dark-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800">
                                </div>
                                
                                <div class="w-full xl:w-1/2">
                                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                        Date envoyé
                                    </label>
                                    <input type="date" value="<?= old_value('date_envoye') ?>" name="date_envoye" class="dark:bg-dark-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800">
                                </div>
                                
                                <div class="w-full xl:w-1/2">
                                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                        Date reçu *
                                    </label>
                                    <input type="date" value="<?= old_value('date_recu') ?>" name="date_recu" class="dark:bg-dark-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800">
                                </div>
                                
                                <div class="w-full xl:w-1/2">
                                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                        Date signature
                                    </label>
                                    <input type="date" value="<?= old_value('date_signature') ?>" name="date_signature" class="dark:bg-dark-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800">
                                </div>
                                
                                <div class="w-full xl:w-1/2">
                                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                        Numéro référence
                                    </label>
                                    <input type="text" value="<?= old_value('numero_reference') ?>" name="numero_reference" class="dark:bg-dark-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800">
                                </div>
                                
                                <div class="w-full xl:w-1/2">
                                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                        Envoyeur *
                                    </label>
                                    <input type="text" value="<?= old_value('envoyeur') ?>" name="envoyeur" class="dark:bg-dark-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800">
                                </div>
                            </div>
                            
                            <!-- Champs conditionnels pour "envoyé" -->
                            <div id="envoye-fields" class="hiddens w-full px-2.5 grid grid-cols-1 md:grid-cols-2 gap-5">
                                <div class="w-full xl:w-1/2">
                                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                        Numéro expédié *
                                    </label>
                                    <input type="text" value="<?= old_value('numero_expedie') ?>" name="numero_expedie" class="dark:bg-dark-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800">
                                </div>
                                
                                <div class="w-full xl:w-1/2">
                                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                        Numéro d'expédition *
                                    </label>
                                    <input type="text" value="<?= old_value('numero_expedition') ?>" name="numero_expedition" class="dark:bg-dark-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800">
                                </div>
                                
                                <div class="w-full xl:w-1/2">
                                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                        Date d'expédition *
                                    </label>
                                    <input type="date" value="<?= old_value('date_expedition') ?>" name="date_expedition" class="dark:bg-dark-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800">
                                </div>
                                
                                <div class="w-full xl:w-1/2">
                                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                        Institution destinataire *
                                    </label>
                                    <input type="text" value="<?= old_value('institution_destinataire') ?>" name="institution_destinataire" class="dark:bg-dark-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800">
                                </div>
                            </div>
                            <!-- Additionnel -->





                            <div class="w-full px-2.5">
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                Description * (1000 lettres max)
                                </label>
                                <textarea name="description" placeholder="La description du lettre" rows="6" class="dark:bg-dark-900 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800"><?= old_value('description') ?></textarea>
                                <?php if (!empty($errors['description'])) : ?>
                                    <p class="text-theme-xs text-error-500 mt-1.5"><?= $errors['description'] ?></p>
                                <?php endif; ?>
                            </div>

                            <div class=" px-2.5" style="margin-left: auto;">
                                <button type="submit" class="flex items-center justify-center gap-2 p-3 text-sm font-medium text-white transition-colors rounded-lg bg-brand-500 hover:bg-brand-600">
                                    Ajouter un fichier
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>

<?php elseif ($action == "edit") : ?>

    <main>
        <div class="p-4 mx-auto max-w-(--breakpoint-2xl) md:p-6">
            <!-- Breadcrumb Start -->
            <div x-data="{ pageName: `Edit File` }">
                <div class="flex flex-wrap items-center justify-between gap-3 mb-6">
                    <h2 class="text-xl font-semibold text-gray-800 dark:text-white/90" x-text="pageName">Edit File</h2>
                    <nav>
                        <a href="<?= $_SERVER['HTTP_REFERER'] ?? ROOT ?>" class="flex w-full rounded-lg px-3 py-2 text-left font-medium " style="color:#fff;background:#1d2939;">
                            Annuler
                        </a>
                    </nav>
                </div>
            </div>

            <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
                <div class="p-5 space-y-6 dark:border-gray-800 sm:p-6">
                    <?php if (is_object($data['row'])) : ?>
                        <form id="submissionForm" method="post" enctype="multipart/form-data">
                            <div class="submission-container">
                                <div class="form-group file-upload-group">
                                    <div class="file-upload-area" id="fileUploadArea">
                                        <?php if (!empty($row->file_path)) : ?>
                                            <?php
                                                $filePath = $row->file_path;
                                                $fileName = basename($filePath);
                                                $fileUrl = ROOT . '/' . $filePath;
                                                $ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
                                            ?>
                                            <div class="file-preview-content" style="margin-bottom: 15px;">
                                                <?php if (in_array($ext, ['jpg', 'jpeg', 'png'])): ?>
                                                    <img src="<?= $fileUrl ?>" alt="<?= $fileName ?>" style="max-width:80px;max-height:80px;border-radius:6px;">
                                                <?php elseif ($ext === 'pdf'): ?>
                                                    <div class="file-icon-svg" style="width:32px;height:32px;margin-right:10px;">
                                                        <!-- Icône PDF -->
                                                        <svg viewBox="0 0 24 24" fill="#e53e3e"><path d="M6 2a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8l-6-6H6zm7 1.5V9h5.5L13 3.5zM6 4h6v5a1 1 0 0 0 1 1h5v10a1 1 0 0 1-1 1H6a1 1 0 0 1-1-1V4zm2 7v2h8v-2H8zm0 4v2h5v-2H8z"/></svg>
                                                    </div>
                                                <?php elseif (in_array($ext, ['doc', 'docx'])): ?>
                                                    <div class="file-icon-svg" style="width:32px;height:32px;margin-right:10px;">
                                                        <!-- Icône Word -->
                                                        <svg viewBox="0 0 24 24" fill="#2563eb"><path d="M6 2a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8l-6-6H6zm7 1.5V9h5.5L13 3.5zM6 4h6v5a1 1 0 0 0 1 1h5v10a1 1 0 0 1-1 1H6a1 1 0 0 1-1-1V4zm2.5 7l1.5 5 1.5-5h1.5l-2.25 7h-1.5L7 11h1.5z"/></svg>
                                                    </div>
                                                <?php elseif (in_array($ext, ['xls', 'xlsx'])): ?>
                                                    <div class="file-icon-svg" style="width:32px;height:32px;margin-right:10px;">
                                                        <!-- Icône Excel -->
                                                        <svg viewBox="0 0 24 24" fill="#22c55e"><path d="M6 2a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8l-6-6H6zm7 1.5V9h5.5L13 3.5zM6 4h6v5a1 1 0 0 0 1 1h5v10a1 1 0 0 1-1 1H6a1 1 0 0 1-1-1V4zm2.5 7l1.5 2 1.5-2h1.5l-2.25 3 2.25 3h-1.5l-1.5-2-1.5 2H8l2.25-3L8 11h1.5z"/></svg>
                                                    </div>
                                                <?php elseif (in_array($ext, ['zip', 'rar'])): ?>
                                                    <div class="file-icon-svg" style="width:32px;height:32px;margin-right:10px;">
                                                        <!-- Icône ZIP -->
                                                        <svg viewBox="0 0 24 24" fill="#f59e42"><path d="M6 2a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8l-6-6H6zm7 1.5V9h5.5L13 3.5zM6 4h6v5a1 1 0 0 0 1 1h5v10a1 1 0 0 1-1 1H6a1 1 0 0 1-1-1V4zm3 7v2h2v-2h-2zm0 4v2h2v-2h-2z"/></svg>
                                                    </div>
                                                <?php else: ?>
                                                    <div class="file-icon-svg" style="width:32px;height:32px;margin-right:10px;">
                                                        <!-- Icône générique -->
                                                        <svg viewBox="0 0 24 24" fill="#64748b"><path d="M14,2H6A2,2 0 0,0 4,4V20A2,2 0 0,0 6,22H18A2,2 0 0,0 20,20V8L14,2M18,20H6V4H13V9H18V20Z"/></svg>
                                                    </div>
                                                <?php endif; ?>
                                                <div class="file-info">
                                                    <a href="<?= $fileUrl ?>" target="_blank" class="file-name underline text-blue-600"><?= $fileName ?></a>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                        <input type="file" id="fileInput" name="file_path" accept=".jpg,.jpeg,.png,.pdf,.doc,.docx,.xls,.xlsx,.zip,.rar">
                                        <label for="fileInput" class="file-upload-label">
                                            <div class="file-icon-svg" id="defaultFileIcon">
                                                <svg viewBox="0 0 24 24"><path d="M14,2H6A2,2 0 0,0 4,4V20A2,2 0 0,0 6,22H18A2,2 0 0,0 20,20V8L14,2M18,20H6V4H13V9H18V20Z"/></svg>
                                            </div>
                                            <span id="fileLabelText">Glissez-déposez votre fichier ou cliquez pour sélectionner</span>
                                            <span class="file-requirements">(Formats acceptés: JPG, PNG, PDF, DOC, XLS, ZIP - Max 10MB)</span>
                                        </label>
                                        <div class="file-preview" id="filePreview"></div>
                                    </div>
                                </div>
                            </div>
                            <!-- Champ Fichier -->
                            <div class="-mx-2.5 flex flex-wrap gap-y-5">
                                <div class="w-full px-2.5 xl:w-1/2">
                                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                    Type de fichier *
                                    </label>
                                    <div class="relative z-20 bg-transparent">
                                        <select name="type" class="dark:bg-dark-900 z-20 h-11 w-full appearance-none rounded-lg border border-gray-300 bg-transparent bg-none px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800" :class="isOptionSelected &amp;&amp; 'text-gray-500 dark:text-gray-400'" @change="isOptionSelected = true">
                                            <option value="" <?= old_value('type') === '' ? 'selected' : '' ?> class="text-gray-500 dark:bg-gray-900 dark:text-gray-400">
                                                Sélectionnez le type
                                            </option>
                                            <option value="recu" <?= old_value('type', $row->type) === 'recu' ? 'selected' : '' ?> class="text-gray-500 dark:bg-gray-900 dark:text-gray-400">
                                                Reçu
                                            </option>
                                            <option value="envoye" <?= old_value('type', $row->type) === 'envoye' ? 'selected' : '' ?> class="text-gray-500 dark:bg-gray-900 dark:text-gray-400">
                                                Envoye
                                            </option>
                                            <option value="interne" <?= old_value('type', $row->interne) === 'interne' ? 'selected' : '' ?> class="text-gray-500 dark:bg-gray-900 dark:text-gray-400">
                                                interne
                                            </option>
                                        </select>
                                        <span class="absolute z-30 text-gray-500 -translate-y-1/2 right-4 top-1/2 dark:text-gray-400">
                                            <svg class="stroke-current" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M4.79175 7.396L10.0001 12.6043L15.2084 7.396" stroke="" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                            </svg>
                                        </span>
                                    </div>
                                    <?php if (!empty($errors['type'])) : ?>
                                        <p class="text-theme-xs text-error-500 mt-1.5"><?= $errors['type'] ?></p>
                                    <?php endif; ?>
                                </div>

                                <div class="w-full px-2.5 xl:w-1/2">
                                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                    Titre *
                                    </label>
                                    <input type="text" name="title" value="<?= old_value('title', $row->title) ?>" placeholder="Enter a title for the file" class="dark:bg-dark-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800">
                                    <?php if (!empty($errors['title'])) : ?>
                                        <p class="text-theme-xs text-error-500 mt-1.5"><?= $errors['title'] ?></p>
                                    <?php endif; ?>
                                </div>

                                <div class="w-full px-2.5 xl:w-1/2">
                                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                    Objet *
                                    </label>
                                    <input type="text" name="objet" value="<?= old_value('objet', $row->objet) ?>" placeholder="Enter last name" class="dark:bg-dark-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800">
                                    <?php if (!empty($errors['objet'])) : ?>
                                        <p class="text-theme-xs text-error-500 mt-1.5"><?= $errors['objet'] ?></p>
                                    <?php endif; ?>
                                </div>



                                <hr width="100%" height="2px">
                            <!-- Additionnel -->

                            <div id="recu-fields" class="hiddens w-full px-2.5 grid grid-cols-1 md:grid-cols-2 gap-5">
                                <div class="w-full xl:w-1/2">
                                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                        Numéro interne *
                                    </label>
                                    <input type="text" value="<?= old_value('numero_interne', $row->numero_interne) ?>" name="numero_interne" class="dark:bg-dark-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800">
                                </div>
                                
                                <div class="w-full xl:w-1/2">
                                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                        Numéro de réception *
                                    </label>
                                    <input type="text" value="<?= old_value('numero_reception', $row->numero_reception) ?>" name="numero_reception" class="dark:bg-dark-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800">
                                </div>
                                
                                <div class="w-full xl:w-1/2">
                                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                        Date envoyé
                                    </label>
                                    <input type="date" value="<?= old_value('date_envoye', $row->date_envoye) ?>" name="date_envoye" class="dark:bg-dark-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800">
                                </div>
                                
                                <div class="w-full xl:w-1/2">
                                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                        Date reçu *
                                    </label>
                                    <input type="date" value="<?= old_value('date_recu', $row->date_recu) ?>" name="date_recu" class="dark:bg-dark-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800">
                                </div>
                                
                                <div class="w-full xl:w-1/2">
                                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                        Date signature
                                    </label>
                                    <input type="date" value="<?= old_value('date_signature', $row->date_signature) ?>" name="date_signature" class="dark:bg-dark-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800">
                                </div>
                                
                                <div class="w-full xl:w-1/2">
                                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                        Numéro référence
                                    </label>
                                    <input type="text" value="<?= old_value('numero_reference', $row->numero_reference) ?>" name="numero_reference" class="dark:bg-dark-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800">
                                </div>
                                
                                <div class="w-full xl:w-1/2">
                                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                        Envoyeur *
                                    </label>
                                    <input type="text" value="<?= old_value('envoyeur', $row->envoyeur) ?>" name="envoyeur" class="dark:bg-dark-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800">
                                </div>
                            </div>
                            
                            <!-- Champs conditionnels pour "envoyé" -->
                            <div id="envoye-fields" class="hiddens w-full px-2.5 grid grid-cols-1 md:grid-cols-2 gap-5">
                                <div class="w-full xl:w-1/2">
                                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                        Numéro expédié *
                                    </label>
                                    <input type="text" value="<?= old_value('numero_expedie', $row->numero_expedie) ?>" name="numero_expedie" class="dark:bg-dark-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800">
                                </div>
                                
                                <div class="w-full xl:w-1/2">
                                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                        Numéro d'expédition *
                                    </label>
                                    <input type="text" value="<?= old_value('numero_expedition', $row->numero_expedition) ?>" name="numero_expedition" class="dark:bg-dark-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800">
                                </div>
                                
                                <div class="w-full xl:w-1/2">
                                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                        Date d'expédition *
                                    </label>
                                    <input type="date" value="<?= old_value('date_expedition', $row->date_expedition) ?>" name="date_expedition" class="dark:bg-dark-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800">
                                </div>
                                
                                <div class="w-full xl:w-1/2">
                                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                        Institution destinataire *
                                    </label>
                                    <input type="text" value="<?= old_value('institution_destinataire', $row->institution_destinataire) ?>" name="institution_destinataire" class="dark:bg-dark-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800">
                                </div>
                            </div>
                            <!-- Additionnel -->






                                <div class="w-full px-2.5">
                                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                    Description * (1000 lettres max)
                                    </label>
                                    <textarea name="description" placeholder="La description du lettre" rows="6" class="dark:bg-dark-900 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800"><?= old_value('description', $row->description) ?></textarea>
                                    <?php if (!empty($errors['description'])) : ?>
                                        <p class="text-theme-xs text-error-500 mt-1.5"><?= $errors['description'] ?></p>
                                    <?php endif; ?>
                                </div>

                                <div class=" px-2.5" style="margin-left: auto;">
                                    <button type="submit" class="flex items-center justify-center gap-2 p-3 text-sm font-medium text-white transition-colors rounded-lg bg-brand-500 hover:bg-brand-600">
                                        Edit
                                    </button>
                                </div>
                            </div>
                        </form>
                    <?php else : ?>
                        No records find
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </main>

<?php elseif ($action == "delete") : ?>

    <main>
        <div class="p-4 mx-auto max-w-(--breakpoint-2xl) md:p-6">
            <!-- Breadcrumb Start -->
            <div x-data="{ pageName: `Delete File` }">
                <div class="flex flex-wrap items-center justify-between gap-3 mb-6">
                    <h2 class="text-xl font-semibold text-gray-800 dark:text-white/90" x-text="pageName">Delete File</h2>
                    <nav>
                        <a href="<?= $_SERVER['HTTP_REFERER'] ?? ROOT ?>" class="flex w-full rounded-lg px-3 py-2 text-left font-medium " style="color:#fff;background:#1d2939;">
                            Annuler
                        </a>
                    </nav>
                </div>
            </div>

            <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
                <div class="p-5 space-y-6 dark:border-gray-800 sm:p-6">
                    <?php if (!empty($row)) : ?>
                        <form id="submissionForm" method="post" enctype="multipart/form-data">
                            <div class="submission-container">
                                <div class="w-full px-2.5">
                                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                        Fichier stocké
                                    </label>
                                    <?php
                                        $filePath = $row->file_path;
                                        $fileName = basename($filePath);
                                        $fileUrl = ROOT . '/' . $filePath;
                                        $ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
                                    ?>
                                    <div class="file-preview-content">
                                        <?php if (in_array($ext, ['jpg', 'jpeg', 'png'])): ?>
                                            <img src="<?= $fileUrl ?>" alt="<?= $fileName ?>" style="max-width:80px;max-height:80px;border-radius:6px;">
                                        <?php elseif ($ext === 'pdf'): ?>
                                            <div class="file-icon-svg" style="width:32px;height:32px;margin-right:10px;">
                                                <!-- Icône PDF -->
                                                <svg xmlns="http://www.w3.org/2000/svg" width="800px" height="800px" viewBox="-4 0 40 40" fill="none">
                                                    <path d="M25.6686 26.0962C25.1812 26.2401 24.4656 26.2563 23.6984 26.145C22.875 26.0256 22.0351 25.7739 21.2096 25.403C22.6817 25.1888 23.8237 25.2548 24.8005 25.6009C25.0319 25.6829 25.412 25.9021 25.6686 26.0962ZM17.4552 24.7459C17.3953 24.7622 17.3363 24.7776 17.2776 24.7939C16.8815 24.9017 16.4961 25.0069 16.1247 25.1005L15.6239 25.2275C14.6165 25.4824 13.5865 25.7428 12.5692 26.0529C12.9558 25.1206 13.315 24.178 13.6667 23.2564C13.9271 22.5742 14.193 21.8773 14.468 21.1894C14.6075 21.4198 14.7531 21.6503 14.9046 21.8814C15.5948 22.9326 16.4624 23.9045 17.4552 24.7459ZM14.8927 14.2326C14.958 15.383 14.7098 16.4897 14.3457 17.5514C13.8972 16.2386 13.6882 14.7889 14.2489 13.6185C14.3927 13.3185 14.5105 13.1581 14.5869 13.0744C14.7049 13.2566 14.8601 13.6642 14.8927 14.2326ZM9.63347 28.8054C9.38148 29.2562 9.12426 29.6782 8.86063 30.0767C8.22442 31.0355 7.18393 32.0621 6.64941 32.0621C6.59681 32.0621 6.53316 32.0536 6.44015 31.9554C6.38028 31.8926 6.37069 31.8476 6.37359 31.7862C6.39161 31.4337 6.85867 30.8059 7.53527 30.2238C8.14939 29.6957 8.84352 29.2262 9.63347 28.8054ZM27.3706 26.1461C27.2889 24.9719 25.3123 24.2186 25.2928 24.2116C24.5287 23.9407 23.6986 23.8091 22.7552 23.8091C21.7453 23.8091 20.6565 23.9552 19.2582 24.2819C18.014 23.3999 16.9392 22.2957 16.1362 21.0733C15.7816 20.5332 15.4628 19.9941 15.1849 19.4675C15.8633 17.8454 16.4742 16.1013 16.3632 14.1479C16.2737 12.5816 15.5674 11.5295 14.6069 11.5295C13.948 11.5295 13.3807 12.0175 12.9194 12.9813C12.0965 14.6987 12.3128 16.8962 13.562 19.5184C13.1121 20.5751 12.6941 21.6706 12.2895 22.7311C11.7861 24.0498 11.2674 25.4103 10.6828 26.7045C9.04334 27.3532 7.69648 28.1399 6.57402 29.1057C5.8387 29.7373 4.95223 30.7028 4.90163 31.7107C4.87693 32.1854 5.03969 32.6207 5.37044 32.9695C5.72183 33.3398 6.16329 33.5348 6.6487 33.5354C8.25189 33.5354 9.79489 31.3327 10.0876 30.8909C10.6767 30.0029 11.2281 29.0124 11.7684 27.8699C13.1292 27.3781 14.5794 27.011 15.985 26.6562L16.4884 26.5283C16.8668 26.4321 17.2601 26.3257 17.6635 26.2153C18.0904 26.0999 18.5296 25.9802 18.976 25.8665C20.4193 26.7844 21.9714 27.3831 23.4851 27.6028C24.7601 27.7883 25.8924 27.6807 26.6589 27.2811C27.3486 26.9219 27.3866 26.3676 27.3706 26.1461ZM30.4755 36.2428C30.4755 38.3932 28.5802 38.5258 28.1978 38.5301H3.74486C1.60224 38.5301 1.47322 36.6218 1.46913 36.2428L1.46884 3.75642C1.46884 1.6039 3.36763 1.4734 3.74457 1.46908H20.263L20.2718 1.4778V7.92396C20.2718 9.21763 21.0539 11.6669 24.0158 11.6669H30.4203L30.4753 11.7218L30.4755 36.2428ZM28.9572 10.1976H24.0169C21.8749 10.1976 21.7453 8.29969 21.7424 7.92417V2.95307L28.9572 10.1976ZM31.9447 36.2428V11.1157L21.7424 0.871022V0.823357H21.6936L20.8742 0H3.74491C2.44954 0 0 0.785336 0 3.75711V36.2435C0 37.5427 0.782956 40 3.74491 40H28.2001C29.4952 39.9997 31.9447 39.2143 31.9447 36.2428Z" fill="#EB5757"/>
                                                </svg>
                                            </div>
                                        <?php elseif (in_array($ext, ['doc', 'docx'])): ?>
                                            <div class="file-icon-svg" style="width:32px;height:32px;margin-right:10px;">
                                                <!-- Icône Word -->
                                                <svg viewBox="0 0 24 24" fill="#2563eb"><path d="M6 2a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8l-6-6H6zm7 1.5V9h5.5L13 3.5zM6 4h6v5a1 1 0 0 0 1 1h5v10a1 1 0 0 1-1 1H6a1 1 0 0 1-1-1V4zm2.5 7l1.5 5 1.5-5h1.5l-2.25 7h-1.5L7 11h1.5z"/></svg>
                                            </div>
                                        <?php elseif (in_array($ext, ['xls', 'xlsx'])): ?>
                                            <div class="file-icon-svg" style="width:32px;height:32px;margin-right:10px;">
                                                <!-- Icône Excel -->
                                                <svg viewBox="0 0 24 24" fill="#22c55e"><path d="M6 2a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8l-6-6H6zm7 1.5V9h5.5L13 3.5zM6 4h6v5a1 1 0 0 0 1 1h5v10a1 1 0 0 1-1 1H6a1 1 0 0 1-1-1V4zm2.5 7l1.5 2 1.5-2h1.5l-2.25 3 2.25 3h-1.5l-1.5-2-1.5 2H8l2.25-3L8 11h1.5z"/></svg>
                                            </div>
                                        <?php elseif (in_array($ext, ['zip', 'rar'])): ?>
                                            <div class="file-icon-svg" style="width:32px;height:32px;margin-right:10px;">
                                                <!-- Icône ZIP -->
                                                <svg viewBox="0 0 24 24" fill="#f59e42"><path d="M6 2a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8l-6-6H6zm7 1.5V9h5.5L13 3.5zM6 4h6v5a1 1 0 0 0 1 1h5v10a1 1 0 0 1-1 1H6a1 1 0 0 1-1-1V4zm3 7v2h2v-2h-2zm0 4v2h2v-2h-2z"/></svg>
                                            </div>
                                        <?php else: ?>
                                            <div class="file-icon-svg" style="width:32px;height:32px;margin-right:10px;">
                                                <!-- Icône générique -->
                                                <svg viewBox="0 0 24 24" fill="#64748b"><path d="M14,2H6A2,2 0 0,0 4,4V20A2,2 0 0,0 6,22H18A2,2 0 0,0 20,20V8L14,2M18,20H6V4H13V9H18V20Z"/></svg>
                                            </div>
                                        <?php endif; ?>
                                        <div class="file-info">
                                            <a href="<?= $fileUrl ?>" target="_blank" class="file-name underline text-blue-600"><?= $fileName ?></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Champ Fichier -->
                            
                            <div class="-mx-2.5 flex flex-wrap gap-y-5">
                                <div class="w-full px-2.5 xl:w-1/2">
                                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                    Type de fichier
                                    </label>
                                    <div class="relative z-20 bg-transparent">
                                        <div class="dark:bg-dark-900 z-20 h-11 w-full appearance-none rounded-lg border border-gray-300 bg-transparent bg-none px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800" :class="isOptionSelected &amp;&amp; 'text-gray-500 dark:text-gray-400'" @change="isOptionSelected = true">
                                            <?= old_value('type', $row->type) ?>
                                        </div>
                                    </div>
                                </div>

                                <div class="w-full px-2.5 xl:w-1/2">
                                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                    Titre
                                    </label>
                                    <div class="dark:bg-dark-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800">
                                        <?= old_value('title', $row->title) ?>
                                    </div>
                                </div>

                                <div class="w-full px-2.5 xl:w-1/2">
                                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                    Objet
                                    </label>
                                    <div class="dark:bg-dark-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800">
                                        <?= old_value('objet', $row->objet) ?>
                                    </div>
                                </div>

                                <div class="w-full px-2.5 xl:w-1/2">
                                    <input type="hidden" name="deleted_by">
                                </div>

                                <div class="w-full px-2.5">
                                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                    Description 
                                    </label>
                                    <div class="dark:bg-dark-900 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800">
                                        <?= old_value('description', $row->description) ?>
                                    </div>
                                </div>

                                <div class=" px-2.5" style="margin-left: auto;">
                                    <button type="submit" class="flex items-center justify-center gap-2 p-3 text-sm font-medium text-white transition-colors rounded-lg bg-brand-500 hover:bg-brand-600" style="background-color: #f04438;">
                                        Supprimer
                                    </button>
                                </div>
                            </div>
                        </form>
                    <?php else : ?>

                    <?php endif; ?>
                </div>
            </div>
        </div>
    </main>

<?php else : ?>

    <main>
        <div class="p-4 mx-auto max-w-(--breakpoint-2xl) md:p-6">
            <!-- Breadcrumb Start -->
            <div x-data="{ pageName: `All Files` }">
                <div class="flex flex-wrap items-center justify-between gap-3 mb-6">
                    <h2 class="text-xl font-semibold text-gray-800 dark:text-white/90" x-text="pageName">All Files</h2>
                    <nav>
                        <a href="<?= ROOT ?>/upload/file/add" class="inline-flex items-center gap-2 px-4 py-3 text-sm font-medium text-white transition rounded-lg bg-brand-500 shadow-theme-xs hover:bg-brand-600">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m3.75 9v6m3-3H9m1.5-12H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                            </svg>
                            Upload Files
                        </a>
                    </nav>
                </div>
            </div>

            <!-- DATATABLE -->
            <?php if ($rows) : ?>
                <div class="col-span-12 dark:border-gray-800">
                    <div class="rounded-2xl bg-white dark:border-gray-800 dark:bg-white/[0.03]">
                        <div class="max-w-full px-5 sm:px-6">
                            <table class="min-w-full">
                                <thead class="border-y border-gray-100 py-3 dark:border-gray-800">
                                    <tr>
                                        <th class="py-3 pr-5 font-normal whitespace-nowrap sm:pr-6">
                                            <div class="flex items-center">
                                                <p class="text-theme-sm text-gray-500 dark:text-gray-400">File Title</p>
                                            </div>
                                        </th>
                                        <th class="px-5 py-3 font-normal whitespace-nowrap sm:px-6">
                                            <div class="flex items-center">
                                                <p class="text-theme-sm text-gray-500 dark:text-gray-400">Object</p>
                                            </div>
                                        </th>
                                        <th class="px-5 py-3 font-normal whitespace-nowrap sm:px-6">
                                            <div class="flex items-center">
                                                <p class="text-theme-sm text-gray-500 dark:text-gray-400">Created by </p>
                                            </div>
                                        </th>
                                        <th class="px-5 py-3 font-normal whitespace-nowrap sm:px-6">
                                            <div class="flex items-center">
                                                <p class="text-theme-sm text-gray-500 dark:text-gray-400"> Type </p>
                                            </div>
                                        </th>
                                        <th class="px-5 py-3 font-normal whitespace-nowrap sm:px-6">
                                            <div class="flex items-center">
                                                <p class="text-theme-sm text-gray-500 dark:text-gray-400">Created date</p>
                                            </div>
                                        </th>
                                        
                                        <th class="px-5 py-3 font-normal whitespace-nowrap sm:px-6">
                                            <div class="flex items-center">
                                                <p class="text-theme-sm text-gray-500 dark:text-gray-400">Action</p>
                                            </div>
                                        </th>
                                    </tr>
                                </thead>

                                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                                    <?php foreach ($rows as $row) : ?>
                                        <tr>
                                            <td class="py-3 pr-5 whitespace-nowrap sm:pr-5">
                                                <div class="col-span-3 flex items-center">
                                                    <div class="flex items-center gap-3">
                                                        <div class="h-8 w-8">
                                                            <?= $fileHelper->getIcon($row->file_path) ?>
                                                        </div>

                                                        <div>
                                                            <span class="text-theme-sm block font-medium text-gray-700 dark:text-gray-400">
                                                                <?= esc(ucfirst($row->title)) ?>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-5 py-3 whitespace-nowrap sm:px-6">
                                                <div class="flex items-center">
                                                    <p class="text-theme-sm text-gray-700 dark:text-gray-400">
                                                        <?= esc(ucfirst($row->objet)) ?>
                                                    </p>
                                                </div>
                                            </td>
                                            <td class="px-5 py-3 whitespace-nowrap sm:px-6">
                                                <div class="flex items-center">
                                                    <p class="text-theme-sm text-gray-700 dark:text-gray-400">
                                                        <?=  esc(ucfirst($uploads->getUserNameById($row->uploaded_by))) ?>
                                                    </p>
                                                </div>
                                            </td>
                                            <td class="px-5 py-3 whitespace-nowrap sm:px-6">
                                                <div class="flex items-center">
                                                    <p class="text-theme-sm text-gray-700 dark:text-gray-400">
                                                        <?= esc(ucfirst($row->type)) ?>
                                                    </p>
                                                </div>
                                            </td>
                                            <td class="px-5 py-3 whitespace-nowrap sm:px-6">
                                                <div class="flex items-center">
                                                    <p class="bg-success-50 text-theme-xs text-success-600 dark:bg-success-500/15 dark:text-success-500 rounded-full px-2 py-0.5 font-medium">
                                                        
                                                        <?php
                                                            $formatter = new IntlDateFormatter(
                                                                'fr_FR',                         // locale
                                                                IntlDateFormatter::LONG,        // date format
                                                                IntlDateFormatter::SHORT,       // time format
                                                                'Europe/Paris',                 // timezone
                                                                IntlDateFormatter::GREGORIAN,   // calendar
                                                                "d MMMM yyyy 'à' HH:mm"         // pattern personnalisé
                                                            );

                                                            $date = new DateTime($row->created_at);
                                                            echo $formatter->format($date);
                                                        ?>
                                                    </p>
                                                </div>
                                                
                                            </td>
                                            <td class="px-5 py-3 whitespace-nowrap sm:px-6">
                                                <div x-data="{ open: false, openAssign: false }" class="relative">
                                                    <button @click="open = !open" class="text-gray-500 dark:text-gray-400">
                                                        <!-- SVG ... -->
                                                        <svg class="fill-current" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                            <path fill-rule="evenodd" clip-rule="evenodd" d="M5.99902 10.245C6.96552 10.245 7.74902 11.0285 7.74902 11.995V12.005C7.74902 12.9715 6.96552 13.755 5.99902 13.755C5.03253 13.755 4.24902 12.9715 4.24902 12.005V11.995C4.24902 11.0285 5.03253 10.245 5.99902 10.245ZM17.999 10.245C18.9655 10.245 19.749 11.0285 19.749 11.995V12.005C19.749 12.9715 18.9655 13.755 17.999 13.755C17.0325 13.755 16.249 12.9715 16.249 12.005V11.995C16.249 11.0285 17.0325 10.245 17.999 10.245ZM13.749 11.995C13.749 11.0285 12.9655 10.245 11.999 10.245C11.0325 10.245 10.249 11.0285 10.249 11.995V12.005C10.249 12.9715 11.0325 13.755 11.999 13.755C12.9655 13.755 13.749 12.9715 13.749 12.005V11.995Z" fill=""></path>
                                                        </svg>
                                                    </button>
                                                    <div x-show="open" @click.outside="open = false"
                                                        class="shadow-theme-lg dark:bg-gray-dark fixed w-40 space-y-1 rounded-2xl border border-gray-200 bg-white p-2 dark:border-gray-800"
                                                        style="position: absolute; top: 20px; right: 0; z-index: 999;">
                                                        <button @click="openAssign = true; open = false" class="text-theme-xs flex w-full rounded-lg px-3 py-2 text-left font-medium text-gray-500 hover:bg-gray-100">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" heigth="16" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                                                <path stroke-linecap="round" stroke-linejoin="round" d="M7.217 10.907a2.25 2.25 0 1 0 0 2.186m0-2.186c.18.324.283.696.283 1.093s-.103.77-.283 1.093m0-2.186 9.566-5.314m-9.566 7.5 9.566 5.314m0 0a2.25 2.25 0 1 0 3.935 2.186 2.25 2.25 0 0 0-3.935-2.186Zm0-12.814a2.25 2.25 0 1 0 3.933-2.185 2.25 2.25 0 0 0-3.933 2.185Z" />
                                                            </svg> &nbsp;
                                                            Assign
                                                        </button>
                                                        <a href="<?= ROOT ?>/upload/file/edit/<?= esc($row->id) ?>" class="text-theme-xs flex w-full rounded-lg px-3 py-2 text-left font-medium text-gray-500 hover:bg-gray-100">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" heigth="16" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                                                <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L6.832 19.82a4.5 4.5 0 0 1-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 0 1 1.13-1.897L16.863 4.487Zm0 0L19.5 7.125" />
                                                            </svg> &nbsp;
                                                            Edit
                                                        </a>
                                                        <a href="<?= ROOT ?>/upload/file/delete/<?= esc($row->id) ?>" class="text-theme-xs flex w-full rounded-lg px-3 py-2 text-left font-medium text-gray-500 hover:bg-gray-100">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" heigth="16" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                                                <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                                            </svg> &nbsp;
                                                            Delete
                                                        </a>
                                                        
                                                        
                                                        <!-- autres actions -->
                                                    </div>
                                                    <!-- Pop-up d'assignation -->
                                                    <div x-show="openAssign" style="position: fixed; top:0; left:0; width:100vw; height:100vh; background: rgb(0 0 0 / 30%); z-index:1000;" class="flex items-center justify-center">
                                                        <div class="bg-white rounded-lg p-6 shadow-lg w-80 relative">
                                                            <button @click="openAssign = false" class="absolute top-2 right-2 text-gray-400 hover:text-gray-700">&times;</button>
                                                            <h3 class="font-bold mb-4 text-lg">Assigner à un utilisateur</h3>
                                                            <ul>
                                                                <?php foreach ($users as $user): ?>
                                                                    <li>
                                                                        <form method="post" action="<?= ROOT ?>/documentassignment/assign/<?= $row->id ?>" style="display:inline;">
                                                                            <input type="hidden" name="assigned_to" value="<?= $user->id ?>">
                                                                            <button type="submit" class="block w-full text-left px-2 py-1 hover:bg-gray-100 rounded">
                                                                                <?= esc($user->prenom . ' ' . $user->nom) ?>
                                                                            </button>
                                                                        </form>
                                                                    </li>
                                                                <?php endforeach; ?>
                                                            </ul>
                                                            <button @click="isModalOpen = false" type="button" class="flex w-full justify-center rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 hover:text-gray-800 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.03] dark:hover:text-gray-200 sm:w-auto">
                                                                Close
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            <?php endif; ?>


        </div>
    </main>

<?php endif; ?>

<?php $this->view('footer'); ?>