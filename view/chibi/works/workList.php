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
            <td>名称</td>
            <td>分类</td>
            <td>首页滚动图片</td>
            <td>首页滚动图片排序</td>
            <td>首页底部图片</td>
            <td>首页底部图片排序</td>
            <td>封面</td>
            <td>排序</td>
            <td>创建时间</td>
            <td>修改时间</td>
        </tr>
<?php
if (empty($objs)) {
    $emptyMsg = '<div class="emptyMsg">暂无数据</div>' . "\n";
} else {
    foreach ($objs as $obj) {
        $pic = (bool)$obj->cover ? "<a href=\"" .  Url::fileUrl($obj->cover) . "\" target=\"_blank\" title=\"点击查看原图\"><img src=\"" . Url::smallImage($obj->cover) . "\" alt=\"点击查看原图\" /></a>" : null;
        $banner_pic = (bool)$obj->banner_pic ? "<a href=\"" .  Url::fileUrl($obj->banner_pic) . "\" target=\"_blank\" title=\"点击查看原图\"><img src=\"" . Url::smallImage($obj->banner_pic) . "\" alt=\"点击查看原图\" /></a>" : null;
        $home_pic = (bool)$obj->home_pic ? "<a href=\"" .  Url::fileUrl($obj->home_pic) . "\" target=\"_blank\" title=\"点击查看原图\"><img src=\"" . Url::smallImage($obj->home_pic) . "\" alt=\"点击查看原图\" /></a>" : null;
        $dataList .= "<tr>";
        $dataList .= "<td><input type=\"checkbox\" name=\"id[]\" value=\"{$obj->id}\" />{$obj->id}</td>";
        $dataList .= "<td><a class=\"button button-orange\" href=\"" . Url::siteUrl("chibi/{$controller}/{$controller}Modify?id={$obj->id}")  . "\"><span class=\"pencil\"></span>编辑</a></td>";
        $dataList .= "<td><a href=\"" . Url::siteUrl("chibi/{$controller}/{$controller}Modify?id={$obj->id}") . "\">{$obj->title}</a></td>";
        $dataList .= "<td>{$obj->category_zh}</td>";
        $dataList .= "<td>{$banner_pic}</td>";
        $dataList .= "<td>{$obj->banner_sort}</td>";
        $dataList .= "<td>{$home_pic}</td>";
        $dataList .= "<td>{$obj->home_sort}</td>";
        $dataList .= "<td>{$pic}</td>";
        $dataList .= "<td>{$obj->sort}</td>";
        $dataList .= "<td>" . date('Y-m-d H:i:s', $obj->create_time) ."</td>";
        $dataList .= "<td>" . date('Y-m-d H:i:s', $obj->update_time) ."</td>";
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