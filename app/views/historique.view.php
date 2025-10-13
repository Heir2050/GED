<?php $this->view('head'); ?>

<main>
    <div class="p-4 mx-auto max-w-(--breakpoint-2xl) md:p-6">
        <!-- Breadcrumb Start -->
        <div class="mb-6 flex flex-wrap items-center justify-between gap-3">
            <h2 class="text-xl font-semibold text-gray-800 dark:text-white/90">
                Historique des Actions
            </h2>
            <nav class="flex gap-6">
                <!-- Boutons de filtrage optionnels -->
            </nav>
        </div>
        <!-- Breadcrumb End -->

        <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
            <?php if (!empty($historique)): ?>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead>
                            <tr>
                                <?php if (isset($is_admin) && $is_admin): ?>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-400">
                                        Utilisateur
                                    </th>
                                <?php endif; ?>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-400">
                                    Action
                                </th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-400">
                                    Détails
                                </th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-400">
                                    Date
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            <?php foreach ($historique as $action): ?>
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50">
                                    <?php if (isset($is_admin) && $is_admin): ?>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-800 dark:text-white/90">
                                            <?= htmlspecialchars($action->employe_prenom . ' ' . $action->employe_nom) ?>
                                            <?php if ($action->employe_cible_id): ?>
                                                <br>
                                                <span class="text-xs text-gray-500 dark:text-gray-400">
                                                    → <?= htmlspecialchars($action->employe_cible_prenom . ' ' . $action->employe_cible_nom) ?>
                                                </span>
                                            <?php endif; ?>
                                        </td>
                                    <?php endif; ?>
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        <?php
                                        // Utiliser type_action_code au lieu de type_action
                                        $action_code = $action->type_action_code ?? '';
                                        $action_label = $action_labels[$action_code] ?? $action_code;
                                        $action_class = $action_classes[$action_code] ?? 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200';
                                        ?>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium <?= $action_class ?>">
                                            <?= htmlspecialchars($action_label) ?>
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-800 dark:text-white/90">
                                        <?= htmlspecialchars($action->details) ?>
                                        <?php if ($action->dossier_nom): ?>
                                            <br>
                                            <span class="text-xs text-gray-500 dark:text-gray-400">
                                                Dossier: <?= htmlspecialchars($action->dossier_nom) ?>
                                            </span>
                                        <?php endif; ?>
                                        <?php if ($action->document_nom): ?>
                                            <br>
                                            <span class="text-xs text-gray-500 dark:text-gray-400">
                                                Document: <?= htmlspecialchars($action->document_nom) ?>
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                        <?= date('d/m/Y H:i', strtotime($action->date_action)) ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="text-center py-8">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">Aucune action enregistrée</h3>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        Aucune action n'a été enregistrée dans l'historique pour le moment.
                    </p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</main>

<?php $this->view('footer'); ?>