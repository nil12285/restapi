<?php

class Model
{
    protected $db;
    
    function __construct()
    {
        $this->init();            
    }
    
    
    private function init()
    {
        global $global_config;
        $primaryDB = $global_config['database'];
        
        $dbClass = 'Db'.$primaryDB['driver'];
        
        require_once SYS_PATH . 'db/db_' .strtolower($primaryDB['driver']).'.php';
        $this->db = new $dbClass;
        
    }
    
    /**
     * @name insert
     * @param $table=$table_name; $data = array()
     * @return boolean [true|false]
     **/
    function insert($table,$data)
    {
        if(empty($table) || empty($data)){return false;}
        $query = "INSERT INTO $table SET ";
        $vQ = '';
        foreach($data as $field=>$value){
            if(!empty($vQ)){$vQ .= ', ';}
            $vQ .= " `$field` = '".$this->db->escape($value)."' ";
        }
        $query .= $vQ;
        
        return $this->db->query($query);
    }
    
    
    /**
     * @name update
     * @param $table=$table_name; $data = array(); $where=array|string; $limit=integer;
     * @return boolean [true|false];
     **/
    function update($table,$data,$where=array(),$limit='')
    {
        if(empty($table) || empty($data) || empty($where)){return false;}
        $query = "UPDATE $table SET ";
        $vQ = $wStr = '';
        foreach($data as $field=>$value){
            if(!empty($vQ)){$vQ .= ', ';}
            $vQ .= " `$field` = '".$this->db->escape($value)."' ";
        }
        $query .= $vQ;
        
        if(!is_array($where))
            $wStr = $where;
        else{
            foreach($where as $field=>$value){
                if(!empty($wStr)){$wStr .= ' AND ';}
                    $wStr .= " `$field` = '".$this->db->escape($value)."' ";    
            }
        }
        $query .= 'WHERE '.$wStr;
        if(!empty($limit)){
            $query .= 'LIMIT '.$limit;
        }
        
        return $this->db->query($query);
    }
    
    
    function delete($table,$where,$limit='')
    {
        if(empty($table) || empty($where)){return false;}
        
        $query = "Delete from $table ";
        $wStr = '';
        
        if(!is_array($where))
            $wStr = $where;
        else{
            foreach($where as $field=>$value){
                if(!empty($wStr)){$wStr .= ' AND ';}
                    $wStr .= " `$field` = '".$this->db->escape($value)."' ";    
            }
        }
        $query .= 'WHERE '.$wStr;
        if(!empty($limit)){
            $query .= 'LIMIT '.$limit;
        }
        
        return $this->db->query($query);
    }
}