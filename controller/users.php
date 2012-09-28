<?php require_once SYS_PATH . 'core/controller.php';

class Users extends Controller 
{
    
    private $main;
    
    function __construct(){
        parent::__construct();
        
        #Get User Object [$this->user]
        $this->load('model','user');
    }
    
    public function register_post()
    {
        
        if(!empty($this->post) && !empty($this->post['email'])) {
            
                #validate data                
                $error_messages = $this->_validate_user_data($this->post);
                
                if(!empty($error_messages) && $error_messages !== true) {                    
                    foreach($error_messages as $k=>$e) {
                        $em[] = array('field' => $k, 'error' => $e); 
                    }
                    
                    $this->response(array('error_messages'=>$em),200,'Registration Fail',true);
                    
                } else {
                    $isUser = $this->user->getUserByEmail($this->post['email']);
                        
                    if(empty($isUser)) {
                        
                        unset($this->post['passconf']);
                        
                        #add User                    
                        $user_id = $this->user->addUser($this->post);
                                            
                        if($user_id) {
                            $this->response(array('user_id'=>$user_id),200,'Thanks you are registered');
                        } else {                    
                            $this->response(array(),200,'Registration Fail',true);
                        }
                    } else {
                        $em[] = array(
                                        'field'=>'email',
                                        'error'=>'This e-mail address already exists in system'
                                    );
                                        
                        $this->response(array('error_messages'=>$em),200,'Registration Fail',true);
                    }
                }            
                        
        } else {            
            $this->response(array(),200,'Invalid param',true);
        }        
    }
    
    
    public function login_post()
    {        
        $email      = $this->post['email'];
        $password   = $this->post['password'];        
        
        $user = $this->user->authenticateUser($email,$password);
        
        if(!empty($user)) {
            
            foreach($user as $k=>$v) {
                $um[] = array('field' => $k, 'value' => $v); 
            }
            
            $this->response(array('user'=>$um),200,'Login Success');
            
        } else {
            $em[] = array(
                            'field'=>'email',
                            'error'=>'the email or password you entered is incorrect'
                        );
            $em[] = array(
                            'field'=>'password',
                            'error'=>'the email or password you entered is incorrect'
                        );
                        
            $this->response(array('error_messages'=>$em),200,'the email or password you entered is incorrect',true);
        }
    }
    
    
    private function _validate_user_data($data)
    {
        $this->load('library','validation');
        $error = array();
        
        if(!$this->validation->valid_email($data['email'])) {
            $error[]['field'] = 'email';
            $error[]['error'] = 'invalid email';
        }
        
        if(empty($data['first_name'])) {
            $error[]['field'] = 'first_name';
            $error[]['error'] = 'first name is required';
        }
        
        if(empty($data['last_name'])) {
            $error[]['field'] = 'last_name';
            $error[]['error'] = 'last name is required';
        }
        
        if(empty($error))
            return true;
        
        return $error;        
    }
    
    
}