;(function($){   
    $.fn.extend({    
        //plugin name - accordion        
        keyPager: function(options) {   
            var defaults = {
                prevId : 'prevLink',
                nextId : 'nextLink'
            };            
            var options = $.extend(defaults, options);            
            var prev = $('#' + options.prevId);
            var next = $('#' + options.nextId);
            function redirect(link) {
                var href = link.attr('href');
                if (link.length > 0 && href != undefined && href != '#' && href != 'javascript:void(0);') {
                     window.location.href = href;
                }               
            }
            $(document).keyup(function(event){
                switch (event.keyCode) {
                    case 37:
                        redirect(prev);
                        break;
                    case 39:
                        redirect(next);
                        break;
                    default:
                        break;
                }
            });
        }   
    });   
})(jQuery);