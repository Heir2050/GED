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
            <div x-data="{ pageName: '<?= !empty($service_nom) ? $service_nom : 'Dashboard' ?>'}">
                <div class="mb-6 flex flex-wrap items-center justify-between gap-3">
                    <h2 class="text-xl font-semibold text-gray-800 dark:text-white/90" x-text="pageName">
                        <?= !empty($service_nom) ? htmlspecialchars($service_nom) : 'Dashboard' ?>
                    </h2>
                    <nav class="flex gap-6">
                    </nav>
                </div>
            </div>
            <!-- Breadcrumb End -->
            
            <div class="grid grid-cols-12 gap-4 md:gap-6">
                <div class="col-span-12 space-y-6">
                    <!-- Metric Group One -->
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4 md:gap-6">
                        <!-- Card: Dossiers Actifs -->
                        <div class="rounded-2xl border border-gray-200 bg-white px-6 pb-5 pt-6 dark:border-gray-800 dark:bg-white/[0.03]" style="padding-top: 2rem;">
                            <div class="mb-6 flex items-center gap-3">
                                <div class="h-10 w-10">
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
                                <div>
                                    <h3 class="text-base font-semibold text-gray-800 dark:text-white/90">
                                        DOSSIERS ACTIFS
                                    </h3>
                                </div>
                            </div>
                            <div class="flex items-end justify-between">
                                <div>
                                    <h4 class="text-lg font-semibold text-gray-800 dark:text-white/90">
                                        <?= (int)($folders_count ?? 0) ?>
                                    </h4>
                                </div>
                            </div>
                        </div>

                        <!-- Card: Dossiers Archivés -->
                        <div class="rounded-2xl border border-gray-200 bg-white px-6 pb-5 pt-6 dark:border-gray-800 dark:bg-white/[0.03]" style="padding-top: 2rem;">
                            <div class="mb-6 flex items-center gap-3">
                                <div class="h-10 w-10">
                                    <svg width="36" height="36" xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="100" height="100" viewBox="0 0 64 64">
                                        <radialGradient id="JgiLTpLYUVYqJAJq8M0ssa_118961_gr1" cx="32" cy="32" r="33.002" gradientUnits="userSpaceOnUse" spreadMethod="reflect"><stop offset="0" stop-color="#efdcb1"></stop><stop offset="0" stop-color="#f2e0bb"></stop><stop offset=".011" stop-color="#f2e0bc"></stop><stop offset=".362" stop-color="#f9edd2"></stop><stop offset=".699" stop-color="#fef4df"></stop><stop offset="1" stop-color="#fff7e4"></stop></radialGradient><path fill="url(#JgiLTpLYUVYqJAJq8M0ssa_118961_gr1)" d="M55.454,45.668c0.5,0.898,1.504,1.337,2.532,1.332c1.75-0.008,3.153,1.483,3.002,3.262 C60.855,51.838,59.434,53,57.852,53L48,53v1H26h-8H7c-1.71,0-3.086-1.431-2.996-3.161C4.089,49.216,5.545,48,7.17,48L8,48 c1.215,0,2.176-1.083,1.973-2.336C9.813,44.681,8.889,44,7.893,44L3,44c-1.71,0-3.086-1.431-2.996-3.161 C0.089,39.216,1.545,38,3.17,38l6.33,0c1.381,0,2.5-1.119,2.5-2.5v0c0-1.381-1.119-2.5-2.5-2.5h0C8.119,33,7,31.881,7,30.5v0 C7,29.119,8.119,28,9.5,28H18v-7h-7.5c-1.995,0-3.601-1.67-3.495-3.688C7.104,15.419,8.803,14,10.698,14L18,14v-4h24h6h8.5 c1.995,0,3.601,1.67,3.495,3.688C59.896,15.581,58.197,17,56.302,17L53.5,17c-1.995,0-3.601,1.67-3.495,3.688 C50.104,22.581,51.803,24,53.698,24L56,24c1.777,0,3.194,1.546,2.978,3.366c-0.179,1.509-1.546,2.572-3.064,2.635 c-1.197,0.05-2.122,1.153-1.874,2.406C54.228,33.355,55.123,34,56.089,34H60.5c1.995,0,3.601,1.67,3.495,3.688 C63.896,39.581,62.197,41,60.302,41L58,41C55.808,41,54.166,43.35,55.454,45.668z M2.5,33L2.5,33C3.881,33,5,31.881,5,30.5v0 C5,29.119,3.881,28,2.5,28h0C1.119,28,0,29.119,0,30.5v0C0,31.881,1.119,33,2.5,33z M2.5,33L2.5,33C3.881,33,5,31.881,5,30.5v0 C5,29.119,3.881,28,2.5,28h0C1.119,28,0,29.119,0,30.5v0C0,31.881,1.119,33,2.5,33z"></path><linearGradient id="JgiLTpLYUVYqJAJq8M0ssb_118961_gr2" x1="33" x2="33" y1="58" y2="6" gradientUnits="userSpaceOnUse" spreadMethod="reflect"><stop offset="0" stop-color="#41bfec"></stop><stop offset=".232" stop-color="#4cc5ef"></stop><stop offset=".644" stop-color="#6bd4f6"></stop><stop offset="1" stop-color="#8ae4fd"></stop></linearGradient><path fill="url(#JgiLTpLYUVYqJAJq8M0ssb_118961_gr2)" d="M50,58H16c-1.657,0-3-1.343-3-3V9c0-1.657,1.343-3,3-3h20.757 c0.796,0,1.559,0.316,2.121,0.879l13.243,13.243C52.684,20.684,53,21.447,53,22.243V55C53,56.657,51.657,58,50,58z"></path><linearGradient id="JgiLTpLYUVYqJAJq8M0ssc_118961_gr3" x1="38.879" x2="46.454" y1="20.121" y2="12.546" gradientUnits="userSpaceOnUse" spreadMethod="reflect"><stop offset="0" stop-color="#c6effd"></stop><stop offset=".375" stop-color="#b7ecfd"></stop><stop offset="1" stop-color="#95e6fd"></stop></linearGradient><path fill="url(#JgiLTpLYUVYqJAJq8M0ssc_118961_gr3)" d="M52.121,20.122L38.878,6.879c-0.255-0.255-0.556-0.452-0.878-0.6V18c0,1.657,1.343,3,3,3 h11.721C52.574,20.678,52.377,20.377,52.121,20.122z"></path><linearGradient id="JgiLTpLYUVYqJAJq8M0ssd_118961_gr4" x1="45.5" x2="45.5" y1="24.083" y2="18.083" gradientUnits="userSpaceOnUse" spreadMethod="reflect"><stop offset="0" stop-color="#42c6ee"></stop><stop offset=".48" stop-color="#3bc3ed"></stop><stop offset="1" stop-color="#2ebeea"></stop></linearGradient><path fill="url(#JgiLTpLYUVYqJAJq8M0ssd_118961_gr4)" d="M41,21c-1.657,0-3-1.343-3-3v3c0,1.657,1.343,3,3,3h12v-1.757 c0-0.434-0.102-0.855-0.279-1.243H41z"></path><linearGradient id="JgiLTpLYUVYqJAJq8M0sse_118961_gr5" x1="22" x2="22" y1="21" y2="6" gradientUnits="userSpaceOnUse" spreadMethod="reflect"><stop offset="0" stop-color="#81dcf7"></stop><stop offset=".48" stop-color="#8ce1f9"></stop><stop offset="1" stop-color="#9ee8fd"></stop></linearGradient><path fill="url(#JgiLTpLYUVYqJAJq8M0sse_118961_gr5)" d="M21.5,21H13V9c0-1.657,1.343-3,3-3h9.5C26.881,6,28,7.119,28,8.5v0 c0,1.381-1.119,2.5-2.5,2.5h-5c-1.381,0-2.5,1.119-2.5,2.5v0c0,1.381,1.119,2.5,2.5,2.5h1c1.381,0,2.5,1.119,2.5,2.5v0 C24,19.881,22.881,21,21.5,21z M29,17c-1.105,0-2,0.895-2,2s0.895,2,2,2c1.105,0,2-0.895,2-2S30.105,17,29,17z"></path><linearGradient id="JgiLTpLYUVYqJAJq8M0ssf_118961_gr6" x1="41.5" x2="41.5" y1="41" y2="58" gradientUnits="userSpaceOnUse" spreadMethod="reflect"><stop offset="0" stop-color="#47c3ee"></stop><stop offset=".98" stop-color="#32b5e8"></stop><stop offset="1" stop-color="#32b5e8"></stop></linearGradient><path fill="url(#JgiLTpLYUVYqJAJq8M0ssf_118961_gr6)" d="M44,41l9,0v14c0,1.657-1.343,3-3,3H33c-1.657,0-3-1.343-3-3v0c0-1.657,1.343-3,3-3l11.5,0 c1.381,0,2.5-1.119,2.5-2.5v0c0-1.381-1.119-2.5-2.5-2.5H44c-1.657,0-3-1.343-3-3v0C41,42.343,42.343,41,44,41z M36,41 c-1.657,0-3,1.343-3,3s1.343,3,3,3s3-1.343,3-3S37.657,41,36,41z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-base font-semibold text-gray-800 dark:text-white/90">
                                        DOSSIERS ARCHIVÉS
                                    </h3>
                                </div>
                            </div>
                            <div class="flex items-end justify-between">
                                <div>
                                    <h4 class="text-lg font-semibold text-gray-800 dark:text-white/90">
                                        <?= (int)($archives_count ?? 0) ?>
                                    </h4>
                                </div>
                            </div>
                        </div>

                        <!-- Card: Utilisateurs (Admin seulement) -->
                        <?php if (isset($if_is_admin) && $if_is_admin) : ?>
                            <div class="rounded-2xl border border-gray-200 bg-white px-6 pb-5 pt-6 dark:border-gray-800 dark:bg-white/[0.03]" style="padding-top: 2rem;">
                                <div class="mb-6 flex items-center gap-3">
                                    <div class="h-10 w-10">
                                        <svg enable-background="new 0 0 24 24"  width="36" height="36" id="Layer_1" version="1.0" viewBox="0 0 24 24" xml:space="preserve" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"><g>
                                            <path d="M9,9c0-1.7,1.3-3,3-3s3,1.3,3,3c0,1.7-1.3,3-3,3S9,10.7,9,9z M12,14c-4.6,0-6,3.3-6,3.3V19h12v-1.7C18,17.3,16.6,14,12,14z   "/></g><g><g><circle cx="18.5" cy="8.5" r="2.5"/></g><g>
                                            <path d="M18.5,13c-1.2,0-2.1,0.3-2.8,0.8c2.3,1.1,3.2,3,3.2,3.2l0,0.1H23v-1.3C23,15.7,21.9,13,18.5,13z"/></g></g><g><g><circle cx="18.5" cy="8.5" r="2.5"/></g><g>
                                            <path d="M18.5,13c-1.2,0-2.1,0.3-2.8,0.8c2.3,1.1,3.2,3,3.2,3.2l0,0.1H23v-1.3C23,15.7,21.9,13,18.5,13z"/></g></g><g><g><circle cx="5.5" cy="8.5" r="2.5"/></g><g>
                                            <path d="M5.5,13c1.2,0,2.1,0.3,2.8,0.8c-2.3,1.1-3.2,3-3.2,3.2l0,0.1H1v-1.3C1,15.7,2.1,13,5.5,13z"/></g></g>
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="text-base font-semibold text-gray-800 dark:text-white/90">
                                            UTILISATEURS
                                        </h3>
                                    </div>
                                </div>
                                <div class="flex items-end justify-between">
                                    <div>
                                        <h4 class="text-lg font-semibold text-gray-800 dark:text-white/90">
                                            <?= (int)($users_count ?? 0) ?>
                                        </h4>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>

                        <!-- Card: Dossiers Reçus -->
                        <div class="rounded-2xl border border-gray-200 bg-white px-6 pb-5 pt-6 dark:border-gray-800 dark:bg-white/[0.03]" style="padding-top: 2rem;">
                            <div class="mb-6 flex items-center gap-3">
                                <div class="h-10 w-10">
                                    <svg width="36" height="36" data-name="Livello 1" id="Livello_1" viewBox="0 0 128 128" xmlns="http://www.w3.org/2000/svg"><title/>
                                        <path d="M105,82a23,23,0,0,0-22.49,18.17L41.74,77.29a23,23,0,0,0,0-26.57L82.51,27.83a23,23,0,1,0-.44-6.63l-44.53,25a23,23,0,1,0,0,35.62l44.53,25A23,23,0,1,0,105,82Zm0-76A17,17,0,1,1,88,23,17,17,0,0,1,105,6ZM11,76A17,17,0,0,1,35,52h0A17,17,0,0,1,11,76Zm94,46a17,17,0,1,1,17-17A17,17,0,0,1,105,122Z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-base font-semibold text-gray-800 dark:text-white/90">
                                        DOSSIERS REÇUS
                                    </h3>
                                </div>
                            </div>
                            <div class="flex items-end justify-between">
                                <div>
                                    <h4 class="text-lg font-semibold text-gray-800 dark:text-white/90">
                                        <?= (int)($received_count ?? 0) ?>
                                    </h4>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Section Statistiques par Service (Admin seulement) -->
                    <?php if (isset($if_is_admin) && $if_is_admin && isset($stats_by_service) && !empty($stats_by_service)) : ?>
                        <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90 mb-6">
                                Statistiques par Service
                            </h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                <?php foreach ($stats_by_service as $service_stats) : ?>
                                    <div class="rounded-xl border border-gray-200 bg-gray-50 p-4 dark:border-gray-700 dark:bg-gray-800">
                                        <h4 class="font-semibold text-gray-800 dark:text-white/90 mb-3">
                                            <?= htmlspecialchars($service_stats->service_nom) ?>
                                        </h4>
                                        <div class="space-y-2">
                                            <div class="flex justify-between text-sm">
                                                <span class="text-gray-600 dark:text-gray-400">Dossiers Actifs:</span>
                                                <span class="font-medium text-gray-800 dark:text-white/90"><?= (int)$service_stats->dossiers_actifs ?></span>
                                            </div>
                                            <div class="flex justify-between text-sm">
                                                <span class="text-gray-600 dark:text-gray-400">Dossiers Archivés:</span>
                                                <span class="font-medium text-gray-800 dark:text-white/90"><?= (int)$service_stats->dossiers_archives ?></span>
                                            </div>
                                            <div class="flex justify-between text-sm">
                                                <span class="text-gray-600 dark:text-gray-400">Dossiers Reçus:</span>
                                                <span class="font-medium text-gray-800 dark:text-white/90"><?= (int)$service_stats->dossiers_recus ?></span>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php elseif (isset($if_is_admin) && $if_is_admin) : ?>
                        <div class="rounded-2xl border border-blue-200 bg-blue-50 p-4 dark:border-blue-500/30 dark:bg-blue-500/15">
                            <p class="text-sm text-blue-700 dark:text-blue-300">
                                Aucune statistique par service disponible.
                            </p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </main>
       
<?php $this->view('footer') ?>