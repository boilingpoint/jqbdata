<?php
namespace JingqubaoScript\DataAcquisition\Ctrip;

use JingqubaoScript\DataAcquisition\Rules;

class CtripRules extends Rules {
    private $questionListUrl = "http://you.ctrip.com/asks/search/p{page}?keywords={keyword}&type=1";
    private $questionListRegStr = "<\s*ul\s.*?class\s*=\"asklist\">(.*)</ul>";
    private $questionPageRegStr = "'<\s*div\s.*?class\s*=\s*\"pager_v1\">(.*)</ul>'isx";
    private $questionTitleRegStr = "<\s*h1\s.*?class\s*=\s*\"ask_title\">(.*)</h1>";
    private $questionDescRegStr = "<\s*p\s.*?id=\"host_asktext\"\s.*?class=\"ask_text\">(.*)</p>";
    private $questionAnswersRegStr = "<\s*ul\s.*?class\s*=\"otheranswer_con\">(.*)</ul>";
    
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
    
    public function getQuestionList($keyword) {
        $questionPageList = $this->getQuestionPagesList($keyword);
        foreach($questionPageList as $pageUrl) {
            $this->snoopyObj->fetch($pageUrl);
            $document = $this->snoopyObj->getResults();
            preg_match_all($this->questionListRegStr, $document, $pages);
            $this->snoopyObj->fetchStripLink($pages[0]);
            foreach($this->snoopyObj->getResults() as $link) {
                $questionList[] = $link;
            }
        }
        return $questionList;
    }
    
    public function getQuestionPagesList($keyword) {
        $url = str_replace("{page}", 1, $this->questionListUrl);
        $url = str_replace("{keyword}", $keyword, $url);
        $this->snoopyObj->fetch($url);
        preg_match_all($this->questionPageRegStr, $this->snoopyObj->getResults(), $pages);
        if(is_array($pages) && count($pages) > 0) {
            $this->snoopyObj->fetchStripText($page[0]);
            $pages = $this->snoopyObj->getResults();
            for($i=1;$i<=$pages[count($pages) - 1];$i++) {
                $url = str_replace("{page}", $i, $this->questionListUrl);
                $url = str_replace("{keyword}", $keyword, $url);
                $questionPageList[] = $url;
            }
        } else {
            $questionPageList[] = $url;
        }
        return $questionPageList;
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

