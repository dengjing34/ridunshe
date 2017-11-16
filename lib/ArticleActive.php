<?php
//dengjing34@vip.qq.com
class ArticleActive extends Article{
    
    public $status = self::STATUS_ACTIVE;    
    
    function __construct() {
        parent::__construct();
    }        
}

?>
