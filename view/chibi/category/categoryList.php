<?php
//dengjing34@vip.qq.com
$emptyMsg = $dataList = '';
$currentLink = ' class="current"';
?>
<div class="dataFilter">
    <form name="filterForm" id="filterForm" method="get" action="">
        <table>
            <?php
            foreach (array('name' => '中文分类名称', 'ename' => '英文分类名称', 'pid' => '父级Id', 'level' => '级别') as $k => $v) {
            ?>
            <tr><th><?=$v?>：</th><td><input type="text" name="<?=$k?>" value="<?=$url->get($k)?>" /></td></tr>
            <?php
            }
            ?>
            <tr><th>一级分类：</th><td>
            <a<?=!$url->get('pid') ? $currentLink : ''?> href="<?=Url::siteUrl("chibi/{$controller}/{$controller}List?" . Pager::getQueryString('pid'))?>">全部</a>        
            <?php
            foreach ($firstCategory as $first) {
            ?>
            <a<?=$url->get('pid')==$first->id ? $currentLink : ''?> href="<?=Url::siteUrl("chibi/{$controller}/{$controller}List?pid={$first->id}&" . Pager::getQueryString('pid'))?>"><?=$first->name?></a>        
            <?php
            }
            ?></td></tr>            
            <tr><th>状态：</th><td>
            <a<?=!$url->get('status') ? $currentLink : ''?> href="<?=Url::siteUrl("chibi/{$controller}/{$controller}List?" . Pager::getQueryString('status'))?>">全部</a>        
            <?php
            foreach (Category::$_status as $k=>$v) {
            ?>
            <a<?=$url->get('status')==$k ? $currentLink : ''?> href="<?=Url::siteUrl("chibi/{$controller}/{$controller}List?status={$k}&" . Pager::getQueryString('status'))?>"><?=$v?></a>        
            <?php
            }
            ?></td></tr>
            <tr><th>&nbsp</th><td><button type="submit" class="button button-green"><span class="search"></span>查询</button></td></tr>
        </table>
        <input type="hidden" name="status" value="<?=$url->get('status') ? $url->get('status') : null?>">
    </form>
</div>
<div class="pager"><?=$pager?></div>
<div style="margin:5px"><button type="button" class="button button-gray" onclick="_global.delConfirm();"><span class="bin"></span>批量删除</button></td></div>
<form name="listForm" id="listForm" action="" method="post">
    <table class="data_list">
        <tr class="trTitle">
            <td><input type="checkbox" name="selectAll" id="selecteAll" onclick="$('input[name=id[]]').attr('checked', $(this).attr('checked'));" />ID</td>
            <td>操作</td>
            <td>中文名称</td>
            <td>英文名称</td>
            <td>状态</td>
            <td>父级Id</td>
            <td>级别</td>
            <td>路径</td>
            <td>排序</td>            
            <td>创建时间</td>
            <td>修改时间</td>
        </tr>
<?php
if (empty($objs)) {
    $emptyMsg = '<div class="emptyMsg">暂无数据</div>' . "\n";
} else {
    foreach ($objs as $obj) {
        $status = $obj->status == Category::STATUS_ACTIVE ? '<span class="green">'. Category::$_status[$obj->status] .'</span>' : '<span class="red">'. Category::$_status[$obj->status] .'</span>';
        $dataList .= "<tr>";
        $dataList .= "<td><input type=\"checkbox\" name=\"id[]\" value=\"{$obj->id}\" />{$obj->id}</td>";
        $dataList .= "<td><a class=\"button button-orange\" href=\"" . Url::siteUrl("chibi/{$controller}/{$controller}Modify?id={$obj->id}")  . "\"><span class=\"pencil\"></span>编辑</a>";
        $dataList .= " <a title=\"切换状态\" class=\"button button-orange\" href=\"" . Url::siteUrl("chibi/{$controller}/{$controller}List?id={$obj->id}&action=switch")  . "\"><span class=\"switch\"></span>切换</a></td>";
        $dataList .= "<td><a href=\"" . Url::siteUrl("chibi/{$controller}/{$controller}Modify?id={$obj->id}") . "\">{$obj->name}</a></td>";
        $dataList .= "<td>{$obj->ename}</td>";
        $dataList .= "<td>{$status}</td>";
        $dataList .= "<td>{$obj->pid}</td>";
        $dataList .= "<td>{$obj->level}</td>";
        $dataList .= "<td>{$obj->path}</td>";
        $dataList .= "<td>{$obj->sort}</td>";        
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