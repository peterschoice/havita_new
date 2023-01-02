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
namespace Controller\Front\Service;


use Request;
/**
 * 사이트 접속 페이지
 *
 * @author Jong-tae Ahn <qnibus@godo.co.kr>
 */
class InquiryController extends \Controller\Front\Controller
{
    /**
     * {@inheritdoc}
     */
    public function index()
    {

        $getData=\Request::get()->toArray();
        $labType = '브레인랩';
        $lab = 'brain';
        if($getData['lab'] == 'beauty'){
            $labType = '뷰티랩';
            $lab = 'beauty';
        }
        $this->setData('labType',$labType);
        $this->setData('lab',$lab);

        $member=\Session::get('member');
        /** @var  \Component\Member\Member $member */
        $member2 = \App::load('\\Component\\Member\\Member');
        $memberData = $member2->getMember($member['memNo'], 'memNo', 'memNm,memNo, memId, birthDt, sexFl, cellPhone');

        $this->setData('memNm',$memberData['memNm']);
        $this->setData('writerSex',$memberData['sexFl']);
        $this->setData('writerMobile',$memberData['cellPhone']);
        $this->setData('writerAge',(date("Y") - explode('-',$memberData['birthDt'])[0]));
    }
}