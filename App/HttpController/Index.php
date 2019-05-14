<?php
/**
 * Created by PhpStorm.
 * User: gaffey
 * Date: 19-5-14
 * Time: 下午6:03
 */
namespace App\HttpController;

class Index extends BaseController
{
    function index()
    {
        $this->response()->write('这是我的第一个控制器');
    }
}