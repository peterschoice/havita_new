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

namespace Component\Board;

use Component\Board\BoardUtil;
use Component\Admin\AdminMenu;
use Component\Member\Util\MemberUtil;
use Component\Goods\AddGoodsAdmin;
use Component\Goods\Goods;
use Component\Mail\MailAuto;
use Component\Member\MemberReport;
use Component\Order\Order;
use Component\Page\Page;
use Component\Storage\Storage;
use Component\Validator\Validator;
use Component\Database\DBTableField;
use Framework\Debug\Exception\RequiredLoginException;
use Framework\Utility\ArrayUtils;
use Framework\Utility\ImageUtils;
use Framework\Utility\SkinUtils;
use Framework\Utility\StringUtils;

use Request;
use App;
use Session;
use Respect\Validation\Rules\MyValidator;


abstract class Board extends \Bundle\Component\Board\Board
{

    protected static $_db;

    public function __construct($req)
    {
        parent::__construct($req);
        self::$_db = App::load('DB');
    }

    public function getQaType(){
        $arrField = DBTableField::tableBd();
        $arrField[] = ["val" => "writerSex", 'typ' => 's', 'def' => '']; // 이름
        $arrField[] = ["val" => "writerAge", 'typ' => 'i', 'def' => '']; // 나이
        $arrField[] = ["val" => "callTime", 'typ' => 's', 'def' => '']; // 상담시간
        return $arrField;
    }

    public function saveData()
    {
        if ($this->cfg['bdId'] == 'qa' && $this->req['mode'] == 'write') {
            $validator = new Validator();
            $arrData = [];
            gd_isset($this->req['isNotice'], 'n');
            gd_isset($this->req['isSecret'], 'n');
            if (empty($this->channel)) {
                $remoteAddr = Request::getRemoteAddress();
            }
            $isMobile = $this->req['isMobile'] ? 'y' : 'n';
            $memNo = gd_isset($this->member['memNo'], 0);
            $encryptPw = md5(gd_isset($this->req['writerPw']));
            $parentSno = 0;
            $msgs = '';
            if (gd_is_login()) {
                $this->req['writerNm'] = $this->member['memNm'];
            }
            $writerId = $this->member['memId'];
            $writerNick = $this->member['memNick'];

            $groupNo = BoardUtil::createGroupNo($this->cfg['bdId']);

            $this->setSaveData('writerPw', $encryptPw, $arrData, $validator);
            $this->setSaveData('memNo', $memNo, $arrData, $validator);
            $this->setSaveData('writerIp', $remoteAddr, $arrData, $validator);
            $this->setSaveData('parentSno', $parentSno, $arrData, $validator);
            $this->setSaveData('isMobile', $isMobile, $arrData, $validator);
            $this->setSaveData('writerNm', $this->req['writerNm'], $arrData, $validator);
            $this->setSaveData('writerId', $writerId, $arrData, $validator);
            $this->setSaveData('writerNick', $writerNick, $arrData, $validator);
            $this->setSaveData('groupNo', $groupNo, $arrData, $validator);
            $this->setSaveData('groupThread', $groupThread, $arrData, $validator);
            $this->setSaveData('writerEmail', $this->req['writerEmail'], $arrData, $validator);
            $this->setSaveData('writerHp', $this->req['writerHp'], $arrData, $validator);
            $this->setSaveData('writerMobile', $this->req['writerMobile'], $arrData, $validator);
            $this->setSaveData('subject', $this->req['subject'], $arrData, $validator);
            $this->setSaveData('subSubject', $this->req['subSubject'], $arrData, $validator);
            $this->setSaveData('contents', $this->req['contents'], $arrData, $validator);
            $this->setSaveData('urlLink', $this->req['urlLink'], $arrData, $validator);
            $this->setSaveData('uploadFileNm', $file['uploadFileNm'], $arrData, $validator);
            $this->setSaveData('subSubject', $this->req['subSubject'], $arrData, $validator);
            $this->setSaveData('saveFileNm', $file['saveFileNm'], $arrData, $validator);
            $this->setSaveData('bdUploadStorage', $file['bdUploadStorage'], $arrData, $validator);
            $this->setSaveData('bdUploadPath', $file['bdUploadPath'], $arrData, $validator);
            $this->setSaveData('bdUploadThumbPath', $file['bdUploadThumbPath'], $arrData, $validator);
            $this->setSaveData('isNotice', $this->req['isNotice'], $arrData, $validator);
            $this->setSaveData('isSecret', $this->req['isSecret'], $arrData, $validator);
            $this->setSaveData('category', $this->req['category'], $arrData, $validator);
            $this->setSaveData('writerSex', $this->req['writerSex'], $arrData, $validator);
            $this->setSaveData('writerAge', $this->req['writerAge'], $arrData, $validator);
            $this->setSaveData('callTime', $this->req['callTime'], $arrData, $validator);

            $arrBind = $this->db->get_binding($this->getQaType(), $arrData, 'insert', array_keys($arrData));
            $strBind = [];
            foreach ($arrBind['param'] as $_bind) {
                $strBind[] = '?';
            }

            //30초 이내 등록된 게시글이 있는지 확인하고 INSERT 실행
            $strSQL = 'INSERT INTO ' . DB_BD_ . $this->cfg['bdId'] . '(' . implode(',', $arrBind['param']) . ', regDt)';
            $strSQL .= ' SELECT ' . implode(',', $strBind) . ', NOW() FROM DUAL ' ;
            $strSQL .= ' WHERE (SELECT count(*) FROM ' . DB_BD_ . $this->cfg['bdId'] . ' WHERE groupNo <= ? AND groupThread = \'\' AND memNo = ? AND subject = ? AND writerIp = ? AND regDt >= (now()-INTERVAL 30 SECOND)) = 0 ';
            $chkGroupNo = gd_isset(Session::get('groupNo_' . $this->cfg['bdId']), $groupNo);
            $this->db->bind_param_push($arrBind['bind'], 'i', $chkGroupNo);
            $this->db->bind_param_push($arrBind['bind'], 'i', $memNo);
            $this->db->bind_param_push($arrBind['bind'], 's', $arrData['subject']);
            $this->db->bind_param_push($arrBind['bind'], 's', $remoteAddr);
            $this->db->bind_query($strSQL, $arrBind['bind']);
            $insId = $this->db->insert_id();
            if ($insId < 1) { //중복글로 인한 저장 실패
                throw new \Exception(__("중복된 게시물을 연속으로 등록할 수 없습니다. \n중복 게시물이 아닌 경우, 잠시 후 다시 등록하시기 바랍니다."));
            } else {
                Session::set('groupNo_' . $this->cfg['bdId'], $groupNo); //등록된 groupNo 값으로 갱신.
            }
            $data = $this->buildQuery->selectOneWithGoodsAndMember($insId);

            $this->handleAfterWrite($data, $msgs);

        } else {
            parent::saveData();
        }

    }

    public function update($arrData, $sno)
    {
        $arrBind = self::$_db->get_binding($this->getQaType(), $arrData, 'update', array_keys($arrData));
        self::$_db->bind_param_push($arrBind['bind'], 'i', $sno);
        $affectedRows = self::$_db->set_update_db('es_bd_qa', $arrBind['param'], 'sno = ?', $arrBind['bind']);
        return $affectedRows;
    }

}
