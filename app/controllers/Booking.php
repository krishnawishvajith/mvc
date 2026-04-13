<?php
class Booking extends Controller
{
    private $stadiumModel;
    private $stadiumOwnerModel;

    public function __construct()
    {
        try {
            $this->stadiumModel = $this->model('M_Stadiums');
            $this->stadiumOwnerModel = $this->model('M_Stadium_owner');

            if (session_status() == PHP_SESSION_NONE) {
                session_start();
            }
        } catch (Exception $e) {
            error_log('Booking Controller Constructor Error: ' . $e->getMessage());
        }
    }

    public function create_booking()
    {
        try {
            // Check if user is logged in
            if (!Auth::isLoggedIn()) {
                header('Location: ' . URLROOT . '/login?redirect=' . urlencode($_SERVER['REQUEST_URI']));
                exit;
            }

            if ($_SERVER['REQUEST_METHOD'] != 'POST') {
                echo json_encode(['success' => false, 'message' => 'Invalid request method']);
                exit;
            }

            $customer_id = Auth::getUserId();
            $stadium_id = $_POST['stadium_id'] ?? 0;
            $booking_date = $_POST['booking_date'] ?? '';
            $start_date = $_POST['start_date'] ?? '';
            $end_date = $_POST['end_date'] ?? '';
            $start_time = $_POST['start_time'] ?? '';
            $end_time = $_POST['end_time'] ?? '';
            $duration_hours = $_POST['duration_hours'] ?? 0;
            $stadium_price = $_POST['stadium_price'] ?? 0;
            $notes = $_POST['notes'] ?? '';

            // Validate inputs
            if (!$stadium_id || !$booking_date || !$start_time || !$end_time || !$duration_hours || !$stadium_price) {
                echo json_encode(['success' => false, 'message' => 'Missing required booking information']);
                exit;
            }

            // Get stadium owner
            $stadium = $this->stadiumModel->getStadiumById($stadium_id);
            if (!$stadium) {
                echo json_encode(['success' => false, 'message' => 'Stadium not found']);
                exit;
            }

            // Check availability
            $available = $this->stadiumOwnerModel->checkAvailability($stadium_id, $start_date, $start_time, $end_time);
            if (!$available) {
                echo json_encode(['success' => false, 'message' => 'This time slot is not available. Please choose another time.']);
                exit;
            }

            // Calculate total price
            $total_price = floatval($stadium_price) * floatval($duration_hours);

            // Create booking
            $bookingData = [
                'stadium_id' => $stadium_id,
                'customer_id' => $customer_id,
                'owner_id' => $stadium->owner_id,
                'booking_date' => $booking_date,
                'start_date' => $start_date,
                'end_date' => $end_date,
                'start_time' => $start_time,
                'end_time' => $end_time,
                'duration_hours' => $duration_hours,
                'total_price' => $total_price,
                'status' => 'pending', // Pending payment
                'payment_status' => 'pending',
                'customer_notes' => $notes
            ];

            $result = $this->stadiumOwnerModel->createBooking($bookingData);

            if ($result['success']) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Booking created successfully!',
                    'booking_id' => $result['booking_id'],
                    'redirect' => URLROOT . '/booking/confirm/' . $result['booking_id']
                ]);
            } else {
                echo json_encode(['success' => false, 'message' => $result['message']]);
            }
            exit;
        } catch (Exception $e) {
            error_log('Booking Create Error: ' . $e->getMessage());
            echo json_encode(['success' => false, 'message' => 'Error creating booking']);
            exit;
        }
    }

    public function confirm($booking_id = null)
    {
        try {
            if (!Auth::isLoggedIn()) {
                header('Location: ' . URLROOT . '/login');
                exit;
            }

            if (!$booking_id) {
                header('Location: ' . URLROOT . '/stadiums');
                exit;
            }

            $booking = $this->stadiumOwnerModel->getBookingDetails($booking_id);

            if (!$booking || $booking->customer_id != Auth::getUserId()) {
                header('Location: ' . URLROOT . '/stadiums');
                exit;
            }

            $data = [
                'title' => 'Confirm Booking',
                'booking' => $booking
            ];

            $this->view('booking/v_confirm_booking', $data);
        } catch (Exception $e) {
            error_log('Booking Confirm Error: ' . $e->getMessage());
            die('Error confirming booking');
        }
    }

    public function process_payment()
    {
        try {
            if (!Auth::isLoggedIn()) {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => 'Not logged in']);
                exit;
            }

            if ($_SERVER['REQUEST_METHOD'] != 'POST') {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => 'Invalid request method']);
                exit;
            }

            header('Content-Type: application/json');

            $booking_id = $_POST['booking_id'] ?? 0;

            $booking = $this->stadiumOwnerModel->getBookingDetails($booking_id);

            if (!$booking || $booking->customer_id != Auth::getUserId()) {
                echo json_encode(['success' => false, 'message' => 'Booking not found']);
                exit;
            }

            // Process Stripe payment
            $this->processStripePayment($booking_id);
            exit;
        } catch (Exception $e) {
            error_log('Booking Payment Error: ' . $e->getMessage());
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Error processing payment']);
            exit;
        }
    }

    private function processStripePayment($booking_id)
    {
        try {
            $stripe_payment_method_id = $_POST['stripe_payment_method_id'] ?? '';
            $booking = $this->stadiumOwnerModel->getBookingDetails($booking_id);

            if (!$booking) {
                echo json_encode(['success' => false, 'message' => 'Booking not found']);
                return;
            }

            if (!$stripe_payment_method_id) {
                echo json_encode(['success' => false, 'message' => 'Invalid payment method']);
                return;
            }

            // Calculate amount in cents (Stripe uses cents for currency)
            $amount = intval($booking->total_price * 1.02 * 100); // Amount with 2% service fee in cents

            // Create return URL for after payment
            $returnUrl = URLROOT . '/customer/bookings';

            // Create payment intent using Stripe API
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'https://api.stripe.com/v1/payment_intents');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_USERPWD, STRIPE_SECRET_KEY . ':');
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
                'amount' => $amount,
                'currency' => strtolower(STRIPE_CURRENCY),
                'payment_method' => $stripe_payment_method_id,
                'confirm' => 'true',
                'return_url' => $returnUrl,
                'automatic_payment_methods[enabled]' => 'true',
                'automatic_payment_methods[allow_redirects]' => 'always',
                'metadata' => [
                    'booking_id' => $booking_id,
                    'stadium_id' => $booking->stadium_id,
                    'customer_id' => $booking->customer_id
                ]
            ]));

            $response = curl_exec($ch);
            $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            $result = json_decode($response, true);
            error_log('Stripe Response: ' . json_encode($result));

            // Check if payment was successful
            if ($http_code == 200 && isset($result['status'])) {
                if ($result['status'] === 'succeeded' || $result['status'] === 'processing') {
                    // Update booking status to confirmed
                    $updateResult = $this->stadiumOwnerModel->updateBookingStatus($booking_id, 'confirmed', 'paid');

                    if ($updateResult['success']) {
                        // Send confirmation email to customer
                        $this->sendBookingConfirmationEmail($booking_id);

                        echo json_encode([
                            'success' => true,
                            'message' => 'Payment successful! Your booking is confirmed.',
                            'booking_id' => $booking_id,
                            'redirect' => URLROOT . '/booking/success/' . $booking_id
                        ]);
                    } else {
                        echo json_encode(['success' => false, 'message' => 'Payment succeeded but booking confirmation failed']);
                    }
                } else {
                    echo json_encode(['success' => false, 'message' => 'Payment was not completed. Status: ' . $result['status']]);
                }
            } else {
                $errorMsg = $result['error']['message'] ?? 'Payment failed. Please try again.';
                error_log('Stripe Error: ' . $errorMsg);
                echo json_encode(['success' => false, 'message' => $errorMsg]);
            }
        } catch (Exception $e) {
            error_log('Stripe Payment Error: ' . $e->getMessage());
            echo json_encode(['success' => false, 'message' => 'Error processing Stripe payment']);
        }
    }

    public function success($booking_id = null)
    {
        try {
            if (!Auth::isLoggedIn()) {
                header('Location: ' . URLROOT . '/login');
                exit;
            }

            if (!$booking_id) {
                header('Location: ' . URLROOT . '/stadiums');
                exit;
            }

            $booking = $this->stadiumOwnerModel->getBookingDetails($booking_id);

            if (!$booking || $booking->customer_id != Auth::getUserId()) {
                header('Location: ' . URLROOT . '/stadiums');
                exit;
            }

            $data = [
                'title' => 'Booking Confirmed',
                'booking' => $booking
            ];

            $this->view('booking/v_booking_success', $data);
        } catch (Exception $e) {
            error_log('Booking Success Error: ' . $e->getMessage());
            die('Error loading booking confirmation');
        }
    }

    public function my_bookings()
    {
        try {
            if (!Auth::isLoggedIn()) {
                header('Location: ' . URLROOT . '/login');
                exit;
            }

            $customer_id = Auth::getUserId();
            $db = new Database();

            // Get customer's bookings
            $db->query("SELECT b.*, 
                s.name as stadium_name, s.location, s.image,
                u.first_name as owner_first_name, u.last_name as owner_last_name
                FROM bookings b
                JOIN stadiums s ON b.stadium_id = s.id
                JOIN users u ON b.owner_id = u.id
                WHERE b.customer_id = :customer_id
                ORDER BY b.booking_date DESC");

            $db->bind(':customer_id', $customer_id);
            $bookings = $db->resultSet();

            $data = [
                'title' => 'My Bookings',
                'bookings' => $bookings,
                'user_name' => Auth::getUserName() ?: 'Customer'
            ];

            $this->view('booking/v_my_bookings', $data);
        } catch (Exception $e) {
            error_log('My Bookings Error: ' . $e->getMessage());
            die('Error loading bookings');
        }
    }

    public function cancel_booking($booking_id = null)
    {
        try {
            if (!Auth::isLoggedIn()) {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => 'Not logged in']);
                exit;
            }

            if (!$booking_id || $_SERVER['REQUEST_METHOD'] != 'POST') {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => 'Invalid request']);
                exit;
            }

            $booking = $this->stadiumOwnerModel->getBookingDetails($booking_id);

            if (!$booking || $booking->customer_id != Auth::getUserId()) {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => 'Booking not found']);
                exit;
            }

            // Check if booking is already cancelled
            if ($booking->status === 'cancelled') {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => 'Booking is already cancelled']);
                exit;
            }

            // Check if booking is completed
            if ($booking->status === 'completed') {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => 'Cannot cancel completed bookings']);
                exit;
            }

            // Calculate if cancellation is allowed (6 hours before start time)
            $bookingDateTime = new DateTime($booking->booking_date . ' ' . $booking->start_time);
            $currentDateTime = new DateTime();
            $interval = $currentDateTime->diff($bookingDateTime);

            // If booking is in the past or within 6 hours
            if ($bookingDateTime <= $currentDateTime) {
                header('Content-Type: application/json');
                echo json_encode([
                    'success' => false,
                    'message' => 'Booking time has already passed',
                    'can_cancel' => false
                ]);
                exit;
            }

            // Calculate hours remaining
            $hoursRemaining = ($interval->days * 24) + $interval->h + ($interval->i / 60);

            if ($hoursRemaining < 6) {
                header('Content-Type: application/json');
                echo json_encode([
                    'success' => false,
                    'message' => 'Cancellations are only allowed 6 hours before the booking time. ' . round($hoursRemaining, 1) . ' hours remaining.',
                    'can_cancel' => false,
                    'hours_remaining' => round($hoursRemaining, 1),
                    'required_hours' => 6
                ]);
                exit;
            }

            // Allow cancellation
            $reason = $_POST['reason'] ?? 'Customer requested cancellation';
            $result = $this->stadiumOwnerModel->cancelBooking($booking_id, $reason);

            if ($result['success']) {
                error_log("Booking $booking_id cancelled successfully by customer " . Auth::getUserId());
            }

            header('Content-Type: application/json');
            echo json_encode($result);
            exit;
        } catch (Exception $e) {
            error_log('Cancel Booking Error: ' . $e->getMessage());
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Error cancelling booking']);
            exit;
        }
    }

    public function checkout($booking_id = null)
    {
        try {
            // Ensure session is started
            if (session_status() == PHP_SESSION_NONE) {
                session_start();
            }

            // Check if user is logged in
            if (!Auth::isLoggedIn()) {
                error_log("Booking checkout: User not logged in. Session: " . json_encode($_SESSION));
                header('Location: ' . URLROOT . '/login?redirect=' . urlencode($_SERVER['REQUEST_URI']));
                exit;
            }

            error_log("Booking checkout: User logged in as " . Auth::getUserId());

            // Get current user role
            $userRole = Auth::getUserRole();

            // Check if user is admin or stadium owner (not allowed to book)
            if ($userRole === 'admin' || $userRole === 'stadium_owner') {
                header('Location: ' . URLROOT . '/stadiums');
                exit;
            }

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                // Handle AJAX POST request for checkout form submission
                header('Content-Type: application/json');

                // Parse input data (support both JSON and form data)
                $input = [];

                // Check if Content-Type is JSON
                $contentType = $_SERVER['CONTENT_TYPE'] ?? '';
                if (strpos($contentType, 'application/json') !== false) {
                    $rawInput = file_get_contents('php://input');
                    $input = json_decode($rawInput, true) ?? [];
                    error_log('Booking JSON Input: ' . $rawInput);
                } else {
                    // Use POST data if form-encoded
                    $input = $_POST ?? [];
                    error_log('Booking POST Input: ' . print_r($input, true));
                }

                // Extract and validate data
                $customer_id = Auth::getUserId();
                $stadium_id = isset($input['stadium_id']) ? (int)$input['stadium_id'] : 0;
                $booking_date = isset($input['booking_date']) ? trim($input['booking_date']) : (isset($input['date']) ? trim($input['date']) : '');
                $start_time = isset($input['start_time']) ? trim($input['start_time']) : '';
                $duration_hours = isset($input['duration_hours']) ? (int)$input['duration_hours'] : 0;
                $stadium_price = isset($input['stadium_price']) ? (float)$input['stadium_price'] : 0;

                error_log("Booking Data - stadium_id: $stadium_id, date: $booking_date, time: $start_time, hours: $duration_hours, price: $stadium_price");

                // Validate each required field
                if (!$stadium_id) {
                    echo json_encode(['success' => false, 'message' => 'Stadium ID is required']);
                    exit;
                }
                if (!$booking_date) {
                    echo json_encode(['success' => false, 'message' => 'Booking date is required']);
                    exit;
                }
                if (!$start_time) {
                    echo json_encode(['success' => false, 'message' => 'Start time is required']);
                    exit;
                }
                if (!$duration_hours || $duration_hours <= 0) {
                    echo json_encode(['success' => false, 'message' => 'Duration must be greater than 0']);
                    exit;
                }
                if (!$stadium_price || $stadium_price <= 0) {
                    echo json_encode(['success' => false, 'message' => 'Stadium price is required']);
                    exit;
                }

                // Get stadium details
                $stadium = $this->stadiumModel->getStadiumById($stadium_id);
                if (!$stadium) {
                    echo json_encode(['success' => false, 'message' => 'Stadium not found']);
                    exit;
                }

                // Ensure user is not the stadium owner
                if ($stadium->owner_id == $customer_id) {
                    echo json_encode(['success' => false, 'message' => 'Stadium owners cannot book their own stadiums']);
                    exit;
                }

                // Calculate end time based on duration
                $startDateTime = new DateTime($booking_date . ' ' . $start_time);
                $endDateTime = clone $startDateTime;
                $endDateTime->add(new DateInterval('PT' . intval($duration_hours) . 'H'));

                $end_time = $endDateTime->format('H:i');
                $end_date = $endDateTime->format('Y-m-d');

                error_log("Booking times - booking_date: $booking_date, start_time: $start_time, end_time: $end_time, end_date: $end_date, duration: $duration_hours hours");

                // Check availability
                error_log("Checking availability for stadium $stadium_id");
                $available = $this->stadiumOwnerModel->checkAvailability($stadium_id, $booking_date, $start_time, $end_time);
                if (!$available) {
                    error_log("SLOT NOT AVAILABLE: stadium $stadium_id on $booking_date from $start_time to $end_time");
                    echo json_encode(['success' => false, 'message' => 'This time slot is not available. Please choose another time.']);
                    exit;
                }

                // Calculate total price
                $total_price = floatval($stadium_price) * floatval($duration_hours);
                error_log("Booking price - stadium_price: $stadium_price, duration_hours: $duration_hours, total_price: $total_price");

                // Create temporary booking with "reserved" status
                $bookingData = [
                    'stadium_id' => $stadium_id,
                    'customer_id' => $customer_id,
                    'owner_id' => $stadium->owner_id,
                    'booking_date' => $booking_date,
                    'start_date' => $booking_date,
                    'end_date' => $end_date,
                    'start_time' => $start_time,
                    'end_time' => $end_time,
                    'duration_hours' => $duration_hours,
                    'total_price' => $total_price,
                    'status' => 'reserved', // Temporary reservation
                    'payment_status' => 'pending',
                    'customer_notes' => ''
                ];

                error_log("Creating booking: " . json_encode($bookingData));
                $result = $this->stadiumOwnerModel->createBooking($bookingData);
                error_log("Booking result: " . json_encode($result));

                if ($result['success']) {
                    echo json_encode([
                        'success' => true,
                        'message' => 'Booking reservation created successfully!',
                        'booking_id' => $result['booking_id']
                    ]);
                } else {
                    $errorMsg = $result['message'] ?? 'Failed to create booking';
                    error_log("Booking failed: $errorMsg");
                    echo json_encode(['success' => false, 'message' => $errorMsg]);
                }
                exit;
            }

            // Handle GET request to display checkout page
            if (!$booking_id) {
                header('Location: ' . URLROOT . '/stadiums');
                exit;
            }

            $booking = $this->stadiumOwnerModel->getBookingDetails($booking_id);

            if (!$booking || $booking->customer_id != Auth::getUserId()) {
                header('Location: ' . URLROOT . '/stadiums');
                exit;
            }

            // Ensure booking is in reserved status
            if ($booking->status !== 'reserved') {
                header('Location: ' . URLROOT . '/booking/my_bookings');
                exit;
            }

            $data = [
                'title' => 'Checkout - Complete Your Booking',
                'booking' => $booking,
                'timer_duration' => 300 // 5 minutes in seconds
            ];

            $this->view('booking/v_checkout', $data);
        } catch (Exception $e) {
            error_log('Booking Checkout Error: ' . $e->getMessage());
            header('Location: ' . URLROOT . '/stadiums');
            exit;
        }
    }

    public function release_reservation()
    {
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                echo json_encode(['success' => false, 'message' => 'Invalid request method']);
                exit;
            }

            header('Content-Type: application/json');

            $booking_id = $_POST['booking_id'] ?? 0;

            if (!$booking_id) {
                echo json_encode(['success' => false, 'message' => 'Booking ID is required']);
                exit;
            }

            // Check if user is logged in
            if (!Auth::isLoggedIn()) {
                echo json_encode(['success' => false, 'message' => 'Not authorized']);
                exit;
            }

            $booking = $this->stadiumOwnerModel->getBookingDetails($booking_id);

            if (!$booking || $booking->customer_id != Auth::getUserId()) {
                echo json_encode(['success' => false, 'message' => 'Booking not found']);
                exit;
            }

            // Cancel the reserved booking
            $reason = 'Customer cancelled from checkout page (timer expired or manual cancellation)';
            $result = $this->stadiumOwnerModel->cancelBooking($booking_id, $reason);

            echo json_encode($result);
            exit;
        } catch (Exception $e) {
            error_log('Release Reservation Error: ' . $e->getMessage());
            echo json_encode(['success' => false, 'message' => 'Error releasing reservation']);
            exit;
        }
    }

    /**
     * Send booking confirmation email to customer
     * @param int $booking_id The booking ID
     */
    private function sendBookingConfirmationEmail($booking_id)
    {
        try {
            // Get booking details
            $booking = $this->stadiumOwnerModel->getBookingDetails($booking_id);

            if (!$booking) {
                error_log("Booking not found for email sending: $booking_id");
                return false;
            }

            // Prepare customer email data
            $customerEmail = $booking->email;
            $customerName = $booking->first_name . ' ' . $booking->last_name;

            // Format dates and times nicely
            $bookingDate = date('F j, Y', strtotime($booking->booking_date));
            $startTime = date('g:i A', strtotime($booking->start_time));
            $endTime = date('g:i A', strtotime($booking->end_time));

            // Calculate total with service fee
            $serviceFee = $booking->total_price * 0.02;
            $totalWithFee = $booking->total_price + $serviceFee;

            // Create email subject
            $subject = 'Your Stadium Booking Confirmation - BookMyGround';

            // Create plain text email body
            $emailBody = "Dear $customerName,

Your stadium booking has been confirmed successfully! Here are your booking details:

" . str_repeat("=", 60) . "
BOOKING CONFIRMATION
" . str_repeat("=", 60) . "

Booking Reference: #$booking_id
Stadium Name: $booking->stadium_name
Location: $booking->location
Booking Date: $bookingDate
Time: $startTime - $endTime
Duration: " . $booking->duration_hours . " hour(s)

" . str_repeat("-", 60) . "
PRICING DETAILS
" . str_repeat("-", 60) . "

Subtotal:    " . number_format($booking->total_price, 2) . " " . strtoupper(STRIPE_CURRENCY) . "
Service Fee (2%): " . number_format($serviceFee, 2) . " " . strtoupper(STRIPE_CURRENCY) . "
Total Amount: " . number_format($totalWithFee, 2) . " " . strtoupper(STRIPE_CURRENCY) . "

" . str_repeat("=", 60) . "
IMPORTANT INFORMATION
" . str_repeat("=", 60) . "

• Please arrive 15 minutes before your scheduled time
• Your booking reference is: #$booking_id
• You can view or manage your bookings in your account dashboard
• For any changes or cancellations, please contact us as soon as possible

If you have any questions or need to modify your booking, please contact us at " . MAIL_FROM . "

Thank you for choosing " . SITENAME . "!

" . str_repeat("=", 60) . "
This is an automated email. Please do not reply directly to this email.
© " . date('Y') . " " . SITENAME . ". All rights reserved.
" . str_repeat("=", 60);

            // Send email via SMTP if configured, otherwise use PHP mail
            if (Mail::isConfigured()) {
                $mail = new Mail();
                $fromName = SITENAME;
                $fromEmail = defined('SMTP_FROM_EMAIL') ? SMTP_FROM_EMAIL : MAIL_FROM;

                $result = $mail->send(
                    $customerEmail,
                    $subject,
                    $emailBody,
                    $fromEmail,
                    $fromName
                );

                if ($result) {
                    error_log("Booking confirmation email sent successfully to: $customerEmail for booking: $booking_id");
                    return true;
                } else {
                    error_log("Failed to send booking confirmation email to: $customerEmail. Error: " . $mail->getLastError());
                    return false;
                }
            } else {
                // Fallback to PHP mail
                $headers = "MIME-Version: 1.0" . "\r\n";
                $headers .= "Content-type: text/html; charset=UTF-8" . "\r\n";
                $headers .= "From: " . MAIL_FROM . "\r\n";

                if (mail($customerEmail, $subject, $emailBody, $headers)) {
                    error_log("Booking confirmation email sent (via PHP mail) to: $customerEmail for booking: $booking_id");
                    return true;
                } else {
                    error_log("Failed to send booking confirmation email (via PHP mail) to: $customerEmail");
                    return false;
                }
            }
        } catch (Exception $e) {
            error_log("Exception in sendBookingConfirmationEmail: " . $e->getMessage());
            return false;
        }
    }
}
