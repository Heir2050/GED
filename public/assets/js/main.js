
document.addEventListener('DOMContentLoaded', function() {
    const fileInput = document.getElementById('fileInput');
    const fileUploadArea = document.getElementById('fileUploadArea');
    const filePreview = document.getElementById('filePreview');
    const fileLabelText = document.getElementById('fileLabelText');
    const defaultFileIcon = document.getElementById('defaultFileIcon');

    // Extensions autorisées (doivent correspondre à votre validation PHP)
    const allowedExtensions = ['jpg', 'jpeg', 'png', 'pdf', 'doc', 'docx', 'xls', 'xlsx', 'zip', 'rar'];
    const maxFileSize = 10 * 1024 * 1024; // 10MB

    // Gestion du glisser-déposer
    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        fileUploadArea.addEventListener(eventName, preventDefaults, false);
    });

    function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }

    ['dragenter', 'dragover'].forEach(eventName => {
        fileUploadArea.addEventListener(eventName, highlight, false);
    });

    ['dragleave', 'drop'].forEach(eventName => {
        fileUploadArea.addEventListener(eventName, unhighlight, false);
    });

    function highlight() {
        fileUploadArea.classList.add('active');
    }

    function unhighlight() {
        fileUploadArea.classList.remove('active');
    }

    fileUploadArea.addEventListener('drop', handleDrop, false);

    function handleDrop(e) {
        const dt = e.dataTransfer;
        const files = dt.files;
        if (files.length > 0) {
            fileInput.files = files;
            handleFileSelection(files[0]);
        }
    }

    fileInput.addEventListener('change', function() {
        if (this.files.length > 0) {
            handleFileSelection(this.files[0]);
        }
    });

    function handleFileSelection(file) {
        // Vérifier l'extension du fichier
        const fileExt = file.name.split('.').pop().toLowerCase();
        if (!allowedExtensions.includes(fileExt)) {
            showError('Extension de fichier non autorisée');
            clearFileInput();
            return;
        }

        // Vérifier la taille du fichier
        if (file.size > maxFileSize) {
            showError('Fichier trop volumineux (max 10MB)');
            clearFileInput();
            return;
        }

        // Mettre à jour le texte du label
        fileLabelText.textContent = file.name;
        defaultFileIcon.style.display = 'none';
        
        // Créer la prévisualisation
        filePreview.innerHTML = '';
        filePreview.style.display = 'block';
        
        const previewContent = document.createElement('div');
        previewContent.className = 'file-preview-content';
        
        // Ajouter l'icône SVG appropriée
        const fileIcon = document.createElement('div');
        fileIcon.className = 'file-icon-svg';
        fileIcon.innerHTML = getFileIconSVG(fileExt);
        previewContent.appendChild(fileIcon);
        
        // Pour les images, afficher la prévisualisation
        if (file.type.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const imgPreview = document.createElement('img');
                imgPreview.src = e.target.result;
                imgPreview.style.maxWidth = '100px';
                imgPreview.style.maxHeight = '100px';
                previewContent.prepend(imgPreview);
            };
            reader.readAsDataURL(file);
        }
        
        const fileInfo = document.createElement('div');
        fileInfo.className = 'file-info';
        
        const fileName = document.createElement('div');
        fileName.className = 'file-name';
        fileName.textContent = file.name;
        
        const fileSize = document.createElement('div');
        fileSize.className = 'file-size';
        fileSize.textContent = formatFileSize(file.size);
        
        const removeBtn = document.createElement('button');
        removeBtn.className = 'remove-file';
        removeBtn.innerHTML = '&times;';
        removeBtn.addEventListener('click', function(e) {
            e.preventDefault();
            clearFileInput();
        });
        
        fileInfo.appendChild(fileName);
        fileInfo.appendChild(fileSize);
        previewContent.appendChild(fileInfo);
        previewContent.appendChild(removeBtn);
        filePreview.appendChild(previewContent);
    }
    
    function getFileIconSVG(extension) {
        // Définir les SVG pour chaque type de fichier
        const svgIcons = {
            'pdf': '<svg viewBox="0 0 24 24"><path fill="#E53935" d="M14,2H6A2,2 0 0,0 4,4V20A2,2 0 0,0 6,22H18A2,2 0 0,0 20,20V8L14,2M18,20H6V4H13V9H18V20M12.5,11.5C10.29,11.5 8.5,13.29 8.5,15.5C8.5,17.71 10.29,19.5 12.5,19.5C14.71,19.5 16.5,17.71 16.5,15.5C16.5,13.29 14.71,11.5 12.5,11.5M13,13H14V14H13V13M13,15H14V17H13V15Z"/></svg>',
            'doc': '<svg viewBox="0 0 24 24"><path fill="#2196F3" d="M14,2H6A2,2 0 0,0 4,4V20A2,2 0 0,0 6,22H18A2,2 0 0,0 20,20V8L14,2M18,20H6V4H13V9H18V20M17,13H13V18H15V15H17V13Z"/></svg>',
            'docx': '<svg viewBox="0 0 24 24"><path fill="#2196F3" d="M14,2H6A2,2 0 0,0 4,4V20A2,2 0 0,0 6,22H18A2,2 0 0,0 20,20V8L14,2M18,20H6V4H13V9H18V20M17,13H13V18H15V15H17V13Z"/></svg>',
            'xls': '<svg viewBox="0 0 24 24"><path fill="#4CAF50" d="M14,2H6A2,2 0 0,0 4,4V20A2,2 0 0,0 6,22H18A2,2 0 0,0 20,20V8L14,2M18,20H6V4H13V9H18V20M12.9,14.5L15.8,11.6L16.2,12C16.6,12.4 16.6,13 16.2,13.4L13.3,16.3L16.2,19.2C16.6,19.6 16.6,20.2 16.2,20.6C15.8,21 15.2,21 14.8,20.6L12,17.7L9.2,20.6C8.8,21 8.2,21 7.8,20.6C7.4,20.2 7.4,19.6 7.8,19.2L10.7,16.3L7.8,13.4C7.4,13 7.4,12.4 7.8,12C8.2,11.6 8.8,11.6 9.2,12L12.1,14.9L12.9,14.5Z"/></svg>',
            'xlsx': '<svg viewBox="0 0 24 24"><path fill="#4CAF50" d="M14,2H6A2,2 0 0,0 4,4V20A2,2 0 0,0 6,22H18A2,2 0 0,0 20,20V8L14,2M18,20H6V4H13V9H18V20M12.9,14.5L15.8,11.6L16.2,12C16.6,12.4 16.6,13 16.2,13.4L13.3,16.3L16.2,19.2C16.6,19.6 16.6,20.2 16.2,20.6C15.8,21 15.2,21 14.8,20.6L12,17.7L9.2,20.6C8.8,21 8.2,21 7.8,20.6C7.4,20.2 7.4,19.6 7.8,19.2L10.7,16.3L7.8,13.4C7.4,13 7.4,12.4 7.8,12C8.2,11.6 8.8,11.6 9.2,12L12.1,14.9L12.9,14.5Z"/></svg>',
            'zip': '<svg viewBox="0 0 24 24"><path fill="#795548" d="M14,2H6A2,2 0 0,0 4,4V20A2,2 0 0,0 6,22H18A2,2 0 0,0 20,20V8L14,2M18,20H6V4H13V9H18V20M10,11H8V18H10V11M16,11H12V13H14V15H12V18H16V15H14V13H16V11Z"/></svg>',
            'rar': '<svg viewBox="0 0 24 24"><path fill="#795548" d="M14,2H6A2,2 0 0,0 4,4V20A2,2 0 0,0 6,22H18A2,2 0 0,0 20,20V8L14,2M18,20H6V4H13V9H18V20M10,11H8V18H10V11M16,11H12V13H14V15H12V18H16V15H14V13H16V11Z"/></svg>',
            'jpg': '<svg viewBox="0 0 24 24"><path fill="#4CAF50" d="M8.5,13.5L11,16.5L14.5,12L19,18H5M21,19V5C21,3.89 20.1,3 19,3H5A2,2 0 0,0 3,5V19A2,2 0 0,0 5,21H19A2,2 0 0,0 21,19Z"/></svg>',
            'jpeg': '<svg viewBox="0 0 24 24"><path fill="#4CAF50" d="M8.5,13.5L11,16.5L14.5,12L19,18H5M21,19V5C21,3.89 20.1,3 19,3H5A2,2 0 0,0 3,5V19A2,2 0 0,0 5,21H19A2,2 0 0,0 21,19Z"/></svg>',
            'png': '<svg viewBox="0 0 24 24"><path fill="#4CAF50" d="M8.5,13.5L11,16.5L14.5,12L19,18H5M21,19V5C21,3.89 20.1,3 19,3H5A2,2 0 0,0 3,5V19A2,2 0 0,0 5,21H19A2,2 0 0,0 21,19Z"/></svg>',
            'default': '<svg viewBox="0 0 24 24"><path fill="#9E9E9E" d="M14,2H6A2,2 0 0,0 4,4V20A2,2 0 0,0 6,22H18A2,2 0 0,0 20,20V8L14,2M18,20H6V4H13V9H18V20Z"/></svg>'
        };

        return svgIcons[extension] || svgIcons['default'];
    }
    
    function formatFileSize(bytes) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    }
    
    function showError(message) {
        alert(message); // Vous pouvez remplacer par un affichage plus élégant
    }
});

function clearFileInput() {
    const fileInput = document.getElementById('fileInput');
    const filePreview = document.getElementById('filePreview');
    const fileLabelText = document.getElementById('fileLabelText');
    const defaultFileIcon = document.getElementById('defaultFileIcon');
    
    fileInput.value = '';
    filePreview.style.display = 'none';
    filePreview.innerHTML = '';
    fileLabelText.textContent = 'Glissez-déposez votre fichier ou cliquez pour sélectionner';
    defaultFileIcon.style.display = 'block';
}

function resetForm() {
    document.getElementById('submissionForm').reset();
    clearFileInput();
}

document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('submissionForm');
    const fileInput = document.getElementById('fileInput');

    form.addEventListener('submit', function(e) {
        if (!fileInput.files || fileInput.files.length === 0) {
            e.preventDefault();
            alert('Veuillez sélectionner un fichier avant de soumettre le formulaire.');
        }
    });
});

// Toast message
document.addEventListener('DOMContentLoaded', function() {
    let toast = document.getElementById('toast-notif');
    if (toast) {
        toast.style.opacity = 1;
        setTimeout(() => {
            toast.style.opacity = 0;
        }, 5000); // 5 secondes
    }
});