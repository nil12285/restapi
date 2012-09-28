<?php  require_once SYS_PATH . 'core/controller.php'; 

class Contacts extends Controller {
    
    function __construct()
    {
        parent::__construct();
        $this->load('model','contact');
    }
    
    function user_contact_get()
    {
        $user_id = $this->get['user_id'];
                
        $contacts = $this->contact->getUserContacts($user_id);
                
        if($contacts) {
            foreach($contacts as $key=>$val) {
                unset($contacts[$key]['user_id']);
                unset($contacts[$key]['created']);
                unset($contacts[$key]['updated']);
            }
            $this->response(array('contacts'=>$contacts),200,"Contacts for user_id : $user_id");
        } else {
            $this->response(array(),200,'No Contact found for user_id',true);
        }
    }
    
    
    function upsert_post()
    {
        if(!empty($this->post) && !empty($this->post['phone'])) {
        
            $this->load('model','user');        
            $data = $this->post;

            if(empty($data['user_id']) || 
                $this->user->isUserIdExist($data['user_id']) == false) {
                    $this->response(array(),200,'Invalid Request',true);
            }
        
            #validate data
            $error_messages = $this->_validate_contact_data($this->post);
                    
            if(!empty($error_messages) && $error_messages !== true) {
                foreach($error_messages as $k=>$e) {
                    $em[] = array('field' => $k, 'error' => $e);
                }
                
                $this->response(array('error_messages'=>$em),200,'Fail to save contact',true);
                
            } else {            
                #check if contact_id passed
                if(isset($data['contact_id']))
                    $contact_id = $data['contact_id'];
                    
                if(!empty($contact_id) && is_numeric($contact_id) && $contact_id != 0) {
                    unset($data['contact_id']);                
                    $rs = $this->contact->updateContact($contact_id,$data);
                    if(!$rs)
                        $contact_id = false;
                } else {
                    $contact_id = $this->contact->addContact($data);
                }
    
                if($contact_id) {
                    $this->response(array('contact_id'=>$contact_id),200,'Contact saved successfully');
                } else {
                    $this->response(array(),200,'Fail to save contact',true);
                }
            }
        } else {
            $this->response(array(),200,'Invalid param',true);
        }
    }
    
    
    
    function delete_contacts_post()
    {
        $data = $this->post;
        $contact_id = $data['contact_id'];
        $user_id    = $data['user_id'];
        
        if(!empty($contact_id) || !empty($user_id)) {
            $rs = $this->contact->deleteContactById($contact_id);
            if($rs) {
                $this->response(array(),200,'Contact deleted successfully');
            }
        }
    }
    
    
    
    private function _validate_contact_data($data)
    {
        $this->load('library','validation');
        
        $error = array();
        
        if(!$this->validation->valid_phone($data['phone'])) {
            $error['phone'] = 'invalid phone';
        }
        
        if(empty($data['contact_name'])) {
            $error['contact_name'] = 'contact name is required';
        }
        
        if(empty($error))
            return true;
        
        return $error;        
    }
}    