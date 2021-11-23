///
// slideMenu for mobile
///
mo_slide = {
    'init' : function() {
        this.action();
    },
    'action' : function() {
        var $ele = {
            'win' : $(window),
            'doc' : $(document),
            'slide' : $('#slide-menu'),
            'bg' : $('#slide-bg'),
            'btn' : $('#slide-btn'),
            'close' : $('#slide-close')
        }
        //open & close
        $ele.btn.on({
            'click' : function(e) {
                e.preventDefault();
                var on = $(this).hasClass('on');

                if (!on) {
                    $ele.btn.addClass('on');
                    $ele.slide.addClass('on');
                    $ele.bg.addClass('on');

                } else {
                    $ele.btn.removeClass('on');
                    $ele.slide.removeClass('on');
                    $ele.bg.removeClass('on');
                }
            }
        })
    }
}
$(function() {
    mo_slide.init();
})


///
// gnb for mobile
///
mo_gnb = {
    'init' : function() {
        this.action();
    },
    'action' : function() {
        var $ele = {
            'win' : $(window),
            'doc' : $(document),
            'menu' : $('#mo-gnb a')
        }
        //open & close
        $ele.menu.on({
            'click' : function(e) {
                var $ul = $(this).parent('li').children('ul');
                var $ul_sib = $(this).parent('li').siblings().children('ul');
                var len = $ul.length;

                if (len > 0) {
                    e.preventDefault();
                    $ul.toggle();
                    $ul_sib.hide();
                }
            }
        })
    }
}
$(function() {
    mo_gnb.init();
})


///
// gnb active
///
gnbActive = {
    'init' : function() {
        this.action();
    },
    'action' : function() {
        if (typeof PH_CATEGORY_KEY !== 'undefined') {
            $('#gnb a[data-category-key=' + PH_CATEGORY_KEY + '], #mo-gnb a[data-category-key=' + PH_CATEGORY_KEY + ']').parents('li').addClass('on');
        }
    }
}
$(function() {
    gnbActive.init();
})
