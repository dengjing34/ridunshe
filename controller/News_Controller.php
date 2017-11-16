<?php
//dengjing34@vip.qq.com
class News_Controller extends Controller{
    function __construct() {
        parent::__construct();
    }
    
    function index() {
        $this->fork();
        $o = new ArticleActive();
        $total = $o->count();
        Pager::$pageSize = 15;
        $page = Pager::requestSegmentPage($total);        
        $oo = $o->find(
            array(
                'limit' => Pager::limit($page),
            )
        );        
        $pager = Pager::showSegmentPage($total);
        $view = new View('news/welcome', compact('oo', 'pager'));        
        $this->render($view->render());
    }
    
    function detail() {
        $id = $this->url->segment('detail');
        if (is_null($id) || !is_numeric($id)) ErrorHandler::show_404('params error');
        $o = new ArticleActive();
        $o->id = $id;
        try {
            $o->load()->hitIncrease();
            $near = ArticleActive::near($o);
        } catch (Exception $e) {
            ErrorHandler::show_404($e->getMessage());
        }
        $crumb = $this->crumbs(array(
            'detail' => array(
                'text' => $o->title,
            ),
        ));
        $keywords = !($o->keywords) ? array($o->title) : explode(',', $o->keywords);        
        $metas = array(
            'title' => $o->title,
            'keywords' => $o->seoKeywords(),
            'description' => $o->seoDescription(),
            'nav' => $this->navBack(Url::siteUrl('news')),
        );                
        $view = new View('news/detail', compact('o', 'crumb', 'near'));
        $this->render($view->render(), $metas);
    }
}

?>
