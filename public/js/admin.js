// Sidebar Toggle for Mobile
const sidebarToggle = document.querySelector('.sidebar-toggle');
const sidebar = document.querySelector('.sidebar');

if (sidebarToggle && sidebar) {
    sidebarToggle.addEventListener('click', () => {
        sidebar.classList.toggle('active');
    });
}

// Active Navigation Link
const navLinks = document.querySelectorAll('.nav-link');
const currentPath = window.location.pathname;

navLinks.forEach(link => {
    if (link.getAttribute('href') === currentPath) {
        link.classList.add('active');
    }
});

// Quick Action Buttons
document.addEventListener('DOMContentLoaded', function() {
    // Payout buttons
    const payoutButtons = document.querySelectorAll('.btn-payout');
    payoutButtons.forEach(button => {
        button.addEventListener('click', function() {
            if (confirm('Are you sure you want to release this payout?')) {
                this.textContent = 'Processing...';
                this.disabled = true;
                
                // Simulate API call
                setTimeout(() => {
                    this.textContent = 'Released';
                    this.style.background = '#28a745';
                }, 2000);
            }
        });
    });
    
    // Notification handling
    const notifications = document.querySelector('.notifications');
    if (notifications) {
        notifications.addEventListener('click', function() {
            alert('Notifications feature will be implemented later');
        });
    }
});

// Auto-refresh dashboard data every 5 minutes
setInterval(function() {
    if (window.location.pathname.includes('/admin') && 
        !window.location.pathname.includes('/admin/login')) {
        // This would be replaced with actual AJAX call to refresh data
        console.log('Refreshing dashboard data...');
    }
}, 300000); // 5 minutes