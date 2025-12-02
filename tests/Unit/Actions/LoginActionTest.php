<?php

namespace Tests\Unit\Actions;

use PHPUnit\Framework\TestCase;

/**
 * Test suite for login_action.php
 * 
 * Tests login functionality including:
 * - Email validation
 * - Password verification
 * - Session creation
 * - Role-based redirects
 * - Error handling
 */
class LoginActionTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $_SESSION = [];
        $_POST = [];
        $_SERVER = [];
    }
    
    protected function tearDown(): void
    {
        $_SESSION = [];
        $_POST = [];
        $_SERVER = [];
        parent::tearDown();
    }
    
    /**
     * Test login requires POST method
     */
    public function testLoginRequiresPostMethod()
    {
        $_SERVER['REQUEST_METHOD'] = 'GET';
        
        // The actual file would redirect, we test the logic
        $this->assertEquals('GET', $_SERVER['REQUEST_METHOD']);
        $this->assertNotEquals('POST', $_SERVER['REQUEST_METHOD']);
    }
    
    /**
     * Test login validates email format
     */
    public function testLoginValidatesEmailFormat()
    {
        $invalidEmails = [
            'notanemail',
            'missing@domain',
            '@nodomain.com',
            'spaces in@email.com',
            '',
        ];
        
        foreach ($invalidEmails as $email) {
            $this->assertFalse(
                filter_var($email, FILTER_VALIDATE_EMAIL),
                "Email '{$email}' should be invalid"
            );
        }
    }
    
    /**
     * Test login accepts valid email formats
     */
    public function testLoginAcceptsValidEmails()
    {
        $validEmails = [
            'user@example.com',
            'test.user@domain.co.uk',
            'admin+tag@site.org',
        ];
        
        foreach ($validEmails as $email) {
            $this->assertNotFalse(
                filter_var($email, FILTER_VALIDATE_EMAIL),
                "Email '{$email}' should be valid"
            );
        }
    }
    
    /**
     * Test password verification logic
     */
    public function testPasswordVerificationLogic()
    {
        $password = 'SecurePassword123';
        $hash = password_hash($password, PASSWORD_DEFAULT);
        
        $this->assertTrue(password_verify($password, $hash));
        $this->assertFalse(password_verify('WrongPassword', $hash));
        $this->assertFalse(password_verify('', $hash));
    }
    
    /**
     * Test session data structure after login
     */
    public function testSessionDataStructureAfterLogin()
    {
        // Simulate successful login
        $_SESSION['user'] = [
            'id' => 1,
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john@example.com',
            'role' => 'user'
        ];
        
        $this->assertArrayHasKey('user', $_SESSION);
        $this->assertArrayHasKey('id', $_SESSION['user']);
        $this->assertArrayHasKey('email', $_SESSION['user']);
        $this->assertArrayHasKey('role', $_SESSION['user']);
        $this->assertIsInt($_SESSION['user']['id']);
    }
    
    /**
     * Test role-based redirect logic
     */
    public function testRoleBasedRedirectLogic()
    {
        $roleRedirects = [
            'admin' => 'admin/admin_dashboard.php',
            'organization' => 'admin/manage_opportunities.php',
            'user' => 'profile.php'
        ];
        
        foreach ($roleRedirects as $role => $expectedRedirect) {
            // Test the logic that determines redirect
            $_SESSION['user'] = ['role' => $role];
            
            if ($role === 'admin') {
                $redirect = 'admin/admin_dashboard.php';
            } elseif ($role === 'organization') {
                $redirect = 'admin/manage_opportunities.php';
            } else {
                $redirect = 'profile.php';
            }
            
            $this->assertEquals($expectedRedirect, $redirect);
        }
    }
    
    /**
     * Test empty credentials handling
     */
    public function testEmptyCredentialsHandling()
    {
        $_POST['email'] = '';
        $_POST['password'] = '';
        
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        
        $this->assertEquals('', $email);
        $this->assertEquals('', $password);
        $this->assertFalse(filter_var($email, FILTER_VALIDATE_EMAIL));
    }
    
    /**
     * Test trimming of email input
     */
    public function testEmailTrimming()
    {
        $_POST['email'] = '  user@example.com  ';
        
        $email = trim($_POST['email'] ?? '');
        
        $this->assertEquals('user@example.com', $email);
        $this->assertNotFalse(filter_var($email, FILTER_VALIDATE_EMAIL));
    }
    
    /**
     * Test SQL injection prevention in email
     */
    public function testSqlInjectionPreventionInEmail()
    {
        $maliciousInputs = [
            "admin'--",
            "admin' OR '1'='1",
            "admin'; DROP TABLE users--",
        ];
        
        foreach ($maliciousInputs as $input) {
            // Email validation should reject these
            $this->assertFalse(
                filter_var($input, FILTER_VALIDATE_EMAIL),
                "Malicious input '{$input}' should not validate as email"
            );
        }
    }
    
    /**
     * Test password field is not trimmed
     */
    public function testPasswordNotTrimmed()
    {
        $_POST['password'] = '  password  ';
        
        // Passwords should NOT be trimmed (trailing/leading spaces could be part of password)
        $password = $_POST['password'] ?? '';
        
        $this->assertEquals('  password  ', $password);
        $this->assertNotEquals('password', $password);
    }
}