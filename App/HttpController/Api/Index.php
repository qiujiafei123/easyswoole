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
use App\Utility\Pool\MysqlPool;
use EasySwoole\Component\Pool\PoolManager;
use EasySwoole\Mysqli\Mysqli;

class Index extends BaseController
{
    public function video()
    {
        $db = PoolManager::getInstance()->getPool(MysqlPool::class)->getObj();

        $data = $db->get('test');//获取一个表的数据
        PoolManager::getInstance()->getPool(MysqlPool::class)->recycleObj($db);

//        $data = [
//            'id' => 1,
//            'param' => $this->request()->getRequestParam()
//        ];
        return $this->successJson($data);
    }
}