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

use Component\Validator\Validator;
use Framework\Utility\StringUtils;
use Framework\StaticProxy\Proxy\App;

abstract class Board extends \Bundle\Component\Board\Board
{

    protected static $_db;

    public function __construct($req)
    {
        parent::__construct($req);
        self::$_db = App::load('DB');
    }

    public function getQaType(){
        $arrField[] = ["val" => "writerSex", 'typ' => 's', 'def' => '']; // 이름
        $arrField[] = ["val" => "writerAge", 'typ' => 'i', 'def' => '']; // 나이
        $arrField[] = ["val" => "callTime", 'typ' => 's', 'def' => '']; // 상담시간
        return $arrField;
    }

    public function saveData()
    {
        parent::saveData();

        $validator = new Validator();

        if ($this->cfg['bdQaFl'] == 'y') {
            $this->setSaveData('writerSex', $this->req['writerSex'], $arrData, $validator);
            $this->setSaveData('writerAge', $this->req['writerAge'], $arrData, $validator);
            $this->setSaveData('callTime', $this->req['callTime'], $arrData, $validator);
            $this->update($arrData, $this->req['sno']);
        }

    }

    public function update($arrData, $sno)
    {
        $arrBind = self::$_db->get_binding($this->getQaType(), $arrData, 'update', array_keys($arrData));
        self::$_db->bind_param_push($arrBind['bind'], 'i', $sno);
        $affectedRows = self::$_db->set_update_db('es_bd_event', $arrBind['param'], 'sno = ?', $arrBind['bind']);
        return $affectedRows;
    }

}
