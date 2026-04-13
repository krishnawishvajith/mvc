// Rental Packages JavaScript

document.addEventListener('DOMContentLoaded', function() {
    // Smooth scroll to packages section
    const heroFeatures = document.querySelectorAll('.hero-feature');
    heroFeatures.forEach(feature => {
        feature.addEventListener('click', function() {
            document.querySelector('.packages-section').scrollIntoView({ 
                behavior: 'smooth',
                block: 'start'
            });
        });
    });

    // Package selection buttons
    const selectButtons = document.querySelectorAll('.btn-select-package');
    selectButtons.forEach(button => {
        button.addEventListener('click', function() {
            const packageCard = this.closest('.package-card');
            const packageName = packageCard.querySelector('.package-name').textContent;
            
            // Show confirmation dialog
            const confirmed = confirm(`You selected the ${packageName}. Would you like to proceed with registration?`);
            
            if (confirmed) {
                // Redirect to registration page with package parameter
                const packageType = packageCard.classList.contains('basic') ? 'basic' : 
                                   packageCard.classList.contains('standard') ? 'standard' : 'premium';
                window.location.href = `${window.location.origin}/bookmygroundlk/register/rental_owner?package=${packageType}`;
            }
        });
    });

    // Animate cards on scroll
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver(function(entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, observerOptions);

    // Observe all cards
    const cards = document.querySelectorAll('.package-card, .step-card, .faq-item');
    cards.forEach(card => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        card.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
        observer.observe(card);
    });

    // FAQ accordion functionality (optional)
    const faqItems = document.querySelectorAll('.faq-item');
    faqItems.forEach(item => {
        item.addEventListener('click', function() {
            this.classList.toggle('active');
            
            // Optional: Add active styling
            if (this.classList.contains('active')) {
                this.style.borderLeft = '4px solid #667eea';
            } else {
                this.style.borderLeft = 'none';
            }
        });
    });

    // Comparison table scroll hint for mobile
    const tableWrapper = document.querySelector('.comparison-table-wrapper');
    if (tableWrapper && window.innerWidth < 768) {
        const scrollHint = document.createElement('div');
        scrollHint.className = 'scroll-hint';
        scrollHint.innerHTML = '← Scroll to see more →';
        scrollHint.style.cssText = `
            text-align: center;
            padding: 10px;
            background: #667eea;
            color: white;
            font-size: 0.9rem;
            border-radius: 5px;
            margin-bottom: 10px;
        `;
        tableWrapper.parentNode.insertBefore(scrollHint, tableWrapper);
        
        // Hide hint after first scroll
        tableWrapper.addEventListener('scroll', function() {
            scrollHint.style.display = 'none';
        }, { once: true });
    }

    // Package price calculator (optional feature)
    function calculateRevenue(commission, monthlyBookings = 10, avgBookingAmount = 5000) {
        const totalRevenue = monthlyBookings * avgBookingAmount;
        const commissionAmount = totalRevenue * (commission / 100);
        const netRevenue = totalRevenue - commissionAmount;
        
        return {
            total: totalRevenue,
            commission: commissionAmount,
            net: netRevenue
        };
    }

    // Add revenue calculator tooltip to commission badges
    const commissionBadges = document.querySelectorAll('.commission-badge');
    commissionBadges.forEach(badge => {
        badge.style.cursor = 'help';
        badge.title = 'Click to see revenue example';
        
        badge.addEventListener('click', function() {
            const commissionRate = parseInt(this.textContent);
            const revenue = calculateRevenue(commissionRate);
            
            const message = `📊 Revenue Example (10 bookings/month at Rs. 5,000 each):\n\n` +
                          `Total Revenue: Rs. ${revenue.total.toLocaleString()}\n` +
                          `Commission (${commissionRate}%): Rs. ${revenue.commission.toLocaleString()}\n` +
                          `Your Net Revenue: Rs. ${revenue.net.toLocaleString()}`;
            
            alert(message);
        });
    });

    // Highlight popular package on page load
    const popularCard = document.querySelector('.package-card.popular');
    if (popularCard) {
        setTimeout(() => {
            popularCard.style.animation = 'pulse 1s ease-in-out';
        }, 500);
    }

    // Add CSS for pulse animation
    const style = document.createElement('style');
    style.textContent = `
        @keyframes pulse {
            0%, 100% { transform: scale(1.05); }
            50% { transform: scale(1.08); }
        }
    `;
    document.head.appendChild(style);

   

    // Track package views (for analytics)
    const trackPackageView = (packageName) => {
        console.log(`Package viewed: ${packageName}`);
        // In production, send to analytics service
        // Example: ga('send', 'event', 'Package', 'View', packageName);
    };

    // Track when packages come into view
    const packageCards = document.querySelectorAll('.package-card');
    const packageObserver = new IntersectionObserver(function(entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const packageName = entry.target.querySelector('.package-name').textContent;
                trackPackageView(packageName);
                packageObserver.unobserve(entry.target); // Track only once
            }
        });
    }, { threshold: 0.5 });

    packageCards.forEach(card => packageObserver.observe(card));

    // Add keyboard navigation for accessibility
    document.addEventListener('keydown', function(e) {
        // Press 1, 2, or 3 to select packages
        if (e.key === '1' || e.key === '2' || e.key === '3') {
            const index = parseInt(e.key) - 1;
            const buttons = document.querySelectorAll('.btn-select-package');
            if (buttons[index]) {
                buttons[index].click();
            }
        }
    });

    // Show keyboard shortcuts hint
    const shortcutHint = document.createElement('div');
    shortcutHint.innerHTML = '💡 Tip: Press 1, 2, or 3 to quickly select a package';
    shortcutHint.style.cssText = `
        position: fixed;
        bottom: 80px;
        right: 20px;
        padding: 10px 15px;
        background: rgba(0, 0, 0, 0.8);
        color: white;
        border-radius: 8px;
        font-size: 0.85rem;
        z-index: 999;
        opacity: 0;
        transition: opacity 0.3s ease;
        pointer-events: none;
    `;
    document.body.appendChild(shortcutHint);

    // Show hint after 3 seconds
    setTimeout(() => {
        shortcutHint.style.opacity = '1';
        setTimeout(() => {
            shortcutHint.style.opacity = '0';
        }, 5000);
    }, 3000);
});

// Export for potential module usage
if (typeof module !== 'undefined' && module.exports) {
    module.exports = {
        // Any functions you want to export
    };
}