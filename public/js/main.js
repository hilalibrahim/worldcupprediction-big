/**
 * PredictCup - Main JavaScript
 */

document.addEventListener('DOMContentLoaded', function() {
    setupToastNotifications();
    setupFormValidation();
    setupLoadingStates();
    setupModals();
    setupNotifications();
    setupMatchPrediction();
});

function setupToastNotifications() {
    const toastContainer = document.getElementById('toastContainer');
    
    if (!toastContainer) return;
    
    const flashMessage = document.body.dataset.flashMessage;
    const flashType = document.body.dataset.flashType;
    
    if (flashMessage && flashType) {
        showToast(flashMessage, flashType);
    }
}

function showToast(message, type = 'info') {
    const toast = document.createElement('div');
    toast.className = 'toast toast-' + type;
    toast.innerHTML = '<div class="toast-content"><span class="toast-message">' + message + '</span><button class="toast-close">&times;</button></div>';
    
    document.body.appendChild(toast);
    
    setTimeout(function() {
        toast.classList.add('active');
    }, 100);
    
    toast.querySelector('.toast-close').addEventListener('click', function() {
        toast.classList.remove('active');
        setTimeout(function() { toast.remove(); }, 300);
    });
    
    setTimeout(function() {
        toast.classList.remove('active');
        setTimeout(function() { toast.remove(); }, 300);
    }, 5000);
}

function setupFormValidation() {
    const forms = document.querySelectorAll('form');
    
    forms.forEach(function(form) {
        form.addEventListener('submit', function(e) {
            let isValid = true;
            
            const required = form.querySelectorAll('[required]');
            required.forEach(function(field) {
                if (!field.value.trim()) {
                    isValid = false;
                    field.classList.add('error');
                } else {
                    field.classList.remove('error');
                }
            });
            
            const email = form.querySelector('[type="email"]');
            if (email) {
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailRegex.test(email.value)) {
                    isValid = false;
                    email.classList.add('error');
                }
            }
            
            const password = form.querySelector('[type="password"]');
            if (password && password.value.length < 8) {
                isValid = false;
                password.classList.add('error');
            }
            
            if (!isValid) {
                e.preventDefault();
                showToast('Please fill in all required fields correctly', 'error');
            }
        });
    });
}

function setupLoadingStates() {
    const forms = document.querySelectorAll('form[data-ajax]');
    
    forms.forEach(function(form) {
        form.addEventListener('submit', function() {
            const submitBtn = this.querySelector('button[type="submit"]');
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<span class="loading"></span> Loading...';
            }
        });
    });
}

function setupModals() {
    const modals = document.querySelectorAll('.modal');
    
    modals.forEach(function(modal) {
        const closeBtn = modal.querySelector('.modal-close');
        const backdrop = modal;
        
        if (closeBtn) {
            closeBtn.addEventListener('click', function() {
                modal.classList.remove('active');
            });
        }
        
        backdrop.addEventListener('click', function(e) {
            if (e.target === backdrop) {
                modal.classList.remove('active');
            }
        });
    });
}

function setupNotifications() {
    const notificationIcon = document.getElementById('notificationIcon');
    const notificationCount = document.getElementById('notificationCount');
    const notificationsDropdown = document.getElementById('notificationsDropdown');
    
    if (notificationIcon && notificationCount) {
        loadUnreadCount();
        
        notificationIcon.addEventListener('click', function(e) {
            e.stopPropagation();
            notificationsDropdown.classList.toggle('active');
            loadNotifications();
        });
        
        document.addEventListener('click', function() {
            notificationsDropdown.classList.remove('active');
        });
    }
}

function loadUnreadCount() {
    fetch('/worldcupprediction-big/api/notifications/unread-count')
        .then(function(res) { return res.json(); })
        .then(function(data) {
            if (data.count > 0) {
                notificationCount.textContent = data.count;
                notificationCount.classList.add('show');
            }
        });
}

function loadNotifications() {
    fetch('/worldcupprediction-big/api/notifications')
        .then(function(res) { return res.json(); })
        .then(function(notifications) {
            const dropdown = document.getElementById('notificationsDropdown');
            const list = dropdown.querySelector('.notifications-list');
            
            list.innerHTML = notifications.map(function(n) {
                return '<div class="notification-item"><span class="notification-type badge badge-warning">' + n.type + '</span><p class="notification-message">' + n.message + '</p><small class="notification-time">' + formatTimeAgo(n.created_at) + '</small></div>';
            }).join('');
        });
}

function setupMatchPrediction() {
    const predictForm = document.getElementById('predictForm');
    
    if (predictForm) {
        // Just let the form submit normally to /worldcupprediction-big/predict
        // No need for AJAX handling
        return;
    }
}

function formatTimeAgo(timestamp) {
    const date = new Date(timestamp);
    const now = new Date();
    const diff = now - date;
    
    const seconds = Math.floor(diff / 1000);
    const minutes = Math.floor(seconds / 60);
    const hours = Math.floor(minutes / 60);
    const days = Math.floor(hours / 24);
    
    if (days > 0) return days + ' day' + (days > 1 ? 's' : '') + ' ago';
    if (hours > 0) return hours + ' hour' + (hours > 1 ? 's' : '') + ' ago';
    if (minutes > 0) return minutes + ' minute' + (minutes > 1 ? 's' : '') + ' ago';
    return 'Just now';
}

function getCsrfToken() {
    const token = document.querySelector('[name="csrf_token"]');
    return token ? token.value : '';
}
