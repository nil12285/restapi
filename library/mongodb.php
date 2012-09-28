<?php
/**
 * @name mongo
 * @todo add index functions, and make it part of framework
 * @version 1.0
 * @author Neelay
 */
 
class mongodb
{
    private $dbConfig; 
    private $conn;
    
    function __construct()
    {
        $this->__setConfig();
        $this->__open();
    }

    private function __setConfig()
    {
        global $mongoPrimary;

        $this->dbConfig['host']       = $mongoPrimary['host'];
        $this->dbConfig['port']       = $mongoPrimary['port'];
        $this->dbConfig['user']       = $mongoPrimary['user'];
        $this->dbConfig['password']   = $mongoPrimary['password'];
        $this->dbConfig['database']   = $mongoPrimary['database'];
    }

    private function __open()
    {
        $connString = 'mongodb://'.$this->dbConfig['user'].':'.$this->dbConfig['password'].'@'.$this->dbConfig['host'].':'.$this->dbConfig['port'].'/'.$this->dbConfig['database'];
        
        try {
            return $this->conn = new Mongo($connString);
        }
        catch (MongoConnectionException $e) {
            $this->__sendException(array(), $this->dbConfig, array(), array(), array('Error connecting to MongoDB server'));
        } catch (MongoException $e) {
            $this->__sendException(array(), $this->dbConfig, array(), array(), array($e->getMessage()));
        }
    }
    
    /**
     * @name fetch
     * @param $collection=table_name,$where=array('deal_id'=>123245)
     * @return single row in array formate
     */
    public function fetch($collection, $where = array())
    {
        $mCollection = $this->conn->kdbigdata->$collection;
        $result = $mCollection->findOne($where);
        return json_encode($result);
    }
    
    /**
     * @name fetch
     * @param $collection=table_name,$where=array('deal_id'=>123245)
     * @return single row in array formate
     */
    public function fetchAll($collection, $where = array(), $sort = array(), $limit = null, $skip = null)
    {
        $mCollection = $this->conn->kdbigdata->$collection;
        $cursor = $mCollection->find($where);
        
        if ($sort)
            $cursor->sort($sort);
                
        if ($limit)
            $cursor->limit($limit);
        
        if ($skip)
            $cursor->skip($skip);     
                
        foreach ($cursor as $id => $value)
            $result[] = $value; 
        
        return $result;
    }
    
    public function count($collection, $where = array())
    {
        if (empty($collection))
            return false;
        
        $mCollection = $this->conn->kdbigdata->$collection;
        
        return $mCollection->count();
    }
    
    public function insert($collection, $data = array(), $extra = array())    
    {
        if (empty($data) || empty($collection))
            return false;
        
        return $this->__insert($collection, $data); 
    }
    
    public function update($collection, $data = array(), $where = array(), $extra = array())
    {
        if (empty($data) || empty($collection))
            return false;
        
        return $this->__update($collection, $data, $where, $extra);
    }
    
    public function upsert($collection, $data = array(), $where = array(), $extra = array())
    {
        if (empty($data) || empty($collection))
            return false;
        
        $extra['upsert'] = true;
        return $this->__update($collection, $data, $where, $extra);
    }
    
    public function delete($collection, $where = array(), $extra = array())
    {
        if (empty($collection))
            return false;
        
        $mCollection = $this->conn->kdbigdata->$collection;
        
        if (count($where) == 1 && !empty($where['_id']))
            $query = array('_id' => new MongoId($where['_id']));
        else
            $query = $where;
        
        return $this->__delete($collection,$query,$extra);
    }
    
    private function __insert(&$collection, $data)
    {      
        try {
            $mCollection = $this->conn->kdbigdata->$collection;
            
            return $mCollection->insert($data);
        } catch(MongoCursorException $e) {
            $this->__sendException($collection, $data, array('insert query'), $extra, $e);
        }
    }
    
    private function __update(&$collection, $data, $where, $extra)
    {
        try {
            $mCollection = $this->conn->kdbigdata->$collection;
            
            return $mCollection->update($where, $data, $extra);
        } catch(MongoCursorException $e) {
            $this->__sendException($collection, $data, $where, $extra, $e);
        }
    }
    
    private function __delete(&$collection, $where, $extra)
    {
        if (empty($extra))
            $extra = array("justOne" => true);
        
        try {
            $mCollection = $this->conn->kdbigdata->$collection;
            
            return $mCollection->remove($where, $extra);
        } catch(MongoCursorException $e) {
            $this->__sendException($collection, array('delete query'), $where, $extra, $e);
        }
        
    }
    
    private function __sendException($collection = array(), $data = array(), $where = array(), $extra = array(), $e = array())
    {
        /*notify_devs("MongoDB Error:  @ {".date()."}", 
                    "IP:\n" .  shell_exec('hostname --ip-address') . "\n\n" .
                    "collection:\n"  . $collection . "\n\n" .
                    "data:\n" . print_r($data, true) . "\n\n" .
                    "where:" . print_r($where, true) . "\n\n" .
                    "extra:" . print_r($extra, true) . "\n\n" .
                    "exception:" . print_r($e, true) . "\n\n" .
                    "\$_SERVER contents:\n" .  print_r($_SERVER, true) . "\n\n" .
                    "\$_REQUEST contents:\n" .  print_r($_REQUEST, true)  . "\n\n"
                );
        */
    }
    
}
?>
