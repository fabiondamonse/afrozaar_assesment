<?php

namespace app\Helper;

class Helper
{
    /**
     * Search array of objects
     *
     * @param array $arrayOfObjects
     * @param string $needle
     * @param string $pointer
     * @return array|false
     */
    public static function searchObjectArray(array $arrayOfObjects, string $needle, string $pointer){

        $filteredArray = array_filter($arrayOfObjects, function ($obj) use ($needle, $pointer) {
            return $obj->{$pointer} == $needle;
        });

        if(!empty($filteredArray)){
            return array_values($filteredArray);
        }

        return false;
    }

    /**
     * Load session from session id
     *
     * @param string $sessionID
     * @return void
     */
    public static function setSessionId(string $sessionID){

        // check if session exists, destoy before setting new one
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_destroy();
        }

        if(!empty($sessionID)){
            session_id($sessionID);
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
        }
    }

    /**
     * Start session if no session is set
     * @return void
     */
    public static function startSession(){
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }


    /**
     * Return baseurl
     *
     * @return string
     */
    public static function getBaseUrl():string
    {
        return sprintf(
            "%s://%s",
            isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https' : 'http',
            $_SERVER['SERVER_NAME']
        );
    }



}