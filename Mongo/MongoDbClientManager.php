<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of MongoDbClientManager
 *
 * @author hpduan
 */
//@todo 外界传入配置来修改client
//namespace Etour\Gds\Base;

//use Etour\Gds\Config\Base;
//use Etour\Gds\Config\MongoDb;

require_once __DIR__.'\Config\Base.php';
//require_once  __DIR__.'\Config\MongoDb.php';

class MongoDbClientManager
{

    static $self = null;
    public $db = null;
    public $collection = null;
    
    
    public static $mongodb = array(
        'host'=>'127.0.0.1',
        'port'=>27017,
        'dbname'=>'acquisition',
        'username'=>'',
        'password'=>''
    );
    
    private function __construct($options)
    {
        $env = Base::getEnv();
        if($options['mongodbConfig']){
            $config = $options['mongodbConfig'];
        }else{
            $config = static::$mongodb[$env];
        }
        if($env === 'production'){
            $this->db =  new \MongoClient('mongodb://etourer:etourer#$!beijing@127.0.0.1:27017');
        }else if($env === 'testing'){
            $this->db =  new \MongoClient('mongodb://etourer:etourer!#$beijing@127.0.0.1:27017');
        }else{
            $this->db =  new \MongoClient('mongodb://127.0.0.1:27017');
        }
    }

    static function getInstance($options = array())
    {
        if (null === static::$self) {
            static::$self = new MongoDbClientManager($options);
        }

        return static::$self;
    }

    public function selectDb($dbname)
    {
        return $this->db->selectDb($dbname);
    }

//    public function selectCollection($collectionName)
//    {
//        try {
//            $this->collection = $this->db->selectCollection($collectionName);
//        } catch (Exception $e) {
//            $this->collection = $this->db->createCollection($collectionName);
//        }
//        
//        return $this->collection;
//    }

    private function __clone()
    {
        
    }

    public function getMongoDb()
    {
        return $this->db;
    }
    public function getCollection()
    {
        return $this->collection;
    }
    
    public function getGridFs()
    {
        return $this->db->getGridFs();
    }

    
}

?>
