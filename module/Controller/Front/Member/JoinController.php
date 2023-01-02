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
namespace Controller\Front\Member;

use Component\Facebook\Facebook;
use Component\Godo\GodoPaycoServerApi;
use Component\Member\MemberValidation;
use Component\Member\Util\MemberUtil;
use Component\SiteLink\SiteLink;
use Framework\Utility\ArrayUtils;
use Framework\Security\Token;
use Request;
use Session;

/**
 * Class 회원가입 정보입력
 * @package Bundle\Controller\Front\Member
 * @author  yjwee
 */
class JoinController extends \Bundle\Controller\Front\Member\JoinController
{
	public function index()
	{


        $request = \App::getInstance('request');

        \Component\Member\MemberValidation::checkJoinToken($request->post()->all());
        \Component\Member\MemberValidation::checkJoinAgreement($request->post()->get('agreementInfoFl'), $request->post()->get('privateApprovalFl'));

		parent::index();

	    // 카카오톡 회원가입 추가 입력 설정 정보 가져오기
        $session = \App::getInstance('session');
        $kakaoLoginPolicy = gd_policy('member.kakaoLogin');

        if($session->has(\Component\Godo\GodoKakaoServerApi::SESSION_USER_PROFILE) && $kakaoLoginPolicy['useFl'] === 'y') {
            $kakaoProfile = $session->get(\Component\Godo\GodoKakaoServerApi::SESSION_USER_PROFILE);
            //gd_debug($kakaoProfile);

            $kakaoProfile['nickname'] = $kakaoProfile['properties']['nickname'];
            $kakaoProfile['email'] = $kakaoProfile['kakao_account']['email'];
            $kakaoProfile['gender'] = $kakaoProfile['kakao_account']['gender'];
            $kakaoProfile['id'] = $kakaoProfile['id'];
            $kakaoProfile['birthday'] = $kakaoProfile['birthyear'];

            //@formatter:off
            ArrayUtils::unsetDiff($kakaoProfile, ['id','nickname', 'email','birthday','birthyear']);
            //@formatter:on
            $scripts[] = 'gd_kakao.js';
            if($kakaoLoginPolicy['useFl'] === 'y' || empty($this->getData('kakaoProfile'))) {
                $this->setData('kakaoProfile', json_encode($kakaoProfile));
            }

            $data = $this->getData('data');
            $data['memId'] = $kakaoProfile['id'].'@kakao';
            $this->setData('data',$data);

        }

	}
}
