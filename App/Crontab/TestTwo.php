<?php
/**
 * Created by PhpStorm.
 * User: gaffey
 * Date: 19-5-20
 * Time: 下午3:24
 */
namespace App\Crontab;

use EasySwoole\EasySwoole\Crontab\AbstractCronTask;

class TestTwo extends AbstractCronTask
{
    public static function getRule(): string
    {
        return '*/1 * * * *';
    }

    public static function getTaskName(): string
    {
        return 'test_two';
    }

    public static function run(\swoole_server $server, int $taskId, int $fromWorkerId, $flags = null)
    {
        var_dump('test_two_task');
    }
}