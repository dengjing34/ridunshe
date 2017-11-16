<?php
//dengjing34@vip.qq.com
class Url {

    private $get;
    private $path;
    const UTF8_SAFE = true;

    public function __construct() {
        $this->get = $this->stripallslashes($_GET);
        $this->path = isset($_SERVER['SCRIPT_URL']) ? $_SERVER['SCRIPT_URL'] : '/';
    }

    private function stripallslashes($value) {
        if (!is_array($value)) {
            return trim(stripslashes($value));
        } else {
            $vals = array();
            foreach ($value as $key => $val) {
                $vals[$key] = $this->stripallslashes($val);
            }
            return $vals;
        }
    }
    
    public static function siteUrl ($uri = null) {
        return BASEURL . $uri;
    }
    
    public static function fileUrl($filePath) {
        return UPLOAD_URL . $filePath;
    }
    
    private static function generateImage($filePath, $type) {
        $pathArray = explode('.', $filePath);        
        $ext = array_pop($pathArray);
        $pathArray[] = "_{$type}";
        return self::fileUrl(implode($pathArray) . ".{$ext}");
    }
    
    public static function smallImage($filePath) {
        return self::generateImage($filePath, 'small');
    }
    
    public static function middleImage($filePath) {
        return self::generateImage($filePath, 'middle');
    }
    
    public static function bigImage($filePath) {
        return self::generateImage($filePath, 'big');
    }
    
    public static function biggerImage($filePath) {
        return self::generateImage($filePath, 'bigger');
    }
    
    public static function filePath($filePath) {
        return '.' . UPLOAD_PATH . $filePath;
    }
    
    public static function segment($index, $default = null) {
        $uri = self::segmentsArray();
        if (is_int($index) && $index < 0) {
            $index = count($uri) + 1 + $index;
        } elseif (is_array($index)) {
            $keys = array();
            foreach (array_intersect($uri, $index) as $val) {
                if ((bool)($key = array_search($val, $uri)) && isset($uri[$key + 1])) $keys[$val] = $uri[$key + 1];
                else $keys[$val] = $default;
            }
            return $keys;
        } elseif (is_string($index) && in_array($index, $uri)) {
            if ((bool)($key = array_search($index, $uri))) $index = $key + 1;
        }
        return isset($uri[$index]) ? $uri[$index] : $default;
    }
    
    public static function segmentsArray() {
        return array_filter(explode('/', current(explode('?', $_SERVER['REQUEST_URI']))));
    }


    public function setPath($value) {
        $this->path = $value;
        return $this;
    }

    public function getPath() {
        return $this->path;
    }

    public function set($name, $value) {
        $this->get[$name] = trim(urldecode($value));
        return $this;
    }

    public static function getRefer($utf8Safe = false) {
        $urlStr = isset($_SERVER['HTTP_REFERER']) ? trim($_SERVER['HTTP_REFERER']) : '';
        if ($utf8Safe)
            $urlStr = iconv('GBK', 'UTF-8', @iconv('UTF-8', 'GBK//IGNORE', $urlStr));
        return $urlStr;
    }

    public static function getUserAgent() {
        return isset($_SERVER['HTTP_USER_AGENT']) ? trim($_SERVER['HTTP_USER_AGENT']) : '';
    }

    public function delete($name) {
        if (isset($this->get[$name]))
            unset($this->get[$name]);
        return $this;
    }

    public function get($name = null, $default = null) {
        if ($name == null)
            return $this->get;
        return isset($this->get[$name]) ? $this->get[$name] : $default;
    }

    public function post($name = null, $default = null) {
        if ($name == null)
            return $this->stripallslashes($_POST);
        return isset($_POST[$name]) ? $this->stripallslashes($_POST[$name]) : $default;
    }

    public function __toString() {
        $url = $this->path;
        if (count($this->get) > 0) {
            ksort($this->get);
            $url .= '?' . http_build_query($this->get);
        }
        return $url;
    }

    public function switchValues($key1, $key2) {
        $minSal = $this->get($key1);
        $maxSal = $this->get($key2);
        if (strlen($minSal) > 0 && strlen($maxSal) > 0 && $minSal > $maxSal && ($maxSal != '*')) {
            $this->set($key1, $maxSal);
            $this->set($key2, $minSal);
        }
    }

    public static function redirect($uri = '', $method = '302') {
        $uri = str_replace("\n", '', $uri);

        $codes = array(
            '300' => 'Multiple Choices',
            '301' => 'Moved Permanently',
            '302' => 'Found',
            '303' => 'See Other',
            '304' => 'Not Modified',
            '305' => 'Use Proxy',
            '307' => 'Temporary Redirect'
        );
        $method = isset($codes[$method]) ? $method : '302';

        header('HTTP/1.1 ' . $method . ' ' . $codes[$method]);
        header('Location: ' . $uri);
        exit('<a href="' . $uri . '">' . $uri . '</a>'); // Last resort, exit and display the URL
    }

    public static function getFullUrl($utf8Safe = false) {
        if (PHP_SAPI == 'cli') {
            $args = '';
            foreach ($_SERVER['argv'] as $a) {
                $args .= $a . ' ';
            }
            return $args;
        } else {
            $url = 'http://' . $_SERVER['HTTP_HOST'] . (isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '');
            if ($utf8Safe)
                $url = iconv('GBK', 'UTF-8', @iconv('UTF-8', 'GBK//IGNORE', $url));
            return $url;
        }
    }

    public static function innerKeywords() {
        $refer = self::getRefer();
        if (preg_match('/[?&]query=([^&]+)/', $refer, $matches)) { // Inner
            return urldecode($matches[1]);
        }
        return '';
    }

    public static function seoKeywords() {
        $refer = self::getRefer();
        if (preg_match("/baidu.*[?&]wd=([^&]+)/", $refer, $matches)) { //百度
            return mb_convert_encoding(urldecode($matches[1]), 'utf8', 'gbk');
        } else if (preg_match("/google.*[?&]q=([^&]+)/", $refer, $matches)) { // Google
            return urldecode(urldecode($matches[1]));
        } else if (preg_match("/sogou.*[?&]query=([^&]+)/", $refer, $matches)) { // Sogou
            return mb_convert_encoding(urldecode($matches[1]), 'utf8', 'gbk');
        }
        return '';
    }

    public static function seoKeywordsExtra() { //获取原始搜索词,如百度的前一个搜索词
        $refer = self::getRefer();
        if (preg_match("/baidu.*[?&]bs=([^&]+)/", $refer, $matches)) { //百度
            return mb_convert_encoding(urldecode($matches[1]), 'utf8', 'gbk');
        } else {
            return self::seoKeywords();
        }
        return '';
    }

}

?>
