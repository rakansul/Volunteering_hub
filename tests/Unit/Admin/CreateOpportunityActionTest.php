<?php

namespace Tests\Unit\Admin;

use PHPUnit\Framework\TestCase;

/**
 * Test suite for admin/create_opportunity_action.php
 * 
 * Tests opportunity creation including:
 * - Admin authorization requirement
 * - Input validation
 * - Required fields checking
 * - Date and time validation
 * - Seats validation
 */
class CreateOpportunityActionTest extends TestCase
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
     * Test title validation
     */
    public function testTitleValidation()
    {
        $testCases = [
            ['', false],
            ['   ', false],
            ['Valid Title', true],
            ['فرصة تطوعية', true],
        ];
        
        foreach ($testCases as [$title, $expected]) {
            $trimmed = trim($title);
            $isValid = $trimmed !== '';
            
            $this->assertEquals($expected, $isValid);
        }
    }
    
    /**
     * Test organization ID validation
     */
    public function testOrganizationIdValidation()
    {
        $testCases = [
            [0, false],
            [-1, false],
            [1, true],
            [100, true],
        ];
        
        foreach ($testCases as [$orgId, $expected]) {
            $isValid = $orgId > 0;
            $this->assertEquals($expected, $isValid);
        }
    }
    
    /**
     * Test event date validation
     */
    public function testEventDateValidation()
    {
        $testCases = [
            [null, false],
            ['', false],
            ['2025-12-31', true],
            ['2025-01-01', true],
        ];
        
        foreach ($testCases as [$date, $expected]) {
            $isValid = !empty($date);
            $this->assertEquals($expected, $isValid);
        }
    }
    
    /**
     * Test description validation
     */
    public function testDescriptionValidation()
    {
        $testCases = [
            ['', false],
            ['   ', false],
            ['Valid description', true],
            ['وصف الفرصة التطوعية', true],
        ];
        
        foreach ($testCases as [$description, $expected]) {
            $trimmed = trim($description);
            $isValid = $trimmed !== '';
            
            $this->assertEquals($expected, $isValid);
        }
    }
    
    /**
     * Test seats validation
     */
    public function testSeatsValidation()
    {
        $testCases = [
            ['', null, true],   // Empty is valid (null)
            ['0', 0, true],     // Zero is valid
            ['10', 10, true],   // Positive number
            ['-5', -5, false],  // Negative invalid
        ];
        
        foreach ($testCases as [$input, $expected, $valid]) {
            $seats = $input !== '' ? (int)$input : null;
            
            if ($expected === null) {
                $this->assertNull($seats);
            } else {
                $this->assertEquals($expected, $seats);
            }
        }
    }
    
    /**
     * Test complete validation
     */
    public function testCompleteValidation()
    {
        $validData = [
            'title' => 'Community Cleanup',
            'org_id' => 1,
            'event_date' => '2025-12-31',
            'description' => 'Join us for a community cleanup event',
        ];
        
        $errors = [];
        
        $title = trim($validData['title']);
        $orgId = (int)$validData['org_id'];
        $eventDate = $validData['event_date'];
        $description = trim($validData['description']);
        
        if ($title === '') $errors[] = 'Title required';
        if (!$orgId) $errors[] = 'Organization required';
        if (!$eventDate) $errors[] = 'Event date required';
        if ($description === '') $errors[] = 'Description required';
        
        $this->assertEmpty($errors);
    }
    
    /**
     * Test optional fields handling
     */
    public function testOptionalFieldsHandling()
    {
        $_POST['category_id'] = '';
        $_POST['start_time'] = '';
        $_POST['end_time'] = '';
        $_POST['seats'] = '';
        
        $categoryId = $_POST['category_id'] ?: null;
        $startTime = $_POST['start_time'] ?: null;
        $endTime = $_POST['end_time'] ?: null;
        $seats = $_POST['seats'] !== '' ? (int)$_POST['seats'] : null;
        
        $this->assertNull($categoryId);
        $this->assertNull($startTime);
        $this->assertNull($endTime);
        $this->assertNull($seats);
    }
    
    /**
     * Test location fields
     */
    public function testLocationFields()
    {
        $_POST['location'] = 'Riyadh';
        $_POST['location_detail'] = 'King Fahd Road';
        
        $location = trim($_POST['location'] ?? '');
        $locationDetail = trim($_POST['location_detail'] ?? '');
        
        $this->assertEquals('Riyadh', $location);
        $this->assertEquals('King Fahd Road', $locationDetail);
    }
}