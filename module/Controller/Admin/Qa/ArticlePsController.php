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
namespace Controller\Admin\Qa;

use Component\Board\Board;
use Component\Board\BoardView;
use Component\Board\ArticleWriteAdmin;
use Component\Board\ArticleActAdmin;
use Component\Board\BoardReport;
use Framework\Debug\Exception\AlertBackException;
use Component\Memo\MemoActAdmin;
use Framework\Debug\Exception\LayerException;
use Message;
use Request;
use Framework\Debug\Exception\Framework\Debug\Exception;
use Framework\Utility\StringUtils;

class ArticlePsController extends \Bundle\Controller\Admin\Board\ArticlePsController
{

    /**
     * Description
     *
     * @throws Except
     */
    public function index()
    {
       parent::index();
    }
}
