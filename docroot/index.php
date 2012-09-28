<?php
    require_once('../system/core/config.php');
    /* We can use defines now */
    //require_once(PREVIOUSPATH . LIBPATH . 'helper.php');
    require_once(SYS_PATH . '/core/main.php');

    bootstrap();
    
    function bootstrap()
    {
        return main::getInstance();
    }
