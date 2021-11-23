<?php
use Corelib\Session;
use Corelib\SessionHandler;
use Corelib\Blocked;
use Make\Database\Pdosql;

$varsql = new Pdosql();

//Modules
$mpath = PH_MOD_PATH;
$mopen = opendir($mpath);
$midx = 0;

while ($dir = readdir($mopen)) {
    if ($dir != '.' && $dir != '..') {
        $MODULE[$midx] = $dir;
        $midx++;
    }
}

//Themes
$tpath = PH_PATH.'/theme/';
$topen = opendir($tpath);
$tidx = 0;

while ($dir = readdir($topen)) {
    if ($dir != '.' && $dir != '..') {
        $THEME[$tidx] = $dir;
        $tidx++;
    }
}

//Default information
$varsql->query(
    "
    SELECT *
    FROM {$varsql->table("siteconfig")}
    LIMIT 1
    ", ''
);
$st_arr = $varsql->fetchs();

foreach ($st_arr as $key=>$value) {
    $key = str_replace('st_', '', $key);
    $CONF[$key] = $value;
}

for ($i=1; $i <= 10; $i++) {
    $CONF['st_'.$i] = $CONF[$i];
    unset($CONF[$i]);
}

$varsql->specialchars = 0;
$varsql->nl2br = 0;
$CONF['script'] = $varsql->fetch('st_script');
$CONF['meta'] = $varsql->fetch('st_meta');

//Theme constants
define('PH_THEME', $CONF['theme']); //Theme 경로
define('PH_THEME_DIR', PH_DIR.'/theme/'.$CONF['theme']); //Theme 경로
define('PH_THEME_PATH', PH_PATH.'/theme/'.$CONF['theme']); //Theme PHP 경로

//회원이라면, 회원의 기본 정보 가져옴
define('IS_MEMBER', Session::is_sess('MB_IDX'));

if (IS_MEMBER) {
    define('MB_IDX',Session::sess('MB_IDX'));

} else {
    define('MB_IDX',NULL);
}

$MB = array();

if (IS_MEMBER) {
    $varsql->query(
        "
        SELECT *
        FROM {$varsql->table("member")}
        WHERE mb_idx=:col1
        ",
        array(
            MB_IDX
        )
    );

    $mb_arr = $varsql->fetchs();

    foreach ($mb_arr as $key => $value) {
        $key = str_replace('mb_', '', $key);
        $MB[$key] = $value;
    }

    for ($i=1; $i <= 10; $i++) {
        $MB['mb_'.$i] = $MB[$i];
        unset($MB[$i]);
    }

} else {
    $MB = array(
        'level' => 10,
        'adm' => null,
        'idx' => 0,
        'id' => null,
        'pwd' => null,
        'email' => null,
        'name' => null,
        'phone' => null,
        'telephone' => null
    );
}

//회원 레벨별 명칭 배열화
$MB['type'] = array();
$vars = explode('|', $CONF['mb_division']);
for ($i=1; $i <= 10; $i++) {
    $MB['type'][$i] = $vars[$i - 1];
}
