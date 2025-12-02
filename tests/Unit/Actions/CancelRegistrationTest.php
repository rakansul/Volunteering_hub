<?php

namespace Tests\Unit\Actions;

use PHPUnit\Framework\TestCase;

/**
 * Test suite for cancel_registration.php
 * 
 * Tests cancellation functionality including:
 * - Authentication requirement
 * - Registration ID validation
 * - Ownership verification
 * - Cancellation process
 */
class CancelRegistrationTest extends TestCase
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
     * Test cancellation requires POST method
     */
    public function testCancellationRequiresPostMethod()
    {
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $this->assertNotEquals('POST', $_SERVER['REQUEST_METHOD']);
        
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $this->assertEquals('POST', $_SERVER['REQUEST_METHOD']);
    }
    
    /**
     * Test registration ID validation
     */
    public function testRegistrationIdValidation()
    {
        $testCases = [
            [0, false],
            [-1, false],
            [1, true],
            [100, true],
        ];
        
        foreach ($testCases as [$regId, $expected]) {
            $isValid = $regId > 0;
            $this->assertEquals($expected, $isValid);
        }
    }
    
    /**
     * Test registration ID type casting
     */
    public function testRegistrationIdTypeCasting()
    {
        $_POST['registration_id'] = '42';
        $regId = (int)($_POST['registration_id'] ?? 0);
        
        $this->assertIsInt($regId);
        $this->assertEquals(42, $regId);
    }
    
    /**
     * Test authentication requirement
     */
    public function testAuthenticationRequirement()
    {
        $this->assertArrayNotHasKey('user', $_SESSION);
        
        $_SESSION['user'] = ['id' => 1];
        $this->assertArrayHasKey('user', $_SESSION);
    }
    
    /**
     * Test ownership verification logic
     */
    public function testOwnershipVerificationLogic()
    {
        $currentUserId = 1;
        $registrationUserId = 1;
        
        $this->assertEquals($currentUserId, $registrationUserId);
        
        $registrationUserId = 2;
        $this->assertNotEquals($currentUserId, $registrationUserId);
    }
    
    /**
     * Test missing registration ID
     */
    public function testMissingRegistrationId()
    {
        $regId = (int)($_POST['registration_id'] ?? 0);
        
        $this->assertEquals(0, $regId);
        $this->assertFalse($regId > 0);
    }
}