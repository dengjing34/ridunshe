<?php
//dengjing34@vip.qq.com
class Works extends Data {
    public $id, $title, $intro, $projectId, $projectName, $pic, $heart, $hit, $createdTime, $updatedTime, $attributeData;
    public static $formFields = array(
        'title' => array(
            'text' => '作品标题', 'hint' => '非必填,但是尽量填写以便被搜索引擎收录', 'size' => 50,
        ),
        'intro' => array(
            'text' => '作品介绍', 'type' => 'textarea', 'hint' => '非必填,但是尽量填写以便被搜索引擎收录,会显示在作品详情的下方', 'width' => '400', 'height' => '60',
        ),
        'pic' => array(
            'text' => '作品图片', 'type' => 'file', 'required' => true, 'hint' => '请上传宽度在700以内的图片', 'size' => 40, 'tip' => '必须上传作品图片', 'resizable' => true, //'watermark' => Uploader::WATER_MARK_TEXT,
        ),
        'heart' => array(
            'text' => '喜欢次数', 'value' => 0, 'rule' => "/^\d+$/", 'hint' => '访客点击喜欢的次数', 'size' => 10, 'tip' => '必须是数字,默认为0',
        ),
        'hit' => array(
            'text' => '浏览次数', 'value' => 0, 'rule' => "/^\d+$/", 'hint' => '访客点击喜欢的次数', 'size' => 10, 'tip' => '必须是数字,默认为0',
        ),
        'sort' => array(
            'text' => '排序', 'rule' => "/^\d+$/", 'hint' => '项目排序,数值越大越靠前', 'size' => 10, 'tip' => '请输入一个数字',
        ),
        'projectId' => array(
            'text' => '所属项目', 'required' => true, 'type' => 'select', 'options' => array(), 'tip' => '请选择作品所属的项目',
        ),
    );
    public static $defaultSort = array('sort' => 'DESC');
    function __construct() {
        $options = array(
            'key' => 'id',
            'table' => 't_works',
            'columns' => array(
                'id' => 'id',
                'title' => 'title',
                'intro' => 'intro',
                'projectId' => 'projectId',
                'projectName' => 'projectName',
                'pic' => 'pic',
                'heart' => 'heart',
                'hit' => 'hit',
                'sort' => 'sort',
                'createdTime' => 'createdTime',
                'updatedTime' => 'updatedTime',
                'attributeData' => 'attributeData',
            ),
            'saveNeeds' => array(
                'pic',
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
        if (is_null($this->projectId)) throw new Exception('please select a project for this works');
        $project = new Project();
        try {
            $project->load($this->projectId);
        } catch (Exception $e) {
            throw new Exception("projectId {$this->projectId} not exists");
        }
        $this->projectName = $project->name;
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
    
    public static function heartIncrease($id) {
        $o = new self();
        try {
            $o->load($id);
            $o->heart++;
            $o->save();
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }        
    }
    
    public function hitIncrease() {
        if (!is_null($this->id)) {
            $this->hit++;
            try {
                $this->save();
            } catch (Exception $e) {
                throw new Exception($e->getMessage());
            }
        }
        return $this;
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

?>
