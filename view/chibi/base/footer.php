<?php
//dengjing34@vip.qq.com
?>

</div>
<div id="pageFooter">
    <div id="footerInner">
        <p class="wrapper">
            <span style="float: right;"><a href="javascript:void(0);">文档</a> | <a href="javascript:void(0);">反馈</a></span>
            IP <?=$_SERVER['REMOTE_ADDR']?> | <span class="orangeRed"><?=round((microtime(true) - START_TIME) * 1000, 2)?></span>ms | Mysql Queries <span class="orangeRed"><?=Data::$counter?></span> | memory use <span class="orangeRed"><?=round(memory_get_usage() / 1024, 2)?>K</span> | Better experience with <a href="http://www.google.com/chrome" target="_blank">Chrome</a> or <a href="http://firefox.com.cn/download/" target="_blank">FireFox</a> | &copy; <?=date('Y')?>. All rights reserved. Theme design by <a href="mailto:dengjing34@vip.qq.com">HIRO</a>
        </p>
    </div>
</div>
</body>
</html>