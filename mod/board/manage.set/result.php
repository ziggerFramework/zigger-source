<?php
use Corelib\Func;
use Corelib\Method;
use Corelib\Valid;
use Make\Database\Pdosql;
use Make\Library\Uploader;
use Make\Library\Paging;
use Make\Library\Mail;
use Manage\ManageFunc;

class Result extends \Controller\Make_Controller {

    public function init()
    {
        $this->layout()->mng_head();
        $this->layout()->view(MOD_BOARD_PATH.'/manage.set/html/result.tpl.php');
        $this->layout()->mng_foot();
    }

    public function func()
    {
        function board_total($arr)
        {
            return Func::number($arr['board_total']);
        }

        function data_total($arr)
        {
            global $board_id;

            $sql = new Pdosql();

            $board_id = $arr['id'];

            $sql->query(
                "
                SELECT *
                FROM {$sql->table("mod:board_data_".$board_id)}
                WHERE dregdate IS NULL AND (use_notice='Y' or use_notice='N')
                ",
                array(
                    $board_id
                )
            );
            return Func::number($sql->getcount());
        }
    }

    public function make()
    {
        global $PARAM, $sortby, $searchby, $orderby;

        $sql = new Pdosql();
        $paging = new Paging();
        $manage = new ManageFunc();

        $sql->query(
            "
            SELECT
            (
                SELECT COUNT(*)
                FROM {$sql->table("mod:board_config")}
            ) board_total
            ", []
        );
        $sort_arr['board_total'] = $sql->fetch('board_total');

        //orderby
        if (!$PARAM['ordtg']) {
            $PARAM['ordtg'] = 'regdate';
        }
        if (!$PARAM['ordsc']) {
            $PARAM['ordsc'] = 'desc';
        }
        $orderby = $PARAM['ordtg'].' '.$PARAM['ordsc'];

        //list
        $sql->query(
            $paging->query(
                "
                SELECT *
                FROM {$sql->table("mod:board_config")}
                WHERE 1 $sortby $searchby
                ORDER BY $orderby
                ", []
            )
        );
        $list_cnt = $sql->getcount();
        $total_cnt = Func::number($paging->totalCount);
        $print_arr = array();

        if ($list_cnt > 0) {
            do {
                $arr = $sql->fetchs();

                $arr['no'] = $paging->getnum();
                $arr[0]['data_total'] = data_total($arr);
                $arr['regdate'] = Func::datetime($arr['regdate']);

                $print_arr[] = $arr;

            } while ($sql->nextRec());
        }

        $this->set('manage', $manage);
        $this->set('keyword', $PARAM['keyword']);
        $this->set('board_total', board_total($sort_arr));
        $this->set('pagingprint', $paging->pagingprint($manage->pag_def_param()));
        $this->set('print_arr', $print_arr);

    }

    public function form($idx)
    {
        $form = new \Controller\Make_View_Form();
        $form->set('id', 'makeBoardForm'.$idx);
        $form->set('type', 'html');
        $form->set('action', PH_MANAGE_DIR.'/mod/'.MOD_BOARD_DIR.'/result/result-clone-submit');
        $form->run();
    }

}

/***
Submit for Result clone
***/
class Result_clone_submit{

    public function init()
    {
        global $board_id, $clone_id, $board_title;

        $sql = new Pdosql();
        $manage = new ManageFunc();

        Method::security('referer');
        Method::security('request_post');
        $req = Method::request('post','board_id, clone_id');

        Valid::get(
            array(
                'input' => 'board_id',
                'value' => $req['board_id'],
                'check' => array(
                    'defined' => 'idx'
                )
            )
        );
        Valid::get(
            array(
                'input' => 'clone_id',
                'value' => $req['clone_id'],
                'check' => array(
                    'defined' => 'idx'
                )
            )
        );

        $board_id = $req['board_id'];
        $clone_id = $req['clone_id'];

        $sql->query(
            "
            SELECT *
            FROM {$sql->table("mod:board_config")}
            WHERE id=:col1
            ORDER BY regdate DESC
            ",
            array(
                $board_id
            )
        );

        if ($sql->getcount() < 1) {
            Valid::error('', '복제할 게시판이 존재하지 않습니다.');
        }
        $board_title = addSlashes($sql->fetch('title'));


        $sql->query(
            "
            SELECT *
            FROM {$sql->table("mod:board_config")}
            WHERE id=:col1
            ORDER BY regdate DESC
            ",
            array(
                $clone_id
            )
        );

        if ($sql->getcount() > 0) {
            Valid::error('clone_id', '생성할 게시판 id가 이미 존재하는 id입니다.');
        }

        $sql->query(
            "
            INSERT INTO
            {$sql->table("mod:board_config")}
            (id,title,regdate,theme,use_list,use_secret,use_comment,use_likes,use_reply,use_file1,use_file2,use_mng_feed,use_category,category,file_limit,list_limit,sbj_limit,txt_limit,article_min_len,list_level,write_level,secret_level,comment_level,delete_level,read_level,ctr_level,reply_level,write_point,read_point,top_source,bottom_source,ico_file,ico_secret,ico_secret_def,ico_new,ico_new_case,ico_hot,ico_hot_case,conf_1,conf_2,conf_3,conf_4,conf_5,conf_6,conf_7,conf_8,conf_9,conf_10,conf_exp)
            SELECT
            (:col1),(:col2),(now()),theme,use_list,use_secret,use_comment,use_likes,use_reply,use_file1,use_file2,use_mng_feed,use_category,category,file_limit,list_limit,sbj_limit,txt_limit,article_min_len,list_level,write_level,secret_level,comment_level,delete_level,read_level,ctr_level,reply_level,write_point,read_point,top_source,bottom_source,ico_file,ico_secret,ico_secret_def,ico_new,ico_new_case,ico_hot,ico_hot_case,conf_1,conf_2,conf_3,conf_4,conf_5,conf_6,conf_7,conf_8,conf_9,conf_10,conf_exp
            FROM {$sql->table("mod:board_config")}
            WHERE id=:col3
            ",
            array(
                $clone_id,
                '\''.$board_title.'\'에서 복제됨',
                $board_id
            )
        );

        $board_id = $clone_id;

        $sql->query(
            "
            CREATE TABLE IF NOT EXISTS {$sql->table("mod:board_data_")}$board_id (
            idx int(11) NOT NULL auto_increment,
            category varchar(255) default NULL,
            ln int(11) default '0',
            rn int(11) default '0',
            mb_idx int(11) default '0',
            mb_id varchar(255) default NULL,
            writer varchar(255) default NULL,
            pwd text,
            email varchar(255) default NULL,
            article text,
            subject varchar(255) default NULL,
            file1 text,
            file1_cnt int(11) default '0',
            file2 text,
            file2_cnt int(11) default '0',
            use_secret char(1) default 'N',
            use_notice char(1) default 'N',
            use_html char(1) default 'Y',
            use_email char(1) default 'Y',
            view int(11) default '0',
            ip varchar(255) default NULL,
            regdate datetime default NULL,
            dregdate datetime default NULL,
            data_1 text,
            data_2 text,
            data_3 text,
            data_4 text,
            data_5 text,
            data_6 text,
            data_7 text,
            data_8 text,
            data_9 text,
            data_10 text,
            PRIMARY KEY(idx)
            )ENGINE=InnoDB DEFAULT CHARSET=utf8;
            ", []
        );

        $sql->query(
            "
            CREATE TABLE IF NOT EXISTS {$sql->table("mod:board_cmt_")}$board_id (
            idx int(11) NOT NULL auto_increment,
            ln int(11) default '0',
            rn int(11) default '0',
            bo_idx int(11) default NULL,
            mb_idx int(11) default '0',
            writer varchar(255) default NULL,
            comment text,
            ip varchar(255) default NULL,
            regdate datetime default NULL,
            cmt_1 text,
            cmt_2 text,
            cmt_3 text,
            cmt_4 text,
            cmt_5 text,
            cmt_6 text,
            cmt_7 text,
            cmt_8 text,
            cmt_9 text,
            cmt_10 text,
            PRIMARY KEY(idx)
            )ENGINE=InnoDB DEFAULT CHARSET=utf8;
            ", []
        );

        Valid::set(
            array(
                'return' => 'alert->reload',
                'msg' => '게시판이 성공적으로 복제 되었습니다.'
            )
        );
        Valid::return();
    }

}

/***
Regist
***/
class Regist extends \Controller\Make_Controller {

    public function init(){
        $this->layout()->mng_head();
        $this->layout()->view(MOD_BOARD_PATH.'/manage.set/html/regist.tpl.php');
        $this->layout()->mng_foot();
    }

    public function func()
    {
        function board_theme(){
            $tpath = PH_THEME_PATH.'/mod-'.MOD_BOARD.'/board/';
            $topen = opendir($tpath);
            $topt = '';

            while ($dir = readdir($topen)) {
                if ($dir != '.' && $dir != '..') {
                    $topt .= '<option value="'.$dir.'">'.$dir.'</option>';
                    $bd_theme[] = $dir;
                }
            }
            return $topt;
        }
    }

    public function make()
    {
        $manage = new ManageFunc();

        Func::add_javascript(PH_PLUGIN_DIR.'/'.PH_PLUGIN_CKEDITOR.'/ckeditor.js');

        $manage->make_target('게시판 기본 설정|권한 설정|아이콘 출력 설정|여분필드');

        $this->set('manage', $manage);
        $this->set('print_target', $manage->print_target());
        $this->set('board_theme', board_theme());
    }

    public function form()
    {
        $form = new \Controller\Make_View_Form();
        $form->set('id', 'makeBoardForm');
        $form->set('type', 'html');
        $form->set('action', PH_MANAGE_DIR.'/mod/'.MOD_BOARD_DIR.'/result/regist-submit');
        $form->run();
    }

}

/***
Submit for Regist
***/
class Regist_submit {

    public function init(){
        global $board_id;

        $sql = new Pdosql();
        $manage = new ManageFunc();

        Method::security('referer');
        Method::security('request_post');
        $req = Method::request('post', 'id, title, theme, use_category, category, use_list, m_use_list, list_limit, m_list_limit, sbj_limit, m_sbj_limit, txt_limit, m_txt_limit, use_likes, use_reply, use_comment, use_secret, ico_secret_def, use_file1, use_file2, use_mng_feed, file_limit, article_min_len, top_source, bottom_source, ctr_level, list_level, write_level, secret_level, comment_level, reply_level, delete_level, read_level, write_level, read_point, write_point, ico_file, ico_secret, ico_new, ico_new_case, ico_hot, ico_hot_case_1, ico_hot_case_2, ico_hot_case_3, conf_1, conf_2, conf_3, conf_4, conf_5, conf_6, conf_7, conf_8, conf_9, conf_10, conf_exp');

        $board_id = $req['id'];

        Valid::get(
            array(
                'input' => 'id',
                'value' => $req['id'],
                'check' => array(
                    'defined' => 'idx'
                )
            )
        );
        Valid::get(
            array(
                'input' => 'title',
                'value' => $req['title']
            )
        );
        Valid::get(
            array(
                'input' => 'file_limit',
                'value' => $req['file_limit'],
                'check' => array(
                    'charset' => 'number',
                    'maxlen' => 50
                )
            )
        );
        Valid::get(
            array(
                'input' => 'ico_new_case',
                'value' => $req['ico_new_case'],
                'check' => array(
                    'charset' => 'number',
                    'maxlen' => 10
                )
            )
        );
        Valid::get(
            array(
                'input' => 'ico_hot_case_1',
                'value' => $req['ico_hot_case_1'],
                'check' => array(
                    'charset' => 'number',
                    'maxlen' => 10
                )
            )
        );
        Valid::get(
            array(
                'input' => 'ico_hot_case_2',
                'value' => $req['ico_hot_case_2'],
                'check' => array(
                    'charset' => 'number',
                    'maxlen' => 10
                )
            )
        );

        if ($req['use_category'] == 'Y' && !$req['category']) {
            Valid::error('category', '카테고리 설정을 확인하세요.');
        }
        if (!$req['list_limit']) {
            $req['list_limit'] = 15;
        }
        if (!$req['m_list_limit']) {
            $req['m_list_limit'] = 10;
        }
        if (!$req['sbj_limit']) {
            $req['sbj_limit'] = 50;
        }
        if (!$req['m_sbj_limit']) {
            $req['m_sbj_limit'] = 30;
        }
        if (!$req['txt_limit']) {
            $req['txt_limit'] = 150;
        }
        if (!$req['m_txt_limit']) {
            $req['m_txt_limit'] = 100;
        }
        if (!$req['article_min_len']) {
            $req['article_min_len'] = 30;
        }
        if (!$req['read_point']) {
            $req['read_point'] = 0;
        }
        if (!$req['write_point']) {
            $req['write_point'] = 0;
        }

        $conf_exp = $sql->etcfd_exp(implode('|', $req['conf_exp']));

        $req['use_list'] = $req['use_list'].'|'.$req['m_use_list'];
        $req['list_limit'] = $req['list_limit'].'|'.$req['m_list_limit'];
        $req['sbj_limit'] = $req['sbj_limit'].'|'.$req['m_sbj_limit'];
        $req['txt_limit'] = $req['txt_limit'].'|'.$req['m_txt_limit'];
        $req['ico_hot_case'] = $req['ico_hot_case_1'].'|'.$req['ico_hot_case_3'].'|'.$req['ico_hot_case_2'];

        $sql->query(
            "
            CREATE TABLE IF NOT EXISTS {$sql->table("mod:board_data_")}$board_id (
            idx int(11) NOT NULL auto_increment,
            category varchar(255) default NULL,
            ln int(11) default '0',
            rn int(11) default '0',
            mb_idx int(11) default '0',
            mb_id varchar(255) default NULL,
            writer varchar(255) default NULL,
            pwd text,
            email varchar(255) default NULL,
            article text,
            subject varchar(255) default NULL,
            file1 text,
            file1_cnt int(11) default '0',
            file2 text,
            file2_cnt int(11) default '0',
            use_secret char(1) default 'N',
            use_notice char(1) default 'N',
            use_html char(1) default 'Y',
            use_email char(1) default 'Y',
            view int(11) default '0',
            ip varchar(255) default NULL,
            regdate datetime default NULL,
            dregdate datetime default NULL,
            data_1 text,
            data_2 text,
            data_3 text,
            data_4 text,
            data_5 text,
            data_6 text,
            data_7 text,
            data_8 text,
            data_9 text,
            data_10 text,
            PRIMARY KEY(idx)
            )ENGINE=InnoDB DEFAULT CHARSET=utf8;
            ", []
        );

        $sql->query(
            "
            CREATE TABLE IF NOT EXISTS {$sql->table("mod:board_cmt_")}$board_id (
            idx int(11) NOT NULL auto_increment,
            ln int(11) default '0',
            rn int(11) default '0',
            bo_idx int(11) default NULL,
            mb_idx int(11) default '0',
            writer varchar(255) default NULL,
            comment text,
            ip varchar(255) default NULL,
            regdate datetime default NULL,
            cmt_1 text,
            cmt_2 text,
            cmt_3 text,
            cmt_4 text,
            cmt_5 text,
            cmt_6 text,
            cmt_7 text,
            cmt_8 text,
            cmt_9 text,
            cmt_10 text,
            PRIMARY KEY(idx)
            )ENGINE=InnoDB DEFAULT CHARSET=utf8;
            ", []
        );

        $sql->query(
            "
            INSERT INTO {$sql->table("mod:board_config")}
            (id,theme,title,use_list,use_secret,use_comment,use_likes,use_reply,use_file1,use_file2,use_mng_feed,use_category,category,file_limit,list_limit,sbj_limit,txt_limit,article_min_len,list_level,write_level,secret_level,comment_level,delete_level,read_level,ctr_level,reply_level,write_point,read_point,top_source,bottom_source,ico_file,ico_secret,ico_secret_def,ico_new,ico_new_case,ico_hot,ico_hot_case,regdate,conf_1,conf_2,conf_3,conf_4,conf_5,conf_6,conf_7,conf_8,conf_9,conf_10,conf_exp)
            VALUES
            (:col1,:col2,:col3,:col4,:col5,:col6,:col7,:col8,:col9,:col10,:col11,:col12,:col13,:col14,:col15,:col16,:col17,:col18,:col19,:col20,:col21,:col22,:col23,:col24,:col25,:col26,:col27,:col28,:col29,:col30,:col31,:col32,:col33,:col34,:col35,:col36,:col37,now(),:col38,:col39,:col40,:col41,:col42,:col43,:col44,:col45,:col46,:col47,:col48)
            ",
            array(
                $req['id'], $req['theme'], $req['title'], $req['use_list'], $req['use_secret'], $req['use_comment'], $req['use_likes'], $req['use_reply'], $req['use_file1'], $req['use_file2'], $req['use_mng_feed'], $req['use_category'], $req['category'], $req['file_limit'], $req['list_limit'], $req['sbj_limit'], $req['txt_limit'], $req['article_min_len'], $req['list_level'], $req['write_level'], $req['secret_level'], $req['comment_level'], $req['delete_level'], $req['read_level'], $req['ctr_level'], $req['reply_level'], $req['write_point'], $req['read_point'], $req['top_source'], $req['bottom_source'], $req['ico_file'], $req['ico_secret'], $req['ico_secret_def'], $req['ico_new'], $req['ico_new_case'], $req['ico_hot'], $req['ico_hot_case'], $req['conf_1'], $req['conf_2'], $req['conf_3'], $req['conf_4'], $req['conf_5'], $req['conf_6'], $req['conf_7'], $req['conf_8'], $req['conf_9'], $req['conf_10'], $conf_exp
            )
        );

        $sql->query(
            "
            SELECT *
            FROM {$sql->table("mod:board_config")}
            ORDER BY regdate DESC
            ", []
        );
        $idx = $sql->fetch('idx');

        Valid::set(
            array(
                'return' => 'alert->location',
                'msg' => '성공적으로 추가 되었습니다.',
                'location' => PH_MANAGE_DIR.'/mod/'.MOD_BOARD.'/result/modify?idx='.$idx
            )
        );
        Valid::return();
    }

}

/***
Modify
***/
class Modify extends \Controller\Make_Controller {

    public function init(){
        $this->layout()->mng_head();
        $this->layout()->view(MOD_BOARD_PATH.'/manage.set/html/modify.tpl.php');
        $this->layout()->mng_foot();
    }

    public function func()
    {
        function board_theme($arr)
        {
            $tpath = PH_THEME_PATH.'/mod-'.MOD_BOARD.'/board/';
            $topen = opendir($tpath);
            $topt = '';

            while ($dir = readdir($topen)) {
                $slted = '';
                if ($dir != '.' && $dir != '..') {
                    if ($dir == $arr['theme']) {
                        $slted = 'selected';
                    }
                    $topt .= '<option value="'.$dir.'" '.$slted.'>'.$dir.'</option>';
                }
            }
            return $topt;
        }

        function set_chked($arr, $val)
        {
            $setarr = array(
                'Y' => '',
                'N' => '',
                'AND' => '',
                'OR' => ''
            );
            foreach ($setarr as $key => $value) {
                if ($key == $arr[$val]) {
                    $setarr[$key] = 'checked';
                }
            }
            return $setarr;
        }
    }

    public function make()
    {
        $sql = new Pdosql();
        $manage = new ManageFunc();

        $req = Method::request('get', 'idx');

        Func::add_javascript(PH_PLUGIN_DIR.'/'.PH_PLUGIN_CKEDITOR.'/ckeditor.js');

        $manage->make_target('게시판 기본 설정|권한 설정|아이콘 출력 설정|여분필드');

        $sql->query(
            "
            SELECT *
            FROM {$sql->table("mod:board_config")}
            WHERE idx=:col1
            LIMIT 1
            ",
            array(
                $req['idx']
            )
        );

        if ($sql->getcount() < 1) {
            Func::err_back('게시판이 존재하지 않습니다.');
        }

        $arr = $sql->fetchs();
        $sql->specialchars = 0;
        $sql->nl2br = 0;

        $arr['top_source'] = $sql->fetch('top_source');
        $arr['bottom_source'] = $sql->fetch('bottom_source');

        $use_list = explode('|', $arr['use_list']);
        $arr['use_list'] = $use_list[0];
        $arr['m_use_list'] = $use_list[1];

        $list_limit = explode('|', $arr['list_limit']);
        $arr['list_limit'] = $list_limit[0];
        $arr['m_list_limit'] = $list_limit[1];

        $sbj_limit = explode('|', $arr['sbj_limit']);
        $arr['sbj_limit'] = $sbj_limit[0];
        $arr['m_sbj_limit'] = $sbj_limit[1];

        $txt_limit = explode('|', $arr['txt_limit']);
        $arr['txt_limit'] = $txt_limit[0];
        $arr['m_txt_limit'] = $txt_limit[1];

        $ico_hot_case = explode('|', $arr['ico_hot_case']);
        $arr['ico_hot_case_1'] = $ico_hot_case[0];
        $arr['ico_hot_case_2'] = $ico_hot_case[2];
        $arr['ico_hot_case_3'] = $ico_hot_case[1];

        $ex = explode('|', $arr['conf_exp']);

        for ($i = 1; $i <= 10; $i++) {
            $arr['conf_'.$i.'_exp'] = $ex[$i - 1];
        }

        $write = array();

        if (isset($arr)) {
            foreach ($arr as $key => $value) {
                $write[$key] = $value;
            }

        } else {
            $write = null;
        }

        $this->set('manage', $manage);
        $this->set('write', $write);
        $this->set('print_target', $manage->print_target());
        $this->set('board_theme', board_theme($arr));
        $this->set('use_category', set_chked($arr, 'use_category'));
        $this->set('use_list', set_chked($arr, 'use_list'));
        $this->set('m_use_list', set_chked($arr, 'm_use_list'));
        $this->set('use_likes', set_chked($arr, 'use_likes'));
        $this->set('use_reply', set_chked($arr, 'use_reply'));
        $this->set('use_comment', set_chked($arr, 'use_comment'));
        $this->set('use_secret', set_chked($arr, 'use_secret'));
        $this->set('ico_secret_def', set_chked($arr, 'ico_secret_def'));
        $this->set('use_file1', set_chked($arr, 'use_file1'));
        $this->set('use_file2', set_chked($arr, 'use_file2'));
        $this->set('use_mng_feed', set_chked($arr, 'use_mng_feed'));
        $this->set('ico_file', set_chked($arr, 'ico_file'));
        $this->set('ico_secret', set_chked($arr, 'ico_secret'));
        $this->set('ico_new', set_chked($arr, 'ico_new'));
        $this->set('ico_hot', set_chked($arr, 'ico_hot'));
        $this->set('ico_hot_case_3', set_chked($arr, 'ico_hot_case_3'));
    }

    public function form()
    {
        $form = new \Controller\Make_View_Form();
        $form->set('id', 'modifyBoardForm');
        $form->set('type', 'html');
        $form->set('action', PH_MANAGE_DIR.'/mod/'.MOD_BOARD_DIR.'/result/modify-submit');
        $form->run();
    }

}

/***
Submit for Modify
***/
class Modify_submit {

    public function init()
    {
        global $req;

        $manage = new ManageFunc();

        Method::security('referer');
        Method::security('request_post');
        $req = Method::request('post', 'mode, idx, id, title, theme, use_category, category, use_list, m_use_list, list_limit, m_list_limit, sbj_limit, m_sbj_limit, txt_limit, m_txt_limit, use_likes, use_reply, use_comment, use_secret, ico_secret_def, use_file1, use_file2, use_mng_feed, file_limit, article_min_len, top_source, bottom_source, ctr_level, list_level, write_level, secret_level, comment_level, reply_level, delete_level, read_level, write_level, read_point, write_point, ico_file, ico_secret, ico_new, ico_new_case, ico_hot, ico_hot_case_1, ico_hot_case_2, ico_hot_case_3, conf_1, conf_2, conf_3, conf_4, conf_5, conf_6, conf_7, conf_8, conf_9, conf_10, conf_exp');
        $manage->req_hidden_inp('post');

        switch ($req['mode']) {
            case 'mod' :
                $this->get_modify();
                break;

            case 'del' :
                $this->get_delete();
                break;
        }
    }

    ///
    // modify
    ///
    public function get_modify()
    {
        global $req;

        $sql = new Pdosql();

        Valid::get(
            array(
                'input' => 'title',
                'value' => $req['title']
            )
        );
        Valid::get(
            array(
                'input' => 'file_limit',
                'value' => $req['file_limit'],
                'check' => array(
                    'charset' => 'number',
                    'maxlen' => 50
                )
            )
        );
        Valid::get(
            array(
                'input' => 'ico_new_case',
                'value' => $req['ico_new_case'],
                'check' => array(
                    'charset' => 'number',
                    'maxlen' => 10
                )
            )
        );
        Valid::get(
            array(
                'input' => 'ico_hot_case_1',
                'value' => $req['ico_hot_case_1'],
                'check' => array(
                    'charset' => 'number',
                    'maxlen' => 10
                )
            )
        );
        Valid::get(
            array(
                'input' => 'ico_hot_case_2',
                'value' => $req['ico_hot_case_2'],
                'check' => array(
                    'charset' => 'number',
                    'maxlen' => 10
                )
            )
        );

        if ($req['use_category'] == 'Y' && !$req['category']) {
            Valid::error('category', '카테고리 설정을 확인하세요.');
        }
        if (!$req['list_limit']) {
            $req['list_limit'] = 15;
        }
        if (!$req['m_list_limit']) {
            $req['m_list_limit'] = 10;
        }
        if (!$req['sbj_limit']) {
            $req['sbj_limit'] = 50;
        }
        if (!$req['m_sbj_limit']) {
            $req['m_sbj_limit'] = 30;
        }
        if (!$req['txt_limit']) {
            $req['txt_limit'] = 150;
        }
        if (!$req['m_txt_limit']) {
            $req['m_txt_limit'] = 100;
        }
        if (!$req['article_min_len']) {
            $req['article_min_len'] = 30;
        }
        if (!$req['read_point']) {
            $req['read_point'] = 0;
        }
        if (!$req['write_point']) {
            $req['write_point'] = 0;
        }

        $conf_exp = $sql->etcfd_exp(implode('|', $req['conf_exp']));

        $req['use_list'] = $req['use_list'].'|'.$req['m_use_list'];
        $req['list_limit'] = $req['list_limit'].'|'.$req['m_list_limit'];
        $req['sbj_limit'] = $req['sbj_limit'].'|'.$req['m_sbj_limit'];
        $req['txt_limit'] = $req['txt_limit'].'|'.$req['m_txt_limit'];
        $req['ico_hot_case'] = $req['ico_hot_case_1'].'|'.$req['ico_hot_case_3'].'|'.$req['ico_hot_case_2'];

        $sql->query(
            "
            UPDATE {$sql->table("mod:board_config")}
            SET theme=:col1,title=:col2,use_list=:col3,use_secret=:col4,use_comment=:col5,use_likes=:col6,use_reply=:col7,use_file1=:col8,use_file2=:col9,use_mng_feed=:col10,use_category=:col11,category=:col12,file_limit=:col13,list_limit=:col14,sbj_limit=:col15,txt_limit=:col16,article_min_len=:col17,list_level=:col18,write_level=:col19,secret_level=:col20,comment_level=:col21,delete_level=:col22,read_level=:col23,ctr_level=:col24,reply_level=:col25,write_point=:col26,read_point=:col27,top_source=:col28,bottom_source=:col29,ico_file=:col30,ico_secret=:col31,ico_secret_def=:col32,ico_new=:col33,ico_new_case=:col34,ico_hot=:col35,ico_hot_case=:col36,conf_1=:col37,conf_2=:col38,conf_3=:col39,conf_4=:col40,conf_5=:col41,conf_6=:col42,conf_7=:col43,conf_8=:col44,conf_9=:col45,conf_10=:col46,conf_exp=:col47
            WHERE idx=:col48
            ",
            array(
                $req['theme'], $req['title'], $req['use_list'], $req['use_secret'], $req['use_comment'], $req['use_likes'], $req['use_reply'], $req['use_file1'], $req['use_file2'], $req['use_mng_feed'], $req['use_category'], $req['category'], $req['file_limit'], $req['list_limit'], $req['sbj_limit'], $req['txt_limit'], $req['article_min_len'], $req['list_level'], $req['write_level'], $req['secret_level'], $req['comment_level'], $req['delete_level'], $req['read_level'], $req['ctr_level'], $req['reply_level'], $req['write_point'], $req['read_point'], $req['top_source'], $req['bottom_source'], $req['ico_file'], $req['ico_secret'], $req['ico_secret_def'], $req['ico_new'], $req['ico_new_case'], $req['ico_hot'], $req['ico_hot_case'], $req['conf_1'], $req['conf_2'], $req['conf_3'], $req['conf_4'], $req['conf_5'], $req['conf_6'], $req['conf_7'], $req['conf_8'], $req['conf_9'], $req['conf_10'], $conf_exp,
                $req['idx']
            )
        );

        Valid::set(
            array(
                'return' => 'alert->reload',
                'msg' => '성공적으로 변경 되었습니다.'
            )
        );
        Valid::return();
    }

    ///
    // delete
    ///
    public function get_delete()
    {
        global $req, $board_id;

        $sql = new Pdosql();
        $uploader = new Uploader();
        $manage = new ManageFunc();

        $sql->query(
            "
            SELECT *
            FROM {$sql->table("mod:board_config")}
            WHERE idx=:col1
            LIMIT 1
            ",
            array(
                $req['idx']
            )
        );

        $board_id = $sql->fetch('id');

        if ($sql->getcount() < 1) {
            Valid::error('', '게시판이 존재하지 않습니다.');
        }

        $sql->query(
            "
            DELETE
            FROM {$sql->table("mod:board_config")}
            WHERE idx=:col1
            ",
            array(
                $req['idx']
            )
        );

        $sql->query(
            "
            DROP TABLE {$sql->table("mod:board_data_")}$board_id
            ", []
        );

        $sql->query(
            "
            DROP TABLE {$sql->table("mod:board_cmt_")}$board_id
            ", []
        );

        $uploader->path = MOD_BOARD_DATA_PATH.'/'.$board_id.'/thumb';
        $uploader->dropdir();
        $uploader->path = MOD_BOARD_DATA_PATH.'/'.$board_id;
        $uploader->dropdir();

        Valid::set(
            array(
                'return' => 'alert->location',
                'msg' => '성공적으로 삭제 되었습니다.',
                'location' => PH_MANAGE_DIR.'/mod/'.MOD_BOARD.'/result/result'.$manage->retlink('')
            )
        );
        Valid::return();
    }

}
