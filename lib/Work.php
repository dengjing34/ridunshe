<?php
//dengjing34@vip.qq.com
class Work extends Data {
    public $id;
    public $title;
    public $category_id;
    public $category_zh;
    public $category_en;
    public $sub_title;
    public $intro;
    public $cover;
    public $year;
    public $area;
    public $banner_pic;
    public $banner_sort;
    public $home_pic;
    public $home_sort;
    public $pic;
    public $sort;
    public $content;
    public $create_time;
    public $update_time;
    public static $formFields = array(
        'title' => array(
            'text' => '名称', 'required' => true, 'hint' => '详情页左上方展示的作品名称', 'size' => 50,
        ),
        'sub_title' => array(
            'text' => '副标题', 'required' => true, 'hint' => '详情页右上方展示的作品副标题', 'size' => 50,
        ),
        'year' => array(
            'text' => '年份', 'required' => true, 'hint' => '详情页左上方展示的作品年份, 如:2014', 'size' => 50,
        ),
        'area' => array(
            'text' => '城市', 'required' => true, 'hint' => '详情页左上方展示的作品所在城市, 如:中国>四川>成都', 'size' => 50,
        ),
        'intro' => array(
            'text' => '简介', 'type' => 'textarea', 'required' => true, 'hint' => '详情页右上方的简短的文字介绍', 'width' => '800', 'height' => '180',
        ),
        'cover' => array(
            'text' => '封面图片', 'type' => 'file', 'required' => true, 'hint' => '作品列表页展示的封面图片, 396 × 264', 'size' => 40, 'tip' => '必须上传封面', 'resizable' => true, //'watermark' => Uploader::WATER_MARK_TEXT,
        ),
        'pic' => array(
            'text' => '详情页主图', 'type' => 'file', 'required' => true, 'hint' => '作品详情页的第一张图片, 1200 × 850', 'size' => 40, 'tip' => '必须上传详情页主图', 'resizable' => true, //'watermark' => Uploader::WATER_MARK_TEXT,
        ),
        'banner_pic' => array(
            'text' => '首页banner', 'type' => 'file', 'hint' => '在首页展示的5张轮播图片(不上传则不在首页展示), 1200 × 500', 'size' => 40, 'resizable' => true, //'watermark' => Uploader::WATER_MARK_TEXT,
        ),
        'banner_sort' => array(
            'text' => '首页banner排序', 'rule' => "/^\d+$/", 'hint' => '在首页展示的5张轮播图片排序,数值越大越靠前', 'size' => 10, 'tip' => '请输入一个数字',
        ),
        'home_pic' => array(
            'text' => '首页底部图片', 'type' => 'file', 'hint' => '在首页底部展示的4张图片(不上传则不在首页展示), 294 × 294', 'size' => 40, 'resizable' => true, //'watermark' => Uploader::WATER_MARK_TEXT,
        ),
        'home_sort' => array(
            'text' => '首页底部图片排序', 'rule' => "/^\d+$/", 'hint' => '在首页底部展示的4张图片排序,数值越大越靠前', 'size' => 10, 'tip' => '请输入一个数字',
        ),
        'category_id' => array(
            'text' => '分类', 'required' => true, 'type' => 'select', 'options' => array(), 'hint' => '作品所属的类别', 'tip' => '请选择分类',
        ),
        'content' => array(
            'text' => '详细介绍', 'type' => 'ckeditor', 'required' => true, 'tip' => '详细介绍必填',
        ),
        'sort' => array(
            'text' => '排序', 'required' => true, 'rule' => "/^\d+$/", 'hint' => '排序,数值越大越靠前', 'size' => 10, 'tip' => '请输入一个数字',
        ),
    );
    public static $defaultSort = array('sort' => 'DESC');
    function __construct() {
        $options = array(
            'key' => 'id',
            'table' => 'works',
            'columns' => array(
                'id' => 'id',
                'title' => 'title',
                'category_id' => 'category_id',
                'category_zh' => 'category_zh',
                'category_en' => 'category_en',
                'sub_title' => 'sub_title',
                'intro' => 'intro',
                'cover' => 'cover',
                'year' => 'year',
                'area' => 'area',
                'banner_pic' => 'banner_pic',
                'banner_sort' => 'banner_sort',
                'home_pic' => 'home_pic',
                'home_sort' => 'home_sort',
                'pic' => 'pic',
                'sort' => 'sort',
                'content' => 'content',
                'create_time' => 'create_time',
                'update_time' => 'update_time',
            ),
            'saveNeeds' => array(
//                'pic',
            )
        );
        parent::init($options);
    }
    
    function save() {
        if (is_null($this->id)) {
            $this->create_time = time();
        } else {
            $this->update_time = time();
        }
        if (is_null($this->category_id)) throw new Exception('please select a category for this work');
        $cate = new WorkCategory();
        try {
            $cate->load($this->category_id);
        } catch (Exception $e) {
            throw new Exception("category {$this->category_id} not exists");
        }
        $this->category_zh = $cate->name;
        $this->category_en = $cate->ename;
        parent::save();
    }
    
    public function find($options = array()) {
        if (!isset($options['order'])) $options['order'] = self::$defaultSort;
        return parent::find($options);
    }

    public static function lastWorks() {
        $o = new self();
        return $o->find(array(
            'limit' => '20'
        ));
    }
    
    public static function near($obj) {
        if (is_null($obj->id)) return array();        
        $o = new self();
        $o->projectId = $obj->projectId;
        $prevWhere = array(
            array('sort', ">= {$obj->sort}"),
            array('id', '!= ' . $obj->id),
        );
        $nextWhere = array(
            array('sort', "<= {$obj->sort}"),
            array('id', '!= ' . $obj->id),
        );
        $limit = '1';
        $order = array('sort' => 'ASC');
        $result['prev'] = current($o->find(array('whereAnd' => $prevWhere, 'limit' => $limit, 'order' => $order)));
        $result['next'] = current($o->find(array('whereAnd' => $nextWhere, 'limit' => $limit)));
//        if (!$result['next']) {
//            $p = new Project();
//            $nextProject = current($p->find(array(
//                'whereAnd' => array(
//                    array('id', "< {$o->projectId}"),
//                ),
//                'limit' => $limit,
//            )));
//            if ((bool)$nextProject) {
//                $o->projectId = $nextProject->id;
//                $result['next'] = current($o->find(array(
//                    'limit' => $limit,
//                )));
//            }
//        }
//        if (!$result['prev']) {
//            $p = new Project();
//            $prevProject = current($p->find(array(
//                'whereAnd' => array(
//                    array('id', "> {$o->projectId}"),
//                ),
//                'limit' => $limit,
//            )));
//            if ((bool)$prevProject) {
//                $o->projectId = $prevProject->id;
//                $result['prev'] = current($o->find(array(
//                    'limit' => $limit,
//                    'order' => $order,
//                )));
//            }
//        }
        return $result;
    }
}
