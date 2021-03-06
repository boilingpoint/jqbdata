<?php
//namespace JingqubaoScript\DataAcquisition\Ly;
//
//use JingqubaoScript\DataAcquisition\Rules;

require_once __DIR__."/../Rules.php";
require_once __DIR__."/LyDocument.php";
require_once __DIR__."/LyService.php";

class LyRules extends Rules {
    private $scenicListUrl = "http://www.ly.com/scenery/BookSceneryTicket_{pid}.html";
    //<div class="positionAddress">景点地址：<span>陕西省西安市蓝田县蓝桥镇</span></div>
    private $scenicNameRegStr = "'<\s*h1\s.*?class=\"scenicName\"[^>]*?>(.*?)</h1>'isx";
    private $scenicLevelRegStr = "'<\s*span\s.*?class\s*=\s*\"scenicStar\">.*?<b>(.*?)</b>'isx";
    private $scenicAddressRegStr = "'<\s*div\s.*?class\s*=\s*\"positionAddress\">.*?<span>(.*?)</span>'isx";
    private $scenicZipcodeRegStr = "'<\s*div\s.*?class\s*=\s*\"positionAddress\">.*?<span>.*?邮编[:：]([0-9\-\—]*?)]'isx";
    private $scenicPhoneRegStr = "'<\s*div\s.*?class\s*=\s*\"positionAddress\">.*?<span>.*?电话[:：]([0-9\-\—]*?)]'isx";
    private $scenicFaxRegStr = "'<\s*div\s.*?class\s*=\s*\"positionAddress\">.*?<span>.*?传真[:：]([0-9\-\—]*?)]'isx";
    private $scenicIntroRegStr = "'<\s*div\s.*?class\s*=\"left_con\">(.*?)</div>'isx";
    private $startId = 34;
    private $endId = 32638;
    
    public function __construct() {
        parent::__construct();
        //34
        //32638
    }
    
    
    public function getScenicList() {
        for($i=$this->startId;$i<=$this->endId;$i++) {
            $scenicUrl = str_replace("{pid}", $i, $this->scenicListUrl);
            $this->snoopyObj->fetch($scenicUrl);
            
            $document = $this->snoopyObj->getResults();
            $scenic = array(
                'ScenicId'=>$i,
                'Name'=>$this->getScenicName($document),
                'Level'=>$this->getScenicLevel($document),
                'Address'=>$this->getAddress($document),
//                'Zipcode'=>$this->getZipcode($document),
//                'Phone'=>$this->getPhone($document),
//                'Fax'=>$this->getFax($document),
                'Intro'=>$this->getIntro($document)
            );
            $scenicList[] = $scenic;
            $scenicDocument = new LyDocument($scenic);
            LyService::saveScenic($scenicDocument);
            sleep(1);
        }
        
        return $scenicList;
    }
    
    public function getScenicName($document) {        
        preg_match($this->scenicNameRegStr, $document, $contents);
        if(is_array($contents) && count($contents) > 1) {
            return $this->_fetchStripText($contents[1]);
        }
        return "";
    }
    
    public function getScenicLevel($document) {
        preg_match($this->scenicLevelRegStr, $document, $contents);
        if(is_array($contents) && count($contents) > 1) {
            return $this->_fetchStripText($contents[1]);
        }
        return "";
    }
    
    public function getAddress($document) {
        preg_match($this->scenicAddressRegStr, $document, $contents);
        if(is_array($contents) && count($contents) > 1) {
            return $this->_fetchStripText($contents[1]);
        }
        return "";
    }
    
    public function getZipcode($document) {
        preg_match($this->scenicZipcodeRegStr, $document, $contents);
        if(is_array($contents) && count($contents) > 1) {
            return $this->_fetchStripText($contents[1]);
        }
        return "";
    }
    
    public function getPhone($document) {
        preg_match($this->scenicPhoneRegStr, $document, $contents);
        if(is_array($contents) && count($contents) > 1) {
            return $this->_fetchStripText($contents[1]);
        }
        return "";
    }
    
    public function getFax($document) {
        preg_match($this->scenicFaxRegStr, $document, $contents);
        if(is_array($contents) && count($contents) > 1) {
            return $this->_fetchStripText($contents[1]);
        }
        return "";
    }
    
    public function getIntro($document) {
        preg_match($this->scenicIntroRegStr, $document, $contents);
        if(is_array($contents) && count($contents) > 1) {
            return $contents[1];
        }
        return "";
    }
}

