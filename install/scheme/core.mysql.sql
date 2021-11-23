<?php
$SCHEME_CORE = "

CREATE TABLE IF NOT EXISTS {$req['pfx']}banner (
    idx int(11) NOT NULL auto_increment,
    bn_key varchar(255) BINARY NOT NULL,
    pc_img text,
    mo_img text,
    title varchar(255) NOT NULL,
    link text NOT NULL,
    link_target varchar(255) default NULL,
    hit int(11) NOT NULL default '0',
    zindex int(11) NOT NULL default '1',
    regdate datetime default NULL,
    PRIMARY KEY  (idx)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO {$req['pfx']}banner (idx, bn_key, pc_img, mo_img, title, link, link_target, hit, zindex, regdate) VALUES
(1, 'test_banner', '', '', 'test banner', 'https://www.zigger.net', '_self', 0, 1, now());

CREATE TABLE IF NOT EXISTS {$req['pfx']}blockmb (
    idx int(11) NOT NULL auto_increment,
    ip varchar(255) default NULL,
    mb_idx int(11) default NULL,
    mb_id varchar(255) default NULL,
    memo text NOT NULL,
    regdate datetime default NULL,
    PRIMARY KEY  (idx)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS {$req['pfx']}mailtpl (
    idx int(11) NOT NULL auto_increment,
    type varchar(255) BINARY default NULL,
    title varchar(255) default NULL,
    html text,
    system char(1) NOT NULL default 'N',
    regdate datetime default NULL,
    PRIMARY KEY  (idx)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

INSERT INTO {$req['pfx']}mailtpl (idx, type, title, html, system, regdate) VALUES
(1, 'signup', '회원가입 인증 자동 발송 메일', '<table align=\"center\" style=\"width:740px;border-collapse: collapse;background:#fff;\">\r\n	<tbody>\r\n<tr>\r\n<td style=\"padding:20px 40px;border-bottom:2px solid #ddd;font-size:16px;line-height:30px;color:#666666;font-family:Malgun Gothic;letter-spacing:-1px;\"><strong>{{site_title}}</strong></td>\r\n</tr>\r\n<tr>\r\n<td style=\"padding:40px 40px 20px 40px;font-size: 36px;color: #554bbd;letter-spacing:-3px;font-family:Malgun Gothic;\">{{site_title}} 이메일 인증 안내</td>\r\n</tr>\r\n<tr>\r\n<td style=\"padding:0 40px 40px 40px;font-size:16px;line-height:30px;color:#666666;font-family:Malgun Gothic;letter-spacing:-1px;border-bottom:1px solid #ddd;\">\r\n<p>안녕하세요?<br />\r\n{{name}} 님.<br />\r\n회원가입 완료를 위해&nbsp;{{site_title}} 이메일 인증 부탁 드립니다.<br />\r\n<br />\r\n아래 링크를 클릭 하시면 이메일이 인증 완료 됩니다.<br />\r\n감사합니다.<br />\r\n<br />\r\n<span style=\"color:#554bbd;\">{{check_url}}</span></p>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td style=\"border:1px solid #ddd;border-width:1px 0 1px 0;padding:20px 15px;text-align:center;font-size:12px;line-height:20px;font-family:dotum;color:#999;\">ⓒ {{site_title}} All Rights Reserved.</td>\r\n</tr>\r\n	</tbody>\r\n</table>\r\n', 'Y', now()),
(2, 'forgot', '로그인 정보 자동 발송 메일', '<table align=\"center\" style=\"width:740px;border-collapse: collapse;background:#fff;\">\r\n	<tbody>\r\n<tr>\r\n<td style=\"padding:20px 40px;border-bottom:2px solid #ddd;font-size:16px;line-height:30px;color:#666666;font-family:Malgun Gothic;letter-spacing:-1px;\"><strong>{{site_title}}</strong></td>\r\n</tr>\r\n<tr>\r\n<td style=\"padding:40px 40px 20px 40px;font-size: 36px;color: #554bbd;letter-spacing:-3px;font-family:Malgun Gothic;\">{{site_title}} 로그인 정보 안내</td>\r\n</tr>\r\n<tr>\r\n<td style=\"padding:0 40px 40px 40px;font-size:16px;line-height:30px;color:#666666;font-family:Malgun Gothic;letter-spacing:-1px;border-bottom:1px solid #ddd;\">\r\n<p>안녕하세요?<br />\r\n{{name}} 님.<br />\r\n{{site_title}} 회원 로그인 정보를 보내 드립니다.<br />\r\n<br />\r\nUser ID : {{id}}<br />\r\nPassword : {{password}}</p>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td style=\"border:1px solid #ddd;border-width:1px 0 1px 0;padding:20px 15px;text-align:center;font-size:12px;line-height:20px;font-family:dotum;color:#999;\">ⓒ {{site_title}} All Rights Reserved.</td>\r\n</tr>\r\n	</tbody>\r\n</table>\r\n', 'Y', now()),
(3, 'default', '기본 템플릿', '<table align=\"center\" style=\"width:740px;border-collapse: collapse;background:#fff;\">\r\n	<tbody>\r\n<tr>\r\n<td style=\"padding:20px 40px;border-bottom:2px solid #ddd;font-size:16px;line-height:30px;color:#666666;font-family:Malgun Gothic;letter-spacing:-1px;\"><strong>{{site_title}}</strong></td>\r\n</tr>\r\n<tr>\r\n<td style=\"padding:40px 40px 20px 40px;font-size: 36px;color: #554bbd;letter-spacing:-3px;font-family:Malgun Gothic;\">{{site_title}} 안내 메일</td>\r\n</tr>\r\n<tr>\r\n<td style=\"padding:0 40px 40px 40px;font-size:16px;line-height:30px;color:#666666;font-family:Malgun Gothic;letter-spacing:-1px;border-bottom:1px solid #ddd;\">{{article}}</td>\r\n</tr>\r\n<tr>\r\n<td style=\"border:1px solid #ddd;border-width:1px 0 1px 0;padding:20px 15px;text-align:center;font-size:12px;line-height:20px;font-family:dotum;color:#999;\">ⓒ {{site_title}} All Rights Reserved.</td>\r\n</tr>\r\n	</tbody>\r\n</table>\r\n', 'Y', now());

CREATE TABLE IF NOT EXISTS {$req['pfx']}mbchk (
    chk_idx int(11) NOT NULL auto_increment,
    mb_idx int(11) NOT NULL,
    chk_code text,
    chk_mode varchar(255) NOT NULL default 'chk',
    chk_chk char(1) default 'N',
    chk_regdate datetime default NULL,
    chk_dregdate datetime default NULL,
    PRIMARY KEY  (chk_idx)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS {$req['pfx']}mbpoint (
    idx int(11) NOT NULL auto_increment,
    mb_idx int(11) NOT NULL,
    p_in int(11) default NULL,
    p_out int(11) default NULL,
    memo text,
    regdate datetime default NULL,
    PRIMARY KEY  (idx)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS {$req['pfx']}member (
    mb_adm char(1) default 'N',
    mb_idx int(11) NOT NULL auto_increment,
    mb_id varchar(255) BINARY NOT NULL,
    mb_email varchar(255) NOT NULL,
    mb_pwd text NOT NULL,
    mb_name varchar(255) default NULL,
    mb_level int(11) default '9',
    mb_gender char(1) default 'M',
    mb_phone varchar(255) default NULL,
    mb_telephone varchar(255) default NULL,
    mb_lately datetime default NULL,
    mb_lately_ip varchar(255) default NULL,
    mb_point int(11) default '0',
    mb_email_chk char(1) default 'N',
    mb_email_chg varchar(255) NOT NULL,
    mb_sns_ka text default NULL,
    mb_sns_ka_token text default NULL,
    mb_sns_nv text default NULL,
    mb_sns_nv_token text default NULL,
    mb_app_key text default NULL,
    mb_regdate datetime default NULL,
    mb_dregdate datetime default NULL,
    mb_1 text,
    mb_2 text,
    mb_3 text,
    mb_4 text,
    mb_5 text,
    mb_6 text,
    mb_7 text,
    mb_8 text,
    mb_9 text,
    mb_10 text,
    mb_exp text NOT NULL,
    PRIMARY KEY  (mb_idx)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS {$req['pfx']}mng_feeds (
    idx int(11) NOT NULL auto_increment,
    msg_from text collate utf8_bin,
    href text collate utf8_bin,
    memo text collate utf8_bin,
    regdate datetime default NULL,
    chked char(1) collate utf8_bin default 'N',
    PRIMARY KEY  (idx)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS {$req['pfx']}popup (
    idx int(11) NOT NULL auto_increment,
    id varchar(255) BINARY NOT NULL,
    title varchar(255) NOT NULL,
    link text,
    link_target varchar(255) default NULL,
    width int(11) default NULL,
    height int(11) default NULL,
    pos_left int(11) default NULL,
    pos_top int(11) default NULL,
    level_from int(11) default NULL,
    level_to int(11) default NULL,
    show_from datetime default NULL,
    show_to datetime default NULL,
    html text,
    mo_html text,
    regdate datetime default NULL,
    PRIMARY KEY  (idx)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS {$req['pfx']}sentmail (
    idx int(11) NOT NULL auto_increment,
    template varchar(255) BINARY default NULL,
    to_mb varchar(255) default NULL,
    level_from int(11) default NULL,
    level_to int(11) default NULL,
    subject text,
    html text,
    regdate datetime default NULL,
    PRIMARY KEY  (idx)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS {$req['pfx']}session (
    idx int(11) NOT NULL auto_increment,
    sesskey text NOT NULL,
    expiry int(11) NOT NULL,
    value text,
    mb_idx int(11) default '0',
    ip varchar(255) default NULL,
    regdate datetime default NULL,
    PRIMARY KEY  (idx)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS {$req['pfx']}siteconfig (
    st_idx int(11) NOT NULL,
    st_title varchar(255) default NULL,
    st_domain text,
    st_description text,
    st_use_mobile char(1) default 'Y',
    st_use_emailchk char(1) NOT NULL default 'Y',
    st_use_recaptcha char(1) NOT NULL default 'N',
    st_recaptcha_key1 text,
    st_recaptcha_key2 text,
    st_email varchar(255) default NULL,
    st_tel varchar(255) default NULL,
    st_favicon text,
    st_logo text,
    st_mb_division text,
    st_og_type varchar(255) NOT NULL default 'website',
    st_og_title text,
    st_og_description text,
    st_og_image text,
    st_og_url text,
    st_naver_verific text,
    st_google_verific text,
    st_theme varchar(255) default 'zigger-default',
    st_use_smtp char(1) NOT NULL default 'N',
    st_smtp_server varchar(255) default 'ssl\\:\\/\\/',
    st_smtp_port char(10) default NULL,
    st_smtp_id text,
    st_smtp_pwd text,
    st_script text,
    st_meta text,
    st_privacy text,
    st_policy text,
    st_use_sns_ka char(1) default 'N',
    st_sns_ka_key1 text default NULL,
    st_sns_ka_key2 text default NULL,
    st_use_sns_nv char(1) default 'N',
    st_sns_nv_key1 text default NULL,
    st_sns_nv_key2 text default NULL,
    st_1 text,
    st_2 text,
    st_3 text,
    st_4 text,
    st_5 text,
    st_6 text,
    st_7 text,
    st_8 text,
    st_9 text,
    st_10 text,
    st_exp text NOT NULL,
    PRIMARY KEY  (st_idx)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO {$req['pfx']}siteconfig (st_idx, st_title, st_domain, st_description, st_use_mobile, st_use_emailchk, st_email, st_tel, st_favicon, st_logo, st_mb_division, st_og_type, st_og_title, st_og_description, st_og_image, st_og_url, st_naver_verific, st_google_verific, st_theme, st_use_smtp, st_smtp_server, st_smtp_port, st_smtp_id, st_smtp_pwd, st_script, st_meta, st_privacy, st_policy, st_use_sns_ka, st_sns_ka_key1, st_sns_ka_key2, st_use_sns_nv, st_sns_nv_key1, st_sns_nv_key2, st_1, st_2, st_3, st_4, st_5, st_6, st_7, st_8, st_9, st_10, st_exp) VALUES
(0, 'Zigger Website', '".$protocol.$_SERVER['HTTP_HOST'].$realdir."', 'Zigger Website Description', 'Y', 'Y', '', '', '', '', '최고관리자|관리자|게시판관리자|정회원|정회원|정회원|정회원|정회원|일반회원|비회원', 'website', 'Zigger Website', 'Zigger Website Description', '', '".$protocol.$_SERVER['HTTP_HOST'].$realdir."', '', '', 'zigger-default', 'N', '', '', '', '', '', '', '개인정보 처리방침 ( Manage에서 작성됨 )', '서비스 이용약관 ( Manage에서 작성됨 )', 'N', NULL, NULL, 'N', NULL, NULL, '', '', '', '', '', '', '', '', '', '', '|||||||||');

CREATE TABLE IF NOT EXISTS {$req['pfx']}sitemap (
    idx int(11) NOT NULL,
    caidx text collate utf8_bin,
    title varchar(255) collate utf8_bin default NULL,
    href text collate utf8_bin,
    visible char(1) collate utf8_bin NOT NULL default 'Y',
    children int(11) NOT NULL default '0',
    PRIMARY KEY  (idx)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO {$req['pfx']}sitemap (idx, caidx, title, href, visible, children) VALUES
(1, '0001', 'About Zigger', 'sub/view/contents', 'Y', 0),
(2, '0002', 'Board', 'sub/board/news', 'Y', 2),
(3, '00020001', 'News', 'sub/board/news', 'Y', 0),
(4, '00020002', 'Freeboard', 'sub/board/free', 'Y', 0),
(5, '0003', 'Contact us', 'sub/view/contactus', 'Y', 0);

CREATE TABLE IF NOT EXISTS {$req['pfx']}visitcount (
    idx int(11) NOT NULL auto_increment,
    mb_idx int(11) default NULL,
    mb_id varchar(255) default NULL,
    ip varchar(255) default NULL,
    device varchar(255) default NULL,
    browser varchar(255) default NULL,
    regdate datetime default NULL,
    PRIMARY KEY  (idx)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

";
