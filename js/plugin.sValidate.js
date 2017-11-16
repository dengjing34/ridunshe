(function(g,i){var e={$:function(a){return document.getElementById(a)},gt:function(a,b){return a.getElementsByTagName(b)},db:document.body,dd:document.documentElement,i:0,mix:function(a,b){for(var d in b)a[d]=b[d];return a},html:'<div class="lj-tipsWrap lj-<%=kind%>" id="tipsWrap-<%=r%>"><div class="lj-content"></div><span class="lj-in lj-<%=p%>"><span class="lj-in lj-span"></span></span><a href="javascript:void(0)" id="ljClose<%=r%>" class="lj-close">x</a></div>'},j={prefix:"JunLu",p:"right",kind:"correct",
closeP:"ljClose",wrapP:"tipsWrap-",closeBtn:true,time:null,offset:null,content:function(){return"Hello World"},of:15,rand:0},h=function(a){if(!(this instanceof h))return new h(a);this.elem=a?typeof a=="string"?e.$(a):a:this;this.defaultConfig=e.mix({},j);this._config={};this.func=this.clearTime=null;e.db!==document.body&&this._init()};h.prototype={show:function(a){var b=this,d=b._config,c,f;c=Object.prototype.toString.call(a);f=b.defaultConfig.content;if(!a||a&&!a.content)a=e.mix(a||{},{content:typeof f==
"function"?f.call(b.elem,b.elem):f});if(/String|Number/.test(c))a={content:a};if("[object Function]"==c)a={content:a.call(b.elem,b.elem)};e.mix(d,b.defaultConfig);e.mix(d,a);b._updateInfo();b.id&&b.hide();a=b._append();e.gt(a,"DIV")[0].innerHTML=d.content;c=b._pos.call(b,d.p,a.offsetWidth,a.offsetHeight);f=b._getScroll();a.style.top=c.top+f.top+"px";a.style.left=c.left+f.left+"px";b._winSizeCheck(a);if(d.time)b.clearTime=setTimeout(function(){b.hide(void 0)},d.time);return false},hide:function(){this.clearTime&&
clearTimeout(this.clearTime);this._clear(e.$(this.id))},_init:function(){e.mix(e,{dd:document.body,db:document.documentElement})},_clear:function(a){a&&a.parentNode&&a.parentNode.removeChild(a);g.detachEvent?g.detachEvent("onresize",this.func):g.removeEventListener("resize",this.func,false)},_updateInfo:function(){var a=this.elem,b=this._config;b.width=a.offsetWidth;b.height=a.offsetHeight;b.offset=a.getBoundingClientRect()},_append:function(){var a=this,b=a._config,d,c;d=b.rand=++e.i;c=document.createElement("DIV");
c.id=b.prefix+d;a.id=c.id;c.innerHTML=e.html.replace("<%=p%>",b.p).replace(/<%=r%>/g,d).replace("<%=kind%>",b.kind);document.body.appendChild(c);if(b.closeBtn)e.$(b.closeP+d).onclick=function(){a.hide()};else e.$(b.closeP+d).style.display="none";return e.$(b.wrapP+d)},_pos:function(a,b,d){var c=this._config;return{left:function(f){return{top:c.offset.top,left:c.offset.left-f-c.of}},top:function(f,k){return{top:c.offset.top-k-c.of,left:c.offset.left}},right:function(){return{top:c.offset.top,left:c.offset.left+
c.width+c.of}},bottom:function(){return{top:c.offset.top+c.height+c.of,left:c.offset.left}}}[a](b,d)},_getScroll:function(){return{top:e.db.scrollTop+e.dd.scrollTop,left:e.db.scrollLeft+e.dd.scrollLeft}},_winSizeCheck:function(a){var b=this,d=b._config;b.func=function(){b._updateInfo();var c=b._pos.call(b,d.p,a.offsetWidth,a.offsetHeight),f=b._getScroll();a.style.top=c.top+f.top+"px";a.style.left=c.left+f.left+"px"};g.attachEvent?g.attachEvent("onresize",b.func):g.addEventListener("resize",b.func,
false)}};g[i]=h;g[i].config=j})(window,"Stip");

function sValidation(fields){
    var msg = '';
    if (typeof(fields) == 'object') {
        var i = 1;
        for (var id in fields) {
            var flag = false;
            if (typeof(fields[id]['type']) == 'undefined') {
                if (typeof(fields[id]['rule']) == 'undefined') {
                    flag = $.trim($('#'+id).val()) != '' ? true : false;
                } else {
                    flag = fields[id]['rule'].test($('#'+id).val()) ? true : false;
                }
                msg += flag ? '' : i + ':' + fields[id]['tip'] + "\n";
                if (!flag) Stip(id).show({content:fields[id]['tip'], kind:'error', time:5000});
            } else {
                switch (fields[id]['type']) {
                    case 'checkbox':
                        var checkedNum = $("input[name='"+ id +"']:checked").length;
                        var minChecked = typeof(fields[id]['minChecked']) == 'undefined' ? 1 : fields[id]['minChecked'];
                        var maxChecked = typeof(fields[id]['maxChecked']) == 'undefined' ? $("input[name='"+ id +"']").length : fields[id]['maxChecked'];
                        flag = minChecked <= checkedNum && checkedNum <= maxChecked ? true : false;
                        break;
                    case 'radio':
                        var checkedNum = $("input[name='"+ id +"']:checked").length;
                        flag = checkedNum > 0 ? true : false;
                        break;
                    case 'select':
                        flag = $.trim($('#'+id).val()) != '' ? true : false;
                        break;
                    case 'password':
                        flag = $.trim($('#'+id).val()) != '' ? true : false;
                        break;
                    case 'textarea':
                        flag = $.trim($('#'+id).val()) != '' ? true : false;
                        break;
                    case 'ckeditor':
                        flag = fields[id]['data'] != '' ? true : false;
                        break;
                    case 'file':
                        flag = $.trim($('#'+id).val()) != '' ? true : false;
                        break;
                    default:
                        break;
                }
                msg += flag ? '' : i + ':' + fields[id]['tip'] + "\n";
                if (!flag) {
                    var tipElementId = '';
                    switch (fields[id]['type']){
                        case 'checkbox':
                            tipElementId = fields[id]['tipElementId'] ? fields[id]['tipElementId'] : $("input[name='"+ id +"']").eq(0).attr('id');break;
                        case 'radio':
                            tipElementId = $("input[name='"+ id +"']").eq(0).attr('id');break;                            
                        case 'select':
                            tipElementId = id;break;
                        case 'password':
                            tipElementId = id;break;
                        case 'textarea':
                            tipElementId = id;break;
                        case 'ckeditor':
                            tipElementId = 'cke_' + id;break
                        case 'file':
                            tipElementId = id;break    
                        default:
                            break;
                    }
                    if (tipElementId) {
                        Stip(tipElementId).show({content:fields[id]['tip'], kind:'error', time:5000});
                    }
                }
            }
            i++;
        }
    }
    if (msg != '') {
        //alert(msg);
        return false;
    } else {
        return true;
    }
}