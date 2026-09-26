<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/* 1️⃣ Unset all session variables */
$_SESSION = array();

/* 2️⃣ Destroy the session cookie (browser session) */
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(),     // PHPSESSID or custom session name
        '',                 // Empty value
        time() - 42000,     // Expire in the past
        $params["path"],
        $params["domain"],
        $params["secure"],
        $params["httponly"]
    );
}

/* 3️⃣ Destroy the session */
session_destroy();

/* 4️⃣ Delete custom cookies if any (adjust cookie names as used in your app) */
$cookies_to_delete = ['user', 'mail', 'email']; // add any other cookies you use

foreach ($cookies_to_delete as $cookie_name) {
    if (isset($_COOKIE[$cookie_name])) {
        setcookie($cookie_name, '', time() - 3600, '/'); // expire in the past
        unset($_COOKIE[$cookie_name]);
    }
}

/* 5️⃣ Optional: Clear any cached headers to prevent back button issues */
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

/* 6️⃣ Redirect to the appropriate login page */
header("Location:index.php");
exit();
?>