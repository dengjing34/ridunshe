<?php
//dengjing34@vip.qq.com
class Access extends Data {

    public $id, $name, $cname, $pid, $level, $order, $createdTime, $updatedTime, $attributeData;

    const CACHE_PREFIX = 't_access';

    const LEVEL_1 = 1, LEVEL_2 = 2, LEVEL_3 = 3;
    
    public static $_level = array (
        self::LEVEL_1 => '一级菜单',
        self::LEVEL_2 => '二级菜单',
        self::LEVEL_3 => '操作按钮',
    );

    public static  $formFields = array(
        'name' => array(
            'text' => '控制器名称', 'required' => true, 'rule' => '/^[\w|\/|,]+$/', 'hint' => '若一级菜单有多个控制器请用","隔开', 'size' => '50', 'tip' => '不符合规范'
        ),
        'cname' => array(
            'text' => '控制器中文名称', 'required' => true, 'size' => '50','tip' => '必填',
        ),
        'pid' => array(
            'text' => '上级权限ID', 'required' => true, 'rule' => '/^[\d|-]+$/', 'hint' => '若为一级菜单请填写-1', 'tip' => '必须为数字'
        ),
        'level' => array(
            'text' => '权限级别', 'required' => true, 'type' => 'select', 'options' => array(), 'tip' => '未选择',
        ),
        'btnClass' => array(
            'text' => '按钮样式', 'hint' => '操作按钮才需要填写按钮样式,如:[add, user-add, user-edit]等',
        ),
        'order' => array(
            'text' => '排序', 'required'=> true, 'rule' => '/^\d+$/', 'hint' => '数值越小排位越靠前', 'tip' => '必须为数字', 'value' => 1,
        ),
    );

    function __construct() {
        $options = array(
            'key' => 'id',
            'table' => 't_access',
            'columns' => array(
                'id' => 'id',
                'name' => 'name',
                'cname' => 'cname',
                'pid' => 'pid',
                'level' => 'level',
                'order' => 'order',
                'createdTime' => 'createdTime',
                'updatedTime' => 'updatedTime',
                'attributeData' => 'attributeData',
            ),
            'saveNeeds' => array(
                'name',
                'cname',
                'pid',
            )
        );
        parent::init($options);
    }

    function save() {
        if (is_null($this->id)) {
            $obj = new $this->className;
            $obj->name = $this->name;
            try{
                $result = $obj->find(array('limit' => 1));
                if(!empty($result)){
                    throw new Exception("{$this->name} 已存在");
                }
            }  catch (Exception $e) {
                throw new Exception($e->getMessage());
            }
            $this->createdTime = time();
        } else {
            $obj = new $this->className;
            $obj->name = $this->name;
            try{
                $result = $obj->find(array('limit' => 1));
                if (!empty($result) && $result[0]->id != $this->id) {
                    throw new Exception("{$this->name} {$result[0]->id} {$this->id}已存在");
                }
            }catch (Exception $e) {
                throw new Exception($e->getMessage());
            }            
        }
        $this->updatedTime = time();
        $this->htmlspecialchars();
        parent::save();
        $cache = new Cache_Lite();
        $cache->clean(self::CACHE_PREFIX);//rebuild this access Cache
        $role = new Role();
        try {
            $id = 1;
            $role->load($id);
            $roleAccess = explode(',', $role->access);
            $roleAccess[] = $this->id;
            $roleAccess = array_unique($roleAccess);
            $role->access = implode(',', $roleAccess);
            $role->save();
        } catch (Exception $e) {
            throw new Exception("id为{$id}的超级管理员的角色不存在了");
        }
    }

    public function loadByName($name) {
        $obj = new $this->className;
        $obj->name = $name;
        try {
            $result = $obj->find(array('limit' => '1'));
        } catch (Exception $exc) {
            throw new Exception($exc->getMessage());
        }
        return empty($result) ? null : $result[0];
    }

    public function getAccessTree() {
        $cacheId = self::CACHE_PREFIX . "_tree";
        $cacheLite = new Cache_Lite();
        $cacheLite->setLifeTime(60 * 60 * 24);
        if ($result = $cacheLite->get($cacheId, self::CACHE_PREFIX)) {
            $firstAccess = $result;
        } else {
            $obj = new $this->className;
            $obj->level = self::LEVEL_1;
            $result = $obj->find(array(
                'order' => array('order' => 'ASC', 'id' => 'ASC'),
            ));
            $firstAccess = array();
            $fields = array('id', 'name', 'cname', 'pid', 'level', 'order','btnClass');
            foreach($result as $k => $firsLevel){
                foreach ($fields as $field) $firstAccess[$firsLevel->id][$field] = $firsLevel->get($field);
                $obj = new $this->className;
                $obj->pid = $firsLevel->id;
                $result2 = $obj->find(array(
                    'order' => array('order' => 'ASC', 'id' => 'ASC')
                ));
                $firstAccess[$firsLevel->id]['children'] = array();
                foreach($result2 as $key => $secondLevel) {
                    $secondAccess = array();
                    foreach ($fields as $field) $secondAccess[$secondLevel->id][$field] = $secondLevel->get($field);
                    $obj = new $this->className;
                    $obj->level = self::LEVEL_3;
                    $obj->pid = $secondLevel->id;
                    $result3 = $obj->find(array(
                        'order' => array('order' => 'ASC', 'id' => 'ASC')
                    ));
                    $secondAccess[$secondLevel->id]['children'] = array();
                    foreach ($result3 as $key => $thirdLevel) {
                        $thirdAccess = array();
                        foreach ($fields as $field) $thirdAccess[$thirdLevel->id][$field] = $thirdLevel->get($field);
                        $secondAccess[$secondLevel->id]['children'][$thirdLevel->id] = $thirdAccess[$thirdLevel->id];
                    }
                    $firstAccess[$firsLevel->id]['children'][$secondLevel->id] = $secondAccess[$secondLevel->id];
                }
            }
            $cacheLite->save($firstAccess, $cacheId, self::CACHE_PREFIX);
        }
        return $firstAccess;
    }

}
?>