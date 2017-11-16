<?php
//dengjing34@vip.qq.com
?>

<div class="news-list">
    <?php
    if (!empty($oo)) {
        $html= array();
        foreach ($oo as $o) {
            $date = date('Y-m-d', $o->createdTime);
            $url = Url::siteUrl("news/detail/{$o->id}");
            $imageUrl = $o->firstImage();
            $pic = is_null($imageUrl) ? null : "<li><img src=\"{$imageUrl}\" /></li>";
            $html[] = "<ul><li>{$date}</li><li><a href=\"{$url}\" title=\"{$o->title}\">{$o->title}</a></li>{$pic}<li>{$o->seoDescription()}</li></ul>";
        }        
        echo implode("\n", $html);
    }
    ?>
    <div class="clear"></div>
</div>
<div class="pager"><?=$pager?></div>