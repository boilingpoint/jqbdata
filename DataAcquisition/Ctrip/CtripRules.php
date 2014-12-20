<?php
//namespace JingqubaoScript\DataAcquisition\Ctrip;
require_once dirname(__FILE__)."/../Rules.php";
//use JingqubaoScript\DataAcquisition\Rules;

class CtripRules extends Rules {
    private $questionListUrl = "http://you.ctrip.com/asks/search/p{page}?keywords={keyword}&type=1";
    private $questionListRegStr = "'<\s*ul\s.*?class\s*=\"asklist\">(.*?)</ul>'isx";
    private $questionPageRegStr = "'<\s*div\s.*?class\s*=\s*\"pager_v1\">(.*?)</ul>'isx";
    private $questionTitleRegStr = "'<\s*h1\s.*?class\s*=\s*\"ask_title\">(.*?)</h1>'isx";
    private $questionDescRegStr = "'<\s*p\s.*?id=\"host_asktext\"\s.*?class=\"ask_text\">(.*?)</p>'isx";
    private $questionAnswersRegStr = "'<\s*ul\s.*?class\s*=\"otheranswer_con\">(.*?)</ul>[^<]*?</div>'isx";
    private $questionAnswerRegStr = "'<\s*li\s*>(.*?)</div>[^<]*?</li>'isx";
    private $questionAnswerTextRegStr = "'<\s*p\s.*?class=\"answer_text\">(.*?)</p>'isx";
    private $answerCommentsRegStr = "'<\s*ul\s.*?class\s*=\"answer_comment_list\">(.*?)</ul>'isx";
    private $answerCommentRegStr = "'<\s*p\s.*?class\s*=\"comment_text\">(.*?)</p>'isx";
    
    public function __construct() {
        parent::__construct();
    }
    /*
    public function CtripRules($ruleList){
        $this->Rules($ruleList);
    }
     * 
     */
    
    public function executeRule($rule, $document) {
        //
    }
    
    public function getQuestionList($keyword) {
        $keyword = urlencode($keyword);
        $questionPageList = $this->getQuestionPagesList($keyword);
        foreach($questionPageList as $pageUrl) {
            $this->snoopyObj->fetch($pageUrl);
            $document = $this->snoopyObj->getResults();
            preg_match_all($this->questionListRegStr, $document, $pages);
            
            if(is_array($pages) && count($pages) > 1 && count($pages[1]) > 0) {
                $links = $this->_fetchStripLink($pages[1][0]);
                foreach($links as $link) {
                    $link = preg_replace("'^/'", "http://you.ctrip.com/", $link);
                    $questionUrlList[] = $link;
                }
            }
        }
        
        foreach($questionUrlList as $questionUrl) {
            $document = $this->getQuestion($questionUrl);
            $questionList[] = array(
                'Title'=>$this->getTitle($document),
                'Desc'=>$this->getDesc($document),
                'Answer'=>$this->getAnswers($document)
            );
        }
        return $questionList;
    }
    
    private function _fetchStripLink($document) {
        
        preg_match_all("'<\s*li\s.*?href\s*=\s*			# find <a href=
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
        $url = str_replace("{page}", 1, $this->questionListUrl);
        $url = str_replace("{keyword}", $keyword, $url);
        $this->snoopyObj->fetch($url);
        preg_match_all($this->questionPageRegStr, $this->snoopyObj->getResults(), $pages);
        if(is_array($pages) && count($pages) > 1 && count($pages[1]) > 0) {
            $this->snoopyObj->fetchStripText($page[1][0]);
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
    
    public function getAnswers($document) {
        preg_match_all($this->questionAnswersRegStr, $document, $titles);
        if(is_array($titles) && count($titles) > 1 && count($titles[1]) > 0) {
            
            preg_match_all($this->questionAnswerRegStr, $titles[1][0], $answersBlock);
            if(is_array($answersBlock) && count($answersBlock) > 0 && count($answersBlock[1]) > 0) {
                foreach($answersBlock[1] as $answerBlock) {
                    $answers[] = array(
                        'Content'=>$this->getContent($this->questionAnswerTextRegStr, $answerBlock),
                        'Comment'=>$this->getComments($answerBlock)
                    );
                }
            }
        }
        return $answers;
    }
    
    public function getComments($document) {
        preg_match($this->answerCommentsRegStr, $document, $commentsBlock);
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


