<?php

// OAuthライブラリの読み込み
require "twitterOAuth/vendor/autoload.php";

use Abraham\TwitterOAuth\TwitterOAuth;
require_once("config.php");

//tweetHandlerクラス
class tweetHandler{

  private $twObj;
  private $mentionsArray;

  function __construct(){
    $consumerKey       = API_KEY;
    $consumerSecret    = API_SECRET;
    $accessToken       = ACCESS_TOKEN;
    $accessTokenSecret = ACCESS_SECRET;

    $this->twObj = new TwitteroAuth($consumerKey, $consumerSecret, $accessToken, $accessTokenSecret);
    $this->mentionsArray = array();
  }

  //メンション取得
  function getMentions(){

    $past_mentions = intval(file_get_contents('mentions_num.txt'));

    $jsonRes=$this->twObj->get("statuses/mentions_timeline", array("since_id" => $past_mentions));

    foreach ($jsonRes as $content){

      preg_match("/(@.*)( )(.*)/",$content->text,$music_name);
      echo $content->user->screen_name."\n";
      if($music_name){
          array_push($this->mentionsArray,$music_name[3]);
      }
    }

    $this->changeMentionNum($past_mentions+1);
    return $this->mentionsArray;
  }

  function changeMentionNum($num)
  {

    $fp = fopen('mentions_num.txt', 'w');

    if ($fp){
      if (flock($fp, LOCK_EX)){
        fwrite($fp,$num);
      }

      flock($fp, LOCK_UN);
    }else{
      print('ファイルロックに失敗しました');
    }
    fclose($fp);
  }

  function readMentionNum()
  {

    $mention_num = 0;
    $fp = fopen('./debugLog.txt', 'r');

    if ($fp){
      if (flock($fp, LOCK_EX)){
        fwrite($fp,$line."\n");
      }

      flock($fp, LOCK_UN);
    }else{
      print('ファイルロックに失敗しました');
    }

    fclose($fp);
  }

  //postする機能
  function post($mes,$rep_id,$screen_name){
    //$jsonRes=$this->twObj->post("statuses/update", array("status" => $mes));
    $rep_id = 679616523464355840;
    $screen_name = '@yakk512';

    $jsonRes=$this->twObj->post("statuses/update", array("status" => $screen_name." ".$mes, "in_reply_to_statu_id"=>$rep_id));
    var_dump($jsonRes);
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