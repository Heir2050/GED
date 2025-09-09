<?php $this->view('head'); ?>


<!-- filepath: c:\xampp\htdocs\gde\app\views\details.view.php -->
<main>
    <div class="max-w-2xl mx-auto p-6 bg-white shadow">
        <h2 class="text-2xl font-bold mb-4">Détails du fichier</h2>
        <?php if ($allrow) : ?>
            <table class="w-full mb-6">
                <tr>
                    <th class="text-left">Titre :</th>
                    <td><?= esc($allrow->title) ?></td>
                </tr>
                <tr>
                    <th class="text-left">Objet :</th>
                    <td><?= esc($allrow->objet) ?></td>
                </tr>
                <tr>
                    <th class="text-left">Type :</th>
                    <td><?= esc($allrow->type) ?></td>
                </tr>
                <tr>
                    <th class="text-left">Description :</th>
                    <td><?= esc($allrow->description) ?></td>
                </tr>
                <tr>
                    <th class="text-left">Fichier :</th>
                    <td>
                        <?php if ($allrow->file_path): ?>
                            <a href="<?= ROOT . '/' . $allrow->file_path ?>" target="_blank" class="text-blue-600 underline">Voir / Télécharger</a>
                        <?php else: ?>
                            Aucun fichier
                        <?php endif; ?>
                    </td>
                </tr>
                <tr>
                    <th class="text-left">Date de création :</th>
                    <td>
                        <?php
                            $formatter = new IntlDateFormatter(
                                'fr_FR',                         // locale
                                IntlDateFormatter::LONG,        // date format
                                IntlDateFormatter::SHORT,       // time format
                                'Europe/Paris',                 // timezone
                                IntlDateFormatter::GREGORIAN,   // calendar
                                "d MMMM yyyy 'à' HH:mm"         // pattern personnalisé
                            );

                            $date = new DateTime($allrow->created_at);
                            echo $formatter->format($date);
                        ?>
                    </td>
                </tr> 
                <tr>
                    <th class="text-left">Statut :</th>
                    <td>
                        <?php if ($allrow->status == "" || $allrow->status == "en_attente") : ?>
                            <span style="background-color: #1d2939;color:white;padding:5px; border-radius: 10px;">
                            En attente
                            </span>
                        <?php elseif ($allrow->status == "approuve") : ?>
                            <span style="background-color: #ecfdf3;color:#039855;padding:5px; border-radius: 10px;">
                            ✅ Accepté
                            </span>
                        <?php elseif ($allrow->status == "refuse") : ?>
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
            <?php if (!($_SESSION['USER']->id == $allrow->uploaded_by)) : ?>
                <form method="post" action="<?= ROOT ?>/details/handleAction/<?= $allrow->id ?>" enctype="multipart/form-data" class="mt-6">
                    <div class="mb-4">
                        <label class="block text-gray-700 mb-2">Commentaire : (Optionnel)</label>
                        <textarea name="comment" class="w-full px-3 py-2 border rounded"></textarea>
                        <?php if (!empty($errors['file_path'])) : ?>
                            <p class="text-theme-xs text-error-500 mt-1.5"><?= $errors['file_path'] ?></p>
                        <?php endif; ?>
                    </div>
                    
                    <div class="mb-4">
                        <label class="block text-gray-700 mb-2">Fichier justificatif :</label>
                        <input type="file" name="attachment_file" class="w-full px-3 py-2 border rounded" style="cursor: pointer;" required>
                        <p class="text-sm text-gray-500 mt-1">Format PDF, DOCX</p>
                        <?php if (!empty($errors['file_path'])) : ?>
                            <p class="text-theme-xs text-error-500 mt-1.5"><?= $errors['file_path'] ?></p>
                        <?php endif; ?>
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
                    
                    <a href="<?= ROOT . '/' . $existing_response->file_path ?>" 
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

        <?php if (!empty($assigned_users) && $_SESSION['USER']->id == $allrow->uploaded_by) : ?>
            <div class="col-span-12 mt-6 dark:border-gray-800">
                <div class="rounded-2xl border border-gray-200 bg-white pt-4 dark:border-gray-800 dark:bg-white/[0.03]">
                    <div class="mb-4 flex flex-col gap-2 px-5 sm:flex-row sm:items-center sm:justify-between sm:px-4">
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">
                            Utilisateur Assigné
                        </h3>
                    </div>

                    <div class="custom-scrollbar max-w-full overflow-x-auto overflow-y-visible px-5 sm:px-6">
                        <table class="min-w-full">
                            <thead class="border-y border-gray-100 py-3 dark:border-gray-800">
                                <tr>
                                    <th class="py-3 pr-5 font-normal whitespace-nowrap sm:pr-6">
                                        <div class="flex items-center">
                                            <p class="text-theme-sm text-gray-500 dark:text-gray-400">Nom et Prenom</p>
                                        </div>
                                    </th>
                                    <th class="px-5 py-3 font-normal whitespace-nowrap sm:px-6">
                                        <div class="flex items-center">
                                            <p class="text-theme-sm text-gray-500 dark:text-gray-400">Email</p>
                                        </div>
                                    </th>
                                    <th class="px-5 py-3 font-normal whitespace-nowrap sm:px-6">
                                        <div class="flex items-center">
                                            <p class="text-theme-sm text-gray-500 dark:text-gray-400">Role</p>
                                        </div>
                                    </th>
                                    <th class="px-5 py-3 font-normal whitespace-nowrap sm:px-6">
                                        <div class="flex items-center">
                                            <p class="text-theme-sm text-gray-500 dark:text-gray-400">Date assigné</p>
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
                                <?php foreach ($assigned_users as $user) : ?>
                                    <tr>
                                        <td class="py-3 pr-5 whitespace-nowrap sm:pr-5">
                                            <div class="col-span-3 flex items-center">
                                                <div class="flex items-center gap-3">
                                                    <div>
                                                        <span class="text-theme-sm block font-medium text-gray-700 dark:text-gray-400">
                                                            <?= esc($user->nom . ' ' . $user->prenom) ?>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-5 py-3 whitespace-nowrap sm:px-6">
                                            <div class="flex items-center">
                                                <p class="text-theme-sm text-gray-700 dark:text-gray-400">
                                                    <?= esc(ucfirst($user->email)) ?>
                                                </p>
                                            </div>
                                        </td>
                                        <td class="px-5 py-3 whitespace-nowrap sm:px-6">
                                            <div class="flex items-center">
                                                <p class="text-theme-sm text-gray-700 dark:text-gray-400">
                                                    <?= esc(ucfirst($user->role)) ?>
                                                </p>
                                            </div>
                                        </td>
                                        <td class="px-5 py-3 whitespace-nowrap sm:px-6">
                                            <div class="flex items-center">
                                                <p class="text-theme-sm text-gray-700 dark:text-gray-400">
                                                    <?= esc(ucfirst($user->assigned_at)) ?>
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
                                                <div x-show="open" @click.outside="open = false" class="shadow-theme-lg dark:bg-gray-dark fixed w-40 space-y-1 rounded-2xl border border-gray-200 bg-white p-2 dark:border-gray-800" style="position: absolute; top: 20px; right: 0; z-index: 999;">
                                                    <button @click="openAssign = true; open = false" class="text-theme-xs flex w-full rounded-lg px-3 py-2 text-left font-medium text-gray-500 hover:bg-gray-100">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" heigth="16" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                                        </svg> &nbsp;
                                                        Retirer
                                                    </button>
                                                </div>
                                                <!-- Pop-up de retrait d'un utilisateur a un fichier -->
                                                <div x-show="openAssign" style="position: fixed; top:0; left:0; width:100vw; height:100vh; background: rgb(0 0 0 / 30%); z-index:1000;" class="flex items-center justify-center">
                                                    <div class="bg-white rounded-lg p-6 shadow-lg w-80 relative">
                                                        <button @click="openAssign = false" class="absolute top-2 right-2 text-gray-400 hover:text-gray-700">&times;</button>
                                                        <h3 class="font-bold mb-4 text-lg">Désassigner l'utilisateur</h3>
                                                        <p class="mb-4">Voulez-vous vraiment retirer <b><?= esc($user->nom . ' ' . $user->prenom) ?></b> de ce fichier ?</p>
                                                        <form method="post" action="<?= ROOT ?>/details/unassign">
                                                            <input type="hidden" name="document_id" value="<?= (int)$allrow->id ?>">
                                                            <input type="hidden" name="user_id" value="<?= (int)$user->id ?>">
                                                            <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700" style="background-color: #12b76a;">Confirmer</button>
                                                        </form>
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

<?php $this->view('footer'); ?>