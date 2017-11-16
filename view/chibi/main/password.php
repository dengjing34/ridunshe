<?php
//dengjing34@vip.qq.com
echo $jsValidation;
?>
<script type="text/javascript">
$(function(){
	$('#updateForm').submit(function(){
		return sValidation({
			userName:{tip:'姓名必填'},
			password:{tip:'密码必填'}
		});
	});
});
</script>
<form action="" method="post" name="updateForm" id="updateForm" class="normalForm"><fieldset><legend>修改密码</legend>
<table class="formTable">
<?php
$fields = array ('userName' => '姓名', 'password' => '密码');
foreach ($fields as $key => $val) {
    $type = $key == 'password' ?  $key : 'text';
    $readOnly = $key == 'userName' ? 'readonly="readonly"' : '';
    $fieldValue = $user->$key;
?>
    <tr><th><span>*</span><?=$val?>：</th><td><input <?=$readOnly?> type="<?=$type?>" name="<?=$key?>" id="<?=$key?>" value="<?=$fieldValue?>" /></td></tr>
<?php
}
?>
</table>
<div class="buttonZone" style=""><input type="submit" name="submitBtn" value="提交" class="button" style="margin: 0 0 0 150px;"><input type="button" value="返回" class="button" style="margin: 0 0 0 15px;" onclick="history.go(-1);" /></div>
</fieldset>
</form>