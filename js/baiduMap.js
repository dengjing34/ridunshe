function loadScript() {
    var script = document.createElement("script");
    script.src = "http://api.map.baidu.com/api?v=1.3&callback=initMap";
    document.body.appendChild(script);
}

function initMap() {
    var sContent = '<div style="padding:5px;line-height:22px;"><p>日敦社地址：</p><p>四川省成都市锦江区三圣乡幸福梅林幸福路幸福联合四组二号(乔家大院往鸟语林方向前行200米,笙轩园对面)</p></div>';
    var map = new BMap.Map("baiduMap");
    var point = new BMap.Point(104.141494,30.588587);
    var marker = new BMap.Marker(point);
    var infoWindow = new BMap.InfoWindow(sContent);  // 创建信息窗口对象
    map.centerAndZoom(point, 15);
    map.enableScrollWheelZoom();    //启用滚轮放大缩小，默认禁用
    map.enableContinuousZoom();    //启用地图惯性拖拽，默认禁用
    map.addControl(new BMap.NavigationControl());  //添加默认缩放平移控件
//    map.addControl(new BMap.NavigationControl({anchor: BMAP_ANCHOR_TOP_RIGHT, type: BMAP_NAVIGATION_CONTROL_SMALL}));  //右上角，仅包含平移和缩放按钮
//    map.addControl(new BMap.NavigationControl({anchor: BMAP_ANCHOR_BOTTOM_LEFT, type: BMAP_NAVIGATION_CONTROL_PAN}));  //左下角，仅包含平移按钮
//    map.addControl(new BMap.NavigationControl({anchor: BMAP_ANCHOR_BOTTOM_RIGHT, type: BMAP_NAVIGATION_CONTROL_ZOOM}));  //右下角，仅包含缩放按钮        
    map.addOverlay(marker);
    marker.addEventListener("click", function(){          
        this.openInfoWindow(infoWindow);
        //图片加载完毕重绘infowindow
//        document.getElementById('imgDemo').onload = function (){
//            infoWindow.redraw();
//        }
    });
}
window.onload = function() {
    loadScript();
}
