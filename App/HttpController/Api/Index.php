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
use App\Utility\Pool\MysqlObject;
use App\Utility\Pool\MysqlPool;
use App\Utility\Pool\RedisObject;
use App\Utility\Pool\RedisPool;
use EasySwoole\Component\Pool\PoolManager;
use EasySwoole\Mysqli\Mysqli;

class Index extends BaseController
{
    public function index()
    {
        //两种调用方式，第一种主动回收，第二种自动回收
//        $db = PoolManager::getInstance()->getPool(MysqlPool::class)->getObj();
//
//        $data = $db->get('test');//获取一个表的数据
//        PoolManager::getInstance()->getPool(MysqlPool::class)->recycleObj($db);
        //自动回收连接池方式，比较推荐
        MysqlPool::invoke(function (MysqlObject $mysql) {
            $data = $mysql->get('test');
            return $this->successJson($data);
        });

    }

    public function redis()
    {
        RedisPool::invoke(function (RedisObject $redis){
            $redis->set('key','仙士可');
            $data = $redis->get('key');
            $this->successJson($data);
        });
    }
}