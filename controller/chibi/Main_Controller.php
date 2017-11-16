<?php
//dengjing34@vip.qq.com
class Main_Controller extends Chibi_Controller {
    function  __construct() {
        parent::__construct();
    }

    function index() {
        $this->mainIndex();
    }

    function mainIndex() {
        $view = new View('chibi/main/dashBoard');
        $view->enabled = '<span class="green">√</span>';
        $view->disabled = '<span class="red">×</span>';
        $this->render($view->render());
    }

    function quit() {
        $userLogic = new User_Logic();
        $userLogic->quit();
        Url::redirect('chibi');
    }

    function userPassword() {
        $fields = array(
            'userName' => array(
                'text' => '用户名称', 'readonly' => true, 'required' => true, 'rule' => '/^[a-z]+$/', 'size' => 30
            ),
            'password' => array(
                'text' => '用户密码', 'type' => 'password', 'required' => true, 'size' => 30
            ),
        );
        $form = new Form($fields);
        $user = new User();
        $user->load($this->_userId);
        if ($this->url->post('submit')) {
            $validateResult = $form->validatePost();
            if($validateResult['error']) {
                $this->_error($validateResult['error']);return;
            }
            if ($user->password != $validateResult['fields']['password']) {
                foreach ($validateResult['fields'] as $k => $v) {
                    if ($k == 'password') {
                        $user->set($k, md5($validateResult['fields']['userName'] . $v));continue;
                    }
                    $user->set($k, $v);
                }
                try {
                    $user->save();
                    $this->_success(Url::siteUrl('chibi/main/quit'), '密码修改成功,需要重新登录');return;
                }catch (Exception $e) {
                    $this->_error($e->getMessage());return;
                }
            } else {
                $this->_error('你似乎并没有修改你的密码');return;
            }
        }
        $form->assign($user);
        $scripts = new View('chibi/components/sValidation');
        $view = new View('chibi/components/tmpAdd');
        $view->scripts = $scripts->render();
        $view->form = $form;        
        $this->render($view->render());
    }

    function profile() {
        $form = new Form(User::$formFields);
        $form->filterFields(array('userName', 'mobile', 'email', 'sex', 'status', 'roleId'));                        
        $user = new User();
        $role = new Role();
        try {
            $user->load($this->_userId);
            $role->load($user->roleId);
        } catch (Exception $e) {
            $this->_error($e->getMessage());return;
        }
        if ($this->url->post('submit')) {
            $validateResult = $form->validatePost();
            if($validateResult['error']) {
                $this->_error($validateResult['error']);return;
            }
            foreach ($validateResult['fields'] as $k => $v) {
                $user->set($k, $v);
            }
            try {
                $user->save();
                $this->_success(Url::siteUrl('chibi/main/profile'));return;
            } catch (Exception $e) {
                $this->_error($e->getMessage());return;
            }
        }
        $form->set('userName', array(
            'hint' => '不允许更改',
            'readonly' => true,
        ));
        $form->set('sex', array(
            'options' => User::$_sex,
        ));
        $form->set('status', array(
            'options' => array($user->status => User::$_status[$user->status]),
            'nofirstSelect' => true,
        ));
        $form->set('roleId', array(
            'options' => array($user->roleId => $role->cname),
            'nofirstSelect' => true,
        ));
        $form->assign($user);
        $scripts = new View('chibi/components/sValidation');
        $view = new View('chibi/components/tmpAdd');
        $view->scripts = $scripts->render();
        $view->form = $form;
        $this->render($view->render());

    }

    function help() {
        $this->render("help center");
    }
}

?>
