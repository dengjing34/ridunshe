(function($){   
    $.fn.extend({    
        //plugin name - accordion        
        accordion: function(options) {   
            var defaults = {   
                animation: 'slide',   
                speed: 300,   
                expandNum: false,   
                oneExpandOnly: true,
                trigger: 'click' //hover or click
            };   
            var options = $.extend(defaults, options);
            function expand(option, object, firstLevel, secondLevel){                
                if(object.hasClass('expand')) object.toggleClass('dropdown');
                switch(option.animation){
                    case 'slide':
                        object.next("ul").slideToggle(option.speed);
                        break;
                    case 'fade':								
                        if(object.next("ul").is(":visible")){
                            object.next("ul").fadeOut('fast')	
                        }else{
                            object.next("ul").fadeIn(option.speed)	
                        }
                        break;
                    default:
                        object.next("ul").toggle();
                        break;
                }	
                if(option.oneExpandOnly){
                    //alert($(this).parent("li").length)
                    //alert(firstLevel.not($(this).parent("li")).length)
                    firstLevel.find('>a').not(object).each(function(){$(this).removeClass('dropdown');});             
                    $(">ul", firstLevel.not(object.parent("li"))).removeClass('dropdown');
                    switch(option.animation){
                        case 'slide':
                            $(">ul", firstLevel.not(object.parent("li"))).slideUp(option.speed);
                            break;
                        case 'fade':								
                            $(">ul", firstLevel.not(object.parent("li"))).fadeOut(option.speed);
                            break;
                        default:
                            $(">ul", firstLevel.not(object.parent("li"))).hide();
                            break;
                    }														
                }                
            }
            return this.each(function(){
                var o = options;   
                var obj = $(this);                   
                var items = $("ul:first-child", obj);	
                var firstLevel = $(">li",items);
                var secondLevel = $(">li>ul",items);
                //收起所有菜单
                secondLevel.each(function(){
                    $(this)	.hide();																										
                });
                //指定展开菜单
                if(o.expandNum){
                    secondLevel.eq(o.expandNum - 1).show()
                }                
                $('> a',firstLevel).each(function(){
                    switch (o.trigger) {
                        case 'hover' :
                            $(this).parent().hover(
                                function(){expand(o, $(this).find('a'), firstLevel, secondLevel);},
                                function(){}
                            );
                            break;
                        default :
                            $(this).click(function(){
                                expand(o, $(this), firstLevel, secondLevel);
                                if ($(this).next('ul').length > 0 ) return false;
                            });   
                    }                    					  												  
                })									
            })
        }   
    });   
})(jQuery);