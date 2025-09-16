<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <link rel="stylesheet" href="<?= ROOT ?>/assets/css/style.css" />
    <title> <?= APP_NAME ?> </title>
    <link rel="icon" href="<?= ROOT ?>/assets/images/favicon.ico">
    <link href="<?= ROOT ?>/assets/css/style.css" rel="stylesheet">
    <link href="<?= ROOT ?>/assets/css/styles.css" rel="stylesheet">
    
    <?php $ses = new \Core\Session(); ?>

    <style>
        .lg\:grid-cols-4 {
            @media (width >= 1024px) {
                grid-template-columns: repeat(4, minmax(0, 1fr));
            }
        }

        @media (width >= 1024px) {
            .lg\:grid-cols-4 {
                grid-template-columns: repeat(4, minmax(0, 1fr));
            }
        }

        .xl\:w-1\/2 {
            @media (width >= 1280px) {
                width: calc(1 / 2 * 100%);
            }
        }

        .lg\:grid-cols-3 {
            @media (width >= 1024px) {
                grid-template-columns: repeat(3, minmax(0, 1fr));
            }
        }
    </style>




</head>
<body x-data="{ open: false, page: 'ecommerce', 'loaded': true, 'darkMode': false, 'stickyMenu': false, 'sidebarToggle': false, 'scrollTop': false, 'dropdown': false, pageName: `Upload Files`, showCreateFolder: false, openDelete: false, 'isProfileInfoModal': false, 'DemandeConges': false }" x-init=" darkMode = JSON.parse(localStorage.getItem('darkMode')); $watch('darkMode', value => localStorage.setItem('darkMode', JSON.stringify(value))); window.addEventListener('opendelete', e => openDelete = e.detail.id);" :class="{'dark bg-gray-900': darkMode === true}" style="position: relative;">
    <!-- ===== Preloader Start ===== -->
    <div x-show="loaded" x-init="window.addEventListener('DOMContentLoaded', () => {setTimeout(() => loaded = false, 500)})" class="fixed left-0 top-0 z-999999 flex h-screen w-screen items-center justify-center bg-white dark:bg-black">
        <div class="h-16 w-16 animate-spin rounded-full border-4 border-solid border-brand-500 border-t-transparent"></div>
    </div>
    <!-- ===== Preloader End ===== -->

    <!-- ===== Page Wrapper Start ===== -->
    <div class="flex h-screen overflow-hidden">
        <!-- ===== Sidebar Start ===== -->
        <?php $this->view('sidebar'); ?>
        <!-- ===== Sidebar End ===== -->

        <!-- ===== Content Area Start ===== -->
        <div class="relative flex flex-col flex-1 overflow-x-hidden overflow-y-auto">
            <!-- Small Device Overlay Start -->
            <div @click="sidebarToggle = false" :class="sidebarToggle ? 'block lg:hidden' : 'hidden'" class="fixed w-full h-screen z-9 bg-gray-900/50"></div>
            <!-- Small Device Overlay End -->

            <!-- ===== Header Start ===== -->
            <?php $this->view('header'); ?>
            <!-- ===== Header End ===== -->
            
            