<div id="sub-tit">
    <h2>생성된 게시판</h2>
    <em><i class="fa fa-exclamation-circle"></i>현재까지 생성된 게시판 관리</em>
</div>

<!-- sorting -->
<div id="list-sort">
    <ul>
        <li><a href="result"><em>전체 게시판</em><p><?php echo $board_total; ?></p></a></li>
    </ul>
</div>

<!-- article -->
<article>

    <form id="list-sch" action="" method="get">
        <?php echo $manage->print_hidden_inp(); ?>

        <select name="where">
            <option value="id" <?php echo $manage->sch_where("id"); ?>>id</option>
            <option value="title" <?php echo $manage->sch_where("title"); ?>>제목</option>
        </select>
        <input type="text" name="keyword" class="keyword" value="<?php echo $keyword; ?>" placeholder="검색어를 입력하세요." />
        <button type="submit" class="btn1 small sbm"><i class="fa fa-search"></i>검색</button>
    </form>

    <table class="table1 list">
        <colgroup>
            <col style="width: 50px;" />
            <col style="width: auto;" />
            <col style="width: auto;" />
            <col style="width: auto;" />
            <col style="width: auto;" />
            <col style="width: auto;" />
            <col style="width: auto;" />
            <col style="width: auto;" />
            <col style="width: 200px;" />
            <col style="width: 100px;" />
        </colgroup>
        <thead>
            <tr>
                <th>No.</th>
                <th><a href="<?php echo $manage->orderlink("id"); ?>">id</a></th>
                <th><a href="<?php echo $manage->orderlink("title"); ?>">게시판 타이틀</a></th>
                <th>게시글 수</th>
                <th><a href="<?php echo $manage->orderlink("list_level"); ?>">접근 권한</a></th>
                <th><a href="<?php echo $manage->orderlink("read_level"); ?>">읽기 권한</a></th>
                <th><a href="<?php echo $manage->orderlink("write_level"); ?>">작성 권한</a></th>
                <th><a href="<?php echo $manage->orderlink("regdate"); ?>">생성일</a></th>
                <th>복제 생성</th>
                <th>관리</th>
            </tr>
        </thead>
        <tbody id="boardList">
            <?php foreach ($print_arr as $list) { ?>
            <tr>
                <td class="no tac"><?php echo $list['no']; ?></td>
                <td class="tac"><?php echo $list['id']; ?></td>
                <td class="tac"><strong><?php echo $list['title']; ?></strong></td>
                <td class="tac"><?php echo $list[0]['data_total']; ?>건</td>
                <td class="tac"><?php echo $list['list_level']; ?></td>
                <td class="tac"><?php echo $list['read_level']; ?></td>
                <td class="tac"><?php echo $list['write_level']; ?></td>
                <td class="tac"><?php echo $list['regdate']; ?></td>
                <td class="tac">
                    <form <?php $this->form($list['idx']); ; ?>>
                        <input type="text" name="clone_id" class="inp w100" placeholder="생성할 id" title="생성할 id" />
                        <input type="hidden" name="board_id" value="<?php echo $list['id']; ?>" />
                        <button type="submit" class="btn1 small clone-btn" data-form-before-confirm="게시판 복제를 수행 하시겠습니까?">복제</button>
                    </form>
                </td>
                <td class="tac">
                    <a href="./modify<?php echo $manage->lnk_def_param('&idx='.$list['idx']); ?>" class="btn1 small">관리</a>
                </td>
            </tr>
            <?php } ?>
        </tbody>
    </table>

    <!-- no data -->
    <?php if (!$print_arr) { ?>
    <p id="list-nodata"><?=SET_NODATA_MSG; ?></p>
    <?php } ?>

    <!-- paging -->
    <div id="list-paging">
        <?php echo $pagingprint; ?>
    </div>

    <div class="btn-wrap">
        <div class="center">
            <a href="./regist" class="btn1"><i class="fa fa-plus"></i>신규 게시판 생성</a>
        </div>
    </div>

</article>
