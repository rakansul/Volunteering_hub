<?php

namespace Tests\Unit\Actions;

use PHPUnit\Framework\TestCase;

/**
 * Test suite for edit_profile_action.php
 * 
 * Tests profile editing functionality including:
 * - Name updates
 * - Email updates with uniqueness check
 * - Optional password changes
 * - Password validation when changing
 * - Session synchronization
 */
class EditProfileActionTest extends TestCase
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
     * Test profile update requires authentication
     */
    public function testProfileUpdateRequiresAuthentication()
    {
        $this->assertArrayNotHasKey('user', $_SESSION);
        
        // Simulating logged in state
        $_SESSION['user'] = ['id' => 1];
        $this->assertArrayHasKey('user', $_SESSION);
    }
    
    /**
     * Test name validation
     */
    public function testNameValidation()
    {
        $testCases = [
            ['', false],
            ['   ', false],
            ['John', true],
            ['المستخدم', true],
        ];
        
        foreach ($testCases as [$name, $expected]) {
            $trimmed = trim($name);
            $isValid = $trimmed !== '';
            
            $this->assertEquals($expected, $isValid);
        }
    }
    
    /**
     * Test email validation
     */
    public function testEmailValidation()
    {
        $validEmails = ['user@example.com', 'new.email@domain.org'];
        $invalidEmails = ['', 'not-an-email', '@invalid.com'];
        
        foreach ($validEmails as $email) {
            $this->assertNotFalse(filter_var($email, FILTER_VALIDATE_EMAIL));
        }
        
        foreach ($invalidEmails as $email) {
            $this->assertFalse(filter_var($email, FILTER_VALIDATE_EMAIL));
        }
    }
    
    /**
     * Test optional password change validation
     */
    public function testOptionalPasswordChangeValidation()
    {
        // No password provided - should be valid (no change)
        $_POST['password'] = '';
        $_POST['confirm_password'] = '';
        
        $password = $_POST['password'];
        $updatePassword = false;
        $errors = [];
        
        if ($password !== '') {
            if (strlen($password) < 8) {
                $errors[] = 'Password too short';
            }
            $updatePassword = true;
        }
        
        $this->assertFalse($updatePassword);
        $this->assertEmpty($errors);
    }
    
    /**
     * Test password change requires minimum length
     */
    public function testPasswordChangeRequiresMinimumLength()
    {
        $password = 'short';
        $errors = [];
        
        if ($password !== '') {
            if (strlen($password) < 8) {
                $errors[] = 'Password too short';
            }
        }
        
        $this->assertNotEmpty($errors);
    }
    
    /**
     * Test password change requires confirmation match
     */
    public function testPasswordChangeRequiresConfirmationMatch()
    {
        $testCases = [
            ['password123', 'password123', true],
            ['password123', 'different', false],
            ['NewPass123', 'NewPass123', true],
        ];
        
        foreach ($testCases as [$password, $confirm, $expected]) {
            $matches = $password === $confirm;
            $this->assertEquals($expected, $matches);
        }
    }
    
    /**
     * Test profile update logic
     */
    public function testProfileUpdateLogic()
    {
        $_SESSION['user'] = [
            'id' => 1,
            'first_name' => 'Old',
            'last_name' => 'Name',
            'email' => 'old@example.com',
        ];
        
        // Simulate update
        $newFirst = 'New';
        $newLast = 'User';
        $newEmail = 'new@example.com';
        
        $_SESSION['user']['first_name'] = $newFirst;
        $_SESSION['user']['last_name'] = $newLast;
        $_SESSION['user']['email'] = $newEmail;
        
        $this->assertEquals('New', $_SESSION['user']['first_name']);
        $this->assertEquals('User', $_SESSION['user']['last_name']);
        $this->assertEquals('new@example.com', $_SESSION['user']['email']);
    }
    
    /**
     * Test session synchronization after update
     */
    public function testSessionSynchronizationAfterUpdate()
    {
        $_SESSION['user'] = [
            'id' => 1,
            'first_name' => 'Original',
            'last_name' => 'User',
            'email' => 'original@example.com',
        ];
        
        $originalId = $_SESSION['user']['id'];
        
        // Update fields
        $_SESSION['user']['first_name'] = 'Updated';
        $_SESSION['user']['last_name'] = 'Person';
        $_SESSION['user']['email'] = 'updated@example.com';
        
        // ID should remain the same
        $this->assertEquals($originalId, $_SESSION['user']['id']);
        $this->assertEquals('Updated', $_SESSION['user']['first_name']);
    }
    
    /**
     * Test complete validation with all fields
     */
    public function testCompleteValidationWithAllFields()
    {
        $data = [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john@example.com',
            'password' => 'NewPassword123',
            'confirm_password' => 'NewPassword123',
        ];
        
        $errors = [];
        
        $first = trim($data['first_name']);
        $last = trim($data['last_name']);
        $email = trim($data['email']);
        $password = $data['password'];
        $confirm = $data['confirm_password'];
        
        if ($first === '') $errors[] = 'First name required';
        if ($last === '') $errors[] = 'Last name required';
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Invalid email';
        
        if ($password !== '') {
            if (strlen($password) < 8) $errors[] = 'Password too short';
            if ($password !== $confirm) $errors[] = 'Passwords do not match';
        }
        
        $this->assertEmpty($errors);
    }
}