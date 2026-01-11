<?php
// test_base_url.php
require_once 'config/config.php';

echo "BASE_URL: " . BASE_URL . "\n";
echo "SCRIPT_NAME: " . $_SERVER['SCRIPT_NAME'] . "\n";
echo "REQUEST_URI: " . $_SERVER['REQUEST_URI'] . "\n";
echo "DOCUMENT_ROOT: " . $_SERVER['DOCUMENT_ROOT'] . "\n";
echo "SCRIPT_FILENAME: " . $_SERVER['SCRIPT_FILENAME'] . "\n";

// Test the redirect_to function
echo "\nTesting redirect paths:\n";
echo "redirect_to('admin/login.php') would go to: " . BASE_URL . "admin/login.php\n";
echo "redirect_to('index.php') would go to: " . BASE_URL . "index.php\n";
echo "redirect_to('dashboard.php') from admin/ would go to: " . BASE_URL . "dashboard.php\n";
?>