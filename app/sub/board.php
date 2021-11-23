<?php

/***
News
***/
class News extends \controller\Make_Controller {

    public function init()
    {
        $this->layout()->category_key(3);
        $this->layout()->head();
        $this->layout()->view(PH_THEME_PATH.'/html/sub/news.tpl.php');
        $this->layout()->foot();
    }

    public function make(){

    }

    public function module(){
        $module = new \Module\Board\Make_Controller();
        $module->set('id', 'news');
        $module->run();
    }

}

/***
Free
***/
class Free extends \controller\Make_Controller {

    public function init()
    {
        $this->layout()->category_key(4);
        $this->layout()->head();
        $this->layout()->view(PH_THEME_PATH.'/html/sub/free.tpl.php');
        $this->layout()->foot();
    }

    public function make(){

    }

    public function module(){
        $module = new \Module\Board\Make_Controller();
        $module->set('id', 'freeboard');
        $module->run();
    }

}
