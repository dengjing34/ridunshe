<?php
//dengjing34@vip.qq.com
class Ajax_Controller extends Controller{
    
    function __construct() {
        parent::__construct();
    }
    
    private function output($msg) {
        if (is_array($msg)) {
            echo json_encode($msg);
        } else {
            echo $msg;
        }
    }
    
    function index() {
        $this->fork();        
    }
    
    function heart() {
        $params = array(
            'id',
        );
        $output = array('result' => 'failed', 'msg' => '');
        foreach ($params as $val) ${$val} = $this->url->post($val);
        if (is_numeric($id)) {
            try {
                Works::heartIncrease($id);
                $output['result'] = 'successed';
            } catch (Exception $e) {
                $output['msg'] = $e->getMessage();
            }                
        } else {
            $output['msg'] = 'id is not a numeric';
        }
        $this->output($output);
    }
}

?>
