<?php
/**
 * Created by PhpStorm.
 * User: Gaffey
 * Date: 2019/5/14 8:47 PM
 * Email: 253896514@qq.com
 * Github: https://github.com/qiujiafei123
 */
namespace App\HttpController\Api;

use App\HttpController\BaseController;

class Index extends BaseController
{
    public function video()
    {
        new abc();
        $data = [
            'id' => 1,
            'param' => $this->request()->getRequestParam()
        ];
        return $this->successJson($data);
    }
}