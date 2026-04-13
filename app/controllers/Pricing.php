<?php
class Pricing extends Controller {
    private $pricingModel;
    private $adminModel;
    private $loginModel;

    public function __construct()
    {
        $this->pricingModel = $this->model('M_Pricing');
        $this->adminModel = $this->model('M_Admin');
        $this->loginModel = $this->model('M_Login');
    }

    public function index() {
        // Get pricing packages
        $packages = $this->pricingModel->getPricingPackages();

        $data = [
            'title' => 'Pricing Plans',
            'packages' => $packages
        ];

        $this->view('pricing/v_pricing', $data);
    }

    public function compare() {
        // Detailed comparison page
        $packages = $this->pricingModel->getPricingPackages();
        $features = $this->pricingModel->getFeatureComparison();

        $data = [
            'title' => 'Compare Plans',
            'packages' => $packages,
            'features' => $features
        ];

        $this->view('pricing/v_compare', $data);
    }

    public function checkout($packageName = null) {
        if (!$packageName) {
            header('Location: ' . URLROOT . '/pricing');
            exit;
        }

        // Get package details
        $package = $this->pricingModel->getPackageByName($packageName);
        
        if (!$package) {
            header('Location: ' . URLROOT . '/pricing');
            exit;
        }

        // Check if user is logged in
        $isLoggedIn = isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'] === true;
        
        $data = [
            'title' => 'Checkout - ' . $package->name . ' Plan',
            'package' => $package,
            'is_logged_in' => $isLoggedIn,
            'user_name' => $isLoggedIn ? ($_SESSION['user_name'] ?? '') : '',
            'user_email' => $isLoggedIn ? ($_SESSION['user_email'] ?? '') : '',
            'user_role' => $isLoggedIn ? ($_SESSION['user_role'] ?? '') : '',
            'error' => '',
            'success' => ''
        ];

        // Handle POST (purchase/registration)
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = $this->processCheckout($data, $package);
        }

        $this->view('pricing/v_checkout', $data);
    }

    private function processCheckout($data, $package) {
        if (!isset($_SESSION['user_logged_in']) || $_SESSION['user_logged_in'] !== true) {
            $data['error'] = 'Please log in to complete your purchase.';
            return $data;
        }

        $stripeToken = trim($_POST['stripeToken'] ?? '');
        
        if (empty($stripeToken)) {
            $data['error'] = 'Payment information is required.';
            return $data;
        }

        // Check if Stripe is configured
        if (!Stripe::isConfigured()) {
            $data['error'] = 'Payment system is not configured. Please contact support.';
            return $data;
        }

        $amount = (int) ($package->setup_fee * 100); // Convert to cents
        $currency = defined('STRIPE_CURRENCY') ? STRIPE_CURRENCY : 'lkr';

        // Create Stripe charge
        $stripe = new Stripe();
        $charge = $stripe->createCharge(
            $amount,
            $currency,
            $stripeToken,
            [
                'package_id' => $package->id,
                'package_name' => $package->name,
                'user_id' => $_SESSION['user_id'],
                'user_email' => $_SESSION['user_email'],
                'package_type' => 'stadium_owner'
            ]
        );

        if (!$charge) {
            $errorMessage = $stripe->getLastError();
            $data['error'] = 'Payment failed: ' . $errorMessage;
            
            // Save failed payment attempt to database
            $this->adminModel->createPackagePurchase([
                'user_id' => $_SESSION['user_id'],
                'package_id' => $package->id,
                'payment_amount' => $package->setup_fee,
                'payment_method' => 'stripe',
                'stripe_charge_id' => null,
                'package_status' => 'failed'
            ]);
            
            return $data;
        }

        // Payment successful - Store purchase in database
        $userId = $_SESSION['user_id'];
        $currentRole = $_SESSION['user_role'];

        // Save purchase record with automatic activation
        $purchaseId = $this->adminModel->createPackagePurchase([
            'user_id' => $userId,
            'package_id' => $package->id,
            'payment_amount' => $package->setup_fee,
            'payment_method' => 'stripe',
            'stripe_charge_id' => $charge['id'],
            'package_status' => 'active' // Automatically active after payment
        ]);

        if ($purchaseId) {
            // Update user role to stadium_owner in database
            $roleUpdated = $this->loginModel->updateUserRole($userId, 'stadium_owner');
            
            if ($roleUpdated) {
                // Update session role
                $_SESSION['user_role'] = 'stadium_owner';
                error_log('Pricing: User role updated to stadium_owner for user ID: ' . $userId);
            } else {
                error_log('Pricing: Failed to update user role for user ID: ' . $userId);
            }
            
            $data['success'] = 'Payment successful! Your ' . $package->name . ' plan has been activated. Redirecting to dashboard...';
            $data['charge_id'] = $charge['id'];
            
            // TODO: Send confirmation email
            
            header('refresh:2;url=' . URLROOT . '/stadium_owner');
        } else {
            $data['error'] = 'Payment processed but failed to save purchase record. Please contact support with charge ID: ' . $charge['id'];
        }

        return $data;
    }
}
?>