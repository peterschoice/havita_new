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
namespace Controller\Front;



/**
 * 사이트 접속 페이지
 *
 * @author Jong-tae Ahn <qnibus@godo.co.kr>
 */
class IndexController extends \Bundle\Controller\Front\IndexController
{
    /**
     * {@inheritdoc}
     */
    public function index()
    {
        // main/index 파일을 호출
        // naver 정책에 의해 index 파일 무조건 해당 위치로
        parent::index();


        $db = \App::load(\DB::class);

        // 최근 매거진 게시글 3개 불러오기
        $arrBind = null;
        $query = "SELECT *  FROM es_bd_magazine order by sno desc limit 3";
        $bdList = $db->query_fetch($query,$arrBind,false);
        foreach($bdList as $key=>$val){
            $bdList[$key]['subSubject'] = mb_substr(strip_tags($bdList[$key]['contents']),0,100,'utf-8');
        }
        $this->setData('magazineList',$bdList);
        unset($bdList);

        /** @var  \Component\Goods\Goods $goods */
        $goods = \App::load('\\Component\\Goods\\Goods');

        // 최근 리뷰게시글 5개 불러오기
        $arrBind = null;
        $query = "SELECT *  FROM es_bd_goodsreview Where goodsNo > 0 order by sno desc limit 5";
        $bdList = $db->query_fetch($query,$arrBind,false);
        foreach($bdList as $key=>$val){
            $contents = $bdList[$key]['contents'];

            preg_match("/<img[^>]*src=[\"']?([^>\"']+)[\"']?[^>]*>/i",  $contents, $match);
            $imgSrc = str_replace('\"', '', $match[0]);

            if ($imgSrc) {
                $bdList[$key]['viewListImage'] = $imgSrc;
            }

            if($bdList[$key]['goodsNo']){

                $goodsView = $goods -> getGoodsView($bdList[$key]['goodsNo']);
                //$bdList[$key]['goodsView'] = $goodsView;
                //gd_debug($goodsView);
                $bdList[$key]['goodsNo'] = $goodsView['goodsNo'];
                $bdList[$key]['goodsImage'] = $goodsView['image']['detail']['img'][0];
                $bdList[$key]['brandNm'] = $goodsView['brandNm'];
                $bdList[$key]['fixedPrice'] = $goodsView['fixedPrice'];
                $bdList[$key]['goodsPrice'] = $goodsView['goodsPrice'];
                $bdList[$key]['goodsNm'] = $goodsView['goodsNm'];
                                unset($goodsView);
            }

            $bdList[$key]['regDt'] = explode(' ', $bdList[$key]['regDt'])[0];
            $bdList[$key]['subSubject'] = mb_substr(strip_tags($bdList[$key]['contents']),0,100,'UTF-8');
            unset($bdList[$key]['contents']);
        }

        $this->setData('reviewList',$bdList);

        unset($bdList);
    }
}
