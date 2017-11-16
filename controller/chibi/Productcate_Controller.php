<?php
//dengjing34@vip.qq.com
require_once CONTROLLER_DIR  . "chibi/Category_Controller.php";
class Productcate_Controller extends Category_Controller {
    function __construct() {
        parent::__construct();
        $this->objName = 'ProductCategory';
    }
    
    function index() {
        $this->productcateList();
    }
    
    function productcateList() {
        parent::cateList();
    }
    
    function productcateAdd() {
        parent::cateAdd();
    }
    
    function productcateModify() {
        parent::cateModify();
    }
}
?>
