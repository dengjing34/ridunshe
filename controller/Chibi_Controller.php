<?php
//dengjing34@vip.qq.com
class Chibi_Controller extends Controller {
    public $controllerName, $methodName, $_crumb, $_title, $_userName, $_userId, $_userRole, $_userRoleId, $_userAccess;
    private $accessTree, $currAccess = null;

    function  __construct() {
        $this->noCache();// no cache in Chibi_Controller pages
        parent::__construct();    
    }

    private function validateLogin() {
        $loginFlag = true;
        $validateFileds = array('_userName', '_userId', '_userRole', '_userRoleId', '_userAccess');
        foreach ($validateFileds as $val) {
            $this->{$val} = Cookie::get($val);
            if (is_null($this->{$val})) {
                $loginFlag = false;
                break;
            }
        }
        if (!$loginFlag) {
            Url::redirect(Url::siteUrl('chibi'));
        }
    }

    protected function render ($html = null, $config = array()) {
        header('Content-type:text/html; charset=utf-8');
        $baseHtml = '';
        $header = new View('chibi/base/header');
        $header->access = $this->accessTree;
        $header->userName = $this->_userName;
        $header->controllerName = $this->controllerName;
        $header->methodName = $this->methodName;
        $header->title = $this->_title;
        $header->crumb = $this->_crumb;
        $baseHtml .= $header->render();
        $baseHtml .= $html;
        $footer = new View('chibi/base/footer');
        $baseHtml .= $footer->render();
        echo $baseHtml;
    }

    private function getCurrAccess($item, $key, $access) {        
        foreach ($item['children'] as $v) {
            if ($v['name'] == $access) {
                $this->currAccess = $v;return;
            }
            foreach ($v['children'] as $v1) {
                if ($v1['name'] == $access) {
                    $this->currAccess = $v1;return;
                }
            }
        }
    }

    private function validateAccess() {
        $userAccess = explode(',', base64_decode($this->_userAccess));
        $access = new Access();
        $allAccess = $access->getAccessTree();
        $accessPath = "{$this->controllerName}/{$this->methodName}";
        array_walk($allAccess, array($this, 'getCurrAccess'), $accessPath);//从所有access中找出当前访问路径的数据
        if (is_null($this->currAccess)) {
            throw new Exception("控制器:{$this->controllerName}/{$this->methodName} 还未加入ACL中,请联系管理员添加");
        }
        if (!in_array($this->currAccess['id'], $userAccess)) {
            throw new Exception('您无权访问此页面');
        }
        $crumb = array();
        switch ($this->currAccess['level']){
            case Access::LEVEL_2 :
                $crumb[] = $allAccess[$this->currAccess['pid']]['children'][$this->currAccess['id']];break;
            case Access::LEVEL_3 :
                foreach ($allAccess as $val) {
                    if (isset ($val['children'][$this->currAccess['pid']])) {
                        $crumb[] = $val['children'][$this->currAccess['pid']];
                        $crumb[] = $val['children'][$this->currAccess['pid']]['children'][$this->currAccess['id']];
                        break;
                    }
                }
                break;
        }
        $this->_crumb = $crumb;
        $title = array();
        foreach ($this->_crumb as $val1) {
            $title[] = $val1['cname'];
        }
        $this->_title = implode(' - ', $title);
        foreach (array_keys($allAccess) as $v1) {
            if (!in_array($v1, $userAccess)) {
				unset ($allAccess[$v1]);continue;				
			}                        
            foreach(array_keys($allAccess[$v1]['children']) as $v2){
                if (!in_array($v2, $userAccess)) {
					unset ($allAccess[$v1]['children'][$v2]);continue;					
				}
                foreach(array_keys($allAccess[$v1]['children'][$v2]['children']) as $v3){
                    if (!in_array($v3, $userAccess)) unset ($allAccess[$v1]['children'][$v2]['children'][$v3]);
                }
            }
        }
        $this->accessTree = $allAccess;
    }

    function _success($url = null, $msg = '操作成功!', $redirect = true) {
        $html = '<div class="message success"><h3 style="color:#990000;font-size:14px;margin:0 0 4px 0;">' . $msg . '</h3><br /><br />';
        if ($redirect) {
            $html .= '<a href="' . $url . '">2秒后自动跳转,若没有跳转请点击此处</a></div>';
            $html .= '<script type="text/javascript">setTimeout(function(){window.location.href="' . $url . '"}, 2000);</script>';
        } else if ($url) {
            $html .= '<a class="button button-gray" href="' . $url . '">点击返回</a></div>';
        } else {
            $html .= '</div>';
        }
        $this->render($html);
    }

    function _error($msg = null) {
        $html = '<div class="message error"><h3 style="color:#990000;font-size:14px;margin:0 0 4px 0;">有点儿问题:</h3>' . $msg . '<br /><br /><a href="#" class="button button-gray" onclick="history.go(-1);return false;">&lt;&lt; 返回</a></div>';
        $this->render($html);
    }

    function index() {
        $uri = array_filter(explode('/', current(explode('?', $_SERVER['REQUEST_URI']))));        
        $defaultController = isset ($uri[2]) ? ucfirst($uri[2]) . '_Controller' : DEFAULT_CONTROLLER;
        $defaultMethod = isset($uri[3]) ? $uri[3] : DEFAULT_METHOD;
        if (is_file(CONTROLLER_DIR . 'chibi/' .$defaultController . '.php')) require_once CONTROLLER_DIR . 'chibi/' . $defaultController . '.php';
        else ErrorHandler::show_404 ('有点儿问题', "controller:{$defaultController} not found");
        $class = new $defaultController ();
        if (get_class($class) != DEFAULT_CONTROLLER) {
            $class->validateLogin();//若不是登录的控制器:Home_Controller就验证是否登录
            $class->controllerName = $uri[2];
            $class->methodName = $defaultMethod;
            try {
                $class->validateAccess();//验证是否有权限访问
            }catch (Exception $e) {
                ErrorHandler::show_404('有点儿问题', $e->getMessage());
            }
        }
        if (method_exists($class, $defaultMethod)) $class->{$defaultMethod} ();
        else ErrorHandler::show_404 ('有点儿问题', "method:{$defaultMethod} not found");
    }
}
?>
