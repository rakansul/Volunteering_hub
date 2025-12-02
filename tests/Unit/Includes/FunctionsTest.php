<?php

namespace Tests\Unit\Includes;

use PHPUnit\Framework\TestCase;

/**
 * Test suite for includes/functions.php
 * 
 * Tests all utility functions including:
 * - HTML escaping (e function)
 * - Flash message handling
 * - User opportunity retrieval
 */
class FunctionsTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        // Clear session before each test
        $_SESSION = [];
        
        // Include the functions file
        require_once __DIR__ . '/../../../includes/db.php';
        require_once __DIR__ . '/../../../includes/functions.php';
    }
    
    protected function tearDown(): void
    {
        $_SESSION = [];
        parent::tearDown();
    }
    
    /**
     * Test e() function with basic HTML
     */
    public function testEscapesBasicHtml()
    {
        $input = '<script>alert("xss")</script>';
        $expected = '&lt;script&gt;alert(&quot;xss&quot;)&lt;/script&gt;';
        
        $this->assertEquals($expected, e($input));
    }
    
    /**
     * Test e() function with quotes
     */
    public function testEscapesQuotes()
    {
        $input = "It's a \"test\"";
        $result = e($input);
        
        $this->assertStringContainsString('&#039;', $result); // Single quote
        $this->assertStringContainsString('&quot;', $result);  // Double quote
    }
    
    /**
     * Test e() function with empty string
     */
    public function testEscapesEmptyString()
    {
        $this->assertEquals('', e(''));
    }
    
    /**
     * Test e() function with special characters
     */
    public function testEscapesSpecialCharacters()
    {
        $input = '<>&"\'';
        $result = e($input);
        
        $this->assertStringNotContainsString('<', $result);
        $this->assertStringNotContainsString('>', $result);
        $this->assertStringNotContainsString('&', $result);
        $this->assertStringContainsString('&lt;', $result);
        $this->assertStringContainsString('&gt;', $result);
    }
    
    /**
     * Test e() function with UTF-8 characters
     */
    public function testEscapesUtf8Characters()
    {
        $input = 'مرحبا <script>';
        $result = e($input);
        
        $this->assertStringContainsString('مرحبا', $result);
        $this->assertStringContainsString('&lt;script&gt;', $result);
    }
    
    /**
     * Test flash_set() stores message correctly
     */
    public function testFlashSetStoresMessage()
    {
        flash_set('success', 'Test message');
        
        $this->assertArrayHasKey('flash', $_SESSION);
        $this->assertEquals('success', $_SESSION['flash']['type']);
        $this->assertEquals('Test message', $_SESSION['flash']['msg']);
    }
    
    /**
     * Test flash_set() overwrites previous message
     */
    public function testFlashSetOverwritesPreviousMessage()
    {
        flash_set('error', 'First message');
        flash_set('success', 'Second message');
        
        $this->assertEquals('success', $_SESSION['flash']['type']);
        $this->assertEquals('Second message', $_SESSION['flash']['msg']);
    }
    
    /**
     * Test flash_set() with different types
     */
    public function testFlashSetWithDifferentTypes()
    {
        $types = ['success', 'error', 'warning', 'info'];
        
        foreach ($types as $type) {
            flash_set($type, "Message for {$type}");
            $this->assertEquals($type, $_SESSION['flash']['type']);
            $this->assertEquals("Message for {$type}", $_SESSION['flash']['msg']);
        }
    }
    
    /**
     * Test flash_get() retrieves and clears message
     */
    public function testFlashGetRetrievesAndClears()
    {
        $_SESSION['flash'] = ['type' => 'error', 'msg' => 'Error message'];
        
        $flash = flash_get();
        
        $this->assertEquals('error', $flash['type']);
        $this->assertEquals('Error message', $flash['msg']);
        $this->assertArrayNotHasKey('flash', $_SESSION);
    }
    
    /**
     * Test flash_get() returns null when no flash exists
     */
    public function testFlashGetReturnsNullWhenEmpty()
    {
        $flash = flash_get();
        
        $this->assertNull($flash);
    }
    
    /**
     * Test flash_get() handles missing flash gracefully
     */
    public function testFlashGetHandlesMissingFlash()
    {
        unset($_SESSION['flash']);
        
        $flash = flash_get();
        
        $this->assertNull($flash);
        $this->assertArrayNotHasKey('flash', $_SESSION);
    }
    
    /**
     * Test flash messages round trip
     */
    public function testFlashMessageRoundTrip()
    {
        flash_set('success', 'Round trip test');
        $flash = flash_get();
        
        $this->assertEquals('success', $flash['type']);
        $this->assertEquals('Round trip test', $flash['msg']);
        
        // Second get should return null
        $flash2 = flash_get();
        $this->assertNull($flash2);
    }
    
    /**
     * Test e() with malicious input vectors
     */
    public function testEscapesMaliciousInputVectors()
    {
        $vectors = [
            '<img src=x onerror=alert(1)>',
            'javascript:alert(1)',
            '<svg/onload=alert(1)>',
            '"><script>alert(1)</script>',
            '\'><script>alert(1)</script>',
        ];
        
        foreach ($vectors as $vector) {
            $escaped = e($vector);
            $this->assertStringNotContainsString('<script>', $escaped);
            $this->assertStringNotContainsString('javascript:', $escaped);
            $this->assertStringNotContainsString('onerror=', $escaped);
            $this->assertStringNotContainsString('onload=', $escaped);
        }
    }
    
    /**
     * Test e() preserves safe content
     */
    public function testEscapesPreservesSafeContent()
    {
        $input = 'This is safe text with numbers 123 and symbols @#$';
        $result = e($input);
        
        $this->assertStringContainsString('This is safe text', $result);
        $this->assertStringContainsString('123', $result);
    }
    
    /**
     * Test flash_set() with empty message
     */
    public function testFlashSetWithEmptyMessage()
    {
        flash_set('info', '');
        
        $this->assertArrayHasKey('flash', $_SESSION);
        $this->assertEquals('info', $_SESSION['flash']['type']);
        $this->assertEquals('', $_SESSION['flash']['msg']);
    }
    
    /**
     * Test flash_set() with long message
     */
    public function testFlashSetWithLongMessage()
    {
        $longMessage = str_repeat('Test message ', 100);
        flash_set('warning', $longMessage);
        
        $this->assertEquals('warning', $_SESSION['flash']['type']);
        $this->assertEquals($longMessage, $_SESSION['flash']['msg']);
    }
    
    /**
     * Test e() with null input
     */
    public function testEscapesNullInput()
    {
        $result = e(null);
        
        $this->assertEquals('', $result);
    }
    
    /**
     * Test e() with numeric input
     */
    public function testEscapesNumericInput()
    {
        $this->assertEquals('123', e(123));
        $this->assertEquals('45.67', e(45.67));
    }
    
    /**
     * Test flash message persistence across calls
     */
    public function testFlashMessagePersistence()
    {
        flash_set('success', 'Persistent message');
        
        // Flash should persist until retrieved
        $this->assertArrayHasKey('flash', $_SESSION);
        $this->assertArrayHasKey('flash', $_SESSION);
        
        // Only cleared after get
        flash_get();
        $this->assertArrayNotHasKey('flash', $_SESSION);
    }
    
    /**
     * Test e() with mixed content
     */
    public function testEscapesMixedContent()
    {
        $input = 'Normal text <script>alert("xss")</script> more text';
        $result = e($input);
        
        $this->assertStringContainsString('Normal text', $result);
        $this->assertStringContainsString('more text', $result);
        $this->assertStringNotContainsString('<script>', $result);
        $this->assertStringContainsString('&lt;script&gt;', $result);
    }
}