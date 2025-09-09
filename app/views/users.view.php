<?php $this->view("head"); 
    $url1 = URL(0);
    $url2 = URL(1);
    $url3 = URL(2);
?>

<?php if (isset($action) && $action == 'add') : ?>
    <div class="p-4 mx-auto max-w-(--breakpoint-2xl) md:p-6">
        <div class="space-y-6">
            <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
                <div class="p-5 space-y-6 border-t border-gray-100 dark:border-gray-800 sm:p-6">
                    <form method="post" enctype="multipart/form-data">
                        <h4 class="mb-6 text-lg font-medium text-gray-800 dark:text-white/90">Ajouter un Utilisateur</h4>
                        <!-- Error Message -->
                        <?php if (!empty($errors)): ?>
                            <div class="rounded-xl mb-5 border border-error-500 bg-error-50 p-4 dark:border-error-500/30 dark:bg-error-500/15">
                                <div class="flex items-start gap-3">
                                    <div>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">
                                            <?= implode("<br>", $errors) ?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                        <div class="-mx-2.5 flex flex-wrap gap-y-5">
                            <div class="w-full md:w-1/2 px-2.5">
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Nom</label>
                                <input type="text" name="nom" value="<?= old_value('nom') ?>" placeholder="John"
                                    class="dark:bg-dark-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                                <?php if (!empty($errors['nom'])) : ?>
                                    <p class="text-theme-xs text-error-500 mt-1.5"><?= $errors['nom'] ?></p>
                                <?php endif; ?>
                            </div>
                            <div class="w-full md:w-1/2 px-2.5">
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Prénom</label>
                                <input type="text" name="prenom" value="<?= old_value('prenom') ?>" placeholder="Doe"
                                    class="dark:bg-dark-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                                <?php if (!empty($errors['prenom'])) : ?>
                                    <p class="text-theme-xs text-error-500 mt-1.5"><?= $errors['prenom'] ?></p>
                                <?php endif; ?>
                            </div>
                            <div class="w-full md:w-1/2 px-2.5">
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Adresse Email</label>
                                <input type="email" name="email" value="<?= old_value('email') ?>" placeholder="randomuser@pimjo.com"
                                    class="dark:bg-dark-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                                <?php if (!empty($errors['email'])) : ?>
                                    <p class="text-theme-xs text-error-500 mt-1.5"><?= $errors['email'] ?></p>
                                <?php endif; ?>
                            </div>
                            <div class="w-full md:w-1/2 px-2.5">
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Rôle</label>
                                <div x-data="{ isOptionSelected: false }" class="relative z-20 bg-transparent">
                                    <select name="role"
                                        class="dark:bg-dark-900 z-20 h-11 w-full appearance-none rounded-lg border border-gray-300 bg-transparent bg-none px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs dark:border-gray-700 dark:bg-gray-900 dark:text-white/90"
                                        :class="isOptionSelected && 'text-gray-500 dark:text-gray-400'"
                                        @change="isOptionSelected = true">
                                        <option value="employe" <?= old_select('role', 'employe') ?>>Employé</option>
                                        <option value="chef" <?= old_select('role', 'chef') ?>>Chef</option>
                                        <option value="secretaire" <?= old_select('role', 'secretaire') ?>>Secrétaire</option>
                                        <option value="admin" <?= old_select('role', 'admin') ?>>Admin</option>
                                    </select>
                                    <span class="absolute z-30 text-gray-500 -translate-y-1/2 right-4 top-1/2 dark:text-gray-400">
                                        <svg class="stroke-current" width="20" height="20" viewBox="0 0 20 20" fill="none">
                                            <path d="M4.79175 7.396L10.0001 12.6043L15.2084 7.396" stroke-width="1.5" stroke-linecap="round"
                                                stroke-linejoin="round"></path>
                                        </svg>
                                    </span>
                                </div>
                                <?php if (!empty($errors['role'])) : ?>
                                    <p class="text-theme-xs text-error-500 mt-1.5"><?= $errors['role'] ?></p>
                                <?php endif; ?>
                            </div>
                            <div class="w-full md:w-1/2 px-2.5">
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Mot de passe</label>
                                <input type="password" name="password" placeholder="********" class="dark:bg-dark-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                                <?php if (!empty($errors['password'])) : ?>
                                    <p class="text-theme-xs text-error-500 mt-1.5"><?= $errors['password'] ?></p>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="flex items-center justify-end w-full gap-3 mt-6">
                            <a href="<?= ROOT ?>/users"
                                class="flex w-full justify-center rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm font-medium text-gray-700 shadow-theme-xs transition-colors hover:bg-gray-50 hover:text-gray-800 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.03] dark:hover:text-gray-200 sm:w-auto">
                                Annuler
                            </a>
                            <button type="submit"
                                class="flex justify-center w-full px-4 py-3 text-sm font-medium text-white rounded-lg bg-brand-500 shadow-theme-xs hover:bg-brand-600 sm:w-auto">
                                Ajouter
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

<?php elseif ($action == 'edit') : ?>
    <main>
        <div class="p-4 mx-auto max-w-(--breakpoint-2xl) md:p-6">
            <div class="space-y-6">
                <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
                    <div class="p-5 space-y-6 border-t border-gray-100 dark:border-gray-800 sm:p-6">
                        <form method="post" enctype="multipart/form-data">
                            <h4 class="mb-6 text-lg font-medium text-gray-800 dark:text-white/90">Modifier les Informations personnelles</h4>

                            <!-- Error Message -->
                            <?php if (!empty($errors)): ?>
                                <div class="rounded-xl mb-5 border border-error-500 bg-error-50 p-4 dark:border-error-500/30 dark:bg-error-500/15">
                                    <div class="flex items-start gap-3">
                                        <div>
                                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                                <?= implode("<br>", $errors) ?>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                            <div class="-mx-2.5 flex flex-wrap gap-y-5">
                                <div class="w-full md:w-1/2 px-2.5">
                                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Nom</label>
                                    <input type="text" name="nom" value="<?= old_value('nom', $data['row']->nom) ?>" placeholder="John" class="dark:bg-dark-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                                    <?php if (!empty($errors['nom'])) : ?>
                                        <p class="text-theme-xs text-error-500 mt-1.5"><?= $errors['nom'] ?></p>
                                    <?php endif; ?>
                                </div>
                                <div class="w-full md:w-1/2 px-2.5">
                                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Prénom</label>
                                    <input type="text" name="prenom" value="<?= old_value('prenom', $data['row']->prenom) ?>" placeholder="Doe"
                                        class="dark:bg-dark-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                                    <?php if (!empty($errors['prenom'])) : ?>
                                        <p class="text-theme-xs text-error-500 mt-1.5"><?= $errors['prenom'] ?></p>
                                    <?php endif; ?>
                                </div>
                                <div class="w-full md:w-1/2 px-2.5">
                                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Adresse Email</label>
                                    <input type="email" name="email" value="<?= old_value('email', $data['row']->email) ?>" placeholder="randomuser@pimjo.com"
                                        class="dark:bg-dark-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                                    <?php if (!empty($errors['email'])) : ?>
                                        <p class="text-theme-xs text-error-500 mt-1.5"><?= $errors['email'] ?></p>
                                    <?php endif; ?>
                                </div>
                                <div class="w-full md:w-1/2 px-2.5">
                                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Rôle</label>
                                    <div x-data="{ isOptionSelected: false }" class="relative z-20 bg-transparent">
                                        <select name="role"
                                            class="dark:bg-dark-900 z-20 h-11 w-full appearance-none rounded-lg border border-gray-300 bg-transparent bg-none px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90"
                                            :class="isOptionSelected && 'text-gray-500 dark:text-gray-400'"
                                            @change="isOptionSelected = true">
                                            <option value="employe" <?= old_select('role', 'employe') ?>>Employé</option>
                                            <option value="chef" <?= old_select('role', 'chef') ?>>Chef</option>
                                            <option value="secretaire" <?= old_select('role', 'secretaire') ?>>Secrétaire</option>
                                            <option value="admin" <?= old_select('role', 'admin') ?>>Admin</option>
                                        </select>
                                        <span class="absolute z-30 text-gray-500 -translate-y-1/2 right-4 top-1/2 dark:text-gray-400">
                                            <svg class="stroke-current" width="20" height="20" viewBox="0 0 20 20" fill="none">
                                                <path d="M4.79175 7.396L10.0001 12.6043L15.2084 7.396" stroke-width="1.5" stroke-linecap="round"
                                                    stroke-linejoin="round"></path>
                                            </svg>
                                        </span>
                                    </div>
                                </div>
                                <div class="w-full md:w-1/2 px-2.5">
                                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Mot de passe</label>
                                    <input type="password" name="password" placeholder="********" class="dark:bg-dark-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                                </div>
                            </div>

                            <!-- Boutons -->
                            <div class="flex items-center justify-end w-full gap-3 mt-6">
                                <a href="<?= ROOT ?>/users"
                                    class="flex w-full justify-center rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm font-medium text-gray-700 shadow-theme-xs transition-colors hover:bg-gray-50 hover:text-gray-800 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.03] dark:hover:text-gray-200 sm:w-auto">
                                    Annuler
                                </a>
                                <button type="submit"
                                    class="flex justify-center w-full px-4 py-3 text-sm font-medium text-white rounded-lg bg-brand-500 shadow-theme-xs hover:bg-brand-600 sm:w-auto">
                                    Modifier
                                </button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </main>

<?php elseif ($action == 'delete') : ?>
    <main>
        <div class="p-4 mx-auto max-w-(--breakpoint-2xl) md:p-6">
            <div class="space-y-6">
                <?php if (!empty($row)) : ?>
                    <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
                        <div class="p-5 space-y-6 border-t border-gray-100 dark:border-gray-800 sm:p-6">
                            <form method="post" enctype="multipart/form-data">
                                <h4 class="mb-6 text-lg font-medium text-gray-800 dark:text-white/90">Supprimer un Utilisateur</h4>
                                <div class="-mx-2.5 flex flex-wrap gap-y-5">
                                    <div class="w-full md:w-1/2">
                                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Nom</label>
                                        <div class="dark:bg-dark-900 h-11 w-full rounded-lg border border-gray-200 bg-transparent px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                                            <?= old_value('nom', $data['row']->nom) ?>
                                        </div>
                                    </div>
                                    <div class="w-full md:w-1/2">
                                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Prénom</label>
                                        <div class="dark:bg-dark-900 h-11 w-full rounded-lg border border-gray-200 bg-transparent px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                                            <?= old_value('prenom', $data['row']->prenom) ?>
                                        </div>
                                    </div>
                                    <div class="w-full md:w-1/2">
                                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Adresse Email</label>
                                        <div class="dark:bg-dark-900 h-11 w-full rounded-lg border border-gray-200 bg-transparent px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                                            <?= old_value('email', $data['row']->email) ?>
                                        </div>
                                    </div>
                                    <div class="w-full md:w-1/2">
                                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Rôle</label>
                                        <div class="dark:bg-dark-900 h-11 w-full rounded-lg border border-gray-200 bg-transparent px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                                            <?= htmlspecialchars($user->role ?? '') ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex items-center justify-end w-full gap-3 mt-6">
                                    <a href="<?= ROOT ?>/users"
                                        class="flex w-full justify-center rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm font-medium text-gray-700 shadow-theme-xs transition-colors hover:bg-gray-50 hover:text-gray-800 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.03] dark:hover:text-gray-200 sm:w-auto">
                                        Annuler
                                    </a>
                                    <button type="submit"
                                        class="flex justify-center w-full px-4 py-3 text-sm font-medium text-white rounded-lg bg-brand-500 shadow-theme-xs hover:bg-brand-600 sm:w-auto">
                                        Supprimer
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </main>
<?php else : ?>
    <main>
        <div class="p-4 mx-auto max-w-(--breakpoint-2xl) md:p-6">
            <!-- Breadcrumb Start -->
            <div x-data="{ pageName: `Utilisateurs`}">
                <div class="mb-6 flex flex-wrap items-center justify-between gap-3">
                    <h2 class="text-xl font-semibold text-gray-800 dark:text-white/90" x-text="pageName">Utilisateurs</h2>

                    <nav>
                        <a href="<?= ROOT ?>/users/user/add" class="inline-flex items-center gap-2 px-4 py-3 text-sm font-medium text-white transition rounded-lg bg-brand-500 shadow-theme-xs hover:bg-brand-600">
                            Ajouter un utilisateur
                            <svg class="stroke-current" width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M8 3.3335V12.6668M3.3335 8H12.6668" stroke="" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"></path>
                            </svg>
                        </a>
                    </nav>
                </div>
            </div>
            <!-- Breadcrumb End -->
            <div class="space-y-5 sm:space-y-6">
                <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
                    <div class="">
                        <?php if (!empty($rows)) : ?>
                            <table class="min-w-full">
                                <!-- table header start -->
                                <thead>
                                    <tr class="border-b border-gray-100 dark:border-gray-800">
                                        <th class="px-5 py-3 sm:px-6">
                                            <div class="flex items-center">
                                                <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">
                                                    User
                                                </p>
                                            </div>
                                        </th>
                                        <th class="px-5 py-3 sm:px-6">
                                            <div class="flex items-center">
                                            <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">
                                                Email
                                            </p>
                                            </div>
                                        </th>
                                        <th class="px-5 py-3 sm:px-6">
                                            <div class="flex items-center">
                                                <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">
                                                    Created At
                                                </p>
                                            </div>
                                        </th>
                                        <th class="px-5 py-3 sm:px-6">
                                            <div class="flex items-center">
                                                <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">
                                                    Action
                                                </p>
                                            </div>
                                        </th>
                                    </tr>
                                </thead>
                                <!-- table body start -->
                                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                                    <?php foreach ($rows as $row) : ?>
                                        <tr>
                                            <td class="px-5 py-4 sm:px-6">
                                                <div class="flex items-center">
                                                    <div class="flex items-center gap-3">
                                                        <div class="w-10 h-10 overflow-hidden rounded-full">
                                                            <img src="<?= get_image(!empty($row->image) ? $row->image : '') ?>" alt="brand"  style="height: 100%;object-fit:cover;">
                                                        </div>

                                                        <div>
                                                            <span class="block font-medium text-gray-800 text-theme-sm dark:text-white/90">
                                                                <?= $row->nom ?> <?= $row->prenom ?>
                                                            </span>
                                                            <span class="block text-gray-500 text-theme-xs dark:text-gray-400">
                                                                <?= $row->role ?>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-5 py-4 sm:px-6">
                                                <div class="flex items-center">
                                                    <p class="text-gray-500 text-theme-sm dark:text-gray-400">
                                                        <?= $row->email ?>
                                                    </p>
                                                </div>
                                            </td>
                                            <td class="px-5 py-4 sm:px-6">
                                                <div class="flex items-center">
                                                    <p class="text-gray-500 text-theme-sm dark:text-gray-400">
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
                                                <div class="col-span-1 flex items-center py-[17.5px]">
                                                    <div class="flex w-full items-center gap-4">
                                                        <a href="<?= ROOT ?>/users/user/edit/<?= $row->id ?>" class="text-gray-500 hover:text-gray-800 dark:text-gray-400 dark:hover:text-white/90">
                                                            <svg class="fill-current" width="21" height="21" viewBox="0 0 21 21" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                <path fill-rule="evenodd" clip-rule="evenodd" d="M17.0911 3.53206C16.2124 2.65338 14.7878 2.65338 13.9091 3.53206L5.6074 11.8337C5.29899 12.1421 5.08687 12.5335 4.99684 12.9603L4.26177 16.445C4.20943 16.6931 4.286 16.9508 4.46529 17.1301C4.64458 17.3094 4.90232 17.3859 5.15042 17.3336L8.63507 16.5985C9.06184 16.5085 9.45324 16.2964 9.76165 15.988L18.0633 7.68631C18.942 6.80763 18.942 5.38301 18.0633 4.50433L17.0911 3.53206ZM14.9697 4.59272C15.2626 4.29982 15.7375 4.29982 16.0304 4.59272L17.0027 5.56499C17.2956 5.85788 17.2956 6.33276 17.0027 6.62565L16.1043 7.52402L14.0714 5.49109L14.9697 4.59272ZM13.0107 6.55175L6.66806 12.8944C6.56526 12.9972 6.49455 13.1277 6.46454 13.2699L5.96704 15.6283L8.32547 15.1308C8.46772 15.1008 8.59819 15.0301 8.70099 14.9273L15.0436 8.58468L13.0107 6.55175Z" fill=""></path>
                                                            </svg>
                                                        </a>
                                                        <a href="<?= ROOT ?>/users/user/delete/<?= $row->id ?>" class="text-gray-500 hover:text-error-500 dark:text-gray-400 dark:hover:text-error-500">
                                                            <svg class="fill-current" width="21" height="21" viewBox="0 0 21 21" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                <path fill-rule="evenodd" clip-rule="evenodd" d="M7.04142 4.29199C7.04142 3.04935 8.04878 2.04199 9.29142 2.04199H11.7081C12.9507 2.04199 13.9581 3.04935 13.9581 4.29199V4.54199H16.1252H17.166C17.5802 4.54199 17.916 4.87778 17.916 5.29199C17.916 5.70621 17.5802 6.04199 17.166 6.04199H16.8752V8.74687V13.7469V16.7087C16.8752 17.9513 15.8678 18.9587 14.6252 18.9587H6.37516C5.13252 18.9587 4.12516 17.9513 4.12516 16.7087V13.7469V8.74687V6.04199H3.8335C3.41928 6.04199 3.0835 5.70621 3.0835 5.29199C3.0835 4.87778 3.41928 4.54199 3.8335 4.54199H4.87516H7.04142V4.29199ZM15.3752 13.7469V8.74687V6.04199H13.9581H13.2081H7.79142H7.04142H5.62516V8.74687V13.7469V16.7087C5.62516 17.1229 5.96095 17.4587 6.37516 17.4587H14.6252C15.0394 17.4587 15.3752 17.1229 15.3752 16.7087V13.7469ZM8.54142 4.54199H12.4581V4.29199C12.4581 3.87778 12.1223 3.54199 11.7081 3.54199H9.29142C8.87721 3.54199 8.54142 3.87778 8.54142 4.29199V4.54199ZM8.8335 8.50033C9.24771 8.50033 9.5835 8.83611 9.5835 9.25033V14.2503C9.5835 14.6645 9.24771 15.0003 8.8335 15.0003C8.41928 15.0003 8.0835 14.6645 8.0835 14.2503V9.25033C8.0835 8.83611 8.41928 8.50033 8.8335 8.50033ZM12.9168 9.25033C12.9168 8.83611 12.581 8.50033 12.1668 8.50033C11.7526 8.50033 11.4168 8.83611 11.4168 9.25033V14.2503C11.4168 14.6645 11.7526 15.0003 12.1668 15.0003C12.581 15.0003 12.9168 14.6645 12.9168 14.2503V9.25033Z" fill=""></path>
                                                            </svg>
                                                        </a>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        <?php else : ?>
                            <div class="p-6 text-center" style="max-width: 70%;margin: 0 auto;">
                                <p class="text-gray-500 font-bold text-theme-sm dark:text-gray-400">
                                    No users found !
                                </p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </main>
<?php endif; ?> 

<?php $this->view("footer"); ?>