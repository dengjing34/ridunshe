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
<link rel="shortcut icon" href="<?=Url::siteUrl('images/favicon.ico');?>" />
<style type="text/css">
body,div,dl,dt,dd,ul,ol,li,h1,h2,h3,h4,h5,h6,pre,form,fieldset,input,textarea,p,blockquote,th,td {padding: 0;margin: 0;font-size:12px;color:#666;}
fieldset,img {border: 0;}
address,caption,cite,code,dfn,em,strong,th,var {font-weight: normal;font-style: normal;}
ol,ul {list-style: none;}
caption,th {text-align: left;}
h1,h2,h3,h4,h5,h6 {font-weight: normal;font-size: 100%;}
abbr,acronym { border: 0;}
select{font-size:14px;}
.clear{clear:both;}
.hidden{display:none;}

a:link{ color:#262221;text-decoration:none;}a:visited{color:#262221;text-decoration:none;}a:hover{color:#838383;text-decoration:none;}a:active{color:#262221;text-decoration:none;}
#words-wrapper{position:absolute;top:50%;height:350px;margin-top:-175px;width:100%;/* negative half of the height*/}
#words-wrapper #container{width:1000px;margin:0 auto;}
#words-wrapper #container #words{height:138px;width:900px;margin:0 auto 100px auto;}
#words-wrapper #container #words a:link,#words-wrapper #container #words a:visited,#words-wrapper #container #words a:hover,#words-wrapper #container #words a:active{display:block;text-indent:-999px;height:138px;width:900px;background:url(../images/idea_index.png) no-repeat;}
#words-wrapper #container #words a.one{background-position:0px 0px;}
#words-wrapper #container #words a.two{background-position:0px -138px;}
#words-wrapper #container #words a.three{background-position:0px -276px;}
#words-wrapper #container #words a.four{background-position:0px -414px;}
#words-wrapper #container #words a.five{background-position:0px -552px;}
#words-wrapper #container #logo{width:100px;height:65px;margin:0 auto;text-align:center;}
#words-wrapper #container #enter{margin:20px auto 0 auto;width:200px;text-align:center;}
#words-wrapper #container #skip{text-align:center;vertical-align:middle;margin-top:50px;}
#words-wrapper #container #skip ul li{padding:5px;}
</style>
</head>
    
<body>
    <div id="words-wrapper">
        <div id="container">
            <div id="words">
                <object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=8,0,0,0" width="900" height="138">
                <param name="movie" value="<?=$flaSrc?>" />
                <param name="quality" value="high" />
                <param name="allowScriptAccess" value="always" />
                <param name="wmode" value="transparent">
                    <embed src="<?=$flaSrc?>"
                    quality="high"
                    type="application/x-shockwave-flash"
                    WMODE="transparent"
                    width="900"
                    height="138"
                    pluginspage="http://www.macromedia.com/go/getflashplayer"
                    allowScriptAccess="always" />
                </object>                
            </div>
            <div id="logo"><a href="<?=Url::siteUrl('homepage')?>" title="点击进入日敦社"><img src="<?=Url::siteUrl('images/samples/logo_new_1.jpg')?>" alt="点击进入日敦社" /></a></div>
            <div id="enter"><a class="enter" href="<?=Url::siteUrl('homepage')?>">Enter</a></div>
            <div class="clear"></div>
        </div>
    </div>
</body>
</html>
<?=$analyticsCode?>