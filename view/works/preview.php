<?php
//dengjing34@vip.qq.com
?>

<?//=$crumb?>
<div class="work-preview">
    <div class="work-pic">
        <?php 
        if (($near['prev'])) echo "<a class=\"prev\" id=\"prevLink\" href=\"" . Url::siteUrl("works/preview/{$near['prev']->id}") . "\" title=\"{$near['prev']->projectName} {$near['prev']->title}\"></a> ";
        else echo "<a href=\"javascript:void(0);\" class=\"hidden\"></a>";
        ?>
        <span><img src="<?=Url::fileUrl($o->pic)?>" alt="<?="{$o->projectName} {$o->title}"?>"  title="<?="{$o->projectName} {$o->title}"?>"/></span>
        <?php
        if (($near['next'])) echo "<a class=\"next\" id=\"nextLink\" href=\"" . Url::siteUrl("works/preview/{$near['next']->id}") . "\" title=\"{$near['next']->projectName} {$near['next']->title}\"></a>";
        else echo "<a href=\"javascript:void(0);\" class=\"hidden\"></a>";
        ?>
    </div>
    <div class="work-title">
        <?="{$o->projectName} {$o->title}"?>
        <a class="like" title="我喜欢" id="<?="works_{$o->id}"?>" href="javascript:void(0)"><?=$o->heart?></a>
    </div>
    <div class="work-intro"><?php echo $o->intro?></div>
    <?php if (count(array_filter($near)) > 0)  {?><div class="work-tips">小提示:可使用键盘的←→方向键进行左右翻页</div><?php }?>
</div>
<script type="text/javascript" src="<?=Url::siteUrl('js/jquery.likeThis.js')?>"></script>
<script type="text/javascript" src="<?=Url::siteUrl('js/jquery.keyPager.js')?>"></script>
<script type="text/javascript">
$(function(){
    $('a.like').likeThis();
    $(this).keyPager();
});
</script>
