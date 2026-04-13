<?php require APPROOT.'/views/rentalowner/inc/header.php'; ?>

<div class="kal-rental-dashboard-shop-container">        
    <!-- Main Content -->
    <div class="kal-rental-dashboard-shop-main">
        <header class="kal-rental-dashboard-shop-header">
            <h2>Manage Your Rental Shops</h2>
            <a href="<?php echo URLROOT; ?>/rentalowner/addShop" class="kal-rental-dashboard-shop-btn kal-rental-dashboard-shop-btn-primary">Add New Shop</a>
        </header>
        
        <?php if (isset($data['limits'])): ?>
            <div class="package-limits-banner">
                <span class="limit-info">📦 Your Package: <strong><?php echo $data['limits']['current_shops']; ?> / <?php echo $data['limits']['shops_limit']; ?> shops used</strong></span>
                <?php if (!$data['limits']['can_add_more']): ?>
                    <span class="limit-warning">⚠️ Limit reached - Upgrade to add more shops</span>
                <?php endif; ?>
            </div>
        <?php endif; ?>
        
        <!-- Success/Error Message Area -->
        <?php if (!empty($data['success_message'])): ?>
            <div class="shop-message shop-message-success">
                <?php echo $data['success_message']; ?>
            </div>
        <?php endif; ?>
        <div id="shopMessage" class="shop-message" style="display: none;"></div>
        
        <div class="kal-rental-dashboard-shop-shops-grid">
            <?php if (!empty($data['shops'])): ?>
                <?php foreach ($data['shops'] as $shop): ?>
                    <!-- Shop Card -->
                    <div class="kal-rental-dashboard-shop-shop-card">
                        <div class="kal-rental-dashboard-shop-shop-image">
                            <?php 
                            // Determine correct image path
                            $localFallback = URLROOT . '/images/rental/pro-sports-gear.jpg';
                            if (!empty($shop->image) && $shop->image !== 'placeholder.jpg') {
                                // Check if it's already a full path
                                if (strpos($shop->image, 'uploads/') === 0) {
                                    $imagePath = URLROOT . '/' . $shop->image;
                                } elseif (strpos($shop->image, '/') === false) {
                                    // Bare filename - assume it's in rental_shops uploads
                                    $imagePath = URLROOT . '/uploads/rental_shops/' . $shop->image;
                                } else {
                                    $imagePath = URLROOT . '/' . ltrim($shop->image, '/');
                                }
                            } else {
                                $imagePath = $localFallback;
                            }
                            ?>
                            <img src="<?php echo $imagePath; ?>" 
                                 alt="<?php echo htmlspecialchars($shop->shop_name); ?>"
                                 onerror="this.src='<?php echo $localFallback; ?>'; this.onerror=null;">
                            <div class="kal-rental-dashboard-shop-shop-status status-<?php echo $shop->status; ?>">
                                <?php echo ucfirst($shop->status); ?>
                            </div>
                            <div class="kal-rental-dashboard-shop-shop-category">
                                <?php echo $shop->category; ?>
                            </div>
                        </div>
                        <div class="kal-rental-dashboard-shop-shop-info">
                            <h3><?php echo $shop->shop_name; ?></h3>
                            <p class="kal-rental-dashboard-shop-shop-address">📍 <?php echo $shop->address; ?></p>
                            <p class="kal-rental-dashboard-shop-shop-description"><?php echo $shop->description; ?></p>
                            
                            <!-- Equipment Types -->
                            <div class="kal-rental-dashboard-shop-equipment-types">
                                <?php foreach ($shop->equipment_types as $equipment): ?>
                                    <span class="kal-rental-dashboard-shop-equipment-tag">
                                        <?php 
                                        $icon = '';
                                        switch(strtolower($equipment)) {
                                            case 'cricket': $icon = '🏏'; break;
                                            case 'football': $icon = '⚽'; break;
                                            case 'tennis': $icon = '🎾'; break;
                                            case 'basketball': $icon = '🏀'; break;
                                            case 'badminton': $icon = '🏸'; break;
                                            default: $icon = '🎯'; break;
                                        }
                                        echo $icon . ' ' . $equipment;
                                        ?>
                                    </span>
                                <?php endforeach; ?>
                            </div>
                            
                            <div class="kal-rental-dashboard-shop-shop-stats">
                                <div class="kal-rental-dashboard-shop-stat-item">
                                    <span class="kal-rental-dashboard-shop-stat-icon">🛠️</span>
                                    <span class="kal-rental-dashboard-shop-stat-value"><?php echo $shop->equipment_count; ?> items</span>
                                </div>
                                <div class="kal-rental-dashboard-shop-stat-item">
                                    <span class="kal-rental-dashboard-shop-stat-icon">💰</span>
                                    <span class="kal-rental-dashboard-shop-stat-value">LKR <?php echo number_format($shop->daily_rate, 2); ?>/day</span>
                                </div>
                                <div class="kal-rental-dashboard-shop-stat-item">
                                    <span class="kal-rental-dashboard-shop-stat-icon">📅</span>
                                    <span class="kal-rental-dashboard-shop-stat-value"><?php echo $shop->rentals_count; ?> rentals</span>
                                </div>
                            </div>
                            
                            <!-- Features -->
                            <div class="kal-rental-dashboard-shop-shop-features">
                                <?php foreach ($shop->features as $feature): ?>
                                    <span class="kal-rental-dashboard-shop-feature-tag">✓ <?php echo $feature; ?></span>
                                <?php endforeach; ?>
                            </div>
                            
                            <div class="kal-rental-dashboard-shop-shop-contact">
                                <div class="kal-rental-dashboard-shop-contact-item">
                                    <span class="kal-rental-dashboard-shop-contact-icon">📞</span>
                                    <span class="kal-rental-dashboard-shop-contact-value"><?php echo $shop->contact_phone; ?></span>
                                </div>
                                <div class="kal-rental-dashboard-shop-contact-item">
                                    <span class="kal-rental-dashboard-shop-contact-icon">📧</span>
                                    <span class="kal-rental-dashboard-shop-contact-value"><?php echo $shop->contact_email; ?></span>
                                </div>
                                <div class="kal-rental-dashboard-shop-contact-item">
                                    <span class="kal-rental-dashboard-shop-contact-icon">🕒</span>
                                    <span class="kal-rental-dashboard-shop-contact-value"><?php echo $shop->operating_hours; ?></span>
                                </div>
                            </div>
                        </div>
                        <div class="kal-rental-dashboard-shop-shop-actions">
                            <a href="<?php echo URLROOT; ?>/rentalowner/editShop/<?php echo $shop->id; ?>" class="kal-rental-dashboard-shop-btn kal-rental-dashboard-shop-btn-edit">Edit Details</a>
                            <button class="kal-rental-dashboard-shop-btn kal-rental-dashboard-shop-btn-delete">Delete Shop</button>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="kal-rental-dashboard-shop-no-shops">
                    <div class="kal-rental-dashboard-shop-no-shops-icon">🏢</div>
                    <h3>No Shops Added Yet</h3>
                    <p>Start by adding your first rental shop to manage your sports equipment rental business.</p>
                    <a href="<?php echo URLROOT; ?>/rentalowner/addShop" class="kal-rental-dashboard-shop-btn kal-rental-dashboard-shop-btn-primary">Add Your First Shop</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
// Simple JavaScript for delete confirmation
document.addEventListener('DOMContentLoaded', function() {
    const deleteButtons = document.querySelectorAll('.kal-rental-dashboard-shop-btn-delete');
    const shopMessage = document.getElementById('shopMessage');
    
    function showMessage(message, type = 'success') {
        shopMessage.textContent = message;
        shopMessage.className = `shop-message shop-message-${type}`;
        shopMessage.style.display = 'block';
        
        // Auto hide after 5 seconds
        setTimeout(() => {
            shopMessage.style.display = 'none';
        }, 5000);
    }
    
    deleteButtons.forEach(button => {
        button.addEventListener('click', function() {
            const shopCard = this.closest('.kal-rental-dashboard-shop-shop-card');
            const shopName = shopCard.querySelector('h3').textContent;
            
            // Get shop ID from edit button URL
            const editButton = shopCard.querySelector('.kal-rental-dashboard-shop-btn-edit');
            const editUrl = editButton.getAttribute('href');
            const shopId = editUrl.split('/').pop();
            
            if (confirm(`Are you sure you want to delete "${shopName}"? This action cannot be undone.`)) {
                // Disable button to prevent double-clicks
                this.disabled = true;
                this.textContent = 'Deleting...';
                
                // Make AJAX call to delete the shop
                fetch('<?php echo URLROOT; ?>/rentalowner/deleteShop/' + shopId, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showMessage(`Shop "${shopName}" has been deleted successfully.`, 'success');
                        // Remove the card from the DOM with animation
                        shopCard.style.opacity = '0';
                        shopCard.style.transform = 'scale(0.9)';
                        setTimeout(() => {
                            shopCard.remove();
                            
                            // Check if there are no more shops
                            const remainingShops = document.querySelectorAll('.kal-rental-dashboard-shop-shop-card');
                            if (remainingShops.length === 0) {
                                location.reload(); // Reload to show "no shops" message
                            }
                        }, 300);
                    } else {
                        showMessage(`Failed to delete shop: ${data.message}`, 'error');
                        this.disabled = false;
                        this.textContent = 'Delete Shop';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showMessage('An error occurred while deleting the shop.', 'error');
                    this.disabled = false;
                    this.textContent = 'Delete Shop';
                });
            }
        });
    });
    
    // Check if there's a URL parameter for success message
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('success') === '1') {
        showMessage('Shop operation completed successfully!', 'success');
    }
});
</script>

<style>
/* Additional CSS for rental shop specific elements */
.kal-rental-dashboard-shop-shop-category {
    position: absolute;
    top: 10px;
    left: 10px;
    background: rgba(52, 152, 219, 0.9);
    color: white;
    padding: 4px 8px;
    border-radius: 12px;
    font-size: 11px;
    font-weight: 600;
    text-transform: uppercase;
    z-index: 2;
}

.kal-rental-dashboard-shop-shop-status {
    position: absolute;
    top: 10px;
    right: 10px;
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 600;
    text-transform: uppercase;
    z-index: 2;
}

.status-pending {
    background: rgba(255, 152, 0, 0.2);
    color: #ff9800;
    border: 1px solid #ff9800;
}

.status-pending::before {
    content: '⏳ ';
}

.status-approved {
    background: rgba(3, 178, 0, 0.2);
    color: #03B200;
    border: 1px solid #03B200;
}

.status-approved::before {
    content: '✅ ';
}

.status-rejected {
    background: rgba(244, 67, 54, 0.2);
    color: #f44336;
    border: 1px solid #f44336;
}

.status-rejected::before {
    content: '❌ ';
}

.kal-rental-dashboard-shop-equipment-types {
    display: flex;
    flex-wrap: wrap;
    gap: 5px;
    margin-bottom: 15px;
}

.kal-rental-dashboard-shop-equipment-tag {
    background: rgba(255, 193, 7, 0.1);
    color: #856404;
    padding: 4px 8px;
    border-radius: 12px;
    font-size: 11px;
    font-weight: 500;
    border: 1px solid rgba(255, 193, 7, 0.2);
}

.kal-rental-dashboard-shop-shop-features {
    display: flex;
    flex-wrap: wrap;
    gap: 5px;
    margin-bottom: 15px;
}

.kal-rental-dashboard-shop-feature-tag {
    background: rgba(40, 167, 69, 0.1);
    color: #155724;
    padding: 4px 8px;
    border-radius: 12px;
    font-size: 11px;
    font-weight: 500;
    border: 1px solid rgba(40, 167, 69, 0.2);
}

.kal-rental-dashboard-shop-shop-card {
    transition: opacity 0.3s ease, transform 0.3s ease;
}

/* Message Styles */
.shop-message {
    padding: 12px 16px;
    margin-bottom: 20px;
    border-radius: 6px;
    font-weight: 500;
}

.shop-message-success {
    background-color: #d4edda;
    color: #155724;
}

.package-limits-banner {
    background: #0a0a0a;
    border: 1px solid #03B200;
    border-radius: 8px;
    padding: 15px 20px;
    margin-bottom: 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.limit-info {
    color: #ccc;
    font-size: 14px;
}

.limit-info strong {
    color: #03B200;
}

.limit-warning {
    color: #ffa500;
    font-size: 13px;
    font-weight: 600;
    border: 1px solid #c3e6cb;
}

.shop-message-error {
    background-color: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
}
</style>

<?php require APPROOT.'/views/rentalowner/inc/footer.php'; ?>