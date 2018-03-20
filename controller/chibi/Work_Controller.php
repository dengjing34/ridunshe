<?php
//dengjing34@vip.qq.com
class Work_Controller extends Chibi_Controller{
    function __construct() {
        parent::__construct();
    }
    
    function workList() {
        $o = new Work();
        if ($ids = $this->url->post('id')) {
            foreach ($ids as $id) {
                try {$o->delete($id);} catch (Exception $e) {$this->_error($e->getMessage());return;}
            }
            $this->_success(Url::getRefer());return;
        }        
        $where = array();
        foreach (array('title', 'projectId') as $val) {
            $$val = $this->url->get($val);
            switch ($val) {
                case 'projectId':
                    if ($$val) $o->$val = $$val;
                    break;
                default:
                     if ($$val) $where[] = array($val, "like '%{$$val}%'");
            }           
        } 
        try {
            $total = $o->count(array('whereAnd' => $where));
        } catch (Exception $e) {
            $this->_error($e->getMessage());return;
        }        
        $page = Pager::requestPage($total);        
        $objs = $o->find(
            array(
                'whereAnd' => $where,
                'limit' => Pager::limit($page),
            )
        );
        $pager = Pager::showPage($total);
        $controller = $this->controllerName;
        $view = new View('chibi/works/workList', compact('objs', 'controller', 'pager'));
        $view->url = $this->url;
        $this->render($view->render());
    }
    
    function workAdd() {
        $o = new Work();
        $form = new Form(Work::$formFields);
        if ($this->url->post('submit')) {
            $validateResult = $form->validatePost();
            if($validateResult['error']) {
                $this->_error($validateResult['error']);return;
            }            
            foreach ($validateResult['fields'] as $k => $v) $o->set($k, $v);           
            try {
                $o->save();
                $this->_success(Url::siteUrl("chibi/work/workList"), '操作成功');return;
            } catch (Exception $e) {
                $this->_error($e->getMessage());return;
            }
        }        
        $result = WorkCategory::selectOptions();
        $projectOptions = array();
        foreach ($result as $r) {
            $projectOptions[$r->id] = $r->name;
        }
        $form->set('category_id', array('options' => $projectOptions));
        $lastRow = $o->find(array('limit' => '1'));
        $sorts = array('banner_sort' => 0, 'home_sort' => 0);
        if (empty($lastRow)) {
            $sorts['sort'] = 1;
        } else {
            $sorts['sort'] = $lastRow[0]->id + 1;
        }
        $form->customAssign($sorts);     
        $scripts = new View('chibi/components/sValidation');
        $view =  new View('chibi/components/tmpAdd', compact('scripts', 'form'));
        $this->render($view->render());
    }
    
    function workModify() {
        $id = $this->url->get('id');
        $o = new Work();
        try {
            $o->load($id);
        } catch (Exception $e) {
            $this->_error($e->getMessage());return;
        }
        $form = new Form(Work::$formFields);
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
        $result = WorkCategory::selectOptions();
        $projectOptions = array();
        foreach ($result as $r) {
            $projectOptions[$r->id] = $r->name;
        }
        $form->set('category_id', array('options' => $projectOptions));        
        $scripts = new View('chibi/components/sValidation');
        $view =  new View('chibi/components/tmpModify');
        $view->scripts = $scripts->render();
        $view->form = $form;
        $view->refer = Url::getRefer() ? Url::getRefer() : Url::siteUrl("chibi/work/workList") ;
        $this->render($view->render()); 
    }
}

