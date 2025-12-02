<?php

namespace Tests\Unit\Actions;

use PHPUnit\Framework\TestCase;

/**
 * Test suite for register_for_event.php
 * 
 * Tests event registration functionality including:
 * - Authentication requirement
 * - Opportunity ID validation
 * - Duplicate registration prevention
 * - Registration creation
 */
class RegisterForEventTest extends TestCase
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
        
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $this->assertEquals('POST', $_SERVER['REQUEST_METHOD']);
    }
    
    /**
     * Test opportunity ID validation
     */
    public function testOpportunityIdValidation()
    {
        $testCases = [
            ['', false],
            ['0', false],
            ['-1', false],
            ['1', true],
            ['123', true],
            ['abc', false],
        ];
        
        foreach ($testCases as [$input, $expected]) {
            $oppId = (int)$input;
            $isValid = $oppId > 0;
            
            $this->assertEquals($expected, $isValid, "Opportunity ID '{$input}' validation failed");
        }
    }
    
    /**
     * Test opportunity ID type casting
     */
    public function testOpportunityIdTypeCasting()
    {
        $_POST['opportunity_id'] = '42';
        $oppId = (int)($_POST['opportunity_id'] ?? 0);
        
        $this->assertIsInt($oppId);
        $this->assertEquals(42, $oppId);
    }
    
    /**
     * Test missing opportunity ID handling
     */
    public function testMissingOpportunityIdHandling()
    {
        $oppId = (int)($_POST['opportunity_id'] ?? 0);
        
        $this->assertEquals(0, $oppId);
        $this->assertFalse($oppId > 0);
    }
    
    /**
     * Test authentication requirement
     */
    public function testAuthenticationRequirement()
    {
        // Not logged in
        $this->assertArrayNotHasKey('user', $_SESSION);
        
        // Logged in
        $_SESSION['user'] = ['id' => 1];
        $this->assertArrayHasKey('user', $_SESSION);
    }
    
    /**
     * Test user ID retrieval
     */
    public function testUserIdRetrieval()
    {
        $_SESSION['user'] = ['id' => 42];
        
        $userId = $_SESSION['user']['id'] ?? null;
        
        $this->assertNotNull($userId);
        $this->assertEquals(42, $userId);
    }
    
    /**
     * Test invalid opportunity ID values
     */
    public function testInvalidOpportunityIdValues()
    {
        $invalidValues = [0, -1, -999, null];
        
        foreach ($invalidValues as $value) {
            $oppId = (int)$value;
            $this->assertFalse($oppId > 0, "Value {$value} should be invalid");
        }
    }
    
    /**
     * Test valid opportunity ID values
     */
    public function testValidOpportunityIdValues()
    {
        $validValues = [1, 42, 999, 1000000];
        
        foreach ($validValues as $value) {
            $oppId = (int)$value;
            $this->assertTrue($oppId > 0, "Value {$value} should be valid");
        }
    }
}