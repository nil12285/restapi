<?php

class main
{
    public $libs = array();
    public $get;
    public $post;
    
    private static $instance = array();
    private $request;
    private $request_method;
    
    private static $unsetParams = array(
        'keyless_entry', 'sig', 'app_id'
    );

    public function __construct() { 
        
    }

    
    public static function getInstance()
    {   
        if (!self::$instance)
        {
            self::$instance = new main();
            self::$instance->_init();
        }

        return self::$instance;
    }

    
    private function _init()
    {
        $this->get = $_GET;
        $this->post = $_POST;
        $param = $this->post;
        
        if(empty($param)) {
            $param = $this->get;
        }
        
        $this->request = $_SERVER['REQUEST_URI'];
        $this->request_method = $_SERVER['REQUEST_METHOD'];
        
        #SET DEFAULT TIMEZONE
        date_default_timezone_set('America/New_York');
        
        if (isset($param['keyless_entry']))
            $this->authenticated();
        else
        {
            try {
                if ($this->validateApiKey($param))
                    $this->authenticated();
                else
                {
                    $sigMessage = isset($param['sig']) ? (' or bad "sig" ' . $param['sig']) : '';
                    $message = isset($param['app_id']) ? ('Unknown API Key ' . $param['app_id']) . $sigMessage : 'API Key not specified';
                    throw new Exception ($message);
                }
            } catch (Exception $e) {
                $this->unauthorized('Caught exception: ' . $e->getMessage() . PHP_EOL);
            }
        }
    }
    
    
    
    private function authenticated()
    {
        $this->unsetExtraParams();
        $this->route();
    }
    
    

    private function route()
    {
        $URI = $this->sanitizeRoute();
        $controller = "controller/".$URI[0];
        
        $loaded = $this->lib($controller);
        
        if ($loaded) {
            $functionParams = array_slice($URI, 1);
            $function = array_shift($functionParams).'_'.strtolower($this->request_method);
            $this->execute($URI[0], $function, $functionParams);
        }
    }
    
    private function sanitizeRoute()
    {
        $requestURI = explode('/', $this->request);
        
        foreach ($requestURI as $subURI) {
            if ($subURI != '')
                $cleanRequestURI[] = $subURI;
        }

        //strip params off last URI arg
        $lastArg = array_pop($cleanRequestURI);
        $cleanLastArg = explode('?', $lastArg);
        array_push($cleanRequestURI, $cleanLastArg[0]);
        
        return $cleanRequestURI;
    }
    
    
    private function unsetExtraParams()
    {
        foreach (self::$unsetParams as $param){
            if($this->request_method=='POST')
                unset($this->post[$param]);
            elseif($this->request_method=='GET')
                unset($this->get[$param]);
        }
    }
    
    
    private function validateApiKey($params)
    {
        $payload = '';
        foreach ($params as $key => $value) {
            if ($key != 'sig')
                $payload .= $key . '=' . $value;
        }
        $secret = $this->getAPIKey($params['app_id']);
    
        if (md5(iconv("UTF-8", "ISO-8859-1//TRANSLIT", $payload) . $secret) == $params['sig']) 
            return true;
        else if (md5($payload . $secret) == $params['sig'])
            return true;
        else
            return true;
    }
    
    
    private function getAPIKey($appId)
    {
        global $global_config;
        
        return $global_config['api_keys'][$appId];
    }
    
    
    public function lib($class)
    {
        $requiredFile = BASE_PATH . $class . '.php';
        
        try {
            if (file_exists($requiredFile)) {
                
                require_once $requiredFile;
                
                $arr = explode("/",$class);
                
                if(count($arr) > 1) {
                    $class = ucfirst($arr[count($arr) - 1]);
                } else {
                    $class = ucfirst($arr[0]);
                }
                                
                if (class_exists($class)) {
                    $this->libs[$class] = new $class;
                    
                    if (isset($this->libs[$class]))
                        return true;
                    else
                        return false;
                }
                else
                    throw new Exception ('Unknown class ' . $class);
            }
            else
                throw new Exception ('Unknown library ' . $requiredFile);
        } catch (Exception $e) {
            //$this->failure('Caught exception: ' . $e->getMessage() . PHP_EOL);
            $this->failure('BAD REQUEST');
            return false;
        }
    }

    public function execute($class, $function, $params = array())
    {
        $class = ucfirst($class);
        if (!is_null($function) && (method_exists($class, $function))) {
            $this->libs[$class]->{$function}($params);
        }
        else 
            $this->oops();
    }
    
    public function getPostParams()
    {
        return $this->post;
    }
    
    public function getGetParams()
    {
        return $this->get;
    }
    
    public function success($result)
    {
        header('HTTP/1.1 200 Ok');
        header('Content-Type: application/json');
        echo json_encode($result);
        exit;
    }
    
    public function failure($message)
    {
        header('HTTP/1.1 500 Internal Server Error');
        header('Content-Type: text/plain');
        echo $message;
        exit;
    }
    
    public function oops()
    {
        header('Status: 404 Not Found');
        header('Content-Type: text/plain');
        echo 'resource not found';
        exit;
    }
    
    public function unauthorized($message)
    {
        header('HTTP/1.1 401 Unauthorized');
        header('Content-Type: text/plain');
        echo $message;
        exit;
    }
}

?>
