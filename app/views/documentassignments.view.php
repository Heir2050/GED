<?php $this->view("head"); ?>

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
        <div x-data="{ pageName: `Document Assignés` }" class="mb-6">
            <div class="mb-6 flex flex-wrap items-center justify-between gap-3">
                <h2 class="text-xl font-semibold text-gray-800 dark:text-white/90" x-text="pageName">Document Assignés</h2>

                <!-- <nav>
                    <a href="<?= ROOT ?>/users/user/add" class="inline-flex items-center gap-2 px-4 py-3 text-sm font-medium text-white transition rounded-lg bg-brand-500 shadow-theme-xs hover:bg-brand-600">
                        Ajouter un document
                        <svg class="stroke-current" width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M8 3.3335V12.6668M3.3335 8H12.6668" stroke="" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"></path>
                        </svg>
                    </a>
                </nav> -->
            </div>
        </div>
        
        <div class="space-y-5 sm:space-y-6">
            <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
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
                                    <p class="text-theme-sm text-gray-500 dark:text-gray-400">Statut</p>
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
                        <?php foreach ($assignments as $item): 
                            // Vérifier s'il y a une réponse dans document_attachments
                            $hasResponse = !empty($item['document']->has_response); // Supposons que cette info est jointe dans le contrôleur
                            $responseType = $item['document']->last_action ?? null; // 'approuve' ou 'refuse'
                        ?>
                            <tr>
                                <!-- Colonne Fichier -->
                                <td class="py-3 px-5 whitespace-nowrap sm:px-5">
                                    <div class="col-span-3 flex items-center">
                                        <div class="flex items-center gap-3">
                                            <div class="h-8 w-8">
                                                <!-- <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" viewBox="0 0 40 40">
                                                    <path fill="#fff" d="M6.5 37.5L6.5 2.5 24.793 2.5 33.5 11.207 33.5 37.5z"></path>
                                                    <path fill="#788b9c" d="M24.586,3L33,11.414V37H7V3H24.586 M25,2H6v36h28V11L25,2L25,2z"></path>
                                                    <path fill="#fff" d="M24.5 11.5L24.5 2.5 24.793 2.5 33.5 11.207 33.5 11.5z"></path>
                                                    <path fill="#788b9c" d="M25 3.414L32.586 11H25V3.414M25 2h-1v10h10v-1L25 2 25 2zM12 16H28V17H12zM12 19H24V20H12zM12 22H28V23H12zM12 25H24V26H12zM12 28H28V29H12z"></path>
                                                </svg> -->
                                                <?= getFileIcon($item['document']->file_path) ?>
                                            </div>
                                            <div>
                                                <span class="text-theme-sm block font-medium text-gray-700 dark:text-gray-400">
                                                    <?= esc(basename($item['document']->file_path)) ?>
                                                </span>
                                                <span class="text-xs text-gray-500">
                                                    <?= esc($item['document']->description ?? '') ?>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                
                                <!-- Colonne Statut -->
                                <td class="px-5 py-3 whitespace-nowrap sm:px-6">
                                    <div class="flex items-center">
                                        <?php if ($item['hasResponse']): ?>
                                            <span class="px-2 py-1 rounded-full text-xs font-medium 
                                                <?= $item['responseType'] == 'approuve' 
                                                    ? 'bg-success-50 text-success-600 dark:bg-green-900/20 dark:text-green-500' 
                                                    : 'bg-error-50 text-error-600 dark:bg-red-900/20 dark:text-red-500' ?>">
                                                <?= $item['responseType'] == 'approuve' ? 'Approuvé' : 'Refusé' ?>
                                            </span>
                                        <?php else: ?>
                                            <span class="px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300">
                                                En attente
                                            </span>
                                        <?php endif; ?>
                                    </div>
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
                                            <a href="<?= ROOT ?>/details/<?= $item['document']->id ?>" class="text-theme-xs flex w-full rounded-lg px-3 py-2 text-left font-medium text-gray-500 hover:bg-gray-100">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="mr-2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                </svg>
                                                Détails
                                            </a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                
                <?php if (empty($assignments)): ?>
                    <div class="p-6 text-center text-gray-500 dark:text-gray-400">
                        Aucun document assigné pour le moment.
                    </div>
                <?php endif; ?>
            </div>
        </div>

    </div>
</main>

<?php $this->view("footer"); ?>