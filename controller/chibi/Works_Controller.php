<?php
//dengjing34@vip.qq.com
class Works_Controller extends Chibi_Controller{
    function __construct() {
        parent::__construct();
    }
    
    function worksList() {
        $o = new Works();
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
        $result = Project::selectOptions();
        $projectOptions = array(
            '<option value=""></option>',
        );
        foreach ($result as $r) {
            $selected = $r->id == $this->url->get('projectId') ? ' selected="selected"' : null;
            $projectOptions[] = "<option{$selected} value=\"{$r->id}\">{$r->name}</option>"; 
        } 
        $pager = Pager::showPage($total);
        $controller = $this->controllerName;
        $view = new View('chibi/works/worksList', compact('objs', 'controller', 'pager', 'projectOptions'));
        $view->url = $this->url;
        $this->render($view->render());
    }
    
    function worksAdd() {
        $o = new Works();
        $form = new Form(Works::$formFields);
        if ($this->url->post('submit')) {
            $validateResult = $form->validatePost();
            if($validateResult['error']) {
                $this->_error($validateResult['error']);return;
            }            
            foreach ($validateResult['fields'] as $k => $v) $o->set($k, $v);           
            try {
                $o->save();
                $this->_success(Url::siteUrl("chibi/works/worksList"), '操作成功');return;
            } catch (Exception $e) {
                $this->_error($e->getMessage());return;
            }
        }        
        $result = Project::selectOptions();
        $projectOptions = array();
        foreach ($result as $r) {
            $projectOptions[$r->id] = $r->name;
        }
        $form->set('projectId', array('options' => $projectOptions));        
        $scripts = new View('chibi/components/sValidation');
        $view =  new View('chibi/components/tmpAdd', compact('scripts', 'form'));
        $this->render($view->render());
    }
    
    function worksModify() {
        $id = $this->url->get('id');
        $o = new Works();
        try {
            $o->load($id);
        } catch (Exception $e) {
            $this->_error($e->getMessage());return;
        }
        $form = new Form(Works::$formFields);
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
        $result = Project::selectOptions();
        $projectOptions = array();
        foreach ($result as $r) {
            $projectOptions[$r->id] = $r->name;
        }
        $form->set('projectId', array('options' => $projectOptions));        
        $scripts = new View('chibi/components/sValidation');
        $view =  new View('chibi/components/tmpModify');
        $view->scripts = $scripts->render();
        $view->form = $form;
        $view->refer = Url::getRefer() ? Url::getRefer() : Url::siteUrl("chibi/works/worksList") ;
        $this->render($view->render()); 
    }
}

?>
