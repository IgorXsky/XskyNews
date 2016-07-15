<?php

class Session{

    protected static $flash_message;

    public static function setFlash($message){
        $_SESSION['flash_message'] = $message;
    }

    public static function hasFlash(){
        return   isset($_SESSION['flash_message']) && !is_null($_SESSION['flash_message']);
    }

    public static function flash(){
        echo ($_SESSION['flash_message']);
        $_SESSION['flash_message'] = null;
    }

    public static function set($key, $value){
        $_SESSION[$key] = $value;
    }

    public static function get($key){
        if ( isset($_SESSION[$key]) ){
            return $_SESSION[$key];
        }
        return null;
    }

    public static function delete($key){
        if ( isset($_SESSION[$key]) ){
            unset($_SESSION[$key]);
        }
    }

    public static function destroy(){
        session_destroy();
    }

}