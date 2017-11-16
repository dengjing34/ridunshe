<?php
//dengjing34@vip.qq.com
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title></title>
<style>
body,div,dl,dt,dd,ul,ol,li,h1,h2,h3,h4,h5,h6,pre,form,fieldset,input,textarea,p,blockquote,th,td {padding: 0;margin: 0;font-size:12px;color:#666;}
fieldset,img {border: 0;}
address,caption,cite,code,dfn,em,strong,th,var {font-weight: normal;font-style: normal;}
ol,ul {list-style: none;}
caption,th {text-align: left;}
h1,h2,h3,h4,h5,h6 {font-weight: normal;font-size: 100%;}
abbr,acronym { border: 0;}
select{font-size:14px;}
</style>
</head>
    <body>
        <form style="padding:2px 0 0 5px;margin:0;" onsubmit="return check();" action="<?=$action?>" method="post" id="uploadForm" enctype="multipart/form-data">
            <input type="file" name="Filedata" id="Filedata" /><button type="submit">上传</button>
        </form>
    </body>
</html>
<script>
function check() {
    var fileInput = document.getElementById('Filedata');
    if (fileInput.value == '') {
        alert('请先选择文件');return false;
    }
    if (!/(.*)\.(jpg|png|gif|jpeg)$/.test(fileInput.value)) {
        alert('文件格式非法');return false;
    }    
}
</script>