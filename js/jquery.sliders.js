/*
* Slides, A Slideshow Plugin for jQuery
* Intructions: http://slidesjs.com
* By: Nathan Searles, http://nathansearles.com
* Version: 1.1.9
* Updated: September 5th, 2011
*
* Licensed under the Apache License, Version 2.0 (the "License");
* you may not use this file except in compliance with the License.
* You may obtain a copy of the License at
*
* http://www.apache.org/licenses/LICENSE-2.0
*
* Unless required by applicable law or agreed to in writing, software
* distributed under the License is distributed on an "AS IS" BASIS,
* WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
* See the License for the specific language governing permissions and
* limitations under the License.
*/
(function(a){
    a.fn.slides=function(b){
        return b=a.extend({},a.fn.slides.option,b),this.each(function(){
            function w(g,h,i){
                if(!p&&o){
                    p=!0,b.animationStart(n+1);
                    switch(g){
                        case"next":
                            l=n,k=n+1,k=e===k?0:k,r=f*2,g=-f*2,n=k;
                            break;
                        case"prev":
                            l=n,k=n-1,k=k===-1?e-1:k,r=0,g=0,n=k;
                            break;
                        case"pagination":
                            k=parseInt(i,10),l=a("."+b.paginationClass+" li."+b.currentClass+" a",c).attr("href").match("[^#/]+$"),k>l?(r=f*2,g=-f*2):(r=0,g=0),n=k
                    }
                    h==="fade"?b.crossfade?d.children(":eq("+k+")",c).css({
                        zIndex:10
                    }).fadeIn(b.fadeSpeed,b.fadeEasing,function(){
                        b.autoHeight?d.animate({
                            height:d.children(":eq("+k+")",c).outerHeight()
                        },b.autoHeightSpeed,function(){
                            d.children(":eq("+l+")",c).css({
                                display:"none",
                                zIndex:0
                            }),d.children(":eq("+k+")",c).css({
                                zIndex:0
                            }),b.animationComplete(k+1),p=!1
                        }):(d.children(":eq("+l+")",c).css({
                            display:"none",
                            zIndex:0
                        }),d.children(":eq("+k+")",c).css({
                            zIndex:0
                        }),b.animationComplete(k+1),p=!1)
                    }):d.children(":eq("+l+")",c).fadeOut(b.fadeSpeed,b.fadeEasing,function(){
                        b.autoHeight?d.animate({
                            height:d.children(":eq("+k+")",c).outerHeight()
                        },b.autoHeightSpeed,function(){
                            d.children(":eq("+k+")",c).fadeIn(b.fadeSpeed,b.fadeEasing)
                        }):d.children(":eq("+k+")",c).fadeIn(b.fadeSpeed,b.fadeEasing,function(){
                            a.browser.msie&&a(this).get(0).style.removeAttribute("filter")
                        }),b.animationComplete(k+1),p=!1
                    }):(d.children(":eq("+k+")").css({
                        left:r,
                        display:"block"
                    }),b.autoHeight?d.animate({
                        left:g,
                        height:d.children(":eq("+k+")").outerHeight()
                    },b.slideSpeed,b.slideEasing,function(){
                        d.css({
                            left:-f
                        }),d.children(":eq("+k+")").css({
                            left:f,
                            zIndex:5
                        }),d.children(":eq("+l+")").css({
                            left:f,
                            display:"none",
                            zIndex:0
                        }),b.animationComplete(k+1),p=!1
                    }):d.animate({
                        left:g
                    },b.slideSpeed,b.slideEasing,function(){
                        d.css({
                            left:-f
                        }),d.children(":eq("+k+")").css({
                            left:f,
                            zIndex:5
                        }),d.children(":eq("+l+")").css({
                            left:f,
                            display:"none",
                            zIndex:0
                        }),b.animationComplete(k+1),p=!1
                    })),b.pagination&&(a("."+b.paginationClass+" li."+b.currentClass,c).removeClass(b.currentClass),a("."+b.paginationClass+" li:eq("+k+")",c).addClass(b.currentClass))
                }
            }
            function x(){
                clearInterval(c.data("interval"))
            }
            function y(){
                b.pause?(clearTimeout(c.data("pause")),clearInterval(c.data("interval")),u=setTimeout(function(){
                    clearTimeout(c.data("pause")),v=setInterval(function(){
                        w("next",i)
                    },b.play),c.data("interval",v)
                },b.pause),c.data("pause",u)):x()
            }
            a("."+b.container,a(this)).children().wrapAll('<div class="slides_control"/>');
            var c=a(this),d=a(".slides_control",c),e=d.children().size(),f=d.children().outerWidth(),g=d.children().outerHeight(),h=b.start-1,i=b.effect.indexOf(",")<0?b.effect:b.effect.replace(" ","").split(",")[0],j=b.effect.indexOf(",")<0?i:b.effect.replace(" ","").split(",")[1],k=0,l=0,m=0,n=0,o,p,q,r,s,t,u,v;
            if(e<2)return a("."+b.container,a(this)).fadeIn(b.fadeSpeed,b.fadeEasing,function(){
                o=!0,b.slidesLoaded()
            }),a("."+b.next+", ."+b.prev).fadeOut(0),!1;
            if(e<2)return;
            h<0&&(h=0),h>e&&(h=e-1),b.start&&(n=h),b.randomize&&d.randomize(),a("."+b.container,c).css({
                overflow:"hidden",
                position:"relative"
            }),d.children().css({
                position:"absolute",
                top:0,
                left:d.children().outerWidth(),
                zIndex:0,
                display:"none"
            }),d.css({
                position:"relative",
                width:f*3,
                height:g,
                left:-f
            }),a("."+b.container,c).css({
                display:"block"
            }),b.autoHeight&&(d.children().css({
                height:"auto"
            }),d.animate({
                height:d.children(":eq("+h+")").outerHeight()
            },b.autoHeightSpeed));
            if(b.preload&&d.find("img:eq("+h+")").length){
                a("."+b.container,c).css({
                    background:"url("+b.preloadImage+") no-repeat 50% 50%"
                });
                var z=d.find("img:eq("+h+")").attr("src")+"?"+(new Date).getTime();
                a("img",c).parent().attr("class")!="slides_control"?t=d.children(":eq(0)")[0].tagName.toLowerCase():t=d.find("img:eq("+h+")"),d.find("img:eq("+h+")").attr("src",z).load(function(){
                    d.find(t+":eq("+h+")").fadeIn(b.fadeSpeed,b.fadeEasing,function(){
                        a(this).css({
                            zIndex:5
                        }),a("."+b.container,c).css({
                            background:""
                        }),o=!0,b.slidesLoaded()
                    })
                })
            }else d.children(":eq("+h+")").fadeIn(b.fadeSpeed,b.fadeEasing,function(){
                o=!0,b.slidesLoaded()
            });
            b.bigTarget&&(d.children().css({
                cursor:"pointer"
            }),d.children().click(function(){
                return w("next",i),!1
            })),b.hoverPause&&b.play&&(d.bind("mouseover",function(){
                x()
            }),d.bind("mouseleave",function(){
                y()
            })),b.generateNextPrev&&(a("."+b.container,c).after('<a href="#" class="'+b.prev+'">Prev</a>'),a("."+b.prev,c).after('<a href="#" class="'+b.next+'">Next</a>')),a("."+b.next,c).click(function(a){
                a.preventDefault(),b.play&&y(),w("next",i)
            }),a("."+b.prev,c).click(function(a){
                a.preventDefault(),b.play&&y(),w("prev",i)
            }),b.generatePagination?(b.prependPagination?c.prepend("<ul class="+b.paginationClass+"></ul>"):c.append("<ul class="+b.paginationClass+"></ul>"),d.children().each(function(){
                a("."+b.paginationClass,c).append('<li><a href="#'+m+'">'+(m+1)+"</a></li>"),m++
            })):a("."+b.paginationClass+" li a",c).each(function(){
                a(this).attr("href","#"+m),m++
            }),a("."+b.paginationClass+" li:eq("+h+")",c).addClass(b.currentClass),a("."+b.paginationClass+" li a",c).click(function(){
                return b.play&&y(),q=a(this).attr("href").match("[^#/]+$"),n!=q&&w("pagination",j,q),!1
            }),a("a.link",c).click(function(){
                return b.play&&y(),q=a(this).attr("href").match("[^#/]+$")-1,n!=q&&w("pagination",j,q),!1
            }),b.play&&(v=setInterval(function(){
                w("next",i)
            },b.play),c.data("interval",v))
        })
    },a.fn.slides.option={
        preload:!1,
        preloadImage:"/img/loading.gif",
        container:"slides_container",
        generateNextPrev:!1,
        next:"next",
        prev:"prev",
        pagination:!0,
        generatePagination:!1,
        prependPagination:!1,
        paginationClass:"pagination",
        currentClass:"current",
        fadeSpeed:1050,
        fadeEasing:"",
        slideSpeed:350,
        slideEasing:"",
        start:1,
        effect:"slide",
        crossfade:!1,
        randomize:!1,
        play:0,
        pause:0,
        hoverPause:!1,
        autoHeight:!1,
        autoHeightSpeed:350,
        bigTarget:!1,
        animationStart:function(){},
        animationComplete:function(){},
        slidesLoaded:function(){}
    },a.fn.randomize=function(b){
        function c(){
            return Math.round(Math.random())-.5
        }
        return a(this).each(function(){
            var d=a(this),e=d.children(),f=e.length;
            if(f>1){
                e.hide();
                var g=[];
                for(i=0;i<f;i++)g[g.length]=i;
                g=g.sort(c),a.each(g,function(a,c){
                    var f=e.eq(c),g=f.clone(!0);
                    g.show().appendTo(d),b!==undefined&&b(f,g),f.remove()
                })
            }
        })
    }
})(jQuery);