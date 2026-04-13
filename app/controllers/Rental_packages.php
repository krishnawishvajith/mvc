<?php
class Rental_packages extends Controller {
    private $rentalPackagesModel;
    private $adminModel;

    public function __construct()
    {
        $this->rentalPackagesModel = $this->model('M_Rental_packages');
        $this->adminModel = $this->model('M_Admin');
    }

    public function index() {
        // Show rental packages page
        $data = [
            'title' => 'Rental Service Packages - BookMyGround',
            'packages' => $this->rentalPackagesModel->getPackages()
        ];

        $this->view('rental_packages/v_packages', $data);
    }

    public function checkout($packageId = null) {
        // Get package details
        $packages = $this->rentalPackagesModel->getPackages();
        $package = null;

        if (!$packageId && count($packages) > 0) {
            $package = reset($packages);
        }

        if (isset($packages[$packageId])) {
            $package = $packages[$packageId];
        } else {
            $decodedId = urldecode($packageId);
            if (isset($packages[$decodedId])) {
                $package = $packages[$decodedId];
            }
        }

        if (!$package && ctype_digit($packageId)) {
            foreach ($packages as $pkg) {
                if ((string)$pkg['id'] === $packageId) {
                    $package = $pkg;
                    break;
                }
            }
        }

        if (!$package) {
            header('Location: ' . URLROOT . '/rental_packages');
            exit;
        }

        // Check if user is logged in
        $isLoggedIn = isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'] === true;
        
        $data = [
            'title' => 'Checkout - ' . $package['name'],
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
            $data = $this->processCheckout($data, $packageId);
        }

        $this->view('rental_packages/v_checkout', $data);
    }

    private function processCheckout($data, $packageId) {
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

        $package = $data['package'];
        $amount = (int) ($package['price'] * 100); // Convert to cents (LKR 12300 = 1230000 cents)
        $currency = defined('STRIPE_CURRENCY') ? STRIPE_CURRENCY : 'lkr';

        // Create Stripe charge
        $stripe = new Stripe();
        $charge = $stripe->createCharge(
            $amount,
            $currency,
            $stripeToken,
            [
                'package_id' => $packageId,
                'user_id' => $_SESSION['user_id'],
                'user_email' => $_SESSION['user_email'],
                'package_name' => $package['name']
            ]
        );

        if (!$charge) {
            $errorMessage = $stripe->getLastError();
            $data['error'] = 'Payment failed: ' . $errorMessage;
            
            // Save failed payment attempt to database
            $dbPackage = $this->rentalPackagesModel->getPackageById($package['id'] ?? 1);
            $actualPackageId = $dbPackage ? $dbPackage->id : 1;
            
            $this->adminModel->createRentalPackagePurchase([
                'user_id' => $_SESSION['user_id'],
                'package_id' => $actualPackageId,
                'payment_amount' => $package['price'],
                'payment_method' => 'stripe',
                'stripe_charge_id' => null,
                'package_status' => 'failed'
            ]);
            
            return $data;
        }

        // Payment successful - Store purchase in database
        $userId = $_SESSION['user_id'];
        $currentRole = $_SESSION['user_role'];

        // Get actual package ID from database if available
        $dbPackage = $this->rentalPackagesModel->getPackageById($package['id'] ?? 1);
        $actualPackageId = $dbPackage ? $dbPackage->id : 1;

        // Save purchase record with automatic activation
        $purchaseId = $this->adminModel->createRentalPackagePurchase([
            'user_id' => $userId,
            'package_id' => $actualPackageId,
            'payment_amount' => $package['price'],
            'payment_method' => 'stripe',
            'stripe_charge_id' => $charge['id'],
            'package_status' => 'active' // Automatically active after payment
        ]);

        if ($purchaseId) {
            $data['success'] = 'Payment successful! Your package has been activated. Redirecting to your dashboard...';
            $data['charge_id'] = $charge['id'];
            
            // TODO: Send confirmation email
            
            // Redirect to rental owner dashboard
            header('refresh:2;url=' . URLROOT . '/rentalowner');
        } else {
            $data['error'] = 'Payment processed but failed to save purchase record. Please contact support with charge ID: ' . $charge['id'];
        }

        return $data;
    }
}
?>