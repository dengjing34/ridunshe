<?php
//dengjing34@vip.qq.com
class Home_Controller extends Chibi_Controller {
    function  __construct() {
        parent::__construct();
    }

    function index() {
        $url = $this->url;
        $error = '';
        if ($url->post('name') && $url->post('password')) {
                $name = $url->post('name');
                $password = $url->post('password');
                $userLogic = new User_Logic();
                try {                    
                    $result = $userLogic->login($name, $password);
                    if ($result) Url::redirect(Url::siteUrl('chibi/main'));
                } catch (Exception $e) {
                    $error = $e->getMessage();
                }
        }
        $view = new View('chibi/login');
        $view->error = $error;
        $view->render(true);
    }
}

?>
