<?php
//dengjing34@vip.qq.com
class Category_Controller extends Chibi_Controller {
    protected $objName;
    function __construct() {
        parent::__construct();
    }
    
    function index() {
        $this->cateList();
    }
    
    function cateList() {
        $_obj = new $this->objName();
        if ($ids = $this->url->post('id')) {
            foreach ($ids as $id) {
                try {$_obj->delete($id);} catch (Exception $e) {$this->_error($e->getMessage());return;}
            }
            $this->_success(Url::getRefer());return;
        }
        if ($this->url->get('action') == 'switch') {
            $id = $this->url->get('id');
            if (!is_null($id) && is_numeric($id)) {
                try {
                    $_obj->load($id);
                    $_obj->switchStatus();
                    Url::redirect(Url::getRefer());
                } catch (Exception $e) {
                    $this->_error($e->getMessage());return;
                }                
            }
        }
        $where = array ();
        foreach (array('name', 'pid', 'level', 'status') as $val) {
            $$val = $this->url->get($val);
            if ($$val && in_array($val, array('pid', 'level', 'status'))) {
                $where[] = array($val, "= '{$$val}'");
                continue;
            }
            if ($$val) $where[] = array($val, "like '%{$$val}%'");
        }
        try {
            $total = $_obj->count(array('whereAnd' => $where));
        } catch (Exception $e) {
            $this->_error($e->getMessage());return;
        }
        $page = Pager::requestPage($total);
        $limit = ($page - 1) * Pager::$pageSize . "," . Pager::$pageSize;
        $objs = $_obj->find(
            array(
                'whereAnd' => $where,
                'limit' => $limit,
            )
        );
        $firstCategory = $_obj->firstCategory();
        $view = new View('chibi/category/categoryList');
        $view->pager = Pager::showPage($total);
        $view->objs = $objs;
        $view->url = $this->url;
        $view->controller = $this->controllerName;
        $view->firstCategory = $firstCategory;
        $this->render($view->render());
    }
    
    function cateAdd() {
        $_obj = new $this->objName();
        $form = new Form(Category::$formFields);
        $form->set('status', array('options' => Category::$_status));        
        $form->customAssign(array('status' => Category::STATUS_ACTIVE));
        if ($this->url->post('submit')) {
            $validateResult = $form->validatePost();
            if($validateResult['error']) {
                $this->_error($validateResult['error']);return;
            }            
            foreach ($validateResult['fields'] as $k => $v) {
                $_obj->set($k, $v);
            }
            try {
                $_obj->save();
                $this->_success(Url::siteUrl("chibi/{$this->controllerName}/{$this->controllerName}List"), '操作成功');return;
            } catch (Exception $e) {
                $this->_error($e->getMessage());return;
            }
        }        
        $result = $_obj->find(array('limit' => '1'));
        $lastId = empty ($result) ? array('sort' => 1) : array('sort' => $result[0]->id + 1);
        $form->customAssign($lastId);
        $scripts = new View('chibi/components/sValidation');
        $view = new View('chibi/components/tmpAdd');
        $view->scripts = $scripts->render();
        $view->form = $form;
        $this->render($view->render());
    }
    
    function cateModify() {
        $id = $this->url->get('id');
        if (is_null($id) || !is_numeric($id)) {
            $this->_error('id is null or not a number');return;
        }
        $_obj = new $this->objName();
        $_obj->id = $id;
        $form = new Form(Category::$formFields);
        $form->set('status', array('options' => Category::$_status));
        try {
            $_obj->load();
        } catch (Exception $e) {
             $this->_error($e->getMessage());return;
        }
        if ($this->url->post('submit')) {
            $validateResult = $form->validatePost();
            if($validateResult['error']) {
                $this->_error($validateResult['error']);return;
            }
            foreach ($validateResult['fields'] as $k => $v) {
                $_obj->set($k, $v);
            }
            try {
                $_obj->save();
                $this->_success($this->url->post('refer'));return;
            } catch (Exception $e) {
                $this->_error($e->getMessage());return;
            }
        }        
        $form->assign($_obj);
        $scripts = new View('chibi/components/sValidation');
        $view =  new View('chibi/components/tmpModify');
        $view->scripts = $scripts->render();
        $view->form = $form;
        $view->refer = Url::getRefer() ? Url::getRefer() : Url::siteUrl("chibi/{$this->controllerName}/{$this->controllerName}List") ;
        $this->render($view->render());
    }
}
?>
