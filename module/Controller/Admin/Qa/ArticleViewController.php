<?php

/**
 * This is commercial software, only users who have purchased a valid license
 * and accept to the terms of the License Agreement can install and use this
 * program.
 *
 * Do not edit or add to this file if you wish to upgrade Godomall5 to newer
 * versions in the future.
 *
 * @copyright ⓒ 2016, NHN godo: Corp.
 * @link http://www.godo.co.kr
 */
namespace Bundle\Controller\Admin\Board;

use Component\Board\ArticleViewAdmin;
use Component\Board\ArticleWriteAdmin;
use Component\Board\Board;
use Component\Board\BoardAdmin;
use Component\Board\BoardReport;
use Component\Goods\GoodsCate;
use Component\Page\Page;
use Framework\Debug\Exception\AlertRedirectException;
use Framework\Debug\Exception\AlertBackException;
use Request;

class ArticleViewController extends \Controller\Admin\Controller
{
    /**
     * Description
     */
    public function index()
    {
        try {
            $req = array_merge((array)Request::get()->toArray(), (array)Request::post()->toArray());
            $articleWrite = new ArticleWriteAdmin($req);
            $boardView = new ArticleViewAdmin($req);
            if($req['listType'] == 'memo') {
                $getData = $boardView->getMemoView($req['sno']);
            } else {
                $getData = $boardView->getView();
            }

            gd_isset($getData['isShow'], 'y');
            gd_isset($req['listType'], 'board');
            $this->setData('isShow', $getData['isShow']);
            $this->setData('listType', $req['listType']);

            if($getData['isShow'] == 'n') {
                $boardReport = new BoardReport($req);
                $reportData = $boardReport->reportGet($req);
                $this->setData('reportData', $reportData);
            }

            if (gd_is_provider()) {
                if (Request::get()->get('bdId') == Board::BASIC_GOODS_REIVEW_ID) {
                    $this->callMenu('board', 'board', 'goodsReviewView');
                } else {
                    $this->callMenu('board', 'board', 'goodsQaView');
                }
            } else {
                if ($getData['isShow'] == 'y') {
                    $this->callMenu('board', 'board', 'boardView');
                } else {
                    $this->callMenu('board', 'board', 'boardReport');
                }
            }
            $this->addScript([
                'gd_board_common.js',
                'gd_board_view.js',
            ]);

            if (gd_is_provider()) {
                if ($getData['goodsData']['scmNo'] != \Session::get('manager.scmNo')) {
                    throw new \Exception(__('잘못된 경로로 접근하셨습니다.'));
                }
            }

            $bdView['cfg'] = gd_isset($boardView->cfg);
            $bdView['data'] = gd_isset($getData);
            $bdView['member'] = gd_isset($boardView->member);
            $this->setData('writer', $articleWrite->getWrite());
            $this->setData('req', gd_isset($req));
            $this->setData('bdView', $bdView);
            if($req['listType'] == 'memo') {
                return true;
            }

            $relationList = $boardView->getRelation($getData);
            $this->setData('listReplyStatus', Board::REPLY_STATUS_LIST);
            $this->setData('relationList', $relationList);

            $checkSecretReplyView = $boardView->setSecretReplyView($bdView['cfg']['bdSecretReplyFl']);
            $this->setData('checkSecretReplyView', $checkSecretReplyView['checkbox']);
            $this->setData('hiddenCheckboxInModify', gd_isset($checkSecretReplyView['hiddenCheckboxInModify']));
            $this->setData('hiddenCheckboxInReply', gd_isset($checkSecretReplyView['hiddenCheckboxInReply']));
            $this->setData('hiddenCheckboxInWrite', gd_isset($checkSecretReplyView['hiddenCheckboxInWrite']));

            // CRM고객관리에서 클릭 시 팝업모드실행
            if ($req['popupMode'] === 'yes') {
                $this->getView()->setDefine('layout', 'layout_blank.php');
            }
            $this->getView()->setPageName('board/article_view.php');
        } catch (\Exception $e) {
            throw new AlertBackException($e->getMessage());
            //throw new AlertRedirectException($e->getMessage(), null, null, Request::getReferer());
        }
    }
}
