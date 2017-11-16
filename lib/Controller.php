<?php
//dengjing34@vip.qq.com
class Controller {        
    protected $url, $className;

    function  __construct() {
        $this->url = new Url();
        $this->className = get_class($this);
        header("Content-type:text/html; charset=utf-8");
    }

    //set client browser no cache
    protected function noCache() {
        header("Expires: Mon, 26 Jul 1970 05:00:00 GMT");
        header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
        header("Cache-Control: no-cache, must-revalidate");
        header("Pragma: no-cache");
    }
    
    protected function fork($methodSegment = null) {
        $methodName = is_null($methodSegment) ? $this->url->segment(strtolower(str_replace('_Controller', '', $this->className))) : $this->url->segment($methodSegment);        
        if (!is_null($methodName) && method_exists($this, $methodName) && is_callable(array($this, $methodName))) {
            $this->$methodName();
            exit;
        }
    }
    
    protected function crumbs($crumbs, $output = false) {
        $crumbHtml = null;
        if (!empty($crumbs)) {
            $crumbHtml = '<div id="crumb">';
            $controller = $this->url->segment(1);
            try {
                $navigator = Config::item('nav');
            } catch (Exception $e) {
                ErrorHandler::show_404($e->getMessage());
            }
            $crumbArray = array(               
                'home' => array(
                    'url' => 'homepage',
                    'text' => '首页',
                ),
            );            
            if (isset($navigator[$controller])) $crumbArray[$controller] = $navigator[$controller];
            $crumbArray = array_merge($crumbArray, $crumbs);            
            $last = array_pop($crumbArray);
            $crumbLink = array();
            foreach ($crumbArray as $k => $v) {
                $crumbLink[] = "<a title=\"{$v['text']}\" href=\"" . $this->url->siteUrl($v['url']) . "\">{$k}</a>";
            }
            $crumbLink[] = "<h1>{$last['text']}</h1>";
            $crumbHtml .= implode(' &gt; ', $crumbLink) . '</div>';            
        }
        if ($output == false) return $crumbHtml;
        else echo $crumbHtml;
    }
    
    protected function render($html = null, $config = array()) {
        $this->noCache();
        try {
            $navigator = Config::item('nav');
            $seo = Config::item('seo');
        } catch (Exception $e) {
            ErrorHandler::show_404($e->getMessage());
        }
        $controller = $this->url->segment(1);
        $configList = array(
            'title',
            'keywords',
            'description',
        );
        $conf = array();
        foreach ($configList as $val) {
            $conf[$val] = isset($config[$val]) ? $config[$val] : null;
        }
        $defaultTitle = array($seo['title']['1st']);
        $siteName = implode(' - ', $seo['title']);
        $defaultKeywords = $seo['keywords'];
        if (!is_null($conf['keywords'])) {
            $customKeywords = is_array($conf['keywords']) ? $conf['keywords'] : explode(',', $conf['keywords']);
            $defaultKeywords = array_merge($customKeywords, array_slice($defaultKeywords, count($customKeywords)));
        } elseif (isset($navigator[$controller]['text'])){
            array_unshift($defaultKeywords, $navigator[$controller]['text']);
        }
        if (is_null($conf['title']) && isset($navigator[$controller]['text'])) array_unshift($defaultTitle, $navigator[$controller]['text']);
        elseif (is_null($conf['title'])) $defaultTitle[] = $seo['title']['2nd'];
        else {
            if (is_array($conf['title'])) $defaultTitle = array_merge($conf['title'], $defaultTitle);
            else array_unshift($defaultTitle, $conf['title']);
        }
        if (is_null($conf['description'])) $conf['description'] = implode(',', $seo['description']);
        elseif (is_array($conf['description'])) $conf['description'] = implode(',', array_merge($conf['description'], $seo['description']));
        $conf['title'] = implode(' - ', $defaultTitle);
        $conf['keywords'] = implode(',', $defaultKeywords);        
        $baseHtml = $navHtml = '';
        if (isset($config['nav'])) {
            $navHtml = $config['nav'];
        } else {
            $nav = new View("base/nav", compact('navigator', 'controller'));
            $navHtml = $nav->render();            
        }
        $header = new View('base/header', compact('navHtml', 'controller', 'conf', 'siteName'));
        $baseHtml .= $header->render();
        $baseHtml .= $html;
        $analyticsCode = Analytics::code();
        $footer = new View('base/footer', compact('analyticsCode'));
        $baseHtml .= $footer->render();
        echo $baseHtml;
    }
    
    protected function navBack($backUrl = null) {
        $view = new View('base/navBack', compact('backUrl'));
        return $view->render();
    }
    protected function seoMeta($metas = array()) {
        return array_fill_keys(array('title', 'keywords', 'description'), $metas);
    }
	//子入口
    protected function branch($controllerSegment = 2, $debug = false) {
        $msg = "page not found";
        $uri = array_filter(explode('/', current(explode('?', $_SERVER['REQUEST_URI']))));        
        $defaultController = isset($uri[$controllerSegment]) ? ucfirst($uri[$controllerSegment]) . '_Controller' : DEFAULT_CONTROLLER;        
        $defaultMethod = isset($uri[$controllerSegment + 1]) ? $uri[$controllerSegment + 1] : DEFAULT_METHOD;
        $folder = strtolower(str_replace('_Controller', '', $this->className));
        if (is_file(CONTROLLER_DIR . $folder . '/' .$defaultController . '.php')) require_once CONTROLLER_DIR . $folder .'/' . $defaultController . '.php';
        else ErrorHandler::show_404 ('有点儿问题', $debug ? "controller:" . CONTROLLER_DIR ."{$folder}/{$defaultController}.php not found" : $msg);
        $class = new $defaultController ();
        if (method_exists($class, $defaultMethod)) $class->{$defaultMethod} ();
        else ErrorHandler::show_404 ('有点儿问题', $debug ? "method:{$defaultController}->{$defaultMethod}() not found" : $msg);		
    }    
}
?>
