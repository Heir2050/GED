<?php 
    $this->view('head');

    // Si on est à la racine, affiche tous les dossiers racines
    if (empty($parent_id) || $parent_id === null || $parent_id === "0") :
        $to_display = $home_folders;
    else:
        // Sinon, affiche les sous-dossiers du dossier courant
        $to_display = $folders;
    endif;
    
?>

    <main>
        <div class="p-4 mx-auto max-w-(--breakpoint-2xl) md:p-6">
            <!-- Breadcrumb Start -->
            <div x-data="{ pageName: `Folders`}">
                <div class="mb-6 flex flex-wrap items-center justify-between gap-3">
                    <h2 class="text-xl font-semibold text-gray-800 dark:text-white/90" x-text="pageName">Folders</h2>

                    <nav>
                        <!-- Pop up creating folder -->
                        <div x-show="showCreateFolder" style="position:fixed;top:0;left:0;width:100vw;height:100vh;background:rgba(0,0,0,0.3);z-index:9999;" class="flex items-center justify-center">
                            <form method="post" action="<?= ROOT ?>/folder/add<?= !empty($parent_id) ? '/' . $parent_id : '' ?>" class="bg-white rounded-lg p-6 shadow-lg w-80 relative">
                                <button type="button" @click="showCreateFolder = false" class="absolute top-2 right-2 text-gray-400 hover:text-gray-700" style="right: 1.5rem;width:2rem;height:2rem;background:#f2f4f7;color:black;font-size: 1.2rem;">&times;</button>
                                <h3 class="font-bold mb-4 text-lg">Créer un dossier</h3>
                                <input type="hidden" name="parent_id" value="<?= esc($parent_id) ?>">
                                <input type="text" name="name" placeholder="Nom du dossier" class="w-full mb-4 px-3 py-2 border rounded focus:outline-none focus:border-brand-500" />
                                <?php if (!empty($errors['name'])) : ?>
                                    <p class="text-theme-xs text-error-500 mt-1.5"><?= $errors['name'] ?></p>
                                <?php endif; ?>
                                <button type="submit" class="w-full bg-brand-500 text-white py-2 rounded-lg hover:bg-brand-600">Valider</button>
                            </form>
                        </div>
                        
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

            <?php // var_dump($folders) ?>
            
            <?php // var_dump($parent_id) ?>

            <div class="space-y-5 sm:space-y-6">
                <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
                    <div class="max-w-full overflow-x-auto">
                        <?php if (!empty($to_display)) : ?>
                            <!-- Folders -->

                            <div class=" p-5 sm:p-6">
                                <div class="grid lg:grid-cols-4 grid-cols-1 sm:grid-cols-2 sm:gap-6">
                                    <?php foreach ($to_display as $row) : ?>
                                        <a href="<?= ROOT ?>/folder/<?= $row->folder_id ?>">
                                            <div class="rounded-2xl bg-gray-50 px-6 py-6 dark:bg-white/[0.03] xl:py-[27px]">
                                                <div class="mb-6 flex justify-between">
                                                    <div>
                                                        <svg width="36" height="36" viewBox="0 0 36 36" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                            <path fill-rule="evenodd" clip-rule="evenodd" d="M13.3986 4.40674C12.9265 3.77722 12.1855 3.40674 11.3986 3.40674H2.5C1.11929 3.40674 0 4.52602 0 5.90674V30.0959C0 31.4766 1.11929 32.5959 2.5 32.5959H33.5C34.8807 32.5959 36 31.4766 36 30.0959V11.7446C36 10.3639 34.8807 9.24458 33.5 9.24458H18.277C17.4901 9.24458 16.7492 8.87409 16.277 8.24458L13.3986 4.40674Z" fill="url(#paint0_linear_2816_28044)"></path>
                                                            <defs>
                                                                <linearGradient id="paint0_linear_2816_28044" x1="18" y1="3.40674" x2="18" y2="32.5959" gradientUnits="userSpaceOnUse">
                                                                    <stop stop-color="#FFDC78"></stop>
                                                                    <stop offset="1" stop-color="#FBBC1A"></stop>
                                                                </linearGradient>
                                                            </defs>
                                                        </svg>
                                                    </div>

                                                    <div x-data="{openDropDown: false}" class="relative">
                                                        <button @click="openDropDown = !openDropDown" :class="openDropDown ? 'text-gray-700 dark:text-white' : 'text-gray-400 hover:text-gray-700 dark:hover:text-white'" class="text-gray-400 hover:text-gray-700 dark:hover:text-white">
                                                            <svg class="fill-current" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                <path fill-rule="evenodd" clip-rule="evenodd" d="M10.2441 6C10.2441 5.0335 11.0276 4.25 11.9941 4.25H12.0041C12.9706 4.25 13.7541 5.0335 13.7541 6C13.7541 6.9665 12.9706 7.75 12.0041 7.75H11.9941C11.0276 7.75 10.2441 6.9665 10.2441 6ZM10.2441 18C10.2441 17.0335 11.0276 16.25 11.9941 16.25H12.0041C12.9706 16.25 13.7541 17.0335 13.7541 18C13.7541 18.9665 12.9706 19.75 12.0041 19.75H11.9941C11.0276 19.75 10.2441 18.9665 10.2441 18ZM11.9941 10.25C11.0276 10.25 10.2441 11.0335 10.2441 12C10.2441 12.9665 11.0276 13.75 11.9941 13.75H12.0041C12.9706 13.75 13.7541 12.9665 13.7541 12C13.7541 11.0335 12.9706 10.25 12.0041 10.25H11.9941Z" fill=""></path>
                                                            </svg>
                                                        </button>
                                                        <div x-show="openDropDown" @click.outside="openDropDown = false" class="absolute right-0 top-full z-40 w-40 space-y-1 rounded-2xl border border-gray-200 bg-white p-2 shadow-theme-lg dark:border-gray-800 dark:bg-gray-dark" style="display: none;">
                                                            <button class="flex w-full rounded-lg px-3 py-2 text-left text-theme-xs font-medium text-gray-500 hover:bg-gray-100 hover:text-gray-700 dark:text-gray-400 dark:hover:bg-white/5 dark:hover:text-gray-300">
                                                                View More
                                                            </button>
                                                            <button class="flex w-full rounded-lg px-3 py-2 text-left text-theme-xs font-medium text-gray-500 hover:bg-gray-100 hover:text-gray-700 dark:text-gray-400 dark:hover:bg-white/5 dark:hover:text-gray-300">
                                                                Delete
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>

                                                <h4 class="mb-1 text-sm font-medium text-gray-800 dark:text-white/90">
                                                    <?= esc($row->name) ?>
                                                </h4>
                                                <!-- <div class="flex items-center justify-between">
                                                    <span class="block text-sm text-gray-500 dark:text-gray-400">
                                                        345 Files
                                                    </span>
                                                    <span class="block text-right text-sm text-gray-500 dark:text-gray-400">
                                                        26.40 GB
                                                    </span>
                                                </div> -->
                                            </div>
                                        </a>
                                    <?php endforeach; ?>
                                </div>
                            </div>

                        <?php // else : ?>
                            <!-- <div class="p-6 text-center" style="max-width: 70%;margin: 0 auto;">
                                <p class="text-gray-500 font-bold text-theme-sm dark:text-gray-400">
                                    No folder found !
                                </p>
                            </div> -->
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </main>



<?php $this->view('footer'); ?>