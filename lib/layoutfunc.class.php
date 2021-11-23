<?php
namespace Make\View;

use Corelib\Func;

class Layout {

    public function logo_title()
    {
        global $CONF;
        return $CONF['title'];
    }

    public function site_href()
    {
        return PH_DOMAIN;
    }

    public function logo_src()
    {
        global $CONF;

        if ($CONF['logo']) {
            return PH_DATA_DIR.'/manage/'.$CONF['logo'];

        }else{
            return PH_THEME_DIR.'/layout/images/logo.png';
        }
    }

    public function signin_href()
    {
        $link = PH_DIR.'/sign/signin?redirect='.urlencode(Func::thisuriqry());

        if (Func::thisctrlr() == 'sign' || Func::thisctrlr() == 'member') {
            $link = PH_DIR.'/sign/signin?redirect=/';
        }

        return $link;
    }

}
