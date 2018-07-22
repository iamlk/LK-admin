var count=0;
var oked=0;

function ajaxPost(){
    if(oked==1) return;
    oked = 1;
    $.ajax({
        async: false,
        type : "GET",
        url : '/backend/stream/view',
        success:function(left){
            if(left<0) left=0;
            $('.counts').text(left+'');
            var width = (count-left)*100/count;
            width = parseInt(width);
            $('#progress').attr('style','width: '+width+'%');
            if(left>0) {
                oked = 0;
                return true;
            }else {
                oked = 0;
                location.reload();
            }
        },
        complete:function (data) {
            oked = 0;
            return true;
        }
    });
    oked = 0;
    return true;
}


(function($) {
    count = $('#progress').data('count');
    window.setInterval("ajaxPost()",500);
})(jQuery);