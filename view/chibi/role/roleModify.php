<?php
//dengjing34@vip.qq.com
echo $scripts;
?>

<script type="text/javascript">
$(function(){
    $('#form').submit(function(){
        return sValidation({
            'access[]':{tip:'请勾选一些权限',type:'checkbox',minChecked:1, tipElementId:'accessTip'},
            <?=$form->createScripts()?>
        });
    });
    Stip('nav_1').show({content:'一级菜单', kind:'error', p:'top'});//time:1000
    Stip('pageSubheader').show({content:'二级菜单', kind:'error', p:'top'});//time:1000
    Stip('crumb').show({content:'操作按钮', kind:'correct', p:'top'});//time:1000
});
</script>
<style>
.accessList{clear:both;height:auto;display:block;border:1px solid #ddd;margin:10px 0;zoom:1;}
.accessList dt{border:none;padding:2px 5px;color:#5c9425;margin:5px;}
.accessList dd{border:none;float:left;padding:2px 5px;color:#F47A20;border:1px solid #CADCEA;margin:5px;}
.accessList dd ul li{color:#0078a5;}
</style>
<form id="form" class="form" method="post">
    <table>
        <?=$form->createForm()?>
        <tr>
            <th>选择权限：</th>
            <td><em>一级菜单为<span style="color:#5c9425">绿色</span>, 二级菜单为<span style="color:#F47A20">橙色</span>, 操作按钮为<span style="color:#0078a5">蓝色</span></em>
                <div id="accessTip">全部勾选<input type="checkbox" onclick="$('input[name=access[]]').attr('checked', $(this).attr('checked'));" /></div>
<?php
$accessHtml = null;
foreach ($access as $first) {
    $checked = in_array($first['id'], $roleAccess) ? 'checked="checked"' : null;
    $accessHtml .= "<dl class=\"accessList\">\n";
    $accessHtml .= "<dt><input type=\"checkbox\" name=\"access[]\" id=\"access_{$first['id']}\" value=\"{$first['id']}\" {$checked} /> <label for=\"access_{$first['id']}\">{$first['cname']}</label></dt>\n";
    if(isset ($first['children'])){
        foreach ($first['children'] as $second) {
            $checked = in_array($second['id'], $roleAccess) ? 'checked="checked"' : null;
            $accessHtml .= "<dd><input type=\"checkbox\" name=\"access[]\" id=\"access_{$second['id']}\" value=\"{$second['id']}\" {$checked} /> <label for=\"access_{$second['id']}\">{$second['cname']}</label>";
            if(isset ($second['children'])){
                $accessHtml .= "<ul>\n";
                foreach ($second['children'] as $third) {
                    $checked = in_array($third['id'], $roleAccess) ? 'checked="checked"' : null;
                    $accessHtml .= "<li><input type=\"checkbox\" name=\"access[]\" id=\"access_{$third['id']}\" value=\"{$third['id']}\" {$checked} /> <label for=\"access_{$third['id']}\">{$third['cname']}</label></li>";
                }
                $accessHtml .= "</ul>\n";
            }
            $accessHtml .= "</dd>\n";
        }
    }
    $accessHtml .= "<div class=\"clear\"></div></dl>\n";
}
echo $accessHtml;
?>
            </td>
        </tr>
        <tr>
            <th></th>
            <td><button type="submit" name="submit" value="保存" id="insertBtn" class="button button-orange"><span class="add"></span>保存</button>&nbsp;&nbsp;
            <button type="button" class="button button-gray" onclick="history.go(-1);">返回</button>
            <input type="hidden" name="refer" value="<?=$refer?>" />
            </td>
        </tr>
    </table>
</form>
