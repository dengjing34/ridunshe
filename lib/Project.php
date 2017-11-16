<?php
//dengjing34@vip.qq.com
class Project extends Data {
    public $id, $name, $pic, $description, $createdTime, $updatedTime, $attributeData;
    public static $formFields = array(
        'name' => array(
            'text' => '项目名称', 'required' => true, 'hint' => '尽量简单明了,不超过30个字', 'size' => 50, 'tip' => '不符合规范,必须填写项目名称'
        ),
        'pic' => array(
            'text' => '项目图标', 'type' => 'file', 'required' => true, 'hint' => '项目的logo,用210*152像素的图片', 'size' => 40, 'tip' => '请务必上传一个200*145像素的图标', 'resizable' => true, //'watermark' => Uploader::WATER_MARK_TEXT,
        ),
        'sort' => array(
            'text' => '排序', 'rule' => "/^\d+$/", 'hint' => '项目排序,数值越大越靠前', 'size' => 10, 'tip' => '请输入一个数字',
        ),
        'description' => array(
            'text' => '项目介绍', 'type' => 'ckeditor', 'width' => 900, 'height' => 200, 'hint' => '简短的文字介绍,非必填',
        ),
    );
    
    public static $defaultSort = array('sort' => 'DESC');
    function __construct() {
        $options = array(
            'key' => 'id',
            'table' => 't_project',
            'columns' => array(
                'id' => 'id',
                'name' => 'name',
                'pic' => 'pic',
                'sort' => 'sort',
                'description' => 'description',
                'createdTime' => 'createdTime',
                'updatedTime' => 'updatedTime',
                'attributeData' => 'attributeData',
            ),
            'saveNeeds' => array(
                'name',
                'pic',
                'sort',
            )
        );
        parent::init($options);
    }
    
    public function find($options = array()) {
        if (!isset($options['order'])) $options['order'] = self::$defaultSort;
        return parent::find($options);
    }
    
    function save() {
        if (is_null($this->id)) {
            $this->createdTime = time();
        } else {
            $this->updatedTime = time();
        }
        parent::save();
    }
    
    public function seoDescription() {
        return !empty($this->description) ? mb_strimwidth(trim(str_replace(array('&nbsp;', "\n", "\r", '  '), '', strip_tags($this->description))), 0, 108, '', 'UTF-8') : $this->description;
    }
    
    public static function selectOptions($config = array('order' => array('id' => 'DESC'))) {
        $o = new self();
        return $o->find(array(
            'order' => $config['order'],
        ));
    }

}

?>
