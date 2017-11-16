<?php
//dengjing34@vip.qq.com
$emptyMsg = $dataList = '';
?>
<div class="dataFilter">
    <form name="filterForm" id="filterForm" method="get" action="">
        <table>
            <?php
            foreach (array('name' => '角色英文名', 'cname' => '角色中文名') as $k => $v) {
            ?>
            <tr><th><?=$v?>：</th><td><input type="text" name="<?=$k?>" value="<?=$url->get($k)?>" /></td></tr>
            <?php
            }
            ?>
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
            <td>角色英文名称</td>
            <td>角色中文名称</td>
            <td>权限Id</td>
            <td>创建时间</td>
            <td>修改时间</td>
        </tr>
<?php
if (empty($objs)) {
    $emptyMsg = '<div class="emptyMsg">暂无数据</div>' . "\n";
} else {
    foreach ($objs as $obj) {
        $accesses = array_chunk(explode(',', $obj->access), 10);
        $accessStr = array();
        foreach ($accesses as $access) {
            $accessStr[] = implode(',', $access);
        }
        $button = $obj->get('btnClass') ? "<button type=\"button\" class=\"button button-blue\"><span class=\"{$obj->get('btnClass')}\"></span>{$obj->cname}</button>" : null;
        $dataList .= "<tr>";
        $dataList .= "<td><input type=\"checkbox\" name=\"id[]\" value=\"{$obj->id}\" />{$obj->id}</td>";
        $dataList .= "<td><a class=\"button button-orange\" href=\"" . Url::siteUrl("chibi/role/roleModify?id={$obj->id}")  . "\"><span class=\"pencil\"></span>编辑</a></td>";
        $dataList .= "<td><a href=\"" . Url::siteUrl("chibi/role/roleModify?id={$obj->id}") . "\">{$obj->name}</a></td>";
        $dataList .= "<td>{$obj->cname}</td>";
        $dataList .= "<td>" . implode('<br />', $accessStr) . "</td>";
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