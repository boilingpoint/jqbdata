<?php
namespace JingqubaoScript\DataAcquisition;
use JingqubaoScript\DataAcquisition\Ctrip\CtripRules;
class test{
    function execute() {
        $rules = new CtripRules(null);
        $list = $rules->getQuestionList('凤凰岭');
        return $list;
    }
}
$test = new test();
$list = $test->execute();
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