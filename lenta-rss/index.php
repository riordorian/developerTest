<?php
$url = 'https://lenta.ru/rss';
$news = simplexml_load_file($url, "SimpleXMLElement",LIBXML_NOCDATA);
for($i = 5; $i > 0; $i--){
    echo $news->channel->item[$i]->title;
    echo $news->channel->item[$i]->link;
    echo $news->channel->item[$i]->description;
}
?>