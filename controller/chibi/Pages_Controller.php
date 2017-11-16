<?php
//dengjing34@vip.qq.com
class Pages_Controller extends Chibi_Controller{
    function __construct() {
        parent::__construct();
    }
    
    function pagesList() {
        $o = new Pages();
        if ($ids = $this->url->post('id')) {
            foreach ($ids as $id) {
                try {$o->delete($id);} catch (Exception $e) {$this->_error($e->getMessage());return;}
            }
            $this->_success(Url::getRefer());return;
        }        
        $where = array();
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
        $view = new View('chibi/pages/pagesList', compact('objs', 'controller', 'pager'));
        $this->render($view->render());
    }
    
    function pagesAdd() {
        $o = new Pages();
        $form = new Form(Pages::$formFields);
        if ($this->url->post('submit')) {
            $validateResult = $form->validatePost();
            if($validateResult['error']) {
                $this->_error($validateResult['error']);return;
            }            
            foreach ($validateResult['fields'] as $k => $v) $o->set($k, $v);           
            try {
                $o->save();
                $this->_success(Url::siteUrl("chibi/pages/pagesList"), '操作成功');return;
            } catch (Exception $e) {
                $this->_error($e->getMessage());return;
            }
        }
        try {
            $form->set('englishName', array('options' => Pages::getEnglishNameOptions()));
        } catch (Exception $e) {
            $this->_error($e->getMessage());return;
        }       
        $scripts = new View('chibi/components/sValidation');
        $view =  new View('chibi/components/tmpAdd', compact('scripts', 'form'));
        $this->render($view->render());
    }
    
    function pagesModify() {
        $id = $this->url->get('id');
        $o = new Pages();
        try {
            $o->load($id);
        } catch (Exception $e) {
            $this->_error($e->getMessage());return;
        }
        $form = new Form(Pages::$formFields);
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
        try {
            $form->set('englishName', array('options' => Pages::getEnglishNameOptions()));
        } catch (Exception $e) {
            $this->_error($e->getMessage());return;
        }        
        $scripts = new View('chibi/components/sValidation');
        $view =  new View('chibi/components/tmpModify');
        $view->scripts = $scripts->render();
        $view->form = $form;
        $view->refer = Url::getRefer() ? Url::getRefer() : Url::siteUrl("chibi/pages/pagesList") ;
        $this->render($view->render());
    }
}

?>
