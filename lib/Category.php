<?php
//dengjing34@vip.qq.com
class Category extends Data {
    public $id, $name, $ename, $pid, $level, $path, $sort, $status, $createdTime, $updatedTime, $attributeData;
    const STATUS_ACTIVE = 1, STATUS_INACTIVE = 2;
    public static $_status = array(
        self::STATUS_ACTIVE => '有效',
        self::STATUS_INACTIVE => '无效',
    );
    public static $cachePrefix, $allCategories = array();
    public static $treeOptions = array();    
    public static $formFields = array(
        'name' => array(
            'text' => '分类中文名称', 'required' => true, 'hint' => '不要超过50个字符', 'size' => '50', 'tip' => '中文名称必填'
        ),
        'ename' => array(
            'text' => '分类英文名称', 'required' => true, 'rule' => '/^[a-z]\w{0,49}$/', 'hint' => '只允许小写字母开头的字母数字下划线且不超过50字符', 'size' => '50', 'tip' => '只允许小写字母开头的字母数字下划线且不超过50字符'
        ),
        'pid' => array(
            'text' => '父级分类Id', 'required' => true, 'rule' => '/^[\d|-]+$/', 'hint' => '若为一级分类请填写-1', 'tip' => '必须为数字'
        ),
        'sort' => array(
            'text' => '排序', 'required'=> true, 'rule' => '/^\d+$/', 'hint' => '数值越小排位越靠前', 'tip' => '必须为数字', 'value' => 1,
        ),
        'status' => array(
            'text' => '状态', 'required'=> true, 'type' => 'select', 'tip' => '请选择状态'
        ),
    );
    
    public static $formFieldsCustom = array();//you can put some arrays here to custom your formFields 

    function __construct() {        
        $options = array(
            'key' => 'id',
            'table' => $this->table,
            'columns' => array(
                'id' => 'id',
                'name' => 'name',
                'ename' => 'ename',
                'pid' => 'pid',
                'level' => 'level',
                'path' => 'path',
                'sort' => 'sort',
                'status' => 'status',
                'createdTime' => 'createdTime',
                'updatedTime' => 'updatedTime',
                'attributeData' => 'attributeData',
                
            ),
            'saveNeeds' => array(
                'name',
                'ename',
                'pid',
                'level',
                'sort',
                'status',
            )            
        );        
        self::$formFields = array_merge(self::$formFields, self::$formFieldsCustom);
        self::$cachePrefix = $this->table;
        parent::init($options);
    }
    
    function save() {
        $insertFlag = false;
        if ($this->pid != -1) {
            $obj = new $this->className;            
            $obj->id = $this->pid;
            try {
                $obj->load();
            } catch (Exception $e) {
                throw new Exception("pid : {$this->pid} not found");
            }
            $this->level = $obj->level + 1;
        } else {
            $this->level = 1;
        }        
        if (is_null($this->id)) {
            foreach (array('name', 'ename') as $v) {
                $obj = new $this->className;
                $obj->$v = $this->$v;
                if ($obj->count() > 0) throw new Exception("{$v} : {$this->$v} 已存在");
            }            
            $this->createdTime = $this->updatedTime = time();
            $insertFlag = true;
        } else {
            foreach (array('name', 'ename') as $v) {
                $obj = new $this->className;
                $obj->$v = $this->$v;
                $obj->whereAnd('id', "<> '{$this->id}'");
                if ($obj->count() > 0) throw new Exception("{$v} : {$this->$v} 已存在");
            }
            $this->updatedTime = time();
            if ($this->pid != -1) {
                $this->path = "{$obj->path},{$this->id}";
            } else {
                $this->path = $this->id;
            }
            
        }        
        parent::save();        
        if ($insertFlag) {
            if ($this->pid == -1) {
                $this->path = $this->id;
            } else {
                $this->path = "{$obj->path},{$this->id}";
            }
            parent::save();
        }
        $cache = new Cache_Lite();
        $cache->clean(self::$cachePrefix);//clean this group self::$cachePrefix Cache
    }
    
    function switchStatus(){
        switch ($this->status) {
            case self::STATUS_ACTIVE :
                $this->status = self::STATUS_INACTIVE;
                break;
            case self::STATUS_INACTIVE:
                $this->status = self::STATUS_ACTIVE;
            default:                
                break;
        }
        $this->save();
    }

    function firstCategory(){
        $cacheId = self::$cachePrefix . '_firstCategory';
        $cacheLite = new Cache_Lite(array(
            'lifeTime' => 60 * 60 * 24
        ));
        if ($result = $cacheLite->get($cacheId, self::$cachePrefix)) {
            return $result;
        } else {
            $obj = new $this->className;
            $obj->status = self::STATUS_ACTIVE;
            $obj->level = 1;
            $obj->pid = -1;
            $result = $obj->find(array(
                'order' => array('sort' => 'ASC'),
            ));
            $cacheLite->save($result, $cacheId, self::$cachePrefix);
            return $result;
        }        
    }
    
    function getCategories() {
        $cacheId = self::$cachePrefix . '_getCategories';
        if (isset(self::$allCategories[$cacheId])) return self::$allCategories[$cacheId];        
        $cacheLite = new Cache_Lite(array(
            'lifeTime' => 60 * 60 * 24,
        ));
        if ($result = $cacheLite->get($cacheId, self::$cachePrefix)) {
            self::$allCategories[$cacheId] = $result;
            return $result;
        } else {
            $result = array();
            $order = array('sort' => 'ASC');
            $obj = new $this->className;
            $obj->status = self::STATUS_ACTIVE;
            $obj->level = 1;
            $obj->pid = -1;
            $first = $obj->find(array(
                'order' => $order,
            ));
            $fields = array_merge(array('id', 'name', 'ename', 'pid'), array_keys(self::$formFieldsCustom));
            $obj->level = 2;
            foreach ($first as $eachFirst) {
                foreach ($fields as $field) {
                    $result[$eachFirst->id][$field] = $eachFirst->get($field);                                     
                }
                $obj->pid = $eachFirst->id;
                foreach ($obj->find(array('order' => $order)) as $second) {
                    foreach ($fields as $field) $result[$eachFirst->id]['children'][$second->id][$field] = $second->get($field); 
                }                  
            }
            self::$allCategories[$cacheId] = $result;
            $cacheLite->save($result, $cacheId, self::$cachePrefix);
            return $result;
        }        
    }
    
    public function loadById($id) {
        $result = null;
        if (is_numeric($id)) {
            $categories = $this->getCategories();
            if (isset($categories[$id])) {
                $result = $categories[$id];
            } else {
                foreach ($categories as $first) {
                    if (isset($first['children'])) {
                        foreach ($first['children'] as $key => $val) {
                            if ($id == $key) {
                                $result = $val;
                                break 2;
                            }    
                        }                        
                    }
                }
            }
        }
        return $result;
    }
    
    function treeOptions($level = 1, $pid = -1) {
        $cachId = self::$cachePrefix . "_treeOptions";
        $cacheLite = new Cache_Lite();
        $cacheLite->setLifeTime(60 * 60 * 24);
        if ($result = $cacheLite->get($cachId, self::$cachePrefix)) {
            self::$treeOptions = $result;
        } else {
            $obj = new $this->className;        
            $obj->status = self::STATUS_ACTIVE;
            $obj->level = $level;
            $obj->pid = $pid;
            $result = $obj->find(array(
                'order' => array('sort' => 'ASC')
            ));
            $prefixStr = $level > 1 ? str_repeat('&nbsp;', ($level - 2) * 4) .'├ ' : null;
            foreach ($result as $o) {
                self::$treeOptions[$o->id] = $prefixStr .$o->name;
                $this->treeOptions($level+1, $o->id);
            };
            if($level == 1) $cacheLite->save(self::$treeOptions, $cachId, self::$cachePrefix);
        }
        return self::$treeOptions;
    }
}
?>
