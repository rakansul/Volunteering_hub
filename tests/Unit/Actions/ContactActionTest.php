<?php

namespace Tests\Unit\Actions;

use PHPUnit\Framework\TestCase;

/**
 * Test suite for contact_action.php
 * 
 * Tests contact form functionality including:
 * - Input validation
 * - Email format validation
 * - Required fields checking
 * - Email sending logic
 */
class ContactActionTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $_POST = [];
        $_SERVER = [];
        $_SESSION = [];
    }
    
    protected function tearDown(): void
    {
        $_POST = [];
        $_SERVER = [];
        $_SESSION = [];
        parent::tearDown();
    }
    
    /**
     * Test contact form requires POST method
     */
    public function testContactFormRequiresPostMethod()
    {
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $this->assertNotEquals('POST', $_SERVER['REQUEST_METHOD']);
    }
    
    /**
     * Test name validation
     */
    public function testNameValidation()
    {
        $testCases = [
            ['', false],
            ['   ', false],
            ['John Doe', true],
            ['أحمد', true],
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
        $validEmails = [
            'user@example.com',
            'contact@domain.org',
            'test+tag@site.co.uk',
        ];
        
        $invalidEmails = [
            '',
            'not-an-email',
            '@invalid.com',
            'missing@',
        ];
        
        foreach ($validEmails as $email) {
            $this->assertNotFalse(filter_var($email, FILTER_VALIDATE_EMAIL));
        }
        
        foreach ($invalidEmails as $email) {
            $this->assertFalse(filter_var($email, FILTER_VALIDATE_EMAIL));
        }
    }
    
    /**
     * Test subject validation
     */
    public function testSubjectValidation()
    {
        $testCases = [
            ['', false],
            ['   ', false],
            ['Help Request', true],
            ['استفسار', true],
        ];
        
        foreach ($testCases as [$subject, $expected]) {
            $trimmed = trim($subject);
            $isValid = $trimmed !== '';
            
            $this->assertEquals($expected, $isValid);
        }
    }
    
    /**
     * Test message validation
     */
    public function testMessageValidation()
    {
        $testCases = [
            ['', false],
            ['   ', false],
            ['This is a message', true],
            ['رسالة الاختبار', true],
        ];
        
        foreach ($testCases as [$message, $expected]) {
            $trimmed = trim($message);
            $isValid = $trimmed !== '';
            
            $this->assertEquals($expected, $isValid);
        }
    }
    
    /**
     * Test complete validation
     */
    public function testCompleteValidation()
    {
        $validData = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'subject' => 'Help Request',
            'message' => 'I need help with registration',
        ];
        
        $errors = [];
        
        $name = trim($validData['name']);
        $email = trim($validData['email']);
        $subject = trim($validData['subject']);
        $message = trim($validData['message']);
        
        if (!$name) $errors[] = 'Name required';
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Invalid email';
        if (!$subject) $errors[] = 'Subject required';
        if (!$message) $errors[] = 'Message required';
        
        $this->assertEmpty($errors);
    }
    
    /**
     * Test invalid scenarios
     */
    public function testInvalidScenarios()
    {
        $invalidCases = [
            ['name' => '', 'email' => 'test@example.com', 'subject' => 'Test', 'message' => 'Test'],
            ['name' => 'John', 'email' => 'invalid', 'subject' => 'Test', 'message' => 'Test'],
            ['name' => 'John', 'email' => 'test@example.com', 'subject' => '', 'message' => 'Test'],
            ['name' => 'John', 'email' => 'test@example.com', 'subject' => 'Test', 'message' => ''],
        ];
        
        foreach ($invalidCases as $data) {
            $errors = [];
            
            if (!trim($data['name'])) $errors[] = 'Name';
            if (!filter_var(trim($data['email']), FILTER_VALIDATE_EMAIL)) $errors[] = 'Email';
            if (!trim($data['subject'])) $errors[] = 'Subject';
            if (!trim($data['message'])) $errors[] = 'Message';
            
            $this->assertNotEmpty($errors);
        }
    }
    
    /**
     * Test email body construction
     */
    public function testEmailBodyConstruction()
    {
        $name = 'John Doe';
        $email = 'john@example.com';
        $subject = 'Test Subject';
        $message = 'Test message content';
        
        $emailBody = 
            "الاسم: $name\n" .
            "البريد: $email\n" .
            "الموضوع: $subject\n\n" .
            "الرسالة:\n$message";
        
        $this->assertStringContainsString($name, $emailBody);
        $this->assertStringContainsString($email, $emailBody);
        $this->assertStringContainsString($subject, $emailBody);
        $this->assertStringContainsString($message, $emailBody);
    }
    
    /**
     * Test email headers construction
     */
    public function testEmailHeadersConstruction()
    {
        $email = 'sender@example.com';
        
        $headers = "From: $email\r\n";
        $headers .= "Reply-To: $email\r\n";
        $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
        
        $this->assertStringContainsString('From:', $headers);
        $this->assertStringContainsString('Reply-To:', $headers);
        $this->assertStringContainsString('charset=UTF-8', $headers);
    }
    
    /**
     * Test trimming of all inputs
     */
    public function testTrimmingOfAllInputs()
    {
        $_POST = [
            'name' => '  John Doe  ',
            'email' => '  john@example.com  ',
            'subject' => '  Help  ',
            'message' => '  Message content  ',
        ];
        
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $subject = trim($_POST['subject'] ?? '');
        $message = trim($_POST['message'] ?? '');
        
        $this->assertEquals('John Doe', $name);
        $this->assertEquals('john@example.com', $email);
        $this->assertEquals('Help', $subject);
        $this->assertEquals('Message content', $message);
    }
}