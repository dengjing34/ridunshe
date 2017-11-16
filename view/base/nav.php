<?php
//dengjing34@vip.qq.com
?>
    <div id="nav">
        <div id="nav-inner">
            <ul>
                <?php
                $nav = array();
                foreach ($navigator as $key => $val) {
                    $classes = array();
                    $url = Url::siteUrl($val['url']);
                    if (is_null($val['url'])) $classes[] = 'home';
                    if ($controller == $val['url']) $classes[] = 'current';
                    $className = empty($classes) ? null : ' class="' . implode(' ', $classes) . '"';
                    $nav[] = "<li><a{$className} title=\"{$key}\" href=\"{$url}\">{$val['text']}</a></li>"; 
                }
                echo implode("\n", $nav);
                ?>                
            </ul>
            <div class="red" style="float:right;">13408631535</div>
            <div class="clear"></div>
        </div>
    </div>