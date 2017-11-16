<?php
//dengjing34@vip.qq.com
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>用户登录</title>

<style>
body,div,dl,dt,dd,ul,ol,li,h1,h2,h3,h4,h5,h6,pre,form,fieldset,input,textarea,p,blockquote,th,td {padding: 0;margin: 0;font-size:12px;color:#222222;}
fieldset,img {border: 0;}
address,caption,cite,code,dfn,em,strong,th,var {font-weight: normal;font-style: normal;}
ol,ul {list-style: none;}
caption,th {text-align: left;}
h1,h2,h3,h4,h5,h6 {font-weight: normal;font-size: 100%;}
abbr,acronym { border: 0;}
select{font-size:11px;}
a:link{ color:green;text-decoration:none;padding:3px 6px;}a:visited{color:green;text-decoration:none;}a:hover{color:#fff;background:green;border-radius:5px;-moz-border-radius:5px;-webkit-border-radius:5px;}a:active{color:silver;text-decoration:none;}
html,body{font-family:Arial,Tahoma,Helvetica;}
body{background:#EAE7E0 url(<?=Url::siteUrl('images/chibi/sand.jpg')?>) repeat-x 0 0;}
#loginDiv{position:absolute;top:35%;left:40%;width:312px;}
#loginDiv h1{text-align:center;font-size: 16px;height: 24px;line-height: 24px;padding:10px 0;background:url(<?=BASEURL?>images/chibi/icons/locked.png) no-repeat center;}
.inputText{outline:medium none;border-radius:10px 10px 0 0;backgournd:#FAFAFA;border:1px solid #AAA;line-height:14px;padding:10px 15px;width:280px;box-shadow:0 1px 2px rgba(0, 0, 0, 0.1) inset, 0 1px 0 rgba(255, 255, 255, 0.2);behavior: url(/PIE.htc);}
#password{border-radius:0 0 10px 10px;border-top:none;margin-bottom:20px;*margin-top:-2px;behavior: url(/PIE.htc);}
.button{background-color:#EEEEEE;background-image;linear-gradient(center top , #FEFEFE, #EEEEEE);border-color:#AAAAAA;box-shadow:0 1px 0 #FFFFFF inset, 0 1px 3px rgba(0, 0, 0, 0.15);color:#111111;text-shadow:0 1px 0 #FFFFFF;border:1px solid #AAAAAA;border-radius:2px;cursor:pointer;padding:4px 10px;behavior: url(/PIE.htc);}
.button:hover{background-color:#f2f2f2;background-image;linear-gradient(center top , #ffffff, #f2f2f2);border-color:#888888;padding:4px 10px;behavior: url(/PIE.htc);}
.fr{float:right;}.fl{float:left;}
.error{color:#BE4741;padding:4px;}
</style>
<script type="text/javascript" src="<?=BASEURL?>js/jquery-1.4.2.min.js"></script>
<!--[if lte IE 9 ]>
<script type="text/javascript">
$(function(){
    $('#name')
        .css('color','gray')
        .val($('#name').attr('placeholder'))
        .focus(function(){
            $(this).val($(this).val() == $(this).attr('placeholder') ? '' : $(this).val()).css('color','#222');
        })
        .blur(function(){
            $(this).val($(this).val() == '' ? $(this).attr('placeholder') : $(this).val()).css('color','gray');
        });
    $('#password')
        .css('color','gray')
        .val($('#password').attr('placeholder'))
        .focus(function(){            
            $(this).val('').css('color', '#222');
        })
        .blur(function(){
            $(this).val($(this).val() == '' ? $(this).attr('placeholder') : $(this).val()).css('color', 'gray');
        });
});
</script>
<![endif]-->
<script type="text/javascript">
$(function(){
    $('#loginForm').submit(function(){
        var flag_name = false;
        var flag_password =false;
        flag_name = $.trim($('#name').val()) !='' && $('#name').val() != $('#name').attr('placeholder') ? true : false;
        flag_password = $.trim($('#password').val()) !='' && $('#password').val() != $('#password').attr('placeholder') ? true : false;

        if(!flag_name || !flag_password){
            var msg = '';
            msg += !flag_name ? "--请填写用户名称\n" : '';
            msg += !flag_password ? "--请填写用户密码\n" : '';
            alert(msg);
            return false;
        }
    });
    $('#name').focus();
});
</script>
</head>

<body>
    <div id="loginDiv">
        <form action="" method="post" name="loginForom" id="loginForm" class="normalForm" autocomplete="off">
            <h1></h1>
            <input type="text" name="name" class="inputText" id="name" placeholder="用户名称" required="required" x-webkit-speech="x-webkit-speech"/>
            <input type="password" name="password" class="inputText" id="password" placeholder="用户密码" required="required" />
            <div class="error fl"><?=$error?></div><button class="button fr" type="submit">登录</button>
        </form>
    </div>
</body>
</html>