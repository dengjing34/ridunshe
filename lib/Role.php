<?php
//dengjing34@vip.qq.com
class Role extends Data {

    public $id, $name, $cname, $createdTime, $updatedTime, $access, $attributeData;

    public static  $formFields = array(
        'name' => array(
            'text' => '角色英文名', 'required' => true, 'rule' => '/^[a-z]+$/', 'hint' => '只允许小写字母,保存后不允许修改', 'size' => '30', 'tip' => '不符合规范'
        ),
        'cname' => array(
            'text' => '角色中文名', 'required' => true, 'size' => '30','tip' => '必填',
        ),
    );
   
    function __construct() {
        $options = array(
            'key' => 'id',
            'table' => 't_role',
            'columns' => array(
                'id' => 'id',
                'name' => 'name',
                'cname' => 'cname',
                'access' => 'access',
                'createdTime' => 'createdTime',
                'updatedTime' => 'updatedTime',
                'attributeData' => 'attributeData',
            ),
            'saveNeeds' => array(
                'name',
                'cname',
                'access',
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
                    throw new Exception("{$this->name} 已存在");
                }
            }catch (Exception $e) {
                throw new Exception($e->getMessage());
            }            
        }
        $this->updatedTime = time();
        $this->htmlspecialchars();
        parent::save();
    }

    function  delete($value = null) {
        if ($value == 1) throw new Exception("[sa]角色无法删除");
        $user = new User();
        $user->roleId = $value;
        try {
            $total = $user->count();
            if ($total > 0) throw new Exception("RoleId={$value}的角色还存在{$total}个用户,无法删除");
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
        parent::delete($value);
    }

}
?>
