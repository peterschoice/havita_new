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
 * @link      http://www.godo.co.kr
 */
namespace Component\Database;

use Component\Database\Traits\APITableField; // API 관련 테이블

/**
 * DB Table 기본 Field 클래스 - DB 테이블의 기본 필드를 설정한 클래스 이며, prepare query 생성시 필요한 기본 필드 정보임
 * @package Component\Database
 * @static  tableConfig
 */
class DBTableField extends \Bundle\Component\Database\DBTableField
{

    public static function tableBd($req)
    {
        $arrField = parent::tableBd();
        $getData = \Request::request()->toArray();
        if($getData['bdId'] == 'qa') {
            $arrField[] = ["val" => "writerSex", 'typ' => 's', 'def' => '']; // 이름
            $arrField[] = ["val" => "writerAge", 'typ' => 'i', 'def' => '']; // 나이
            $arrField[] = ["val" => "callTime", 'typ' => 's', 'def' => '']; // 상담시간
        }
        return $arrField;

    }

}