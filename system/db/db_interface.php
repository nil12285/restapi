<?php
/**
 * An interface for db classes
 */
interface DbInterface
{
    /**
     * Connect to DB and return handler
     */
    public function connect();
    
    /**
     * Escape for sql injection
     *
     * @param string $string
     * @return escaped string
     */
    public function escape($string);
    
    /**
     * Query (or execute) function
     *
     * @param string $query
     * @param array $args (optional)
     */
    public function query($query);
    
    /**
     * fetch a result row as an associative array
     *
     * @param mixed $result ex: mysqli_result from parent::query()
     */
    public function fetchArray($result = false);
    
    /**
     * fetch all results as an associative
     *
     * @param mixed $result ex: mysqli_result from parent::query()
     */
    public function fetchArrayAll($result = false);    
    
    /**
     * Returns affected rows
     */
    public function getAffectedRows();
    
    /**
     * Returns last insert id
     */
    public function getlastInsertId();
    
    /**
     * a function that must return an array containg:
     *      number, 
     *      message 
     * whenever there is an error
     * else return false when no errors     
     */
    public function getError();  

}
