<?php
//dengjing34@vip.qq.com
class Article extends Data {
    public $id, $cid, $categoryName, $categoryEnglishName, $title, $keywords, $description, $jumpurl, $source, $hits, $author, $pic, $status, $up, $down, $content, $createdTime, $updatedTime, $attributeData;
    const STATUS_ACTIVE = 1, STATUS_INACTIVE = 2;
    public static $_status = array(
        self::STATUS_ACTIVE => '有效',
        self::STATUS_INACTIVE => '无效',
    );
    
    public static $formFields = array(
        'title' => array(
            'text' => '文章标题', 'required' => true, 'size' => '100','tip' => '文章标题必填',
        ),
        'cid' => array(
            'text' => '文章分类', 'required' => true, 'type' => 'select', 'tip' => '请选择文章分类'
        ),        
        'keywords' => array(
            'text' => 'SEO关键字', 'hint' => '用于搜索引擎优化关键字,若填写请用半角逗号","隔开', 'size' => 50
        ),
        'description' => array(
            'text' => 'SEO描述', 'type' => 'textarea', 'width' => 400, 'height' => 60, 'hint' => '用于搜索引擎优化的描述信息,不填写将截取内容的前100个字符'
        ),
        'content' => array(
            'text' => '文章内容', 'type' => 'ckeditor', 'required' => true, 'tip' => '文章内容必填',
        ),
        'pic' => array(
            'text' => '文章图片', 'type' => 'file', 'hint' => '文章的索引图', 'size' => 40
        ),        
        'jumpurl' => array(
            'text' => '跳转链接', 'hint' => '需要跳转到某个页面的地址', 'size' => 60
        ),
        'author' => array(
            'text' => '文章作者'
        ),
        'status' => array(
            'text' => '文章状态', 'required' => true, 'type' => 'select',
        ),
        'up' => array(
            'text' => '支持次数', 'required' => true, 'rule' => '/^\d+$/', 'size' => '10', 'hint' => '被"顶"的次数', 'tip' => '只能是数字', 'value' => 0
        ),
        'down' => array(
            'text' => '反对次数', 'required' => true, 'rule' => '/^\d+$/', 'size' => '10', 'hint' => '被"踩"的次数', 'tip' => '只能是数字', 'value' => 0
        ),        
    );


    function __construct() {
        $options = array(
            'key' => 'id',
            'table' => 't_article',
            'columns' => array(
                'id' => 'id',
                'cid' => 'cid',
                'categoryName' => 'category_name',
                'categoryEnglishName' => 'category_english_name',
                'title' => 'title',
                'keywords' => 'keywords',
                'description' => 'description',
                'jumpurl' => 'jumpurl',
                'source' => 'source',
                'hits' => 'hits',
                'author' => 'author',
                'pic' => 'pic',
                'status' => 'status',
                'up' => 'up',
                'down' => 'down',
                'content' => 'content',
                'createdTime' => 'createdTime',
                'updatedTime' => 'updatedTime',
                'attributeData' => 'attributeData',
                
            ),
            'saveNeeds' => array(
                'cid',
                'title',
                'status',
                'content',
            )            
        );
        parent::init($options);
    }
    
    function save() {
        if (is_null($this->id)) {
            $this->createdTime = $this->updatedTime = time();
        } else {
            $this->updatedTime = time();
        }
        if (is_null($this->cid) || !is_numeric($this->cid)) throw new Exception('category is not selected');
        $category = new ArticleCategory();
        try {
            $category->load($this->cid);
            $this->categoryName = $category->name;
            $this->categoryEnglishName = $category->ename;
        } catch (Exception $e) {
            throw new Exception("category id {$this->cid} not exists");
        }
        parent::save();
    }
    
    public function hitIncrease() {
        if (!is_null($this->id)) {
            $this->hits++;
            try {
                $this->save();
            } catch (Exception $e) {
                throw new Exception($e->getMessage());
            }
        }
        return $this;
    }
    
    public function seoDescription() {
        return !($this->description) ? mb_strimwidth(trim(str_replace(array('&nbsp;', "\n", "\r", '  '), '', strip_tags($this->content))), 0, 108, '', 'UTF-8') : $this->description;
    }
    
    public function seoKeywords() {
        return !($this->keywords) ? array($this->title) : explode(',', $this->keywords);
    }
    
    public function firstImage() {
        $result = null;
        if (preg_match('#<img.*src="(.*)".* />#U', $this->content, $matches)) {
            $result = Url::siteUrl(implode('/', array_filter(explode('/', $matches[1]))));
        }
        return $result;
    }
    
    public static function near($obj) {
        if (is_null($obj->id)) return array();        
        $o = new self();
        $o->cid = $obj->cid;
        $prevWhere = array(
            array('id', "> {$obj->id}")
        );
        $nextWhere = array(
            array('id', "< {$obj->id}")
        );
        $limit = '1';
        $order = array('id' => 'ASC');
        $result['prev'] = current($o->find(array('whereAnd' => $prevWhere, 'limit' => $limit, 'order' => $order)));
        $result['next'] = current($o->find(array('whereAnd' => $nextWhere, 'limit' => $limit)));
        return $result;
    }    
}
?>
