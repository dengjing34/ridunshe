<?php
//dengjing34@vip.qq.com
$emptyMsg = $dataList = '';
?>

<div class="pager"><?=$pager?></div>
<div style="margin:5px"><button type="button" class="button button-gray" onclick="_global.delConfirm();"><span class="bin"></span>批量删除</button></td></div>
<form name="listForm" id="listForm" action="" method="post">
    <table class="data_list">
        <tr class="trTitle">
            <td><input type="checkbox" name="selectAll" id="selecteAll" onclick="$('input[name=id[]]').attr('checked', $(this).attr('checked'));" />ID</td>
            <td>操作</td>
            <td>新闻标题</td>
            <td>列表主图</td>
            <td>创建时间</td>
            <td>修改时间</td>
        </tr>
<?php
if (empty($objs)) {
    $emptyMsg = '<div class="emptyMsg">暂无数据</div>' . "\n";
} else {
    foreach ($objs as $obj) {
        $pic = (bool)$obj->pic ? "<a href=\"" .  Url::fileUrl($obj->pic) . "\" target=\"_blank\" title=\"点击查看原图\"><img src=\"" . Url::smallImage($obj->pic) . "\" alt=\"点击查看原图\" /></a>" : null;
        $dataList .= "<tr>";
        $dataList .= "<td><input type=\"checkbox\" name=\"id[]\" value=\"{$obj->id}\" />{$obj->id}</td>";
        $dataList .= "<td><a class=\"button button-orange\" href=\"" . Url::siteUrl("chibi/{$controller}/{$controller}Modify?id={$obj->id}")  . "\"><span class=\"pencil\"></span>编辑</a></td>";
        $dataList .= "<td><a href=\"" . Url::siteUrl("chibi/{$controller}/{$controller}Modify?id={$obj->id}") . "\">{$obj->title}</a></td>";
        $dataList .= "<td>{$pic}</td>";
        $dataList .= "<td>" . date('Y-m-d H:i:s', $obj->created_time) ."</td>";
        $dataList .= "<td>" . date('Y-m-d H:i:s', $obj->updated_time) ."</td>";
        $dataList .= "</tr>";
    }
}
?>
        <?=$dataList?>
    </table>
</form>
<?=$emptyMsg?>
<div style="margin:5px"><button type="button" class="button button-gray" onclick="_global.delConfirm();"><span class="bin"></span>批量删除</button></td></div>
<div class="pager"><?=$pager?></div>