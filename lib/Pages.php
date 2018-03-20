<?php
//dengjing34@vip.qq.com
class Pages extends Data {
    public $id, $name, $englishName, $sort, $status, $content, $createdTime, $updatedTime, $attributeData;
    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 0;
    public static $_status = array(
        self::STATUS_ACTIVE => '有效',
        self::STATUS_INACTIVE => '无效',
    );
    public static $formFields = array(
        'name' => array(
            'text' => '页面中文名称', 'required' => true, 'hint' => '尽量简单明了,不超过10个字,如"联系我们", "关于我们"之类', 'size' => 50, 'tip' => '不符合规范,必须填写页面名称'
        ),
        'englishName' => array(
            'text' => '页面英文名称', 'required' => true,
            'hint' => '页面英文名称,如 http://www.ridunshe.com/<span style="color:red;">contact</span> 中的contact', 'tip' => '请填写一个页面英文名',
        ),
        'sort' => array(
            'text' => '排序', 'required' => true, 'rule' => "/^\d+$/", 'hint' => '排序,数值越大越靠前', 'size' => 10, 'tip' => '请输入一个数字',
        ),
        'status' => array(
            'text' => '状态', 'required'=> true, 'type' => 'select', 'tip' => '请选择状态',
        ),
        'content' => array(
            'text' => '页面内容', 'type' => 'ckeditor', 'width' => 980, 'height' => 200, 'hint' => '简短的文字介绍,非必填', 'toolbar' => 'Full',
        ),
    );
    function __construct() {
        $options = array(
            'key' => 'id',
            'table' => 't_pages',
            'columns' => array(
                'id' => 'id',
                'name' => 'name',
                'englishName' => 'english_name',
                'sort' => 'sort',
                'status' => 'status',
                'content' => 'content',
                'createdTime' => 'createdTime',
                'updatedTime' => 'updatedTime',
                'attributeData' => 'attributeData',
            ),
            'saveNeeds' => array(
                'name',
                'englishName',
            )
        );
        parent::init($options);
    }
    
    function save() {
        if (is_null($this->id)) {
            if ((bool)($o = self::loadByEnglishName($this->englishName))) throw new Exception("页面[{$this->name}]的url名称[{$this->englishName}]已存在,请重新选取或直接编辑该页面");
            $this->createdTime = $this->updatedTime = time();
        } else {
            if ((bool)($o = self::loadByEnglishName($this->englishName)) && $this->id != $o->id) throw new Exception("页面[{$this->name}]的url名称[{$this->englishName}]已存在,无法完成编辑");
            $this->updatedTime = time();
        }
        parent::save();
    }

    public static function loadByEnglishName($englishName) {
        $o = new self();
        $o->englishName = $englishName;
        return current($o->find(array('limit' => 1)));
    }
    
    public static function getEnglishNameOptions() {
        try {
            $nav = Config::item('nav');
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
        $englishNameOptions = array();
        foreach ($nav as $val) {
            if (isset($val['pages']) && $val['pages'] == true) {
                 $englishNameOptions[$val['url']] = "{$val['text']} - {$val['url']}";
            }                
        }
        return $englishNameOptions;
    }
    
    public static function render($englishName) {
        if (!(bool)($o = self::loadByEnglishName($englishName))) throw new Exception("page \"{$englishName}\" not found");
        $view = new View('base/pages', compact('o'));
        return $view->render();
    }
}

?>
