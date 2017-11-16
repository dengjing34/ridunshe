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
            <td>查看图片</td>
            <td>标题</td>
            <td>排序(越小越靠前)</td>
            <td>跳转url地址</td>
            <td>创建时间</td>
            <td>修改时间</td>
        </tr>
<?php
if (empty($objs)) {
    $emptyMsg = '<div class="emptyMsg">暂无数据</div>' . "\n";
} else {
    foreach ($objs as $obj) {
        $pageUrl = Url::siteUrl($obj->url);
        $picUrl = Url::fileUrl($obj->pic);
        $dataList .= "<tr>";
        $dataList .= "<td><input type=\"checkbox\" name=\"id[]\" value=\"{$obj->id}\" />{$obj->id}</td>";
        $dataList .= "<td><a class=\"button button-orange\" href=\"" . Url::siteUrl("chibi/{$controller}/{$controller}Modify?id={$obj->id}")  . "\"><span class=\"pencil\"></span>编辑</a></td>";
        $dataList .= "<td><a href=\"{$picUrl}\" target=\"_blank\">查看图片</a></td>";
        $dataList .= "<td><a href=\"" . Url::siteUrl("chibi/{$controller}/{$controller}Modify?id={$obj->id}") . "\">{$obj->title}</a></td>";
        $dataList .= "<td>{$obj->sort}</td>";
        $dataList .= "<td><a href=\"{$pageUrl}\" target=\"_blank\">{$pageUrl} 点击预览</a></td>";    
        $dataList .= "<td>" . date('Y-m-d H:i:s', $obj->createdTime) ."</td>";
        $dataList .= "<td>" . date('Y-m-d H:i:s', $obj->updatedTime) ."</td>";
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