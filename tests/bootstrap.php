<?php
/**
 * PHPUnit Bootstrap File
 * 
 * This file is loaded before any tests run.
 * It sets up the testing environment and autoloader.
 */

// Set error reporting for tests
error_reporting(E_ALL);
ini_set('display_errors', '1');

// Prevent actual session operations in tests
if (!defined('PHPUNIT_TESTING')) {
    define('PHPUNIT_TESTING', true);
}

// Load Composer autoloader
require_once __DIR__ . '/../vendor/autoload.php';

// Set up test session for testing
if (!isset($_SESSION)) {
    $_SESSION = [];
}

// Ensure timezone is set
date_default_timezone_set('UTC');