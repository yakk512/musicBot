<?php

// OAuthライブラリの読み込み
require "twitterOAuth/vendor/autoload.php";

use Abraham\TwitterOAuth\TwitterOAuth;
require_once("config.php");

//tweetHandlerクラス
class tweetHandler{

  private $twObj;
  public $mentionsArray;

  function __construct(){
    $consumerKey       = API_KEY;
    $consumerSecret    = API_SECRET;
    $accessToken       = ACCESS_TOKEN;
    $accessTokenSecret = ACCESS_SECRET;

    $this->twObj = new TwitteroAuth($consumerKey, $consumerSecret, $accessToken, $accessTokenSecret);
    $this->mentionsArray = array();
  }

  //メンション取得のデバッグ表示
  function displayMentions(){
      var_dump($this->mentionsArray);
  }

  //メンション取得
  function getMentions(){

    $past_mentions = file_get_contents('mentions_num.txt');

    $jsonRes=$this->twObj->get("statuses/mentions_timeline", array("since_id" => $past_mentions));

    foreach ($jsonRes as $content){

        $tmpArray = array("screen_name"=>"","music_name"=>"","reply_id"=>"");

        preg_match("/(@.*)( )(.*)/",$content->text,$music_name);

        $tmpArray["screen_name"] = $content->user->screen_name;
        $tmpArray["reply_id"] = $content->id;
        $past_mentions = $content->id;
        if($music_name){
            $tmpArray["music_name"] = $music_name[3];
            array_push($this->mentionsArray,$tmpArray);
        }
    }
    echo "$past_mentions\n";
    $this->changeMentionNum($past_mentions);
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
      $jsonRes;
      //リプライ用IDとscreen_nameが存在する場合はメンションで返信する
      if(!empty($rep_id) and !empty($screen_name)){

          $jsonRes=$this->twObj->post("statuses/update", array("status" => "@".$screen_name." ".$mes, 'in_reply_to_status_id'=>(int)$rep_id));
      }else{
          $jsonRes=$this->twObj->post("statuses/update", array("status" => $mes));
      }
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