<?php
$SCHEME_MOD = "

CREATE TABLE IF NOT EXISTS {$req['pfx']}mod_board_config (
    idx int(11) NOT NULL auto_increment,
    id varchar(255) BINARY default NULL,
    theme varchar(255) default 'list',
    title varchar(255) default NULL,
    use_list char(3) default 'Y|N',
    use_secret char(1) default 'Y',
    use_comment char(1) default 'Y',
    use_likes char(1) default 'Y',
    use_reply char(1) default 'Y',
    use_file1 char(1) default 'Y',
    use_file2 char(1) default 'N',
    use_mng_feed char(1) NOT NULL default 'Y',
    use_category char(1) default 'N',
    category text,
    file_limit int(50) default '5242880',
    list_limit varchar(50) default '15|5',
    sbj_limit varchar(255) NOT NULL default '100|50',
    txt_limit varchar(50) default '50|30',
    article_min_len int(11) NOT NULL default '30',
    list_level int(11) default '10',
    write_level int(11) default '9',
    secret_level int(11) default '1',
    comment_level int(11) default '9',
    delete_level int(11) default '9',
    read_level int(11) default '10',
    ctr_level int(11) default '3',
    reply_level int(11) default '9',
    write_point int(11) default '10',
    read_point int(11) default '0',
    top_source text,
    bottom_source text,
    ico_file char(3) default 'Y',
    ico_secret char(3) default 'Y',
    ico_secret_def char(1) default 'N',
    ico_new char(3) default 'Y',
    ico_new_case int(11) default '4320',
    ico_hot char(3) default 'N',
    ico_hot_case varchar(50) default '10|OR|50',
    regdate datetime default NULL,
    conf_1 text,
    conf_2 text,
    conf_3 text,
    conf_4 text,
    conf_5 text,
    conf_6 text,
    conf_7 text,
    conf_8 text,
    conf_9 text,
    conf_10 text,
    conf_exp text,
    PRIMARY KEY  (idx)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS {$req['pfx']}mod_board_data_freeboard (
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
    PRIMARY KEY  (idx)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS {$req['pfx']}mod_board_cmt_freeboard (
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
    PRIMARY KEY  (idx)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS {$req['pfx']}mod_board_data_news (
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
    PRIMARY KEY  (idx)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS {$req['pfx']}mod_board_cmt_news (
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
    PRIMARY KEY  (idx)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

INSERT INTO {$req['pfx']}mod_board_config (idx, id, theme, title, use_list, use_secret, use_comment, use_likes, use_reply, use_file1, use_file2, use_mng_feed, use_category, category, file_limit, list_limit, sbj_limit, txt_limit, article_min_len, list_level, write_level, secret_level, comment_level, delete_level, read_level, ctr_level, reply_level, write_point, read_point, top_source, bottom_source, ico_file, ico_secret, ico_secret_def, ico_new, ico_new_case, ico_hot, ico_hot_case, regdate, conf_1, conf_2, conf_3, conf_4, conf_5, conf_6, conf_7, conf_8, conf_9, conf_10, conf_exp) VALUES
(2, 'freeboard', 'gallery', 'Freeboard', 'Y|Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'N', '', 5242880, '15|10', '50|30', '150|100', 30, 10, 10, 1, 10, 10, 10, 3, 10, 10, 0, '', '', 'Y', 'Y', 'N', 'Y', 60, 'Y', '2|AND|1', now(), '', '', '', '', '', '', '', '', '', '', '|||||||||'),
(1, 'news', 'basic', 'News', 'Y|Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'N', '', 5242880, '15|10', '50|30', '150|100', 30, 10, 10, 1, 10, 10, 10, 3, 10, 10, 0, '', '', 'Y', 'Y', 'N', 'Y', 60, 'Y', '2|AND|1', now(), '', '', '', '', '', '', '', '', '', '', '|||||||||');

CREATE TABLE IF NOT EXISTS {$req['pfx']}mod_board_like (
    idx int(11) NOT NULL auto_increment,
    id varchar(255) default NULL,
    data_idx int(11) default NULL,
    mb_idx int(11) default NULL,
    likes int(11) default '0',
    unlikes int(11) default '0',
    regdate datetime default NULL,
    PRIMARY KEY  (idx)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS {$req['pfx']}mod_contactform (
    idx int(11) NOT NULL auto_increment,
    rep_idx int(11) default '0',
    mb_idx int(11) default '0',
    article text,
    name varchar(255) default NULL,
    email text,
    phone varchar(255) default NULL,
    regdate datetime default NULL,
    contact_1 text,
    contact_2 text,
    contact_3 text,
    contact_4 text,
    contact_5 text,
    contact_6 text,
    contact_7 text,
    contact_8 text,
    contact_9 text,
    contact_10 text,
    contact_exp text,
    PRIMARY KEY  (idx)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS {$req['pfx']}mod_contents (
    idx int(11) NOT NULL auto_increment,
    data_key varchar(255) NOT NULL,
    title varchar(255) default NULL,
    html text,
    mo_html text,
    use_mo_html char(1) NOT NULL default 'N',
    regdate datetime default NULL,
    PRIMARY KEY  (idx)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

INSERT INTO {$req['pfx']}mod_contents (idx, data_key, title, html, mo_html, use_mo_html, regdate) VALUES
(1, 'sample', 'About Zigger', '<p><span style=\"font-size:38px;letter-spacing:-2px;color:#333\"><img src=\"/theme/zigger-default/layout/images/logo.png\" />&nbsp;를 선택해&nbsp;주셔서 감사합니다!</span></p>\r\n\r\n<div style=\"margin:30px 0;border-radius:10px;border:1px solid #ddd;padding:45px;background:#f7f7f7;line-height:30px;letter-spacing:-1px;\"><span style=\"color:#666666;letter-spacing:-1px;\"><span style=\"font-size:15px;letter-spacing:-1px;\">zigger는 MVC PHP로 개발된 CMS Framework 입니다.<br />\r\nMVC 로직을 통해 빠르게 반응형 웹사이트를 구축할 수 있으며,<br />\r\nzigger 공식 사이트에서 배포하는 다양한 모듈을 Core에 추가하여 원하는 기능을 쉽고 간편하게 구축할 수 있습니다.<br />\r\n<br />\r\n공식 웹사이트(https://www.zigger.net)를 통해 지속적인 업데이트를 지원 받으실&nbsp;수 있으며,<br />\r\n이용 가이드 및 다양한 소식을 빠르게 확인할 수 있습니다.<br />\r\n<br />\r\nzigger는 GNU 라이선스가 적용되어 있어 영리 및 비영리 웹사이트 구축시 자유롭게 사용할 수 있습니다.<br />\r\n다만, 무단 수정 및 배포는 금지되어 있으므로, zigger가 설치된 ROOT 리렉토리 내 LICENSE 파일을 확인 하시길 바랍니다.<br />\r\n<br />\r\n공식 웹사이트는 아래 버튼을 클릭하여 접속 가능합니다.<br />\r\nzigger를 설치해 주셔서 감사합니다.</span></span><br />\r\n<br />\r\n<strong><span style=\"font-size:12px;letter-spacing:-1px;\">이 웹페이지는 콘텐츠 모듈 ( &#39;Manage &gt; 모듈 &gt; 콘텐츠&#39; ) 로 제작된 페이지입니다. Manage에서 본 샘플 웹페이지를 확인해 보세요.</span></strong></div>\r\n\r\n<p><a href=\"https://www.zigger.net/\" style=\"display:block;margin:0 auto;line-height:50px;border-radius:4px;text-align:center;width:260px;padding:0 20px;background:#564bbe;font-size:16px;color:#fff;letter-spacing:-1px;\" target=\"_blank\">zigger 공식 사이트 이동</a></p>\r\n\r\n<p>&nbsp;</p>\r\n', '<p><span style=\"letter-spacing: -1px; font-size:22px;color: rgb(51, 51, 51);\"><img src=\"/theme/zigger-default/layout/images/logo.png\" style=\"width: auto;height:16px;\" />&nbsp;를 선택해&nbsp;주셔서 감사합니다!</span></p>\r\n\r\n<div style=\"margin:20px 0;border-radius:10px;border:1px solid #ddd;padding:15px;background:#f7f7f7;line-height:20px;letter-spacing:-1px;\"><span style=\"font-size:12px;\"><span style=\"color:#666666;letter-spacing:-1px;\"><span style=\"letter-spacing: -1px;\">zigger는 MVC PHP로 개발된 CMS Framework 입니다.<br />\r\nMVC 로직을 통해 빠르게 반응형 웹사이트를 구축할 수 있으며,<br />\r\nzigger 공식 사이트에서 배포하는 다양한 모듈을 Core에 추가하여 원하는 기능을 쉽고 간편하게 구축할 수 있습니다.<br />\r\n<br />\r\n공식 웹사이트(https://www.zigger.net)를 통해 지속적인 업데이트를 지원 받으실&nbsp;수 있으며,<br />\r\n이용 가이드 및 다양한 소식을 빠르게 확인할 수 있습니다.<br />\r\n<br />\r\nzigger는 GNU 라이선스가 적용되어 있어 영리 및 비영리 웹사이트 구축시 자유롭게 사용할 수 있습니다.<br />\r\n다만, 무단 수정 및 배포는 금지되어 있으므로, zigger가 설치된 ROOT 리렉토리 내 LICENSE 파일을 확인 하시길 바랍니다.<br />\r\n<br />\r\n공식 웹사이트는 아래 버튼을 클릭하여 접속 가능합니다.<br />\r\nzigger를 설치해 주셔서 감사합니다.<br />\r\n<br />\r\n<strong><span style=\"font-size:12px;letter-spacing:-1px;\">이 웹페이지는 콘텐츠 모듈 ( &#39;Manage &gt; 모듈 &gt; 콘텐츠&#39; ) 로 제작된 페이지입니다. Manage에서 본 샘플 웹페이지를 확인해 보세요.</span></strong></span></span></span></div>\r\n\r\n<p><a href=\"https://www.zigger.net/\" style=\"display:block;margin:0 auto;line-height:40px;border-radius:4px;text-align:center;width:160px;padding:0 10px;background:#564bbe;font-size:13px;color:#fff;letter-spacing:-1px;\" target=\"_blank\">zigger 공식 사이트 이동</a></p>\r\n\r\n<p>&nbsp;</p>\r\n', 'Y', now());

CREATE TABLE IF NOT EXISTS {$req['pfx']}mod_message (
    idx int(11) NOT NULL auto_increment,
    from_mb_idx int(11) NOT NULL,
    to_mb_idx int(11) NOT NULL,
    parent_idx int(11) default NULL,
    article text,
    regdate datetime NOT NULL,
    chked datetime default NULL,
    PRIMARY KEY (idx)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS {$req['pfx']}mod_alarm (
    idx int(11) NOT NULL auto_increment,
    msg_from text,
    from_mb_idx int(11) NOT NULL,
    to_mb_idx int(11) default NULL,
    href text,
    memo text,
    regdate datetime default NULL,
    chked char(1) DEFAULT 'N',
    PRIMARY KEY (idx)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

";
