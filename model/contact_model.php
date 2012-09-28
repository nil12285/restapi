<?php  require_once SYS_PATH . 'core/model.php';

class Contact_model extends Model 
{    
    
    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }
    
    
    /**
     * @name addContact
     * @param Array $contactData;
     * @return Boolean;
     **/
    function addContact($contactData)
    {
        if(empty($contactData) || empty($contactData['user_id']))
            return false;
            
        $contactData['created'] = date('Y-m-d H:i:s');

        $rs = $this->insert('Contacts', $contactData);
        if($rs)
            return $this->db->getlastInsertId();
        else
            return $rs;
    }
    
    
    
    /**
     * @name updateContact
     * @param integer $contact_id;
     * @param Array $contactData;
     * @return Boolean;
     **/
    function updateContact($contact_id,$contactData)
    {
        if(empty($contactData) || empty($contactData['user_id']) || empty($contact_id))
            return false;
        
        $user_id = $contactData['user_id'];
        unset($contactData['user_id']);
        
        $rs = $this->update('Contacts', $contactData, array('contact_id'=>$contact_id, 'user_id'=>$user_id));
        return $rs;
    }
    
    
    /**
     * @name getUserContacts
     * @param Integer $user_id;
     * @return Array $contacts;
     **/
    function getUserContacts($user_id)
    {
        if(empty($user_id) || !is_numeric($user_id))
            return false;                    
        
        $query = "select * from Contacts 
                    where `user_id` = '". $this->db->escape($user_id)."'";
        
        $result = $this->db->query($query);
        
        if($this->db->getAffectedRows() > 0)
            return $this->db->fetchArrayAll($result);
        else
            return false;
    }
    
    
    
    /**
     * @name getContactById
     * @param Integer $contact_id;
     * @return Array $contact;
     **/
    function getContactById($contact_id)
    {
        if(empty($contact_id) || !is_numeric($contact_id))
            return false;
            
        $contact = array();
        
        $res = $this->db->get_where('Contacts',array('contact_id'=>$contact_id));
        if($res->num_rows() > 0)
            $contact = $res->result("array");

        return $contact[0];        
    }
    
    
    /**
     * @name deleteContactById
     * @param Integer $contact_id;
     * @return Boolean;
     **/
    function deleteContactById($contact_id)
    {
        if(empty($contact_id) || !is_numeric($contact_id))
            return false;
            
        $res = $this->delete('Contacts',array('contact_id'=>$contact_id),1);
        return $res;
    }
    
    
    function syncContact(){}
    
}