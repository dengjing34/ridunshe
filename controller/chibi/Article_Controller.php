<?php
//dengjing34@vip.qq.com
class Article_Controller extends Chibi_Controller{
    function __construct() {
        parent::__construct();
    }
    
    function articleList() {        
        $o = new Article();
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
        $view = new View('chibi/article/articleList', compact('objs', 'controller', 'pager'));
        $this->render($view->render());
    }
    
    function articleAdd() {
        $o = new Article();
        $form = new Form(Article::$formFields);
        if ($this->url->post('submit')) {
            $validateResult = $form->validatePost();
            if($validateResult['error']) {
                $this->_error($validateResult['error']);return;
            }            
            foreach ($validateResult['fields'] as $k => $v) $o->set($k, $v);
            try {
                $o->save();
                $this->_success(Url::siteUrl("chibi/article/articleList"), '操作成功');return;
            } catch (Exception $e) {
                $this->_error($e->getMessage());return;
            }
        }
        $category = new ArticleCategory();
        $form->set('cid', array('options' => $category->treeOptions()));
        $form->set('status', array('options' => Article::$_status));
        $form->customAssign(array('status' => Article::STATUS_ACTIVE));        
        $scripts = new View('chibi/components/sValidation');
        $view =  new View('chibi/components/tmpAdd');
        $view->scripts = $scripts->render();
        $view->form = $form;
        $this->render($view->render());
    }
    
    function articleModify() {
        $id = $this->url->get('id');
        $o = new Article();
        try {
            $o->load($id);
        } catch (Exception $e) {
            $this->_error($e->getMessage());return;
        }
        $form = new Form(Article::$formFields);
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
        $category = new ArticleCategory();
        $form->set('cid', array('options' => $category->treeOptions()));
        $form->set('status', array('options' => Article::$_status));        
        $scripts = new View('chibi/components/sValidation');
        $view =  new View('chibi/components/tmpModify');
        $view->scripts = $scripts->render();
        $view->form = $form;
        $view->refer = Url::getRefer() ? Url::getRefer() : Url::siteUrl("chibi/article/articleList") ;
        $this->render($view->render());
    }
}
?>
