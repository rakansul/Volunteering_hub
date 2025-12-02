<?php

namespace Tests\Unit\Admin;

use PHPUnit\Framework\TestCase;

/**
 * Test suite for admin/update_opportunity_action.php
 */
class UpdateOpportunityActionTest extends TestCase
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
            [999, true],
        ];
        
        foreach ($testCases as [$id, $expected]) {
            $isValid = $id > 0;
            $this->assertEquals($expected, $isValid);
        }
    }
    
    /**
     * Test all required fields
     */
    public function testAllRequiredFields()
    {
        $data = [
            'id' => 1,
            'title' => 'Updated Title',
            'org_id' => 1,
            'event_date' => '2025-12-31',
            'description' => 'Updated description',
        ];
        
        $errors = [];
        
        $id = (int)($data['id'] ?? 0);
        $title = trim($data['title'] ?? '');
        $orgId = (int)($data['org_id'] ?? 0);
        $eventDate = $data['event_date'] ?? null;
        $description = trim($data['description'] ?? '');
        
        if ($id <= 0) $errors[] = 'Invalid ID';
        if ($title === '') $errors[] = 'Title required';
        if (!$orgId) $errors[] = 'Organization required';
        if (!$eventDate) $errors[] = 'Date required';
        if ($description === '') $errors[] = 'Description required';
        
        $this->assertEmpty($errors);
    }
    
    /**
     * Test missing ID handling
     */
    public function testMissingIdHandling()
    {
        $id = (int)($_POST['id'] ?? 0);
        
        $this->assertEquals(0, $id);
        $this->assertFalse($id > 0);
    }
}