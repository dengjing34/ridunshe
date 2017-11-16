<?php
//dengjing34@vip.qq.com
?>
<script type="text/javascript" src="<?=Url::siteUrl('js/jquery.sliders.js')?>"></script>
<script type="text/javascript">
$(function(){	
    $('#slides').slides({
        preload: true,
        preloadImage: baseUrl + 'images/load-indicator.gif',
        play: 4000,
        pause: 1000,
        effect:"fade",
        hoverPause: true
    });
});
</script>
<div class="slides_wrapper">
    <div id="slides">
        <div class="slides_container">
            <?php
            foreach ($sliders as $id => $slider) {
            ?>
                <a href="<?=$slider['url']?>" title="<?=$slider['title']?>"><img src="<?=$slider['pic']?>" alt="<?=$slider['title']?>" /></a>
            <?php
            }
            ?>
        </div>
    </div>
</div>    
