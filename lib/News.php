<?php
//dengjing34@vip.qq.com
class News extends Data {
    public $id;
    public $title;
    public $intro;
    public $pic;
    public $content;
    public $created_time;
    public $updated_time;
    public static $formFields = array(
        'title' => array(
            'text' => '标题', 'required' => true, 'hint' => '新闻列表页展示新闻的标题', 'size' => 50,
        ),
        'intro' => array(
            'text' => '简介', 'type' => 'textarea', 'required' => true, 'hint' => '新闻列表页展示的新闻简介', 'tip' => '新闻简介必填', 'width' => '800', 'height' => '180',
        ),
        'pic' => array(
            'text' => '列表主图', 'type' => 'file', 'required' => true, 'hint' => '新闻列表页展示的图片, 1200 × 850', 'size' => 40, 'tip' => '必须上传列表页图片', 'resizable' => true, //'watermark' => Uploader::WATER_MARK_TEXT,
        ),
        'content' => array(
            'text' => '详细内容', 'type' => 'ckeditor', 'required' => true, 'tip' => '详细介绍必填',
        ),
    );
    public static $defaultSort = array('id' => 'DESC');
    function __construct() {
        $options = array(
            'key' => 'id',
            'table' => 'news',
            'columns' => array(
                'id' => 'id',
                'title' => 'title',
                'intro' => 'intro',
                'pic' => 'pic',
                'content' => 'content',
                'created_time' => 'created_time',
                'updated_time' => 'updated_time',
            ),
            'saveNeeds' => array(
//                'pic',
            )
        );
        parent::init($options);
    }
    
    function save() {
        if (is_null($this->id)) {
            $this->created_time = time();
        } else {
            $this->updated_time = time();
        }
        parent::save();
    }
    
    public function find($options = array()) {
        if (!isset($options['order'])) {
            $options['order'] = self::$defaultSort;
        }
        return parent::find($options);
    }
}
