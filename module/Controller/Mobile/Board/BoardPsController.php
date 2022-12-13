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
namespace Controller\Mobile\Board;

use Component\Board\BoardWrite;
use Component\Board\BoardConfig;
use Component\Storage\Storage;
use Component\Board\BoardBuildQuery;
use Component\Board\BoardUtil;
use Component\Goods\GoodsCate;
use Component\Page\Page;
use Component\Validator\Validator;
use Component\Board\BoardList;
use Component\Board\BoardView;
use Component\Board\BoardAct;
use Component\Board\BoardReport;
use Framework\Debug\Exception\AlertBackException;
use Framework\Debug\Exception\AlertOnlyException;
use Framework\StaticProxy\Proxy\Logger;
use Framework\Utility\ArrayUtils;
use View\Template;
use Request;

class BoardPsController extends \Controller\Front\Board\BoardPsController
{

    public function index()
    {
        $req = Request::post()->toArray();
        if($req['mode'] == 'write' && $req['bdId'] == 'qa'){
            $boardAct = new BoardAct($req);
          if (method_exists($boardAct, 'setHttpStorage') === true) { //HTTP Storage setting.
                $boardAct->setHttpStorage(false);
          }

          $addScrpt = '';
          $boardAct->saveData();
          $msg = '상담이 신청되었습니다.';
          $addScrpt .= 'alert("' . $msg . '");';
          $this->js($addScrpt . 'location.href="../service/inquiry.php?lab=' . $req['returnUrl'] . '";');
          exit;
        }

        parent::index();
    }

}
