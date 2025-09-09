<?php $this->view('head'); ?>
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
    <div class="max-w-2xl mx-auto p-6 bg-white shadow">
        <h2 class="text-2xl font-bold mb-4">Détails de la demande de congé</h2>
        <?php if ($detailsconge) : ?>
            <table class="w-full mb-6">
                <tr>
                    <th class="text-left">Date début :</th>
                    <td><?= esc($detailsconge->date_debut) ?></td>
                </tr>
                <tr>
                    <th class="text-left">Date fin :</th>
                    <td><?= esc($detailsconge->date_fin) ?></td>
                </tr>
                <tr>
                    <th class="text-left">Raison :</th>
                    <td><?= esc($detailsconge->raison) ?></td>
                </tr>
                <tr>
                    <th class="text-left">Date de demande :</th>
                    <td><?= esc($detailsconge->date_demande) ?></td>
                </tr>
                <tr>
                    <th class="text-left">Fichier :</th>
                    <td>
                        <?php if ($detailsconge->document_path): ?>
                            <a href="<?= ROOT . '/' . $detailsconge->document_path ?>" target="_blank" class="text-blue-600 underline">Voir / Télécharger</a>
                        <?php else: ?>
                            Aucun fichier
                        <?php endif; ?>
                    </td>
                </tr>
                <tr>
                    <th class="text-left">Statut :</th>
                    <td>
                        <?php if ($detailsconge->statut == "" || $detailsconge->statut == "en_attente") : ?>
                            <span style="background-color: #1d2939;color:white;padding:5px; border-radius: 10px;">
                            En attente
                            </span>
                        <?php elseif ($detailsconge->statut == "approuve") : ?>
                            <span style="background-color: #ecfdf3;color:#039855;padding:5px; border-radius: 10px;">
                            ✅ Accepté
                            </span>
                        <?php elseif ($detailsconge->statut == "refuse") : ?>
                            <span style="background-color: #f04438;color:white;padding:5px; border-radius: 10px;">
                            ❌ Refusé
                            </span>
                        <?php else : ?>
                            <span style="background-color: #1d2939;color:white;padding:5px; border-radius: 10px;">
                                Non défini
                            </span>
                        <?php endif; ?>
                    </td>
                </tr>
            </table>
        <?php endif; ?>

        <!-- Actions sur la demande -->
        <?php if (!$has_response) : ?>
            <?php if (!($_SESSION['USER']->id == $detailsconge->user_id)) : ?>
                <form method="post" action="<?= ROOT ?>/detailconges/handleAction/<?= $detailsconge->id ?>" enctype="multipart/form-data" class="mt-6">
                    <div class="mb-4">
                        <label class="block text-gray-700 mb-2">Commentaire : (Optionnel)</label>
                        <textarea name="comment" class="w-full px-3 py-2 border rounded"></textarea>
                        
                    </div>
                    
                    <div class="mb-4">
                        <label class="block text-gray-700 mb-2">Fichier justificatif :</label>
                        <input type="file" name="attachment_file" class="w-full px-3 py-2 border rounded" style="cursor: pointer;" required>
                        <p class="text-sm text-gray-500 mt-1">Format PDF, DOCX</p>
                        
                    </div>
                    
                    <div class="flex gap-3">
                        <button name="action" value="approuve" type="submit" class="text-white px-4 py-2 rounded" style="background-color: #12b76a;">
                            Approuver 
                        </button>
                        <button name="action" value="refuse" type="submit" class="text-white px-4 py-2 rounded" style="background-color: #f04438;">
                            Refuser
                        </button>
                    </div>
                </form>
            <?php endif; ?>

        <?php else : ?>
            <!-- Affichage de la réponse existante -->
            <div class="mt-6 p-4 <?= $existing_response->action === 'approuve' ? 'bg-success-50' : 'bg-error-50' ?> rounded-lg">
                <h3 class="text-lg font-semibold mb-2">
                    Réponse déjà soumise
                    <?php if ($responder) : ?>
                        <span class="text-sm font-normal text-gray-600">
                            par <?= esc($responder->nom . ' ' . $responder->prenom) ?>
                        </span>
                        <span class="text-sm font-normal"> (<?= $responder->role ?>)</span>
                    <?php endif; ?>
                </h3>
                
                <div class="flex items-start gap-4">
                    <div class="flex-1">
                        <p class="font-medium mb-1">
                            <?= $existing_response->action === 'approuve' ? '✅ Approuvé' : '❌ Refusé' ?>
                        </p>
                        <?php if (!empty($existing_response->comment)) : ?>
                            <p class="text-gray-700"><?= esc($existing_response->comment) ?></p>
                        <?php endif; ?>
                    </div>
                    
                    <a href="<?= ROOT . '/' . $existing_response->document_path ?>" 
                    target="_blank" 
                    class="text-blue-600 underline hover:text-blue-800">
                        Voir / Télécharger le fichier joint
                    </a>
                </div>
                
                <p class="text-sm text-gray-500 mt-2">
                    Soumis le <?= date('d/m/Y H:i', strtotime($existing_response->created_at)) ?>
                </p>
            </div>
        <?php endif; ?>
    </div>
</main>

<?php $this->view('footer'); ?>