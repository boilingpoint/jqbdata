<?php
/*
 * Curl 多线程类
 * 使用方法：
 * ========================
	$urls = array("http://baidu.com", "http://dzone.com", "http://google.com");
	$mp = new MultiHttpRequest($urls);
	$mp->start();
	
 * ========================
 */
class MultiHttpRequest {
    public $urls = array();
    public $curlopt_header = 0;
    public $method = "GET";
    
    function __construct($urls = false) {
        $this->urls = $urls;
    }
    function set_urls($urls) {
        $this->urls = $urls;
        return $this;
     }
     function is_return_header($b) {
         $this->curlopt_header = $b;
         return $this;
     }
     function set_method($m) {
         $this->medthod = strtoupper($m);
         return $this;
     }
     function start() {
         if(!is_array($this->urls) or count($this->urls) == 0){
            return false;
         }
         $curl = $text = array();
         $handle = curl_multi_init();
         foreach($this->urls as $k=>$v){
            $curl[$k] = $this->add_handle($handle, $v);
         }
         $this->exec_handle($handle);
         foreach($this->urls as $k=>$v){
             //curl_multi_getcontent($curl[$k]);
             //echo $curl[$k]."\n";
             $text[$k] =  curl_multi_getcontent($curl[$k]);
             //echo $text[$k], "\n\n";
             curl_multi_remove_handle($handle, $curl[$k]);
         }
         curl_multi_close($handle);
		
         return $text;
     }
     private function add_handle($handle, $url) {
         $curl = curl_init();
         curl_setopt($curl, CURLOPT_URL, $url);
         
         curl_setopt($curl, CURLOPT_HEADER, $this->curlopt_header);
         curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
         curl_multi_add_handle($handle, $curl);
         return $curl;
     }
     private function exec_handle($handle) {
         $flag = null;
         do {
            curl_multi_exec($handle, $flag);
         } while ($flag > 0);
     }
     
     public function get_content($url){
		$ch = curl_init();
		curl_setopt ($ch, CURLOPT_URL, $url);
		curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT,10);
		return curl_exec($ch);
     }
}