<?php
//dengjing34@vip.qq.com
class File_Controller extends Controller{
    function __construct() {
        parent::__construct();
    }
    
    /*@methodname: upload()
     * 接收post过来的name为Filedata文件进行保存
     * output json格式的上传结果信息
    */    
    function upload() {
        $uploader = new Uploader();
        $uploader->needResize = (bool)($this->url->get('resizable')) ? true : false;// if need resize
        $uploader->needWaterMark = $this->url->get('watermark');// if need resize
        echo $uploader->save();                   
    }
    
    function form() {
        $this->noCache();
        $query = $this->url->get();;
        $action = !empty($query) ? Url::siteUrl('file/upload?' . http_build_query($query)) : Url::siteUrl('file/upload');         
        $view = new View('base/uplodForm', compact('action'));
        $view->render(true);
    }
}
?>
