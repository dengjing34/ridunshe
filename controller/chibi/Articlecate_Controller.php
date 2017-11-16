<?php
//dengjing34@vip.qq.com
require_once CONTROLLER_DIR  . "chibi/Category_Controller.php";
class Articlecate_Controller extends Category_Controller {
    function __construct() {
        parent::__construct();
        $this->objName = 'ArticleCategory';
    }
    
    function index() {
        $this->articlecateList();
    }
    
    function articlecateList() {
        parent::cateList();
    }
    
    function articlecateAdd() {
        parent::cateAdd();
    }
    
    function articlecateModify() {
        parent::cateModify();
    }
}
?>
