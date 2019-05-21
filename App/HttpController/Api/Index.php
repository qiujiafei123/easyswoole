<?php
/**
 * Created by PhpStorm.
 * User: Gaffey
 * Date: 2019/5/14 8:47 PM
 * Email: 253896514@qq.com
 * Github: https://github.com/qiujiafei123
 */
namespace App\HttpController\Api;

use App\Crontab\CacheVideo;
use App\HttpController\BaseController;
use App\Models\VideoModel;
use App\Utility\Pool\MysqlObject;
use App\Utility\Pool\MysqlPool;
use App\Utility\Pool\RedisObject;
use App\Utility\Pool\RedisPool;
use EasySwoole\Component\Pool\PoolManager;
use EasySwoole\Mysqli\Mysqli;
use EasySwoole\Validate\Validate;
use function FastRoute\TestFixtures\empty_options_cached;

class Index extends BaseController
{
    public function index()
    {
        $condition['cat_id'] = isset($this->param['cat_id']) ? $this->param['cat_id'] : [];
        if ($this->param['page'] == 1) {
            $this->list();
        }
        try {
            $data = (new VideoModel())->getPaginationData($this->param['page'], $condition);
        } catch (\Exception $e) {
            return $this->errorJson('首页数据查询失败');
        }

        if (! empty($data['lists'])) {
            foreach ($data['lists'] as &$list) {
                $list['create_time'] = date("Y-m-d H:i:s", $list['create_time']);
                $list['video_duration'] = gmstrftime("%H:%M:%S", $list['video_duration']);
            }
        }

        return $this->successJson($data);
    }

    public function list()
    {
        $catId = isset($this->param['cat_id']) ? $this->param['cat_id'] : 0;
        $key = CacheVideo::getCacheKey($catId);
        try {
            RedisPool::invoke(function (RedisObject $redis) use ($key) {
                $data = $redis->get($key);
                $data = json_decode($data, true);
                return $this->successJson($data);
            });
        } catch (\Exception $e) {
            return $this->errorJson('首页数据查询失败');
        }
    }

    public function mysql()
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