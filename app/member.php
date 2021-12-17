<?php
use Corelib\Func;
use Corelib\Method;
use Corelib\Session;
use Corelib\Valid;
use Corelib\Blocked;
use Make\Database\Pdosql;
use Make\Library\Mail;
use Make\Library\Paging;
use Module\Message\Library as Message_Library;
use Module\Alarm\Library as Alarm_Library;

/***
Index
***/
class Index extends \Controller\Make_Controller {

    public function init()
    {
        $this->layout()->head();
        $this->layout()->view(PH_THEME_PATH.'/html/member/index.tpl.php');
        $this->layout()->foot();
    }

    public function make()
    {
        global $MB;

        $Message_Library = new Message_Library();
        $Alarm_Library = new Alarm_Library();

        $this->set('message_new_count', Func::number($Message_Library->get_new_count()));
        $this->set('alarm_new_count', Func::number($Alarm_Library->get_new_count()));
        $this->set('point_total_count', Func::number($MB['point']));
    }

}

/***
Info
***/
class Info extends \Controller\Make_Controller {

    public function init()
    {
        $this->layout()->head();
        $this->layout()->view(PH_THEME_PATH.'/html/member/info.tpl.php');
        $this->layout()->foot();
    }

    public function func()
    {
        //성별 처리
        function gender_chked($obj)
        {
            $arr = array(
                'M' => '',
                'F' => ''
            );
            foreach ($arr as $key => $value) {
                if ($key == $obj['mb_gender']) {
                    $arr[$key] = 'checked';
                }
            }
            return $arr;
        }
    }

    public function make()
    {
        global $MB;

        $sql = new Pdosql();

        Func::getlogin(SET_NOAUTH_MSG);

        if ($MB['adm'] == 'Y') {
            Func::err_location('최고 레벨의 관리자는 Manage 에서 정보 변경 가능합니다.', PH_DOMAIN);
        }

        //회원 정보 select
        $sql->query(
            "
            SELECT *
            FROM {$sql->table("member")}
            WHERE mb_idx=:col1 AND mb_dregdate IS NULL
            ",
            array(
                MB_IDX
            )
        );
        $arr = $sql->fetchs();

        $arr['mb_point'] = Func::number($arr['mb_point']);
        $arr['mb_regdate'] = Func::datetime($arr['mb_regdate']);
        $arr['mb_lately'] = Func::datetime($arr['mb_lately']);

        $mb = array();

        if (isset($arr)) {
            foreach($arr as $key => $value){
                $mb[$key] = $value;
            }

        }else{
            $mb = null;
        }

        $this->set('gender_chked', gender_chked($arr));
        $this->set('mb', $mb);
    }

    public function form()
    {
        $form = new \Controller\Make_View_Form();
        $form->set('type', 'html');
        $form->set('action', '/member/info-submit');
        $form->run();
    }

}

/***
Submit for Info
***/
class Info_submit {

    public function init()
    {
        global $req;

        Method::security('referer');
        Method::security('request_post');
        $req = Method::request('post', 'mode, email, pwd, pwd2, name, gender, phone, telephone, email_chg_cc');

        if (!IS_MEMBER) {
            Valid::error('', SET_NOAUTH_MSG);
        }

        switch ($req['mode']) {
            case 'mdf' :
                $this->get_mdf();
                break;

            case 'lv' :
                $this->get_lv();
                break;
        }
    }

    ///
    // 회원 정보 변경
    ///
    private function get_mdf()
    {
        global $MB, $CONF, $req;

        $sql = new Pdosql();
        $mail = new Mail();

        Valid::get(
            array(
                'input' => 'name',
                'value' => $req['name'],
                'check' => array(
                    'defined' => 'nickname'
                )
            )
        );
        Valid::get(
            array(
                'input' => 'phone',
                'value' => $req['phone'],
                'check' => array(
                    'null' => true,
                    'defined' => 'phone'
                )
            )
        );
        Valid::get(
            array(
                'input' => 'telephone',
                'value' => $req['telephone'],
                'check' => array(
                    'null' => true,
                    'defined' => 'phone'
                )
            )
        );

        //기존 회원 정보에 이메일이 없다면 수정 금지
        if (!$req['email'] && $req['email_chg_cc'] != 'checked' && !$MB['email']) {
            Valid::error('email', '기존 회원정보에 이메일 정보가 없어 수정이 불가합니다. 수정 전 이메일 변경 먼저 해주세요.');
        }

        //비밀번호가 입력된 경우
        if ($req['pwd'] != $req['pwd2']) {
            Valid::error('pwd2', '비밀번호와 비밀번호 확인이 일치하지 않습니다.');
        }
        if ($req['pwd'] != '') {
            Valid::get(
                array(
                    'input' => 'pwd',
                    'value' => $req['pwd'],
                    'check' => array(
                        'defined' => 'password'
                    )
                )
            );
        }

        //이메일이 입력된 경우
        $mb_email_chg = $MB['email_chg'];

        if ($req['email'] != '' && $req['email'] == $MB['email']) {
            Valid::error('email', '회원님이 이미 사용중인 이메일입니다.');
        }
        if ($req['email'] != '') {
            Valid::get(
                array(
                    'input' => 'email',
                    'value' => $req['email'],
                    'check' => array(
                        'defined' => 'email'
                    )
                )
            );

            $sql->query(
                "
                SELECT *
                FROM {$sql->table("member")}
                WHERE mb_email=:col1 AND mb_email!=:col2 AND mb_dregdate IS NULL
                ",
                array(
                    $req['email'],
                    $MB['email']
                )
            );

            if ($sql->getcount() > 0) {
                Valid::error('email', '다른 회원이 사용중인 이메일입니다.');
            }
            $mb_email_chg = $req['email'];
        }

        //이메일이 입력된 경우 인증 메일 발송
        if ($req['email'] != '') {
            $chk_code = md5(date('YmdHis').$req['email']);
            $chk_url = PH_DOMAIN.'/sign/emailchk?chk_code='.$chk_code;

            $mail->set(
                array(
                    'tpl' => 'signup',
                    'to' => array(
                        [
                            'email' => $req['email'],
                            'name' => $req['name']
                        ]
                    ),
                    'subject' => $req['name'].'님, '.$CONF['title'].' 이메일 변경 인증을 해주세요.',
                    'chk_url' => '<a href="'.$chk_url.'" target="_blank">[이메일 인증하기]</a>'
                )
            );
            $mail->send();

            $sql->query(
                "
                INSERT INTO {$sql->table("mbchk")}
                (mb_idx,chk_code,chk_chk,chk_mode,chk_regdate)
                VALUES
                (:col1,:col2,'N','chg',now())
                ",
                array(
                    MB_IDX,
                    $chk_code
                )
            );
        }

        //이메일 변경 취소
        if (!$req['email'] && $req['email_chg_cc'] == 'checked') {
            $mb_email_chg = '';
        }

        //update
        if ($req['pwd'] != '') {
            $sql->query(
                "
                UPDATE {$sql->table("member")}
                SET mb_pwd=password(:col1),mb_name=:col2,mb_gender=:col3,mb_phone=:col4,mb_telephone=:col5,mb_email_chg=:col6
                WHERE mb_idx=:col7 AND mb_dregdate IS NULL
                ",
                array(
                    $req['pwd'],
                    $req['name'],
                    $req['gender'],
                    $req['phone'],
                    $req['telephone'],
                    $mb_email_chg,
                    MB_IDX
                )
            );

        } else {
            $sql->query(
                "
                UPDATE {$sql->table("member")}
                SET mb_pwd=:col1,mb_name=:col2,mb_gender=:col3,mb_phone=:col4,mb_telephone=:col5,mb_email_chg=:col6
                WHERE mb_idx=:col7 AND mb_dregdate IS NULL
                ",
                array(
                    $MB['pwd'],
                    $req['name'],
                    $req['gender'],
                    $req['phone'],
                    $req['telephone'],
                    $mb_email_chg,
                    MB_IDX
                )
            );
        }

        //return
        Valid::set(
            array(
                'return' => 'alert->reload',
                'msg' => '성공적으로 변경 되었습니다.'
            )
        );
        Valid::turn();
    }

    ///
    // 회원 탈퇴
    ///
    private function get_lv()
    {
        global $MB;

        $sql = new Pdosql();

        if ($MB['adm'] == 'Y') {
            Valid::error('', '최고 관리자는 탈퇴할 수 없습니다.');
        }

        //delete
        $sql->query(
            "
            UPDATE {$sql->table("member")}
            SET mb_dregdate=now()
            WHERE mb_idx=:col1 AND mb_dregdate IS NULL
            ",
            array(
                MB_IDX
            )
        );

        //로그인 세션 삭제
        Session::drop_sess();

        //return
        Valid::set(
            array(
                'return' => 'alert->location',
                'msg' => '탈퇴가 완료 되었습니다. 그동안 이용해 주셔서 감사합니다.',
                'location' => PH_DOMAIN,
            )
        );
        Valid::turn();
    }
}

/***
Point list
***/
class point extends \Controller\Make_Controller {

    public function init()
    {
        $this->layout()->head();
        $this->layout()->view(PH_THEME_PATH.'/html/member/point.tpl.php');
        $this->layout()->foot();
    }

    public function make()
    {
        global $MB;

        $sql = new Pdosql();
        $paging = new Paging();

        Func::getlogin(SET_NOAUTH_MSG);

        $paging->setlimit(SET_LIST_LIMIT);

        $sql->query(
            $paging->query(
                "
                SELECT *
                FROM {$sql->table("mbpoint")}
                WHERE mb_idx=:col1
                ORDER BY regdate DESC
                ",
                array(
                    MB_IDX
                )
            )
        );
        $print_arr = array();

        if ($sql->getcount() > 0) {
            do {
                $arr = $sql->fetchs();

                $arr['no'] = $paging->getnum();
                $arr['regdate'] = Func::datetime($arr['regdate']);
                $arr['p_in'] = Func::number($arr['p_in']);
                $arr['p_out'] = Func::number($arr['p_out']);

                $print_arr[] = $arr;

            } while($sql->nextRec());
        }

        $this->set('print_arr', $print_arr);
        $this->set('pagingprint', $paging->pagingprint(''));
        $this->set('total_point', Func::number($MB['point']));
    }

}

/***
Warning
***/
class Warning extends \Controller\Make_Controller {

    public function init()
    {
        $this->common()->head();
        $this->layout()->view(PH_THEME_PATH.'/html/member/warning.tpl.php');
        $this->common()->foot();
    }

    public function make()
    {
        global $MB,$ip_qry;

        $sql = new Pdosql();

        Blocked::get_qry();

        $sql->query(
            "
            SELECT *
            FROM {$sql->table("blockmb")}
            WHERE (ip=:col1 OR ip=:col2 OR ip=:col3 OR ip=:col4) OR (mb_idx=:col5 AND mb_id=:col6)
            ",
            array(
                $ip_qry[0],
                $ip_qry[1],
                $ip_qry[2],
                $ip_qry[3],
                $MB['idx'],
                $MB['id']
            )
        );

        if ($sql->getcount() < 1) {
            Func::err_location('차단 내역이 없습니다.', PH_DOMAIN);
        }

        $msg = $sql->fetch('memo');

        $this->set('msg', $msg);
    }

}
