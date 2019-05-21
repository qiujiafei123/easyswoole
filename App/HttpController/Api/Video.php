<?php
/**
 * Created by PhpStorm.
 * User: gaffey
 * Date: 19-5-16
 * Time: 下午9:01
 */
namespace App\HttpController\Api;

use App\Common\Help;
use App\HttpController\BaseController;
use App\Models\VideoModel;
use App\Utility\Pool\RedisObject;
use App\Utility\Pool\RedisPool;
use EasySwoole\EasySwoole\Swoole\Task\TaskManager;
use EasySwoole\Validate\Validate;

class Video extends BaseController
{
    /**
     * 上传视频
     * @return bool
     */
    public function post()
    {
        $post = $this->request()->getRequestParam();

        $validata = new Validate();
        $validata->addColumn('name')->required('视频名称必填');
        $validata->addColumn('image')->required('视频封面必填');
        $validata->addColumn('cat_id')->required('视频分类必填');
        $validata->addColumn('url')->required('视频分类必填');
        $validata->addColumn('cat_id')->required('视频分类必填');

        if (!$this->validate($validata)) {
            return $this->errorJson($validata->getError()->__toString(), 'fail');
        }
        try {
            $baseData = ['create_time' => time(), 'update_time' => time()];
            $post['cat_id'] = intval($post['cat_id']);
            $data = array_unique(array_merge($post, $baseData));
            $flag = (new VideoModel())->insertData($data);

        } catch (\Exception $e) {
            return $this->errorJson($e->getMessage());
        }


        if ($flag === false) {
            return $this->errorJson("记录失败");
        }

        return $this->successJson(['id' => $flag]);
    }

    /**
     * 获取单条视频记录
     * @return bool
     */
    public function getOne()
    {
        $post = $this->request()->getRequestParam();

        $validata = new Validate();
        $validata->addColumn('id')->required('id必填');
        if (!$this->validate($validata)) {
            return $this->errorJson($validata->getError()->__toString(), 'fail');
        }

        try {

            $data = (new VideoModel())->getOne(['id' => $post['id']]);

        } catch (\Exception $e) {
            return $this->errorJson($e->getMessage());
        }

        if ($data === null) {
            return $this->errorJson("查询失败");
        }
        //格式化时间为可读格式
        foreach ($data as $key => &$val) {
            if ($key == 'create_time') {
                $val = date("Y-m-d H:i:s", $val);
            }

            if ($key == 'video_duration') {
                $val = gmstrftime("%H:%M:%S", $val);
            }
        }

        //异步任务记录分数
        TaskManager::async(function () use ($post) {
            RedisPool::invoke(function (RedisObject $redis) use ($post) {
                $redis->zIncrBy(Help::getConfig('RedisKey.play'),1, $post['id']);
            });
        });

        return $this->successJson($data);
    }

    /**
     * 视频点赞逻辑
     * @return bool
     */
    public function dianzan()
    {
        $post = $this->request()->getRequestParam();

        $validata = new Validate();
        $validata->addColumn('id')->required('id必填');
        if (!$this->validate($validata)) {
            return $this->errorJson($validata->getError()->__toString(), 'fail');
        }

        try {
            $data = (new VideoModel())->getOne(['id' => $post['id']]);
            if ($data === null) {
                return $this->errorJson('视频不存在');
            }
            TaskManager::async(function () use ($post) {
                RedisPool::invoke(function (RedisObject $redis) use ($post) {
                    $redis->zIncrBy(Help::getConfig('RedisKey.zan'),1, $post['id']);
                });
            });
        } catch (\Exception $e) {
            var_dump($e);
        }
        return $this->successJson();
    }

    /**
     * 获取redis播放量排行榜
     * @throws \EasySwoole\Component\Pool\Exception\PoolEmpty
     * @throws \EasySwoole\Component\Pool\Exception\PoolException
     * @throws \Throwable
     */
    public function rank()
    {
        $post = $this->request()->getRequestParam();
        $validata = new Validate();
        $validata->addColumn('type')->required('type必填');
        if (!$this->validate($validata)) {
            return $this->errorJson($validata->getError()->__toString(), 'fail');
        }

        RedisPool::invoke(function (RedisObject $redis) use ($post) {
            $res = [];
            $key = Help::getConfig('RedisKey.'.$post['type']);
            $data = $redis->zRevRange($key,0, -1);
            if (!empty ($data)) {
                foreach ($data as $k => $val) {
                    $res[$k]['video_id'] = intval($val);
                    $res[$k]['num'] = (int)$redis->zScore($key, $val);
                }
            }
            return $this->successJson($res);
        });
    }
}