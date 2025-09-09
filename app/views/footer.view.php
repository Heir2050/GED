        </div>
        <!-- ===== Content Area End ===== -->
    </div>
    
    <script defer src="<?= ROOT ?>/assets/js/bundle.js"></script>
    
    <script src="<?= ROOT ?>/assets/js/main.js"></script>

    <!-- Choix du type de lettre -->
    <script>
document.addEventListener('DOMContentLoaded', function() {
    const typeSelect = document.querySelector('select[name="type"]');
    const recuFields = document.getElementById('recu-fields');
    const envoyeFields = document.getElementById('envoye-fields');
    
    // Fonction pour gérer l'affichage des champs
    function toggleFields() {
        // Masquer tous les champs d'abord
        recuFields.classList.add('hiddens');
        envoyeFields.classList.add('hiddens');

        // Afficher les champs correspondants
        if (typeSelect.value === 'recu') {
            recuFields.classList.remove('hiddens');
        } else if (typeSelect.value === 'envoye') {
            envoyeFields.classList.remove('hiddens');
        }
        
        // Pour "interne", rien n'est affiché
    }
    
    // Écouter les changements sur le select
    typeSelect.addEventListener('change', toggleFields);
    
    // Appeler la fonction au chargement si une valeur est déjà sélectionnée
    if (typeSelect.value === 'recu' || typeSelect.value === 'envoye') {
        toggleFields();
    }
    
    // Validation du formulaire
    const form = document.getElementById('submissionForm');
    form.addEventListener('submit', function(e) {
        let isValid = true;
        
        // Validation de base (vous pouvez ajouter plus de règles)
        if (!typeSelect.value) {
            alert('Veuillez sélectionner un type de document');
            isValid = false;
        }
        
        // Validation conditionnelle pour "reçu"
        if (typeSelect.value === 'recu') {
            const requiredRecuFields = ['numero_interne', 'numero_reception', 'date_recu', 'envoyeur'];
            for (const fieldName of requiredRecuFields) {
                const field = document.querySelector(`[name="${fieldName}"]`);
                if (!field.value.trim()) {
                    alert(`Le champ ${fieldName} est requis pour les documents reçus`);
                    isValid = false;
                    break;
                }
            }
        }
        
        // Validation conditionnelle pour "envoyé"
        if (typeSelect.value === 'envoye') {
            const requiredEnvoyeFields = ['numero_expedie', 'numero_expedition', 'date_expedition', 'institution_destinataire'];
            for (const fieldName of requiredEnvoyeFields) {
                const field = document.querySelector(`[name="${fieldName}"]`);
                if (!field.value.trim()) {
                    alert(`Le champ ${fieldName} est requis pour les documents envoyés`);
                    isValid = false;
                    break;
                }
            }
        }
        
        if (!isValid) {
            e.preventDefault();
        }
    });
});
</script>

<!-- upload new documents -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const fileInput = document.getElementById('fileInput');
        const fileUploadArea = document.getElementById('fileUploadArea');
        const filePreview = document.getElementById('filePreview');
        const filesCount = document.getElementById('filesCount');
        const uploadStatus = document.getElementById('uploadStatus');
        const submitButton = document.getElementById('submitButton');
        const fileLabelText = document.getElementById('fileLabelText');
        
        let selectedFiles = [];
        
        // Événements pour le glisser-déposer
        fileUploadArea.addEventListener('dragover', function(e) {
            e.preventDefault();
            fileUploadArea.classList.add('drag-over');
        });
        
        fileUploadArea.addEventListener('dragleave', function() {
            fileUploadArea.classList.remove('drag-over');
        });
        
        fileUploadArea.addEventListener('drop', function(e) {
            e.preventDefault();
            fileUploadArea.classList.remove('drag-over');
            
            if (e.dataTransfer.files.length > 0) {
                handleFiles(e.dataTransfer.files);
            }
        });
        
        // Événement pour la sélection de fichiers via le bouton
        fileInput.addEventListener('change', function() {
            if (this.files.length > 0) {
                handleFiles(this.files);
            }
        });
        
        // Fonction pour gérer les fichiers sélectionnés
        function handleFiles(files) {
            for (let i = 0; i < files.length; i++) {
                const file = files[i];
                
                // Vérification de la taille du fichier (max 10MB)
                if (file.size > 10 * 1024 * 1024) {
                    showStatus(`Le fichier "${file.name}" dépasse la taille maximale de 10MB`, 'error');
                    continue;
                }
                
                // Vérification du type de fichier
                const fileExtension = file.name.split('.').pop().toLowerCase();
                const allowedExtensions = ['jpg', 'jpeg', 'png', 'pdf', 'doc', 'docx', 'xls', 'xlsx', 'zip', 'rar'];
                
                if (!allowedExtensions.includes(fileExtension)) {
                    showStatus(`Le format de fichier "${file.name}" n'est pas autorisé`, 'error');
                    continue;
                }
                
                // Ajouter le fichier à la liste
                if (!selectedFiles.some(f => f.name === file.name && f.size === file.size)) {
                    selectedFiles.push(file);
                }
            }
            
            updateFilePreview();
            updateFilesCount();
            updateSubmitButton();
        }
        
        // Mettre à jour l'affichage des fichiers
        function updateFilePreview() {
            filePreview.innerHTML = '';
            
            if (selectedFiles.length > 0) {
                fileUploadArea.classList.add('has-files');
                
                selectedFiles.forEach((file, index) => {
                    const fileExtension = file.name.split('.').pop().toLowerCase();
                    const previewItem = document.createElement('div');
                    previewItem.className = 'file-preview-item';
                    
                    // Icône selon le type de fichier
                    let iconPath = '';
                    switch(fileExtension) {
                        case 'pdf':
                            iconPath = 'M5,20H19V8H15V4H5M19,2H15L7,10V22H19V2Z';
                            break;
                        case 'doc':
                        case 'docx':
                            iconPath = 'M5,20H19V8H15V4H5M19,2H15L7,10V22H19V2Z';
                            break;
                        case 'xls':
                        case 'xlsx':
                            iconPath = 'M5,20H19V8H15V4H5M19,2H15L7,10V22H19V2Z';
                            break;
                        case 'zip':
                        case 'rar':
                            iconPath = 'M13,9H18.5L13,3.5V9M6,2H14L20,8V20A2,2 0 0,1 18,22H6C4.89,22 4,21.1 4,20V4C4,2.89 4.89,2 6,2M15,18V16H6V18H15M18,14V12H6V14H18Z';
                            break;
                        case 'jpg':
                        case 'jpeg':
                        case 'png':
                            iconPath = 'M13,9H18.5L13,3.5V9M6,2H14L20,8V20A2,2 0 0,1 18,22H6C4.89,22 4,21.1 4,20V4C4,2.89 4.89,2 6,2M6,20H15L18,20V12L14,16L12,14L6,20M8,9A2,2 0 0,0 6,11A2,2 0 0,0 8,13A2,2 0 0,0 10,11A2,2 0 0,0 8,9Z';
                            break;
                        default:
                            iconPath = 'M14,2H6A2,2 0 0,0 4,4V20A2,2 0 0,0 6,22H18A2,2 0 0,0 20,20V8L14,2M18,20H6V4H13V9H18V20Z';
                    }
                    
                    previewItem.innerHTML = `
                        <div class="file-preview-icon">
                            <svg viewBox="0 0 24 24"><path d="${iconPath}"/></svg>
                        </div>
                        <span class="file-preview-name">${file.name}</span>
                        <span class="file-preview-size">${formatFileSize(file.size)}</span>
                        <div class="remove-file" data-index="${index}">×</div>
                    `;
                    
                    filePreview.appendChild(previewItem);
                });
                
                // Ajouter des écouteurs d'événements pour les boutons de suppression
                document.querySelectorAll('.remove-file').forEach(button => {
                    button.addEventListener('click', function() {
                        const index = parseInt(this.getAttribute('data-index'));
                        selectedFiles.splice(index, 1);
                        updateFilePreview();
                        updateFilesCount();
                        updateSubmitButton();
                    });
                });
            } else {
                fileUploadArea.classList.remove('has-files');
            }
        }
        
        // Mettre à jour le compteur de fichiers
        function updateFilesCount() {
            if (selectedFiles.length === 0) {
                filesCount.textContent = 'Aucun fichier sélectionné';
            } else if (selectedFiles.length === 1) {
                filesCount.textContent = '1 fichier sélectionné';
            } else {
                filesCount.textContent = `${selectedFiles.length} fichiers sélectionnés`;
            }
        }
        
        // Activer/désactiver le bouton de soumission
        function updateSubmitButton() {
            submitButton.disabled = selectedFiles.length === 0;
        }
        
        // Afficher un message de statut
        function showStatus(message, type) {
            uploadStatus.textContent = message;
            uploadStatus.className = 'upload-status ' + type;
            
            setTimeout(() => {
                uploadStatus.textContent = '';
                uploadStatus.className = 'upload-status';
            }, 5000);
        }
        
        // Formater la taille du fichier
        function formatFileSize(bytes) {
            if (bytes === 0) return '0 Bytes';
            const k = 1024;
            const sizes = ['Bytes', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
        }
        
        // Simulation de l'envoi des fichiers
        submitButton.addEventListener('click', function() {
            showStatus('Téléversement en cours...', '');
            
            // Simuler un délai de téléversement
            setTimeout(() => {
                showStatus(`Succès! ${selectedFiles.length} fichier(s) téléversé(s)`, 'success');
                
                // Réinitialiser après un succès
                setTimeout(() => {
                    selectedFiles = [];
                    fileInput.value = '';
                    updateFilePreview();
                    updateFilesCount();
                    updateSubmitButton();
                    uploadStatus.textContent = '';
                }, 3000);
            }, 2000);
        });
    });
</script>


</body>

</html>