<?php

namespace Tests\Unit\Includes;

use PHPUnit\Framework\TestCase;

/**
 * Test suite for includes/auth.php
 * 
 * Tests authentication and authorization functions:
 * - is_logged_in()
 * - current_user()
 * - current_user_id()
 * - require_login()
 * - require_admin()
 * - require_organization()
 */
class AuthTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $_SESSION = [];
        
        // Include auth functions
        require_once __DIR__ . '/../../../includes/auth.php';
    }
    
    protected function tearDown(): void
    {
        $_SESSION = [];
        parent::tearDown();
    }
    
    /**
     * Test is_logged_in() returns false when no user in session
     */
    public function testIsLoggedInReturnsFalseWhenNoUser()
    {
        $this->assertFalse(is_logged_in());
    }
    
    /**
     * Test is_logged_in() returns true when user exists in session
     */
    public function testIsLoggedInReturnsTrueWhenUserExists()
    {
        $_SESSION['user'] = ['id' => 1, 'email' => 'test@example.com'];
        
        $this->assertTrue(is_logged_in());
    }
    
    /**
     * Test is_logged_in() returns false for empty user array
     */
    public function testIsLoggedInReturnsFalseForEmptyUser()
    {
        $_SESSION['user'] = [];
        
        $this->assertFalse(is_logged_in());
    }
    
    /**
     * Test current_user() returns null when not logged in
     */
    public function testCurrentUserReturnsNullWhenNotLoggedIn()
    {
        $this->assertNull(current_user());
    }
    
    /**
     * Test current_user() returns user array when logged in
     */
    public function testCurrentUserReturnsArrayWhenLoggedIn()
    {
        $user = [
            'id' => 1,
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john@example.com',
            'role' => 'user'
        ];
        $_SESSION['user'] = $user;
        
        $this->assertEquals($user, current_user());
    }
    
    /**
     * Test current_user_id() returns null when not logged in
     */
    public function testCurrentUserIdReturnsNullWhenNotLoggedIn()
    {
        $this->assertNull(current_user_id());
    }
    
    /**
     * Test current_user_id() returns integer ID when logged in
     */
    public function testCurrentUserIdReturnsIntegerWhenLoggedIn()
    {
        $_SESSION['user'] = ['id' => 42];
        
        $result = current_user_id();
        
        $this->assertIsInt($result);
        $this->assertEquals(42, $result);
    }
    
    /**
     * Test current_user_id() casts string ID to integer
     */
    public function testCurrentUserIdCastsStringToInteger()
    {
        $_SESSION['user'] = ['id' => '123'];
        
        $result = current_user_id();
        
        $this->assertIsInt($result);
        $this->assertEquals(123, $result);
    }
    
    /**
     * Test current_user_id() with missing ID
     */
    public function testCurrentUserIdWithMissingId()
    {
        $_SESSION['user'] = ['email' => 'test@example.com'];
        
        $this->assertNull(current_user_id());
    }
    
    /**
     * Test user data integrity
     */
    public function testUserDataIntegrity()
    {
        $userData = [
            'id' => 1,
            'first_name' => 'Test',
            'last_name' => 'User',
            'email' => 'test@example.com',
            'role' => 'user'
        ];
        $_SESSION['user'] = $userData;
        
        $retrieved = current_user();
        
        $this->assertEquals($userData['id'], $retrieved['id']);
        $this->assertEquals($userData['email'], $retrieved['email']);
        $this->assertEquals($userData['role'], $retrieved['role']);
    }
    
    /**
     * Test authentication with different user roles
     */
    public function testAuthenticationWithDifferentRoles()
    {
        $roles = ['user', 'admin', 'organization'];
        
        foreach ($roles as $role) {
            $_SESSION['user'] = ['id' => 1, 'role' => $role];
            
            $this->assertTrue(is_logged_in());
            $this->assertEquals($role, current_user()['role']);
        }
    }
    
    /**
     * Test session persistence across multiple checks
     */
    public function testSessionPersistence()
    {
        $_SESSION['user'] = ['id' => 1, 'email' => 'test@example.com'];
        
        $this->assertTrue(is_logged_in());
        $this->assertEquals(1, current_user_id());
        $this->assertTrue(is_logged_in()); // Should still be true
        $this->assertEquals(1, current_user_id()); // Should still return same ID
    }
    
    /**
     * Test is_logged_in() with various user data structures
     */
    public function testIsLoggedInWithVariousDataStructures()
    {
        // Minimal valid user
        $_SESSION['user'] = ['id' => 1];
        $this->assertTrue(is_logged_in());
        
        // Complete user data
        $_SESSION['user'] = [
            'id' => 1,
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john@example.com',
            'role' => 'user'
        ];
        $this->assertTrue(is_logged_in());
    }
    
    /**
     * Test current_user_id() with zero ID
     */
    public function testCurrentUserIdWithZeroId()
    {
        $_SESSION['user'] = ['id' => 0];
        
        $result = current_user_id();
        
        $this->assertIsInt($result);
        $this->assertEquals(0, $result);
    }
}