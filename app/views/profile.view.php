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
        <div class="p-4 mx-auto max-w-(--breakpoint-2xl) md:p-6">
        <!-- Breadcrumb Start -->
        <div x-data="{ pageName: `Profile` }">
            <div class="flex flex-wrap items-center justify-between gap-3 mb-6">
                <h2 class="text-xl font-semibold text-gray-800 dark:text-white/90" x-text="pageName">Profile</h2>
                <nav>
                    <ol class="flex items-center gap-1.5">
                        <li>
                            <a class="inline-flex items-center gap-1.5 text-sm text-gray-500 dark:text-gray-400" href="index.html">
                                Home
                                <svg class="stroke-current" width="17" height="16" viewBox="0 0 17 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M6.0765 12.667L10.2432 8.50033L6.0765 4.33366" stroke="" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"></path>
                                </svg>
                            </a>
                        </li>
                        <li class="text-sm text-gray-800 dark:text-white/90" x-text="pageName">Profile</li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class=" mx-auto max-w-(--breakpoint-2xl)">
            <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] lg:p-6">
                <h4 class="text-lg font-semibold text-gray-800 dark:text-white/90 lg:mb-6" >
                    Informations personnels
                </h4>
                <div class="flex flex-col gap-6 lg:flex-row lg:items-start lg:justify-between" >
                    <div >
                        <div class=" mb-6" >
                            <div class="flex flex-col gap-5 xl:flex-row xl:items-center xl:justify-between">
                                <div class="flex flex-col items-center w-full gap-6 xl:flex-row">
                                    <div class="w-20 h-20 overflow-hidden border border-gray-200 rounded-full dark:border-gray-800">
                                        <img src="<?= get_image($row->image) ?>" alt="user" style="height: 100%;object-fit:cover;"/>
                                    </div>
                                    <div class="order-3 xl:order-2">
                                        <h4 class="mb-2 text-lg font-semibold text-center text-gray-800 dark:text-white/90 xl:text-left">
                                            <?= esc($row->nom ?? '') ?> <?= esc(ucfirst($row->prenom) ?? '') ?>
                                        </h4>
                                        <div class="flex flex-col items-center gap-1 text-center xl:flex-row xl:gap-3 xl:text-left">
                                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                                <?= esc(ucfirst($row->role) ?? '') ?>
                                            </p>
                                            <div class="hidden h-3.5 w-px bg-gray-300 dark:bg-gray-700 xl:block"></div>
                                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                                <?= esc($row->email ?? '') ?>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div>
                            <div class="grid grid-cols-1 gap-4 lg:grid-cols-4 lg:gap-7 2xl:gap-x-32" >
                                <div>
                                    <p class="mb-2 text-xs leading-normal text-gray-500 dark:text-gray-400" >
                                        Date de creation
                                    </p>
                                    <p class="text-sm font-medium text-gray-800 dark:text-white/90" >
                                        <?= esc($row->created_at ?? '') ?>
                                    </p>
                                </div>
                                <div>
                                    <p class="mb-2 text-xs leading-normal text-gray-500 dark:text-gray-400" >
                                        Connexion precedente
                                    </p>
                                    <p class="text-sm font-medium text-gray-800 dark:text-white/90" >
                                        Date connexion
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <button @click="isProfileInfoModal = true" class="flex w-full items-center justify-center gap-2 rounded-full border border-gray-300 bg-white px-4 py-3 text-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 hover:text-gray-800 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.03] dark:hover:text-gray-200 lg:inline-flex lg:w-auto" >
                        <svg class="fill-current" width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg" >
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M15.0911 2.78206C14.2125 1.90338 12.7878 1.90338 11.9092 2.78206L4.57524 10.116C4.26682 10.4244 4.0547 10.8158 3.96468 11.2426L3.31231 14.3352C3.25997 14.5833 3.33653 14.841 3.51583 15.0203C3.69512 15.1996 3.95286 15.2761 4.20096 15.2238L7.29355 14.5714C7.72031 14.4814 8.11172 14.2693 8.42013 13.9609L15.7541 6.62695C16.6327 5.74827 16.6327 4.32365 15.7541 3.44497L15.0911 2.78206ZM12.9698 3.84272C13.2627 3.54982 13.7376 3.54982 14.0305 3.84272L14.6934 4.50563C14.9863 4.79852 14.9863 5.2734 14.6934 5.56629L14.044 6.21573L12.3204 4.49215L12.9698 3.84272ZM11.2597 5.55281L5.6359 11.1766C5.53309 11.2794 5.46238 11.4099 5.43238 11.5522L5.01758 13.5185L6.98394 13.1037C7.1262 13.0737 7.25666 13.003 7.35947 12.9002L12.9833 7.27639L11.2597 5.55281Z" fill=""/>
                        </svg>
                        Modifier
                    </button>
                </div>
            </div>
        </div>
    </main>



    <!-- BEGIN MODAL -->
    <div x-show="isProfileInfoModal" class="fixed inset-0 flex items-center justify-center p-5 overflow-y-auto z-99999" >
        <div class="modal-close-btn fixed inset-0 h-full w-full bg-gray-400/50 backdrop-blur-[32px]" ></div>
        <div @click.outside="isProfileInfoModal = false" class="no-scrollbar relative w-full max-w-[700px] overflow-y-auto rounded-3xl bg-white p-4 dark:bg-gray-900 lg:p-11">
            <!-- close btn -->
            <button @click="isProfileInfoModal = false"class="transition-color absolute right-5 top-5 z-999 flex h-11 w-11 items-center justify-center rounded-full bg-gray-100 text-gray-400 hover:bg-gray-200 hover:text-gray-600 dark:bg-gray-700 dark:bg-white/[0.05] dark:text-gray-400 dark:hover:bg-white/[0.07] dark:hover:text-gray-300">
                <svg class="fill-current" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" >
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M6.04289 16.5418C5.65237 16.9323 5.65237 17.5655 6.04289 17.956C6.43342 18.3465 7.06658 18.3465 7.45711 17.956L11.9987 13.4144L16.5408 17.9565C16.9313 18.347 17.5645 18.347 17.955 17.9565C18.3455 17.566 18.3455 16.9328 17.955 16.5423L13.4129 12.0002L17.955 7.45808C18.3455 7.06756 18.3455 6.43439 17.955 6.04387C17.5645 5.65335 16.9313 5.65335 16.5408 6.04387L11.9987 10.586L7.45711 6.04439C7.06658 5.65386 6.43342 5.65386 6.04289 6.04439C5.65237 6.43491 5.65237 7.06808 6.04289 7.4586L10.5845 12.0002L6.04289 16.5418Z" fill="" />
                </svg>
            </button>
            <div class="px-2 pr-14">
                <h4 class="mb-2 text-2xl font-semibold text-gray-800 dark:text-white/90">
                    Modifier les Informations personnels
                </h4>
                <p class="mb-6 text-sm text-gray-500 dark:text-gray-400 lg:mb-7">
                    Profile personnel
                </p>
            </div>
            <!-- Dans la partie formulaire du modal, remplacez par : -->
            <form class="flex flex-col" method="post" enctype="multipart/form-data">
                <div class="custom-scrollbar h-[450px] overflow-y-auto px-2">
                    <div class="mt-0 gap-y-5">
                        <div class="col-span-2 lg:col-span-1 mb-6">
                            <!-- Conteneur principal -->
                            <div class="space-y-3" style="width: 15rem;height: 15rem;">
                                <!-- Zone d'upload/prévisualisation (carrée) -->
                                <div class="relative group">
                                    <!-- Prévisualisation avec ratio 1:1 -->
                                    <label class="flex items-center justify-center w-full aspect-square overflow-hidden border-2 border-dashed border-gray-300 dark:border-gray-600 cursor-pointer hover:border-brand-500 transition-colors duration-200 bg-gray-50 dark:bg-gray-800">
                                        <!-- Image actuelle ou sélectionnée -->
                                        <img id="imagePreview" 
                                            src="<?= !empty($row->image) ? htmlspecialchars($row->image) : 'data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIyMDAiIGhlaWdodD0iMjAwIiB2aWV3Qm94PSIwIDAgMjQgMjQiIGZpbGw9Im5vbmUiIHN0cm9rZT0iI2QxZDFkMSIgc3Ryb2tlLXdpZHRoPSIxIiBzdHJva2UtbGluZWNhcD0icm91bmQiIHN0cm9rZS1saW5lam9pbj0icm91bmQiPjxyZWN0IHg9IjMiIHk9IjMiIHdpZHRoPSIxOCIgaGVpZ2h0PSIxOCIgcng9IjIiIHJ5PSIyIj48L3JlY3Q+PGNpcmNsZSBjeD0iOC41IiBjeT0iOC41IiByPSIyLjUiPjwvY2lyY2xlPjxwb2x5bGluZSBwb2ludHM9IjIxIDE1IDE2IDEwIDUgMjEiPjwvcG9seWxpbmU+PC9zdmc+' ?>" 
                                            alt="Preview" 
                                            class="absolute inset-0 w-full h-full object-cover transition-opacity duration-300 <?= empty($row->image) ? 'opacity-0' : 'opacity-100' ?>" style="object-fit: cover;z-index: 10;">
                                        
                                        <!-- Overlay et instructions -->
                                        <div id="uploadOverlay" class="absolute inset-0 flex flex-col items-center justify-center p-4 text-center bg-black bg-opacity-0 group-hover:bg-opacity-20 transition-all duration-300">
                                            <svg class="w-8 h-8 mb-2 text-gray-400 dark:text-gray-300 transition-transform duration-300 group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                            <span class="text-sm font-medium text-gray-700 dark:text-gray-200">Cliquer pour modifier</span>
                                            <span class="text-xs text-gray-500 dark:text-gray-300">Formats: JPG, PNG, WEBP</span>
                                        </div>
                                        
                                        <input id="imageUpload" type="file" name="image" accept=".jpg,.jpeg,.png,.webp" class="hidden">
                                    </label>
                                </div>

                                <!-- Messages d'état -->
                                <div id="errorMessage" class="text-xs text-red-500 dark:text-red-400 text-center hidden"></div>
                                <div id="sizeInfo" class="text-xs text-gray-500 dark:text-gray-400 text-center">Taille recommandée : 600×600px</div>
                            </div>
                        </div>


                        <div class="grid grid-cols-1 gap-x-6 gap-y-5 lg:grid-cols-2">
                            <div class="col-span-2 lg:col-span-1">
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                    Nom
                                </label>
                                <input type="text" name="nom" value="<?= $row->nom ?? '' ?>" class="dark:bg-dark-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent bg-none px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800">
                                <?php if (!empty($errors['nom'])) : ?>
                                    <p class="text-theme-xs text-error-500 mt-1.5"><?= $errors['nom'] ?></p>
                                <?php endif; ?>
                            </div>

                            <div class="col-span-2 lg:col-span-1">
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                    Prenom
                                </label>
                                <input type="text" name="prenom" value="<?= $row->prenom ?? '' ?>" class="dark:bg-dark-900 h-11 w-full appearance-none rounded-lg border border-gray-300 bg-transparent bg-none px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800">
                                <?php if (!empty($errors['prenom'])) : ?>
                                    <p class="text-theme-xs text-error-500 mt-1.5"><?= $errors['prenom'] ?></p>
                                <?php endif; ?>
                            </div>

                            <div class="col-span-2 lg:col-span-1">
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                    Addresse Email 
                                </label>
                                <input type="email" name="email" value="<?= $row->email ?? '' ?>" class="dark:bg-dark-900 h-11 w-full appearance-none rounded-lg border border-gray-300 bg-transparent bg-none px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800">
                                <?php if (!empty($errors['email'])) : ?>
                                    <p class="text-theme-xs text-error-500 mt-1.5"><?= $errors['email'] ?></p>
                                <?php endif; ?>
                            </div>

                            <div class="col-span-2 lg:col-span-1">
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                    Mot de Passe (Optionnel)
                                </label>
                                <input type="password" name="password" placeholder="Laissez vide pour ne pas modifier" class="dark:bg-dark-900 h-11 w-full appearance-none rounded-lg border border-gray-300 bg-transparent bg-none px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800">
                            </div>
                        </div>
                    </div>
                </div> 
                <div class="flex items-center gap-3 px-2 mt-6 lg:justify-end">
                    <button @click="isProfileInfoModal = false" type="button" class="flex w-full justify-center rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.03] sm:w-auto">
                        Annuler
                    </button>
                    <button type="submit" class="flex w-full justify-center rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-brand-600 sm:w-auto">
                        Enregistrer les modifications
                    </button>
                </div> 
            </form>
        </div>
    </div>

    <!-- Script to handle image  -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const imageUpload = document.getElementById('imageUpload');
            const imagePreview = document.getElementById('imagePreview');
            const uploadOverlay = document.getElementById('uploadOverlay');
            const errorMessage = document.getElementById('errorMessage');
            
            // Garantir la prévisualisation dans tous les cas
            function ensurePreviewVisibility() {
                if (imagePreview.src && !imagePreview.src.includes('data:image/svg+xml')) {
                    imagePreview.classList.remove('opacity-0');
                    imagePreview.classList.add('opacity-100');
                }
            }

            // Vérification initiale
            ensurePreviewVisibility();

            // Gestion du changement de fichier
            imageUpload.addEventListener('change', function(e) {
                errorMessage.classList.add('hidden');
                const file = e.target.files[0];
                
                if (!file) {
                    ensurePreviewVisibility();
                    return;
                }

                // Validation du type
                const validTypes = ['image/jpeg', 'image/png', 'image/webp'];
                if (!validTypes.includes(file.type)) {
                    errorMessage.textContent = "Format non supporté. Utilisez JPG, PNG ou WEBP.";
                    errorMessage.classList.remove('hidden');
                    return;
                }

                // Validation de la taille (max 5MB)
                if (file.size > 5 * 1024 * 1024) {
                    errorMessage.textContent = "L'image est trop lourde (max 5MB)";
                    errorMessage.classList.remove('hidden');
                    return;
                }

                // Prévisualisation garantie
                const reader = new FileReader();
                reader.onload = function(e) {
                    imagePreview.src = e.target.result;
                    ensurePreviewVisibility();
                };
                reader.onerror = function() {
                    imagePreview.src = URL.createObjectURL(file);
                    ensurePreviewVisibility();
                };
                reader.readAsDataURL(file);
            });

            // Gestion du clic sur la prévisualisation
            imagePreview.addEventListener('click', function(e) {
                e.preventDefault();
                imageUpload.click();
            });
        });
    </script>
                        
<?php $this->view('footer'); ?>