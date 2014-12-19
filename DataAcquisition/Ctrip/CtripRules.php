<?php
namespace JingqubaoScript\DataAcquisition\Ctrip;

use JingqubaoScript\DataAcquisition\Rules;

class CtripRules extends Rules {
    private $questionListUrl = "http://you.ctrip.com/asks/search/p2?keywords=%E6%95%85%E5%AE%AB&type=3";
    
    public function CtripRules($ruleList){
        if(null === $ruleList) {
            $this->Rules($ruleList);
        } else {
            $ruleList = array(
                "list"=>"<\s*ul\s.*?class\s*=\"asklist\">(.*)</ul>",
                "page"=>"<\s*div\s.*?class=\"pager_v1\">(.*)</ul>",
                "title"=>"<div\s+class=\"q-title\"[^>]*>.*<h1>(.*)</h1>",
                "desc"=>"<div\s+class=\"q-info\"[^>]*>.*<div class=\"q-desc\">(.*)</div><div class=\"q-tags\">",
            );
        }
    }
    
    public function executeRule($rule, $document) {
        //
    }
    
    public function getQuestionList() {
        //
    }
    
    public function getQuestionPagesList() {
        //
    }
    
    public function getQuestion() {
        //
    }
    
    public function getTitle() {
        //
    }
    
    public function getDesc() {
        //
    }
    
    public function getAnswers() {
        //
    }
}

