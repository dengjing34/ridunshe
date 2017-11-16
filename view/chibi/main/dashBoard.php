<?php
//dengjing34@vip.qq.com
?>

<table class="data_list">
    <tr class="trTitle">
        <td>系统环境检测</td>
        <td>可用性</td>
    </tr>
    <tr>
        <td>主机名 (IP：端口)：</td>
        <td><?=$_SERVER['SERVER_NAME'] ."(" . $_SERVER['SERVER_ADDR'] . ":". $_SERVER['SERVER_PORT'] .")"?></td>
    </tr>
    <tr>
        <td>程序目录</td>
        <td><?=BASEDIR?></td>
    </tr>
    <tr>
        <td>Web服务器：</td>
        <td><?=$_SERVER['SERVER_SOFTWARE']?></td>
    </tr>
    <tr>
        <td>PHP 运行方式：</td>
        <td><?=PHP_SAPI?></td>
    </tr>
    <tr>
        <td>PHP版本：</td>
        <td><?=PHP_VERSION?></td>
    </tr>
    <tr>
        <td>MySQL 版本：</td>
        <td><?=function_exists("mysql_close") ? mysql_get_client_info() : $disabled?></td>
    </tr>
    <tr>
        <td>Alternative PHP Cache(可选PHP缓存APC)：</td>
        <td><?=function_exists("apc_cache_info") && ($apcSmaInfo = apc_sma_info()) ? "total : " . number_format(next($apcSmaInfo) / 1024 /1024, 2) . "M" : $disabled?></td>
    </tr>
    <tr>
        <td>GD库版本：</td>
        <td><?=function_exists('gd_info') ? current(gd_info()) : $disabled?></td>
    </tr>
    <tr>
        <td>最大上传限制：</td>
        <td><?=ini_get('file_uploads') ? ini_get('upload_max_filesize') : $disabled?></td>
    </tr>
    <tr>
        <td>最大执行时间：</td>
        <td><?=ini_get('max_execution_time') . "秒"?></td>
    </tr>
    <tr>
        <td>采集函数(curl_init)检测：</td>
        <td><?=function_exists('curl_init') ? $enabled : $disabled?></td>
    </tr>
</table>