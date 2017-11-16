<?php
//dengjing34@vip.qq.com
$emptyMsg = $dataList = '';
?>
<div class="dataFilter">
    <form name="filterForm" id="filterForm" method="get" action="">
        <table>
            <?php
            foreach (array('title' => '作品名称') as $k => $v) {
            ?>
            <tr><th><?=$v?>：</th><td><input type="text" name="<?=$k?>" value="<?=$url->get($k)?>" /></td></tr>
            <?php
            }
            ?>
            <tr><th>所属项目：</th><td><select name="projectId"><?=implode("\n", $projectOptions)?></select</td></tr>
            <tr><th>&nbsp</th><td><button type="submit" class="button button-green"><span class="search"></span>查询</button></td></tr>
        </table>
    </form>
</div>
<div class="pager"><?=$pager?></div>
<div style="margin:5px"><button type="button" class="button button-gray" onclick="_global.delConfirm();"><span class="bin"></span>批量删除</button></td></div>
<form name="listForm" id="listForm" action="" method="post">
    <table class="data_list">
        <tr class="trTitle">
            <td><input type="checkbox" name="selectAll" id="selecteAll" onclick="$('input[name=id[]]').attr('checked', $(this).attr('checked'));" />ID</td>
            <td>操作</td>
            <td>作品名称</td>
            <td>作品图片</td>
            <td>所属项目</td>
            <td>排序</td>
            <td>喜欢</td>
            <td>浏览</td>
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
        $dataList .= "<td>{$obj->projectName}</td>";
        $dataList .= "<td>{$obj->sort}</td>";
        $dataList .= "<td>{$obj->heart}</td>";
        $dataList .= "<td>{$obj->hit}</td>";        
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