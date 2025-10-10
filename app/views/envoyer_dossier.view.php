<?php $this->view("head"); ?>

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
                    <select name="service_dest" class="w-full border border-gray-300 rounded-lg px-4 py-2.5">
                        <option value="">Sélectionnez un service</option>
                        <?php foreach ($services as $service): ?>
                            <option value="<?= $service->id ?>"><?= esc($service->nom) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group hidden" id="role-dest-group">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Rôle destinataire</label>
                    <select name="role_dest" class="w-full border border-gray-300 rounded-lg px-4 py-2.5">
                        <option value="">Sélectionnez un rôle</option>
                        <?php foreach ($roles as $role): ?>
                            <option value="<?= $role ?>"><?= esc($role) ?></option>
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