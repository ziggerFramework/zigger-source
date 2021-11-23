<?php

/***
Contents
***/
class Contents extends \controller\Make_Controller {

    public function init()
    {
        $this->layout()->category_key(1);
        $this->layout()->head();
        $this->layout()->view(PH_THEME_PATH.'/html/sub/contents.tpl.php');
        $this->layout()->foot();
    }

    public function make()
    {

    }

    public function module()
    {
        $module = new \Module\Contents\Make_Controller();
        $module->set('key', 'sample');
        $module->run();
    }

}


/***
Contactus
***/
class Contactus extends \controller\Make_Controller {

    public function init()
    {
        $this->layout()->category_key(5);
        $this->layout()->head();
        $this->layout()->view(PH_THEME_PATH.'/html/sub/contactus.tpl.php');
        $this->layout()->foot();
    }

    public function make()
    {

    }

    public function module()
    {
        $module = new \Module\Contactform\Make_Controller();
        $module->run();
    }

}
