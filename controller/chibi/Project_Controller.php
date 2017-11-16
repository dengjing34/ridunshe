<?php
//dengjing34@vip.qq.com
class Project_Controller extends Chibi_Controller{
    
    function __construct() {
        parent::__construct();
    }
    
    function projectList() {
        $o = new Project();
        if ($ids = $this->url->post('id')) {
            foreach ($ids as $id) {
                try {$o->delete($id);} catch (Exception $e) {$this->_error($e->getMessage());return;}
            }
            $this->_success(Url::getRefer());return;
        }        
        $where = array ();
        foreach (array('name') as $val) {
            $$val = $this->url->get($val);
            if ($$val) $where[] = array($val, "like '%{$$val}%'");
        }        
        try {
            $total = $o->count(array('whereAnd' => $where));
        } catch (Exception $e) {
            $this->_error($e->getMessage());return;
        }        
        $page = Pager::requestPage($total);
        $limit = ($page - 1) * Pager::$pageSize . "," . Pager::$pageSize;
        $objs = $o->find(
            array(
                'whereAnd' => $where,
                'limit' => $limit,
            )
        );
        $pager = Pager::showPage($total);
        $controller = $this->controllerName;
        $view = new View('chibi/project/projectList', compact('objs', 'controller', 'pager'));
        $view->url = $this->url;
        $this->render($view->render());
    }
    
    function projectAdd() {
        $o = new Project();
        $form = new Form(Project::$formFields);
        if ($this->url->post('submit')) {
            $validateResult = $form->validatePost();
            if($validateResult['error']) {
                $this->_error($validateResult['error']);return;
            }            
            foreach ($validateResult['fields'] as $k => $v) $o->set($k, $v);           
            try {
                $o->save();
                $this->_success(Url::siteUrl("chibi/project/projectList"), '操作成功');return;
            } catch (Exception $e) {
                $this->_error($e->getMessage());return;
            }
        }
        $result = $o->find(array('limit' => '1'));
        $lastId = empty ($result) ? array('sort' => 1) : array('sort' => $result[0]->id + 1);
        $form->customAssign($lastId);        
        $scripts = new View('chibi/components/sValidation');
        $view =  new View('chibi/components/tmpAdd', compact('scripts', 'form'));
        $this->render($view->render());
    }
    
    function projectModify() {
        $id = $this->url->get('id');
        $o = new Project();
        try {
            $o->load($id);
        } catch (Exception $e) {
            $this->_error($e->getMessage());return;
        }
        $form = new Form(Project::$formFields);
        $form->assign($o);
        if ($this->url->post('submit')) {
            $validateResult = $form->validatePost();
            if($validateResult['error']) {
                $this->_error($validateResult['error']);return;
            }
            foreach ($validateResult['fields'] as $k => $v) $o->set($k, $v);
            try {
                $o->save();
                $this->_success($this->url->post('refer'));return;
            } catch (Exception $e) {
                $this->_error($e->getMessage());return;
            }
        }
        $scripts = new View('chibi/components/sValidation');
        $view =  new View('chibi/components/tmpModify');
        $view->scripts = $scripts->render();
        $view->form = $form;
        $view->refer = Url::getRefer() ? Url::getRefer() : Url::siteUrl("chibi/project/projectList") ;
        $this->render($view->render());        
    }
}

?>
