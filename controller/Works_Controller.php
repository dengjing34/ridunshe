<?php
//dengjing34@vip.qq.com
class Works_Controller extends Controller {
    function __construct() {
        parent::__construct();        
    }
    
    function index() {
        $this->fork();//执行分支操作        
        $o = new Project();
        $total = $o->count();
        Pager::$pageSize = 40;
        $page = Pager::requestSegmentPage($total);
        $oo = $o->find(
            array(
                'limit' => Pager::limit($page),
            )
        );        
        $pager = Pager::showSegmentPage($total);
        $view = new View('works/welcome', compact('oo', 'pager'));
//        $containerStyle = new View('base/containerStyle');
//        $this->render($containerStyle->render() . $view->render());
        $this->render($view->render());
    }
    
    function preview() {
        $id = $this->url->segment('preview');
        if (is_null($id) || !is_numeric($id)) ErrorHandler::show_404('params error');
        $o = new Works();
        $o->id = $id;
        try {
            $o->load()->hitIncrease();
            $near = Works::near($o);
        } catch (Exception $e) {
            ErrorHandler::show_404($e->getMessage());
        }
        $crumb = $this->crumbs(array(
            $o->projectName => array(
                'text' => $o->projectName,
                'url' => "works/project/{$o->projectId}",
            ),
            'preview' => array(
                'text' => $o->title,
            ),
        ));
        $view = new View('works/preview', compact('o', 'crumb', 'near'));
        $backUrl = Url::siteUrl("works/project/{$o->projectId}");
        $nav = $this->navBack($backUrl);
        $this->render($view->render(), array_merge($this->seoMeta(array($o->title, $o->projectName)), array('nav' => $nav)));
    }
    
    function project() {
        $projectId = $this->url->segment('project');
        if (is_null($projectId) || !is_numeric($projectId)) ErrorHandler::show_404('project id is undefined!');
        $p = new Project();
        try {
            $p->load($projectId);
        } catch (Exception $e) {
            ErrorHandler::show_404($e->getMessage());
        }
        $o = new Works();
        $o->projectId = $projectId;
        $oo = $o->find(array(
            'limit' => 1,
        ));
        $view = new View('works/project', compact('oo', 'p'));
        $this->render($view->render(), $this->seoMeta(array($p->name)));
    }
}
?>
