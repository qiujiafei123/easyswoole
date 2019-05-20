<?php
/**
 * Created by PhpStorm.
 * User: yf
 * Date: 2018/5/28
 * Time: 下午6:33
 */

namespace EasySwoole\EasySwoole;


use App\Crontab\CacheVideo;
use App\Crontab\TestTwo;
use App\Utility\Pool\MysqlPool;
use App\Utility\Pool\RedisPool;
use EasySwoole\Component\Pool\PoolManager;
use EasySwoole\Component\Timer;
use EasySwoole\EasySwoole\Crontab\Crontab;
use EasySwoole\EasySwoole\Swoole\EventRegister;
use EasySwoole\EasySwoole\AbstractInterface\Event;
use EasySwoole\Http\Request;
use EasySwoole\Http\Response;

class EasySwooleEvent implements Event
{

    public static function initialize()
    {
        // TODO: Implement initialize() method.
        date_default_timezone_set('Asia/Shanghai');
        PoolManager::getInstance()->register(MysqlPool::class,Config::getInstance()->getConf('MYSQL.POOL_MAX_NUM'));
        PoolManager::getInstance()->register(RedisPool::class,Config::getInstance()->getConf('REDIS.POOL_MAX_NUM'));
    }

    public static function mainServerCreate(EventRegister $register)
    {
        // TODO: Implement mainServerCreate() method.
        /**
         * **************** Crontab任务计划 **********************
         */
        // 开始一个定时任务计划
        //Crontab::getInstance()->addTask(CacheVideo::class);
        //定时器
        $register->add(EventRegister::onWorkerStart, function (\swoole_server $server, $workerId){
            //这里的定时器会为每个worker打印一遍1
            //所以这个任务交给一个定时器做就可以了
            if ($workerId == 0) {
                Timer::getInstance()->loop(1000*2, function () {
                    CacheVideo::cacheVideo();
                });
            }
        });
    }

    public static function onRequest(Request $request, Response $response): bool
    {
        // TODO: Implement onRequest() method.
        return true;
    }

    public static function afterRequest(Request $request, Response $response): void
    {
        // TODO: Implement afterAction() method.
    }
}