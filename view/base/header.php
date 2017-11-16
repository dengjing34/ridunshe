<?php
//dengjing34@vip.qq.com
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title><?=$conf['title']?></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="keywords" content="<?=$conf['keywords']?>" />
<meta name="description" content="<?=$conf['description']?>"/>
<link rel="stylesheet" media="screen" href="<?=Url::siteUrl('css/base.css')?>" />
<link rel="shortcut icon" href="<?=Url::siteUrl('images/favicon.ico');?>" /> 
<!--[if IE 6]>
<style type="text/css">
* html,* html body   /* IE6 Fixed Position Jitter Fix */{background-image:url(about:blank);background-attachment:fixed;}
* html .fixed-top    /* IE6 position fixed Top        */{position:absolute;bottom:auto;top:expression(eval(document.documentElement.scrollTop));}
* html .fixed-right  /* IE6 position fixed right      */{position:absolute;right:auto;left:expression(eval(document.documentElement.scrollLeft+document.documentElement.clientWidth-this.offsetWidth)-(parseInt(this.currentStyle.marginLeft,10)||0)-(parseInt(this.currentStyle.marginRight,10)||0));}
* html .fixed-bottom /* IE6 position fixed Bottom     */{position:absolute;bottom:auto;top:expression(eval(document.documentElement.scrollTop+document.documentElement.clientHeight-this.offsetHeight-(parseInt(this.currentStyle.marginTop,10)||0)-(parseInt(this.currentStyle.marginBottom,10)||0)));}
* html .fixed-left   /* IE6 position fixed Left       */{position:absolute;right:auto;left:expression(eval(document.documentElement.scrollLeft));}
</style>
<![endif]-->
<script type="text/javascript" src="<?=Url::siteUrl('js/jquery-1.5.2.min.js')?>"></script>
<script type="text/javascript">
var baseUrl = '<?=BASEURL?>';    
$(function(){    
    $('#nav-inner ul li a').hover(
        function(){
            var en = $(this).text();
            $(this).text($(this).attr('title'));
            $(this).attr('title', en);
        },
        function(){
            var zh = $(this).text();
            $(this).text($(this).attr('title'));
            $(this).attr('title', zh);
        }
    );
    $('#crumb a').hover(
        function(){
            var en = $(this).text();
            $(this).text($(this).attr('title'));
            $(this).attr('title', en);
        },
        function(){
            var zh = $(this).text();
            $(this).text($(this).attr('title'));
            $(this).attr('title', zh);
        }
    );
    $('img').bind("contextmenu", function(e){ return false;}) //only img tag deny contextmenu
});
</script>
</head>
<body>

<div id="header" class="fixed-top fixed-left">
    <div id="header-inner">
        <a id="logo" href="<?=Url::siteUrl()?>" title="<?=$siteName?>"><img src="<?=Url::siteUrl('images/samples/logo_new_1.jpg')?>" /></a>
    </div>
<?=$navHtml?>
</div>
<div id="wrapper">
    <div id="container">
        
