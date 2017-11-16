<?php
//dengjing34@vip.qq.com
class ProductCategory extends Category {    
 
    function __construct() {
        $this->table = 't_product_category';
        self::$formFieldsCustom = array(
            'description' => array(
                'text' => '英文描述', 'required' => true, 'rule' => '/^[a-z].{0,49}$/', 'hint' => '只允许小写字母开头的内容且不超过50字符', 'size' => '50', 'tip' => '只允许小写字母开头的字母数字下划线且不超过50字符'
            ),
        );
        parent::__construct();
    }
    
    public static function categoryAsNavigator() {
        $o = new self();
        $rs = array();
        foreach ($o->firstCategory() as $obj) {
            $rs[$obj->ename] = array(
                'text' => $obj->name,
                'text_en' => $obj->get('description'),
            );
        }
        return $rs;
    }    
}
?>
