<?php
//dengjing34@vip.qq.com
class News_Controller extends Chibi_Controller{
    function __construct() {
        parent::__construct();
    }
    
    function newsList() {
        $o = new News();
        if (($ids = $this->url->post('id'))) {
            foreach ($ids as $id) {
                try {$o->delete($id);} catch (Exception $e) {$this->_error($e->getMessage());return;}
            }
            $this->_success(Url::getRefer());return;
        }        
        $where = array();
        foreach (array('title') as $val) {
            $$val = $this->url->get($val);
            switch ($val) {
                default:
                     if ($$val) {
                         $where[] = array($val, "like '%{$$val}%'");
                     }
                     break;
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
        $view = new View('chibi/news/newsList', compact('objs', 'controller', 'pager'));
        $view->url = $this->url;
        $this->render($view->render());
    }
    
    function newsAdd() {
        $o = new News();
        $form = new Form(News::$formFields);
        if ($this->url->post('submit')) {
            $validateResult = $form->validatePost();
            if($validateResult['error']) {
                $this->_error($validateResult['error']);return;
            }            
            foreach ($validateResult['fields'] as $k => $v) {
                $o->set($k, $v);
            }
            try {
                $o->save();
                $this->_success(Url::siteUrl("chibi/news/newsList"), '操作成功');return;
            } catch (Exception $e) {
                $this->_error($e->getMessage());return;
            }
        }   
        $scripts = new View('chibi/components/sValidation');
        $view =  new View('chibi/components/tmpAdd', compact('scripts', 'form'));
        $this->render($view->render());
    }
    
    function newsModify() {
        $id = $this->url->get('id');
        $o = new news();
        try {
            $o->load($id);
        } catch (Exception $e) {
            $this->_error($e->getMessage());return;
        }
        $form = new Form(news::$formFields);
        $form->assign($o);
        if ($this->url->post('submit')) {
            $validateResult = $form->validatePost();
            if($validateResult['error']) {
                $this->_error($validateResult['error']);return;
            }
            foreach ($validateResult['fields'] as $k => $v) {
                $o->set($k, $v);
            }
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
        $view->refer = Url::getRefer() ? Url::getRefer() : Url::siteUrl("chibi/news/newsList") ;
        $this->render($view->render()); 
    }
}

