<?php

// OAuthライブラリの読み込み
require "twitterOAuth/vendor/autoload.php";

use Abraham\TwitterOAuth\TwitterOAuth;
require_once("config.php");

class postTweet{

  private $twObj;

  function __construct(){
    $consumerKey = API_KEY;
    $consumerSecret = API_SECRET;
    $accessToken = ACCESS_TOKEN;
    $accessTokenSecret = ACCESS_SECRET;

    $this->twObj = new TwitteroAuth($consumerKey, $consumerSecret, $accessToken, $accessTokenSecret);
  }

  //
  function post($mes,$rep_id,$screen_name){
    //$jsonRes=$this->twObj->post("statuses/update", array("status" => $mes));
    $rep_id = 679616523464355840;
    $screen_name = '@yakk512';

    $jsonRes=$this->twObj->post("statuses/update", array("status" => $screen_name." ".$mes, "in_reply_to_statu_id"=>$rep_id));
    var_dump($jsonRes);
  }

  function debugLog()
  {

    $fp = fopen('debugLog.txt', 'w');

    if ($fp){
    if (flock($fp, LOCK_EX)){
      foreach ($this->locationArray as $line) {
        fwrite($fp,$line."\n");
      }

    flock($fp, LOCK_UN);
    }else{
        print('ファイルロックに失敗しました');
    }
  }
    fclose($fp);
  }
  function getSearchArray()
  {
    //var_dump($this->locationArray);
    $res="";
    foreach ($this->locationArray as $line) {
        $res.=$line."</br>";
      }

    return $res;
  }
}
?>