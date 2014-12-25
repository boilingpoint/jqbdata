<?php

require_once __DIR__."/../../Mongo/MongoDbService.php";
class LyService extends MongoDbService 
{
    protected static $db = 'acquisition';
    protected static $collection = 'scenic';
    
    
    public static function saveScenic($scenicDocument)
    {
        $code = parent::addDocument($scenicDocument);
        $scenicDocument->Code = $code;
        
        
        return $code;
    }
    
    public static function setScenic($where, $keyName, $document) {
        $result = @parent::update($where, array('$set'=>array($keyName=>$document)));
    }
    
    public static function getQuestions($where, $sort, $fields = array(), $start = 0, $limit = 10) {
        
        $ret = self::fetchAll($where, $fields)->sort($sort)->skip($start)->limit($limit);
        if($ret == null) {
            $total = 0;
            $plans = array();
        }else {
            $total = $ret->count();
            $plans = $ret->export();
        }
        return array(
          'total' => $total,
          'start' => $start,
          'limit' => $limit,
          'plans' => $plans
        );
    }
}
