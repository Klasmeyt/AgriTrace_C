<?php
session_start();
// Supabase Configuration
define('SUPABASE_URL', 'https://vkgwhdhreoxokaohcvxp.supabase.co');
define('SUPABASE_ANON_KEY', 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6InZrZ3doZGhyZW94b2thb2hjdnhwIiwicm9sZSI6ImFub24iLCJpYXQiOjE3NzQxNzI0NzAsImV4cCI6MjA4OTc0ODQ3MH0.gjNM0Ujc7powUhAs9vn1z6bBoyOlvnVuSF1i01tn7y0');
define('SUPABASE_SERVICE_KEY', 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6InZrZ3doZGhyZW94b2thb2hjdnhwIiwicm9sZSI6InNlcnZpY2Vfcm9sZSIsImlhdCI6MTc3NDE3MjQ3MCwiZXhwIjoyMDg5NzQ4NDcwfQ.TH9zr04Nuj2I9-0Y5PwTCl7dnMO9MRlQbLSdaLb8VDs');

// Database connection (if using local MySQL alongside Supabase)
$host = 'localhost';
$dbname = 'agritrace';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Supabase PHP Client (you'll need to install via composer: composer require supabase/supabase-php)
require_once 'vendor/autoload.php';
use Supabase\SupabaseClient;

$supabase = new SupabaseClient(SUPABASE_URL, SUPABASE_ANON_KEY);

// Utility functions
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function getUserRole() {
    return $_SESSION['user_role'] ?? null;
}

function requireLogin() {
    if (!isLoggedIn()) {
        header('Location: login.php');
        exit;
    }
}

function requireRole($role) {
    requireLogin();
    if (getUserRole() !== $role) {
        header('Location: index.php');
        exit;
    }
}
?>