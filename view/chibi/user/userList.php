<?php
//dengjing34@vip.qq.com
$emptyMsg = $dataList = null;
?>
<div class="dataFilter">
    <form name="filterForm" id="filterForm" method="get" action="" class="filterForm">
        <table>
            <?php
            foreach (array('userName' => '姓名', 'mobile' => '手机', 'email' => '邮箱') as $k => $v) {
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
<div style="margin:5px"><button type="submit" class="button button-gray" onclick="_global.delConfirm();"><span class="bin"></span>批量删除</button></td></div>
<form name="listForm" id="listForm" action="" method="post">
    <table class="data_list">
        <tr class="trTitle">
                <td><input type="checkbox" name="selectAll" id="selecteAll" onclick="$('input[name=id[]]').attr('checked', $(this).attr('checked'));" />ID</td>
                <td>操作</td>
                <td>姓名</td>
                <td>手机</td>
                <td>邮箱</td>
                <td>性别</td>
                <td>角色</td>
                <td>状态</td>
                <td>创建日期</td>
        </tr>
<?php
if (empty($objs)) {
    $emptyMsg = '<div class="emptyMsg">暂无数据</div>' . "\n";
} else {
    foreach ($objs as $obj) {
        $dataList .= "<tr>\n";
        $dataList .= "<td><input type=\"checkbox\" name=\"id[]\" value=\"{$obj->id}\" />{$obj->id}</td>\n";
        $dataList .= "<td><a class=\"button button-orange\" href=\"" . Url::siteUrl("chibi/user/userModify?id={$obj->id}")  . "\"><span class=\"pencil\"></span>编辑</a></td>\n";
        $dataList .= "<td><a href=\"".Url::siteUrl("chibi/user/userModify?id={$obj->id}")."\">{$obj->userName}</a></td>\n";
        $dataList .= "<td>{$obj->mobile}</td>\n";
        $dataList .= "<td>{$obj->email}</td>\n";
        $dataList .= "<td>". User::$_sex[$obj->sex]."</td>\n";
        $dataList .= "<td>{$obj->role}</td>\n";
        $dataList .= "<td>". User::$_status[$obj->status] ."</a></td>\n";
        $dataList .= "<td>". date('Y-m-d H:i:s', $obj->createdTime) ."</td>\n";
        $dataList .= "</tr>\n";
    }
}
echo $dataList;
?>
    </table>
</form>
<?=$emptyMsg?>
<div style="margin:5px"><button type="submit" class="button button-gray" onclick="_global.delConfirm();"><span class="bin"></span>批量删除</button></td></div>
<div class="pager"><?=$pager?></div>