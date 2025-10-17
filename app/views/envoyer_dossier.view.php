<?php

use Core\Session;

    $this->view("head");
    $ses = new Session();
    $role_utilisateur = $ses->user('role_service') ?? null;
?>

<style>
    .send {
        color: black;
    }
    .send .form-container {
        max-width: 600px;
        margin: 0 auto;
        background: white;
        border-radius: 12px;
        padding: 30px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }
    
    .send .form-group {
        margin-bottom: 20px;
    }
    
    .send .radio-group {
        display: flex;
        gap: 20px;
        margin-bottom: 15px;
    }
    
    .send .radio-option {
        flex: 1;
        border: 2px solid #e5e7eb;
        border-radius: 8px;
        padding: 15px;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    
    .send .radio-option:hover {
        border-color: #3b82f6;
    }
    
    .send .radio-option.selected {
        border-color: #3b82f6;
        background-color: #eff6ff;
    }
    
    .send .hidden {
        display: none;
    }
</style>

<main class="send ">
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
        <div class="mb-6">
            <a href="<?= ROOT ?>/document?dossier_id=<?= $dossier->id ?>" class="inline-flex items-center gap-2 px-4 py-3 text-sm font-medium text-gray-700 transition rounded-lg bg-gray-100 hover:bg-gray-200">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16" class="mr-2">
                    <path fill-rule="evenodd" d="M12 8a.5.5 0 0 1-.5.5H5.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L5.707 7.5H11.5a.5.5 0 0 1 .5.5z"/>
                </svg>
                Retour au dossier
            </a>
        </div>

        <div class="send form-container">
            <h2 class="text-xl font-semibold mb-2">Envoyer le dossier</h2>
            <p class="text-gray-600 mb-6">Dossier : <strong><?= esc($dossier->nom) ?></strong></p>

            <form method="post" action="<?= ROOT ?>/document/envoyer/<?= $dossier->id ?>">
                <div class="form-group">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Type d'envoi</label>
                    <div class="radio-group">
                        <label class="radio-option" id="option-service">
                            <input type="radio" name="type_envoi" value="SERVICE" class="hidden" onchange="toggleDestinations()">
                            <div class="font-medium">Par Service</div>
                            <p class="text-sm text-gray-500">Tous les utilisateurs du service</p>
                        </label>
                        
                        <label class="radio-option" id="option-role">
                            <input type="radio" name="type_envoi" value="ROLE_SERVICE" class="hidden" onchange="toggleDestinations()">
                            <div class="font-medium">Par Fonction</div>
                            <p class="text-sm text-gray-500">Utilisateurs avec un rôle spécifique</p>
                        </label>
                    </div>
                </div>

                <div class="form-group hidden" id="service-dest-group">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Service destinataire</label>
                    <select name="service_dest" class="w-full border border-gray-300 rounded-lg px-4 py-2.5" <?= empty($services) ? 'disabled' : '' ?>>
                    <option desabled>Selectionner un service</option>    
                    <?php if (empty($services)) : ?>
                            <option>Aucun Service Disponible</option>
                        <?php else: ?>
                            <?php foreach ($services as $service): ?>
                                <option value="<?= $service->id ?>"><?= esc($service->nom) ?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>

                <div class="form-group hidden" id="role-dest-group">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Rôle destinataire</label>
                    <select name="role_dest" class="w-full border border-gray-300 rounded-lg px-4 py-2.5">
                        <option value="">Sélectionnez un rôle</option>
                        <?php 
                        $role_utilisateur = $ses->user('role_service') ?? null;
                        $service_id = $_POST['service_dest'] ?? null;
                        
                        foreach ($roles as $role): 
                            // Vérifier si le rôle doit être exclu
                            $deja_envoye = false;
                            
                            // Si l'utilisateur a un rôle_service, on exclut ce rôle de la liste
                            if ($role_utilisateur && $role === $role_utilisateur) {
                                $deja_envoye = true;
                            }
                            
                            // Vérification supplémentaire basée sur le service
                            if (!$deja_envoye && $service_id && isset($roles_exclus[$service_id])) {
                                $deja_envoye = in_array($role, $roles_exclus[$service_id]);
                            }
                        ?>
                            <?php if (!$deja_envoye): ?>
                                <option value="<?= $role ?>"><?= esc($role) ?></option>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="flex justify-end gap-3 mt-6">
                    <a href="<?= ROOT ?>/document?dossier_id=<?= $dossier->id ?>" class="px-4 py-2.5 text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200">
                        Annuler
                    </a>
                    <button type="submit" class="px-4 py-2.5 text-white bg-brand-500 rounded-lg hover:bg-brand-600">
                        Envoyer le dossier
                    </button>
                </div>
            </form>
        </div>
    </div>
</main>

<script>
    function toggleDestinations() {
        const serviceOption = document.querySelector('input[value="SERVICE"]');
        const roleOption = document.querySelector('input[value="ROLE_SERVICE"]');
        
        const serviceGroup = document.getElementById('service-dest-group');
        const roleGroup = document.getElementById('role-dest-group');
        
        const optionService = document.getElementById('option-service');
        const optionRole = document.getElementById('option-role');
        
        // Reset
        serviceGroup.classList.add('hidden');
        roleGroup.classList.add('hidden');
        optionService.classList.remove('selected');
        optionRole.classList.remove('selected');
        
        if (serviceOption.checked) {
            serviceGroup.classList.remove('hidden');
            optionService.classList.add('selected');
        } else if (roleOption.checked) {
            roleGroup.classList.remove('hidden');
            optionRole.classList.add('selected');
        }
    }

    // Initialisation
    document.addEventListener('DOMContentLoaded', function() {
        const radioOptions = document.querySelectorAll('.radio-option');
        radioOptions.forEach(option => {
            option.addEventListener('click', function() {
                const radio = this.querySelector('input[type="radio"]');
                radio.checked = true;
                toggleDestinations();
            });
        });
    });
</script>


<?php $this->view("footer"); ?>