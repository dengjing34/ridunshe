<?php
//dengjing34@vip.qq.com
require_once CONTROLLER_DIR  . "chibi/Category_Controller.php";
class Workcate_Controller extends Category_Controller {
    function __construct() {
        parent::__construct();
        $this->objName = 'WorkCategory';
    }
    
    function index() {
        $this->workcateList();
    }
    
    function workcateList() {
        parent::cateList();
    }
    
    function workcateAdd() {
        parent::cateAdd();
    }
    
    function workcateModify() {
        parent::cateModify();
    }
}

