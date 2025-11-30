import './bootstrap';
import Alpine from 'alpinejs';
import Sortable from 'sortablejs';
// Vue/Pinia removed: not used by current Blade views

// Make Alpine available globally
window.Alpine = Alpine;

// Vue removed: keep Alpine as primary UI behavior

// Global state management
const store = Alpine.store('app', {
    theme: localStorage.getItem('theme') || 'light',
    sidebarOpen: false,
    notifications: [],
    loading: false,
    
    init() {
        // Initialize theme
        this.setTheme(this.theme);
        
        // Check system preference
        if (!localStorage.getItem('theme')) {
            const systemPrefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            this.setTheme(systemPrefersDark ? 'dark' : 'light');
        }
    },
    
    setTheme(theme) {
        this.theme = theme;
        document.documentElement.classList.toggle('dark', theme === 'dark');
        localStorage.setItem('theme', theme);
    },
    
    toggleTheme() {
        this.setTheme(this.theme === 'dark' ? 'light' : 'dark');
    },
    
    toggleSidebar() {
        this.sidebarOpen = !this.sidebarOpen;
    },
    
    addNotification(message, type = 'info', duration = 5000) {
        const id = Date.now();
        this.notifications.push({ id, message, type });
        
        if (duration > 0) {
            setTimeout(() => {
                this.removeNotification(id);
            }, duration);
        }
        
        return id;
    },
    
    removeNotification(id) {
        const index = this.notifications.findIndex(n => n.id === id);
        if (index !== -1) {
            this.notifications.splice(index, 1);
        }
    },
    
    setLoading(state) {
        this.loading = state;
    }
});

// Alpine components
Alpine.data('dropdown', () => ({
    open: false,
    
    toggle() {
        this.open = !this.open;
    },
    
    close() {
        this.open = false;
    }
}));

Alpine.data('modal', (initialOpen = false) => ({
    open: initialOpen,
    
    show() {
        this.open = true;
        document.body.style.overflow = 'hidden';
    },
    
    hide() {
        this.open = false;
        document.body.style.overflow = '';
    }
}));

Alpine.data('tabs', (initialTab = 0) => ({
    activeTab: initialTab,
    
    setActiveTab(index) {
        this.activeTab = index;
    }
}));

Alpine.data('accordion', (initialOpen = false) => ({
    open: initialOpen,
    
    toggle() {
        this.open = !this.open;
    }
}));

Alpine.data('tooltip', (content) => ({
    content,
    show: false,
    
    toggle() {
        this.show = !this.show;
    }
}));

Alpine.data('fileUpload', () => ({
    files: [],
    dragOver: false,
    
    dragenter(e) {
        e.preventDefault();
        this.dragOver = true;
    },
    
    dragleave(e) {
        e.preventDefault();
        this.dragOver = false;
    },
    
    drop(e) {
        e.preventDefault();
        this.dragOver = false;
        this.addFiles(e.dataTransfer.files);
    },
    
    fileChange(e) {
        this.addFiles(e.target.files);
    },
    
    addFiles(files) {
        for (let i = 0; i < files.length; i++) {
            this.files.push(files[i]);
        }
    },
    
    removeFile(index) {
        this.files.splice(index, 1);
    }
}));

Alpine.data('search', () => ({
    query: '',
    results: [],
    loading: false,
    showResults: false,
    
    search() {
        if (this.query.length < 2) {
            this.results = [];
            this.showResults = false;
            return;
        }
        
        this.loading = true;
        
        fetch(`/search?q=${encodeURIComponent(this.query)}`)
            .then(response => response.json())
            .then(data => {
                this.results = data;
                this.showResults = true;
                this.loading = false;
            })
            .catch(error => {
                console.error('Search error:', error);
                this.loading = false;
            });
    },
    
    clear() {
        this.query = '';
        this.results = [];
        this.showResults = false;
    },
    
    selectResult(result) {
        this.query = result.title;
        this.showResults = false;
        window.location.href = result.url;
    }
}));

Alpine.data('countdown', (targetDate) => ({
    days: 0,
    hours: 0,
    minutes: 0,
    seconds: 0,
    
    init() {
        this.update();
        setInterval(() => this.update(), 1000);
    },
    
    update() {
        const now = new Date().getTime();
        const distance = new Date(targetDate).getTime() - now;
        
        if (distance < 0) {
            this.days = 0;
            this.hours = 0;
            this.minutes = 0;
            this.seconds = 0;
            return;
        }
        
        this.days = Math.floor(distance / (1000 * 60 * 60 * 24));
        this.hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        this.minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        this.seconds = Math.floor((distance % (1000 * 60)) / 1000);
    }
}));

Alpine.data('rating', (initialRating = 0, maxRating = 5) => ({
    rating: initialRating,
    maxRating,
    
    setRating(value) {
        this.rating = value;
        this.$dispatch('rating-changed', { rating: value });
    }
}));

Alpine.data('passwordStrength', () => ({
    password: '',
    strength: 0,
    message: '',
    
    checkStrength() {
        if (!this.password) {
            this.strength = 0;
            this.message = '';
            return;
        }
        
        let score = 0;
        
        // Length check
        if (this.password.length >= 8) score++;
        if (this.password.length >= 12) score++;
        
        // Complexity checks
        if (/[a-z]/.test(this.password)) score++;
        if (/[A-Z]/.test(this.password)) score++;
        if (/[0-9]/.test(this.password)) score++;
        if (/[^a-zA-Z0-9]/.test(this.password)) score++;
        
        this.strength = Math.min(5, score);
        
        // Set message based on strength
        switch (this.strength) {
            case 0:
            case 1:
                this.message = 'Very weak';
                break;
            case 2:
                this.message = 'Weak';
                break;
            case 3:
                this.message = 'Fair';
                break;
            case 4:
                this.message = 'Good';
                break;
            case 5:
                this.message = 'Strong';
                break;
        }
    }
}));

// Initialize app
document.addEventListener('DOMContentLoaded', function() {
    // Initialize dark mode
    initDarkMode();
    
    // Initialize sortable lists
    initSortable();
    
    // Initialize notifications
    initNotifications();
    
    // Initialize task interactions
    initTaskInteractions();
    
    // Initialize form enhancements
    initFormEnhancements();
    
    // Initialize mobile menu
    initMobileMenu();
    
    // Initialize search functionality
    initSearch();
    
    // Initialize lazy loading
    initLazyLoading();
    
    // Initialize keyboard shortcuts
    initKeyboardShortcuts();
    
    // Initialize performance monitoring
    initPerformanceMonitoring();
    
    // Initialize error handling
    initErrorHandling();
    
    // Initialize analytics
    initAnalytics();
    
    // Initialize PWA features
    initPWAFeatures();
    
    // Initialize API helpers
    initAPIHelpers();
    
    // Vue mount removed: no #vue-app element in views
});

// Dark Mode Toggle
function initDarkMode() {
    const darkModeToggle = document.getElementById('dark-mode-toggle');
    
    if (darkModeToggle) {
        darkModeToggle.addEventListener('click', function() {
            store.toggleTheme();
        });
    }
}

// Sortable functionality for drag and drop
function initSortable() {
    const taskList = document.getElementById('task-list');
    
    if (taskList) {
        new Sortable(taskList, {
            animation: 150,
            ghostClass: 'sortable-ghost',
            chosenClass: 'sortable-chosen',
            dragClass: 'sortable-drag',
            onEnd: function(evt) {
                // Update task order via AJAX
                updateTaskOrder(evt);
            }
        });
    }
}

// Update task order
async function updateTaskOrder(evt) {
    const taskId = evt.item.dataset.taskId;
    const newIndex = evt.newIndex;
    
    try {
        store.setLoading(true);
        
        const response = await apiRequest('/tasks/reorder', {
            method: 'POST',
            body: JSON.stringify({
                task_id: taskId,
                new_position: newIndex
            })
        });
        
        if (response.success) {
            store.addNotification('Task order updated successfully', 'success');
        } else {
            showNotification('Failed to update task order', 'error');
        }
    } catch (error) {
        console.error('Error:', error);
        store.addNotification('An error occurred while updating task order', 'error');
    } finally {
        store.setLoading(false);
    }
}

// Notification system
function initNotifications() {
    // Initialize notification container if it doesn't exist
    if (!document.getElementById('notification-container')) {
        const container = document.createElement('div');
        container.id = 'notification-container';
        container.className = 'fixed top-4 right-4 z-50 space-y-2';
        document.body.appendChild(container);
    }
}

function showNotification(message, type = 'info', duration = 5000) {
    return store.addNotification(message, type, duration);
}

function hideNotification(id) {
    store.removeNotification(id);
}

// Task interactions
function initTaskInteractions() {
    // Task completion toggle
    document.addEventListener('change', function(e) {
        if (e.target.classList.contains('task-checkbox')) {
            toggleTaskCompletion(e.target);
        }
    });
    
    // Task deletion
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('delete-task-btn')) {
            e.preventDefault();
            deleteTask(e.target);
        }
    });
    
    // Task priority change
    document.addEventListener('change', function(e) {
        if (e.target.classList.contains('task-priority')) {
            changeTaskPriority(e.target);
        }
    });
}

// Toggle task completion
async function toggleTaskCompletion(checkbox) {
    const taskId = checkbox.dataset.taskId;
    const isCompleted = checkbox.checked;
    const taskItem = checkbox.closest('.task-item');
    
    try {
        const response = await apiRequest(`/tasks/${taskId}/toggle`, {
            method: 'POST',
            body: JSON.stringify({
                completed: isCompleted
            })
        });
        
        if (response.success) {
            if (isCompleted) {
                taskItem.classList.add('task-completed');
                showNotification('Task marked as completed!', 'success');
            } else {
                taskItem.classList.remove('task-completed');
                showNotification('Task marked as pending', 'info');
            }
        } else {
            // Revert checkbox state
            checkbox.checked = !isCompleted;
            showNotification('Failed to update task status', 'error');
        }
    } catch (error) {
        console.error('Error:', error);
        checkbox.checked = !isCompleted;
        showNotification('An error occurred while updating task', 'error');
    }
}

// Delete task
async function deleteTask(button) {
    if (!confirm('Are you sure you want to delete this task?')) {
        return;
    }
    
    const taskId = button.dataset.taskId;
    const taskItem = button.closest('.task-item');
    
    try {
        const response = await apiRequest(`/tasks/${taskId}`, {
            method: 'DELETE'
        });
        
        if (response.success) {
            taskItem.style.opacity = '0';
            taskItem.style.transform = 'translateX(-100%)';
            setTimeout(() => {
                taskItem.remove();
            }, 300);
            showNotification('Task deleted successfully', 'success');
        } else {
            showNotification('Failed to delete task', 'error');
        }
    } catch (error) {
        console.error('Error:', error);
        showNotification('An error occurred while deleting task', 'error');
    }
}

// Change task priority
async function changeTaskPriority(select) {
    const taskId = select.dataset.taskId;
    const priority = select.value;
    const taskItem = select.closest('.task-item');
    
    try {
        const response = await apiRequest(`/tasks/${taskId}/priority`, {
            method: 'POST',
            body: JSON.stringify({
                priority: priority
            })
        });
        
        if (response.success) {
            // Update UI to reflect priority change
            taskItem.classList.remove('priority-high', 'priority-medium', 'priority-low');
            taskItem.classList.add(`priority-${priority}`);
            showNotification('Task priority updated', 'success');
        } else {
            showNotification('Failed to update task priority', 'error');
        }
    } catch (error) {
        console.error('Error:', error);
        showNotification('An error occurred while updating task priority', 'error');
    }
}

// Form enhancements
function initFormEnhancements() {
    // Add focus effects to form inputs
    const inputs = document.querySelectorAll('.form-input, .form-select');
    inputs.forEach(input => {
        input.addEventListener('focus', function() {
            this.parentElement.classList.add('ring-2', 'ring-primary-500', 'ring-opacity-50');
        });
        
        input.addEventListener('blur', function() {
            this.parentElement.classList.remove('ring-2', 'ring-primary-500', 'ring-opacity-50');
        });
    });
    
    // Add character counter to textareas
    const textareas = document.querySelectorAll('textarea[maxlength]');
    textareas.forEach(textarea => {
        // Create counter element if it doesn't exist
        if (!textarea.parentElement.querySelector('.char-counter')) {
            const counter = document.createElement('div');
            counter.className = 'char-counter text-xs text-gray-500 mt-1';
            counter.textContent = `0/${textarea.getAttribute('maxlength')}`;
            textarea.parentElement.appendChild(counter);
        }
        
        // Update counter on input
        textarea.addEventListener('input', function() {
            const maxLength = this.getAttribute('maxlength');
            const currentLength = this.value.length;
            const counter = this.parentElement.querySelector('.char-counter');
            if (counter) {
                counter.textContent = `${currentLength}/${maxLength}`;
                
                // Change color based on remaining characters
                if (currentLength > maxLength * 0.9) {
                    counter.classList.add('text-red-500');
                    counter.classList.remove('text-gray-500');
                } else {
                    counter.classList.add('text-gray-500');
                    counter.classList.remove('text-red-500');
                }
            }
        });
    });
    
    // Initialize form validation
    initFormValidation();
    
    // Initialize auto-save functionality
    initAutoSave();
}

// Form validation
function initFormValidation() {
    const forms = document.querySelectorAll('form[data-validate]');
    
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            let isValid = true;
            
            // Validate required fields
            const requiredFields = form.querySelectorAll('[required]');
            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    isValid = false;
                    showFieldError(field, 'This field is required');
                } else {
                    clearFieldError(field);
                }
            });
            
            // Validate email fields
            const emailFields = form.querySelectorAll('[type="email"]');
            emailFields.forEach(field => {
                if (field.value && !isValidEmail(field.value)) {
                    isValid = false;
                    showFieldError(field, 'Please enter a valid email address');
                } else {
                    clearFieldError(field);
                }
            });
            
            // Validate password fields
            const passwordFields = form.querySelectorAll('[type="password"]');
            passwordFields.forEach(field => {
                if (field.value && field.value.length < 8) {
                    isValid = false;
                    showFieldError(field, 'Password must be at least 8 characters');
                } else {
                    clearFieldError(field);
                }
            });
            
            // Validate confirm password
            const confirmPasswordFields = form.querySelectorAll('[data-confirm-password]');
            confirmPasswordFields.forEach(field => {
                const passwordField = document.getElementById(field.dataset.confirmPassword);
                if (passwordField && field.value !== passwordField.value) {
                    isValid = false;
                    showFieldError(field, 'Passwords do not match');
                } else {
                    clearFieldError(field);
                }
            });
            
            if (!isValid) {
                e.preventDefault();
                showNotification('Please fix the errors in the form', 'error');
            }
        });
    });
}

// Show field error
function showFieldError(field, message) {
    clearFieldError(field);
    
    field.classList.add('border-red-500');
    
    const errorElement = document.createElement('div');
    errorElement.className = 'form-error text-red-500 text-sm mt-1';
    errorElement.textContent = message;
    
    field.parentElement.appendChild(errorElement);
}

// Clear field error
function clearFieldError(field) {
    field.classList.remove('border-red-500');
    
    const errorElement = field.parentElement.querySelector('.form-error');
    if (errorElement) {
        errorElement.remove();
    }
}

// Check if email is valid
function isValidEmail(email) {
    const re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(String(email).toLowerCase());
}

// Auto-save functionality
function initAutoSave() {
    const autoSaveForms = document.querySelectorAll('form[data-auto-save]');
    
    autoSaveForms.forEach(form => {
        let saveTimeout;
        
        form.addEventListener('input', function() {
            clearTimeout(saveTimeout);
            saveTimeout = setTimeout(() => {
                autoSaveForm(form);
            }, 2000);
        });
    });
}

// Auto-save form
async function autoSaveForm(form) {
    try {
        const formData = new FormData(form);
        const data = Object.fromEntries(formData.entries());
        
        const response = await apiRequest(form.dataset.autoSave, {
            method: 'POST',
            body: JSON.stringify(data)
        });
        
        if (response.success) {
            showNotification('Draft saved', 'info', 2000);
        }
    } catch (error) {
        console.error('Auto-save error:', error);
    }
}

// Mobile menu
function initMobileMenu() {
    const mobileMenuButton = document.querySelector('[data-mobile-menu-button]');
    const mobileMenu = document.querySelector('[data-mobile-menu]');
    
    if (mobileMenuButton && mobileMenu) {
        mobileMenuButton.addEventListener('click', function() {
            store.toggleSidebar();
        });
    }
}

// Search functionality
function initSearch() {
    const searchInput = document.getElementById('search-input');
    const filterForm = document.getElementById('filter-form');
    
    if (searchInput) {
        let searchTimeout;
        
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                if (filterForm) {
                    filterForm.submit();
                }
            }, 500);
        });
    }
}

// Lazy loading
function initLazyLoading() {
    if ('IntersectionObserver' in window) {
        const lazyImages = document.querySelectorAll('img[data-src]');
        
        const imageObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    img.src = img.dataset.src;
                    img.classList.remove('lazy');
                    imageObserver.unobserve(img);
                }
            });
        });
        
        lazyImages.forEach(img => {
            imageObserver.observe(img);
        });
    }
}

// Keyboard shortcuts
function initKeyboardShortcuts() {
    document.addEventListener('keydown', function(e) {
        // Ctrl/Cmd + K for search
        if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
            e.preventDefault();
            const searchInput = document.getElementById('search-input');
            if (searchInput) {
                searchInput.focus();
            }
        }
        
        // Escape to close modals
        if (e.key === 'Escape') {
            const modals = document.querySelectorAll('.modal.active');
            modals.forEach(modal => {
                modal.classList.remove('active');
            });
        }
        
        // Ctrl/Cmd + / for keyboard shortcuts help
        if ((e.ctrlKey || e.metaKey) && e.key === '/') {
            e.preventDefault();
            toggleKeyboardShortcutsHelp();
        }
    });
}

// Toggle keyboard shortcuts help
function toggleKeyboardShortcutsHelp() {
    const helpModal = document.getElementById('keyboard-shortcuts-help');
    
    if (helpModal) {
        helpModal.classList.toggle('hidden');
    } else {
        // Create help modal if it doesn't exist
        const modal = document.createElement('div');
        modal.id = 'keyboard-shortcuts-help';
        modal.className = 'modal fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50';
        modal.innerHTML = `
            <div class="bg-white dark:bg-gray-800 rounded-lg p-6 max-w-md w-full mx-4">
                <h2 class="text-xl font-bold mb-4">Keyboard Shortcuts</h2>
                <div class="space-y-2">
                    <div class="flex justify-between">
                        <span>Search</span>
                        <kbd class="px-2 py-1 bg-gray-100 dark:bg-gray-700 rounded">Ctrl/Cmd + K</kbd>
                    </div>
                    <div class="flex justify-between">
                        <span>Close Modal</span>
                        <kbd class="px-2 py-1 bg-gray-100 dark:bg-gray-700 rounded">Esc</kbd>
                    </div>
                    <div class="flex justify-between">
                        <span>Toggle Dark Mode</span>
                        <kbd class="px-2 py-1 bg-gray-100 dark:bg-gray-700 rounded">Ctrl/Cmd + D</kbd>
                    </div>
                </div>
                <button class="mt-6 px-4 py-2 bg-primary-600 text-white rounded hover:bg-primary-700" onclick="this.closest('.modal').remove()">Close</button>
            </div>
        `;
        document.body.appendChild(modal);
    }
}

// Performance monitoring
function initPerformanceMonitoring() {
    // Log page load time
    window.addEventListener('load', function() {
        const loadTime = performance.timing.loadEventEnd - performance.timing.navigationStart;
        console.log(`Page load time: ${loadTime}ms`);
    });
    
    // Monitor long tasks
    if ('PerformanceObserver' in window) {
        const observer = new PerformanceObserver((list) => {
            for (const entry of list.getEntries()) {
                if (entry.duration > 50) {
                    console.warn(`Long task detected: ${entry.duration}ms`);
                }
            }
        });
        
        observer.observe({ entryTypes: ['longtask'] });
    }
}

// Error handling
function initErrorHandling() {
    window.addEventListener('error', function(e) {
        console.error('Global error:', e.error);
        showNotification('An unexpected error occurred', 'error');
    });
    
    window.addEventListener('unhandledrejection', function(e) {
        console.error('Unhandled promise rejection:', e.reason);
        showNotification('An unexpected error occurred', 'error');
    });
}

// Analytics
function initAnalytics() {
    // Track page views
    if (typeof gtag !== 'undefined') {
        gtag('config', 'GA_MEASUREMENT_ID', {
            page_location: window.location.href
        });
    }
    
    // Track custom events
    window.trackEvent = function(category, action, label) {
        if (typeof gtag !== 'undefined') {
            gtag('event', action, {
                event_category: category,
                event_label: label
            });
        }
    };
}

// PWA features
function initPWAFeatures() {
    // Register service worker
    if ('serviceWorker' in navigator) {
        navigator.serviceWorker.register('/service-worker.js')
            .then(registration => {
                console.log('ServiceWorker registration successful with scope: ', registration.scope);
            })
            .catch(error => {
                console.log('ServiceWorker registration failed: ', error);
            });
    }
    
    // Install prompt
    let deferredPrompt;
    
    window.addEventListener('beforeinstallprompt', (e) => {
        e.preventDefault();
        deferredPrompt = e;
        
        // Show install button
        const installButton = document.getElementById('install-app');
        if (installButton) {
            installButton.style.display = 'block';
            
            installButton.addEventListener('click', () => {
                deferredPrompt.prompt();
                deferredPrompt.userChoice.then((choiceResult) => {
                    if (choiceResult.outcome === 'accepted') {
                        console.log('User accepted the install prompt');
                    } else {
                        console.log('User dismissed the install prompt');
                    }
                    deferredPrompt = null;
                });
            });
        }
    });
}

// API helpers
function initAPIHelpers() {
    // Set up default headers
    window.apiHeaders = {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
    };
    
    // Generic API request function
    window.apiRequest = async function(url, options = {}) {
        const defaultOptions = {
            headers: window.apiHeaders
        };
        
        const mergedOptions = { ...defaultOptions, ...options };
        
        try {
            const response = await fetch(url, mergedOptions);
            
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            
            return await response.json();
        } catch (error) {
            console.error('API request error:', error);
            throw error;
        }
    };
    
    // Specific API methods
    window.api = {
        get: (url) => apiRequest(url),
        post: (url, data) => apiRequest(url, {
            method: 'POST',
            body: JSON.stringify(data)
        }),
        put: (url, data) => apiRequest(url, {
            method: 'PUT',
            body: JSON.stringify(data)
        }),
        delete: (url) => apiRequest(url, {
            method: 'DELETE'
        })
    };
}

// Export functionality
function exportTasks(format) {
    const url = `/tasks/export?format=${format}`;
    window.open(url, '_blank');
    showNotification(`Exporting tasks to ${format.toUpperCase()}...`, 'info');
}

// Utility functions
window.debounce = function(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
};

window.throttle = function(func, limit) {
    let inThrottle;
    return function() {
        const args = arguments;
        const context = this;
        if (!inThrottle) {
            func.apply(context, args);
            inThrottle = true;
            setTimeout(() => inThrottle = false, limit);
        }
    };
};

// Make functions globally available
window.showNotification = showNotification;
window.hideNotification = hideNotification;
window.exportTasks = exportTasks;

// Start Alpine
Alpine.start();
