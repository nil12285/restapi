<?php  require_once SYS_PATH . 'core/model.php';

class User_model extends Model 
{    

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }
    
    
    
    /**
     * @name addUser
     * @param Array $userData;
     * @return Boolean;
     */
    function addUser($userData)
    {
        if(empty($userData))
            return false;
            
        $userData['user_created'] = date('Y-m-d H:i:s');
        $userData['last_login'] = date('Y-m-d H:i:s');
        $userData['password'] = md5($userData['password']);        

        $rs = $this->insert('Users', $userData);
        if($rs)
            return $this->db->getlastInsertId();
        else
            return $rs;
    }
    
    
    /**
     * @name getUserByEmail
     * @param String $email;
     * @return Array $res | false;
     */
    function getUserByEmail($email)
    {
        if(empty($email))
            return false;
                            
        $query = "select * from Users 
                    where `email` = '". $this->db->escape($email)."'";
        
        $result = $this->db->query($query);
        if($this->db->getAffectedRows() > 0)
            return $this->db->fetchArray($result);
        else
            return false;
    }
    
    
    /**
     * @name getUserById
     * @param Integer $user_id;
     * @return Array $res | false;
     */
    function getUserById($user_id)
    {
        if(empty($user_id))
            return false;
                            
        $query = "select user_id from Users 
                    where `user_id` = ". $this->db->escape($user_id);
        
        $result = $this->db->query($query);
        if($this->db->getAffectedRows() > 0)
            return $this->db->fetchArray($result);
        else
            return false;
    }
    
    
    /**
     * @name isUserIdExist
     * @param Integer $user_id;
     * @return Boolean;
     **/
    function isUserIdExist($user_id)
    {
        if(empty($user_id))
            return false;
        
        $query = "select user_id from Users 
                    where `user_id` = ". $this->db->escape($user_id);
        
        $result = $this->db->query($query);
        if($this->db->getAffectedRows() > 0)
            return true;
        else
            return false;
    }
    
    
    /**
     * @name getUserByEmail
     * @param String $email; String $pass
     * @return Array $user | false;
     */
    function authenticateUser($email,$password)
    {
        if(empty($email) || empty($password))
            return false;
        
        $query = "select * from Users 
                    where `email` = '". $this->db->escape($email) ."' 
                    and `password` = '". md5($this->db->escape($password)) ."'";
        
        
        $result = $this->db->query($query);
        $row = $this->db->fetchArray($result);
        
        if(!empty($row)) {
            return $row;
        }
        else 
            return false;
    }
    
}