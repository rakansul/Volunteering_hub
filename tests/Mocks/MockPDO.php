<?php

namespace Tests\Mocks;

use PDO;
use PDOStatement;

/**
 * Mock PDO class for testing database interactions
 */
class MockPDO extends PDO
{
    private $queries = [];
    private $results = [];
    private $lastInsertId = 1;
    
    public function __construct()
    {
        // Empty constructor to avoid actual database connection
    }
    
    public function prepare($statement, $options = [])
    {
        $this->queries[] = $statement;
        return new MockPDOStatement($this->results[$statement] ?? []);
    }
    
    public function setResults($statement, $results)
    {
        $this->results[$statement] = $results;
    }
    
    public function lastInsertId($name = null)
    {
        return $this->lastInsertId;
    }
    
    public function setLastInsertId($id)
    {
        $this->lastInsertId = $id;
    }
    
    public function getQueries()
    {
        return $this->queries;
    }
}

class MockPDOStatement extends PDOStatement
{
    private $results;
    private $index = 0;
    
    public function __construct($results = [])
    {
        $this->results = is_array($results) ? $results : [$results];
    }
    
    public function execute($params = null)
    {
        return true;
    }
    
    public function fetch($mode = PDO::FETCH_ASSOC, $orientation = PDO::FETCH_ORI_NEXT, $offset = 0)
    {
        if ($this->index < count($this->results)) {
            return $this->results[$this->index++];
        }
        return false;
    }
    
    public function fetchAll($mode = PDO::FETCH_ASSOC, ...$args)
    {
        return $this->results;
    }
}