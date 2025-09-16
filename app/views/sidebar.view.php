<?php $ses = new \Core\Session(); ?>
<aside :class="sidebarToggle ? 'translate-x-0 lg:w-[90px]' : '-translate-x-full'" class="sidebar fixed left-0 top-0 z-9999 flex h-screen w-[290px] flex-col overflow-y-hidden border-r border-gray-200 bg-white px-5 dark:border-gray-800 dark:bg-black lg:static lg:translate-x-0">
            
    <!-- SIDEBAR HEADER -->
    <div
        :class="sidebarToggle ? 'justify-center' : 'justify-between'"
        class="flex items-center gap-2 pt-8 sidebar-header pb-7">
        <a href="<?= ROOT ?>" class="flex items-center gap-2">
            <span class="logo" :class="sidebarToggle ? 'hidden' : ''">
                <img class="dark:hidden" src="<?= ROOT ?>/assets/images/logo.png" width="120px" alt="Logo" />
                <img
                    class="hidden dark:block"
                    src="<?= ROOT ?>/assets/images/logo.png"
                    alt="Logo" 
                    width="120px"/>
            </span>

            <img
                class="logo-icon"
                :class="sidebarToggle ? 'lg:block' : 'hidden'"
                src="<?= ROOT ?>/assets/images/logo.png"
                alt="Logo" />
        </a>
    </div>
    <!-- SIDEBAR HEADER -->

    <div
        class="flex flex-col overflow-y-auto duration-300 ease-linear no-scrollbar">
        <!-- Sidebar Menu -->
        <nav x-data="{selected: $persist('Dashboard')}">
            <!-- Menu Group -->
            <div>
                <h3 class="mb-4 text-xs uppercase leading-[20px] text-gray-400">
                    <span
                        class="menu-group-title"
                        :class="sidebarToggle ? 'lg:hidden' : ''">
                        MENU
                    </span>

                    <svg
                        :class="sidebarToggle ? 'lg:block hidden' : 'hidden'"
                        class="mx-auto fill-current menu-group-icon"
                        width="24"
                        height="24"
                        viewBox="0 0 24 24"
                        fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path
                            fill-rule="evenodd"
                            clip-rule="evenodd"
                            d="M5.99915 10.2451C6.96564 10.2451 7.74915 11.0286 7.74915 11.9951V12.0051C7.74915 12.9716 6.96564 13.7551 5.99915 13.7551C5.03265 13.7551 4.24915 12.9716 4.24915 12.0051V11.9951C4.24915 11.0286 5.03265 10.2451 5.99915 10.2451ZM17.9991 10.2451C18.9656 10.2451 19.7491 11.0286 19.7491 11.9951V12.0051C19.7491 12.9716 18.9656 13.7551 17.9991 13.7551C17.0326 13.7551 16.2491 12.9716 16.2491 12.0051V11.9951C16.2491 11.0286 17.0326 10.2451 17.9991 10.2451ZM13.7491 11.9951C13.7491 11.0286 12.9656 10.2451 11.9991 10.2451C11.0326 10.2451 10.2491 11.0286 10.2491 11.9951V12.0051C10.2491 12.9716 11.0326 13.7551 11.9991 13.7551C12.9656 13.7551 13.7491 12.9716 13.7491 12.0051V11.9951Z"
                            fill="" />
                    </svg>
                </h3>

                <ul class="flex flex-col gap-4 mb-6">
                    <!-- Home -->
                    <li>
                        <a href="<?= ROOT ?>" @click="selected = (selected === 'Calendar' ? '':'Calendar')" class="menu-item group" :class=" (selected === 'Calendar') && (page === 'calendar') ? 'menu-item-active' : 'menu-item-inactive'">
                            <svg
                                :class="(selected === 'Dashboard') || (page === 'ecommerce' || page === 'analytics' || page === 'marketing' || page === 'crm' || page === 'stocks') ? 'menu-item-icon-active'  :'menu-item-icon-inactive'"
                                width="24"
                                height="24"
                                viewBox="0 0 24 24"
                                fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    fill-rule="evenodd"
                                    clip-rule="evenodd"
                                    d="M5.5 3.25C4.25736 3.25 3.25 4.25736 3.25 5.5V8.99998C3.25 10.2426 4.25736 11.25 5.5 11.25H9C10.2426 11.25 11.25 10.2426 11.25 8.99998V5.5C11.25 4.25736 10.2426 3.25 9 3.25H5.5ZM4.75 5.5C4.75 5.08579 5.08579 4.75 5.5 4.75H9C9.41421 4.75 9.75 5.08579 9.75 5.5V8.99998C9.75 9.41419 9.41421 9.74998 9 9.74998H5.5C5.08579 9.74998 4.75 9.41419 4.75 8.99998V5.5ZM5.5 12.75C4.25736 12.75 3.25 13.7574 3.25 15V18.5C3.25 19.7426 4.25736 20.75 5.5 20.75H9C10.2426 20.75 11.25 19.7427 11.25 18.5V15C11.25 13.7574 10.2426 12.75 9 12.75H5.5ZM4.75 15C4.75 14.5858 5.08579 14.25 5.5 14.25H9C9.41421 14.25 9.75 14.5858 9.75 15V18.5C9.75 18.9142 9.41421 19.25 9 19.25H5.5C5.08579 19.25 4.75 18.9142 4.75 18.5V15ZM12.75 5.5C12.75 4.25736 13.7574 3.25 15 3.25H18.5C19.7426 3.25 20.75 4.25736 20.75 5.5V8.99998C20.75 10.2426 19.7426 11.25 18.5 11.25H15C13.7574 11.25 12.75 10.2426 12.75 8.99998V5.5ZM15 4.75C14.5858 4.75 14.25 5.08579 14.25 5.5V8.99998C14.25 9.41419 14.5858 9.74998 15 9.74998H18.5C18.9142 9.74998 19.25 9.41419 19.25 8.99998V5.5C19.25 5.08579 18.9142 4.75 18.5 4.75H15ZM15 12.75C13.7574 12.75 12.75 13.7574 12.75 15V18.5C12.75 19.7426 13.7574 20.75 15 20.75H18.5C19.7426 20.75 20.75 19.7427 20.75 18.5V15C20.75 13.7574 19.7426 12.75 18.5 12.75H15ZM14.25 15C14.25 14.5858 14.5858 14.25 15 14.25H18.5C18.9142 14.25 19.25 14.5858 19.25 15V18.5C19.25 18.9142 18.9142 19.25 18.5 19.25H15C14.5858 19.25 14.25 18.9142 14.25 18.5V15Z"
                                    fill="" />
                            </svg>

                            <span class="menu-item-text" :class="sidebarToggle ? 'lg:hidden' : ''">
                                Dashboard
                            </span>
                        </a>
                    </li>

                    <?php if ($ses->is_logged_in() && $ses->user('role') == 'ADMIN') : ?>
                        <li>
                            <a href="<?= ROOT ?>/users" @click="selected = (selected === 'Calendar' ? '':'Calendar')" class="menu-item group" :class=" (selected === 'Calendar') && (page === 'calendar') ? 'menu-item-active' : 'menu-item-inactive'">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" />
                                </svg>
                                <span class="menu-item-text" :class="sidebarToggle ? 'lg:hidden' : ''">
                                    Utilisateurs
                                </span>
                            </a>
                        </li>
                    <?php endif; ?>

                    <li>
                        <a href="<?= ROOT ?>/document" @click="selected = (selected === 'Calendar' ? '':'Calendar')" class="menu-item group" :class=" (selected === 'Calendar') && (page === 'calendar') ? 'menu-item-active' : 'menu-item-inactive'">
                            <svg viewBox="0 0 64 64" width="24" height="24" xmlns="http://www.w3.org/2000/svg"><g id="Outline"><g data-name="Outline" id="Outline-2">
                                <path d="M17.73,42.63a4.18,4.18,0,0,0,2.43,2.16,4.36,4.36,0,0,1,1.33.85,4.38,4.38,0,0,1,.4,1.54,4.17,4.17,0,0,0,1.29,3,4.12,4.12,0,0,0,3.19.32A4.36,4.36,0,0,1,28,50.37a4.11,4.11,0,0,1,1.19,1A4.15,4.15,0,0,0,32,53a4.15,4.15,0,0,0,2.83-1.66,4.11,4.11,0,0,1,1.19-1,4.36,4.36,0,0,1,1.61.08,4.16,4.16,0,0,0,3.19-.32,4.17,4.17,0,0,0,1.29-3,4.38,4.38,0,0,1,.4-1.54,4.18,4.18,0,0,1,1.33-.85,4.2,4.2,0,0,0,2.43-2.15,4.14,4.14,0,0,0-.68-3.1A4.36,4.36,0,0,1,45,38a4.36,4.36,0,0,1,.59-1.54,4.13,4.13,0,0,0,.68-3.09,4.18,4.18,0,0,0-2.43-2.16,4.49,4.49,0,0,1-1.33-.85,4.38,4.38,0,0,1-.4-1.54,4.17,4.17,0,0,0-1.29-2.95,4.16,4.16,0,0,0-3.19-.32,4.62,4.62,0,0,1-1.61.09,4.55,4.55,0,0,1-1.19-1A4.15,4.15,0,0,0,32,23a4.15,4.15,0,0,0-2.83,1.66,4.55,4.55,0,0,1-1.19,1,4.63,4.63,0,0,1-1.61-.09,4.16,4.16,0,0,0-3.19.32,4.17,4.17,0,0,0-1.29,2.95,4.38,4.38,0,0,1-.4,1.54,4.49,4.49,0,0,1-1.33.85,4.2,4.2,0,0,0-2.43,2.15,4.14,4.14,0,0,0,.68,3.1A4.36,4.36,0,0,1,19,38a4.36,4.36,0,0,1-.59,1.54A4.13,4.13,0,0,0,17.73,42.63Zm2.46-7.08c-.28-.54-.65-1.27-.55-1.57s.83-.69,1.42-1a5.28,5.28,0,0,0,2-1.45,5.43,5.43,0,0,0,.77-2.42c.1-.64.22-1.43.49-1.63s1.06-.07,1.69,0a5.5,5.5,0,0,0,2.55,0,5.3,5.3,0,0,0,2-1.48c.47-.47,1-1.06,1.41-1.06s.94.59,1.41,1.06a5.3,5.3,0,0,0,2,1.48,5.5,5.5,0,0,0,2.55,0c.63-.1,1.42-.23,1.69,0s.39,1,.49,1.63a5.43,5.43,0,0,0,.77,2.42,5.41,5.41,0,0,0,2,1.46c.59.29,1.32.65,1.42,1s-.27,1-.55,1.57A5.47,5.47,0,0,0,43,38a5.43,5.43,0,0,0,.81,2.45c.28.54.65,1.27.55,1.57s-.83.69-1.42,1a5.41,5.41,0,0,0-2,1.46,5.43,5.43,0,0,0-.77,2.42c-.1.64-.22,1.43-.49,1.63s-1.06.07-1.69,0a5.5,5.5,0,0,0-2.55,0,5.3,5.3,0,0,0-2,1.48c-.47.47-1,1.06-1.41,1.06s-.94-.59-1.41-1.06a5.3,5.3,0,0,0-2-1.48,3.25,3.25,0,0,0-1-.15,9.82,9.82,0,0,0-1.52.17c-.63.1-1.42.23-1.69,0s-.39-1-.49-1.63a5.43,5.43,0,0,0-.77-2.42,5.41,5.41,0,0,0-2-1.46c-.59-.29-1.32-.65-1.42-1s.27-1,.55-1.57A5.43,5.43,0,0,0,21,38,5.47,5.47,0,0,0,20.19,35.55Z"/><path d="M32,47a9,9,0,1,0-9-9A9,9,0,0,0,32,47Zm0-16a7,7,0,1,1-7,7A7,7,0,0,1,32,31Z"/><path d="M54,6H51V5.5a3.5,3.5,0,0,0-7,0V6H35.5V5.5a3.5,3.5,0,0,0-7,0V6H20V5.5a3.5,3.5,0,0,0-7,0V6H10a5,5,0,0,0-5,5V42a1,1,0,0,0,2,0V20H57V44a1,1,0,0,1-1,1H51a5,5,0,0,0-5,5v5a1,1,0,0,1-1,1l-35-.06a3,3,0,0,1-3-3V50a1,1,0,0,0-2,0v7a5,5,0,0,0,5,5H54a5,5,0,0,0,5-5V11A5,5,0,0,0,54,6Zm-8-.5a1.5,1.5,0,0,1,3,0v4a1.5,1.5,0,0,1-3,0Zm-15.5,0a1.5,1.5,0,0,1,3,0v4a1.5,1.5,0,0,1-3,0ZM15,5.5a1.5,1.5,0,0,1,3,0v4a1.5,1.5,0,0,1-3,0ZM7,18V11a3,3,0,0,1,3-3h3V9.5a3.5,3.5,0,0,0,7,0V8h8.5V9.5a3.5,3.5,0,0,0,7,0V8H44V9.5a3.5,3.5,0,0,0,7,0V8h3a3,3,0,0,1,3,3v7ZM48,50a3,3,0,0,1,3-3h4.59L48,54.59Zm6,10H10a3,3,0,0,1-3-3.09,4.9,4.9,0,0,0,3,1L45.33,58a5,5,0,0,0,3.55-1.47L57,48.42V57A3,3,0,0,1,54,60Z"/><circle cx="6" cy="46" r="1"/></g></g>
                            </svg>
                            <span class="menu-item-text" :class="sidebarToggle ? 'lg:hidden' : ''">
                            Documents
                            </span>
                        </a>
                    </li>

                    <!-- <li>
                        <a href="<?= ROOT ?>/conges" @click="selected = (selected === 'Calendar' ? '':'Calendar')" class="menu-item group" :class=" (selected === 'Calendar') && (page === 'calendar') ? 'menu-item-active' : 'menu-item-inactive'">
                            <svg viewBox="0 0 64 64" width="24" height="24" xmlns="http://www.w3.org/2000/svg"><g id="Outline"><g data-name="Outline" id="Outline-2">
                                <path d="M17.73,42.63a4.18,4.18,0,0,0,2.43,2.16,4.36,4.36,0,0,1,1.33.85,4.38,4.38,0,0,1,.4,1.54,4.17,4.17,0,0,0,1.29,3,4.12,4.12,0,0,0,3.19.32A4.36,4.36,0,0,1,28,50.37a4.11,4.11,0,0,1,1.19,1A4.15,4.15,0,0,0,32,53a4.15,4.15,0,0,0,2.83-1.66,4.11,4.11,0,0,1,1.19-1,4.36,4.36,0,0,1,1.61.08,4.16,4.16,0,0,0,3.19-.32,4.17,4.17,0,0,0,1.29-3,4.38,4.38,0,0,1,.4-1.54,4.18,4.18,0,0,1,1.33-.85,4.2,4.2,0,0,0,2.43-2.15,4.14,4.14,0,0,0-.68-3.1A4.36,4.36,0,0,1,45,38a4.36,4.36,0,0,1,.59-1.54,4.13,4.13,0,0,0,.68-3.09,4.18,4.18,0,0,0-2.43-2.16,4.49,4.49,0,0,1-1.33-.85,4.38,4.38,0,0,1-.4-1.54,4.17,4.17,0,0,0-1.29-2.95,4.16,4.16,0,0,0-3.19-.32,4.62,4.62,0,0,1-1.61.09,4.55,4.55,0,0,1-1.19-1A4.15,4.15,0,0,0,32,23a4.15,4.15,0,0,0-2.83,1.66,4.55,4.55,0,0,1-1.19,1,4.63,4.63,0,0,1-1.61-.09,4.16,4.16,0,0,0-3.19.32,4.17,4.17,0,0,0-1.29,2.95,4.38,4.38,0,0,1-.4,1.54,4.49,4.49,0,0,1-1.33.85,4.2,4.2,0,0,0-2.43,2.15,4.14,4.14,0,0,0,.68,3.1A4.36,4.36,0,0,1,19,38a4.36,4.36,0,0,1-.59,1.54A4.13,4.13,0,0,0,17.73,42.63Zm2.46-7.08c-.28-.54-.65-1.27-.55-1.57s.83-.69,1.42-1a5.28,5.28,0,0,0,2-1.45,5.43,5.43,0,0,0,.77-2.42c.1-.64.22-1.43.49-1.63s1.06-.07,1.69,0a5.5,5.5,0,0,0,2.55,0,5.3,5.3,0,0,0,2-1.48c.47-.47,1-1.06,1.41-1.06s.94.59,1.41,1.06a5.3,5.3,0,0,0,2,1.48,5.5,5.5,0,0,0,2.55,0c.63-.1,1.42-.23,1.69,0s.39,1,.49,1.63a5.43,5.43,0,0,0,.77,2.42,5.41,5.41,0,0,0,2,1.46c.59.29,1.32.65,1.42,1s-.27,1-.55,1.57A5.47,5.47,0,0,0,43,38a5.43,5.43,0,0,0,.81,2.45c.28.54.65,1.27.55,1.57s-.83.69-1.42,1a5.41,5.41,0,0,0-2,1.46,5.43,5.43,0,0,0-.77,2.42c-.1.64-.22,1.43-.49,1.63s-1.06.07-1.69,0a5.5,5.5,0,0,0-2.55,0,5.3,5.3,0,0,0-2,1.48c-.47.47-1,1.06-1.41,1.06s-.94-.59-1.41-1.06a5.3,5.3,0,0,0-2-1.48,3.25,3.25,0,0,0-1-.15,9.82,9.82,0,0,0-1.52.17c-.63.1-1.42.23-1.69,0s-.39-1-.49-1.63a5.43,5.43,0,0,0-.77-2.42,5.41,5.41,0,0,0-2-1.46c-.59-.29-1.32-.65-1.42-1s.27-1,.55-1.57A5.43,5.43,0,0,0,21,38,5.47,5.47,0,0,0,20.19,35.55Z"/><path d="M32,47a9,9,0,1,0-9-9A9,9,0,0,0,32,47Zm0-16a7,7,0,1,1-7,7A7,7,0,0,1,32,31Z"/><path d="M54,6H51V5.5a3.5,3.5,0,0,0-7,0V6H35.5V5.5a3.5,3.5,0,0,0-7,0V6H20V5.5a3.5,3.5,0,0,0-7,0V6H10a5,5,0,0,0-5,5V42a1,1,0,0,0,2,0V20H57V44a1,1,0,0,1-1,1H51a5,5,0,0,0-5,5v5a1,1,0,0,1-1,1l-35-.06a3,3,0,0,1-3-3V50a1,1,0,0,0-2,0v7a5,5,0,0,0,5,5H54a5,5,0,0,0,5-5V11A5,5,0,0,0,54,6Zm-8-.5a1.5,1.5,0,0,1,3,0v4a1.5,1.5,0,0,1-3,0Zm-15.5,0a1.5,1.5,0,0,1,3,0v4a1.5,1.5,0,0,1-3,0ZM15,5.5a1.5,1.5,0,0,1,3,0v4a1.5,1.5,0,0,1-3,0ZM7,18V11a3,3,0,0,1,3-3h3V9.5a3.5,3.5,0,0,0,7,0V8h8.5V9.5a3.5,3.5,0,0,0,7,0V8H44V9.5a3.5,3.5,0,0,0,7,0V8h3a3,3,0,0,1,3,3v7ZM48,50a3,3,0,0,1,3-3h4.59L48,54.59Zm6,10H10a3,3,0,0,1-3-3.09,4.9,4.9,0,0,0,3,1L45.33,58a5,5,0,0,0,3.55-1.47L57,48.42V57A3,3,0,0,1,54,60Z"/><circle cx="6" cy="46" r="1"/></g></g>
                            </svg>
                            <span class="menu-item-text" :class="sidebarToggle ? 'lg:hidden' : ''">
                                Congéations
                            </span>
                        </a>
                    </li> -->

                    <!-- Menu Drowdown Start -->
                    <li>
                        <a href="#" @click.prevent="selected = (selected === 'Dashboard' ? '':'Dashboard')" class="menu-item group" :class=" (selected === 'Dashboard') || (page === 'ecommerce' || page === 'analytics' || page === 'marketing' || page === 'crm' || page === 'stocks') ? 'menu-item-active' : 'menu-item-inactive'">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" width="24px" heigth="24px" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m18.375 12.739-7.693 7.693a4.5 4.5 0 0 1-6.364-6.364l10.94-10.94A3 3 0 1 1 19.5 7.372L8.552 18.32m.009-.01-.01.01m5.699-9.941-7.81 7.81a1.5 1.5 0 0 0 2.112 2.13" />
                            </svg>


                            <span class="menu-item-text" :class="sidebarToggle ? 'lg:hidden' : ''">
                                Documents
                            </span>

                            <svg class="menu-item-arrow" :class="[(selected === 'Dashboard') ? 'menu-item-arrow-active' : 'menu-item-arrow-inactive', sidebarToggle ? 'lg:hidden' : '' ]" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M4.79175 7.39584L10.0001 12.6042L15.2084 7.39585" stroke="" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </a>
                        <div class="overflow-hidden transform translate" :class="(selected === 'Dashboard') ? 'block' :'hidden'">
                            <ul :class="sidebarToggle ? 'lg:hidden' : 'flex'" class="flex flex-col gap-1 mt-2 menu-dropdown pl-9">
                                <li>
                                    <a href="<?= ROOT ?>/folder" class="menu-dropdown-item group" :class="page === 'ecommerce' ? 'menu-dropdown-item-active' : 'menu-dropdown-item-inactive'">
                                        Dossiers
                                    </a>
                                </li>
                            </ul>
                            <ul :class="sidebarToggle ? 'lg:hidden' : 'flex'" class="flex flex-col gap-1 mt-2 menu-dropdown pl-9">
                                <li>
                                    <a href="<?= ROOT ?>/upload" class="menu-dropdown-item group" :class="page === 'ecommerce' ? 'menu-dropdown-item-active' : 'menu-dropdown-item-inactive'">
                                        Fichiers
                                    </a>
                                </li>
                            </ul>
                            <ul :class="sidebarToggle ? 'lg:hidden' : 'flex'" class="flex flex-col gap-1 mt-2 menu-dropdown pl-9">
                                <li>
                                    <a href="<?= ROOT ?>/documentassignment" class="menu-dropdown-item group" :class="page === 'ecommerce' ? 'menu-dropdown-item-active' : 'menu-dropdown-item-inactive'">
                                        Fichiers Assigné
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>

                    <li>
                        <a href="<?= ROOT ?>/historique" @click="selected = (selected === 'Calendar' ? '':'Calendar')" class="menu-item group" :class=" (selected === 'Calendar') && (page === 'calendar') ? 'menu-item-active' : 'menu-item-inactive'">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-history-icon lucide-history"><path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/>
                                <path d="M3 3v5h5"/><path d="M12 7v5l4 2"/>
                            </svg>
                            <span class="menu-item-text" :class="sidebarToggle ? 'lg:hidden' : ''">
                                Historique
                            </span>
                        </a>
                    </li>
                </ul>
            </div>
        </nav>
    </div>
</aside>