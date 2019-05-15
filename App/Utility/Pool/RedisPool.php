<?php
/**
 * Created by PhpStorm.
 * User: Gaffey
 * Date: 2019/5/14 9:59 PM
 * Email: 253896514@qq.com
 * Github: https://github.com/qiujiafei123
 */
namespace App\Utility\Pool;
use EasySwoole\Component\Pool\AbstractPool;
use EasySwoole\EasySwoole\Config;

class RedisPool extends AbstractPool
{
    protected function createObject()
    {
        // TODO: Implement createObject() method.
        $redis = new RedisObject();
        $conf = Config::getInstance()->getConf('REDIS');
        if( $redis->connect($conf['host'],$conf['port'])){
            if(!empty($conf['auth'])){
                $redis->auth($conf['auth']);
            }
            return $redis;
        }else{
            return null;
        }
    }
}
