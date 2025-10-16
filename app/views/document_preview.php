

<div class="container mx-auto p-6">
    <div class="bg-white rounded-lg shadow-md p-6">
        <!-- En-tête -->
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-800"><?= esc($document->nom) ?></h1>
                <p class="text-gray-600">Dossier : <?= esc($dossier->nom) ?></p>
            </div>
            <div class="flex space-x-3">
                <?php if ($can_display): ?>
                    <a href="<?= ROOT ?>/document/visualiser/<?= $document->id ?>" 
                       target="_blank" 
                       class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                        Ouvrir dans le navigateur
                    </a>
                <?php endif; ?>
                
                <a href="<?= ROOT ?>/document/telecharger/<?= $document->id ?>" 
                   class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                    </svg>
                    Télécharger
                </a>
                
                <a href="<?= ROOT ?>/document?dossier_id=<?= $dossier->id ?>" 
                   class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Retour
                </a>
            </div>
        </div>

        <!-- Zone de prévisualisation -->
        <div class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center">
            <?php if ($can_display): ?>
                <!-- Afficher directement dans un iframe -->
                <iframe src="<?= ROOT ?>/document/visualiser/<?= $document->id ?>" 
                        class="w-full h-96 border rounded-lg" 
                        frameborder="0">
                    Votre navigateur ne supporte pas l'affichage de ce document. 
                    <a href="<?= ROOT ?>/document/telecharger/<?= $document->id ?>">Téléchargez-le</a>.
                </iframe>
            <?php else: ?>
                <!-- Message pour les fichiers non affichables -->
                <div class="py-12">
                    <svg class="w-16 h-16 mx-auto text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <h3 class="mt-4 text-lg font-medium text-gray-900">Prévisualisation non disponible</h3>
                    <p class="mt-2 text-gray-500">
                        Ce type de fichier ne peut pas être affiché directement dans le navigateur.
                    </p>
                    <div class="mt-6">
                        <a href="<?= ROOT ?>/document/telecharger/<?= $document->id ?>" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                            Télécharger le fichier
                        </a>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <!-- Informations du fichier -->
        <div class="mt-6 grid grid-cols-2 gap-4 text-sm text-gray-600">
            <div>
                <strong>Type :</strong> <?= !empty($document->type) ? esc($document->type) : 'Inconnu' ?>
            </div>
            <div>
                <strong>Taille :</strong> <?= !empty($document->taille) ? formatFileSize($document->taille) : 'N/A' ?>
            </div>
            <div>
                <strong>Date d'upload :</strong> <?= !empty($document->date_upload) ? date('d/m/Y H:i', strtotime($document->date_upload)) : 'Inconnue' ?>
            </div>
            <div>
                <strong>Uploadé par :</strong> <?= esc($document->uploader_id) ?>
            </div>
        </div>
    </div>
</div>