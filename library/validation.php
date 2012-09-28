<?php


class Validation
{
    
    function __construct(){}
    
    /**
     * check if email is valid or not
     * @name valid_email
     * @param $email
     * @return bollean [true|false]
     **/
    function valid_email($email)
    {
        $regex = '/^[a-z0-9_\+-]+(\.[a-z0-9_\+-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*\.([a-z]{2,4})/';
        
        if(!empty($email) && preg_match($regex,$email)) {
            return true;
        }
        return false;
    }
    
    
    /**
     * check if phone is valid or not
     * @name valid_phone
     * @param $phone
     * @return bollean [true|false]
     **/
    function valid_phone($phone)
    {
        $regex = '/^[0-9]{3}[\-]{0,1}[0-9]{3}[\-]{0,1}[0-9]{4}/';

        if(!empty($phone) && preg_match($regex,$phone)) {
            return true;
        }
        return false;
    }
    
}