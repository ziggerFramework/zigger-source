<?php
use Corelib\Func;
use Corelib\Method;
use Make\Database\Pdosql;
use Make\Library\Paging;

/***
Index
***/
class index extends \Controller\Make_Controller {

    public function init()
    {
        $this->layout()->head();
        $this->layout()->view(PH_THEME_PATH.'/html/alarm/index.tpl.php');
        $this->layout()->foot();
    }

    public function make()
    {

    }

    public function module(){
        $module = new \Module\Alarm\Make_Controller();
        $module->run();
    }

}
