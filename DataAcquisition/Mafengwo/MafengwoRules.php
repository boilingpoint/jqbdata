<?php

//namespace JingqubaoScript\DataAcquisition\Mafentwo;
require_once dirname(__FILE__)."/../Rules.php";
//use JingqubaoScript\DataAcquisition\Rules;

class MafengwoRules extends Rules {
    private $questionListUrl = "http://www.mafengwo.cn/qa/ajax_pager.php?kw={keyword}&sort=0&action=question_search&start={page}";
    private $answerListUrl = "http://www.mafengwo.cn/qa/ajax_pager.php?_uid=0&qid={qid}&action=question_detail&start={page}";
    private $questionListRegStr = "'<\s*li\s.*?class=\"answer-item\s.*?clearfix\s.*?_j_question_item\"[^>]*?>(.*?)</div>[^<]*?</li>'isx";
    private $questionRegStr = "'<\s*div\s.*?class\s*=\s*\"pager_v1\">(.*?)</ul>'isx";
    private $questionTitleRegStr = "'<\s*h1\s*>(.*?)</h1>'isx";
    private $questionDescRegStr = "'<\s*div\s.*?class=\"q-desc\">(.*?)</div>'isx";
    private $questionAnswersRegStr = "'<\s*ul\s.*?class\s*=\"answer-list\s.*?_j_pager_box\">(.*?)</ul>[^<]*?</div>'isx";
    private $questionAnswerRegStr = "'<\s*li\s.*?class=\"answer-item\s.*?clearfix\s.*?_j_answer_item\"[^>]*?>(.*?)</li>[^<]*?<a'isx";
    private $questionAnswerTextRegStr = "'<\s*dd\s.*?class=\"_j_answer_html\">(.*?)</dd>'isx";
    //private $answerPageRegStr = "";
    private $answerCommentsRegStr = "'<\s*ul\s.*?class\s*=\"comment-list\"[^<]*?>(.*?)</ul>'isx";
    private $answerCommentRegStr = "'<\s*dd\s*>(.*?)</dd>'isx";
    
    public function __construct() {
        parent::__construct();
    }
    /*
    public function MafengwoRules($ruleList){
        $ruleList = array(
            "title"=>"<div\s+class=\"q-title\"[^>]*>.*<h1>(.*)</h1>",
            "desc"=>"<div\s+class=\"q-info\"[^>]*>.*<div class=\"q-desc\">(.*)</div><div class=\"q-tags\">",
        );
    }
     * 
     */
        
    public function getQuestionList($keyword) {
        $keyword = urlencode($keyword);
        $questionPageList = $this->getQuestionPagesList($keyword);
        foreach($questionPageList as $pageUrl) {
            $this->snoopyObj->fetch($pageUrl);
            $document = $this->snoopyObj->getResults();
            $documentArray = json_decode($document, true);
            $search = array("\u003c","\u003e");
            $replace = array("<",">");
            $documentArray['payload']['list_html'] = str_replace($search, $replace, $documentArray['payload']['list_html']);                        
            
            $questionIdList = $this->_fetchStripLink($documentArray['payload']['list_html']);
            foreach ($questionIdList as $id) {
                $link = "http://www.mafengwo.cn/wenda/detail-" . $id . ".html";
                $questionUrlList[$id] = $link;
            }
        }
        
        foreach($questionUrlList as $id=>$questionUrl) {
            $document = $this->getQuestion($questionUrl);
            $questionList[] = array(
                'Title'=>$this->getTitle($document),
                'Desc'=>$this->getDesc($document),
                'Answer'=>$this->getAnswers($id)
            );
        }
        return $questionList;
    }
    
    private function _fetchStripLink($document) {
        
        preg_match_all("'<\s*li\s.*?data-qid\s*=\s*			# find <a href=
						([\"\'])?					# find single or double quote
						(?(1) (.*?)\\1 | ([^\s\>]+))		# if quote found, match up to next matching
													# quote, otherwise match up to next space
						'isx", $document, $links);


        // catenate the non-empty matches from the conditional subpattern

        while (list($key, $val) = each($links[2])) {
            if (!empty($val))
                $match[] = $val;
        }

        while (list($key, $val) = each($links[3])) {
            if (!empty($val))
                $match[] = $val;
        }

        // return the links
        return $match;
    }
    
    public function getQuestionPagesList($keyword) {
        $url = str_replace("{page}", 0, $this->questionListUrl);
        $url = str_replace("{keyword}", $keyword, $url);
        $this->snoopyObj->fetch($url);
        $questionArray = json_decode($this->snoopyObj->getResults(), true);
        if(is_array($questionArray) && $questionArray['payload']['total'] > 10) {
            for($i=0;$i<$questionArray['payload']['total'];$i+=10) {
                $url = str_replace("{page}", $i, $this->questionListUrl);
                $url = str_replace("{keyword}", $keyword, $url);
                $questionPageList[] = $url;
            }
        } else {
            $questionPageList[] = $url;
        }
        
        return $questionPageList;
    }
    
    public function getQuestion($url) {
        $this->snoopyObj->fetch($url);
        return $this->snoopyObj->getResults();
    }
    public function getContent($reg, $document) {
        preg_match($reg, $document, $contents);
        if(is_array($contents) && count($contents) > 1) {
            return $this->_fetchStripText($contents[1]);
        }
        return "";
    }
    public function getTitle($document) {
        return $this->getContent($this->questionTitleRegStr, $document);
    }
    
    public function getDesc($document) {
        return $this->getContent($this->questionDescRegStr, $document);
    }
    
    public function getAnswersPage() {
        //
    }
    
    public function getAnswers($id) {
        $url = str_replace("{page}", 0, $this->answerListUrl);
        $url = str_replace("{qid}", $id, $url);
        
        $this->snoopyObj->fetch($url);
        $answerArray = json_decode($this->snoopyObj->getResults(), true);
        if(is_array($answerArray) && $answerArray['payload']['total'] > 50) {
            for($i=0;$i<$answerArray['payload']['total'];$i+=50) {
                $url = str_replace("{page}", $i, $this->answerListUrl);
                $url = str_replace("{qid}", $id, $url);
                $answerPageList[] = $url;
            }
        } else {
            $answerPageList[] = $url;
        }
        foreach($answerPageList as $answerUrl) {
            $this->snoopyObj->fetch($url);
            $documentArray = json_decode($this->snoopyObj->getResults(), true);
            
            $search = array("\u003c","\u003e");
            $replace = array("<",">");
            $documentArray['payload']['list_html'] = str_replace($search, $replace, $documentArray['payload']['list_html']); 
            
            //preg_match_all($this->questionAnswersRegStr, $documentArray['payload']['list_html'], $titles);var_dump($this->questionAnswersRegStr, $documentArray['payload']['list_html'], $titles);exit;
            //if(is_array($titles) && count($titles) > 1 && count($titles[1]) > 0) {

                preg_match_all($this->questionAnswerRegStr, $documentArray['payload']['list_html'], $answersBlock);
                if(is_array($answersBlock) && count($answersBlock) > 0 && count($answersBlock[1]) > 0) {
                    foreach($answersBlock[1] as $answerBlock) {
                        $answers[] = array(
                            'Content'=>$this->getContent($this->questionAnswerTextRegStr, $answerBlock),
                            'Comment'=>$this->getComments($answerBlock)
                        );
                    }
                }
            //}
        }
        
        return $answers;
    }
    
    public function getComments($document) {
        preg_match($this->answerCommentsRegStr, $document, $commentsBlock);print_r($commentsBlock);
        if(is_array($commentsBlock) && count($commentsBlock) > 0 && count($commentsBlock[1]) > 0) {
            foreach($commentsBlock as $commentBlock) {
                $comment = $this->getContent($this->answerCommentRegStr, $commentBlock);
                if(!empty($comment)) {
                    $comments[] = $comment;
                }
            }
        }
        return $comments;
    }
    
    
    
}

    
    



