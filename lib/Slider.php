<?php
//dengjing34@vip.qq.com
class Slider extends Data{
    public $id, $title, $pic, $url, $sort, $createdTime, $updatedTime, $attributeData;
    public static $formFields = array(
        'title' => array(
            'text' => '幻灯片标题', 'required' => true, 'hint' => '尽量简单明了,不超过10个字,如"联系我们", "关于我们"之类', 'size' => 50, 'tip' => '不符合规范,必须填写标题'
        ),
        'pic' => array(
            'text' => '幻灯图片', 'type' => 'file', 'required' => true, 'hint' => '请上传721*432的图片', 'size' => 40, 'tip' => '必须上传图片', //'resizable' => true, 'watermark' => Uploader::WATER_MARK_TEXT,
        ),
        'url' => array(
            'text' => '跳转地址', 'required' => true, 'size' => 40, 'hint' => '点击幻灯后跳转的url地址,只需填写主机名后面的部分,如:http://www.ridunshe.com/<span style="color:red;">contact</span> 中的contact',
        ),
        'sort' => array(
            'text' => '排序', 'rule' => "/^\d+$/", 'hint' => '数值越小排位越靠前', 'tip' => '只能填写数字', 'value' => 0,
        ),
    );
    function __construct() {
        $options = array(
            'key' => 'id',
            'table' => 't_slider',
            'columns' => array(
                'id' => 'id',
                'title' => 'title',
                'pic' => 'pic',
                'url' => 'url',
                'sort' => 'sort',
                'createdTime' => 'createdTime',
                'updatedTime' => 'updatedTime',
                'attributeData' => 'attributeData',
            ),
            'saveNeeds' => array(
                'title',
                'pic',
                'url',
            )
        );
        parent::init($options);
    }
    
    function save() {
        if (is_null($this->id)) {            
            $this->createdTime = time();            
        } else {            
            $this->updatedTime = time();
        }
        parent::save();
    }
    
    public static function getSliders($limit = 8) {
        $o = new self();
        $order = array('sort' => 'ASC');
        $result = array();
        foreach ($o->find(array(
            'order' => $order,
            'limit' => $limit,
        )) as $oo) {
            $result[$oo->id]['title'] = $oo->title;
            $result[$oo->id]['pic'] = Url::fileUrl($oo->pic);
            $result[$oo->id]['url'] = Url::siteUrl($oo->url);
        }
        return $result;
    }
}

?>
