<?php
/**
 * Created by PhpStorm.
 * User: gaffey
 * Date: 19-5-16
 * Time: 下午9:01
 */
namespace App\HttpController\Api;

use App\HttpController\BaseController;

class Category extends BaseController
{
    public function index()
    {
        return $this->successJson([
            '音乐', '科技', '电影'
        ]);
    }
}