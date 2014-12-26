<?php
//namespace JingqubaoScript\DataAcquisition;
require_once __DIR__."\snoopy-master\Snoopy.class.php";
class Rules {
    private $ruleList = array();
    protected $snoopyObj = null;
    public function __construct() {
        $this->snoopyObj = new Snoopy;
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
    
    public function Rules() {
        $dir = dirname(__FILE__);
        require_once $dir."\snoopy-master\Snoopy.class.php";
        $this->snoopyObj = new Snoopy;var_dump($this->snoopyObj);exit;
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
    
    protected function _replaceNonUTF8($str) {  
        //去除非UTF8字符，这种方式，还需完善字符集
        $str = json_encode($str);
        $search = array(
            "\\ufffd",
            "\\u256d",
            "\\u256e",
            "\\u0437",
            "\\u300d",
            "\\u2220",
            "&amp;#176;",
            "&amp;reg;"
        );
        $replace = array(
            " ",
            " ",
            " ",
            " ",
            " ",
            " ",
            " ",
            " ",
        );
        $str = str_ireplace($search, $replace, $str);
        $str = json_decode($str);
        return $str;
    }
    
    protected function _fetchStripText($document)
    {
        $document = $this->_replaceNonUTF8($document);
        // I didn't use preg eval (//e) since that is only available in PHP 4.0.
        // so, list your entities one by one here. I included some of the
        // more common ones.

        $search = array("'<script[^>]*?>.*?</script>'si", // strip out javascript
            "'<[\/\!]*?[^<>]*?>'si", // strip out html tags
            "'([\r\n])[\s]+'", // strip out white space
            "'&(quot|#34|#034|#x22);'i", // replace html entities
            "'&(amp|#38|#038|#x26);'i", // added hexadecimal values
            "'&(lt|#60|#060|#x3c);'i",
            "'&(gt|#62|#062|#x3e);'i",
            "'&(nbsp|#160|#xa0);'i",
            "'&(iexcl|#161);'i",
            "'&(cent|#162);'i",
            "'&(pound|#163);'i",
            "'&(copy|#169);'i",
            "'&(reg|#174);'i",
            "'&(deg|#176);'i",
            "'&(#39|#039|#x27);'",
            "'&(euro|#8364);'i", // europe
            "'&a(uml|UML);'", // german
            "'&o(uml|UML);'",
            "'&u(uml|UML);'",
            "'&A(uml|UML);'",
            "'&O(uml|UML);'",
            "'&U(uml|UML);'",
            "'&szlig;'i",
            //"�",
        );
        $replace = array("",
            "",
            "\\1",
            "\"",
            "&",
            "<",
            ">",
            " ",
            chr(161),
            chr(162),
            chr(163),
            chr(169),
            chr(174),
            chr(176),
            chr(39),
            chr(128),
            "ä",
            "ö",
            "ü",
            "Ä",
            "Ö",
            "Ü",
            "ß",
            //"",
        );

        $text = preg_replace($search, $replace, $document);

        return $text;
    }
}

