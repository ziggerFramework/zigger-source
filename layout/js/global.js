///
// Popup
///
var SitePopup = {
    'init' : function() {
        this.action();
    },
    'action' : function() {
        $ele = {
            'closeBtn' : $('.ph-pop .close'),
            'closeTodayBtn' : $('.ph-pop .close-today')
        }
        $ele.closeBtn.on({
            'click' : function(e) {
                e.preventDefault();
                $(this).parents('.ph-pop').remove();
            }
        })
        $ele.closeTodayBtn.on({
            'click' : function(e) {
                e.preventDefault();
                var idx = $(this).data('pop-idx');
                setCookie("ph_pop_"+idx, 1, 1);
                $(this).parents('.ph-pop').remove();
            }
        })
    }
}
$(function() {
    SitePopup.init();
})
