<?php if($bdList['cfg']['bdId'] == 'qa') { ?>

<div class="page-header js-affix">
    <h3>상담 리스트 관리
        <small>상담게시물을 수정하고 관리합니다.</small>
    </h3>
    <?php ?>
    <?php if ($board->canWrite() == 'y') { ?>
        <input type="button" id="btnWrite" class="btn btn-red" onclick="btnWrite('<?= $req['bdId'] ?>');" value="등록">
    <?php } ?>
</div>
<div class="table-title gd-help-manual">상담글 관리</div>
<form name="frmSearch" id="frmSearch" action="article_list.php" class="frmSearch js-form-enter-submit">
    <input type="hidden" id="boardListSort" name="boardListSort" value="<?=$boardListSort?>"/>
    <input type="hidden" id="bdId" name="bdId" value="qa"/>
    <input type="hidden" id="isShow" name="isShow" value="<?=$isShow?>"/>
    <input type="hidden" id="listType" name="listType" value="<?=$listType?>"/>
    <div class="search-detail-box">
        <table class="table table-cols">
            <tr>
                <th class="width-md">상담가능시간</th>
                <td class="width-3xl"<?php if ($isShow == 'n') echo 'colspan="3"'; ?>>
                     <select name="callTime" id="callTime" class="form-control width-lg">
                             <option value="">전체보기</option>
                            <?php
                                $callTimes = array('아무때나','09:00-10:00','10:00-11:00','11:00-12:00','13:00-14:00','14:00-15:00','15:00-16:00','16:00-17:00','17:00-18:00');
                                foreach ($callTimes as $callTime) {
                                    ?>
                                    <option
                                        value="<?php echo $callTime ?>" <?php if ($callTime == $req['callTime'])
                                        echo "selected='selected'" ?>> <?php echo $callTime ?></option>
                                    <?php
                            }
                            ?>
                        </select>

                </td>
                <?php if($isShow != 'n') { ?>
                <th class="width-md">상품구매</th>
                <td>
                    <div class="form-inline">
                        <?= gd_isset($bdList['categoryBox'], '-'); ?>
                    </div>
                </td>
                <?php } ?>
            </tr>
            <tr>
                <th><?php if ($isShow == 'n') echo '신고'; ?>상담신청/진행일</th>
                <td colspan="3">
                    <div class="form-inline">
                        <?php if($isShow == 'n') { ?>
                        <input type="hidden" name="searchDateFl" value="reportDt" />
                        <?php } else { ?>
                        <select name="searchDateFl" class="form-control">
                            <option value="regDt" <?php if ($req['searchDateFl'] == 'regDt') echo 'selected' ?>>
                                신청일
                            </option>
                            <option value="modDt" <?php if ($req['searchDateFl'] == 'modDt') echo 'selected' ?>>
                                진행일
                            </option>
                        </select>
                        <?php } ?>

                        <div class="input-group js-datepicker">
                            <input type="text" class="form-control width-md" name="rangDate[]"
                                   value="<?= $req['rangDate'][0]; ?>">
                                    <span class="input-group-addon">
                                        <span class="btn-icon-calendar">
                                        </span>
                                    </span>
                        </div>
                        ~
                        <div class="input-group js-datepicker">
                            <input type="text" class="form-control width-md" name="rangDate[]"
                                   value="<?= $req['rangDate'][1]; ?>">
                                    <span class="input-group-addon">
                                        <span class="btn-icon-calendar">
                                        </span>
                                    </span>
                        </div>
                        <?= gd_search_date(gd_isset($req['searchPeriod'], 6), 'rangDate', false) ?>
                    </div>
                </td>
            </tr>

            <?php if($isShow != 'n') { ?>
            <tr class="js-if-bdGoodsPtFl">
                <?php  if ($bdList['cfg']['bdAnswerStatusFl'] == 'y' || $bdList['cfg']['bdReplyStatusFl'] == 'y') { ?>
                    <?php if($req['bdId'] == 'qa') { ?>
                    <th>처리상태</th>
                    <td class="width-xl">
                        <div class="form-inline">
                            <select name="replyStatus" class="form-control">
                                <option value="">=전체=</option>

                                <?php
                                    $replyStatus = array('전체','상담 신청중','상담 진행중','상담 완료','상담 취소','상담 지연');
                                    for($i = 0;$i<5;$i++){?>
                                    <option value="<?= $i ?>" <?php if ($req['replyStatus'] == $i) echo 'selected' ?>><?= $replyStatus[$i]; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </td>
                    <?php } else { ?>

                    <th>답변상태</th>
                    <td class="width-xl">
                        <div class="form-inline">
                            <select name="replyStatus" class="form-control">
                                <option value="">=전체=</option>
                                <?php foreach ($board::REPLY_STATUS_LIST as $key => $val) { ?>
                                    <option value="<?= $key ?>" <?php if ($req['replyStatus'] == $key) echo 'selected' ?>><?= $val ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </td>
                <?php } ?>

                <?php } ?>

                <?php if ($bdList['cfg']['bdGoodsPtFl'] == 'y') { ?>
                    <th>평점</th>
                    <td>
                        <select name="goodsPt" class="form-control">
                            <option value="">=전체=</option>
                            <?php
                            for ($i = 1; $i < 6; $i++) { ?>
                                <option
                                        value="<?= $i ?>" <?php if ((string)$i == (string)$req['goodsPt']) echo 'selected' ?>><?= $i ?></option>
                            <?php } ?>
                        </select>
                    </td>
                <?php } ?>
            </tr>
            <?php if ($bdList['cfg']['bdEventFl'] == 'y') { ?>
                <tr class="js-if-bdEventFl">
                    <th>이벤트 기간</th>
                    <td colspan="3">
                        <div class="form-inline">
                            <div class="input-group js-datepicker">
                                <input name="rangEventDate[]" class="form-control width-md" type="text"
                                       value="<?= gd_isset($req['rangEventDate'][0]) ?>"
                                       placeholder="수기입력 가능">
                                <span class="input-group-addon">
                                    <span class="btn-icon-calendar">
                                    </span>
                                </span>
                            </div>
                            ~
                            <div class="input-group js-datepicker">
                                <input name="rangEventDate[]" class="form-control width-md" type="text"
                                       value="<?= gd_isset($req['rangEventDate'][1]) ?>"
                                       placeholder="수기입력 가능">
                                <span class="input-group-addon">
                                    <span class="btn-icon-calendar">
                                    </span>
                                </span>
                            </div>
                        </div>
                    </td>
                </tr>
            <?php } ?>
                <tr>
                    <th class="width-md">성별</th>
                    <td class="width-3xl"<?php if ($isShow == 'n') echo 'colspan="3"'; ?>>
                        <select name="writerSex" id="writerSex" class="form-control width-lg">
                            <option value="">전체보기</option>
                                   <option
                                   value="male" <?php if($req['writerSex'] == 'male')
                                    echo "selected='selected'" ?>> 남자</option>
                            <option
                                    value="female" <?php if($req['writerSex'] == 'female')
                                echo "selected='selected'" ?>>여자</option>

                        </select>

                    </td>
                </tr>


            <tr>
                <th>검색어</th>
                <td colspan="3">
                    <div class="form-inline">
                        <select class="form-control" name="searchField">
                            <option
                                    value="">선택
                            </option>
                            <option
                                value="writerNm" <?php if ($req['searchField'] == 'writerNm') echo 'selected' ?>>이름
                            </option>
                            <option
                                value="writerAge" <?php if ($req['searchField'] == 'writerAge') echo 'selected' ?>>
                                나이
                            </option>
                            <option
                                value="writerMobile" <?php if ($req['searchField'] == 'writerMobile') echo 'selected' ?>>
                                전화번호
                            </option>
                            <option
                                value="adminMemo" <?php if ($req['searchField'] == 'adminMemo') echo 'selected' ?>>
                                관리자메모
                            </option>

                        </select>

                        <input name="searchWord" value="<?= gd_isset($req['searchWord']) ?>"
                               class="form-control width-3xl">
                    </div>
                </td>
            </tr>
            <?php } ?>
        </table>
        <div class="table-btn">
            <input type="submit" value="검색" class="btn btn-lg btn-black">
        </div>
</form>

<?php if(!gd_is_provider()) { ?>
<ul class="nav nav-tabs mgb0" role="tablist">
    <li role="presentation" <?=$isShow == 'y' && $listType == 'board' ? 'class="active"' : ''?>>
        <a href="../qa/article_list.php?isShow=y&bdId=<?=$req['bdId']?>&listType=board">일반 게시물</a>
    </li>
    <li role="presentation" <?=$isShow == 'n' && $listType == 'board' ? 'class="active"' : ''?>>
        <a href="../qa/article_list.php?isShow=n&bdId=<?=$req['bdId']?>&listType=board">신고 게시물</a>
    </li>
    <li role="presentation" <?=$isShow == 'n' && $listType == 'memo' ? 'class="active"' : ''?>>
        <a href="../qa/article_list.php?isShow=n&bdId=<?=$req['bdId']?>&listType=memo">신고 댓글</a>
    </li>
</ul>
<?php } ?>

<div class="table-header">
    <div class="pull-left">
        검색&nbsp;<strong><?=number_format($bdList['cnt']['search']) ?></strong>개/
        전체&nbsp;<strong><?= number_format($bdList['cnt']['total']) ?></strong>개
        <?php if($isShow == 'n') { ?>
            <span class="notice-danger">신고 된 게시물의 경우 PC 및 모바일쇼핑몰에서 노출되지 않으니 신속히 확인하시어 대응하는 것을 권장 드립니다.</span>
        <?php } ?>
    </div>
    <div class="pull-right">
        <div class="form-inline">
            <?php if($isShow != 'n') { ?>
            <?= gd_select_box('sort', 'sort', $bdList['sort'], null, $req['sort']); ?>
            <?php } ?>
            <?= gd_select_box_by_page_view_count(Request::get()->get('pageNum',10)); ?>
        </div>
    </div>
</div>

<form name="frmList" id="frmList" action="article_ps.php" method="post">
    <input type="hidden" name="bdId" value="<?= $bdList['cfg']['bdId'] ?>">
    <input type="hidden" name="mode" value="delete">
    <input type="hidden" name="bdListDel" value="y">
    <input type="hidden" id="listType" name="listType" value="<?=$listType?>"/>
    <table id="listTbl" cellpadding="0" cellspacing="0" class="table table-rows table-fixed">
        <thead>
        <tr>
            <th class="width-2xs"><input type="checkbox" class="js-checkall" data-target-name="sno"></th>
            <th class="width-2xs">번호</th>
            <?php if ($bdList['cfg']['bdGoodsFl'] === 'y' && $bdList['cfg']['bdGoodsType'] === 'goods' && ($listType != 'memo')) { ?>
                <th class="width-sm">상품이미지</th>
            <?php } ?>
            <th>제목</th>
            <?php if($isShow == 'n') { ?>
                <th class="width-sm">신고일</th>
                <th class="">신고내용</th>
                <th class="width-sm">관리</th>
            <?php } else { ?>
            <th class="width-sm">상담시간</th>
            <th class="width-sm">작성자</th>
            <th class="width-sm">신청일</th>
            <th class="width-sm">상담일</th>
            <th class="width-sm">성별</th>
            <th class="width-sm">나이</th>
            <th class="width-sm">연락처</th>
            <?php  if ($bdList['cfg']['bdAnswerStatusFl'] == 'y' || $bdList['cfg']['bdReplyStatusFl'] == 'y') { ?>
                <th class="width-sm">처리상태</th>
            <?php } ?>
            <?php if ($bdList['cfg']['bdRecommendFl'] == 'y') { ?>
                <th class="width-2xs"> 추천</th>
            <?php } ?>
            <?php if ($bdList['cfg']['bdGoodsPtFl'] == 'y') { ?>
                <th class="width-2xs">평점</th>
            <?php } ?>
            <?php  if ($bdList['cfg']['bdAnswerStatusFl'] == 'y' || $bdList['cfg']['bdReplyStatusFl'] == 'y') { ?>
                <th class="width-sm">답변일</th>
            <?php } ?>
            <th class="width-sm">답변</th>
            <?php } ?>
        </tr>
        </thead>
        <?php
        if (gd_array_is_empty($bdList['list']) === false) {

            foreach ($bdList['list'] as $val) {

                if ($bdList['cfg']['bdGoodsFl'] === 'y' && $bdList['cfg']['bdGoodsType'] === 'goods') {
                    //게시글 관리에서 노출되는 상품이미지 항목의 노이미지 노출을 위해 imageStorage가 없는 경우 local 셋팅
                    if(!gd_isset($val['imageStorage'])){
                        $val['imageStorage'] = 'local';
                    }
                }
                ?>
                <tr class="center">
                    <td><input name="sno[]" type="checkbox" value="<?= $val['sno'] ?>" <?php if($val['auth']['delete'] != 'y' && $listType != 'memo') echo 'disabled'?>></td>
                    <td class="font-num">
                        <?php
                        if ($listType == 'memo') {
                            echo $page->idx--;
                            echo '<input type="hidden" name="bdSno['.$val['sno'].']" value="'.$val['bdSno'].'">';
                        } else {
                            if ($val['isNotice'] == 'y') {
                                echo gd_isset($bdList['cfg']['bdIconNotice']);
                            } else {
                                echo $val['articleListNo'];
                            }
                        }?>
                    </td>
                    <?php if ($bdList['cfg']['bdGoodsFl'] === 'y' && $bdList['cfg']['bdGoodsType'] === 'goods' && ($listType != 'memo')) { ?>
                        <td><?=gd_html_goods_image($val['goodsNo'], $val['imageName'], $val['imagePath'], $val['imageStorage'], 40, $val['goodsNm'], '_blank'); ?></td>
                    <?php } ?>
                    <td align="left">
                        <?= $val['gapReply'] ?><?php if ($val['groupThread'] != '')
                            echo gd_isset($bdList['cfg']['bdIconReply']); ?>
                        <a class="<?php if ($val['isNotice'] == 'y') {
                            echo 'notice';
                        } ?>"
                           href="javascript:btnView('<?= $bdList['cfg']['bdId'] ?>',<?= $val['sno'] ?>);">
                            <?php
                            if ($val['category']) {
                                echo '[' . $val['category'] . ']';
                            } ?>
                            <?= $listType == 'memo' ? $val['memo'] : $val['subject']; ?>
                        </a>
                        <?php if ($bdList['cfg']['bdMemoFl'] == 'y' && $val['memoCnt']) {
                            echo '&nbsp;<span class="memoCnt">[' . gd_isset($val['memoCnt']) . ']</span>';
                        } ?>
                        <?php if ($val['isSecret'] == 'y') {
                            echo gd_isset($bdList['cfg']['bdIconSecret']);
                        } ?>
                        <?php if ($val['isNew'] == 'y')
                            echo gd_isset($bdList['cfg']['bdIconNew']); ?>
                        <?php if ($val['isHot'] == 'y')
                            echo gd_isset($bdList['cfg']['bdIconHot']); ?>
                        <?php if ($val['isFile'] == 'y')
                            echo gd_isset($bdList['cfg']['bdIconFile']); ?>
                        <?php if ($listType == 'board') { ?>
                        <img src="/admin/gd_share/img/icon_grid_open.png" alt="팝업창열기" class="hand mgl5" border="0" onclick="javascript:articleViewPopup('<?= $val['sno'] ?>');">
                        <?php } ?>
                    </td>
                    <?php if($isShow == 'n') { ?>
                    <td><?=gd_date_format('Y-m-d', $val['reportDt']);?></td>
                    <td><?= gd_html_cut(gd_string_nl2br($val['reportMemo']), 96, '..'); ?></td>
                    <td>
                        <a onclick="btnView('<?= $req['bdId'] ?>', <?= $val['sno'] ?>);"
                           class="btn btn-white btn-sm">상세보기</a>
                    </td>
                    <?php } else { ?>
                    <td><?= $val['callTime'] ?></td>
                    <td><?php if ($val['memNo'] > 0 && !gd_is_provider()) {
                            echo "<a   class='js-layer-crm hand' data-member-no='" . $val['memNo'] . "' >";
                            $aTagClose = '</a>';
                        } ?>
                        <?= $val['writer'] . $aTagClose ?>
                    </td>
                    <td><?= $val['regDate'] ?></td>
                    <td><?= $val['modDt'] ?></td>
                    <td><?= $val['writerSex'] ?></td>
                    <td><?= $val['writerAge'] ?></td>
                    <td><?= $val['writerMobile'] ?></td>
                    <?php  if ($bdList['cfg']['bdAnswerStatusFl'] == 'y' || $bdList['cfg']['bdReplyStatusFl'] == 'y') { ?>
                        <td>
                            <?php
                            $replyStatus = array('상담 신청중','상담 진행중','상담 완료','상담 취소','상담 지연');
                            for($i = 0;$i<5;$i++){?>
                              <?php if ($val['replyStatus'] == $i) echo $replyStatus[$i]; ?>
                            <?php } ?>
                        </td>
                    <?php } ?>
                    <?php if ($bdList['cfg']['bdRecommendFl'] == 'y') { ?>
                        <td class="width-2xs">  <?= gd_isset($val['recommend'], 0) ?></td>
                    <?php } ?>

                    <?php if ($bdList['cfg']['bdGoodsPtFl'] == 'y') { ?>
                        <td class="width-2xs"><?= gd_isset($val['goodsPt'], 0) ?></td>
                    <?php } ?>
                    <?php  if ($bdList['cfg']['bdAnswerStatusFl'] == 'y' || $bdList['cfg']['bdReplyStatusFl'] == 'y') { ?>
                        <?php  if ($val['replyStatus'] == '3') { ?>
                            <td>
                                <?= $val['answerModDate'] ?>
                            </td>
                        <?php } else { ?>
                            <td>
                                <?= '-' ?>
                            </td>
                        <?php } ?>
                    <?php } ?>
                    <td>
                        <?php if($val['auth']['modify'] == 'y') {?>
                        <a onclick="btnModifyWrite('<?= $req['bdId'] ?>', <?= $val['sno'] ?>);"
                           class="btn btn-white btn-sm">수정</a>
                        <?php }?>
                        <?php if($req['bdId'] != 'qa'){ ?>
                        <?php if(!$val['adminFl'] && $val['auth']['reply'] == 'y') {?>
                        <a style="display:none" onclick="btnReplyWrite('<?= $req['bdId'] ?>',<?= $val['sno'] ?>);"
                           class="btn  btn-white btn-sm">답변</a>
                        <?php }?>
                        <?php }?>
                    </td>
                </tr>
                <?php
                }
            }
        } else {
            ?>
            <tr>
                <td colspan="7" height="50" class="no-data">게시물이 없습니다.</td>
            </tr>
        <?php } ?>
    </table>

    <div class="table-action">
        <div class="pull-left form-inline">
            <span class="action-title">선택한 게시글</span>
            <button type="button" class="btn btn-white js-btn-delete"/>삭제</button>
            <?php if($isShow == 'n') { ?>
            <button type="button" class="btn btn-white js-btn-report"/>신고해제</button>
            <?php } ?>
        </div>

        <?php if($isShow == 'y') { ?>
        <div class="pull-right">
            <button type="button" class="btn btn-white btn-icon-excel js-excel-download" data-target-form="frmSearch" data-target-list-form="frmList" data-target-list-sno="sno" data-search-count="<?=$bdList['cnt']['search']?>" data-total-count="<?=$bdList['cnt']['total']?>">엑셀다운로드</button>
        </div>
        <?php } ?>
    </div>

    <div class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog"
         aria-labelledby="mySmallModalLabel">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">엑셀 다운로드</h4>
                </div>

                <div class="modal-body">
                    <p> 다운받을 항목을 선택해주세요.</p>
                    <select name="excelDownloadType" class="form-control">
                        <option value="1">게시글 전체 다운로드</option>
                        <option value="2">선택한 게시글다운로드</option>
                        <option value="3">댓글 전체 다운로드</option>
                        <option value="4">선택한 댓글 다운로드</option>
                    </select>
                    <!--  <a href="./board_excel.php?bdId=<?php /*echo $bdList['cfg']['bdId']*/ ?>">다운로드</a>-->
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-red" onclick="excelDownload(this.form)">확인
                    </button>
                    <button type="button" class="btn btn-white" data-dismiss="modal">취소</button>
                </div>
            </div>
        </div>
    </div>
    <script>


        function writeArticle(sno) {
            frame_popup("article_write.php?bdId=<?=$bdList['cfg']['bdId']?>&mode=write&sno=" + ((sno) ? sno : ""), "<?=$bdList['cfg']['bdNm']?> 게시판", 'wide-lg');
        }

        function replyArticle(sno) {
            frame_popup("article_write.php?bdId=<?=$bdList['cfg']['bdId']?>&mode=reply&sno=" + ((sno) ? sno : ""), "<?=$bdList['cfg']['bdNm']?> 게시판", 'wide-xlg');
        }

        function modifyArticle(sno, hasParent) {
            if (hasParent) {
                frame_popup("article_write.php?bdId=<?=$bdList['cfg']['bdId']?>&mode=modify&sno=" + ((sno) ? sno : ""), "<?=$bdList['cfg']['bdNm']?> 게시판", 'wide-xlg');
            }
            else {
                frame_popup("article_write.php?bdId=<?=$bdList['cfg']['bdId']?>&mode=modify&sno=" + ((sno) ? sno : ""), "<?=$bdList['cfg']['bdNm']?> 게시판", 'wide-lg');
            }
        }

        function view(bdId, sno) {
            location.href = "article_view.php?bdId=" + bdId + "&sno=" + sno;
        }
        function articleViewPopup(sno) {
            window.open("../qa/article_view.php?bdId=<?=$bdList['cfg']['bdId']?>&popupMode=yes&mode=reply&sno=" + sno, "<?=$bdList['cfg']['bdNm']?> 게시판", 'width=1200,height=750,scrollbars=yes,resizable=yes');
        }

        $(document).ready(function () {
            $('.no-data').attr('colspan', $('#listTbl thead th').length);

            $('select[name=\'pageNum\']').change(function () {
                $('#frmSearch').submit();
            });

            $('select[name=\'sort\']').change(function () {
                $('#frmSearch').submit();
            });

            $('select[name=bdId]').bind('change',function(){
                location.href='article_list.php?bdId='+$(this).val()+'&isShow='+$('#isShow').val()+'&listType='+$('#listType').val();
            })

            $('.js-btn-delete').click(function() {
                $('#frmList input[name="mode"]').val('delete');
                $('#frmList').submit();
            });
            $('.js-btn-report').click(function() {
                $('#frmList input[name="mode"]').val('report');
                $('#frmList').submit();
            });

            $('#frmList').validate({
                ignore: ':hidden',
                dialog: false,
                submitHandler: function (form) {
                    var mode = form.mode.value;
                    <?php if($listType == 'memo') { ?>
                    form.action = 'memo_ps.php'
                    <?php } ?>
                    var msg = '';
                    if (mode == 'delete') {
                        var bdReplyDelFl = '<?=$bdList['cfg']['bdReplyDelFl']?>';
                        var confirmMsg = '';
                        if (bdReplyDelFl == 'reply') {
                            confirmMsg = '<br> 해당 글의 답변글도 함께 삭제되며\n\r';
                        }
                        msg = '선택한 글을 삭제하시겠습니까?\n\r ' + confirmMsg + '영구 삭제되어 복원 불가능합니다.';
                    } else if (mode == 'report') {
                        msg = '선택한 게시물을 신고해제 하시겠습니까?\n\r신고해제 시, 기존 신고내역은 확인 불가합니다.';
                    }
                    form.target = 'ifrmProcess';
                    dialog_confirm(msg, function (result) {
                        if (result) {
                            form.submit();
                        }
                    });
                },
                rules: {
                    'sno[]': {
                        required: true
                    }
                },
                messages: {
                    'sno[]': {
                        required: '선택하신 글이 없습니다.'
                    },

                },
            });
        });

        function excelDownload(frm) {
            var bdId = '<?=$bdList['cfg']['bdId']?>';
            var downloadtype = frm.excelDownloadType.value;
            var sno = [];
            $("input[name='sno[]']:checked").each(function () {
                sno.push($(this).val());
            });

            var snos = sno.join('-');
            if (downloadtype == '1' || downloadtype == '2') {
                location.href = './board_excel.php?downloadtype='+downloadtype+'&bdId=' + bdId + '&snos=' + encodeURI(snos);
            }
            else if (downloadtype == '3' || downloadtype == '4') {
                location.href = './memo_excel.php?downloadtype='+downloadtype+'&bdId=' + bdId + '&snos=' + encodeURI(snos);
            }
        }



    </script>
    <div class="center"><?= $bdList['pagination'] ?></div>
</form>
</div>


<?php } else { ?>
    <div class="page-header js-affix">
        <h3><?= end($naviMenu->location); ?>
            <small>게시물을 수정하고 관리합니다.</small>
        </h3>
        <?php ?>
        <?php if ($board->canWrite() == 'y') { ?>
            <input type="button" id="btnWrite" class="btn btn-red" onclick="btnWrite('<?= $req['bdId'] ?>');" value="등록">
        <?php } ?>
    </div>
    <div class="table-title gd-help-manual">게시글 관리</div>
    <form name="frmSearch" id="frmSearch" action="article_list.php" class="frmSearch js-form-enter-submit">
        <input type="hidden" id="boardListSort" name="boardListSort" value="<?=$boardListSort?>"/>
        <input type="hidden" id="isShow" name="isShow" value="<?=$isShow?>"/>
        <input type="hidden" id="listType" name="listType" value="<?=$listType?>"/>
        <div class="search-detail-box">
            <table class="table table-cols">
                <tr>
                    <th class="width-xs">게시판</th>
                    <td class="width-3xl"<?php if ($isShow == 'n') echo 'colspan="3"'; ?>>
                        <?php if (!gd_is_provider()) { ?>
                            <select name="bdId" id="bdId" class="form-control width-lg">
                                <?php
                                if (isset($boards) && is_array($boards)) {
                                    foreach ($boards as $val) {
                                        ?>
                                        <option
                                                value="<?= $val['bdId'] ?>" <?php if ($val['bdId'] == $bdList['cfg']['bdId'])
                                            echo "selected='selected'" ?> data-bdReplyStatusFl="<?=$val['bdReplyStatusFl']?>" data-bdEventFl="<?=$val['bdEventFl']?>" data-bdGoodsPtFl="<?=$val['bdGoodsPtFl']?>" data-bdGoodsFl="<?=$val['bdGoodsFl']?>"><?= $val['bdNm'] . '(' . $val['bdId'] . ')' ?></option>
                                        <?php
                                    }
                                }
                                ?>
                            </select>
                        <?php } else { ?>
                            <?= $bdList['cfg']['bdNm'] ?> (<?= $bdList['cfg']['bdId'] ?>)
                            <input type="hidden" name="bdId" value="<?= $bdList['cfg']['bdId'] ?>"/>
                        <?php } ?>

                    </td>
                    <?php if($isShow != 'n') { ?>
                        <th class="width-xs">말머리</th>
                        <td>
                            <div class="form-inline">
                                <?= gd_isset($bdList['categoryBox'], '-'); ?>
                            </div>
                        </td>
                    <?php } ?>
                </tr>
                <tr>
                    <th><?php if ($isShow == 'n') echo '신고'; ?>일자</th>
                    <td colspan="3">
                        <div class="form-inline">
                            <?php if($isShow == 'n') { ?>
                                <input type="hidden" name="searchDateFl" value="reportDt" />
                            <?php } else { ?>
                                <select name="searchDateFl" class="form-control">
                                    <option value="regDt" <?php if ($req['searchDateFl'] == 'regDt') echo 'selected' ?>>
                                        등록일
                                    </option>
                                    <option value="modDt" <?php if ($req['searchDateFl'] == 'modDt') echo 'selected' ?>>
                                        수정일
                                    </option>
                                </select>
                            <?php } ?>

                            <div class="input-group js-datepicker">
                                <input type="text" class="form-control width-xs" name="rangDate[]"
                                       value="<?= $req['rangDate'][0]; ?>">
                                <span class="input-group-addon">
                                        <span class="btn-icon-calendar">
                                        </span>
                                    </span>
                            </div>
                            ~
                            <div class="input-group js-datepicker">
                                <input type="text" class="form-control width-xs" name="rangDate[]"
                                       value="<?= $req['rangDate'][1]; ?>">
                                <span class="input-group-addon">
                                        <span class="btn-icon-calendar">
                                        </span>
                                    </span>
                            </div>
                            <?= gd_search_date(gd_isset($req['searchPeriod'], 6), 'rangDate', false) ?>
                        </div>
                    </td>
                </tr>
                <?php if($isShow != 'n') { ?>
                    <tr class="js-if-bdGoodsPtFl">
                        <?php  if ($bdList['cfg']['bdAnswerStatusFl'] == 'y' || $bdList['cfg']['bdReplyStatusFl'] == 'y') { ?>
                            <th>답변상태</th>
                            <td class="width-xl">
                                <div class="form-inline">
                                    <select name="replyStatus" class="form-control">
                                        <option value="">=전체=</option>
                                        <?php foreach ($board::REPLY_STATUS_LIST as $key => $val) { ?>
                                            <option value="<?= $key ?>" <?php if ($req['replyStatus'] == $key) echo 'selected' ?>><?= $val ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </td>
                        <?php } ?>
                        <?php if ($bdList['cfg']['bdGoodsPtFl'] == 'y') { ?>
                            <th>평점</th>
                            <td>
                                <select name="goodsPt" class="form-control">
                                    <option value="">=전체=</option>
                                    <?php
                                    for ($i = 1; $i < 6; $i++) { ?>
                                        <option
                                                value="<?= $i ?>" <?php if ((string)$i == (string)$req['goodsPt']) echo 'selected' ?>><?= $i ?></option>
                                    <?php } ?>
                                </select>
                            </td>
                        <?php } ?>
                    </tr>
                    <?php if ($bdList['cfg']['bdEventFl'] == 'y') { ?>
                        <tr class="js-if-bdEventFl">
                            <th>이벤트 기간</th>
                            <td colspan="3">
                                <div class="form-inline">
                                    <div class="input-group js-datepicker">
                                        <input name="rangEventDate[]" class="form-control width-xs" type="text"
                                               value="<?= gd_isset($req['rangEventDate'][0]) ?>"
                                               placeholder="수기입력 가능">
                                        <span class="input-group-addon">
                                    <span class="btn-icon-calendar">
                                    </span>
                                </span>
                                    </div>
                                    ~
                                    <div class="input-group js-datepicker">
                                        <input name="rangEventDate[]" class="form-control width-xs" type="text"
                                               value="<?= gd_isset($req['rangEventDate'][1]) ?>"
                                               placeholder="수기입력 가능">
                                        <span class="input-group-addon">
                                    <span class="btn-icon-calendar">
                                    </span>
                                </span>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    <?php } ?>
                    <tr>
                        <th>검색어</th>
                        <td colspan="3">
                            <div class="form-inline">
                                <select class="form-control" name="searchField">
                                    <option value="subject" <?php if ($req['searchField'] == 'subject') echo 'selected' ?>>
                                        제목
                                    </option>
                                    <option
                                            value="writerNick" <?php if ($req['searchField'] == 'writerNick') echo 'selected' ?>>닉네임
                                    </option>
                                    <option
                                            value="writerNm" <?php if ($req['searchField'] == 'writerNm') echo 'selected' ?>>이름
                                    </option>
                                    <option
                                            value="writerId" <?php if ($req['searchField'] == 'writerId') echo 'selected' ?>>아이디
                                    </option>
                                    <option
                                            value="contents" <?php if ($req['searchField'] == 'contents') echo 'selected' ?>>내용
                                    </option>
                                    <option
                                            value="subject_contents" <?php if ($req['searchField'] == 'subject_contents') echo 'selected' ?>>
                                        제목+내용
                                    </option>
                                    <option class="js-if-bdGoodsFl"
                                            value="goodsNm" <?php if ($req['searchField'] == 'goodsNm') echo 'selected' ?>>
                                        상품명
                                    </option>
                                    <option class="js-if-bdGoodsFl"
                                            value="goodsNo" <?php if ($req['searchField'] == 'goodsNo') echo 'selected' ?>>
                                        상품코드
                                    </option>
                                    <option class="js-if-bdGoodsFl"
                                            value="goodsCd" <?php if ($req['searchField'] == 'goodsCd') echo 'selected' ?>>
                                        자체상품코드
                                    </option>

                                </select>

                                <input name="searchWord" value="<?= gd_isset($req['searchWord']) ?>"
                                       class="form-control width-3xl">
                            </div>
                        </td>
                    </tr>
                <?php } ?>
            </table>
            <div class="table-btn">
                <input type="submit" value="검색" class="btn btn-lg btn-black">
            </div>
    </form>

    <?php if(!gd_is_provider()) { ?>
        <ul class="nav nav-tabs mgb0" role="tablist">
            <li role="presentation" <?=$isShow == 'y' && $listType == 'board' ? 'class="active"' : ''?>>
                <a href="../board/article_list.php?isShow=y&bdId=<?=$req['bdId']?>&listType=board">일반 게시물</a>
            </li>
            <li role="presentation" <?=$isShow == 'n' && $listType == 'board' ? 'class="active"' : ''?>>
                <a href="../board/article_list.php?isShow=n&bdId=<?=$req['bdId']?>&listType=board">신고 게시물</a>
            </li>
            <li role="presentation" <?=$isShow == 'n' && $listType == 'memo' ? 'class="active"' : ''?>>
                <a href="../board/article_list.php?isShow=n&bdId=<?=$req['bdId']?>&listType=memo">신고 댓글</a>
            </li>
        </ul>
    <?php } ?>

    <div class="table-header">
        <div class="pull-left">
            검색&nbsp;<strong><?=number_format($bdList['cnt']['search']) ?></strong>개/
            전체&nbsp;<strong><?= number_format($bdList['cnt']['total']) ?></strong>개
            <?php if($isShow == 'n') { ?>
                <span class="notice-danger">신고 된 게시물의 경우 PC 및 모바일쇼핑몰에서 노출되지 않으니 신속히 확인하시어 대응하는 것을 권장 드립니다.</span>
            <?php } ?>
        </div>
        <div class="pull-right">
            <div class="form-inline">
                <?php if($isShow != 'n') { ?>
                    <?= gd_select_box('sort', 'sort', $bdList['sort'], null, $req['sort']); ?>
                <?php } ?>
                <?= gd_select_box_by_page_view_count(Request::get()->get('pageNum',10)); ?>
            </div>
        </div>
    </div>

    <form name="frmList" id="frmList" action="article_ps.php" method="post">
        <input type="hidden" name="bdId" value="<?= $bdList['cfg']['bdId'] ?>">
        <input type="hidden" name="mode" value="delete">
        <input type="hidden" name="bdListDel" value="y">
        <input type="hidden" id="listType" name="listType" value="<?=$listType?>"/>
        <table id="listTbl" cellpadding="0" cellspacing="0" class="table table-rows table-fixed">
            <thead>
            <tr>
                <th class="width-2xs"><input type="checkbox" class="js-checkall" data-target-name="sno"></th>
                <th class="width-2xs">번호</th>
                <?php if ($bdList['cfg']['bdGoodsFl'] === 'y' && $bdList['cfg']['bdGoodsType'] === 'goods' && ($listType != 'memo')) { ?>
                    <th class="width-sm">상품이미지</th>
                <?php } ?>
                <th>제목</th>
                <?php if($isShow == 'n') { ?>
                    <th class="width-sm">신고일</th>
                    <th class="">신고내용</th>
                    <th class="width-sm">관리</th>
                <?php } else { ?>
                    <th class="width-sm">작성자</th>
                    <th class="width-sm">작성일</th>
                    <th class="width-2xs">조회</th>
                    <?php  if ($bdList['cfg']['bdAnswerStatusFl'] == 'y' || $bdList['cfg']['bdReplyStatusFl'] == 'y') { ?>
                        <th class="width-sm">답변상태</th>
                    <?php } ?>
                    <?php if ($bdList['cfg']['bdRecommendFl'] == 'y') { ?>
                        <th class="width-2xs"> 추천</th>
                    <?php } ?>
                    <?php if ($bdList['cfg']['bdGoodsPtFl'] == 'y') { ?>
                        <th class="width-2xs">평점</th>
                    <?php } ?>
                    <?php  if ($bdList['cfg']['bdAnswerStatusFl'] == 'y' || $bdList['cfg']['bdReplyStatusFl'] == 'y') { ?>
                        <th class="width-sm">답변일</th>
                    <?php } ?>
                    <th class="width-sm">수정/답변</th>
                <?php } ?>
            </tr>
            </thead>
            <?php
            if (gd_array_is_empty($bdList['list']) === false) {
                foreach ($bdList['list'] as $val) {
                    if ($bdList['cfg']['bdGoodsFl'] === 'y' && $bdList['cfg']['bdGoodsType'] === 'goods') {
                        //게시글 관리에서 노출되는 상품이미지 항목의 노이미지 노출을 위해 imageStorage가 없는 경우 local 셋팅
                        if(!gd_isset($val['imageStorage'])){
                            $val['imageStorage'] = 'local';
                        }
                    }
                    ?>
                    <tr class="center">
                    <td><input name="sno[]" type="checkbox" value="<?= $val['sno'] ?>" <?php if($val['auth']['delete'] != 'y' && $listType != 'memo') echo 'disabled'?>></td>
                    <td class="font-num">
                        <?php
                        if ($listType == 'memo') {
                            echo $page->idx--;
                            echo '<input type="hidden" name="bdSno['.$val['sno'].']" value="'.$val['bdSno'].'">';
                        } else {
                            if ($val['isNotice'] == 'y') {
                                echo gd_isset($bdList['cfg']['bdIconNotice']);
                            } else {
                                echo $val['articleListNo'];
                            }
                        }?>
                    </td>
                    <?php if ($bdList['cfg']['bdGoodsFl'] === 'y' && $bdList['cfg']['bdGoodsType'] === 'goods' && ($listType != 'memo')) { ?>
                        <td><?=gd_html_goods_image($val['goodsNo'], $val['imageName'], $val['imagePath'], $val['imageStorage'], 40, $val['goodsNm'], '_blank'); ?></td>
                    <?php } ?>
                    <td align="left">
                        <?= $val['gapReply'] ?><?php if ($val['groupThread'] != '')
                            echo gd_isset($bdList['cfg']['bdIconReply']); ?>
                        <a class="<?php if ($val['isNotice'] == 'y') {
                            echo 'notice';
                        } ?>"
                           href="javascript:btnView('<?= $bdList['cfg']['bdId'] ?>',<?= $val['sno'] ?>);">
                            <?php
                            if ($val['category']) {
                                echo '[' . $val['category'] . ']';
                            } ?>
                            <?= $listType == 'memo' ? $val['memo'] : $val['subject']; ?>
                        </a>
                        <?php if ($bdList['cfg']['bdMemoFl'] == 'y' && $val['memoCnt']) {
                            echo '&nbsp;<span class="memoCnt">[' . gd_isset($val['memoCnt']) . ']</span>';
                        } ?>
                        <?php if ($val['isSecret'] == 'y') {
                            echo gd_isset($bdList['cfg']['bdIconSecret']);
                        } ?>
                        <?php if ($val['isNew'] == 'y')
                            echo gd_isset($bdList['cfg']['bdIconNew']); ?>
                        <?php if ($val['isHot'] == 'y')
                            echo gd_isset($bdList['cfg']['bdIconHot']); ?>
                        <?php if ($val['isFile'] == 'y')
                            echo gd_isset($bdList['cfg']['bdIconFile']); ?>
                        <?php if ($listType == 'board') { ?>
                            <img src="/admin/gd_share/img/icon_grid_open.png" alt="팝업창열기" class="hand mgl5" border="0" onclick="javascript:articleViewPopup('<?= $val['sno'] ?>');">
                        <?php } ?>
                    </td>
                    <?php if($isShow == 'n') { ?>
                        <td><?=gd_date_format('Y-m-d', $val['reportDt']);?></td>
                        <td><?= gd_html_cut(gd_string_nl2br($val['reportMemo']), 96, '..'); ?></td>
                        <td>
                            <a onclick="btnView('<?= $req['bdId'] ?>', <?= $val['sno'] ?>);"
                               class="btn btn-white btn-sm">상세보기</a>
                        </td>
                    <?php } else { ?>
                        <td><?php if ($val['memNo'] > 0 && !gd_is_provider()) {
                                echo "<a   class='js-layer-crm hand' data-member-no='" . $val['memNo'] . "' >";
                                $aTagClose = '</a>';
                            } ?>
                            <?= $val['writer'] . $aTagClose ?>
                        </td>
                        <td><?= $val['regDate'] ?></td>
                        <td><?= number_format($val['hit']) ?></td>
                        <?php  if ($bdList['cfg']['bdAnswerStatusFl'] == 'y' || $bdList['cfg']['bdReplyStatusFl'] == 'y') { ?>
                            <td>
                                <?= $val['replyStatusText'] ?>
                            </td>
                        <?php } ?>
                        <?php if ($bdList['cfg']['bdRecommendFl'] == 'y') { ?>
                            <td class="width-2xs">  <?= gd_isset($val['recommend'], 0) ?></td>
                        <?php } ?>

                        <?php if ($bdList['cfg']['bdGoodsPtFl'] == 'y') { ?>
                            <td class="width-2xs"><?= gd_isset($val['goodsPt'], 0) ?></td>
                        <?php } ?>
                        <?php  if ($bdList['cfg']['bdAnswerStatusFl'] == 'y' || $bdList['cfg']['bdReplyStatusFl'] == 'y') { ?>
                            <?php  if ($val['replyStatus'] == '3') { ?>
                                <td>
                                    <?= $val['answerModDate'] ?>
                                </td>
                            <?php } else { ?>
                                <td>
                                    <?= '-' ?>
                                </td>
                            <?php } ?>
                        <?php } ?>
                        <td>
                            <?php if($val['auth']['modify'] == 'y') {?>
                                <a onclick="btnModifyWrite('<?= $req['bdId'] ?>', <?= $val['sno'] ?>);"
                                   class="btn btn-white btn-sm">수정</a>
                            <?php }?>
                            <?php if(!$val['adminFl'] && $val['auth']['reply'] == 'y') {?>
                                <a onclick="btnReplyWrite('<?= $req['bdId'] ?>',<?= $val['sno'] ?>);"
                                   class="btn  btn-white btn-sm">답변</a>
                            <?php }?>
                        </td>
                        </tr>
                        <?php
                    }
                }
            } else {
                ?>
                <tr>
                    <td colspan="7" height="50" class="no-data">게시물이 없습니다.</td>
                </tr>
            <?php } ?>
        </table>

        <div class="table-action">
            <div class="pull-left form-inline">
                <span class="action-title">선택한 게시글</span>
                <button type="button" class="btn btn-white js-btn-delete"/>삭제</button>
                <?php if($isShow == 'n') { ?>
                    <button type="button" class="btn btn-white js-btn-report"/>신고해제</button>
                <?php } ?>
            </div>

            <?php if($isShow == 'y') { ?>
                <div class="pull-right">
                    <button type="button" class="btn btn-white btn-icon-excel js-excel-download" data-target-form="frmSearch" data-target-list-form="frmList" data-target-list-sno="sno" data-search-count="<?=$bdList['cnt']['search']?>" data-total-count="<?=$bdList['cnt']['total']?>">엑셀다운로드</button>
                </div>
            <?php } ?>
        </div>

        <div class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog"
             aria-labelledby="mySmallModalLabel">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                    aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">엑셀 다운로드</h4>
                    </div>

                    <div class="modal-body">
                        <p> 다운받을 항목을 선택해주세요.</p>
                        <select name="excelDownloadType" class="form-control">
                            <option value="1">게시글 전체 다운로드</option>
                            <option value="2">선택한 게시글다운로드</option>
                            <option value="3">댓글 전체 다운로드</option>
                            <option value="4">선택한 댓글 다운로드</option>
                        </select>
                        <!--  <a href="./board_excel.php?bdId=<?php /*echo $bdList['cfg']['bdId']*/ ?>">다운로드</a>-->
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-red" onclick="excelDownload(this.form)">확인
                        </button>
                        <button type="button" class="btn btn-white" data-dismiss="modal">취소</button>
                    </div>
                </div>
            </div>
        </div>
        <script>
            function writeArticle(sno) {
                frame_popup("article_write.php?bdId=<?=$bdList['cfg']['bdId']?>&mode=write&sno=" + ((sno) ? sno : ""), "<?=$bdList['cfg']['bdNm']?> 게시판", 'wide-lg');
            }

            function replyArticle(sno) {
                frame_popup("article_write.php?bdId=<?=$bdList['cfg']['bdId']?>&mode=reply&sno=" + ((sno) ? sno : ""), "<?=$bdList['cfg']['bdNm']?> 게시판", 'wide-xlg');
            }

            function modifyArticle(sno, hasParent) {
                if (hasParent) {
                    frame_popup("article_write.php?bdId=<?=$bdList['cfg']['bdId']?>&mode=modify&sno=" + ((sno) ? sno : ""), "<?=$bdList['cfg']['bdNm']?> 게시판", 'wide-xlg');
                }
                else {
                    frame_popup("article_write.php?bdId=<?=$bdList['cfg']['bdId']?>&mode=modify&sno=" + ((sno) ? sno : ""), "<?=$bdList['cfg']['bdNm']?> 게시판", 'wide-lg');
                }
            }

            function view(bdId, sno) {
                location.href = "article_view.php?bdId=" + bdId + "&sno=" + sno;
            }
            function articleViewPopup(sno) {
                window.open("../board/article_view.php?bdId=<?=$bdList['cfg']['bdId']?>&popupMode=yes&mode=reply&sno=" + sno, "<?=$bdList['cfg']['bdNm']?> 게시판", 'width=1200,height=750,scrollbars=yes,resizable=yes');
            }

            $(document).ready(function () {
                $('.no-data').attr('colspan', $('#listTbl thead th').length);

                $('select[name=\'pageNum\']').change(function () {
                    $('#frmSearch').submit();
                });

                $('select[name=\'sort\']').change(function () {
                    $('#frmSearch').submit();
                });

                $('select[name=bdId]').bind('change',function(){
                    location.href='article_list.php?bdId='+$(this).val()+'&isShow='+$('#isShow').val()+'&listType='+$('#listType').val();
                })

                $('.js-btn-delete').click(function() {
                    $('#frmList input[name="mode"]').val('delete');
                    $('#frmList').submit();
                });
                $('.js-btn-report').click(function() {
                    $('#frmList input[name="mode"]').val('report');
                    $('#frmList').submit();
                });

                $('#frmList').validate({
                    ignore: ':hidden',
                    dialog: false,
                    submitHandler: function (form) {
                        var mode = form.mode.value;
                        <?php if($listType == 'memo') { ?>
                        form.action = 'memo_ps.php'
                        <?php } ?>
                        var msg = '';
                        if (mode == 'delete') {
                            var bdReplyDelFl = '<?=$bdList['cfg']['bdReplyDelFl']?>';
                            var confirmMsg = '';
                            if (bdReplyDelFl == 'reply') {
                                confirmMsg = '<br> 해당 글의 답변글도 함께 삭제되며\n\r';
                            }
                            msg = '선택한 글을 삭제하시겠습니까?\n\r ' + confirmMsg + '영구 삭제되어 복원 불가능합니다.';
                        } else if (mode == 'report') {
                            msg = '선택한 게시물을 신고해제 하시겠습니까?\n\r신고해제 시, 기존 신고내역은 확인 불가합니다.';
                        }
                        form.target = 'ifrmProcess';
                        dialog_confirm(msg, function (result) {
                            if (result) {
                                form.submit();
                            }
                        });
                    },
                    rules: {
                        'sno[]': {
                            required: true
                        }
                    },
                    messages: {
                        'sno[]': {
                            required: '선택하신 글이 없습니다.'
                        },

                    },
                });
            });

            function excelDownload(frm) {
                var bdId = '<?=$bdList['cfg']['bdId']?>';
                var downloadtype = frm.excelDownloadType.value;
                var sno = [];
                $("input[name='sno[]']:checked").each(function () {
                    sno.push($(this).val());
                });

                var snos = sno.join('-');
                if (downloadtype == '1' || downloadtype == '2') {
                    location.href = './board_excel.php?downloadtype='+downloadtype+'&bdId=' + bdId + '&snos=' + encodeURI(snos);
                }
                else if (downloadtype == '3' || downloadtype == '4') {
                    location.href = './memo_excel.php?downloadtype='+downloadtype+'&bdId=' + bdId + '&snos=' + encodeURI(snos);
                }
            }

        </script>
        <div class="center"><?= $bdList['pagination'] ?></div>
    </form>
    </div>



<?php }