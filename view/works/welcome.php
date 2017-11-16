<?php
//dengjing34@vip.qq.com
?>

<div id="works-preview">
    <?php
    $array = array();
    foreach ($oo as $o) {
        $imageUrl = Url::biggerImage($o->pic);
        $workUrl = Url::siteUrl("works/project/{$o->id}");
        $title = "{$o->name}";
        $array[] = '<dl>';
        $array[] = "<dt><a href=\"{$workUrl}\" title=\"{$title}\"><img src=\"{$imageUrl}\" alt=\"{$title}\" /></a></dt>";
        $array[] = "<dd><a href=\"{$workUrl}\" title=\"{$title}\">{$o->name}</a></dd>";
        $array[] = '</dl>';
    }
    echo implode("\n", $array);
    ?>
    <div class="clear"></div>
</div>
<div class="pager"><?=$pager?></div>