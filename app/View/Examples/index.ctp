<?php
    // タイムラインを順番に表示
    foreach($twitterData as $timeline){
        // 日付を GMT -> JST 変換し表示形式を整形
        $time = date('m月d日 H:i:s',strtotime($timeline['created_at']));
    
        // 簡易表示版
        //print $time.'【'.$timeline['user']['name'].'】@'.
        //	$timeline['user']['screen_name'].' '.$timeline['text'].'<br>';
    
        // 上の簡易表示をコメントアウトし，以下を有効にすると・・
    
        print $this->Html->image($timeline['user']['profile_image_url']);
        print $time.'【'.$timeline['user']['name'].'】@'.
            $this->Html->link($timeline['user']['screen_name'],
                'http://www.twitter.com/'.$timeline['user']['screen_name']).
                $timeline['text'].'<br>';
    
    }
?>
