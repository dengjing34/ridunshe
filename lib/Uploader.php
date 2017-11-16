<?php

//dengjing34@vip.qq.com
class Uploader {
    public $inputname, $immediate, $dirtype, $rootdir, $attachdir, $uploadDir, $maxsize, $msgtype, $allowFile,$enableSize, $needResize = false, $needWaterMark;
    const WATER_MARK_TEXT = 'text', WATER_MARK_IMAGE = 'image';
    function __construct() {
        $this->inputname = 'Filedata';
        $this->immediate = null;
        $this->dirtype = 4; //1:按天存入目录 2:按月存入目录 3:按扩展名存目录 4:按年月方式存储 如201004  建议使用按天存
        $this->rootdir = substr(BASEDIR, 0, -1);
        $this->attachdir = $this->rootdir . UPLOAD_PATH;//上传文件保存路径，结尾不要带/
        $this->uploadDir = UPLOAD_PATH;
        $this->maxsize = 16777216; //2097152;//最大上传大小，默认是2M
        $this->msgtype = 2;//返回上传参数的格式：1，只返回url，2，返回参数数组
        $this->allowFile = 'txt,rar,zip,jpg,jpeg,gif,png,swf,wmv,avi,wma,mp3,mid'; //上传扩展名
        try {
            $this->enableSize = Config::item('imagesize');
        } catch (Exception $e) {
            exit($e->getMessage());
        }
    }
    
    function save($json = true) {                
        $err = $msg = '';        
        $upfile = isset($_FILES[$this->inputname]) ? $_FILES[$this->inputname] : null;
        if (is_null($upfile)) {
            $result = array('err' => 'no file', 'msg' => $msg);
            return $json ? json_encode($result) : $result;
        }
        if (!empty($upfile['error'])) {
            switch ($upfile['error']) {
                case '1':
                    $err = '文件大小超过了php.ini定义的upload_max_filesize值';
                    break;
                case '2':
                    $err = '文件大小超过了HTML定义的MAX_FILE_SIZE值';
                    break;
                case '3':
                    $err = '文件上传不完全';
                    break;
                case '4':
                    $err = '无文件上传';
                    break;
                case '6':
                    $err = '缺少临时文件夹';
                    break;
                case '7':
                    $err = '写文件失败';
                    break;
                case '8':
                    $err = '上传被其它扩展中断';
                    break;
                case '999':
                default:
                    $err = '无有效错误代码';
            }
        } elseif (empty($upfile['tmp_name']) || $upfile['tmp_name'] == 'none') {
            $err = '无文件上传';
        } else {
            $temppath = $upfile['tmp_name'];
            $fileinfo = pathinfo($upfile['name']);
            $extension = $fileinfo['extension'];
            $file_tag = $fileinfo['filename']; //源文件名作为上传后的文件数据库中标签
            if (preg_match('/' . str_replace(',', '|', $this->allowFile) . '/i', $extension)) {
                $filesize = filesize($temppath);
                if ($filesize > $this->maxsize)
                    $err = '文件大小超过' . $this->maxsize . '字节';
                else {
                    if (!is_dir($this->attachdir)) {
                        @mkdir($this->attachdir, 0777);
                    }
                    switch ($this->dirtype) {
                        case 1: $attach_subdir = 'day_' . date('ymd');
                            break;
                        case 2: $attach_subdir = 'month_' . date('ym');
                            break;
                        case 3: $attach_subdir = 'ext_' . $extension;
                            break;
                        case 4: $attach_subdir = date('Ym');
                            break;
                        default:break;
                    }
                    $attach_dir = $this->attachdir . $attach_subdir;
                    if (!is_dir($attach_dir)) {
                        @mkdir($attach_dir, 0777);
                        //@fclose(fopen($attach_dir . '/index.htm', 'w'));
                    }
                    $filename = date("Ymd") . '_' . date('His') . '_' . rand(1000, 9999) . '.' . $extension;
                    $target = $this->rootdir ? str_replace($this->rootdir, '', $attach_dir . '/' . $filename) : $attach_dir . '/' . $filename; //插入编辑器的文件路径 若使用绝对路径则插入编辑器的内容要去掉$this->rootdir
                    $targetfile = $attach_dir . '/' . $filename; //上传的文件地址
                    //move_uploaded_file($upfile['tmp_name'],$target);
                    move_uploaded_file($upfile['tmp_name'], $targetfile);
                    if ($this->immediate == '1')
                        $target = '!' . $target;
                    if ($this->msgtype == 1)
                        $msg = $target;
                    else
                        $msg = array('url' => $target, 'localname' => $upfile['name'], 'dbPath' => str_replace($this->uploadDir, '', $target)); //id参数固定不变，仅供演示，实际项目中可以是数据库ID
                    if ($this->needResize == true) {//需要生成缩略图
                        foreach ($this->enableSize as $size => $val) {
                            $this->resize(str_replace($this->uploadDir, '', $target), $size);
                        }
                    }
                    switch ($this->needWaterMark) {
                        case self::WATER_MARK_IMAGE:
                            $this->waterMarkImage(str_replace($this->uploadDir, '', $target));
                            break;
                        case self::WATER_MARK_TEXT:
                            $this->waterMarkText(str_replace($this->uploadDir, '', $target));
                            break;
                        default:
                            break;
                    }
                    
                }
            }
            else
                $err = '上传文件扩展名必需为：' . $this->allowFile;
                //@unlink($temppath);
        }
        $result = array('err' => $err, 'msg' => $msg);
        return $json ? json_encode($result) : $result;        
        //echo json_encode(array('err' => $err, 'msg' => $msg));
    }

    
    function resize($imagePath, $size) {        
        if ((bool)$imagePath && is_file(Url::filePath($imagePath)) && in_array($size, array_keys($this->enableSize))) {
            $config = array(
                'source_image' => Url::filePath($imagePath),
                'create_thumb' => true,
                'maintain_ratio' => true,
                'width' => $this->enableSize[$size]['width'],
                'height' => $this->enableSize[$size]['height'],
            );         
            $image = new Image($config);
            $image->full_dst_path = str_replace($image->thumb_marker, "_{$size}", $image->full_dst_path);
            $image->resize();
            $image->clear();            
        }
    }
    
    private function waterMark($imagePath, $config) {
        if ((bool)$imagePath && is_file(Url::filePath($imagePath))) {          
            $defaultConfig = array_merge(array(
                'source_image' => Url::filePath($imagePath),
                'quality' => 100,
                'wm_vrt_alignment' => 'bottom',
                'wm_hor_alignment' => 'right',
                'wm_padding' => '0',                               
                'wm_padding' => 0,                                
            ),$config);           
            $image = new Image($defaultConfig);
            //$image->full_dst_path = str_replace($image->thumb_marker, "_{$size}", $image->full_dst_path);
            $image->watermark();
            $image->clear();
        }      
    }
    
    private function waterMarkText($imagePath, $text = null) {
        if (is_null($text)) $text = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : null;
        if (!is_null($text)) {
            $this->waterMark($imagePath, array(
                'wm_text' => $text,
                'wm_type' => 'text',//(必须)设置想要使用的水印处理类型(text, overlay)
                'wm_font_size' => 5,//字体大小 没有使用自定义字体则只能是1-5之间
                'wm_font_color' => '808080',//字体颜色
                'wm_shadow_color' => 'ffffff',//阴影的颜色
                'wm_shadow_distance' => 1,//阴影与文字之间的距离(以像素为单位)。
//                'wm_font_path' => Url::filePath('msyh.ttf'),//水印字体名字和路径                
            ));
        }        
    }
    
    private function waterMarkImage($imagePath) {
        $this->waterMark($imagePath, array(
            'wm_overlay_path' => BASEDIR . 'images/samples/watermark.png',//水印图像的名字和路径
            'wm_opacity' => 10,//水印图像的透明度
            'wm_type' => 'overlay',//(必须)设置想要使用的水印处理类型(text, overlay)
            'wm_x_transp' => 4,//水印图像通道 
            'wm_y_transp' => 4,//水印图像通道 
            'wm_opacity' => 1,//水印图像的透明度
        ));
    }
}

?>
