<?php

// Suppress PHP warnings and notices in UI
error_reporting(E_ALL & ~E_WARNING & ~E_NOTICE & ~E_DEPRECATED);
ini_set('display_errors', 0);

//Database Configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'bookmyground');

//App Root
define('APPROOT', dirname(dirname(__FILE__)));

//URL Root
define('URLROOT', 'http://localhost/mvc');

//WEBSITE
define('SITENAME', 'BookMyGround');

// Mail (for password reset)
define('MAIL_FROM', 'noreply@bookmyground.lk');

// SMTP (optional) - set SMTP_HOST to enable sending via SMTP instead of PHP mail()
// Leave SMTP_HOST empty to use PHP mail() or, on localhost, show reset link on page
define('SMTP_HOST', 'smtp.gmail.com');           // e.g. 'smtp.gmail.com', 'smtp.mailtrap.io'
define('SMTP_PORT', 465);         // 587 for TLS, 465 for SSL, 25 for plain
define('SMTP_SECURE', 'ssl');     // 'tls', 'ssl', or ''
define('SMTP_USERNAME', 'bookmygroundlk@gmail.com');      // SMTP auth username (leave empty if no auth)
define('SMTP_PASSWORD', 'fkty jnxi ntjw fiev
');      // SMTP auth password
define('SMTP_FROM_EMAIL', 'bookmygroundlk@gmail.com');    // From address for SMTP (empty = use MAIL_FROM)

// Stripe Payment (add your keys from dashboard.stripe.com/test/apikeys)
define('STRIPE_PUBLISHABLE_KEY', 'pk_test_51T0iND0r1AwnhJoCMgM0K97LeZNOln5hVEgF5xl1V3DiZv6QOJIB9DmwrP94kznLkgWRD3xlKFbBKr5sJMP0sud600eUc6D8Dc'); // pk_test_... for test mode, pk_live_... for live
define('STRIPE_SECRET_KEY', 'sk_test_51T0iND0r1AwnhJoCLFjKxRL3gBulIwHYj00TnmsQxY9NfGVUTeewWl1q6IkEYPcGn2ztv4J3rZCYG3KlFw5uZma300IAeXniS6');      // sk_test_... for test mode, sk_live_... for live
define('STRIPE_CURRENCY', 'lkr');     // Currency code (lkr = Sri Lankan Rupee)
