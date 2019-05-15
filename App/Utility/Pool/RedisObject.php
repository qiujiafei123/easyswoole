<?php
/**
 * Created by PhpStorm.
 * User: Gaffey
 * Date: 2019/5/14 10:00 PM
 * Email: 253896514@qq.com
 * Github: https://github.com/qiujiafei123
 */
namespace App\Utility\Pool;
use Co\Redis;
use EasySwoole\Component\Pool\PoolObjectInterface;
class RedisObject extends Redis implements PoolObjectInterface
{
    function gc()
    {
        // TODO: Implement gc() method.
        $this->close();
    }
    function objectRestore()
    {
        // TODO: Implement objectRestore() method.
    }
    function beforeUse(): bool
    {
        // TODO: Implement beforeUse() method.
        return true;
    }
}