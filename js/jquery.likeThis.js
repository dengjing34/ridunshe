;(function($){   
    $.fn.extend({    
        //plugin name - accordion        
        likeThis: function(options) {   
            var defaults = {
                url : 'ajax/heart'
            };   
            var options = $.extend(defaults, options);
            options.url = typeof(baseUrl) == undefined ? options.url : baseUrl + options.url;
            return this.click(function(){
                var heart = parseInt($(this).text());
                var prefix = 'works_';
                var id = parseInt($(this).attr('id').replace(prefix, ''));                
                if (!isNaN(heart) && !isNaN(id) && $(this).attr('className') == 'like') {
                    $.post(options.url, {
                        'id': id
                    }, function(data){
                        var json = $.parseJSON(data);
                        if (json.result == 'successed') {
                            $('#' + prefix + id).text(heart + 1).attr('className', 'liked');
                        } else {
                            alert(json.msg);
                        }
                    }); 
                }
            })
        }   
    });   
})(jQuery);