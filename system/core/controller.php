<?php
/**
 * Application Base Controller Class
 **/
class Controller
{
    private static $instance;
    private $main;
    
    protected $loader   = array();
    protected $get      = array();
    protected $post     = array();    
    protected $ouput_format;
    protected $_supported_formats = array(		
		'json' => 'application/json'		
	);
        
    function __construct(){
        self::$instance =& $this;
        $this->init();
    }
    
    private function init()
    {
        global $global_config;
        $this->main = main::getInstance();
        $this->get = $this->main->get;
        $this->post = $this->main->post;
        $this->ouput_format = $global_config['REST']['output_format']; 
    }
    
    /**
     * load class
     * @param $class_type = model|library; $class = class_name
     **/
    function load($class_type,$class)
    {
        if(empty($class_type) || empty($class)) {return;}
        $class_type = strtolower($class_type);
        
        if(isset($this->$class)) {
            return;
        } else {
            if(isset($this->loader[$class_type]->$class)){
                $this->$class = $this->loader[$class_type]->$class;
            } else{ //load class
                switch($class_type) {
                    case 'model':
                        $class_name = $class.'_model';
                        $this->main->lib($class_type.'/'.$class_name);                        
                    break;
                    
                    case 'library':
                        $class_name = $class;
                        $this->main->lib($class_type.'/'.$class);                        
                    break;
                    
                    default:
                        $class_name = $class;
                }
                
                if(isset($this->main->libs[ucfirst($class_name)])) {
                    $this->$class = $this->loader[$class_type]->$class = $this->main->libs[ucfirst($class_name)];
                }
            }
        }
    }
    
    
    /**
     * Takes pure data and optionally a status code, then creates the response
     * @name response
     * @param $data = array(); $http_code = '202'|'200'; 
     * @param $message = 'notification'; $error='error_message'
	 **/
	public function response($data = array(), $http_code = null, $message='', $error=false)
	{
		if (empty($data) && $http_code === null) {
    		$http_code = 404;
    		$output = $data;
    	} else {
    	    if($error)
                $data['status'] = 'error';
            else
                $data['status'] = 'ok';
                
            if($message != ''){
                $data['notification'] = $message;
            }
            			
			is_numeric($http_code) OR $http_code = 200;

			if (method_exists($this, '_format_'.$this->ouput_format)) {
				// Set the correct format header
				header('Content-Type: '.$this->_supported_formats[$this->ouput_format]);
				$output = $this->{'_format_'.$this->ouput_format}($data);
			}
			else {
                header('Content-Type: '.$this->_supported_formats['json']);
				$output = $this->{'_format_json'}($data);
			}
		}

		header('HTTP/1.1: ' . $http_code);
		header('Status: ' . $http_code);
		header('Content-Length: ' . strlen($output));

		exit($output);
	}
    
    
    protected function _format_json($data = array())
	{
		return  json_encode(array('response' => $data));
	}

}

function printr($data,$die=true)
{
    echo "<pre>";
    print_r($data);
    echo "</pre>";
    if($die)
        exit;
}