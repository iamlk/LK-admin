$(document).ajaxError(function(event,xhr,options,exc){
    if(xhr.status==403) {
        alert(xhr.responseText);
    }
});
$(function () {
    'use strict'

    /**
     * Get access to plugins
     */

    $('[data-toggle="control-sidebar"]').controlSidebar()
    $('[data-toggle="push-menu"]').pushMenu()
});