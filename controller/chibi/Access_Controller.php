<?php
//dengjing@vip.qq.com
class Access_Controller extends Chibi_Controller {

    function  __construct() {
        parent::__construct();
    }

    function index() {
        $this->accessList();
    }

    function accessList() {
        $_obj = new Access();
        if ($ids = $this->url->post('id')) {
            foreach ($ids as $id) {
                if ($id == 1) continue;
                try {$_obj->delete($id);} catch (Exception $e) {$this->_error($e->getMessage());return;}
            }
            $this->_success(Url::getRefer());return;
        }
        $where = array ();
        foreach (array('name','cname') as $val) {
            $$val = $this->url->get($val);
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
        $view = new View('chibi/access/accessList');
        $view->pager = Pager::showPage($total);
        $view->objs = $objs;
        $view->url = $this->url;
        $this->render($view->render());
    }

    function accessAdd() {
        $_obj = new Access();
        $form = new Form(Access::$formFields);
        $form->set('level', array('options' => Access::$_level));
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
                $this->_success(Url::siteUrl("chibi/access/accessList"), '操作成功,用户若已分配权限需要重新登录才能查看');return;
            } catch (Exception $e) {
                $this->_error($e->getMessage());return;
            }
        }
        $_obj = new Access();
        $result = $_obj->find(array('limit' => '1'));
        $lastId = empty ($result) ? array('order' => 1) : array('order' => $result[0]->id);
        $form->customAssign($lastId);
        $scripts = new View('chibi/components/sValidation');
        $view =  new View('chibi/access/accessAdd');
        $view->scripts = $scripts->render();
        $view->form = $form;
        $this->render($view->render());
    }

    function accessModify() {
        $id = $this->url->get('id');
        if (is_null($id) || !is_numeric($id)) {
            $this->_error('id is null or not a number');return;
        }
        $_obj = new Access();
        $_obj->id = $id;
        $form = new Form(Access::$formFields);
        $form->set('level', array('options' => Access::$_level));
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
        $view =  new View('chibi/access/accessModify');
        $view->scripts = $scripts->render();
        $view->form = $form;
        $view->refer = Url::getRefer() ? Url::getRefer() : Url::siteUrl("chibi/access/accessList") ;
        $this->render($view->render());
    }
}
?>
