<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Base
 *
 * @author hpduan
 */



class Base
{

    static function getEnv()
    {
        $env = getenv('APPLICATION_ENV');
        return $env?$env:'development';
    }

   static function getHttpHost() {
        $env = static::getEnv();
        $httpHost = '';
        $urlPrefix = 'http://';
        if($_SERVER['HTTPS'] === 'on'){
            $urlPrefix = 'https://';
        }
        switch ($env) {
            case 'production':
                $httpHost = $urlPrefix.'tangseng.totalgds.com';
                break;
            case 'testing':
                $httpHost = $urlPrefix.'wukong.totalgds.com';
                break;
            default :
                $httpHost = $urlPrefix.'tangseng.totalgds.com';
                break;
        }
        return $httpHost;
    }
    
    static function getPosHost(){
        $env = static::getEnv();
        $httpHost = '';
        $urlPrefix = 'http://';
        if($_SERVER['HTTPS'] === 'on'){
            $urlPrefix = 'https://';
        }
        switch ($env) {
            case 'production':
                $httpHost = $urlPrefix.'pos.totalgds.com';
                break;
            case 'testing':
                $httpHost = $urlPrefix.'wukong.totalgds.com:8001';
                break;
            default :
                $httpHost = $urlPrefix.'pos.totalgds.com';
                break;
        }
        return $httpHost;
    }
    
    public static function isDevelopment(){
        if(static::getEnv() === 'development'){
            return true;
        }
        return false;
    }
    
    public static function isTesting(){
        if(static::getEnv() === 'testing'){
            return true;
        }
        return false;
    }
    
    public static function isProduction(){
        if(static::getEnv() === 'production'){
            return true;
        }
        return false;
    }
}

?>
