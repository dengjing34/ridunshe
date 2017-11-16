<?php
//dengjing34@vip.qq.com
echo $scripts;
?>

<script type="text/javascript">
$(function(){
    $('#form').submit(function(){
        return sValidation({
            <?=$form->createScripts()?>
        });
    });
});
</script>
<form id="form" class="form" method="post">
    <table>
        <?=$form->createForm()?>
        <tr>
            <th></th>
            <td><button type="submit" name="submit" value="保存" id="insertBtn" class="button button-orange"><span class="add"></span>保存</button>&nbsp;&nbsp;
            <button type="button" class="button button-gray" onclick="history.go(-1);">返回</button>
            <input type="hidden" name="refer" value="<?=$refer?>" />
            </td>
        </tr>
    </table>
</form>