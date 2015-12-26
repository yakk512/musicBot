<?php

require_once './youtube_apis/search_youtube.php';
require_once './music/tweetHandler.php';

# メンションを拾う
$th = new tweetHandler();
$mentions = $th->getMentions();

$sm = new searchMusic();

# 名前
foreach ($mentions as $mention) {
    $url = $sm->search($mention['music_name']);

    $dtext = $mention['music_name'].' '.$url;
    $dreply_id = $mention['reply_id'];
    $dscreen_name = $mention['screen_name'];

    $th->post($dtext, $dreply_id, $dscreen_name);
}
