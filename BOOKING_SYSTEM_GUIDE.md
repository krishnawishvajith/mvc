# Stadium Booking System - Implementation Guide

## Overview
A complete booking system with automatic price calculation, 5-minute countdown timer for payment, and temporary slot reservations that auto-release when payment is not completed.

---

## Features Implemented

### 1. **Automatic Price Calculation**
**Location:** [app/views/stadiums/v_single_stadium.php](app/views/stadiums/v_single_stadium.php)

- Real-time price calculation when user selects duration
- Formula: `(Price per Hour × Duration) + (2% Service Fee)`
- Updates subtotal, service fee, and total amount dynamically
- Display updates in the booking sidebar

**How it works:**
```javascript
Subtotal = Price per Hour × Duration
Service Fee = Subtotal × 0.02
Total = Subtotal + Service Fee
```

---

### 2. **Booking Form Submission with Validation**
**Location:** [app/views/stadiums/v_single_stadium.php](app/views/stadiums/v_single_stadium.php) - JavaScript section

**Validations:**
- ✅ User must be logged in (redirects to login if not)
- ✅ All fields required: date, start time, duration
- ✅ Button disabled during processing to prevent double-submission
- ✅ Error handling with user-friendly messages

**Flow:**
1. User fills booking form (date, time, duration)
2. User clicks "Book Now" button
3. JavaScript validates all fields
4. If user not logged in → redirect to login
5. Otherwise → send AJAX POST to `/booking/checkout`
6. Creates temporary "reserved" booking
7. Redirects to checkout page with countdown timer

---

### 3. **Checkout Page with 5-Minute Countdown Timer**
**Location:** [app/views/booking/v_checkout.php](app/views/booking/v_checkout.php)

**Features:**
- ⏱️ 5-minute (300 second) countdown timer
- 🎨 Animated timer with visual warning when < 1 minute
- 🔴 Red pulsing animation when timer is critical
- Auto-release slot when timer expires
- User notifications at critical points

**Timer Behavior:**
```
5:00 - Normal display
1:00-0:59 - Warning animation starts
0:00 - Slot released, user redirected to stadium list
```

---

### 4. **Temporary Booking Reservation System**
**Booking Status Flow:**
```
Customer Select Date/Time
        ↓
Click "Book Now"
        ↓
Create Booking with status = "reserved"
        ↓
User Redirected to Checkout Page (5-min timer starts)
        ↓
    ┌───────────────────────────────────────┐
    │   Payment Successful?                 │
    ├───────────────┬───────────────────────┤
    │      YES      │          NO           │
    ├───────────────┼───────────────────────┤
    │ status =      │ Timer expires or      │
    │ "confirmed"   │ user cancels          │
    │ payment_status│ status = "cancelled"  │
    │ = "paid"      │ Slot released for     │
    │ Booking fixed │ other users           │
    └───────────────┴───────────────────────┘
```

---

### 5. **User Role Validation**
**Location:** [app/controllers/Booking.php](app/controllers/Booking.php) - `checkout()` method

**Allowed Users:** Regular customers only

**Blocked Users:**
- ❌ Admin users (`role = 'admin'`)
- ❌ Stadium owners (`role = 'stadium_owner'`)  
- ❌ Stadium owners cannot book their own stadiums

**Validation Code:**
```php
$userRole = Auth::getUserRole();
if ($userRole === 'admin' || $userRole === 'stadium_owner') {
    header('Location: ' . URLROOT . '/stadiums');
    exit;
}
```

---

### 6. **Slot Availability Management**
**Location:** [app/models/M_Stadium_owner.php](app/models/M_Stadium_owner.php) - `checkAvailability()` method

**How It Works:**
- Only counts **confirmed** bookings when checking availability
- Ignores **reserved** bookings (temporary slots that may expire)
- This allows slots to open up when reserved bookings expire

**SQL Query:**
```sql
SELECT COUNT(*) as count FROM bookings 
WHERE stadium_id = :stadium_id 
AND DATE(start_date) = :date
AND status = 'confirmed'  -- Only confirmed, not reserved!
AND (start_time < :end_time AND end_time > :start_time)
```

---

### 7. **Booking Cancellation & Slot Release**
**Location:** [app/controllers/Booking.php](app/controllers/Booking.php) - `release_reservation()` method

**When Slot is Released:**
1. Timer expires (after 5 minutes)
2. User clicks "Cancel Booking" button
3. User closes browser/navigates away

**What Happens:**
- Booking status changed to "cancelled"
- `cancelled_at` timestamp recorded
- Cancellation reason logged
- Slot immediately becomes available for other users

---

## Database Schema (Bookings Table)

The system uses the existing `bookings` table with these relevant fields:

```sql
bookings:
- id (primary key)
- stadium_id
- customer_id
- owner_id
- booking_date
- start_date
- end_date
- start_time
- end_time
- duration_hours
- total_price
- status: ['pending', 'reserved', 'confirmed', 'cancelled']
- payment_status: ['pending', 'paid', 'partial', 'refunded']
- customer_notes
- cancellation_reason
- cancelled_at
- created_at
- updated_at
```

---

## Step-by-Step Usage Flow

### For Customers:

**Step 1: Browse Stadiums**
- Visit []()/stadiums to see all available stadiums

**Step 2: View Stadium Details**
- Click on a stadium to view full details
- See booking form in the right sidebar

**Step 3: Select Booking Details**
- Select date (today or future dates)
- Select start time (6 AM - 9 PM)
- Select duration (1-6 hours)
- Watch price calculate automatically

**Step 4: Click "Book Now"**
- If not logged in → redirected to login page
- If logged in → sent to checkout page

**Step 5: Checkout Page (5-minute timer)**
- Review booking summary
- Choose payment method
- Complete payment before timer expires
- If timer expires → slot released, must book again

**Step 6: Payment Methods**
Three options available:
1. Credit/Debit Card (Visa, Mastercard, Amex)
2. PayPal
3. Bank Transfer (with receipt upload)

**Step 7: Confirmation**
- Payment successful → booking confirmed
- Confirmation email sent
- Booking appears in "My Bookings"
- Can cancel (with refund terms) up to 12 hours before

---

## File Changes Summary

### Modified Files:

1. **`app/views/stadiums/v_single_stadium.php`**
   - Added JavaScript for price calculation
   - Added booking form submission handler
   - AJAX calls to checkpoint endpoint

2. **`app/controllers/Booking.php`**
   - Added `checkout()` method (handles both GET and POST)
   - Added `release_reservation()` method
   - Enhanced authentication checks
   - Added admin/stadium_owner role validation

3. **`app/models/M_Stadium_owner.php`**
   - Updated `checkAvailability()` to only count confirmed bookings

### New Files Created:

1. **`app/views/booking/v_checkout.php`**
   - Complete checkout page
   - 5-minute countdown timer
   - Payment form with multiple methods
   - JavaScript for timer logic and payment handling

---

## API Endpoints

### 1. Checkout (POST)
```
POST /booking/checkout
Parameters:
- stadium_id (required)
- date or booking_date (required)
- start_time (required)
- duration_hours (required)
- stadium_price (required)

Response:
{
  "success": true/false,
  "booking_id": 123,
  "message": "..."
}
```

### 2. Checkout Page (GET)
```
GET /booking/checkout/{booking_id}
Shows checkout page with timer for reserved booking
```

### 3. Release Reservation (POST)
```
POST /booking/release_reservation
Parameters:
- booking_id (required)

Response:
{
  "success": true/false,
  "message": "Booking cancelled successfully"
}
```

### 4. Process Payment (POST)
```
POST /booking/process_payment
Parameters:
- booking_id (required)
- payment_method (required)

Response:
{
  "success": true/false,
  "redirect": "/booking/success/123",
  "message": "..."
}
```

---

## Security Considerations

✅ **Implemented:**
- User authentication required (redirects to login if not authenticated)
- Booking ownership validation (can only process own bookings)
- Admin/stadium owner role checks (cannot book stadiums)
- CSRF protection (via form handling)
- Server-side validation of all inputs
- Status validation (only "reserved" bookings can proceed to payment)

⚠️ **To Implement in Production:**
- Stripe/PayPal integration for real payment processing
- SSL/TLS for all payment pages
- PCI DSS compliance for credit card data
- Rate limiting on API endpoints
- Audit logging for all transactions
- Email verification for confirmations

---

## Testing Checklist

- [ ] User can calculate price automatically
- [ ] Non-logged-in user redirected to login when booking
- [ ] Admin cannot book stadiums (redirect)
- [ ] Stadium owner cannot book their own stadiums
- [ ] Checkout page loads with correct booking data
- [ ] Timer counts down from 5:00
- [ ] Timer triggers warning animation at < 1 minute
- [ ] Timer expiration releases slot
- [ ] Cancel button releases slot immediately
- [ ] Payment form validates required fields
- [ ] Payment success updates booking to "confirmed"
- [ ] User can see confirmed booking in "My Bookings"
- [ ] Cancelled bookings show correctly
- [ ] Slot availability updates correctly after cancellation

---

## Future Enhancements

1. **Real Payment Integration**
   - Stripe API integration
   - PayPal API integration
   - Receipt generation

2. **Advanced Scheduling**
   - Recurring bookings
   - Group bookings
   - Premium time slots

3. **Communication**
   - SMS notifications
   - Push notifications
   - In-app messaging

4. **Analytics**
   - Booking trends
   - Revenue analytics
   - Customer behavior tracking

5. **Admin Features**
   - Manual booking creation
   - Booking management dashboard
   - Revenue reports

6. **Customer Features**
   - Wishlist/favorites
   - Booking history export
   - Rating & review system

---

## Support

For questions or issues with the booking system:
- Check error logs in `/logs/` directory
- Review server error logs
- Test with sample data first
- Ensure database fields exist and are nullable where appropriate

