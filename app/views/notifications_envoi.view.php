<?php
defined('ROOTPATH') or exit('Access Denied!');
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifications d'envoi de dossiers</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .notification-item {
            border-left: 4px solid #007bff;
            transition: all 0.3s ease;
        }
        .notification-item:hover {
            background-color: #f8f9fa;
            transform: translateX(5px);
        }
        .notification-unread {
            background-color: #e3f2fd;
            border-left-color: #2196f3;
        }
        .notification-read {
            opacity: 0.7;
        }
        .badge-notification {
            background-color: #dc3545;
            color: white;
            border-radius: 50%;
            padding: 4px 8px;
            font-size: 0.75rem;
        }
        .time-ago {
            color: #6c757d;
            font-size: 0.875rem;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 d-md-block bg-light sidebar">
                <div class="position-sticky pt-3">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link" href="<?= ROOT ?>/document">
                                <i class="fas fa-folder"></i> Dossiers
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" href="<?= ROOT ?>/notifications/envoi_dossiers">
                                <i class="fas fa-bell"></i> Notifications d'envoi
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= ROOT ?>/notifications">
                                <i class="fas fa-list"></i> Toutes les notifications
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Main content -->
            <div class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">
                        <i class="fas fa-paper-plane text-primary"></i>
                        Notifications d'envoi de dossiers
                    </h1>
                    <div class="btn-toolbar mb-2 mb-md-0">
                        <button type="button" class="btn btn-outline-secondary" onclick="markAllAsRead()">
                            <i class="fas fa-check-double"></i> Marquer tout comme lu
                        </button>
                    </div>
                </div>

                <!-- Statistiques -->
                <div class="row mb-4">
                    <div class="col-md-4">
                        <div class="card text-white bg-primary">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h4><?= count($data['notifications_envoi']) ?></h4>
                                        <p class="mb-0">Total notifications</p>
                                    </div>
                                    <div class="align-self-center">
                                        <i class="fas fa-bell fa-2x"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card text-white bg-warning">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h4 id="unread-count">
                                            <?= count(array_filter($data['notifications_envoi'], function($n) { return !$n->is_read; })) ?>
                                        </h4>
                                        <p class="mb-0">Non lues</p>
                                    </div>
                                    <div class="align-self-center">
                                        <i class="fas fa-exclamation fa-2x"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card text-white bg-success">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h4><?= count(array_filter($data['notifications_envoi'], function($n) { return $n->is_read; })) ?></h4>
                                        <p class="mb-0">Lues</p>
                                    </div>
                                    <div class="align-self-center">
                                        <i class="fas fa-check fa-2x"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Liste des notifications -->
                <div class="row">
                    <div class="col-12">
                        <?php if (empty($data['notifications_envoi'])): ?>
                            <div class="alert alert-info text-center">
                                <i class="fas fa-info-circle fa-2x mb-3"></i>
                                <h4>Aucune notification d'envoi de dossier</h4>
                                <p>Vous n'avez reçu aucun dossier pour le moment.</p>
                            </div>
                        <?php else: ?>
                            <div class="list-group">
                                <?php foreach ($data['notifications_envoi'] as $notification): ?>
                                    <div class="list-group-item notification-item <?= $notification->is_read ? 'notification-read' : 'notification-unread' ?>" 
                                         data-notification-id="<?= $notification->id ?>">
                                        <div class="d-flex w-100 justify-content-between">
                                            <div class="flex-grow-1">
                                                <div class="d-flex align-items-start">
                                                    <div class="me-3">
                                                        <?php if (!$notification->is_read): ?>
                                                            <span class="badge-notification">!</span>
                                                        <?php else: ?>
                                                            <i class="fas fa-check-circle text-success"></i>
                                                        <?php endif; ?>
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <h5 class="mb-1">
                                                            <i class="fas fa-paper-plane text-primary"></i>
                                                            <?= htmlspecialchars($notification->message) ?>
                                                        </h5>
                                                        <p class="mb-1">
                                                            <strong>Dossier :</strong> 
                                                            <a href="<?= ROOT ?>/document?dossier_id=<?= $notification->dossier_id ?>" 
                                                               class="text-decoration-none">
                                                                <?= htmlspecialchars($notification->dossier_nom) ?>
                                                            </a>
                                                        </p>
                                                        <p class="mb-1">
                                                            <strong>Envoyé par :</strong> 
                                                            <?= htmlspecialchars($notification->envoyeur_prenom . ' ' . $notification->envoyeur_nom) ?>
                                                            <?php if ($notification->service_envoyeur_nom): ?>
                                                                <span class="badge bg-secondary"><?= htmlspecialchars($notification->service_envoyeur_nom) ?></span>
                                                            <?php endif; ?>
                                                        </p>
                                                        <small class="time-ago">
                                                            <i class="fas fa-clock"></i>
                                                            <?= date('d/m/Y à H:i', strtotime($notification->date_notification)) ?>
                                                            <?php if ($notification->is_read && $notification->date_lecture): ?>
                                                                - Lu le <?= date('d/m/Y à H:i', strtotime($notification->date_lecture)) ?>
                                                            <?php endif; ?>
                                                        </small>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="ms-3">
                                                <div class="btn-group-vertical" role="group">
                                                    <a href="<?= ROOT ?>/document?dossier_id=<?= $notification->dossier_id ?>" 
                                                       class="btn btn-primary btn-sm">
                                                        <i class="fas fa-eye"></i> Voir le dossier
                                                    </a>
                                                    <?php if (!$notification->is_read): ?>
                                                        <button type="button" class="btn btn-outline-success btn-sm" 
                                                                onclick="markAsRead(<?= $notification->id ?>)">
                                                            <i class="fas fa-check"></i> Marquer comme lu
                                                        </button>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function markAsRead(notificationId) {
            fetch('<?= ROOT ?>/notifications/mark_read/' + notificationId, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                }
            })
            .then(response => response.text())
            .then(data => {
                if (data === 'ok') {
                    // Mettre à jour l'interface
                    const notificationElement = document.querySelector(`[data-notification-id="${notificationId}"]`);
                    if (notificationElement) {
                        notificationElement.classList.remove('notification-unread');
                        notificationElement.classList.add('notification-read');
                        
                        // Supprimer le badge de notification
                        const badge = notificationElement.querySelector('.badge-notification');
                        if (badge) {
                            badge.remove();
                        }
                        
                        // Ajouter l'icône de lu
                        const iconContainer = notificationElement.querySelector('.me-3');
                        if (iconContainer) {
                            iconContainer.innerHTML = '<i class="fas fa-check-circle text-success"></i>';
                        }
                        
                        // Supprimer le bouton "Marquer comme lu"
                        const markButton = notificationElement.querySelector('button[onclick*="markAsRead"]');
                        if (markButton) {
                            markButton.remove();
                        }
                        
                        // Mettre à jour le compteur
                        updateUnreadCount();
                    }
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
                alert('Erreur lors de la mise à jour de la notification');
            });
        }

        function markAllAsRead() {
            fetch('<?= ROOT ?>/notifications/mark_all_read', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                }
            })
            .then(response => response.text())
            .then(data => {
                if (data === 'ok') {
                    // Recharger la page pour mettre à jour l'interface
                    location.reload();
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
                alert('Erreur lors de la mise à jour des notifications');
            });
        }

        function updateUnreadCount() {
            const unreadElements = document.querySelectorAll('.notification-unread');
            const unreadCountElement = document.getElementById('unread-count');
            if (unreadCountElement) {
                unreadCountElement.textContent = unreadElements.length;
            }
        }

        // Auto-refresh des notifications non lues toutes les 30 secondes
        setInterval(function() {
            fetch('<?= ROOT ?>/notifications/api_envoi_non_lues')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const unreadCountElement = document.getElementById('unread-count');
                        if (unreadCountElement) {
                            unreadCountElement.textContent = data.count;
                        }
                    }
                })
                .catch(error => {
                    console.error('Erreur lors du rafraîchissement:', error);
                });
        }, 30000);
    </script>
</body>
</html>
