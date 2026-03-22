import './bootstrap';

import Alpine from 'alpinejs';
window.Alpine = Alpine;
Alpine.start();

/**
 * TC Service Center UI Logic
 */
document.addEventListener('DOMContentLoaded', () => {
    console.log('TC Service Center Luxury UI Loaded');

    // Example: Auto-hide success alerts after 3 seconds
    const alerts = document.querySelectorAll('.alert-success');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 500);
        }, 3000);
    });
});

// Logic for confirming status changes (e.g., moving to "Out for Delivery")
window.confirmStatusChange = function (status) {
    return confirm(`Are you sure you want to move this job to ${status}?`);
};