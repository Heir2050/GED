<?php $this->view('head'); ?>

<!-- ===== Main Content Start ===== -->
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
            <div x-data="{ pageName: `Dashboard`}">
                <div class="mb-6 flex flex-wrap items-center justify-between gap-3">
                    <h2 class="text-xl font-semibold text-gray-800 dark:text-white/90" x-text="pageName">Dashboard</h2>
                    <nav class="flex gap-6">
                        <!-- Pop up creating folder -->
                        <div x-show="showCreateFolder" style="position:fixed;top:0;left:0;width:100vw;height:100vh;background:rgba(0,0,0,0.3);z-index:9999;" class="flex items-center justify-center">
                            <form method="post" action="<?= ROOT ?>/folder/add" class="bg-white rounded-lg p-6 shadow-lg w-80 relative">
                                <button type="button" @click="showCreateFolder = false" class="absolute top-2 right-2 text-gray-400 hover:text-gray-700" style="right: 1.5rem;width:2rem;height:2rem;background:#f2f4f7;color:black;font-size: 1.2rem;">&times;</button>
                                <h3 class="font-bold mb-4 text-lg">Créer un dossier</h3>
                                <input type="text" name="name" placeholder="Nom du dossier" class="w-full mb-4 px-3 py-2 border rounded focus:outline-none focus:border-brand-500" />
                                <?php if (!empty($errors['name'])) : ?>
                                    <p class="text-theme-xs text-error-500 mt-1.5"><?= $errors['name'] ?></p>
                                <?php endif; ?>
                                <button type="submit" class="w-full bg-brand-500 text-white py-2 rounded-lg hover:bg-brand-600">Valider</button>
                            </form>
                        </div>
                        <!-- Pop up creating folder End -->

                        <a href="<?= ROOT ?>/upload/file/add" class="inline-flex items-center gap-2 px-4 py-3 text-sm font-medium text-white transition rounded-lg bg-brand-500 shadow-theme-xs hover:bg-brand-600">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m3.75 9v6m3-3H9m1.5-12H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                            </svg>
                            Upload Files
                        </a>
                        <button @click="showCreateFolder = true" class="inline-flex items-center gap-2 px-4 py-3 text-sm font-medium text-white transition rounded-lg bg-brand-500 shadow-theme-xs hover:bg-brand-600">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 10.5v6m3-3H9m4.06-7.19-2.12-2.12a1.5 1.5 0 0 0-1.061-.44H4.5A2.25 2.25 0 0 0 2.25 6v12a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18V9a2.25 2.25 0 0 0-2.25-2.25h-5.379a1.5 1.5 0 0 1-1.06-.44Z" />
                            </svg>
                            Add folder
                        </button>
                    </nav>
                </div>
            </div>
            <!-- Breadcrumb End -->
            <div class="grid grid-cols-12 gap-4 md:gap-6">
                <!-- Folders -->
                <div class="col-span-12">
                    <!-- DATATABLE -->
                    <?php if (isset($rows)) : ?>
                        <div class="col-span-12 dark:border-gray-800">
                            <div class="rounded-2xl border border-gray-200 bg-white pt-4 dark:border-gray-800 dark:bg-white/[0.03]">
                                <div class="mb-4 flex flex-col gap-2 px-5 sm:flex-row sm:items-center sm:justify-between sm:px-4">
                                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">
                                        Fichiers
                                    </h3>
                                    <a href="<?= ROOT ?>/upload" class="inline-flex items-center gap-2 text-sm font-medium text-gray-500 hover:text-brand-500 dark:text-gray-400 dark:hover:text-brand-500">
                                        Tous les Fichiers
                                        <svg class="fill-current" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd" clip-rule="evenodd" d="M17.4175 9.9986C17.4178 10.1909 17.3446 10.3832 17.198 10.53L12.2013 15.5301C11.9085 15.8231 11.4337 15.8233 11.1407 15.5305C10.8477 15.2377 10.8475 14.7629 11.1403 14.4699L14.8604 10.7472L3.33301 10.7472C2.91879 10.7472 2.58301 10.4114 2.58301 9.99715C2.58301 9.58294 2.91879 9.24715 3.33301 9.24715L14.8549 9.24715L11.1403 5.53016C10.8475 5.23717 10.8477 4.7623 11.1407 4.4695C11.4336 4.1767 11.9085 4.17685 12.2013 4.46984L17.1588 9.43049C17.3173 9.568 17.4175 9.77087 17.4175 9.99715C17.4175 9.99763 17.4175 9.99812 17.4175 9.9986Z" fill=""></path>
                                        </svg>
                                    </a>
                                </div>

                                <div class="max-w-full overflow-x-auto overflow-y-visible px-5 sm:px-6">
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
                                                                    <!-- Display icon File -->
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
                                                                <?= esc(ucfirst($uploads->getUserNameById($row->uploaded_by))) ?>
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
                                                                <a href="<?= ROOT ?>/details/<?= esc($row->id) ?>" class="text-theme-xs flex w-full rounded-lg px-3 py-2 text-left font-medium text-gray-500 hover:bg-gray-100">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" heigth="16" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                                                    </svg> &nbsp;
                                                                    Details
                                                                </a>
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
                                    <!-- Pop-up de confirmation Delete -->
                                    <div 
                                        x-show="openDelete !== false" 
                                        class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50"
                                        x-cloak
                                    >
                                        <div class="bg-white p-6 rounded shadow">
                                            <p>Voulez-vous vraiment supprimer ce fichier ?</p>
                                            <form method="post" :action="'<?= ROOT ?>/upload/delete/' + openDelete">
                                                <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded mr-2">Oui, supprimer</button>
                                                <button type="button" @click="openDelete = false" class="bg-gray-300 px-4 py-2 rounded">Annuler</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="p-6 text-center" style="max-width: 70%;margin: 0 auto;">
                            <p class="text-gray-500 font-bold text-theme-sm dark:text-gray-400">
                                No record found !
                            </p>
                        </div>
                    <?php endif; ?>
            </div>
        </div>
    </main>
       
<?php $this->view('footer') ?>