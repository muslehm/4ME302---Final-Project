<?php
     

$url = 'https://www.news-medical.net/tag/feed/Parkinsons-Disease.aspx';
$contents = file_get_contents($url);
$news=simplexml_load_string($contents);
echo '<h2 class="rsstitle"><a href="'. $news->channel->link .'" target="_blank">'. $news->channel->title . '</a></h2>';

foreach ($news->channel->item as $item) {
   echo '<div class="newsitem"><div class="newsdate">' . substr($item->pubDate, 0, -9) . '</div><h3 class="newstitle"><a href="'. $item->link .'" target="_blank">' . $item->title . '</a></h3><p class="newsfeed">' . $item->description . '</p></div>';
} 
$contents=null;
$news = null;

