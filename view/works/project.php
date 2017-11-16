<?php
//dengjing34@vip.qq.com
?>

<div class="project-intro">
    <div class="project-description">
        <div class="text"><?=$p->description?></div>
    </div>
    <?php
    if ((bool)($o = current($oo))) {
        $url = Url::siteUrl("works/preview/{$o->id}");
        echo "<a class=\"next\" href=\"{$url}\" title=\"{$o->projectName} - {$o->title}\">{$o->projectName} - {$o->title}</a>";
    }
    ?>        
    <div class="clear"></div>
</div>
