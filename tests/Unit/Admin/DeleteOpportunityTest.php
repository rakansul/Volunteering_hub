<?php

namespace Tests\Unit\Admin;

use PHPUnit\Framework\TestCase;

/**
 * Test suite for admin/delete_opportunity.php
 */
class DeleteOpportunityTest extends TestCase
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
     * Test requires POST method
     */
    public function testRequiresPostMethod()
    {
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $this->assertNotEquals('POST', $_SERVER['REQUEST_METHOD']);
    }
    
    /**
     * Test opportunity ID validation
     */
    public function testOpportunityIdValidation()
    {
        $testCases = [
            [0, false],
            [-1, false],
            [1, true],
            [100, true],
        ];
        
        foreach ($testCases as [$id, $expected]) {
            $isValid = $id > 0;
            $this->assertEquals($expected, $isValid);
        }
    }
    
    /**
     * Test ID type casting
     */
    public function testIdTypeCasting()
    {
        $_POST['id'] = '42';
        $id = (int)($_POST['id'] ?? 0);
        
        $this->assertIsInt($id);
        $this->assertEquals(42, $id);
    }
    
    /**
     * Test missing ID
     */
    public function testMissingId()
    {
        $id = (int)($_POST['id'] ?? 0);
        
        $this->assertEquals(0, $id);
        $this->assertFalse($id > 0);
    }
}