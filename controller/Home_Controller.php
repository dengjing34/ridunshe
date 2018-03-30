<?php
//dengjing34@vip.qq.com
class Home_Controller extends Controller{
    function  __construct() {
        $this->noCache();
        parent::__construct();
    }
    
    function index() {
        try {
            $seo = Config::item('seo');
            $conf = array();
            foreach ($seo as $key => $val){
                $split = $key == 'title' ? '-' : ',';
                $conf[$key] = implode($split, $val);
            }
        } catch (Exception $e) {
            ErrorHandler::show_404($e->getMessage());
        }
        $bgClass = array(
            1 => '1',
            2 => '2',
            3 => '3',
            4 => '4',
            5 => '5',
            6 => '1',
            7 => '2',
            8 => '3',
            9 => '4',
            10 => '5',
            11 => '1',
            12 => '2',
        );        
        $flaSrc = Url::siteUrl("images/flash/a{$bgClass[date('n')]}.swf");
        $analyticsCode = Analytics::code();
        $view = new View('home/welcome', compact('conf', 'flaSrc', 'analyticsCode'));
        $view->render(true);
    }
    
    function aboutus() {
        $this->render(Pages::render(__FUNCTION__));  
    }
    
    function staff() {
        $this->render(Pages::render(__FUNCTION__));
    }
    
    function blog() {
        $this->render(Pages::render(__FUNCTION__));
    }
    
    function contactus() {
        $this->render(Pages::render(__FUNCTION__));
    }

    public function process() {
        $this->render(Pages::render(__FUNCTION__));
    }

    public function services() {
        $this->render(Pages::render(__FUNCTION__));
    }
    
    function voice() {
        $this->render('voice');
    }
    
    function homepage() {
        $sliders = Slider::getSliders();        
        $view = new View('home/homepage', compact('sliders'));
        $this->render($view->render());
    }
}
?>
