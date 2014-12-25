<?php
//define("BaseDir", __DIR__.'/../');
//set_include_path(BaseDir);

ini_set('error_log', 'D:/error_log.txt');

require_once "Ctrip\CtripRules.php";
require_once "Mafengwo\MafengwoRules.php";
require_once "Ly\LyRules.php";

require_once "Ctrip\CtripQuestionService.php";
require_once "Ctrip\CtripQuestionDocument.php";

//namespace JingqubaoScript\DataAcquisition;
//use JingqubaoScript\DataAcquisition\Ctrip\CtripRules;
//class test{
//    function execute() {
//        
////        $rules = new CtripRules();
////        $list = $rules->getQuestionList('凤凰岭');
//        $rules = new MafengwoRules();
//        //$list = $rules->getQuestionList('圆明园');
//        $answers = $rules->getAnswers(1014877);
//        return $answers;
//    }
//}

        $scenics = LyService::find(array('Name'=>array('$ne'=>""),'MafengwoQuestion'=>array('$exists'=>false)), 
                array('return_type'=>1,'fields'=>array('ScenicId','Name')));

        foreach($scenics['documents'] as $scenic) {
            if($scenic['ScenicId'] == 311) {
                continue;
            }
            $rules = new CtripRules();
            $list = @$rules->getQuestionList($scenic['Name']);

            if($list != null && $list != false) {file_put_contents("D:/result.txt",var_export($list, true));
                @LyService::setScenic(array('ScenicId'=>$scenic['ScenicId']), 'CtripQuestion', $list);
            }
//            $mafengwoRules = new MafengwoRules();
//            $mafengList = @$mafengwoRules->getQuestionList($scenic['Name']);
//            if($mafengList != null && $mafengList != false) {
//                @LyService::setScenic(array('ScenicId'=>$scenic['ScenicId']), 'MafengwoQuestion', $mafengList);
//            }
        }
        
        var_dump('success');exit;

//$rules = new LyRules();
//$list = $rules->getScenicList();
//var_dump('success');exit;

file_put_contents('D:/ly.txt', var_export($list, true));exit;
var_dump($list);exit;
$dir = dirname(__FILE__);
include $dir."\snoopy-master\Snoopy.class.php";
	$snoopy = new Snoopy;
	//$snoopy->fetchtext('http://sellbest.net/by-brand/limit1800/page[1-1]/1-PRADA.html');
        //$snoopy->fetchlinks('http://sellbest.net/by-brand/limit1800/page[1-1]/1-PRADA.html');
        //$snoopy->fetchform('http://sellbest.net/by-brand/limit1800/page[1-1]/1-PRADA.html');
        $snoopy->fetch('http://sellbest.net/by-brand/limit1800/page[1-1]/1-PRADA.html');
        print $snoopy->results;exit;
        var_dump($snoopy->results);
        /*
	$snoopy->fetchtext("http://www.php.net/");
	print $snoopy->results;
	
	$snoopy->fetchlinks("http://www.phpbuilder.com/");
	print $snoopy->results;
	
	$submit_url = "http://lnk.ispi.net/texis/scripts/msearch/netsearch.html";
	
	$submit_vars["q"] = "amiga";
	$submit_vars["submit"] = "Search!";
	$submit_vars["searchhost"] = "Altavista";
		
	$snoopy->submit($submit_url,$submit_vars);
	print $snoopy->results;
	
	$snoopy->maxframes=5;
	$snoopy->fetch("http://www.ispi.net/");
	echo "<PRE>\n";
	echo htmlentities($snoopy->results[0]); 
	echo htmlentities($snoopy->results[1]); 
	echo htmlentities($snoopy->results[2]); 
	echo "</PRE>\n";

	$snoopy->fetchform("http://www.altavista.com");
	print $snoopy->results;
         * 
         */