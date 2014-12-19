<?php

namespace \JingqubaoScript\DataAcquisition\Rules;

class MafengwoRules extends Rules {
    private $ruleList;
    public function MafengwoRules($ruleList){
        $ruleList = array(
            "title"=>"<div\s+class=\"q-title\"[^>]*>.*<h1>(.*)</h1>",
            "desc"=>"<div\s+class=\"q-info\"[^>]*>.*<div class=\"q-desc\">(.*)</div><div class=\"q-tags\">",
        );
    }
    
    public function getRules() {
        return $ruleList;
    }
    
    public $questionListRule;
    public function getQuestionListRule() {
        return "";
    }
    public $questionTitleRule;
    public function getQuestionTitleRule() {
        /*
        <div class="q-title">
                <h1>想问下，龙泉上垟可以住宿吗？披云山庄的商务宾馆，我在网上怎么找都没找到呢。</h1>
                <span class="reward"><i></i>2000</span>
                            </div>
         * 
         */
        return "<div\s+class=\"q-title\"[^>]*>.*<h1>(.*)</h1>";
    }
    
    public $questionContentRule;
    public function getQuestionContentRule() {
        return "<div\s+class=\"q-info\"[^>]*>.*<div class=\"q-desc\">(.*)</div><div class=\"q-tags\">";
    }
    
    
}

