<?php
namespace JingqubaoScript\DataAcquisition;

class Rules {
    private $ruleList = array();
    public function Rules() {
        $ruleList = array(
            'meta_title'=>'<title>(.*)</title>',
            'meta_keywords'=>'<meta name="keywords" content="(.*)" />',
            'meta_description'=>'<meta name="description" content="(.*)" />',
            'product_name'=>'<h4 class="h4-title float-l"> (.*)</h4>',
            'product_image'=>'<div class="v-inner">.*<a href="(.*)" id="originalImg"><img src=".*" alt=".*" /></a>.*</div>',
            'product_price'=>'Our Price : <strong>(.*)</strong>',
            'product_description'=>'<div class="description-text" id="description"><div class="border-cont">(.*)</div>',
        );
    }
    
    public function getRules() {
        return $ruleList;
    }
}

