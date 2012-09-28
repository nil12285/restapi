<?php

    $ini = parse_ini_file(__DIR__.'/../../config/config.ini', true);

    // Set the current directory correctly for CLI requests
	

    define('LIB_PATH', $ini['general']['lib_path']);
    define('SYS_PATH', __DIR__.'/../');
    define('BASE_PATH', __DIR__.'/../../');
    define('MODEL_PATH', BASE_PATH.$ini['general']['model_path']);
        
    $global_config = array();
    
    foreach($ini as $key=>$val) {
        $global_config[$key] = $val;
    }