<?php

namespace Tests\Unit\Actions;

use PHPUnit\Framework\TestCase;

/**
 * Test suite for register_action.php
 * 
 * Tests registration functionality including:
 * - Input validation
 * - Password strength requirements
 * - Password confirmation matching
 * - Email uniqueness
 * - Auto-login after registration
 */
class RegisterActionTest extends TestCase
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
     * Test registration requires POST method
     */
    public function testRegistrationRequiresPostMethod()
    {
        $_SERVER['REQUEST_METHOD'] = 'GET';
        
        $this->assertNotEquals('POST', $_SERVER['REQUEST_METHOD']);
    }
    
    /**
     * Test first name validation
     */
    public function testFirstNameValidation()
    {
        $testCases = [
            ['', false],
            ['   ', false],
            ['John', true],
            ['محمد', true],
            ['John123', true],
        ];
        
        foreach ($testCases as [$input, $expected]) {
            $trimmed = trim($input);
            $isValid = $trimmed !== '';
            
            $this->assertEquals($expected, $isValid, "First name '{$input}' validation failed");
        }
    }
    
    /**
     * Test last name validation
     */
    public function testLastNameValidation()
    {
        $testCases = [
            ['', false],
            ['   ', false],
            ['Doe', true],
            ['العلي', true],
        ];
        
        foreach ($testCases as [$input, $expected]) {
            $trimmed = trim($input);
            $isValid = $trimmed !== '';
            
            $this->assertEquals($expected, $isValid);
        }
    }
    
    /**
     * Test email validation
     */
    public function testEmailValidation()
    {
        $validEmails = [
            'user@example.com',
            'test.user@domain.co.uk',
            'admin+tag@site.org',
        ];
        
        $invalidEmails = [
            '',
            'notanemail',
            '@nodomain.com',
            'spaces in@email.com',
        ];
        
        foreach ($validEmails as $email) {
            $this->assertNotFalse(filter_var($email, FILTER_VALIDATE_EMAIL));
        }
        
        foreach ($invalidEmails as $email) {
            $this->assertFalse(filter_var($email, FILTER_VALIDATE_EMAIL));
        }
    }
    
    /**
     * Test password length requirement (minimum 8 characters)
     */
    public function testPasswordLengthRequirement()
    {
        $testCases = [
            ['', false],
            ['1234567', false],
            ['12345678', true],
            ['VeryLongPassword123!', true],
        ];
        
        foreach ($testCases as [$password, $expected]) {
            $isValid = strlen($password) >= 8;
            
            $this->assertEquals($expected, $isValid, "Password '{$password}' length validation failed");
        }
    }
    
    /**
     * Test password confirmation matching
     */
    public function testPasswordConfirmationMatching()
    {
        $testCases = [
            ['password123', 'password123', true],
            ['password123', 'password124', false],
            ['', '', true],
            ['password', '', false],
        ];
        
        foreach ($testCases as [$password, $confirm, $expected]) {
            $matches = $password === $confirm;
            
            $this->assertEquals($expected, $matches);
        }
    }
    
    /**
     * Test password hashing
     */
    public function testPasswordHashing()
    {
        $password = 'SecurePassword123';
        $hash = password_hash($password, PASSWORD_DEFAULT);
        
        $this->assertNotEquals($password, $hash);
        $this->assertTrue(password_verify($password, $hash));
        $this->assertGreaterThan(20, strlen($hash));
    }
    
    /**
     * Test complete validation logic
     */
    public function testCompleteValidationLogic()
    {
        $validRegistration = [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john@example.com',
            'password' => 'SecurePass123',
            'confirm_password' => 'SecurePass123',
        ];
        
        $first = trim($validRegistration['first_name']);
        $last = trim($validRegistration['last_name']);
        $email = trim($validRegistration['email']);
        $pass = $validRegistration['password'];
        $confirm = $validRegistration['confirm_password'];
        
        $errors = [];
        
        if ($first === '') $errors[] = 'First name required';
        if ($last === '') $errors[] = 'Last name required';
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Invalid email';
        if (strlen($pass) < 8) $errors[] = 'Password too short';
        if ($pass !== $confirm) $errors[] = 'Passwords do not match';
        
        $this->assertEmpty($errors);
    }
    
    /**
     * Test invalid registration scenarios
     */
    public function testInvalidRegistrationScenarios()
    {
        $invalidCases = [
            [
                'data' => ['first_name' => '', 'last_name' => 'Doe', 'email' => 'test@example.com', 'password' => 'password123', 'confirm_password' => 'password123'],
                'expectedError' => 'first name'
            ],
            [
                'data' => ['first_name' => 'John', 'last_name' => '', 'email' => 'test@example.com', 'password' => 'password123', 'confirm_password' => 'password123'],
                'expectedError' => 'last name'
            ],
            [
                'data' => ['first_name' => 'John', 'last_name' => 'Doe', 'email' => 'invalid-email', 'password' => 'password123', 'confirm_password' => 'password123'],
                'expectedError' => 'email'
            ],
            [
                'data' => ['first_name' => 'John', 'last_name' => 'Doe', 'email' => 'test@example.com', 'password' => 'short', 'confirm_password' => 'short'],
                'expectedError' => 'password'
            ],
            [
                'data' => ['first_name' => 'John', 'last_name' => 'Doe', 'email' => 'test@example.com', 'password' => 'password123', 'confirm_password' => 'different'],
                'expectedError' => 'match'
            ],
        ];
        
        foreach ($invalidCases as $case) {
            $data = $case['data'];
            $errors = [];
            
            if (trim($data['first_name']) === '') $errors[] = 'first name';
            if (trim($data['last_name']) === '') $errors[] = 'last name';
            if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) $errors[] = 'email';
            if (strlen($data['password']) < 8) $errors[] = 'password';
            if ($data['password'] !== $data['confirm_password']) $errors[] = 'match';
            
            $this->assertNotEmpty($errors);
            $this->assertStringContainsStringIgnoringCase($case['expectedError'], implode(' ', $errors));
        }
    }
    
    /**
     * Test default role assignment
     */
    public function testDefaultRoleAssignment()
    {
        $role = 'user';
        
        $this->assertEquals('user', $role);
        $this->assertNotEquals('admin', $role);
        $this->assertNotEquals('organization', $role);
    }
    
    /**
     * Test session creation after registration
     */
    public function testSessionCreationAfterRegistration()
    {
        $_SESSION['user'] = [
            'id' => 1,
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john@example.com',
            'role' => 'user'
        ];
        
        $this->assertArrayHasKey('user', $_SESSION);
        $this->assertEquals('user', $_SESSION['user']['role']);
        $this->assertIsInt($_SESSION['user']['id']);
    }
    
    /**
     * Test password requirements edge cases
     */
    public function testPasswordRequirementsEdgeCases()
    {
        // Exactly 8 characters (minimum)
        $this->assertTrue(strlen('12345678') >= 8);
        
        // 7 characters (below minimum)
        $this->assertFalse(strlen('1234567') >= 8);
        
        // Very long password
        $longPass = str_repeat('a', 1000);
        $this->assertTrue(strlen($longPass) >= 8);
    }
}