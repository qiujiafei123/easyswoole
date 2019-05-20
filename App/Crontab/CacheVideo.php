<?php
/**
 * Created by PhpStorm.
 * User: gaffey
 * Date: 19-5-20
 * Time: 下午3:24
 */
namespace App\Crontab;

use App\Common\Help;
use App\Models\VideoModel;
use App\Utility\Pool\RedisObject;
use App\Utility\Pool\RedisPool;
use EasySwoole\EasySwoole\Crontab\AbstractCronTask;

class CacheVideo extends AbstractCronTask
{
    public static function getRule(): string
    {
        return '*/1 * * * *';
    }

    public static function getTaskName(): string
    {
        return 'cache_video';
    }

    public static function run(\swoole_server $server, int $taskId, int $fromWorkerId, $flags = null)
    {
        self::cacheVideo();
    }

    public static function cacheVideo()
    {
        $cacheType = Help::getConfig('Cache.device');
        try {
            $model = new VideoModel();
            foreach ([0, 1, 2, 3] as $id) {
                $cacheVideo = $model->cacheVideo(['cat_id' => $id]);
                if (empty($cacheVideo)) {
                    continue;
                }
                if (! empty($cacheVideo['lists'])) {
                    foreach ($cacheVideo['lists'] as &$list) {
                        $list['create_time'] = date("Y-m-d H:i:s", $list['create_time']);
                        $list['video_duration'] = gmstrftime("%H:%M:%S", $list['video_duration']);
                    }
                }
                switch ($cacheType) {
                    case 'file':
                        file_put_contents(self::getVideoCatIdFile($id), json_encode($cacheVideo));
                        break;
                    case 'table':
                        //$res = Cache::getInstance()->set($this->getCatKey($catId), $data);
                        break;
                    case 'redis':
                        RedisPool::invoke(function (RedisObject $redis) use ($cacheVideo, $id) {
                            $redis->set(self::getCacheKey($id), json_encode($cacheVideo));
                        });
                        break;
                    default:
                        throw new \Exception("请求不合法");
                        break;
                }
            }

        } catch (\Exception $e) {
            var_dump($e);
        }
    }

    public static function getVideoCatIdFile($catId = 0) {
        return EASYSWOOLE_ROOT."/webroot/video/json/".$catId.".json";
    }

    public static function getCacheKey($catId = 0) {
        return "index_video_data_cat_id_".$catId;
    }
}