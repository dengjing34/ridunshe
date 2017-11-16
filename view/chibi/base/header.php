<?php
//dengjing34@vip.qq.com
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>后台管理<?= $title ? " - {$title}" : null?></title>
<link rel="stylesheet" media="screen" href="<?=BASEURL?>css/chibi/base.css" />
<link rel="shortcut icon" href="<?=Url::siteUrl('/images/favicon.ico');?>" />
<script type="text/javascript" src="<?=BASEURL?>js/jquery-1.4.2.min.js"></script>
<script type="text/javascript">
var baseUrl = '<?=BASEURL?>';
var ctrlUrl = '<?=BASEURL?>chibi/';
 $(function(){
     (function(){
         var access = <?=json_encode($access)?>;
         $('#mainNav ul li a').click(function(){
             $(this).parent().parent().find('li').removeClass('active');
             $(this).parent().addClass('active');
             var id = $(this).attr('id').replace('nav_', '');
             var secondNav = current = '';
             var methodName = '<?=$methodName?>';
             $('#pageSubheader div ul li').remove();
             if (typeof(access[id]['children']) != 'undefined'){
                 for (var i in access[id]['children']){
                     var action = access[id]['children'][i]['name'].split('/');
                     var childMethod = action[1] ? action[1] : '<?=DEFAULT_METHOD?>';
                     current = childMethod == methodName ? ' class="current"' : '';
                     secondNav += '<li><a' + current + ' href="' +  ctrlUrl + access[id]['children'][i]['name'] + '">' + access[id]['children'][i]['cname'] + '</a></li>';
                 }
                 $('#pageSubheader div ul').html(secondNav);
             }
             return false;
         });
     })();
 });
 var _global = {
        delConfirm:function(e){
            var name = e ? e : 'id[]';
            if ( $('input[name=' + name + ']:checked').length == 0) {
                alert('请勾选要删除的数据');
                return false;
            }
            if (confirm('确定要删除'+ $('input[name=id[]]:checked').length + '条数据?')) $('#listForm').submit();
        }
 };
</script>
</head>
<body>

<div id="pageHeader">
    <div class="wrapper">        
        <div id="utilNav">
            <ul>
                <li>欢迎登录,<?=$userName?></li>
                <li><a class="button button-gray" href="<?=Url::siteUrl('chibi/main/profile')?>"><span class="vcard"></span>用户信息</a></li>
                <li><a class="button button-gray" href="<?=Url::siteUrl('chibi/main/userPassword')?>"><span class="vcard-edit"></span>修改密码</a></li>
                <li><a class="button button-gray" href="<?=Url::siteUrl('chibi/main/help')?>"><span class="help"></span>帮助中心</a></li>
                <li><a class="button button-gray" href="<?=BASEURL?>" target="_blank"><span class="home"></span>返回首页</a></li>
                <li><a class="button button-gray" href="<?=Url::siteUrl('chibi/main/quit')?>"><span class="delete"></span>退出登录</a></li>                
            </ul>
        </div>
        <h1>hiro cms managment</h1>
        <div id="mainNav">
            <ul>
                <?php
                $subNav = array();
                foreach ($access as $firstNav){
                    $active = in_array($controllerName, explode(',', $firstNav['name'])) ? ' class="active"' : '';
                    if(in_array($controllerName, explode(',', $firstNav['name']))) $subNav = isset($firstNav['children']) ? $firstNav['children'] : array();
                ?>
                <li<?=$active?>><a href="<?=Url::siteUrl("chibi/{$firstNav['name']}")?>" id="nav_<?=$firstNav['id']?>"><?=$firstNav['cname']?></a></li>
                <?php
                }
                ?>
            </ul>
        </div>        
    </div>
    <div id="pageSubheader">
        <div class="wrapper">
            <ul>
                <?php
                $actionBtns = array();
                foreach($subNav as $nav) {
                    $action = explode('/', $nav['name']);
                    $navMethod = isset($action[1]) ? $action[1] : DEFAULT_METHOD;
                    $current = null;
                    if ($methodName == $navMethod) {
                        $current = ' class="current"';
                        $actionBtns = isset($nav['children']) ? $nav['children'] : array();
                    }
                ?>
                <li><a<?=$current?> href="<?=Url::siteUrl("chibi/{$nav['name']}")?>"><?=$nav['cname']?></a></li>
                <?php
                }
                ?>
            </ul>
        </div>
    </div>
</div>
<div id="container">
    <div id="crumb">
        <?php
        $crumbLink = null;
        foreach ($crumb as $value) {
            $crumbLink[] = "<a href=\"" . Url::siteUrl("chibi/{$value['name']}") . "\">{$value['cname']}</a>";
        }
        echo implode(' &raquo; ', $crumbLink);
        ?>
    </div>
    <div class="actionPannel">
        <?php
        foreach ($actionBtns as $btn) {
            $spanClass = $btn['btnClass'] ? "<span class=\"{$btn['btnClass']}\"></span>" : '';
        ?>
        <a href="<?=Url::siteUrl("chibi/{$btn['name']}")?>" class="button button-blue"><?=$spanClass . $btn['cname']?></a>
        <?php
        }
        ?>
    </div>