<?php
//dengjing34@vip.qq.com
class User_Controller extends Chibi_Controller {

    function  __construct() {
        parent::__construct();
    }

    function index() {
        $this->userList();
    }

    function userAdd() {
        $_obj = new User();
        $form = new Form(User::$formFields);
        $form->set('sex', array('options' => User::$_sex));
        $form->set('status', array('options' => User::$_status));
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
                $this->_success(Url::siteUrl("chibi/user/userList"));return;
            } catch (Exception $e) {
                $this->_error($e->getMessage());return;
            }
        }
        $role = new Role();
        $result = $role->find();
        $roleOptions = array();
        foreach ($result as $r) {
            $roleOptions[$r->id] = $r->cname;
        }
        $form->set('roleId', array('options' => $roleOptions));
        $scripts = new View('chibi/components/sValidation');
        $view = new View('chibi/user/userAdd');
        $view->scripts = $scripts->render();
        $view->form = $form;
        $this->render($view->render());
    }

    function userList() {
        $_obj = new User();
        if ($ids = $this->url->post('id')) {
            foreach ($ids as $id) {
                try {$_obj->delete($id);} catch (Exception $e) {$this->_error($e->getMessage());return;}
            }
            $this->_success(Url::getRefer());return;
        }
        $where = array ();
        foreach (array('userName', 'mobile', 'email') as $val) {
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
        $view = new View('chibi/user/userList');
        $view->pager = Pager::showPage($total);
        $view->objs = $objs;
        $view->url = $this->url;
        $this->render($view->render());
    }

    function userModify() {
        $id = $this->url->get('id');
        if (is_null($id) || !is_numeric($id)) {
            $this->_error('id is null or not a number');return;
        }
        $_obj = new User();
        $_obj->id = $id;
        $form = new Form(User::$formFields);
        $form->set('sex', array('options' => User::$_sex));
        $form->set('status', array('options' => User::$_status));
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
                if ($k == 'password') {
                    $_obj->set($k, md5($validateResult['fields']['userName'] . $v));continue;
                }
                $_obj->set($k, $v);
            }
            try {
                $_obj->save();
                $this->_success($this->url->post('refer'));return;
            } catch (Exception $e) {
                $this->_error($e->getMessage());return;
            }
        }        
        $role = new Role();
        $result = $role->find();
        $roleOptions = array();
        foreach ($result as $r) {
            $roleOptions[$r->id] = $r->cname;
        }
        $form->set('roleId', array('options' => $roleOptions));
        $form->set('userName', array(
            'readonly' => true,
            'hint' => '用户名不允许修改',
        ));
        $form->assign($_obj);
        $scripts = new View('chibi/components/sValidation');
        $view =  new View('chibi/user/userModify');
        $view->scripts = $scripts->render();
        $view->form = $form;
        $view->refer = Url::getRefer() ? Url::getRefer() : Url::siteUrl("chibi/user/userList") ;
        $this->render($view->render());
    }
}
?>
