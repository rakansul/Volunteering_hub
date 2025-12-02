# Volunteering Hub Test Suite

## Overview
This test suite provides comprehensive unit and integration tests for the Volunteering Hub application.

## Installation

1. Install PHPUnit via Composer:
```bash
composer install
```

## Running Tests

Run all tests:
```bash
vendor/bin/phpunit
```

Run specific test suite:
```bash
vendor/bin/phpunit tests/Unit
vendor/bin/phpunit tests/Integration
```

Run specific test file:
```bash
vendor/bin/phpunit tests/Unit/Includes/FunctionsTest.php
```

Run with coverage report:
```bash
vendor/bin/phpunit --coverage-html coverage
```

## Test Structure

- `tests/Unit/` - Unit tests for individual functions and classes
  - `Includes/` - Tests for utility functions and authentication
  - `Actions/` - Tests for action handlers (login, register, etc.)
  - `Admin/` - Tests for admin functionality
- `tests/Integration/` - Integration tests (database interactions, etc.)
- `tests/Mocks/` - Mock objects for testing

## Test Coverage

The test suite covers:
- Input validation
- Authentication and authorization
- Session management
- Database operations (with mocks)
- Error handling
- Edge cases and boundary conditions
- Security (XSS prevention, SQL injection prevention)

## Best Practices

1. Each test should be independent and isolated
2. Use descriptive test method names
3. Test both happy paths and error conditions
4. Mock external dependencies
5. Clean up after tests (tearDown)
6. Use assertions effectively

## Writing New Tests

When adding new functionality:
1. Write tests first (TDD approach recommended)
2. Follow existing test patterns
3. Ensure tests are isolated and repeatable
4. Add tests to appropriate directory
5. Update this README if adding new test categories

## Test Summary

### Unit Tests
- **FunctionsTest.php** (22 tests) - HTML escaping, flash messages, XSS prevention
- **AuthTest.php** (14 tests) - Authentication, session management, role handling
- **LoginActionTest.php** (10 tests) - Login validation, password verification
- **RegisterActionTest.php** (12 tests) - Registration validation, password strength
- **EditProfileActionTest.php** (9 tests) - Profile updates, optional password changes
- **RegisterForEventTest.php** (8 tests) - Event registration, ID validation
- **CancelRegistrationTest.php** (6 tests) - Registration cancellation, ownership
- **ContactActionTest.php** (10 tests) - Contact form validation, email construction
- **CreateOpportunityActionTest.php** (9 tests) - Opportunity creation validation
- **UpdateOpportunityActionTest.php** (4 tests) - Opportunity update validation
- **DeleteOpportunityTest.php** (4 tests) - Opportunity deletion validation

**Total: 108 comprehensive unit tests**

## Coverage Highlights

✅ **Security**: XSS prevention, SQL injection guards  
✅ **Validation**: Email, passwords, dates, numbers  
✅ **Edge Cases**: Empty strings, null values, negative numbers  
✅ **UTF-8**: Arabic text handling throughout  
✅ **Type Safety**: Integer casting, type checking  
✅ **Business Logic**: Registration flows, CRUD operations  
✅ **Error Handling**: Invalid inputs, missing data  
✅ **Session Management**: Login/logout, role-based access