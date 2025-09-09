<?php 
    $this->view('head'); 

    $ses = new \Core\Session();

    use Model\Notification;
    $notif = new Notification();
    $userId = $_SESSION['USER']->id ?? 0;
    $notifCount = $notif->getUnreadCount($userId);
    $notifications = $notif->getUserNotifications($userId); // Ajoute cette ligne
?>
    
    <main>
        <div class="p-4 mx-auto max-w-(--breakpoint-2xl) md:p-6">
        <!-- Breadcrumb Start -->
        <div x-data="{ pageName: `Notifications` }">
            <div class="flex flex-wrap items-center justify-between gap-3 mb-6">
                <h2 class="text-xl font-semibold text-gray-800 dark:text-white/90" x-text="pageName">Notifications</h2>
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
                        <li class="text-sm text-gray-800 dark:text-white/90" x-text="pageName">Notifications</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!-- Breadcrumb End -->
            <div class="space-y-8 mt-7 dark:border-gray-800 sm:mt-0 xl:p-6">
                <div class="flex flex-col gap-2 swim-lane">
                    <!-- task item -->
                    <?php if (!empty($notifications)) : ?>
                        <?php
                            $unread = [];
                            $read = [];
                            foreach ($notifications as $notif) {
                                if (empty($notif->is_read) || $notif->is_read == 0) {
                                    $unread[] = $notif;
                                } else {
                                    $read[] = $notif;
                                }
                            }

                            foreach (array_merge($unread, $read) as $notif):
                        ?>
                        <div data-id="<?= $notif->id ?>" class="notif-item p-5 bg-white rounded-xl shadow-theme-sm dark:border-gray-800 dark:bg-white/5<?= (empty($notif->is_read) || $notif->is_read == 0) ? ' font-bold bg-blue-light-50' : '' ?>">
                            <div class="flex flex-col gap-5 xl:flex-row xl:items-center xl:justify-between">
                                <div class="flex items-start w-full gap-4">
                                    <div class="relative flex items-start">
                                        <p class="-mt-0.5 text-base text-gray-800 dark:text-white/90">
                                            <?= esc($notif->message) ?>
                                            par <span class="font-medium text-gray-800 dark:text-white/90"><?= esc($notif->actor_name . ' ' . $notif->prenom) ?></span>
                                        </p>
                                    </div>
                                </div>
                                <div class="flex flex-col-reverse items-start justify-end w-full gap-3 xl:flex-row xl:items-center xl:gap-5">
                                    <div class="">
                                        <div class="flex items-center gap-3">
                                            <span class="flex items-center gap-1 text-sm text-gray-500 cursor-pointer dark:text-gray-400">
                                                <svg class="fill-current" width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M5.33329 1.0835C5.74751 1.0835 6.08329 1.41928 6.08329 1.8335V2.25016L9.91663 2.25016V1.8335C9.91663 1.41928 10.2524 1.0835 10.6666 1.0835C11.0808 1.0835 11.4166 1.41928 11.4166 1.8335V2.25016L12.3333 2.25016C13.2998 2.25016 14.0833 3.03366 14.0833 4.00016V6.00016L14.0833 12.6668C14.0833 13.6333 13.2998 14.4168 12.3333 14.4168L3.66663 14.4168C2.70013 14.4168 1.91663 13.6333 1.91663 12.6668L1.91663 6.00016L1.91663 4.00016C1.91663 3.03366 2.70013 2.25016 3.66663 2.25016L4.58329 2.25016V1.8335C4.58329 1.41928 4.91908 1.0835 5.33329 1.0835ZM5.33329 3.75016L3.66663 3.75016C3.52855 3.75016 3.41663 3.86209 3.41663 4.00016V5.25016L12.5833 5.25016V4.00016C12.5833 3.86209 12.4714 3.75016 12.3333 3.75016L10.6666 3.75016L5.33329 3.75016ZM12.5833 6.75016L3.41663 6.75016L3.41663 12.6668C3.41663 12.8049 3.52855 12.9168 3.66663 12.9168L12.3333 12.9168C12.4714 12.9168 12.5833 12.8049 12.5833 12.6668L12.5833 6.75016Z" fill=""></path>
                                                </svg>
                                                <span><?= $notif->created_at ?></span>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; endif; ?>
                </div>
            </div>
        </div>
        </main>
                        
<?php $this->view('footer'); ?>