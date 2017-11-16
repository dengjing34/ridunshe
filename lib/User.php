<?php
//dengjing34@vip.qq.com
class User extends Data {

    public $id, $userName, $password, $mobile, $status, $sex, $role, $roleId, $email, $createdTime, $updatedTime, $attributeData;

    const STATUS_ACTIVE = 1;
    const STATUS_LEAVE = 2;
    const STATUS_STOP = 3;

    const SEX_MALE = 1;
    const SEX_FEMAL = 2;
    
    public static $_status = array(
        self::STATUS_ACTIVE => '正常',
        self::STATUS_LEAVE => '暂停',
        self::STATUS_STOP => '冻结',
    );


    public static $formFields = array(
        'userName' => array(
            'text' => '用户姓名', 'required' => true, 'rule' => '/^[a-z]+$/', 'hint' => '只允许小写字母', 'size' => '30', 'tip' => '不符合规范,只允许小写字母'
        ),
        'password' => array(
            'text' => '登录密码', 'type' => 'password', 'required' => true, 'size' => '30','tip' => '必填',
        ),
        'mobile' => array(
            'text' => '手机号码', 'required' => true, 'rule' => '/^1[358]\d{9}$/', 'size' => '30', 'tip' => '不是有效的手机号码',
        ),
        'email' => array(
            'text' => '邮箱地址', 'required' => true, 'rule' => '/^[a-zA-Z0-9_\.\-]+\@([a-zA-Z0-9\-]+\.)+[a-zA-Z0-9]{2,4}$/', 'size' => '30', 'tip' => '不符合规范', 'hint' => '如:xyz@abc.com',
        ),
        'sex' => array(
            'text' => '性别', 'required' => true, 'type' => 'radio', 'options' => array(), 'tip' => '未选择性别',
        ),
        'status' => array(
            'text' => '状态', 'required' => true, 'type' => 'select', 'options' => array(), 'tip' => '状态未选择',
        ),
        'roleId' => array(
            'text' => '角色', 'required' => true, 'type' => 'select', 'options' => array(), 'tip' => '角色未选择',
        ),
    );

    public static $_holdUsers = array ('admin','dengjing');//不允许删除的用户名字

    public static $_sex = array(
        self::SEX_MALE => '先生',
        self::SEX_FEMAL => '女士',
    );
    function __construct() {
        $options = array(
            'key' => 'id',
            'table' => 't_user',
            'columns' => array(
                'id' => 'id',
                'userName' => 'userName',
                'password' => 'password',
                'mobile' => 'mobile',
                'status' => 'status',
                'sex' => 'sex',
                'role' => 'role',
                'roleId' => 'roleId',
                'email' => 'email',
                'createdTime' => 'createdTime',
                'updatedTime' => 'updatedTime',
                'attributeData' => 'attributeData',
            ),
            'saveNeeds' => array(
                'userName',
                'password',
                'mobile',
                'status',
                'role',
                'roleId',
            )
        );
        parent::init($options);
    }

    function save() {
        $insertFlag = false;
        if (!preg_match("/^1[358]\d{9}$/", $this->mobile)) throw new Exception("mobile:{$this->mobile} is not a regular mobile!");
        if (!preg_match("/^[a-zA-Z0-9_\.\-]+\@([a-zA-Z0-9\-]+\.)+[a-zA-Z0-9]{2,4}$/", $this->email)) throw new Exception("email:{$this->email} is not a regular email address!");
        if (is_null($this->roleId)) throw new Exception("please select a role for {$this->userName}");        
        if (is_null($this->id)) {
            $this->password = md5($this->userName . $this->password);
            $this->createdTime = $this->updatedTime = time();      
            foreach (array ('userName', 'mobile', 'email') as $val) {
                $obj = new $this->className;
                $obj->$val = $this->$val;
                $total = $obj->count(array(
                    'limit' => 1
                ));
                if ($total > 0) throw new Exception("{$val}:{$this->{$val}} exists!\n");
            }
            $insertFlag = true;
        } else {
            foreach (array ('userName', 'mobile', 'email') as $val) {
                $obj = new $this->className;
                $obj->$val = $this->$val;
                $total = $obj->count(array(
                    'limit' => 1,
                    'whereAnd' => array(
                        array('id', "<> '{$this->id}'"),
                    )
                ));
                if ($total > 0) throw new Exception("{$val}:{$this->{$val}} exists!\n");
            }
            $this->updatedTime = time();
        }
        $role = new Role();
        $role->id = $this->roleId;
        try {
            $role->load();
            $this->role = $role->name;
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
        $this->htmlspecialchars();
        parent::save();
    }

    function  delete($value = null) {
        if ($value == 1) throw new Exception("[admin]无法删除");
        $obj = new $this->className;
        $obj->id = $value;
        try {
            $obj->load();
            if (in_array($obj->userName, self::$_holdUsers)) throw new Exception("用户[{$obj->userName}]因受保护而无法删除");
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }

        parent::delete($value);
    }

}

?>
