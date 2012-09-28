<?php require_once 'db_interface.php';

class Dbmysqli extends Mysqli implements DbInterface
{
    private $hostname;
    private $username;
    private $password;
    private $database;
    private $port;
    private $connection;
    
    function __construct()
    {
        global $global_config;
        $primaryDB = $global_config['database'];
        
        $this->hostname = $primaryDB['hostname'];
        $this->username = $primaryDB['username'];
        $this->password = $primaryDB['password'];
        $this->database = $primaryDB['database'];
        $this->port     = $primaryDB['port'];
        
        if(!isset($this->port)) {$this->port = '3306';}
        
        $this->connect();
    }
    
        
    /**
     * Connect to DB and return handler
     */
    public function connect()
    {
        if(!isset($this->connection)) {
            $this->connection = new mysqli($this->hostname,$this->username,$this->password,$this->database,$this->port);

            if($this->connection->error) {
                throw new Exception('Connect Error (' . mysqli_connect_errno() . ') ' . mysqli_connect_error() . PHP_EOL);
            }
        }
    }
    
    /**
     * Escape for sql injection
     *
     * @param string $string
     * @return escaped string
     */
    public function escape($string) {
        return mysqli_real_escape_string($this->connection,$string);
    }
    
    /**
     * Query (or execute) function
     *
     * @param string $query
     * @param array $args (optional)
     */
    public function query($query) {        
        $res = mysqli_query($this->connection,$query);
        if($this->connection->errno > 0) {
            throw new Exception('SQL Error : ' . $this->connection->error . PHP_EOL);
            return false;            
        }            
        return $res;        
    }
    
    /**
     * fetch a result row as an associative array
     *
     * @param mixed $result ex: mysqli_result from parent::query()
     */
    public function fetchArray($result = false) {
        if(is_object($result))
            return mysqli_fetch_array($result,MYSQLI_ASSOC);
            
        return false;
    }    
    
    /**
     * fetch all results as an associative
     *
     * @param mixed $result ex: mysqli_result from parent::query()
     */
    public function fetchArrayAll($result = false) {
        $rows = array();
        if (is_object($result)) {
            while($row = mysqli_fetch_assoc($result)) {
                $rows[] = $row;
            }
            return $rows;
        }
        
        return false;
    }    
    
    /**
     * Returns affected rows
     */
    public function getAffectedRows() {
        return $this->connection->affected_rows;
    }
    
    /**
     * Returns last insert id
     */
    public function getlastInsertId() {
        return $this->connection->insert_id;
    }
    
    /**
     * a function that must return an array containg:
     *      number, 
     *      message 
     * whenever there is an error
     * else return false when no errors     
     */
    public function getError() {
        return $this->connection->error;
    }
}