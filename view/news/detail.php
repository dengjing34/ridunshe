<?php
//dengjing34@vip.qq.com
?>

<?//=$crumb?>
<div class="news-detail">
    <h1><?=$o->title?></h1>
    <div class="attr"><?=date('Y-m-d H:i:s', $o->createdTime)?></div>
    <div class="content"><?=$o->content?></div>
    <div class="near">
        <?php 
        if (($near['prev'])) echo "<a class=\"prev-news\" id=\"prevLink\" href=\"" . Url::siteUrl("news/detail/{$near['prev']->id}") . "\" title=\"{$near['prev']->title}\">上一篇：{$near['prev']->title}</a> ";
        if (($near['next'])) echo "<a class=\"next-news\" id=\"nextLink\" href=\"" . Url::siteUrl("news/detail/{$near['next']->id}") . "\" title=\"{$near['next']->title}\">下一篇：{$near['next']->title}</a>";             
        ?>
        <div class="clear"></div>
    </div>
</div>
<script type="text/javascript" src="<?=Url::siteUrl('js/jquery.keyPager.js')?>"></script>
<script type="text/javascript">
$(function(){
    $(this).keyPager();
});
</script>