<?php
//dengjing34@vip.qq.com
class Form {
    public $fields;
    /*sample:
     * array(
     *      'name' => array(
     *          'text' => '名字', 'required' => true, 'rule' => '/^[\w]+$/', 'hint' => '允许字母和数字', 'size' => 30, 'readonly' => true, 'tip' => '不合规范'                                       
     *      ),
     *      'sex' => array(
     *          'text' => '性别', 'type' => 'radio', 'options' => array('1' => '先生', '2' => '女士')
     *      ),
     *      'password' => array(
     *          'text' => '密码', 'type' => 'password',
     *      ),
     *      'role' => array(
     *          'role' => '角色', 'type' => 'select', 'options' => array('1' => '超级管理员', '2' => '网站管理员'), 'disabled' => true, 'nofirstSelect' => true,
     *      ),
     *      'id' => array(
     *          'text' => 'id', 'type' => 'hidden', 'value' => 1
     *      ),
     *      'description' => array(
     *          'text' => 'SEO描述', 'type' => 'textarea', 'width' => 200, 'height' => 200, 'hint' => '用于搜索引擎优化的描述信息,不填写将截取内容的前100个字符', 'required' => true,
     *      ),
     *      'content' => array(
     *          'text' => '文章内容', 'type' => 'ckeditor', 'required' => true, 'tip' => '文章内容必填', 'width' => 600, 'height' => 200, 'toolbar' => 'Basic|Full',
     *      ),
     *      'pic' => array(
     *          'text' => '项目图标', 'type' => 'file', 'required' => true, 'hint' => '项目的logo,用96x96像素的图片', 'size' => 40, 'tip' => '请务必上传一个96x96像素的图标', 'resizable' => true, 'watermark' => Uploader::WATER_MARK_IMAGE,
     *      ),
     * )
     * 新增一个属性请在此注明使用方式
     */
    function  __construct($fields = array()) {
        $this->fields = $fields;
    }
    
    function filterFields($keys = array()) {
        $fields = array();
        foreach ($keys as $key) {
            $fields[$key] = isset($this->fields[$key]) ? $this->fields[$key] : null;
        }
        $this->fields = array_filter($fields);
    }

    function createScripts() {
        $validateRule = array();
        foreach($this->fields as $key => $val){
            if(isset($val['required']) || isset($val['rule'])){
                $rule = isset ($val['rule']) ? ", rule:{$val['rule']}" : null;
                $tip = isset($val['tip']) ? $val['tip'] : '此项必填';
                $type = isset ($val['type']) ? ", type:'{$val['type']}'" : null;
                $editorData = isset ($val['type']) && $val['type'] == 'ckeditor' ? ", data:get{$key}Data()" : null;
                $validateRule[] = "{$key}:{tip:'{$tip}'{$rule}{$type}{$editorData}}";
            }
        }
        return implode(',', $validateRule);
    }

    function assign($obj){
        foreach ($this->fields as $k => $v) {
            $this->fields[$k]['value'] = $obj->get($k);
        }
    }

    function customAssign($array = array()) {
        foreach ($array as $k => $v) {
            if (isset($this->fields[$k])) $this->fields[$k]['value'] = $v;
        }
    }

    function set($key, $value = array()) {
        if(!empty($value)) {
            foreach ($value as $k => $v){
                $this->fields[$key][$k] = $v;
            }
            return true;
        } else {
            return false;
        }
    }

    function createForm() {
        $html = null;
        foreach($this->fields as $key => $val){
            $type = isset($val['type']) ? $val['type'] : 'text';
            $size = isset ($val['size']) ? "size=\"{$val['size']}\"" : null;
            $required = isset ($val['required']) ? '*' : null;
            $hint = isset($val['hint']) ? $val['hint'] : null;
            $readonly = isset($val['readonly']) ? 'readonly="readonly"' : null;
            $inputValue = isset($val['value']) ? $val['value'] : null;
            $disabled = isset ($val['disabled']) ? 'disabled="disabled"' : null;
            switch ($type) {
                case 'select':
                    $options = null;
                    $firstSelect = isset($val['nofirstSelect']) ? null : '<option value="">==请选择==</option>';//是否有第一个空option
                    if (isset($val['options'])) {
                        foreach ($val['options'] as $k1 => $v1) {
                            $selected = $inputValue == $k1 ? ' selected="selected"' : null;
                            $options .= "<option value=\"{$k1}\"$selected>{$v1}</option>";
                        }
                    }
                    $input = "<select name=\"{$key}\" id={$key} {$disabled}>{$firstSelect}{$options}</select>";
                    break;
                case 'password':
                    $input = "<input type=\"password\" name=\"{$key}\" id=\"{$key}\" value=\"{$inputValue}\" {$size} {$readonly} {$disabled} />";
                    break;
                case 'radio':
                    $input = null;
                    if (isset($val['options'])) {
                        $j = 1;
                        foreach ($val['options'] as $k2 => $v2) {
                            if (is_null($inputValue)) {
                                $checked = $j == 1 ? ' checked="checked"' : null;
                            } else {
                                $checked = $inputValue == $k2 ? ' checked="checked"' : null;
                            }
                            $input .= "<input type=\"radio\" name=\"{$key}\" id=\"{$key}_{$k2}\" value=\"{$k2}\"$checked {$disabled}> <label for=\"{$key}_{$k2}\" style=\"margin:0 5px;\">{$v2}</label> ";
                            $j++;
                        }
                    }
                    break;
                case 'hidden':
                    $input = "<input type=\"hidden\" name=\"{$key}\" id=\"{$key}\" value=\"{$inputValue}\"/>";
                    break;
                case 'textarea':
                    $width = isset($val['width']) ? "width:{$val['width']}px;" : null;
                    $height = isset($val['height']) ? "height:{$val['height']}px;" : null;
                    $style = !is_null($width) || !is_null($height) ? "style=\"{$width}{$height}\"" : null;
                    $input = "<textarea name=\"{$key}\" id=\"{$key}\" {$style}>{$inputValue}</textarea>";
                    break;
                case 'ckeditor':
                    $config = array();
                    foreach (array('width', 'height', 'toolbar') as $cnf) {
                        if (isset($val[$cnf])) $config[$cnf] = $val[$cnf];
                    }
                    $ckeditor = new CKEditor(BASEURL . 'ckeditor/');
                    $ckfinder = new CKFinder();
                    $ckfinder->SetupCKEditorObject($ckeditor);
                    $getContentJs = "<script type=\"text/javascript\">function get{$key}Data(){var oEditor = CKEDITOR.instances.{$key};return oEditor.getData();}</script>";
                    $input = $ckeditor->editor($key, $inputValue, $config) . $getContentJs;
                    break;
                case 'file':
                    $uploadParams = array();
                    if (isset($val['resizable']) && $val['resizable'] == true) $uploadParams['resizable'] = 1;
                    if (isset($val['watermark'])) $uploadParams['watermark'] = $val['watermark'];
                    $uploadUrl = !empty($uploadParams) ? Url::siteUrl('file/form?' . http_build_query($uploadParams)) :  Url::siteUrl('file/form');                                        
                    $uploadBtn = "<iframe onload=\"var data = this.contentWindow.document.body.innerHTML;if(/^{(.)err(.):(.*),(.)msg(.):(.*)}$/.test(data)){var json = $.parseJSON(data);if(json['err'] != ''){alert(json['err']);}else{ $('#{$key}').val(json['msg']['dbPath']);}history.go(-1);}\" style=\"margin:0;padding:0;vertical-align:middle;\" frameborder=\"0\" width=\"300\" height=\"30\" scrolling=\"no\"  src=\"{$uploadUrl}\"></iframe>";
                    $input = "<input type=\"text\" name=\"{$key}\" id=\"{$key}\" value=\"{$inputValue}\" {$size} />" . $uploadBtn;
                    break;
                default :
                    $input = "<input type=\"text\" name=\"{$key}\" id=\"{$key}\" value=\"{$inputValue}\" {$size} {$readonly} {$disabled} />";
                    break;

            }
            $display = in_array($type, array('hidden')) ? ' style="display:none"' : null;
            $html .= "<tr{$display}>\n";
            $html .= "<th><label for=\"{$key}\">{$val['text']}：</label></th>\n";
            $html .= "<td>{$input}<span class=\"required\">{$required}</span><em>{$hint}</em></td>\n";
            $html .= "</tr>\n";

        }
        return $html;
    }

    public function validatePost() {
        $error = '';
        $fields = array();
        $i = 1;
        foreach ($this->fields as $key => $val) {
            $flag = false;            
            $text = isset($val['text']) ? $val['text'] : $key;
            $tip = isset($val['tip']) ? $val['tip'] : '不符合规范';
            if (isset($val['type']) && $val['type'] == 'checkbox') {
                if (isset($val['required']) && $val['required'] == true) {
                    if (isset($_POST[$key]) && count($_POST[$key]) >= 1) {
                        $flag = true;
                    } else {
                        $flag = false;
                    }
                }
                if (isset($val['minChecked'])) {
                    if (isset($_POST[$key]) && count($_POST[$key]) >= $val['minChecked']) {
                        $flag = true;
                    } else {
                        $flag = false;
                    }
                }
                if (isset($val['maxChecked'])) {
                    if (isset($_POST[$key]) && count($_POST[$key]) <= $val['maxChecked']) {
                        $flag = true;
                    } else {
                        $flag = false;
                    }
                }
                if (!isset($val['required']) && !isset ($val['minChecked']) && !isset ($val['maxChecked'])) $flag = true;
                $error .= $flag == true ? '' : "{$i}.{$tip}<br />\n";
                $fields[$key] = $flag == true ? $_POST[$key] : null;
            } else {                
                if (isset($val['required']) && $val['required'] == true) {
                    if (isset($_POST[$key]) && $_POST[$key] != '') {
                        $flag = true;
                    } else {
                        $flag = false;
                    }
                }
                if (isset($val['rule']) && isset($_POST[$key])) {
                    if (preg_match($val['rule'], $_POST[$key])) {
                        $flag = true;
                    } else {
                        $flag = false;
                    }
                }                
                if (!isset($val['required']) && !isset ($val['rule'])) {
                    $flag = true;
                }                
                $error .= $flag == true ? '' : "{$i}.{$text}：{$tip}<br />\n";
                $fields[$key] = $flag == true ? $_POST[$key] : null;
            }
            $i++;
        }
        $error = $error == '' ? 0 : $error;
        return array(
            'error' => $error,
            'fields' => $fields,
        );
    }
}

?>
