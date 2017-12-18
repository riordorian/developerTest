<?php
function GetFiveFreshNews($url)
{
    $rss = simplexml_load_file($url);
    $newsAr = [];
    foreach ($rss->channel->item as $k => $item) {
        $news = [];
        $news['title'] = $item->title;
        $news['link'] = $item->link;
        $news['description'] = $item->description;
        $newsAr[] = $news;
    }
    return array_slice($newsAr, 0, 5);
}
GetFiveFreshNews("https://lenta.ru/rss");