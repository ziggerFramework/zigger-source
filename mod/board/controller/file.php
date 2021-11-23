<?php
namespace Module\Board;

use Corelib\Method;
use Corelib\Func;
use Make\Database\Pdosql;
use Make\Library\Uploader;

class Down extends \Controller\Make_Controller {

    public function init()
    {
        global $board_id;

        $sql = new Pdosql();

        $req = Method::request('get', 'board_id, file');

        $board_id = $req['board_id'];

        if (!$board_id) {
            Func::err('board_id 가 누락되었습니다.');
        }

        //게시글의 첨부파일 정보 불러옴
        $sql->query(
            "
            SELECT *
            FROM {$sql->table("mod:board_data_".$board_id)}
            WHERE file1=:col1 OR file2=:col1
            ",
            array(
                $req['file']
            )
        );

        //첨부파일이 확인되지 않는 경우
        if ($sql->getcount() < 1) {
            Func::err('첨부파일이 확인되지 않습니다.');
        }

        //첨부파일 정보
        $file = urldecode($req['file']);
        $fileinfo = array();
        $fileinfo['path'] = MOD_BOARD_DATA_PATH.'/'.$board_id.'/'.$req['file'];
        $fileinfo['size'] = filesize($fileinfo['path']);
        $fileinfo['parts'] = pathinfo($fileinfo['path']);
        $fileinfo['name'] = $fileinfo['parts']['basename'];

        $qry_file = array();
        $qry_file_cnt = array();

        for ($i = 1; $i <= 2; $i++){
            $isfile = $sql->fetch('file'.$i);

            if ($isfile == $file) {
                $qry_file_cnt[$i] = 1;

            } else {
                $qry_file_cnt[$i] = 0;
            }
        }

        //파일 다운로드 횟수 증가
        $sql->query(
            "
            UPDATE {$sql->table("mod:board_data_".$board_id)}
            SET file1_cnt=file1_cnt+:col1,file2_cnt=file2_cnt+:col2
            WHERE file1=:col3 OR file2=:col3
            ",
            array(
                $qry_file_cnt[1], $qry_file_cnt[2], $file
            )
        );

        //파일 다운로드 스트림
        Header('Content-Type:application/octet-stream');
        Header('Content-Disposition:attachment; filename='.$fileinfo['name']);
        Header('Content-Transfer-Encoding:binary');
        Header('Content-Length:'.(string)$fileinfo['size']);
        Header('Cache-Control:Cache,must-revalidate');
        Header('Pragma:No-Cache');
        Header('Expires:0');
        ob_clean();
        flush();

        readfile($fileinfo['path']);
    }

}
