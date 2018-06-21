var count=0;

function ajaxPost(){
    $.ajax({
        async: false,
        type : "GET",
        url : '/backend/stream/view',
        success:function(left){
            $('.counts').text(left+'');
            var width = (count-left)*100/count;
            width = parseInt(width);
            alert(width);
            $('#progress').attr('style','width: '+width+'%');
            if(left>0)
                return true;
            else
                location.reload();
        },
        complete:function (data) {
            return true;
        }
    });
    return true;
}


(function($) {
    count = $('#progress').data('count');
    window.setInterval("ajaxPost()",2000);
})(jQuery);