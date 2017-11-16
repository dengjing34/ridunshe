<?php
//dengjing34@vip.qq.com
class Role_Controller extends Chibi_Controller{
    
    function  __construct() {
        parent::__construct();
    }

    function index() {
        $this->roleList();
    }

    function roleList() {
        $_obj = new Role();
        if ($ids = $this->url->post('id')) {
            foreach ($ids as $id) {
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
        $view = new View('chibi/role/roleList');
        $view->pager = Pager::showPage($total);
        $view->objs = $objs;
        $view->url = $this->url;
        $this->render($view->render());
    }

    function roleAdd() {
        $_obj = new Role();
        $form = new Form(Role::$formFields);
        if ($this->url->post('submit')) {
            $validateResult = $form->validatePost();
            if($validateResult['error']) {
                $this->_error($validateResult['error']);return;
            }
            $access = $this->url->post('access');
            if(is_null($access)) {
                $this->_error('请选择一些权限后再创建角色');return;
            }
            sort($access);//排序
            $validateResult['fields']['access'] = implode(',', $access);            
            foreach ($validateResult['fields'] as $k => $v) {
                $_obj->set($k, $v);
            }
            try {
                $_obj->save();
                $this->_success(Url::siteUrl("chibi/role/roleList"));return;
            } catch (Exception $e) {
                $this->_error($e->getMessage());return;
            }
        }
        $access = new Access();
        $scripts = new View('chibi/components/sValidation');
        $view =  new View('chibi/role/roleAdd');
        $view->scripts = $scripts->render();
        $view->form = $form;
        $view->access = $access->getAccessTree();
        $this->render($view->render());
    }

    function roleModify() {
        $id = $this->url->get('id');
        if (is_null($id) || !is_numeric($id)) {
            $this->_error('id is null or not a number');return;
        }
        $_obj = new Role();
        $_obj->id = $id;
        $form = new Form(Role::$formFields);
        $form->set('name', array(
            'readonly' => true,
            'hint' => '角色英文名不允许修改',
        ));//role name is readonly
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
            $access = $this->url->post('access');
            if(is_null($access)) {
                $this->_error('请选择一些权限后再保存角色');return;
            }
            sort($access);//排序
            $validateResult['fields']['access'] = implode(',', $access);
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
        $access = new Access();
        $scripts = new View('chibi/components/sValidation');
        $view =  new View('chibi/role/roleModify');
        $view->scripts = $scripts->render();
        $view->form = $form;
        $view->access = $access->getAccessTree();
        $view->roleAccess = explode(',',$_obj->access);
        $view->refer = Url::getRefer() ? Url::getRefer() : Url::siteUrl("chibi/role/roleList") ;
        $this->render($view->render());
    }
}
?>
